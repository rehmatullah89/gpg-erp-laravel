<?php

class CustomerController extends BaseController {

    public static $UserStatus = array("A" => "ACTIVE","B" => "BLOCKED") ;	
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
                
	public function dashboardBox(){
		echo "hi";
	}

	public function showWelcome()
	{
		return View::make('hello');
	}
        
        public function index(){
           
         
           
            $modules = Generic::modules();
            $page = Input::get('page', 1);
            $data = $this->getByPage($page, 100);
            $query_data = Paginator::make($data->items, $data->totalItems, 100);
            
            $task_types = array();
           
             
            
            
            $params = array('left_menu' => $modules, 'query_data'=>$query_data,'_DefaultCurrency'=> $this->default_currency());
            
            return View::make('customers.index', $params);
                
        }
        
        public function getByPage($page = 1, $limit = 100){
            
            $results = new \StdClass;
            $results->page = $page;
            $results->limit = $limit;
            $results->totalItems = 0;
            $results->items = array();
            $items_arr = array();
            $start = $limit * ($page - 1);
            
            $_DB_DATE_FORMAT = $this->default_date_format();
            
            if (Input::get("del")==1) {
                $id =Input::get('id');
                   $getImage = mysql_fetch_array(mysql_query("select * from gpg_customer where id = '$id'"));

                   $del_message = mysql_query("DELETE FROM gpg_customer where id = '$id'");
                if ($del_message){
                   @unlink($path."images/memberpic/".$getImage['pic']);
                   $_REQUEST['MSG_STR']="Message : Customer is deleted successfully";
                } 
             }

             $SDate =Input::get("SDate");
             $EDate =Input::get("EDate");
             $SDate2 =Input::get("SDate2");
             $EDate2 =Input::get("EDate2");
             $Filter =Input::get("Filter");
             $FVal =Input::get("FVal");
             $language =Input::get("language");
             $status =Input::get("status");
             $INVSQL = "";
             $INV = ""; // new defined
             $DSQL = ""; //new defined
             $DQ2 = " order by name asc ";
             
             if ($SDate!="" || $EDate!="") {

                 if ($SDate!="" && $EDate =="") {
                       $DSQL.= " AND DATE_FORMAT(created_on,'%Y-%m-%d') = '".date($_DB_DATE_FORMAT,strtotime($SDate))."'" ;    
                 } elseif ($SDate == "" && $EDate != "") {
                       $DSQL.= " AND created_on <= '".date($_DB_DATE_FORMAT." 23:59:59",strtotime($EDate))."'" ;
                     } elseif ($SDate != "" && $EDate != "") {
                       $DSQL.= " AND (created_on >= '".date($_DB_DATE_FORMAT." 00:00:00",strtotime($SDate))."' 
                                 AND created_on <= '".date($_DB_DATE_FORMAT." 23:59:59",strtotime($EDate))."')" ; 
                     }
             }

             if ($SDate2!="" and $EDate2!="") 
             {
                     $INVSQL .=  " AND d.id = (select gpg_job_invoice_info.gpg_job_id from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id= d.id AND gpg_job_invoice_info.invoice_date >= '".date($_DB_DATE_FORMAT,strtotime($SDate2))."' AND  gpg_job_invoice_info.invoice_date <= '".date($_DB_DATE_FORMAT,strtotime($EDate2))."'  order by gpg_job_invoice_info.invoice_date desc limit 0,1)"; 
             $INV .= " AND c.invoice_date >= '".date($_DB_DATE_FORMAT,strtotime($SDate2))."' AND c.invoice_date <= '".date($_DB_DATE_FORMAT,strtotime($EDate2))."' ";
             }
             elseif ($SDate2!="") 
             {
               $INVSQL .=  " AND d.id = (select gpg_job_invoice_info.gpg_job_id from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id= d.id AND gpg_job_invoice_info.invoice_date = '".date($_DB_DATE_FORMAT,strtotime($SDate2))."' order by gpg_job_invoice_info.invoice_date desc limit 0,1)"; 
                $INV .= " AND c.invoice_date = '".date(DB_DATE_FORMAT,strtotime($SDate2))."' ";
               //$INVSQL .= " AND c.invoice_date = '".date(DB_DATE_FORMAT,strtotime($SDate2))."'";
             }

             if ($Filter!="" && ($FVal!="" || $language!="" || $status!="")) {
                if ($Filter !="cus_type"){ 
                 $DSQL.= " AND $Filter like '$FVal%'"; 
                }
                elseif ($Filter =="cus_type")
                {
                    if($status !=""){
                        $DSQL.= " AND $Filter = 'C' AND status = '$status'" ; 
                    } else{
                         $DSQL.= " AND $Filter = 'C'" ; 
                    }
                    
                }
            }
             
           
            
            //echo "select COUNT(a.id) as total_count, (select count(d.id) from gpg_job d where d.GPG_customer_id = a.id $INVSQL ) as t_jobs , (select sum(if(c.invoice_amount<>0,(c.invoice_amount-c.tax_amount),0)) from gpg_job_invoice_info c, gpg_job g where g.id = c.gpg_job_id and g.GPG_customer_id = a.id $INV ) as inv_amt from gpg_customer a WHERE 1 $DSQL $DQ2";exit;
            //$query_count = DB::select( DB::raw('SELECT count(gpg_customer.id) as total_count FROM gpg_customer'));
            
            $query_count = DB::select( DB::raw("select COUNT(a.id) as total_count, (select count(d.id) from gpg_job d where d.GPG_customer_id = a.id $INVSQL ) as t_jobs , (select sum(if(c.invoice_amount<>0,(c.invoice_amount-c.tax_amount),0)) from gpg_job_invoice_info c, gpg_job g where g.id = c.gpg_job_id and g.GPG_customer_id = a.id $INV ) as inv_amt from gpg_customer a WHERE 1 $DSQL $DQ2"));
            

            $results->totalItems = $query_count[0]->total_count;
            
            $query_d = DB::select( DB::raw("select *, (select count(d.id) from gpg_job d where d.GPG_customer_id = a.id $INVSQL ) as t_jobs , (select sum(if(c.invoice_amount<>0,(c.invoice_amount-c.tax_amount),0)) from gpg_job_invoice_info c, gpg_job g where g.id = c.gpg_job_id and g.GPG_customer_id = a.id $INV ) as inv_amt from gpg_customer a WHERE 1 $DSQL $DQ2 limit $start,$limit"));
            
            foreach ($query_d as $key => $value) {
                  foreach ($value as $key1 => $value1) {
                          $items_arr[$key1] = $value1;

                  }

                  $results->items[] = $items_arr;  	
            }
	  
	  return $results;
        
        }
        
        public function getAjaxJobDetailHTML(){
            
            $id = Input::get("id");
            $sdate = Input::get("sdate");
            $edate = Input::get("edate");
            
            $queryPartInvoice = "";
            $INVSQL = "";
            $rangeStr =""; 
            
            if ($sdate!="" and $edate!="") { 
	
                $queryPartInvoice = " AND gpg_job_invoice_info.invoice_date >= '".date(DB_DATE_FORMAT,strtotime($sdate))."' AND gpg_job_invoice_info.invoice_date <= '".date(DB_DATE_FORMAT,strtotime($edate))."' ";
                $INVSQL .= " AND gpg_job.id = (select gpg_job_invoice_info.gpg_job_id from gg_job_invoice_info where gpg_job_invoice_info.gpg_job_id= gpg_job.id AND gpg_job_invoice_info.invoice_date >= '".date(DB_DATE_FORMAT,strtotime($sdate))."' AND  gpg_job_invoice_info.invoice_date <= '".date(DB_DATE_FORMAT,strtotime($edate))."'  order by gpg_job_invoice_info.invoice_date desc limit 0,1)"; 

                //$INVSQL .= " AND invoice_date >= '".date(DB_DATE_FORMAT,strtotime($sdate))."' AND invoice_date <= '".date(DB_DATE_FORMAT,strtotime($edate))."' ";
                $rangeStr = "<br>&nbsp;Inv Date Range: <strong>$sdate - $edate</strong>";
            }
            elseif ($sdate!="") { 
                

                $queryPartInvoice = " AND gpg_job_invoice_info.invoice_date = '".date(DB_DATE_FORMAT,strtotime($sdate))."' "; 	
                $INVSQL .= " AND gpg_job.id = (select gpg_job_invoice_info.gpg_job_id from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id= gpg_job.id AND  gpg_job_invoice_info.invoice_date = '".date(DB_DATE_FORMAT,strtotime($sdate))."' order by gpg_job_invoice_info.invoice_date desc limit 0,1)"; 

                //$INVSQL .= " AND invoice_date = '".date(DB_DATE_FORMAT,strtotime($sdate))."'";
                $rangeStr = "&nbsp;&nbsp;Date: $sdate ";
            }  

            $urow = DB::select( DB::raw("select name from gpg_customer where id = '$id'"));
            
                        

           $jobRs = $query_d = DB::select( DB::raw("select job_num,invoice_date,(select concat(invoice_number,'#~#',sum(invoice_amount),'#~#',invoice_date,'#~#',sum(tax_amount),'#~#',count(id)) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id $queryPartInvoice group by gpg_job_invoice_info.gpg_job_id) as invoice_data from gpg_job where gpg_job.GPG_customer_id = '$id' $INVSQL order by job_num"));
           
           
           
            $params = array('id' => $id,'_DateFormat' => $this->default_date_format() ,'sdate'=>$sdate,'$edate'=> $edate,'jobRs' => $jobRs,'_DefaultCurrency'=> $this->default_currency(),'urow' => $urow,'rangeStr' => $rangeStr);
                    return View::make('customers.ajax_job_details', $params);
                    
            
        }
        
        protected function default_currency(){
           $default_currency = array();
           $default_currency = Generic::application_constants();
           return $default_currency['_DefaultCurrency'];
	}
        
        protected function default_date_format(){
            return Config::get('settings.DB_DATE_FORMAT');
	}
        
        
        public function create(){
            $allInputs = "";
           
           if(isset($_POST) && !empty($_POST)){
               
               $allInputs = Input::except('_token');
               
               Input::flash();
  
                $valid = true;
                
                $name = Input::get("name");
                $login = Input::get("login");
                $pass = Input::get("pass");
                $repass = Input::get("repass");
                $cusType = Input::get("cusType");
                $email_add = Input::get("email_add");
                $phone_no = Input::get("phone_no");
                $address1 = Input::get("address1");
                $address2 = Input::get("address2");
                $city = Input::get("city");
                $state = Input::get("state");
                $zip_code = Input::get("zip_code");
                $attn = Input::get("attn");
                $status = Input::get("status");
                $newpass = Input::get("newpass");
                $id = Input::get("id");
                $action = Input::get("action");
                
                $filename = "";
                
                if($email_add == ""){
                    
                    $valid = false;
                }
                
                if($cusType == 'C'){
                    $rules = array(
                        'email_add' => 'required | between:5,100 | email ',
                        'name' => 'unique:gpg_customer,name',
                        'login' => 'unique:gpg_customer,login',
                        'pass'  => ($cusType == 'C')? 'required|max:20': '',
                        'repass' => ($cusType == 'C')? 'required|max:20|same:pass': '' ,
                    );
                } else {
                    $rules = array(
                        'email_add' => 'required | between:5,100 | email ',
                        'name' => 'unique:gpg_customer,name',
                    );
                }
                
                //$rules = array('gpg_customer' => 'unique:users,email');
                
                $input = Input::all();
                
                $validation = Validator::make($input, $rules);

                if ($validation->fails()){
                    //validation fails to send response with validation errors
                    // print $validator object to see each validation errors and display validation errors in your views
                    return Redirect::to('customers/create')->withErrors($validation);
                }
        
               $data = array(
                   'name' => $name,
                   'email_add' => $email_add,
                   'status' => $status,
                   'address' => $address1,
                   'address2' => $address2,
                   'city' => $city,
                   'state' => $state,
                   'zipcode'=> $zip_code,
                   'phone_no'=> $phone_no,
                   'attn' => $attn,
                   'created_on' => (date('Y-m-d H:is')),
                   'modified_on' => '',
                   'status' => $status,
                   'cus_type'=> $cusType, 
                   'login'=> $login,
                   'pwd'=> md5($pass),
                   'perm' => 'all'
                   
               );
               
               DB::table('gpg_customer')->insert($data);
               
               return Redirect::to('customers/index')->withSuccess('Customer has been created successfully');
               
           }
           
            $modules = Generic::modules();
           
            
            $params = array('left_menu' => $modules);
            
            return View::make('customers.create', $params);
        
        }
        
        /**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		 echo "<pre>";
           print_r($_POST);
        die();
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
            
            $GpgCustomerData = GpgCustomer::find($id);
            
            $modules = Generic::modules();
           
            
            $params = array('left_menu' => $modules);
            
            return View::make('customers.create', compact('GpgCustomerData'),$params);
        
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
            $name = Input::get("name");
            
            $login = "";
            
            
            $pass = Input::get("pass");
            $repass = Input::get("repass");
            $cusType = Input::get("cusType");
            $email_add = Input::get("email_add");
            $phone_no = Input::get("phone_no");
            $address1 = Input::get("address1");
            $address2 = Input::get("address2");
            $city = Input::get("city");
            $state = Input::get("state");
            $zip_code = Input::get("zip_code");
            $attn = Input::get("attn");
            $status = Input::get("status");
            $newpass = Input::get("newpass");
            $action = Input::get("action");
            $old_name = Input::get("old_name");
            $defaultpwd = Input::get("defaultpwd");
            
            
            
            if($cusType == "C"){
                $login = Input::get("login");
            }
            
            
            if($cusType == 'C'){
                
                if($old_name == $name){
                   $rules = array(
                    'email_add' => 'required | between:5,100 | email ',
                    'name' => 'required',
                    'login' => 'required',
                    'pass'  => ($cusType == 'C' && $defaultpwd == 1)? 'required|max:20': '',
                    'repass' => ($cusType == 'C' && $defaultpwd == 1)? 'required|max:20|same:pass': '' ,
                    );
                }else {
                     $rules = array(
                        'email_add' => 'required | between:5,100 | email ',
                        'name' => 'required | unique:gpg_customer,name',
                        'login' => 'unique:gpg_customer,login',
                        'pass'  => ($cusType == 'C' && $defaultpwd == 1)? 'required|max:20': '',
                        'repass' => ($cusType == 'C' && $defaultpwd == 1)? 'required|max:20|same:pass': '' ,
                    );   
                }
                
                
                
            } else {
                if($old_name == $name){
                    $rules = array(
                        'email_add' => 'required | between:5,100 | email ',
                        'name' => 'required',
                    );
                } else {
                    $rules = array(
                        'email_add' => 'required | between:5,100 | email ',
                        'name' => 'unique:gpg_customer,name',
                    );
                }
            }
            
            //$rules = array('gpg_customer' => 'unique:users,email');

            $input = Input::all();

            $validation = Validator::make($input, $rules);

            if ($validation->fails()){
                //validation fails to send response with validation errors
                // print $validator object to see each validation errors and display validation errors in your views
                return Redirect::to('customers/'.$id.'/edit')->withErrors($validation);
            }
                
            
            
            if($pass !=""){
              $pass = md5($pass);
            }
            
            
                
            
            $data = array(
                   'name' => $name,
                   'email_add' => $email_add,
                   'status' => $status,
                   'address' => $address1,
                   'address2' => $address2,
                   'city' => $city,
                   'state' => $state,
                   'zipcode'=> $zip_code,
                   'phone_no'=> $phone_no,
                   'attn' => $attn,
                   'created_on' => (date('Y-m-d H:is')),
                   'modified_on' => '',
                   'status' => $status,
                   'cus_type'=> $cusType, 
                   'login'=> $login,
                   'pwd'=> $pass,
                   'perm' => 'all'
                   
               );
            
            
            DB::table('gpg_customer')
                    ->where('id','=', $id)
                    ->update($data);
            
            
            return Redirect::to('customers/index')->withSuccess('Customer has been updated successfully');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
            DB::table('gpg_job')->where('GPG_customer_id','=',$id)->delete();  
            DB::table('gpg_customer')->where('id','=',$id)->delete();  
            return Redirect::to('customers/index')->withSuccess('Customer has been deleted successfully');
	
	}
        
        public function manageProperty(){
           
          $GpgCustomer = new GpgCustomer;
            
          $managersArray = array('' => 'Select Manager');  
           
          $managersArray +=  DB::table('gpg_customer')
                                    ->where('cus_type','=', 'C')
                                    ->lists('name','id');
            
          
           $modules = Generic::modules();
           
           $params = array('left_menu' => $modules,'managersArray' => $managersArray);
            
           return View::make('customers.manageProperty', compact('$GpgCustomer'),$params);
           
        }
        
        public function getAjaxAreaAsset(){
            //echo "<pre>";
            //print_r($_POST);
            
            $type = Input::get('type');
            $Id = Input::get('Id'); 
            $fg = Input::get('fg'); 
            
            $delMsg = "";  //new defined
            
            switch ($type) {
                case "location":
                 $tblName = "gpg_property_location";
                 $fldName = "location_name";
                 $queryPart = " gpg_customer_id = '$Id' order by location_name";


                break;
                case "area":
                 $tblName = "gpg_property_area";
                 $fldName = "area_name"; 
                 $queryPart = " gpg_property_location_id = '$Id' order by area_name";
                break;
                case "asset":
                 $tblName = "gpg_property_asset";
                 $fldName = "asset_name"; 
                 $queryPart = " gpg_property_area_id = '$Id' order by asset_name";
                break;
            }

            $optionsData = DB::select( DB::raw("select id,$fldName from $tblName WHERE $queryPart"));
            
            $optionsHtml = "";
            
            if(!empty($optionsData)){
                
                foreach($optionsData as $key => $optionObject){
                   $optionsHtml .= '<option value="'.$optionObject->id.'">'.$optionObject->$fldName.'</option>';
                }
            }
            return $optionsHtml;
        }
        
        public function setAjaxLocationAreaAsset(){
            
            $type = Input::get('type');
            $data = Input::get('data');
            $Id = Input::get('Id'); 
            $updateId = Input::get('updateId'); 

            switch ($type) {
                case "location":
                    $tblName = "gpg_property_location";
                    $fldName = "location_name";
                    $queryPart = "gpg_customer_id = '$Id', location_name = '$data' , ";
                    $checkQuery = " AND gpg_customer_id = '$Id'";
                    
                    $dataInsert = array(
                        'location_name' => $data,
                        'gpg_customer_id'=> $Id,
                     );
                     
                break;
                case "area":
                    $tblName = "gpg_property_area";
                    $fldName = "area_name"; 
                    $queryPart = "gpg_property_location_id = '$Id', area_name = '$data' , ";
                    $checkQuery = " AND gpg_property_location_id = '$Id'";
                    
                    $dataInsert = array(
                       
                        'area_name' => $data,
                        'gpg_property_location_id' => $Id,
                     );
                    
                break;
                case "asset":
                    $tblName = "gpg_property_asset";
                    $fldName = "asset_name"; 
                    $queryPart = "gpg_property_area_id = '$Id', asset_name = '$data' , ";
                    $checkQuery = " AND gpg_property_area_id = '$Id'";
                    
                     $dataInsert = array(
                        'asset_name' => $data,
                        'gpg_property_area_id' => $Id,
                     );
                     
                     
                break;
            }
            
            $chkData  = DB::select( DB::raw("select id,$fldName from $tblName WHERE REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE($fldName,'\'',''),'\"',''),',',''),')',''),'(',''),'-',''),'/',''),'.',''),':',''),'?',''),' ','') = '".$data."' $checkQuery "));
            
            if (empty($chkData) && $updateId == "") { 
                
               if ($data!="") {
               
               
               $GetMaxId =  1 + DB::table($tblName)->max('id');
               
               $idArray = array('id' => $GetMaxId,
                                'created_on' => date("Y-m-d H:i:s"),
                                 'modified_on' => date("Y-m-d H:i:s")
                               );
               
               $dataInsert = array_merge($dataInsert,$idArray);
                       
               if(DB::table($tblName)->insert($dataInsert)) {
                   
                    return '<option value="'. $GetMaxId.'">'.stripslashes($data).'</option>';
                    
                 } else {
                     
                    return '<option value="0">BCCCC</option>';
                  }  
               }
            }  else { // Update Case
               
                      DB::table($tblName)
                        ->where('id','=',  $updateId)
                        ->update($dataInsert);
                    
                    return '<option value="'. $updateId.'">'.stripslashes($data).'</option>';
            }
            //mysql_error();
    }
    
    public function deleteAjaxAreaAsset(){
       
        $type = Input::get('type');
        $Id = Input::get('Id'); //parent id or property manager id
        $fg = Input::get('fg'); //deleted id

        $delMsg = "";  //new defined

        switch ($type) {
            case "location":
             $tblName = "gpg_property_location";
            
             DB::table('gpg_property_location')
                     ->where('id','=',$fg)
                     ->where('gpg_customer_id','=',$Id)
                     ->delete();  
             DB::table('gpg_property_area')->where('gpg_property_location_id','=',$Id)->delete();     
             DB::table('gpg_property_asset')->where('gpg_property_area_id','=',$Id)->delete();     

            break;
            case "area":
             DB::table('gpg_property_area')->where('id','=',$fg)->delete();     
             DB::table('gpg_property_asset')->where('gpg_property_area_id','=',$Id)->delete();     
            break;
            case "asset":
             DB::table('gpg_property_asset')->where('id','=',$fg)->delete();     
            break;
        }
            
    }

}