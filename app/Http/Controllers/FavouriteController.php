<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Kreait\Firebase\Factory;

class FavouriteController extends UserController{

    public function index(Request $request){

        if(!isLoggedIn()){

            return redirect()->route('login');

        }

        $limit = 10;

        $page = $request->page ?? 0;

        $list = $this->post('favorites', ['data' => [ 'get_favorites' => 1, api_param('user-id') => session()->get('user')['user_id'], 'limit' => $limit, 'offset' => ($page * $limit)], 'data_param' => '']);

        $data = $this->pagination($list, "favourite", $page, $limit);

        \extract($data);

        $this->html('user.favorites', ['list' => $list, 'limit' => $limit, 'total' => $total, 'next' => $nextPage, 'last' => $lastPage]);

    }

    public function remove(Request $request, $id){

        if(!isLoggedIn()){

            return redirect()->route('login');

        }

        $results = $this->post('favorites', ['data' => [ 'remove_from_favorites' => 1, api_param('product-id') => $id, api_param('user-id') => session()->get('user')['user_id']]]);

        $request->id = $id;

        $this->remove_ajax($request);

        if(isset($results['error']) && !$results['error']){

            return redirect()->back()->with('suc', $results['message'] ?? "Item removed from user's favorite list successfully");

        }else{

            return redirect()->back()->with('err', $results['message'] ?? msg('error'));

        }

    }

    public function add_ajax(Request $request){

        if(!isLoggedIn()){

            echo "login";

        }else{

            echo json_encode($this->post('favorites', ['data' => [ 'add_to_favorites' => 1, api_param('product-id') => $request->id, api_param('user-id') => session()->get('user')['user_id']]]));

        }

    }

    public function remove_ajax(Request $request){

        if(!isLoggedIn()){

            echo "login";

        }else{

            echo json_encode($this->post('favorites', ['data' => [ 'remove_from_favorites' => 1, api_param('product-id') => $request->id, api_param('user-id') => session()->get('user')['user_id']]]));

        }

    }

}