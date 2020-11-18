<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Kreait\Firebase\Factory;

class CheckoutController extends CartController{

    public function index(){

        if(!isLoggedIn()){

            return redirect()->route('login');

        }else{

            $data = $this->getCart();

            if(isset($data['cart']) && is_array($data['cart']) && count($data['cart'])){

                if(!(Cache::has('min_order_amount') && intval($data['subtotal']) >= intval(Cache::get('min_order_amount')))){

                    return redirect()->route('cart');

                }else{

                    $this->html('checkout_summary', $data);

                }

            }else{

                return redirect()->route('shop')->with('err',  msg('no_product_checkount'));

            }

        }
        
    }

    public function init_checkout(){
        
        if(!isLoggedIn()){

            return redirect()->route('login');

        }else{

            $data = $this->getCart();

            if(!(Cache::has('min_order_amount') && intval($data['subtotal']) >= intval(Cache::get('min_order_amount')))){

                return redirect()->route('cart');

            }

            return $data;

        }

    }

    private function getProductId($cart){

        $tmp = array();

        if(isset($cart['cart']) && count($cart['cart'])){

            foreach($cart['cart'] as $item){

                $tmp[] = $item->product_variant_id;

            }

        }

        return json_encode($tmp);

    }

    private function getQty($cart){

        $tmp = array();

        if(isset($cart['cart']) && count($cart['cart'])){

            foreach($cart['cart'] as $item){

                $tmp[] = $item->qty;

            }

        }

        return json_encode($tmp);

    }

    private function getAddress(){

        //"$loggedInUser[street], $loggedInUser[area_name], $loggedInUser[city_name], $loggedInUser[pincode]"

        $address = session()->get('checkout-address');

        $a = [];

        $a[] = $address->name;

        $a[] = $address->address;

        $a[] = $address->landmark;

        $a[] = $address->area_name;

        $a[] = $address->city_name;

        $a[] = $address->state;

        $a[] = $address->country;

        $a[] = $address->pincode;

        $a[] = 'Deliver to '.$address->type;

        return \implode(", ", $a);

    }


    public function address(Request $request){

        $data = $this->init_checkout();
        
        $address = $this->post('addresses', ['data' => ['get_addresses' => 1, 'user_id' => session()->get('user')['user_id']]]);

        $data['address'] = [];

        if(!(isset($address['error']) && $address['error']) && count($address)){

            $data['address'] = $address;

        }

        $addressExist = false;

        if(intval($request->id ?? 0)){

            foreach($data['address'] as $a){

                if($a->id == $request->id){

                    $addressExist = $a;

                }

            }

            if(isset($addressExist->id)){

                $request->session()->put('checkout-address', $addressExist);

                return redirect()->route('checkout-payment');

            }else{

                return redirect()->route('checkout-address')->with('err', 'Selected Address Doen\'t Exists');

            }

        }

        if(isset($data['cart']) && is_array($data['cart']) && count($data['cart'])){

            $this->html('checkout_address', $data);

        }else{

            return redirect()->route('shop')->with('err',  msg('no_product_checkount'));

        }

    }

    public function payment(Request $request){

        $data = [];

        $data = $this->getCart();

        $return = false;

        if(!(Cache::has('min_order_amount') && intval($data['subtotal']) >= intval(Cache::get('min_order_amount')))){

            redirect()->route('cart');

        }

        if(isset($data['cart']) && is_array($data['cart']) && count($data['cart'])){

            if(isset($data['address']->id) && intval($data['address']->id)){

                $user = $this->post('get-user', ['data' => ['get_user_data' => 1, 'user_id' => session()->get('user')['user_id']]]);

                if(isset($user['error']) && $user['error']){

                    $return = redirect()->route('logout');

                }else{

                    $data['user'] = $user;

                    $return = $this->html('checkout_payment', $data);

                }

            }else{

                $return = redirect()->route('checkout-address')->with('err', 'Select Address For Delivery');

            }

        }else{

            $return = redirect()->route('shop')->with('err',  msg('no_product_checkount'));

        }

        return $return;

    }

    public function proceed(Request $request){

        $loggedInUser = session()->get('user');

        $data = [];

        $cart = $this->getCart();

        $msg = msg('no_product_checkount');

        $return = false;

        if(isset($cart['cart']) && is_array($cart['cart']) && count($cart['cart'])){

            $data[api_param('place-order')] = api_param('get-val');

            $data[api_param('user-id')] = $loggedInUser['user_id'];

            $data[api_param('tax-percentage')] = $cart['tax'] ?? '';

            $data[api_param('tax-amount')] = $cart['tax_amount'] ?? 0;

            $data[api_param('total')] = $cart['subtotal'];

            $data[api_param('final-total')] = $cart['total'];

            $data[api_param('product-variant-id')] = $this->getProductId($cart);

            $data[api_param('quantity')] = $this->getQty($cart);

            $data[api_param('mobile')] = $loggedInUser['mobile'];

            $data[api_param('delivery-charge')] = $cart['shipping'] ?? 0;

            $deliverDay = $request->deliverDay ?? '';

            $data[api_param('delivery-time')] = $deliverDay ." ". ($request->deliveryTime ?? '');

            $data[api_param('payment-method')] = $request->paymentMethod;

            $data[api_param('address')] = $this->getAddress();

            $data[api_param('latitude')] = 0;

            $data[api_param('longitude')] = 0;


            $coupon = session()->get('discount', []);

            if(is_array($coupon) && count($coupon) && floatval($coupon['discount']) > 0){

                $data[api_param('promo-code')] = $coupon['promo_code'];

                $data[api_param('promo-discount')] = $coupon['discount'];

            }

            $data[api_param('email')] = $loggedInUser['email'];

            $data[api_param('wallet-used')] = $request->wallet_used ?? false;

            $data[api_param('wallet-balance')] = $request->wallet_balance ?? 0;

            switch ($request->paymentMethod) {

                case 'cod':

                    $return = $this->checkout_cod($data);

                    break;

                case 'razorpay':
                    
                    $request->session()->put('tmp_razorpay', $data);

                    $return = redirect()->route('checkout-razorpay-init');

                    break;

                case 'payumoney':

                    $request->session()->put('tmp_payu', $data);

                    $return = redirect()->route('checkout-payu-init');

                    break;

                case 'payumoney-bolt':

                    $request->session()->put('tmp_payu', $data);

                    $return = redirect()->route('checkout-payu-init-bolt');
    
                    break;

                case 'paypal':

                    $request->session()->put('tmp_paypal', $data);

                    $return = redirect()->route('checkout-paypal-init');
    
                    break;

                default:

                    $msg = msg('error');
   
            }

        }

        return (!$return) ? redirect()->route('cart')->with('err',  $msg) : $return;

    }

}