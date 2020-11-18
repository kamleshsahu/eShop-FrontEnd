<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller{
    
    public function index(Request $request){
        $user = $this->post('get-user', ['data' => ['get_user_data' => 1, 'user_id' => session()->get('user')['user_id']]]);
        if(isset($user['error']) && !$user['error']){
            $data['profile'] = $user;
            $this->html('user.account', $data);
        }else{
            return redirect()->route('logout');
        }
    }

    public function orders(Request $request, $type = ""){

        $limit = 5;

        $page = $request->page ?? 0;

        $data = [api_param('get-orders') => api_param('get-val'), api_param('user-id') => session()->get('user')['user_id'], 'limit' => $limit, 'offset' => ($page * $limit)];

        if(in_array($type, ['cancelled', 'processed', 'delivered', 'received', 'returned', 'shipped'])){

            $data['status'] = $type;

        }
        
        $list = $this->post('order-process', ['data' => $data, 'data_param' => '']);

        $total = $list['total'] ?? 0;

        $lastPage = "";

        if(intval($page - 1) > -1){

            if(intval($page - 1) == 0){

                $lastPage = route('my-orders', ['type' => $type]);

            }else{

                $lastPage = route('my-orders', ['type' => $type, 'page' => $page-1]);

            }

        }

        $nextPage = "";

        if(intval($total / $limit) > $page+1){

            $nextPage = route('my-orders', ['type' => $type, 'page' => $page+1]);

        }

        $this->html('user.orders', ['list' => $list, 'limit' => $limit, 'total' => $total, 'next' => $nextPage, 'last' => $lastPage]); 
        
    }

    public function track($orderId){
        
        $data = [api_param('get-orders') => api_param('get-val'), api_param('user-id') => session()->get('user')['user_id'], 'order_id' => $orderId];
        
        $list = $this->post('order-process', ['data' => $data]);

        if(count($list) && isset($list[0])){

            $this->html('user.order-track', ['list' => $list[0]]); 

        }else{

            abort(404);

        }
        
    }

    public function order_status_update($orderId, $orderItemId, $status){

        $response = $this->post('order-process', ['data' => ['update_order_item_status' => 1, 'user_id' => session()->get('user')['user_id'], 'order_item_id' => $orderItemId, 'order_id' => $orderId, 'status' => $status]]);

        if(isset($response['error']) && !$response['error']){

            return redirect()->back()->with('suc', $response['message'] ?? "Order's Status Updated Successfully.");

        }else{

            return redirect()->back()->with('err', $response['message'] ?? "Unable To Update Order's Status");

        }

    }

    public function walletHistory(Request $request){

        $limit = 10;

        $page = $request->page ?? 0;

        $list = $this->post('wallet-history', ['data' => [ 'get_user_transactions' => 1, api_param('user-id') => session()->get('user')['user_id'], 'type' => 'wallet_transactions', 'limit' => $limit, 'offset' => ($page * $limit)], 'data_param' => '']);

        $data = $this->pagination($list, "wallet-history", $page, $limit);

        \extract($data);

        $this->html('user.wallet-history', ['list' => $list, 'limit' => $limit, 'total' => $total, 'next' => $nextPage, 'last' => $lastPage]); 

    }

    public function transactionHistory(Request $request, $type = 'wallet'){

        $limit = 10;

        $page = $request->page ?? 0;

        $list = $this->post('wallet-history', ['data' => [ 'get_user_transactions' => 1, api_param('user-id') => session()->get('user')['user_id'], 'type' => 'transactions', 'limit' => $limit, 'offset' => ($page * $limit)], 'data_param' => '']);

        $data = $this->pagination($list, "transaction-history", $page, $limit);

        \extract($data);

        $this->html('user.transaction-history', ['list' => $list, 'limit' => $limit, 'total' => $total, 'next' => $nextPage, 'last' => $lastPage]);        

    }

    public function notification(Request $request){

        $limit = 10;

        $page = $request->page ?? 0;

        $list = $this->post('sections', ['data' => [ 'get-notifications' => 1, api_param('user-id') => session()->get('user')['user_id'], 'limit' => $limit, 'offset' => ($page * $limit), 'sort' => 'id', 'order' => 'DESC'], 'data_param' => '']);

        $total = $list['total'] ?? 0;

        $lastPage = "";

        if(intval($page - 1) > -1){

            if(intval($page - 1) == 0){

                $lastPage = route('notification');

            }else{

                $lastPage = route('notification', ['page' => $page-1]);

            }

        }

        $nextPage = "";

        if(intval($total / $limit) > $page){

            $nextPage = route('notification', ['page' => $page+1]);

        }

        $this->html('user.notification', ['list' => $list, 'limit' => $limit, 'total' => $total, 'next' => $nextPage, 'last' => $lastPage]);
    }

    public function update_profile(Request $request){
        $params = $request->only('name', 'email', 'pincode', 'city_id', 'area_id', 'street', 'dob');
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'email',
            'pincode' => 'numeric|required',
            'city_id' => 'required',
            'area_id' => 'required',
            'street' => 'required',
        ]);
        $params['type'] = api_param('edit-profile');
        $params['id'] = session()->get('user')['user_id'];
        $update = $this->post('user-registration', ['data' => $params]);
        if(isset($update['error']) && $update['error'] ===  false){
            $msg = $update['message'] ?? 'Profile Updated Successfully';
            $user = session()->get('user');
            foreach($params as $k => $v){
                $user[$k] = $v;
            }
            session()->put('user', $user);
            return back()->with('suc', $msg);
        }else{
            $msg = $update['message'] ?? msg('error');
            return back()->with('err', $msg);
        }
    }

    public function password(){
        $this->html('user.password');
    }

    public function change_password(Request $request){
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed|min:5',
        ]);
        if($request->current_password == $request->new_password){
            return back()->with('err', 'New Password & Old Password Can\'t Be Same');
        }else{
            $login = $this->post('login', ['data' => [api_param('mobile') => session()->get('user')['mobile'], api_param('password') => $request->current_password]]);
            if(isset($login['user_id']) && intval($login['user_id']) && $login['user_id'] == session()->get('user')['user_id']){
                $params[api_param('password')] = $request->new_password;
                $params[api_param('type')] = api_param('change-password');
                $params[api_param('id')] = session()->get('user')['user_id'];
                $update = $this->post('user-registration', ['data' => $params]);
                if(isset($update['error']) && $update['error'] ===  false){
                    return redirect()->route('my-account')->with('suc', 'Profile Changed Successfully');
                }else{
                    $err = $update['message'] ?? msg('error');
                }
            }else{
                $err = "Your Old Password is Incorrect";
            }
            return back()->with('err', $err);
        }
    }

    public function reset_password(Request $request){
        $response = array('status' => false, 'message' => msg('error'));
		$validator = Validator::make($request->all(), [
            'password' => 'required|min:5|confirmed',
        ]);
		if ($validator->fails()) {
            $errors = $validator->messages()->all();
            $response['message'] = $errors[0];
		}else{
            $tmp_user = session()->get('temp_user');
            $data = ['data' => [api_param('type') => api_param('change-password'), api_param('password') => $request->password, api_param('id') => $tmp_user['id']]];                        
            $response = $this->post('user-registration', $data);
            if(!$response['error']){
                session()->put('suc', 'Password Reset Successfully.');
            }
        }
        echo json_encode($response);
    }

    public function referearn(Request $request){
        
        $user = $this->post('get-user', ['data' => ['get_user_data' => 1, 'user_id' => session()->get('user')['user_id']]]);

        if(isset($user['error']) && !$user['error']){

            $data['profile'] = $user;

            $this->html('user.refer-earn', $data);

        }else{

            return redirect()->route('logout');

        }

    }

    public function logout(Request $request){

        $request->session()->flush();

        return redirect()->route('login')->with('suc', 'You are Logged Out Successfully');

    }

    public function address(Request $request){

        $address = $this->post('addresses', ['data' => ['get_addresses' => 1, 'user_id' => session()->get('user')['user_id']]]);
        
        if(isset($address['error']) && !$address['error']){

            $data['address'] = [];

        }else{

            if($address && count($address)){

                $data['address'] = $address;

            }

        }
        $this->html('user.addresses', ['address' => $address]);

    }

    public function address_add(Request $request){

        $params = $request->only('name', 'mobile', 'alternate_mobile', 'address', 'landmark', 'pincode', 'city', 'area', 'state', 'country', 'type', 'latitude', 'longitude', 'country_code');
        
        $request->validate([
            'name' => 'required|max:255',
            'mobile' => 'required|numeric',
            'pincode' => 'required|numeric',
            'city' => 'required',
            'area' => 'required',
            'address' => 'required',
            'state' => 'required',
            'country' => 'required',
        ]);
        if($request->has('id') && intval($request->id)){
            $params['update_address'] = 1;
            $params['id'] = intval($request->id);
        }else{
            $params['add_address'] = 1;
        }
        if($request->has('is_default') && $request->is_default == "on"){
            $params['is_default'] = 1;
        }else{
            $params['is_default'] = 0;
        }
        $params['city_id'] = $params['city'];
        $params['area_id'] = $params['area'];
        $params['user_id'] = session()->get('user')['user_id'];

        $result = $this->post('addresses', ['data' => $params]);

        if(isset($result['error']) && !$result['error']){

            return redirect()->back()->with('suc', $result['message'] ?? 'Address Added Successfully');

        }else{

            return redirect()->back()->with('err', $result['message'] ?? msg('error'));

        }

    }

    public function address_remove($id){

        $result = $this->post('addresses', ['data' => ['delete_address' => 1, 'id' => $id]]);

        if(isset($result['error']) && !$result['error']){

            return redirect()->back()->with('suc', $result['message'] ?? 'Address Removed Successfully');

        }else{

            return redirect()->back()->with('err', $result['message'] ?? msg('error'));

        }

    }

}