<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function($request) {
	//dd($request->getActionName());
	//echo Route::getCurrentRoute()->getName(); //get route
	//echo Route::currentRouteAction(); // get current action	
	if (isset(Auth::user()->ad_id)){
		$user_id = Auth::user()->ad_id;
		$user_login = Auth::user()->uname;
		$user_type = (Auth::user()->ad_cat_id==0?'Admin':'User');
		$user_name = Auth::user()->fname.' '.Auth::user()->lname;
		$main_module = ucfirst(Request::segment(1)).' Admin';
		$sub_module = ucfirst(Request::segment(1)).' '.(Request::segment(2)==''?'Index':str_replace('_',' ',ucfirst(Request::segment(2))));
		$action = Route::getCurrentRoute()->getName();
		$url = URL::to('/').'/'.Route::getCurrentRoute()->getPath();
		DB::table('gpg_activity_log')->insert(array('gpg_user_id'=>$user_id,'full_name'=>$user_name,'user_name'=>$user_login,'user_type'=>$user_type,'main_module'=>$main_module,'sub_module'=>$sub_module,'action'=>$action,'url'=>$url,'datetime_stamp'=>date('Y-m-d H:i:s')));
	}
	if (isset(Auth::user()->ad_id) && Auth::user()->ad_id != '1') {
		$qry = DB::select(DB::raw("Select m.module_name,m.module_action from gpg_module m,gpg_mod_perm mp where m.id=mp.GPG_module_id and mp.GPG_ad_acc_id=".Auth::user()->ad_id.""));
		$act_arr = array();
		foreach($qry as $mkey=>$mData){
			array_push($act_arr,$mData->module_action);
		}
		if(Route::currentRouteAction() != 'MainController@dashboard' && !in_array(Route::currentRouteAction(), $act_arr)){
			return View::make('500');		
		}
	}
	/*if (Auth::guest()){

		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('login');
		}
	}*/
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	/*if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}*/
});
