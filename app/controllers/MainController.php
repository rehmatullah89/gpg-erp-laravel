<?php

class MainController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/
	//public static $small_widget_var = $this->small_widgets_arr();

	public function showWelcome()
	{
		//Session::put('gpg_admin_id', '1');
		//print_r(Generic::left_menu());
		//$modules = User::all();

		//return View::make('hello');
	}

	public function changePassword(){
		$modules = Generic::modules();
		if (isset($_POST) && !empty($_POST)){
			$oldpass = Input::get('oldpass');
			$newpass = Input::get('newpass');
			$repass = Input::get('repass');
			$id = Input::get('user_id');
			$user = Gpg_ad_acc::where('ad_id', '=', $id)->first();
			if(isset($user)){
				if(!Hash::check($oldpass,$user->gpg_password)) // new password is not correct
				{
					return View::make('main/change_pass',array('validation_error'=>'Old Password is in-correct!'));
				}
				else if(!empty($newpass) && ($newpass == $repass)) // new password is correct
				{ 
					DB::table('gpg_ad_acc')->where('ad_id','=',$id)->update(array('gpg_password'=>Hash::make($newpass),'last_modified_date'=>date('Y-m-d')));
					return View::make('main/login_form',array('validation_error'=>'Password has been changed Successfully!'));
				}else{
					return View::make('main/change_pass',array('validation_error'=>'Compare Password does not match!'));	 
				}
			}
		}
		$params = array('left_menu' => $modules);
		return View::make('main/change_pass',$modules);
	}

	public function showLogin()
	{
		set_time_limit(0);
		if(Auth::check())
			return Redirect::to('dashboard');
		return View::make('main/login_form');
	}
	public function authenticate(){
		if(Auth::check())
			return Redirect::to('dashboard');
		$username = Input::get('username');
		$userpass = Input::get('userpass');
		$data = array('username'=>$username,'userpass'=>$userpass);
		$rules = array('username'=>'required','userpass'=>'required');
		$validator = Validator::make($data,$rules);
		if($validator->fails())
		{
			$messages = $validator->messages();
			return View::make('main/login_form',array('messages'=>$messages));
		}
		$user = Gpg_ad_acc::where('uname', '=', $username)->first();
		if(isset($user)){
			if(!Hash::check($userpass,$user->gpg_password) ) // new password is not correct
			{
				if($user->pwd == md5($userpass))  // old password is correct
				{ // If their password is still MD5
			        $user->gpg_password = Hash::make($userpass); // Convert to new format
			        $user->save();
			        Auth::login($user);
			        $url = Session::get('url_to','notset');
			        if($url=='notset')
			        	return Redirect::to('dashboard');
			        else
			        	return Redirect::to($url);
			    }
			    else // 
			    { // failure authentication
			    	$validation_error = "Invalid Credentials";
			    	return View::make('main/login_form',array('validation_error'=>$validation_error));
			    }
			}
			else // new password is correct
			{ 
					Auth::login($user);

					$url = Session::get('url_to','notset');
					
			        if($url=='notset')
			        	return Redirect::to('dashboard');
			        else
			        	return Redirect::to($url);
					die;
			}

				$validation_error = "Invalid Credentials";
		    	return View::make('main/login_form',array('validation_error'=>$validation_error));
			//die;
			 
		}
			$validation_error = "Invalid Credentials";
			return View::make('main/login_form',array('validation_error'=>$validation_error));
	}
	
	protected function default_currency(){
		$default_currency = array();
        $default_currency = Generic::application_constants();
        return $default_currency['_DefaultCurrency'];
	}

	protected function shop_work_open_quotes(){
		$gpg_shop_work_quote_arr = array();
		$results = DB::select( DB::raw("select count(a.id) as cnt,sum(ifnull(a.grand_list_total,0)) as fQoueAmt,b.name,a.shop_work_quote_status,a.GPG_employee_id  from gpg_shop_work_quote a, gpg_employee b where a.GPG_employee_id = b.id and b.status = 'A' and a.shop_work_quote_status = 'Quote' group by a.GPG_employee_id having cnt > 1 order by b.name") );
		 foreach($results as $key => $value){
            $gpg_shop_work_quote_arr[] = array('name'=>$value->name, 'count'=>$value->cnt, 'price'=>$value->fQoueAmt);
        }
       $gpg_shop_work_quote = json_encode($gpg_shop_work_quote_arr);
       
       return $gpg_shop_work_quote;
	} 

	protected function electrical_open_quotes(){
		$electrical_open_quotes_arr = array();
		$results = DB::select( DB::raw("select COUNT(a.id) AS cnt,SUM(IFNULL(a.grand_total ,0)+IFNULL(a.subquote_total_cost,0)) AS fQoueAmt,b.name,a.electrical_status,a.GPG_employee_id  FROM gpg_job_electrical_quote a, gpg_employee b WHERE a.GPG_employee_id = b.id AND b.status = 'A' AND a.electrical_status = 'Quote' GROUP BY a.GPG_employee_id having cnt > 1 order by b.name") );
		 foreach($results as $key => $value){
            $electrical_open_quotes_arr[] = array('name'=>$value->name, 'count'=>$value->cnt, 'price'=>$value->fQoueAmt);
        }
       $electrical_open_quotes = json_encode($electrical_open_quotes_arr);
       
       return $electrical_open_quotes;
	}  

	protected function field_service_work_open_quotes(){
		$field_service_work_open_quotes_arr = array();
		$results = DB::select( DB::raw("select count(a.id) as cnt,sum(ifnull(a.grand_list_total,0)) as fQoueAmt,b.name,a.field_service_work_status,a.GPG_employee_id  from gpg_field_service_work a, gpg_employee b where a.GPG_employee_id = b.id and b.status = 'A' and a.field_service_work_status = 'Quote' group by a.GPG_employee_id having cnt > 1 order by b.name") );
		 foreach($results as $key => $value){
            $field_service_work_open_quotes_arr[] = array('name'=>$value->name, 'count'=>$value->cnt, 'price'=>$value->fQoueAmt);
        }
       $field_service_work_open_quotes = json_encode($field_service_work_open_quotes_arr);
       
       return $field_service_work_open_quotes;
	}
	protected function grassivy_jobs_listing_filters(){
		$grassivy_jobs_listing_filters_arr = array();
		
		$results1 = DB::select( DB::raw("select count(id) as cnt, sum(if(fixed_price>0,fixed_price,if(nte>0,nte,if(sub_nte>0,sub_nte,(if(contract_amount>0,contract_amount,0)))))) as contractAmt from gpg_job where job_num like 'IG%' and GPG_job_type_id='12' AND complete = '0'") );
		$grassivy_jobs_listing_filters_arr[] = array('value' => $results1[0]->cnt,'label' => 'Jobs Not Completed','amount' => number_format($results1[0]->contractAmt,2) ); 
		$results2 = DB::select( DB::raw("select count(id) as cnt, sum(if(fixed_price>0,fixed_price,if(nte>0,nte,if(sub_nte>0,sub_nte,(if(contract_amount>0,contract_amount,0)))))) as contractAmt from gpg_job where job_num like 'IG%' and GPG_job_type_id='12' AND (complete = '0' AND (select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id limit 0,1))") );
		$grassivy_jobs_listing_filters_arr[] = array('value' => $results2[0]->cnt,'label' => 'Have been Invoiced but Not Completed','amount' => number_format($results2[0]->contractAmt,2) ); 
		$results3 = DB::select( DB::raw("select count(id) as cnt, sum(if(fixed_price>0,fixed_price,if(nte>0,nte,if(sub_nte>0,sub_nte,(if(contract_amount>0,contract_amount,0)))))) as contractAmt from gpg_job where job_num like 'IG%' and GPG_job_type_id='12' AND created_on >= '". date('Y-m-d',strtotime('01/01/2011'))." 00:00:00' AND created_on <= '".date('Y-m-d')." 23:59:59' AND (complete = '1' AND if((select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id limit 0,1)>0,0,1))") );
		$grassivy_jobs_listing_filters_arr[] = array('value' => $results3[0]->cnt,'label' => 'Completed but Have Not been Invoiced (Created Date: '.date('m/d/Y',strtotime('01/01/2011')).' to '.date('m/d/Y').')','amount' => number_format($results3[0]->contractAmt,2) ); 

		$grassivy_jobs_listing_filters = json_encode($grassivy_jobs_listing_filters_arr);
        return $grassivy_jobs_listing_filters;
	}

	protected function shop_work_jobs_listing_filters(){
		$shop_work_jobs_listing_filters_arr = array();
		
		$results1 = DB::select( DB::raw("select count(id) as cnt, sum(ifnull(contract_amount,0)) as contractAmt from gpg_job where job_num like 'SH%' and SUBSTRING(job_num,3)*1 >= 100000 and GPG_job_type_id='4'  AND complete = '0'") );
		$shop_work_jobs_listing_filters_arr[] = array('value' => $results1[0]->cnt,'label' => 'Jobs Not Completed','amount' => number_format($results1[0]->contractAmt,2) ); 
		$shop_work_jobs_listing_filters_arr[] = array('value' => $results1[0]->cnt,'label' => 'Total','amount' => number_format($results1[0]->contractAmt,2) ); 
		
		$shop_work_jobs_listing_filters = json_encode($shop_work_jobs_listing_filters_arr);
        return $shop_work_jobs_listing_filters;
	}

	protected function special_project_jobs_listing_filters(){
		$special_project_jobs_listing_filters_arr = array();
		
		$results1 = DB::select( DB::raw("select count(id) as cnt, sum(if(fixed_price>0,fixed_price,if(nte>0,nte,if(sub_nte>0,sub_nte,(if(contract_amount>0,contract_amount,0)))))) as contractAmt from gpg_job where job_num like 'LK%' and GPG_job_type_id='13' AND complete = '0'") );
		$special_project_jobs_listing_filters_arr[] = array('value' => $results1[0]->cnt,'label' => 'Jobs Not Completed','amount' => number_format($results1[0]->contractAmt,2) ); 
		$results2 = DB::select( DB::raw("select count(id) as cnt, sum(if(fixed_price>0,fixed_price,if(nte>0,nte,if(sub_nte>0,sub_nte,(if(contract_amount>0,contract_amount,0)))))) as contractAmt from gpg_job where job_num like 'LK%' and GPG_job_type_id='13' AND (complete = '0' AND (select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id limit 0,1))") );
		$special_project_jobs_listing_filters_arr[] = array('value' => $results2[0]->cnt,'label' => 'Have been Invoiced but Not Completed','amount' => number_format($results2[0]->contractAmt,2) ); 
		$results3 = DB::select( DB::raw("select count(id) as cnt, sum(if(fixed_price>0,fixed_price,if(nte>0,nte,if(sub_nte>0,sub_nte,(if(contract_amount>0,contract_amount,0)))))) as contractAmt from gpg_job where job_num like 'LK%' and GPG_job_type_id='13' AND created_on >= '".date('Y-m-d',strtotime('01/01/2011'))." 00:00:00' AND created_on <= '".date('Y-m-d')." 23:59:59' AND (complete = '1' AND if((select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id limit 0,1)>0,0,1))") );
		$special_project_jobs_listing_filters_arr[] = array('value' => $results3[0]->cnt,'label' => 'Completed but Have Not been Invoiced (Created Date: '.date('m/d/Y',strtotime('01/01/2011')).' to '.date('m/d/Y').')','amount' => number_format($results3[0]->contractAmt,2) ); 
		
		$special_project_jobs_listing_filters = json_encode($special_project_jobs_listing_filters_arr);
        return $special_project_jobs_listing_filters;
	}

	protected function fsw_report_filters(){
		$fsw_report_filters_arr = array();
		
		$results1 = DB::select( DB::raw("select count(a.id) as cnt, sum(ifnull(a.grand_list_total,0)) as fQoueAmt from gpg_field_service_work a LEFT JOIN gpg_job b ON a.GPG_attach_job_id = b.id where ifnull(a.GPG_attach_job_id,0)<>0 and ifnull(a.date_job_won,0)<>0 AND b.complete = '0' ") );
		$fsw_report_filters_arr[] = array('value' => $results1[0]->cnt,'label' => 'Won but Job(s) Not Completed','amount' => number_format($results1[0]->fQoueAmt,2) ); 
		
		$fsw_report_filters = json_encode($fsw_report_filters_arr);
        return $fsw_report_filters;
	}
	protected function tc_report_filters(){
		$tc_report_filters_arr = array();
		
		$results1 = DB::select( DB::raw("SELECT COUNT(*) as total_sum FROM gpg_job WHERE job_num LIKE 'TC%' AND complete = 0 AND  created_on >= '".date('Y-01-01')." 00:00:00' AND created_on <= '".date('Y-m-d',time())." 23:59:59' ") );
		$tc_report_filters_arr[] = array('label' => 'Contracts having open jobs (Created Date: '.date('01/01/Y').' to '.date('m/d/Y').')','amount' => number_format($results1[0]->total_sum,2) ); 
		
		$tc_report_filters = json_encode($tc_report_filters_arr);
        return $tc_report_filters;
	}

	protected function service_job_recommendation_categories(){
		$service_job_recommendation_categories_arr = array();
		$category_labels_arr = array(1 => 'Basic',2 => 'Intermediate',3 => 'Urgent');
		$results = DB::select( DB::raw("SELECT COUNT(*) AS total,RecCategory FROM gpg_job_doc,gpg_job
		WHERE RecCategory != ''
		AND gpg_job.id = gpg_job_doc.gpg_job_id
		 AND (SELECT COUNT(gpg_job_id) FROM gpg_job_doc WHERE TYPE = 'R' AND IFNULL(filename,'') <> '' AND gpg_job_id = gpg_job.id LIMIT 0,1) > 0 AND (SELECT COUNT(gpg_job_id) FROM gpg_job_doc WHERE TYPE = 'Q' AND IFNULL(filename,'') <> '' AND gpg_job_id = gpg_job.id LIMIT 0,1)=0
		AND gpg_job_doc.type = 'R' GROUP BY RecCategory") );
		
		foreach ($results as $key => $value) {
			$service_job_recommendation_categories_arr[] = array('cat' => $value->RecCategory, 'cat_label'=> $category_labels_arr[$value->RecCategory],'total' => $value->total); 
		}

		$service_job_recommendation_categories = json_encode($service_job_recommendation_categories_arr);
        return $service_job_recommendation_categories;
	}

	protected function service_job_employee_recommendations(){
		$service_job_employee_recommendations_arr = array();
		
		$results = DB::select( DB::raw("SELECT gpg_employee.name, gpg_employee.id,COUNT(*) AS assigned FROM gpg_job,gpg_job_doc,gpg_employee WHERE  GPG_asign_employee_id = gpg_employee.id AND gpg_job_doc.type = 'R'  AND (SELECT COUNT(gpg_job_id) FROM gpg_job_doc WHERE TYPE = 'R' AND IFNULL(filename,'') <> '' AND gpg_job_id = gpg_job.id LIMIT 0,1) > 0
		AND gpg_job.id = gpg_job_doc.gpg_job_id
		AND (SELECT COUNT(gpg_job_id) FROM gpg_job_doc WHERE TYPE = 'Q' AND IFNULL(filename,'') <> '' AND gpg_job_id = gpg_job.id LIMIT 0,1)=0  GROUP BY gpg_employee.id HAVING assigned>0 ORDER BY assigned DESC,gpg_employee.name ASC") );
		foreach ($results as $key => $value) {
			$service_job_employee_recommendations_arr[] = array('name' => $value->name,'assigned' => $value->assigned); 			
		}

		$service_job_employee_recommendations = json_encode($service_job_employee_recommendations_arr);
        return $service_job_employee_recommendations;
	}

	protected function electrical_jobs_listing_filters(){
		$electrical_jobs_listing_filters_arr = array();
		
		$results1 = DB::select( DB::raw("select count(id) as cnt, sum(if(fixed_price>0,fixed_price,if(nte>0,nte,if(sub_nte>0,sub_nte,(if(contract_amount>0,contract_amount,0)))))) as contractAmt from gpg_job where job_num like 'GPG%' and GPG_job_type_id='5' AND complete = '0'") );
		$electrical_jobs_listing_filters_arr[] = array('value' => $results1[0]->cnt,'label' => 'Jobs Not Completed','amount' => number_format($results1[0]->contractAmt,2) ); 
		$results2 = DB::select( DB::raw("select count(id) as cnt, sum(if(fixed_price>0,fixed_price,if(nte>0,nte,if(sub_nte>0,sub_nte,(if(contract_amount>0,contract_amount,0)))))) as contractAmt from gpg_job where job_num like 'GPG%' and GPG_job_type_id='5' AND (complete = '0' AND (select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id limit 0,1))") );
		$electrical_jobs_listing_filters_arr[] = array('value' => $results2[0]->cnt,'label' => 'Have been Invoiced but Not Completed','amount' => number_format($results2[0]->contractAmt,2) ); 
		$results3 = DB::select( DB::raw("select count(id) as cnt, sum(if(fixed_price>0,fixed_price,if(nte>0,nte,if(sub_nte>0,sub_nte,(if(contract_amount>0,contract_amount,0)))))) as contractAmt from gpg_job where job_num like 'GPG%' and GPG_job_type_id='5' AND created_on >= '".date('Y-m-d',strtotime('01/01/2010'))." 00:00:00' AND created_on <= '".date('Y-m-d')." 23:59:59' AND (complete = '1' AND if((select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id limit 0,1)>0,0,1))") );
		$electrical_jobs_listing_filters_arr[] = array('value' => $results3[0]->cnt,'label' => 'Completed but Have Not been Invoiced (Created Date: '.date('m/d/Y',strtotime('01/01/2010')).' to '.date('m/d/Y').')','amount' => number_format($results3[0]->contractAmt,2) ); 

		$electrical_jobs_listing_filters = json_encode($electrical_jobs_listing_filters_arr);
        return $electrical_jobs_listing_filters;
	}

	protected function service_jobs_listing_filters(){
		$service_jobs_listing_filters_arr = array();
		
		$results1 = DB::select( DB::raw("select count(id) as cnt, sum(ifnull(contract_amount,0)) as contractAmt from gpg_job where GPG_job_type_id='4' and job_num not like 'RNT%' AND date_job_scheduled_for >= '". date('Y-m-d',strtotime('01/01/2011'))."' AND date_job_scheduled_for <= '".date('Y-m-d')."' AND complete = '0' ") );
		$service_jobs_listing_filters_arr[] = array('value' => $results1[0]->cnt,'label' => 'Jobs Not Completed (Date Parts Scheduled: '.date('m/d/Y',strtotime('01/01/2010')).' to '.date('m/d/Y').')','amount' => number_format($results1[0]->contractAmt,2) ); 
		$results2 = DB::select( DB::raw("select count(id) as cnt, sum(ifnull(contract_amount,0)) as contractAmt from gpg_job where GPG_job_type_id='4' and job_num not like 'RNT%' AND date_parts_Recieved >= '". date('Y-m-d',strtotime('01/01/2011'))."' AND date_parts_Recieved <= '".date('Y-m-d')."' AND complete = '0'") );
		$service_jobs_listing_filters_arr[] = array('value' => $results2[0]->cnt,'label' => 'Jobs Not Completed (Date Parts Received: '.date('m/d/Y',strtotime('01/01/2010')).' to '.date('m/d/Y').')','amount' => number_format($results2[0]->contractAmt,2) ); 
		$results3 = DB::select( DB::raw("select count(id) as cnt, sum(ifnull(contract_amount,0)) as contractAmt from gpg_job where GPG_job_type_id='4' and job_num not like 'RNT%' AND (select count(gpg_job_id) from gpg_job_doc where type = 'R' and ifnull(filename,'') <> '' and gpg_job_id = gpg_job.id limit 0,1) > 0 AND (select count(gpg_job_id) from gpg_job_doc where type = 'Q' and ifnull(filename,'') <> '' and gpg_job_id = gpg_job.id limit 0,1)=0") );
		$service_jobs_listing_filters_arr[] = array('value' => $results3[0]->cnt,'label' => 'Jobs have Recommendations with no Quote','amount' => number_format($results3[0]->contractAmt,2) ); 
		$results4 = DB::select( DB::raw("select count(id) as cnt, sum(ifnull(contract_amount,0)) as contractAmt from gpg_job where GPG_job_type_id='4' and job_num not like 'RNT%' AND created_on >= '".date('Y-m-d',strtotime('01/01/2010'))." 00:00:00' AND created_on <= '".date('Y-m-d')." 23:59:59' AND (complete = '1' AND if((select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id limit 0,1)>0,0,1))") );
		$service_jobs_listing_filters_arr[] = array('value' => $results4[0]->cnt,'label' => 'Completed but Have Not been Invoiced (Start Date: '.date('m/d/Y',strtotime('01/01/2010')).' to '.date('m/d/Y').')','amount' => number_format($results4[0]->contractAmt,2) ); 
		$results5 = DB::select( DB::raw("select count(id) as cnt, sum(ifnull(contract_amount,0)) as contractAmt from gpg_job where complete = '0' AND GPG_job_type_id='4' AND job_num not like 'RNT%' AND ((job_num in (select b.job_num from gpg_timesheet c , gpg_timesheet_detail b WHERE c.id = b.GPG_timesheet_id and b.job_num=job_num AND DATEDIFF(CURDATE(),c.date)>7)) OR (job_num in (select job_num from gpg_job_cost a where a.job_num=job_num AND DATEDIFF(CURDATE(),a.date)>7)))") );
		$service_jobs_listing_filters_arr[] = array('value' => $results5[0]->cnt,'label' => 'Not Completed And No Labor or Material Activity in Last 7 Days','amount' => number_format($results5[0]->contractAmt,2) ); 

		$service_jobs_listing_filters = json_encode($service_jobs_listing_filters_arr);
        return $service_jobs_listing_filters;
	}

	protected function field_service_work_filters(){
		$field_service_work_filters_arr = array();
		
		$results1 = DB::select( DB::raw("select count(id) as cnt,sum(ifnull(grand_list_total,0)) as fQoueAmt from gpg_field_service_work where ifnull(GPG_attach_job_id,0)=0 AND field_service_work_status = 'Won'") );
		$field_service_work_filters_arr[] = array('value' => $results1[0]->cnt,'label' => 'Won but No Jobs Attached','amount' => number_format($results1[0]->fQoueAmt,2) ); 
		
		$field_service_work_filters = json_encode($field_service_work_filters_arr);
        return $field_service_work_filters;
	}

	protected function rental_invoice_alert(){
		$rental_invoice_alert_arr = array();
		
		$results1 = DB::select( DB::raw("select job_num,schedule_date from gpg_job where job_num like 'RNT%' and curdate()>=ADDDATE(schedule_date,28) and rental_status = 3 order by id") );
		foreach ($results1 as $key => $value) {
			$rental_invoice_alert_arr[] = array('num' => $value->job_num,'date' => $value->schedule_date ); 
		}
		
		$rental_invoice_alert = json_encode($rental_invoice_alert_arr);
        return $rental_invoice_alert;
	}
	public static function small_widgets_arr(){

		$modules = Generic::modules();
		$jobs_selection_array = array(
					'month'	=> date('m'),
					'year'	 => date('Y'),
					'jobs_criteria' => 'created_date'
			);
		$small_widget = array();
		$cus_dashobard = $this->customerDashboard($modules);
		if(is_array($cus_dashobard))
			$small_widget[] = $cus_dashobard;

		$job_dashobard = $this->jobDashboard($modules, 'open', 'small', $jobs_selection_array);
		if(is_array($job_dashobard))
			$small_widget[] = $job_dashobard;
		
		$job_dashobard = $this->jobDashboard($modules,'closed', 'small', $jobs_selection_array);
		if(is_array($job_dashobard))
			$small_widget[] = $job_dashobard;

		$fsw_dashobard = $this->fswDashboard($modules);
		if(is_array($fsw_dashobard))
			$small_widget[] = $fsw_dashobard;

		$params = array('small_widgets'=>$small_widget);
 		return $params;
	}
	public function dashboard()
	{
		 //print_r(Generic::application_constants());die;
		/*echo Config::get('settings._DefaultCurrency');
		die;*/
		$modules = Generic::modules();
		$constants = Generic::application_constants();
		
		if(isset($_REQUEST['m'])){
			$curr_m = $_REQUEST['m'];
		}
		else{
			$curr_m = date('m');
		}
		if(isset($_REQUEST['y'])){
			$curr_y = $_REQUEST['y'];
		}
		else{
			$curr_y = date('Y');
		}
		if(isset($_REQUEST['get_current_month_data'])){
			$curr_m = date('m');
			$curr_y = date('Y');
		}
		elseif(isset($_REQUEST['get_last_month_data'])){
			$n_datetime = strtotime($curr_m.'/01/'.$curr_y." 00:00:00 -1month");
			$curr_m = date('m', $n_datetime);
			$curr_y = date('Y', $n_datetime);
		}
		elseif(isset($_REQUEST['get_next_month_data'])){
			$n_datetime = strtotime($curr_m.'/01/'.$curr_y." 00:00:00 +1month");
			$curr_m = date('m', $n_datetime);
			$curr_y = date('Y', $n_datetime);
		}
		
		if(isset($_REQUEST['general_service_jobs_radio'])){
			$job_criteria = $_REQUEST['general_service_jobs_radio'];
		}
		else{
			$job_criteria = 'created_date';
		}
		$jobs_selection_array = array(
					'month'	=> $curr_m,
					'year'	 => $curr_y,
					'jobs_criteria' => $job_criteria
			);
		$small_widget = array();
		$cus_dashobard = $this->customerDashboard($modules);
		if(is_array($cus_dashobard))
			$small_widget[] = $cus_dashobard;

		$job_dashobard = $this->jobDashboard($modules, 'open', 'small', $jobs_selection_array);
		if(is_array($job_dashobard))
			$small_widget[] = $job_dashobard;
		
		$job_dashobard = $this->jobDashboard($modules,'closed', 'small', $jobs_selection_array);
		if(is_array($job_dashobard))
			$small_widget[] = $job_dashobard;

		$fsw_dashobard = $this->fswDashboard($modules);
		if(is_array($fsw_dashobard))
			$small_widget[] = $fsw_dashobard;
		$small_widget_var = $small_widget;

		//$row_widget = array();
		$jobs_report_panel = array();
		
		$complete_total = 0;
		$jobs_invoiced = 0;
		$jobs_not_invoiced = 0;
		
		$row_widget[] = $this->jobDashboard($modules,'table','detail', $jobs_selection_array);
		if(!empty($row_widget[0])){
			foreach($row_widget[0] as $k => $w){
				if(empty($w[0]))
					$w[0] = 0;
				if(empty($w[1]))
					$w[1] = 0;
				$t_arr = array(
					'title' => $k,
					'added' => $w[0]+$w[1],
					'completed' => $w[1],
					'open' => $w[0],
					'contract' => $w[2],
					'hours' => $w[3]
				);
				$complete_total += $t_arr['completed'];
				array_push($jobs_report_panel, $t_arr);
			}
		}
		else{
			$k= array(0 =>'PM', 1 => 'QT', 2 => 'TC');
			$i=0;
			while ($i<3) {
				$t_arr = array(
					'title' => $k[$i],
					'added' => 0,
					'completed' => 0,
					'open' => 0,
					'contract' => 0,
					'hours' => 0
				);
				$i++;
				$complete_total =0;
				array_push($jobs_report_panel, $t_arr);
			}
		}

		$jobs_summary[] = $this->jobDashboard($modules,'table','summary', $jobs_selection_array);
		if(is_array($jobs_summary)){
			$jobs_invoiced = $jobs_summary[0]['totalInvoiced'];
			$jobs_not_invoiced = $jobs_summary[0]['totalNotInvoiced'];
		}
		//print_r($row_widget[0]);
		
		$month_arr = array();
		for($i = 1; $i <= 12; $i++){
			array_push($month_arr, array(str_pad($i,2,'0',STR_PAD_LEFT),date('F', mktime(0,0,0,$i,1,2000))));
		}
		
		//$small_widget[] = $this->customerDashboard($modules);
		$plotData = array();
		foreach($jobs_report_panel as $thejob){
			$plotData[] = array(
				'name'          => $thejob['title'],
				'added'         => (int)$thejob['added'],
				'completed'     => (int)$thejob['completed'],
				'open_jobs'     => (int)$thejob['open'],
				'open_contract' => (int)$thejob['contract'],
				'allocated_hr'  => ceil($thejob['hours'])
			);
		}
		/*$plotData[] = array(
			'name'          => 'PM',
			'added'         => (int)$addedPM,
			'completed'     => (int)$comPM,
			'open_jobs'     => (int)$notComPM,
			'open_contract' => (int)$notComPMContract,
			'allocated_hr'  => ceil($AllocatedHours)
		);
		$plotData[] = array(
			'name'          => 'QT',
			'added'         => (int)$addedQT,
			'completed'     => (int)$comQT,
			'open_jobs'     => (int)$notComQT,
			'open_contract' => (int)$notComQTContract,
			'allocated_hr'  => ceil($notComQTHour)
		);
		$plotData[] = array(
			'name'          => 'TC',
			'added'         => (int)$addedTC,
			'completed'     => (int)$comTC,
			'open_jobs'     => (int)$notComTC,
			'open_contract' => (int)$notComTCContracts,
			'allocated_hr'  => ceil($notComTCContracts)
		);*/
		
		$plotData = json_encode($plotData);
		
		/*******	Contract Panels		*/
		
		$contract_table = $this->contractsDashboard();
		if(is_array($contract_table)){
			foreach($contract_table as $ctable){
				if($ctable['price'] == 0)
					continue;
				$contract_chartdata[] = array('name'=>$ctable['name'],
											'count' => $ctable['count'],
											'price' => $ctable['price']);
			}
			$contract_chartdata = json_encode($contract_chartdata);
		}
		
		$params = array(
				'left_menu'=>$modules,
				'small_widgets'=>$small_widget, 
				'jobs_report_panel_data'=>$jobs_report_panel,
				'month_array' => $month_arr,
				'plotData'	=> $plotData,
				'_DefaultCurrency'	=> $this->default_currency(),
				'contract_table' => $contract_table,
				'contract_chartdata' => $contract_chartdata,
				'shop_work_open_quotes' => $this->shop_work_open_quotes(),
				'electrical_open_quotes' => $this->electrical_open_quotes(),
				'field_service_work_open_quotes' => $this->field_service_work_open_quotes(),
				'grassivy_jobs_listing_filters' => $this->grassivy_jobs_listing_filters(),
				'shop_work_jobs_listing_filters' => $this->shop_work_jobs_listing_filters(),
				'special_project_jobs_listing_filters' => $this->special_project_jobs_listing_filters(),
				'fsw_report_filters' => $this->fsw_report_filters(),
				'tc_report_filters' => $this->tc_report_filters(),
				'service_job_recommendation_categories' => $this->service_job_recommendation_categories(),
				'service_job_employee_recommendations' => $this->service_job_employee_recommendations(),
				'electrical_jobs_listing_filters' => $this->electrical_jobs_listing_filters(),
				'service_jobs_listing_filters' => $this->service_jobs_listing_filters(),
				'field_service_work_filters' => $this->field_service_work_filters(),
				'rental_invoice_alert' => $this->rental_invoice_alert(),
				'date_vals' => array(
						's_year'	=> date('Y')-20,
						'e_year'	=> date('Y')+20,
						'curr_m'	=> $curr_m,
						'curr_y'	=> $curr_y
					),
				'job_totals' => array(
						'complete_total' 	=> $complete_total,
						'jobs_invoiced'  	 => $jobs_invoiced,
						'jobs_not_invoiced' => $jobs_not_invoiced
					)
			);
		
		return View::make('main/dashboard', $params);
	}


	/**
	* Dashboard functions for Each module are defined below this line
	* Every function uses chechauth to check if the dashboard widget is autorized
	* small means the first row
	* other option will mean detailed view
	*/

	protected function checkauth($module_name, $all_modules,$type="main"){
		if($type=="main"){
			foreach($all_modules[0] as $module_id=>$module_data){
				if($module_data->folder_name==$module_name)
					return true;
			}
			return false;
		}
	}
	
	protected function customerDashboard($modules,$view="small"){
		if(!$this->checkauth('customer', $modules))
		{
			return "Unauthorized";
		}
		$customers = Gpg_customer::where('status','=','A');
		//$active_customers = number_format($customers->count(),2);
		$active_customers = $customers->count();
		return array('icon'=>'fa-user','color'=>'terques','title'=>'Active Customers','value'=>$active_customers,'elementid'=>'actv_cust_count');
	}

	protected function fswDashboard($modules,$view="small"){
		if(!$this->checkauth('job', $modules))
		{
			return "Unauthorized";
		}
		
		$jobs = Gpg_field_service_work::join('gpg_employee','gpg_employee.id','=','GPG_employee_id')
										->where('field_service_work_status','=','Quote')
										->where('gpg_employee.status','=','A');
		//$total_jobs = number_format($jobs->count(),2);
		$total_jobs = $jobs->count();
		return array('icon'=>'fa-bar-chart-o','color'=>'blue','title'=>'FSW Open Quotes','value'=>$total_jobs, 'elementid' => 'fsw_opn_qot');
	}

	protected function jobDashboard($modules,$type="open",$view="small",$search_data=array()){
		$constants = Generic::application_constants();
		
		/*if(!$this->checkauth('job', $modules))
		{
			return "Unauthorized";
		}*/
		$data = array();
		if($view == 'small')
		{
			if($type=="open"){
				$jobs = Gpg_job::where('closed','=','0');
				$data['icon']='fa-tags';
				$data['color']='red';
				$data['title']='Jobs Opened';
				$data['elementid']='jobs_open_count';
			}
			elseif($type=="closed"){
				$jobs = Gpg_job::where('closed','=','1');
				$data['icon']='fa-shopping-cart';
				$data['color']='yellow';
				$data['title']='Jobs Closed';
				$data['elementid'] = 'jobs_closed_count';
			}
			//$jobs = number_format($jobs->count(),2);
			$jobs = $jobs->count();
			$data['value']=$jobs;
		}
		elseif($view=='detail'){
			if($type=='table'){
				if(sizeof($search_data)==0){
					$month = date('F',time());
					$year = date('Y',time());
					$jobs_criteria = "created_date"; // or scheduled_date
				}
				else
				{
					$month = date('F',mktime(0,0,0,$search_data['month'],1,2000));
					$year = $search_data['year'];
					$jobs_criteria = $search_data['jobs_criteria'];
				}
				if($jobs_criteria == 'created_date'){
					$flag = 'created_on';
					$start_date = date(Config::get('settings.DB_DT_FORMAT'),strtotime($month.' 01,'.$year." 00:00:00"));
					$last_date = date('t',strtotime($month.' 01,'.$year." 00:00:00 -1year"));
					$end_date   = date(Config::get('settings.DB_DT_FORMAT'),strtotime($month.' '.$last_date.','.$year." 23:59:59"));
				}
				else{
					$flag = 'schedule_date';
					$start_date = date(Config::get('settings.DB_DATE_FORMAT'),strtotime($month.' 01,'.$year.""));
					$last_date = date('t',strtotime($month.' 01,'.$year." -1year"));
					$end_date   = date(Config::get('settings.DB_DATE_FORMAT'),strtotime($month.' '.$last_date.','.$year.""));
				}
				
				$result = Gpg_job::select(DB::raw('count(*)  AS cnt, substr(job_num,1,2) AS j_num,complete'))
									->where(function($query){
										$query
										->orWhere('job_num','LIKE','PM%')
										->orWhere('job_num','LIKE','TC%')
										->orWhere('job_num','LIKE','QT%');
									})
									->Where($flag, '>=',$start_date)
									->where($flag, '<=',$end_date)
									->groupBy(DB::raw("substr(job_num,1,2)"))
									->groupBy('complete')
									->get();
				
				
				foreach($result as $row){
					$data[$row->j_num][$row->complete] = $row->cnt;
				}
				
				/*** Contracts calculation	**/
				
				//$notComPMQry =	mysql_query("SELECT COUNT(id) as total_jobs , COUNT(DISTINCT(contract_number)) AS c_total, task FROM gpg_job WHERE job_num LIKE 'PM%' AND complete = '0' $queryPart2 GROUP BY task ");
				
				$res_1 = Gpg_job::select(DB::raw('COUNT(id) as total_jobs , COUNT(DISTINCT(contract_number)) AS c_total, task'))
								->where($flag, '>=',$start_date)
								->where($flag, '<=',$end_date)
								->where('job_num','LIKE','PM%')
								->where('complete','=','0')
								->groupBy('task')
								->get();
				
				$AllocatedPmHours = explode(',', $constants['_AllocatedPmHours']);
				$AllocatedHoursArray = array( );
				foreach ( $AllocatedPmHours as $row ) {
					$AllocatedPmHoursTemp = explode('~', $row);
					$AllocatedHoursArray[strtolower($AllocatedPmHoursTemp[0])] = strip_tags(strtolower($AllocatedPmHoursTemp[1]));
				}
				
				$notComPM = 0;
				$AllocatedHours = 0;
				$contractsPM = 0;
				foreach($res_1 as $r_1){
					
					if(empty($r_1['task'])){
						continue;
					}
					
					$notComPM += $r_1['total_jobs'];
					$contractsPM += $r_1['c_total'];
					foreach($AllocatedHoursArray as $k=>$arr){
						
						if(strtolower($r_1['task']) == $k) {
							//echo $r_1['task'] .' = '. $r_1['total_jobs'] .' X '. $arr.'<br />'; 
							$AllocatedHours += $r_1['total_jobs'] * $arr; 
						}
					}
					
				}
				
				$notComQT = 0;
				$notCompQTcontract = 0;
				
				//$notComQTarr=mysql_fetch_assoc(mysql_query("select count(id) as totalQT,COUNT(DISTINCT(contract_number)) as c_total from gpg_job where job_num like 'QT%' and complete = '0' $queryPart2 "));
				
				$res_2 = Gpg_job::select(DB::raw('COUNT(id) as totalQT , COUNT(DISTINCT(contract_number)) as c_total '))
								->where($flag, '>=',$start_date)
								->where($flag, '<=',$end_date)
								->where('job_num','LIKE','QT%')
								->where('complete','=','0')
								->get();
				foreach($res_2 as $r_2){
					$notCompQTcontract = $r_2['c_total'];
				}
				
				$notComQTHour = 0;
				/*
				$queryPart3="and gpg_job.created_on >= '".date(DB_DATE_FORMAT." 00:00:00",strtotime($SDate2))."' AND gpg_job.created_on <= '".date(DB_DATE_FORMAT." 23:59:59",strtotime($EDate2))."' ";	
				
				$notComQTHour=mysql_result(mysql_query("SELECT
					   (SUM(shop)+SUM(labor)+SUM(lbt)+SUM(ot)+SUM(sub_con))  AS toal_hour
					   FROM gpg_field_service_work_labor,gpg_field_service_work,gpg_job
					   WHERE 
						gpg_field_service_work_labor.gpg_field_service_work_id = gpg_field_service_work.id
					AND gpg_field_service_work.GPG_attach_job_num = gpg_job.job_num
					AND (gpg_field_service_work_labor.type = 'A' OR gpg_field_service_work_labor.type = 'S')
					AND gpg_job.job_num LIKE 'QT%'
					AND gpg_job.complete = '0' $queryPart3"),0,0);
				*/
				
				
				$res_com = DB::table('gpg_field_service_work')
							->join('gpg_field_service_work_labor','gpg_field_service_work_labor.gpg_field_service_work_id','=','gpg_field_service_work.id')
							->join('gpg_job','gpg_job.job_num','=','gpg_field_service_work.GPG_attach_job_num')
							//->where('gpg_field_service_work.id','=','gpg_field_service_work_labor.gpg_field_service_work_id')
							//->where('gpg_field_service_work.GPG_attach_job_num','=','gpg_job.job_num')
							->where(function($query){
										$query
										->orWhere('gpg_field_service_work_labor.type','=','A')
										->orWhere('gpg_field_service_work_labor.type','=','S');
									})
							->where('gpg_job.job_num','LIKE','QT%')
							->where('gpg_job.complete','=','0')
							->where('gpg_job.'.$flag, '>=',$start_date)
							->where('gpg_job.'.$flag, '<=',$end_date)
							->select(DB::raw('(SUM(shop)+SUM(labor)+SUM(lbt)+SUM(ot)+SUM(sub_con))  AS toal_hour'))
							->get();
				foreach($res_com as $r){
					$notComQTHour = $r->toal_hour;
				}
				
				$notComTC = 0;
				$notComTCcontract = 0;
				//$notComTCarr=mysql_fetch_assoc(mysql_query("select count(id) as j_total,COUNT(DISTINCT(contract_number)) as c_total from gpg_job where job_num like 'TC%' and complete = '0' $queryPart2 "));
				
				$res_3 = Gpg_job::select(DB::raw('COUNT(id) as j_total , COUNT(DISTINCT(contract_number)) as c_total '))
								->where($flag, '>=',$start_date)
								->where($flag, '<=',$end_date)
								->where('job_num','LIKE','TC%')
								->where('complete','=','0')
								->get();
								
				foreach($res_3 as $r_3){
					$notComTCcontract = $r_3['c_total'];
				}
				
				foreach($data as $nk=>$nval){
					if($nk=='PM'){
						$data[$nk][2] = $contractsPM;
						$data[$nk][3] = $AllocatedHours;
					}
					if($nk=='QT'){
						$data[$nk][2] = $notCompQTcontract;
						$data[$nk][3] = number_format($notComQTHour,2);
					}
					if($nk=='TC'){
						$data[$nk][2] = $notComTCcontract;
						$data[$nk][3] = '-';
					}
				}
				
				//var_dump($data);
				//exit;
			}
		}
		elseif($view == 'summary'){
			//$totalInvoiced= mysql_result(mysql_query("select count(id) from gpg_job where (select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id limit 0,1) and (job_num like 'PM%' or job_num like 'QT%' or job_num like 'TC%') $queryPart2 "),0,0);
			if(sizeof($search_data)==0){
				$month = date('F',time());
				$year = date('Y',time());
				$jobs_criteria = "created_date"; // or scheduled_date
			}
			else
			{
				$month = date('F',mktime(0,0,0,$search_data['month'],1,2000));
				$year = $search_data['year'];
				$jobs_criteria = $search_data['jobs_criteria'];
			}
			if($jobs_criteria == 'created_date'){
				$flag = 'created_on';
				$start_date = date(Config::get('settings.DB_DT_FORMAT'),strtotime($month.' 01,'.$year." 00:00:00"));
				$last_date = date('t',strtotime($month.' 01,'.$year." 00:00:00 -1year"));
				$end_date   = date(Config::get('settings.DB_DT_FORMAT'),strtotime($month.' '.$last_date.','.$year." 23:59:59"));
			}
			else{
				$flag = 'schedule_date';
				$start_date = date(Config::get('settings.DB_DATE_FORMAT'),strtotime($month.' 01,'.$year.""));
				$last_date = date('t',strtotime($month.' 01,'.$year." -1year"));
				$end_date   = date(Config::get('settings.DB_DATE_FORMAT'),strtotime($month.' '.$last_date.','.$year.""));
			}
			
			$res_inv = Gpg_job::select(DB::raw('COUNT(id) as inv_count'))
								->whereExists(function($query)
								{
									$query->select(DB::raw('gpg_job_id'))
										  ->from('gpg_job_invoice_info')
										  ->whereRaw('gpg_job_invoice_info.gpg_job_id = gpg_job.id');
								})
								->where(function($query){
									$query
									->orWhere('job_num','LIKE','PM%')
									->orWhere('job_num','LIKE','TC%')
									->orWhere('job_num','LIKE','QT%');
								})
								->where($flag, '>=',$start_date)
								->where($flag, '<=',$end_date)
								->get();
			foreach($res_inv as $inv){
				$data['totalInvoiced'] = $inv['inv_count'];
			}
			
			//$totalNotInvoiced= mysql_result(mysql_query("select count(id) from gpg_job where  if((select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id  limit 0,1)>0,0,1) AND (job_num like 'PM%' or job_num like 'QT%' or job_num like 'TC%') $queryPart2 "),0,0);
			
			$res_notinv = Gpg_job::select(DB::raw('COUNT(id) as notinv_count'))
								->whereNotExists(function($query)
								{
									$query->select(DB::raw('gpg_job_id'))
										  ->from('gpg_job_invoice_info')
										  ->whereRaw('gpg_job_invoice_info.gpg_job_id = gpg_job.id');
								})
								->where(function($query){
									$query
									->orWhere('job_num','LIKE','PM%')
									->orWhere('job_num','LIKE','TC%')
									->orWhere('job_num','LIKE','QT%');
								})
								->where($flag, '>=',$start_date)
								->where($flag, '<=',$end_date)
								->get();
			foreach($res_notinv as $inv){
				$data['totalNotInvoiced'] = $inv['notinv_count'];
			}
			
		}
		return $data;
	}
	
	protected function contractsDashboard($view="table"){
		//Gpg_contract
		//Gpg_employee
		
		$constants = Generic::application_constants();
		
		/*$contracts = Gpg_contract::select(DB::raw())
								-get();*/
		$data = array();
		$contract_qry = DB::select("SELECT
							  (SELECT
								 NAME
							   FROM gpg_employee
							   WHERE id = GPG_employee_id) AS emp_name,
							  IFNULL(GPG_employee_id,0) AS emp_id,
							  COUNT(DISTINCT(IF(LOCATE(':',job_num)>0,SUBSTRING(job_num,1,5),job_num))) AS cnt,
							  COUNT(*)        AS cnt_all,
							  SUM(
								(IFNULL(pm_adjust,0)+IFNULL(pm_charges,0))*IFNULL(pm_visits,0) +
								((IFNULL(annual_adjust,0)+IFNULL(annual_charges,0))*IFNULL(annual_visits,0)) +
								(SELECT
									  IFNULL(SUM(IF(subtract<>1,IFNULL(eqp_qty,0)*IFNULL(eqp_rate,0)+
									  IFNULL(labor_qty,0)*IFNULL(labor_rate,0),0)),0)
							
									FROM gpg_consum_contract_matrix_load_bank
									WHERE gpg_consum_contract_id = gpg_consum_contract.id)
							  )AS f_sum,
								GROUP_CONCAT(job_num) AS job_nums_all
							FROM gpg_consum_contract
							GROUP BY emp_id
							ORDER BY emp_name")
							;
		foreach($contract_qry as $cdata){
			
			$data[] = array(
					'name' 	=> $cdata->emp_name,
					'count'	 => $cdata->cnt_all,
					'f_sum'	   => $constants['_DefaultCurrency'].number_format($cdata->f_sum,2),
					'price'	   => $cdata->f_sum
				);
		}
		//var_dump($data);
		//exit;
		return $data;
		
	}
	
	public function  dashboard_gen_sev_job_panel_data($mon, $yer, $flag, $selection = '', $usage = 'table'){
		//echo "in job function <br>";
		//echo $mon." - ".$yer." - ".$flag;
		
		if($selection == 'get_current_month_data'){
			$mon = date('m');
			$yer = date('Y');
		}
		elseif($selection == 'get_last_month_data'){
			$n_datetime = strtotime($mon.'/01/'.$yer." 00:00:00 -1month");
			$mon = date('m', $n_datetime);
			$yer = date('Y', $n_datetime);
		}
		elseif($selection == 'get_next_month_data'){
			$n_datetime = strtotime($mon.'/01/'.$yer." 00:00:00 +1month");
			$mon = date('m', $n_datetime);
			$yer = date('Y', $n_datetime);
		}
		else{
			
		}
		
		$complete_total = 0;
		$jobs_invoiced = 0;
		$jobs_not_invoiced = 0;
		$jobs_report_panel = array();
		$modules = Generic::modules();
		
		$jobs_selection_array = array(
				'month'	=> $mon,
				'year'	 => $yer,
				'jobs_criteria' => $flag
		);
			
		$row_widget[] = $this->jobDashboard($modules,'table','detail', $jobs_selection_array);
		if(!empty($row_widget[0])){
			foreach($row_widget[0] as $k => $w){
				if(empty($w[0]))
					$w[0] = 0;
				if(empty($w[1]))
					$w[1] = 0;
				$t_arr = array(
					'title' => $k,
					'added' => $w[0]+$w[1],
					'completed' => $w[1],
					'open' => $w[0],
					'contract' => $w[2],
					'hours' => $w[3]
				);
				$complete_total += $t_arr['completed'];
				array_push($jobs_report_panel, $t_arr);
			}
		}
		else{
			$k= array(0 =>'PM', 1 => 'QT', 2 => 'TC');
			$i=0;
			while ($i<3) {
				$t_arr = array(
					'title' => $k[$i],
					'added' => 0,
					'completed' => 0,
					'open' => 0,
					'contract' => 0,
					'hours' => 0
				);
				$i++;
				$complete_total =0;
				array_push($jobs_report_panel, $t_arr);
			}

		}
		
		$jobs_summary[] = $this->jobDashboard($modules,'table','summary', $jobs_selection_array);
		if(is_array($jobs_summary)){
			$jobs_invoiced = $jobs_summary[0]['totalInvoiced'];
			$jobs_not_invoiced = $jobs_summary[0]['totalNotInvoiced'];
		}
		
		if($usage == 'table'){
			$params = array(
				'jobs_report_panel_data'=>$jobs_report_panel,
				'job_totals' => array(
						'complete_total' 	=> $complete_total,
						'jobs_invoiced'  	 => $jobs_invoiced,
						'jobs_not_invoiced' => $jobs_not_invoiced
					),
				'selection_array' => $jobs_selection_array
			);
			return View::make('main/dashboard_gen_sev_job_panel_view', $params);
		}
		else{
			$plotData = array();
			foreach($jobs_report_panel as $thejob){
				$plotData[] = array(
					'name'          => $thejob['title'],
					'added'         => (int)$thejob['added'],
					'completed'     => (int)$thejob['completed'],
					'open_jobs'     => (int)$thejob['open'],
					'open_contract' => (int)$thejob['contract'],
					'allocated_hr'  => ceil($thejob['hours'])
				);
			}
						
			$plotData = json_encode($plotData);
			echo $plotData;
		}
	}
	
}
