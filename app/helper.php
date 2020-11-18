<?php

function getJSON($string) {
    $j = json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE) ? $j : array('error' => true, 'message' => 'Invalid JSON Response');
}

function _asset($url){
    return get('asset_url').$url;
}

function theme($url){
    return asset("themes/".get('theme')."/$url");
}

function get($key){
    return config("ekart.$key");
}

function api_param($key){
    return get("api-params.$key");
}

function isLoggedIn(){
    return (session()->has('user') && isset(session()->get('user')['user_id']) && intval(session()->get('user')['user_id'])) ? true : false;
}

function getColor($index){
    $arr = ['#ffd7d7', '#FFF68D', '#bcffb8', '#c9fff3', '#ddffeb', '#dee4ff', '#fff0c5'];
    if(isset($arr[$index])){
        return $arr[$index];
    }else{
        return getColor(intval($index - count($arr)));
    }
}

function getMaxQty($v){

    $return = 50;

    if(intval($v->stock)){

        $return = $v->stock;

        $MaxAllowed = intval(Cache::get('max_cart_items_count', 0));

        if(intval($MaxAllowed) && $MaxAllowed < intval($v->stock)){

            $return = $MaxAllowed;

        }

    }

    return $return + 1;

}

function calc_discount_percentage($oldprice, $newprice){

    return intval(100 - intval( round( ( ( doubleval($newprice) / doubleval($oldprice) ) * 100 ), 0, PHP_ROUND_HALF_UP )));

}

function get_varient_name($v){

    $name = $v->measurement;
    if(isset($v->measurement_unit_name) && $v->measurement_unit_name != ""){

        $name .= " ".$v->measurement_unit_name;

    }elseif(isset($v->unit) && $v->unit != ""){

        $name .= " ".$v->unit;
    }

    return $name ?? '-';

}

function get_price($p, $isFree = true){

    if(floatval($p) > 0){

        return (Cache::has('currency') ? Cache::get('currency') : '')." ".$p;

    }elseif($isFree){

        return "Free";

    }else{

        return (Cache::has('currency') ? Cache::get('currency') : '')." 0";

    }

}

function get_savings_varients($v, $inPerecentage = true){

    if(isset($v->discounted_price) && intval($v->discounted_price) > 0){

        $result = $v->discounted_price * 100 / $v->price;

        $percentage = "";

        if(intval($result)){

            $percentage = intval(100 - intval($result))." % Off";

        }

        if($inPerecentage){

            return $percentage;

        }else{

            return intval(intval($v->price) - intval($v->discounted_price))." ($percentage)";

        }

    }

    return "";

}

function get_mrp_varients($v){
	
    if(floatval($v->discounted_price) > 0){

        return number_format($v->price, 2);

    }else{

        return "";

    }

}

function get_price_varients($v, $decimal = 2){

    $price = 0;

    if(floatval($v->discounted_price) > 0){

        $price = $v->discounted_price;

    }else{

        $price = $v->price;

    }

    if(intval($decimal)){

        return number_format($price, intval($decimal));

    }else{

        return $price;

    }

}

function print_discount_percentage($v){

    $return = "";

    if($v->discounted_price > 0){

        $discount = calc_discount_percentage($v->price, $v->discounted_price);

        if($discount > 0){

            $return = $discount." % OFF";

        }elseif($discount == 100){

            $return = "Free";

        }

    }
	
    return $return;

}

function print_mrp($v){

    if(isset($v->variants)){

        $v = $v->variants[0];
    }

    if($v->discounted_price > 0 && $v->discounted_price != $v->price){

        return "<s><!--M.R.P.:--> ".get_price($v->price)."</s>";

    }

    return "";

}

function print_price($v){

    if(isset($v->variants)){

        $v = $v->variants[0];
    }

    if($v->discounted_price > 0){

        if($v->discounted_price != $v->price){

            return "<!--Offer Price--> ".get_price($v->discounted_price);

        }else{

            return "<!--Price--> ".get_price($v->discounted_price);

        }

    }else{

        return "<!--Price--> ".get_price($v->price);

    }

}

function print_saving($v){
	
    if(isset($v->variants)){

        $v = $v->variants[0];
    }


    if($v->discounted_price > 0){

        return "Offer Price ".get_price($v->discounted_price);

    }else{

        return get_price($v->price);

    }

}

function getSlug($title, $slugArray = [], $increment = 0){
	
    $slug = slugify($title);

    if(isset($slugArray[$slug])){

        if($increment > 0){

            if(isset($slugArray[$slug."-".$increment])){

                $slug = getSlug($slug, $slugArray, $increment+1);

            }

        }else{

            $slug  = getSlug($slug, $slugArray, 1);

        }

    }

    return $slug;

}

function slugify($text){

    // replace non letter || digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, '-');

    // remove duplicate -
    $text = preg_replace('~-+~', '-', $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;

}

function get_order_status($status){

    $orderStatus = array('received' => false, 'processed' => false, 'shipped' => false, 'delivered' => false);

    if(count($status)){

        foreach($status as $s){

            foreach($orderStatus as $k => $v){

                if($k == $s[0]){

                    $orderStatus[$k] = $s[1];

                }

            }

        }

    }

    return $orderStatus;
}

if(!function_exists('hmac_sha256')){

    function hmac_sha256($data, $key){

        return hash_hmac("sha256", $data,  $key);

    }

}

function res($success = false, $msg = "", $data = [], $response = 200){

    if(is_array($msg)){

        $msg = $msg[0];

    }elseif(is_string($msg)){

        if(trim($msg) == ""){

            if($success){

                $msg = "Success";

            }else{

                $msg = "Something Went Wrong";

            }

        }

    }

    return response()->json([

        'success' => $success,

        'message' => $msg,

        'data' => $data

    ], $response);

}

function msg($k){
    return trans("msg.$k");
}

function getTxnId(){
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return substr(str_shuffle($permitted_chars), 0, 16);
}