<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Kreait\Firebase\Factory;

class HomeController extends Controller{

    public function index(){

        $this->html('home', [], true);
        
    }

    public function page($slug){

        $page = array('privacy-policy' => 'privacy_policy', 'tnc' => 'terms_conditions', 'about' => 'about_us');

        $title = array('privacy-policy' => 'Privacy Policy', 'tnc' => 'Terms & Conditions', 'about' => 'About Us');

        if(isset($page[$slug]) && Cache::has($page[$slug])){

            $this->html('page', ['title'=> $title[$slug], 'content' => Cache::get($page[$slug])]);

        }else{

            abort(404);

        }
    }

    public function faq(Request $request){

        $page = 0;

        if($request->has('page') && intval($request->page)){

            $page = intval($request->page);

        }

        $limit = 5;

        $result = $this->post('faq', ['data' => [api_param('search') => $request->search ?? '', api_param('limit') => $limit, api_param('page') => $page+1], 'data_param' => '']);

        if(isset($result['error']) && $result['error'] === false){

            $breadcrumb = array();

            $breadcrumb[] = array('title' => 'Home', 'link' => route('home'));
    
            $breadcrumb[] = array('title' => 'Faq', 'link' => '#');
    
            $this->html('faq', ['breadcrumb'=> $breadcrumb, 'faq' => $result['data'], 'page' => $page, 'limit' => $limit, 'total' => $result['total']]);

        }else{
            
            abort(404);

        }

    }

    public function product($slug){
        $product = $this->post('get-product', ['data' => ['slug' => $slug]]);
        if(count($product) && !(isset($product['error']) && $product['error']) && isset($product[0])){
            $product = $product[0];
            $similarProducts = array();
            $data = [api_param('limit') => get('similar-product-load-limit'), api_param('offset') => 0, api_param('sub-category-id') => $product->subcategory_id];
            if(isLoggedIn()){
                $data[api_param('user-id')] = session()->get('user')['user_id'];
            }
            $result = $this->post('get-products-by-subcategory', ['data' => $data]);
            if($result){
                foreach($result as $r){
                    if($r->slug != $slug){
                        $similarProducts[] = $r;
                    }
                }
            }
            $this->html('product', ['product' => $product, 'similarProducts' => $similarProducts]);
        }else{
            abort(404);
        }

    }

    public function get_shop_params_category(Request $request){

        $selectedCategoryIds = [];

        $selectedSubCategoryIds = [];

        $selectedSubCategory = explode(",", $request->get('sub-category', ""));

        $selectedCategory = explode(",", $request->get('category', ""));

        $categories = Cache::get('categories');

        foreach($selectedCategory as $cat){

            if(isset($categories[$cat]->childs)){

                $selectedCategoryIds[intval($categories[$cat]->id ?? 0)] = intval($categories[$cat]->id ?? 0);

                $childs = (array)$categories[$cat]->childs;

                foreach($selectedSubCategory as $sub){

                    if(isset($childs[$sub])){

                        unset($selectedCategoryIds[intval($categories[$cat]->id ?? 0)]);
                    
                        $selectedSubCategoryIds[] = intval($childs[$sub]->id ?? 0);
        
                    }
        
                }

            }

        }

        return ['selectedCategory' => $selectedCategory, 'selectedCategoryIds' => $selectedCategoryIds, 'selectedSubCategory' => $selectedSubCategory, 'selectedSubCategoryIds' => $selectedSubCategoryIds];

    }

    public function get_shop_params(Request $request){
    
        $limit = 8;

        $page = $request->page ?? 0;

        $data = ['limit' => $limit, 'offset' => ($page * $limit)];
        
        $param = [];

        if(isLoggedIn()){

            $data[api_param('user-id')] = session()->get('user')['user_id'];

        }


        $data['s'] = trim($request->s ?? "");

        $param['s'] = trim($request->s ?? "");

        if($request->has('section') && trim($request->section) != ""){

            $sections = Cache::get('sections');

            if(isset($sections[$request->section])){

                $data['section'] = intval($sections[$request->section]->id ?? 0);

                $param['section'] = trim($request->section);
    
            }

        }

        extract($this->get_shop_params_category($request));

        $param['sub-category'] = trim($request->{'sub-category'});

        $param['category'] = trim($request->category);

        $data['category'] = implode(",", $selectedCategoryIds);

        $data['sub-category'] = implode(",", $selectedSubCategoryIds);

        $data[api_param('sort')] = trim($request->sort ?? "");

        $param[api_param('sort')] = trim($request->sort ?? "");

        $data['min_price'] = $request->min_price ?? 0;

        $data['max_price'] = $request->max_price ?? 0;

        return ['data' => $data, 'param' => $param, 'page' => $page, 'limit' => $limit, 'selectedCategory' => $selectedCategory, 'selectedSubCategory' => $selectedSubCategory];

    }

    public function shop(Request $request){

        extract($this->get_shop_params($request));

        $list = $this->post('shop', ['data' => $data, 'data_param' => '']);

        $total = $list['total'] ?? 0;

        $min_price = $list['min_price'] ?? 0;

        $max_price = $list['max_price'] ?? 0;
        
        $selectedMaxPrice = ($request->max_price ?? 0) < $max_price ? $max_price : ($request->max_price ?? 0);

        $selectedMinPrice = ($request->min_price ?? 0) < $min_price ? $min_price : ($request->min_price ?? 0);

        if(isset($list['category']) && is_array($list['category']) && count($list['category'])){
            
            $this->update_categories($list['category']);

        }

        $lastPage = "";

        if(intval($page - 1) > -1){

            if(intval($page - 1) == 0){

                $lastPage = route('shop', $param);

            }else{

                $param['page'] = $page - 1;

                $lastPage = route('shop', $param);

            }

        }

        $nextPage = "";

        if(intval($total / $limit) > $page){

            $param['page'] = $page + 1;

            $nextPage = route('shop', $param);

        }

        $categories = Cache::get('categories', []);

        $this->html('shop', ['list' => $list, 'limit' => $limit, 'total' => $total, 'min_price' => $min_price, 'max_price' => $max_price, 'selectedMinPrice' => $selectedMinPrice, 'selectedMaxPrice' => $selectedMaxPrice, 'next' => $nextPage, 'last' => $lastPage, 'categories' => $categories, 'selectedCategory' => $selectedCategory, 'selectedSubCategory' => $selectedSubCategory]);

    }

    public function category(Request $request, $slug){
        $breadcrumb = array();
        $breadcrumb[] = array('title' => 'Home', 'link' => route('home'));
        $breadcrumb[] = array('title' => 'Shop', 'link' => route('shop'));
        if(Cache::has('categories') && is_array(Cache::get('categories')) && isset(Cache::get('categories')[$slug])){
            $category = Cache::get('categories')[$slug];
            $subCategory = $this->post('get-sub-categories', ['data' => [api_param('category-id') => $category->id]]);
            if(isset($subCategory['error']) && $subCategory['error']){
                return redirect()->route('shop')->with('err', 'No Sub Category Found.');
            }
            $s = Cache::get('sub-categories') ?? [];
            foreach($subCategory as $t){
                $s[$t->slug] = $t;
            }
            Cache::put('sub-categories', $s);
            $breadcrumb[] = array('title' => $category->name, 'link' => route('category', $category->slug));
            
            $this->html('sub-categories', ['breadcrumb' => $breadcrumb, 'category' => $category,'sub-categories' => $subCategory]);
        }else{
            return redirect()->route('shop');
        }
    }

    public function sub_category(Request $request, $categorySlug = "", $subCategorySlug = "", $offset = 0){

        $subTitle = '';

        $products = [];

        $total = 0;

        $breadcrumb = array();

        $breadcrumb[] = array('title' => 'Home', 'link' => route('home'));

        $breadcrumb[] = array('title' => 'Shop', 'link' => route('shop'));

        if(Cache::has('categories') && is_array(Cache::get('categories')) && isset(Cache::get('categories')[$categorySlug])){

            $category = Cache::get('categories')[$categorySlug];

            $breadcrumb[] = array('title' => $category->name, 'link' => route('category', $category->slug));

            $more = false;
            
            if(Cache::has('sub-categories') && isset(Cache::get('sub-categories')[$subCategorySlug])){

                $subCategory = Cache::get('sub-categories')[$subCategorySlug];

                $title = $subCategory->name;

                $breadcrumb[] = array('title' => $title, 'link' => route('sub-category', [$category->slug, $subCategory->slug]));

                $response = $this->post('get-products-by-subcategory', ['data_param' => '', 'data' => [api_param('limit') => get('load-item-limit'), api_param('offset') => intval($offset * get('load-item-limit')), api_param('sub-category-id') => $subCategory->id]]);

                if(isset($response['data']) && is_array($response['data']) && count($response['data'])){

                    $products = $response['data'];
    
                    $total = $response['total'];
    
                    if($total > ($response['limit'] ?? get('load-item-limit'))){
        
                        $more = true;
        
                    }
    
                }

                $this->html('shop', ['title' => $title, 'subTitle' => $subTitle, 'products' => $products, 'more' => $more, 'breadcrumb' => $breadcrumb, 'total' => $total]);

            }else{

                return redirect()->route('category', $categorySlug);

            }

        }else{

            return redirect()->route('shop');

        }
    }

    public function login_page(){
        
        if(isLoggedIn()){

            return redirect()->route('my-account');

        }

        $this->html('login');
    }

    public function login(Request $request){

        $error = msg('error');

        $validator = Validator::make($request->all(), [

            'mobile' => 'required',

            'password' => 'required',

        ]);

		if ($validator->fails()) {

            $errors = $validator->messages()->all();

            $response['message'] = $errors[0];

		}else{
            
            $login = $this->post('login', ['data' => [api_param('mobile') => $request->mobile, api_param('password') => $request->password]]);

            if(isset($login['user_id']) && intval($login['user_id'])){

                $msg = $login['message'];

                unset($login['message']);

                unset($login['error']);

                $request->session()->put('user', $login);

                $lastUrl = $request->get('last_url', '');

                if(trim($lastUrl) != ''){

                    return redirect()->to($lastUrl);

                }else{

                    return redirect()->route('my-account')->with('suc', $msg);

                }

            }elseif(isset($login['message']) && trim($login['message']) != ""){

                $error = $login['message'];

            }

            return back()->with("err", $error);
        }

    }

    public function already_registered(Request $request){
        $response = ['error' => false, 'message' => 'Enter Valid Mobile Number'];
        if(strlen(trim($request->get('mobile', ""))) > 0){
            $response = $this->post('user-registration', ['data' => [api_param('mobile') => $request->mobile, api_param('type') => api_param('verify-user')]]);
            if($response['error']){
                session()->put('temp_user', $response);
            }
        }
        echo json_encode($response);
    }

    public function register(Request $request){
        $factory = (new Factory)->withServiceAccount(base_path('config/firebase.json'));
        $auth = $factory->createAuth();
        $user = $auth->getUser($request->auth_uid);
        if($user->uid == $request->auth_uid){
            if($request->has('action') && $request->action == 'save'){
                $response = array('error' => true, 'message' => 'Something Went Wrong');
                $validator = Validator::make($request->all(), [
                    'password' => 'required|min:5|confirmed',
                ]);
                if ($validator->fails()) {
                    $errors = $validator->messages()->all();
                    $response['message'] = $errors[0];
                }else{
                    $param = array();
                    $session = session()->get('registeration');
                    $mobile = substr($user->phoneNumber, strlen($session['country'] ?? $request->country));
                    $param[api_param('type')] = api_param('register'); 
                    $param[api_param('name')] = $request->display_name;
                    $param[api_param('friends-code')] = $request->friends_code ?? session()->get('friends_code', '');
                    $param[api_param('email')] = $request->email;
                    $param[api_param('mobile')] = $mobile ?? $session['mobile'] ?? $request->mobile;
                    $param[api_param('password')] = $request->password;
                    $param[api_param('pincode')] = $request->pincode;
                    $param[api_param('city-id')] = $request->city;
                    $param[api_param('area-id')] = $request->area;
                    $param[api_param('street')] = $request->address;
                    $param[api_param('latitude')] = 0;
                    $param[api_param('longitude')] = 0;
                    $param[api_param('country-code')] = $session['country'] ?? $request->country;
                    $registration = $this->post('user-registration', ['data' => $param]);
                    if($registration['error'] === false){
                        $response['error'] = false;
                        $response['message'] = $registration['message'];
                        unset($registration['friends_code']);
                        unset($registration['message']);
                        unset($registration['error']);
                        session()->put('user', $registration);
                    }else{
                        $response = $registration;
                    }
                }
                echo json_encode($response);
            }else{
                $data = array();
                $data['email'] = $user->email;
                $data['display_name'] = $user->displayName;
                $data['country'] = $request->country_code;
                $data['mobile'] = substr($request->mobile, strlen($request->country_code));
                $data['auth_uid'] = $request->auth_uid;
                $data['friends_code'] = $request->friends_code ?? session()->get('friends_code', '');

                session()->put('registeration', $data);
                $this->html('register', $data);
            }
        }else{
            return back()->with("err", 'Auth Token Not Matched');
        }
    }

    public function city(Request $request){
        echo json_encode($this->post('get-cities'));
    }

    public function area(Request $request, $cityId = 0){
        echo json_encode($this->post('get-areas', ['data' => [api_param('city-id') => $cityId]]));
    }

    public function refer($code){

        session()->put('friends_code', $code);

        $this->html('login', ['code' => $code]);

    }

    public function newsletter(Request $request){

        $rules = [
    
            'email' => 'required|email',

        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if($validator->fails()){
    
            $messages = $validator->messages();
    
            return res(false, $messages->all());
    
        }else{

            $result = $this->post('newsletter', ['data' => ['email' => $request->email]]);

            return res(!$result['error'], $result['message']);

        }

    }

}