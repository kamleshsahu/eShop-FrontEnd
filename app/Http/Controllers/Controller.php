<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
use Firebase\JWT\JWT;
use Prophecy\Call\Call;

use function GuzzleHttp\json_decode;

class Controller extends BaseController{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $client;
    
    public function __construct(){
        $this->client = new \GuzzleHttp\Client(['base_uri' => config('ekart.api_url')]);
	}

    public function init($reload = false){
        $lmt = Cache::get('lmt', 0);
        if($reload || $lmt + config('ekart.reload_settings') < time()){
            $this->update_settings();
            $this->update_offers();
            $this->update_categories();
            $this->update_sliders();
        }else{
            if(!Cache::has('logo')){
                $this->update_settings();
            }
            if(!Cache::has('categories')){
                $this->update_categories();
            }
            if(!Cache::has('sliders')){
                $this->update_sliders();
            }
            if(!Cache::has('offers')){
                $this->update_offers();
            }
        }
    }

    function post($api, $params = array(), $die = false){

        $return = [];

        $data = $params['data'] ?? [];

        $token = $this->generate_token();

        $data[config('ekart.access_key')] = config('ekart.access_key_val');

        $response = $this->client->post(config("ekart.apis.$api"), [
            'headers' => ["Authorization" => "Bearer $token"],
            'form_params' => $data
        ]);

        if($response->getStatusCode() === 200){
            if($die){
                echo "Bearer $token<br><br>";
                echo "API URL : ".get("apis.$api")."<br><br>";
                echo "Params : <pre>";
                var_dump($data);
                echo "</pre><br><br>";
                echo "API Response : ".$response->getBody(); die();
            }
            $return = $this->response($response->getBody(), $params);
        }
        return $return;
    }

    function response($return = [], $params = []){
        $return = (array)getJSON($return);            
        $error =  true;
        if(isset($return['error'])){
            if(is_bool($return['error'])){
                if($return['error'] === false){
                    $error = false;
                }
            }elseif(is_string($return['error'])){
                if($return['error'] === "false"){
                    $error = false;
                }
            }
        }
        if(!$error){
            $data_param = 'data';
            if(isset($params['data_param'])){
                $data_param = $params['data_param'];
            }
            if(isset($return[$data_param])){
               $return = (array)$return[$data_param];
            }
        }
        return $return;
    }

    function generate_token(){
        return jwt::encode(config('ekart.jwt_payload'), config('ekart.jwt_secret_key'), config('ekart.jwt_alg'));
    }

    public function html($view, $data = array(), $reload = false){
        $this->init($reload);
        $theme = get('theme');
        if(isLoggedIn() && !isset($data['cart'])){
            $data['cart'] = $this->getCart();
        }
        echo view("themes.$theme.common.header", compact('view', 'data'));
        echo view("themes.$theme.$view", compact('theme', 'data'));
        if($view == "home"){
            $this->update_sections();
            if(Cache::has('sections') && is_array(Cache::get('sections')) && count(Cache::get('sections'))){
                foreach(Cache::get('sections') as $s){
                    echo view("themes.$theme.parts.".$s->style, compact('theme','s', 'view'));
                }
            }
            echo view("themes.$theme.parts.categories");
            echo view("themes.$theme.parts.offers");
        }
        echo view("themes.$theme.common.footer");
        return true;
    }
    
    public function update_settings(){
        $settings = $this->post('settings', ['data' => ['all' => true]]);
        foreach($settings as $k => $v){
            if($k == "payment_methods"){
                Cache::put('payment_methods', json_decode($v));
            }else{
                Cache::put($k, $v);
            }
        }
        Cache::put('lmt', time());
    }

    public function update_categories($categories = []){
        if(empty($categories)){
            $categories = $this->post('get-categories', []);
        }
        if(isset($categories['error']) && $categories['error']){
            die('Something Went Wrong');
        }
        if(count($categories)){
            $category = [];
            for($i = 0; $i < count($categories); $i++){
                $slug = getSlug($categories[$i]->name, $category);
                $categories[$i]->slug = $slug;
                $category[$slug] = $categories[$i];
            }
            Cache::put('categories', $category);
            return true;
        }else{
            return false;
        }
    }

    public function update_sliders(){
        $sliders = $this->post('slider-images', ['data' => [api_param('get-slider-images') => api_param('get-val')]]);
		if(count($sliders)){
            if(isset($sliders['error']) && $sliders['error']){
                return false;
            }else{
                Cache::put('sliders', $sliders);
            }
            return true;
        }else{
            return false;
        }
    }

    public function update_offers(){
        return Cache::put('offers', $this->post('offers', ['data' => [api_param('get-offer-images') => api_param('get-val')]]));
    }


    public function update_sections(){
        $data = [api_param('get-all-sections') => api_param('get-val')];
        if(isLoggedIn()){
            $data[api_param('user-id')] = session()->get('user')['user_id'];
        }
        $response = $this->post('sections', ['data' => $data, 'data_param' => 'sections']);        
		if(count($response)){
            if(isset($response['error']) && $response['error']){
                return false;
            }else{
                $sections = [];
                foreach($response as $r){
                    if(isset($r->title)){
                        $r->slug = getSlug($r->title, $sections);
                        $sections[$r->slug] = $r;
                    }
                }
                Cache::put('sections', $sections);
                return true;
            }
        }else{
            return false;
        }
    }

    public function getCart(){

        $cart = $this->post('cart', ['data' => ['get_user_cart' => 1, 'user_id' => session()->get('user')['user_id']]]);

        $data['subtotal'] = 0;

        $data['cart'] = [];

        if(is_array($cart) && count($cart) && !($cart['error'] ?? false)){

            foreach($cart as $c){

                $data['subtotal'] += get_price_varients($c->item[0]) * ($c->qty ?? 1);

            }

            $data['cart'] = $cart;

            $data['total'] = $data['subtotal'];

            $data['tax'] = (floatval(Cache::get('tax', 0)) > 0) ? number_format(Cache::get('tax'), 2) : 0;

            $data['tax_amount'] = (floatval(Cache::get('tax', 0)) > 0) ? floatval(floatval(Cache::get('tax')) * $data['subtotal'] / 100) : 0;

            $data['total'] += $data['tax_amount'];
        
            $data['shipping'] = (intval($data['subtotal']) < Cache::get('min_amount', 0) && Cache::has('delivery_charge')) ? floatval(Cache::get('delivery_charge')) : 0;

            $data['total'] += $data['shipping'];

            $coupon = session()->get('discount', []);

            $data['coupon'] = floatval($coupon['discount'] ?? 0);

            $data['total'] -= $data['coupon'];

        }

        $data['address'] = session()->get('checkout-address');

        return $data;

    }

    public function pagination($list, $routeName = "", $page = 0,  $limit = 10){

        $total = $list['total'] ?? 0;

        $lastPage = "";

        if(intval($page - 1) > -1){

            if(intval($page - 1) == 0){

                $lastPage = route($routeName);

            }else{

                $lastPage = route($routeName, ['page' => $page-1]);

            }

        }

        $nextPage = "";

        if(intval($total / $limit) > $page){

            $nextPage = route($routeName, ['page' => $page+1]);

        }

        return ['list' => $list, 'limit' => $limit, 'total' => $total, 'page' => $page, 'lastPage' => $lastPage, 'nextPage' => $nextPage];

    }

}
