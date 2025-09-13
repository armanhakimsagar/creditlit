<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Session;

class NotFoundController extends Controller
{

    public function pagenotfound()
    {
    	$user_data = Auth::user();

    	if (!empty($user_data)) {

	    	if ($user_data->type == 'admin') {

	    		return redirect()->back();

	    	}

	   }else{
    			return redirect('/');
    		}
    }

    public function pagenotfoundlogin()
    {
        return redirect('/');
    }


}
