<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Razorpay\Api\Api;

use function GuzzleHttp\json_encode;

class CartController extends Controller{
    
    public function index(){

        if(!isLoggedIn()){

            return redirect()->route('login');

        }else{

            $data = $this->getCart();

            $this->html('cart', $data);

        }

    }

    public function getLastUrl(){

        $lastUrlName = "";

        try {
        
            $lastUrl = app('request')->create(url()->previous(), 'GET');
            
            $lastUrlName = app('router')->goroutes()->match($lastUrl)->getName() ?? '';

        }catch(\Exception $e){
                
            $lastUrlName = "";

        }catch (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e) {

            $lastUrlName = "";
    
        }

        return $lastUrlName;

    }

    public function add(Request $request){

        if(!isLoggedIn()){

            $request->session()->put('tmp_cart', $request->all());

            return redirect()->route('login');

        }else{

            $lastUrlName = $this->getLastUrl();
            
            if($lastUrlName == "login" && $request->session()->has('tmp_cart')){

                $tmp  = $request->session()->get('tmp_cart');

                if(isset($tmp['id']) && intval($tmp['id']) && isset($tmp['varient']) && intval($tmp['varient'])){

                    $request->id = $tmp['id'];

                    $request->child_id = $tmp['varient'];
            
                }

            }

            return $this->addToCart($request);

        }

    }

    public function addToCart(Request $request, $lastUrlName = ""){

        $result = $this->post('cart', ['data' => ['add_to_cart' => 1, 'user_id' => session()->get('user')['user_id'], 'product_id' => $request->id, 'product_variant_id' => $request->child_id, 'qty' => $request->qty ?? 1]]);

        $return = false;

        if($result['error']){
            
            if($lastUrlName == 'login'){

                $return = redirect()->route('cart')->with('err', $result['message']);

            }else{

                $return = redirect()->back()->with('err', $result['message']);

            }

        }else{

            $request->session()->forget('tmp_cart');

            if($request->has('submit') && $request->submit == "buynow"){

                $return = redirect()->route('cart')->with('suc', $result['message']);

            }else{
                
                $return = redirect()->back()->with('suc', $result['message']);

            }

        }

        return $return;

    }

    public function add_single_varient(Request $request){

        return $this->add_single($request, $request->id, $request->varient);

    }

    public function add_single(Request $request, $id, $varient_id){

        if(!isLoggedIn()){

            $request->session()->put('last-url', url()->full());

        }

        $request->id = $id;

        $request->child_id = $varient_id;

        return $this->add($request);

    }

    public function update(Request $request, $id){

        if(!isLoggedIn()){

            return redirect()->route('login');

        }else{

            $result = $this->post('cart', ['data' => ['add_to_cart' => 1, 'user_id' => session()->get('user')['user_id'], 'product_id' => $request->id, 'product_variant_id' => $request->child_id, 'qty' => $request->qty]]);

            if($result['error']){

                return redirect()->back()->with('err', $result['message']);

            }else{

                return redirect()->back()->with('suc', $result['message']);

            }

        }

    }

    public function remove($id){

        if(!isLoggedIn()){

            return redirect()->route('login');

        }else{

            $data = ['remove_from_cart' => 1, 'user_id' => session()->get('user')['user_id']];

            if(intval($id)){

               $data['product_variant_id'] = $id;

            }

            $result = $this->post('cart', ['data' => $data]);

            if($result['error']){

                return redirect()->back()->with('err', $result['message']);

            }else{

                return redirect()->back()->with('suc', $result['message']);

            }

        }

    }

    public function calc(){

        $arr = session('cart');

        $total = 0;

        $shipping = 0;

        $discount = 0;

        $tax_amount = 0;

        if(is_array($arr) && count($arr)){

            var_dump($arr); die();

            foreach($arr as $a){

                if(isset($a['varient']->discounted_price) && isset($a['quantity']) && intval($a['quantity'])){

                    $total += $a['variant']->discounted_price * $a['quantity'];

                }

            }

        }

        if(Cache::has('delivery_charge')){
            
            $shipping = floatval(Cache::get('delivery_charge'));

        }

        if(Cache::get('tax') && floatval(Cache::get('tax')) > 0){

            $tax = number_format(Cache::get('tax'), 2);

            $tax_amount = floatval(floatval(Cache::get('tax')) * session()->get('sub_total') / 100);

        }

        if(session()->has('discount')){

            $coupon = session()->get('discount');

            if(is_array($coupon) && count($coupon) && floatval($coupon['discount']) > 0){

                $discount = $coupon['discount'];

            }

        }

        session(['sub_total' => $total, 'shipping' => $shipping, 'tax_percentage' => $tax, 'tax_amount' => $tax_amount, 'discount' => $discount, 'total' => floatval(floatval($total) - floatval($discount) + floatval($tax_amount) + floatval($shipping))]);

    }

    public function order_placed($data){

        $response = $this->post('order-process', [ 'data' => $data ]);

        if(isset($response['error']) && $response['error'] == "false"){

            $this->post('cart', ['data' => ['remove_from_cart' => 1, 'user_id' => session()->get('user')['user_id']]]);

            session()->put('discount', '');
            
            session()->put('checkout-address', '');

            return ['success' => true, 'message' => $response['message'] ?? msg('order_success'), 'data' => $response];
    
        }else{

            return ['success' => false, 'message' => $response['message'] ?? msg('order_error')];

        }

    }

    public function checkout_cod($data){

        $response = $this->order_placed($data);

        if($response['success']){

            return redirect()->route('my-orders')->with('suc', $response['message'] ?? msg('order_success'));

        }else{

            return redirect()->back()->with('err', $response['message'] ?? msg('order_error'));

        }

    }

    public function checkout_paypal_init(Request $request){

        $paymentMethods = Cache::get('payment_methods');

        $tmp = $request->session()->get('tmp_paypal');

        if(isset($paymentMethods->paypal_payment_method) && $paymentMethods->paypal_payment_method == 1){

            $payment_url = "https://www.paypal.com/cgi-bin/webscr";

            if($paymentMethods->paypal_mode == "sandbox"){

                $payment_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
                
            } ?>
            
            <form action="<?php echo $payment_url; ?>" method="post">
                <input type='hidden' name='business' value='<?php echo $paymentMethods->paypal_business_email; ?>'> 
                <input type='hidden' name='item_name' value='<?php echo get('name'); ?>'>
                <input type='hidden' name='item_number' value='<?php echo substr(hash('sha256', getTxnId() . microtime()), 0, 20); ?>'>
                <input type='hidden' name='amount' value='<?php echo $tmp['final_total']; ?>'>
                <input type='hidden' name='no_shipping' value='1'>
                <input type='hidden' name='currency_code' value='<?php echo $paymentMethods->paypal_currency_code; ?>'>
                <input type='hidden' name='notify_url' value='<?php echo get('api_url').get('apis.paypal-ipn') ?>'>
                <input type='hidden' name='cancel_return' value='<?php echo route('checkout-paypal', 'cancel') ?>'>
                <input type='hidden' name='return' value='<?php echo route('checkout-paypal','return') ?>'>
                <input type="hidden" name="cmd" value="_xclick">
            </form>
            <script>
            window.onload = function(){
                document.forms[0].submit();
            }
            </script>
        <?php

        }

    }

    public function checkout_paypal(Request $request, $type = "return"){

        if($type == "return"){
            
            $error = true;
            
            $msg = "Payment either cancelled / failed to initialize. Try again or try some other payment method. Thank you";

            if(isset($_GET['amt']) && isset($_GET['st']) && $_GET['st'] == 'Completed'){
                $msg = "Payment completed successfully";
                $error = false;
            }elseif(isset($_GET['amt']) && isset($_GET['st']) && $_GET['st'] == 'Authrize'){
                $msg = "Payment is authorized successfully. Your order will be fulfilled once we capture the transaction.";
                $error = false;
            }elseif(isset($_GET['tx']) && $_GET['tx'] == 'disabled'){
                $msg = "Paypal payment method is not available currently";
            }

            $orderId = "";

            if(!$error){

                $response = $this->order_placed($request->session()->get('tmp_paypal'));

                $orderId = $response['data']['order_id'] ?? "";

                if(intval($orderId)){

                    $this->add_transaction($response['data']['order_id'], "paypal", $request->item_number ?? '', true, $msg, $request->amt ?? 0);

                    return redirect()->route('my-orders')->with('suc', $response['message'] ?? $msg);

                }

            }

            $this->add_transaction($orderId, "paypal", $request->item_number ?? '', false, $msg, $request->amt ?? 0);

            return redirect()->route('checkout-payment')->with('err', $response['message'] ?? $msg);

        }
        
        return redirect()->route('checkout-payment')->with('err', $msg);

    }

    public function checkout_payu_bolt_init(Request $request){

        $paymentMethods = Cache::get('payment_methods');

        if($request->has('status') && $request->status == 'failed'){

            return redirect()->route('checkout-payment')->with('err', 'Failed To Make Payment With PayUMoney. Kindly Select Another Option');

        }

        if(isset($paymentMethods->payumoney_payment_method) && $paymentMethods->payumoney_payment_method == 1){

            $loggedInUser = $request->session()->get('user');

            $tmp = $request->session()->get('tmp_payu');

            $mode = $paymentMethods->payumoney_mode;

            $merchant_key = $paymentMethods->payumoney_merchant_key;

            $salt = $paymentMethods->payumoney_salt;

            $payment_url = 'https://checkout-static.citruspay.com/bolt/run/bolt.min.js';

            if($mode == 'sandbox'){

                $payment_url = 'https://sboxcheckout-static.citruspay.com/bolt/run/bolt.min.js';

            }

            $data = ['key' => $merchant_key, 'salt' => $salt, 'txnid' => substr(hash('sha256', getTxnId() . microtime()), 0, 20)];

            $data['amount'] = $tmp['total'];

            $data['firstname'] = $loggedInUser['name'];

            $data['email'] = $loggedInUser['email'];

            $data['phone'] = $loggedInUser['mobile'];

            $data['productinfo'] = get('name');

            $data['surl'] = route('checkout-payu-bolt');

            $data['furl'] = route('checkout-payu-bolt', ['status' => 'failed']);

            $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|||||";
            $hashVarsSeq = explode('|', $hashSequence);
            $hash_string = '';	
            foreach($hashVarsSeq as $hash_var) {
              $hash_string .= isset($data[$hash_var]) ? $data[$hash_var] : '';
              $hash_string .= '|';
            }
            $data['hash'] = strtolower(hash('sha512', $hash_string.$salt));
            ?>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" >
            <script id="bolt" src="<?php echo $payment_url; ?>" bolt-color="e34524" bolt-logo="<?php echo asset('images/headerlogo.png'); ?>"></script>
            <script type="text/javascript">
            function launchBOLT()
            {
                bolt.launch({
                key: "<?php echo $data['key']?>",
                txnid: "<?php echo $data['txnid']?>", 
                hash: "<?php echo $data['hash'] ?>",
                amount: "<?php echo $data['amount']?>",
                firstname: "<?php echo $data['firstname'] ?>",
                email: "<?php echo $data['email'] ?>",
                phone: "<?php echo $data['phone'] ?>",
                productinfo: "<?php echo $data['productinfo'] ?>",
                udf5: "",
                surl : "<?php echo $data['surl']?>",
                furl: "<?php echo $data['furl']?>",
                mode: 'dropout'
            },{ 
                responseHandler: function(BOLT){
                    console.log( BOLT.response.txnStatus );		
                    if(BOLT.response.txnStatus != 'CANCEL')
                    {
                        //Salt is passd here for demo purpose only. For practical use keep salt at server side only.
                        var fr = '<form action=\"'+$('#surl').val()+'\" method=\"post\">' +
                        '<input type=\"hidden\" name=\"key\" value=\"'+BOLT.response.key+'\" />' +
                        '<input type=\"hidden\" name=\"salt\" value=\"'+$('#salt').val()+'\" />' +
                        '<input type=\"hidden\" name=\"txnid\" value=\"'+BOLT.response.txnid+'\" />' +
                        '<input type=\"hidden\" name=\"amount\" value=\"'+BOLT.response.amount+'\" />' +
                        '<input type=\"hidden\" name=\"productinfo\" value=\"'+BOLT.response.productinfo+'\" />' +
                        '<input type=\"hidden\" name=\"firstname\" value=\"'+BOLT.response.firstname+'\" />' +
                        '<input type=\"hidden\" name=\"email\" value=\"'+BOLT.response.email+'\" />' +
                        '<input type=\"hidden\" name=\"udf5\" value=\"'+BOLT.response.udf5+'\" />' +
                        '<input type=\"hidden\" name=\"mihpayid\" value=\"'+BOLT.response.mihpayid+'\" />' +
                        '<input type=\"hidden\" name=\"status\" value=\"'+BOLT.response.status+'\" />' +
                        '<input type=\"hidden\" name=\"hash\" value=\"'+BOLT.response.hash+'\" />' +
                        '</form>';
                        var form = jQuery(fr);
                        jQuery('body').append(form);								
                        form.submit();
                    }
                },
                catchException: function(BOLT){
                    window.location.href = '<?php echo $data['furl']; ?>';
                }
            });
            }
            $(document).ready(function(){
                launchBOLT();
            });
            </script>
            <?php
        }else{

            return redirect()->route('cart')->with('err', 'Kindly Select Another Payment Method');

        }

    }

    public function checkout_payu_bolt(Request $request){

        if($request->has('status') && $request->status == 'success'){

            $response = $this->order_placed($request->session()->get('tmp_payu'));

            if($response['success'] && intval($response['data']['order_id'])){

                $trans = $this->add_transaction($response['data']['order_id'], "payumoney", $request->txnid ?? '', true, msg('order_success'), $request->amount ?? 0);

                if(isset($trans['error']) && !$trans['error']){

                    return redirect()->route('my-orders')->with('suc', $response['message'] ?? msg('order_success'));

                }else{

                    return redirect()->route('my-orders')->with('suc', $response['message']."<br>".$trans['message'] ?? msg('order_success'));

                }

            }else{

                $this->add_transaction($response['data']['order_id'], "payumoney", $request->txnid ?? '', true, msg('order_success'), $request->amount ?? 0);

                return redirect()->back()->with('err', $response['message'] ?? msg('order_error'));

            }

        }

    }

    public function add_transaction($orderId = "", $paymentType = "", $txnId = "", $status = true, $message = "", $amount = 0){

        $data = ['add_transaction' => 1, 'user_id' => session()->get('user')['user_id'], 'order_id' => $orderId, 'type' => $paymentType, 'txn_id' => $txnId, 'amount' => $amount, 'status' => ($status ? 'Success' : 'canceled'), 'message' => $message ?? msg('order_success'), 'transaction_date' => date('Y-m-d H:i:s')];

        return $this->post('order-process', ['data' => $data, 'data_param' => '']);

    }

    public function checkout_razorpay_init(Request $request){

        $loggedInUser = $request->session()->get('user');

        $data = $request->session()->get('tmp_razorpay');

        $response = $this->post('razorpay-order', ['data' => ['amount' => $data[api_param('final-total')] * 100, 'user_id' => session()->get('user')['user_id']], 'data_param' => '']);

        if(isset($response['error']) && !$response['error']){
    
        ?>
            <form action="<?php echo route('checkout-razorpay'); ?>" method="POST">
                <input type="hidden" name="data" value='<?php echo json_encode($response); ?>'>
                <input type="hidden" name="amount" value='<?php echo $data[api_param('final-total')]; ?>'>
                <input type="hidden" id="razorpay_payment_id" name="razorpay_payment_id" value="">
                <input type="hidden" id="razorpay_order_id" name="razorpay_order_id" value="">
                <input type="hidden" id="razorpay_signature" name="razorpay_signature" value="">
            </form>
            <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
            <script>
            window.onload = function(){
                var options = {
                    "key": "<?php echo Cache::get('payment_methods')->razorpay_key; ?>", // Enter the Key ID generated from the Dashboard
                    "amount": "<?php echo $response['amount']; ?>", // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
                    "currency": "<?php echo $response['currency']; ?>",
                    "name": "<?php echo get('name'); ?>",
                    "description": "",
                    "image": "<?php echo asset('images/headerlogo.png'); ?>",
                    "order_id": "<?php echo $response['id']; ?>", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
                    "handler": function (response){
                        document.getElementById("razorpay_payment_id").value = response.razorpay_payment_id;
                        document.getElementById("razorpay_order_id").value = response.razorpay_order_id;
                        document.getElementById("razorpay_signature").value = response.razorpay_signature;
                        document.forms[0].submit();
                    },
                    "modal": {
                        "ondismiss": function(){
                            window.location.replace("<?php echo route('checkout-payment') ?>");
                        }
                    },
                    "prefill": {
                        "name": "<?php echo $loggedInUser['name']; ?>",
                        "email": "<?php echo $loggedInUser['email']; ?>",
                        "contact": "<?php echo $loggedInUser['mobile']; ?>"
                    },
                    "theme": {
                        "color": "#F37254"
                    }
                };
                var rzp1 = new Razorpay(options);
                rzp1.open();
            }
            </script>
        <?php

        }else{

            return redirect()->back()->with('err', $response['message'] ?? msg('order_error'));

        }
    }

    public function checkout_razorpay(Request $request){

        $data = json_decode($request->data);

        $generated_signature = hmac_sha256($data->id."|".$request->razorpay_payment_id, Cache::get('payment_methods')->razorpay_secret_key);

        $return = false;

        if($generated_signature == $request->razorpay_signature){

            $response = $this->order_placed($request->session()->get('tmp_razorpay'));

            if($response['success'] && intval($response['data']['order_id'])){

                $trans = $this->add_transaction($response['data']['order_id'], "razorpay", $request->razorpay_payment_id ?? '', true, msg('order_success'), $request->amount ?? 0);

                if(isset($trans['error']) && !$trans['error']){

                    $return = redirect()->route('my-orders')->with('suc', $response['message'] ?? msg('order_success'));

                }else{

                    $return = redirect()->route('my-orders')->with('suc', $response['message']."<br>".$trans['message'] ?? msg('order_success'));

                }

            }else{

                $this->add_transaction($response['data']['order_id'], "razorpay", $request->razorpay_payment_id ?? '', true, msg('order_success'), $request->amount ?? 0);

                $return = redirect()->back()->with('err', $response['message'] ?? msg('order_error'));

            }

        }else{

            $return = redirect()->back()->with('err', $response['message'] ?? msg('order_error'));          

        }

        return $return;

    }

    public function coupon_apply(Request $request){

        $loggedInUser = session()->get('user');

        $response = array('error' => true, 'message' => 'Enter Coupon Code');

        if($request->has('coupon') && trim($request->coupon) != ""){

            $data = [];

            $data['validate_promo_code'] = api_param('get-val');

            $data[api_param('user-id')] = $loggedInUser['user_id'];

            $data[api_param('promo-code')] = $request->coupon;

            $data[api_param('total')] = $request->total ?? 0;

            $response = $this->post('validate-promo-code', ['data' => $data]);

            if(!$response['error'] && floatval($response['discount']) > 0){

                session()->put('discount', $response);

                $response['url'] = route('checkout');

            }

        }

        echo json_encode($response);

    }

    public function coupon_remove(){

        session()->put('discount', '');

        $this->calc();

        return redirect()->back();

    }

}