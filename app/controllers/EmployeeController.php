<?php

class EmployeeController extends BaseController {

    public static $UserStatus = array("A" => "ACTIVE","B" => "BLOCKED") ;	
    public static $frontEndArray = array( "timesheet" => "Time Sheet", "sales" => "Sales Tracking", "po" => "Purchase Order" );
   
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
		
	}

	public function showWelcome()
	{
		return View::make('hello');
	}
        
        public function listEmployeeTypes(){
            
            $modules = Generic::modules();
            $page = Input::get('page', 1);
            $data = $this->getEmployeeTypesByPage($page, 100);
            $query_data = Paginator::make($data->items, $data->totalItems, 100);
            
            $task_types = array();
           
             
            
            
            $params = array('left_menu' => $modules, 'query_data'=>$query_data,'_DefaultCurrency'=> $this->default_currency());
            
            return View::make('employees.employeeTypes', $params);
            
        }
        
        public function getEmployeeTypesByPage($page = 1, $limit = 100){
            
            $results = new \StdClass;
            $results->page = $page;
            $results->limit = $limit;
            $results->totalItems = 0;
            $results->items = array();
            $items_arr = array();
            $start = $limit * ($page - 1);
            
            $query_count = DB::select( DB::raw("select count(a.type_id) as total_count from gpg_employee_type a WHERE 1"));
            

            $results->totalItems = $query_count[0]->total_count;
            
            $query_d = DB::select( DB::raw("select * from gpg_employee_type WHERE 1 limit $start,$limit"));
            
            foreach ($query_d as $key => $value) {
                foreach ($value as $key1 => $value1) {
                        $items_arr[$key1] = $value1;
                }
                
                $results->items[] = $items_arr; 
            }
            
             return $results;
        }
        
        public function index(){
           
           
            $modules = Generic::modules();
            $page = Input::get('page', 1);
            $data = $this->getByPage($page, 100);
            $query_data = Paginator::make($data->items, $data->totalItems, 100);
            
            $task_types = array();
           
             
            
            
            $params = array('left_menu' => $modules, 'query_data'=>$query_data,'_DefaultCurrency'=> $this->default_currency());
            
            return View::make('employees/index', $params);
                
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
            
            
            $SDate = Input::get("SDate");
            
            $EDate = Input::get("EDate");
            $Filter = Input::get("Filter");
            $FVal = trim(Input::get("FVal"));
            $language = Input::get("language");
            $status = Input::get("status");
            $DSQL = "";
            $DQ2 = " order by name ";
            
            if ($SDate!="" || $EDate!="") {

                if ($SDate!="" && $EDate =="") {
                      $DSQL.= " AND DATE_FORMAT(created_on,'%Y-%m-%d') = '".date($_DB_DATE_FORMAT ,strtotime($SDate))."'" ;    
                    } elseif ($SDate == "" && $EDate != "") {
                      $DSQL.= " AND created_on <= '".date($_DB_DATE_FORMAT ." 23:59:59",strtotime($EDate))."'" ;
                    } elseif ($SDate != "" && $EDate != "") {
                      $DSQL.= " AND (created_on >= '".date($_DB_DATE_FORMAT ." 00:00:00",strtotime($SDate))."' 
                                AND created_on <= '".date($_DB_DATE_FORMAT ." 23:59:59",strtotime($EDate))."')" ; 
                    }
            }
            
            if ($Filter!="" && ($FVal!="" || $language!="" || $status!="")) {
               if ($Filter !="status" and $Filter!="new_member") 
               $DSQL.= " AND $Filter like '$FVal'"; 
               elseif ($Filter =="status") $DSQL.= " AND $Filter = '$status'"; 
               elseif ($Filter =="new_member") { 
                   $DQ2= " order by created_on desc ";
                    }  
            }
            
            $query_count = DB::select( DB::raw("select count(a.id) as total_count from gpg_employee a WHERE 1 $DSQL $DQ2"));
            

            $results->totalItems = $query_count[0]->total_count;
            
            //echo "select * from gpg_employee WHERE 1 $DSQL $DQ2 limit $start,$limit";
            //exit;
            $query_d = DB::select( DB::raw("select * from gpg_employee WHERE 1 $DSQL $DQ2 limit $start,$limit"));
            
            foreach ($query_d as $key => $value) {
                foreach ($value as $key1 => $value1) {
                        $items_arr[$key1] = $value1;
                }
 
                $items_arr['location'] = "";
                $items_arr['employee_type'] = "";
                $items_arr['employee_rate'] = "";
                $items_arr['employee_rate_start_date'] = "";
                $items_arr['burden'] = "";
                $items_arr['burden_start_date'] = "";
                $items_arr['estimate_commission'] = "";
                $items_arr['sales_commission'] = "";
                $items_arr['contract_sales_commission'] = "";
                $items_arr['commission_start_date'] = "";
                        
                $location = DB::select( DB::raw("select name as location from gpg_employee_location where id = '".$items_arr['gpg_employee_location_id']."'"));
                if(!empty($location)){
                    
                    $items_arr['location'] = $location[0]-> location;
                }
                
                $employee_type = DB::select( DB::raw("select type as employee_type from gpg_employee_type where type_id = '".$items_arr['GPG_employee_type_id']."'"));
                
                if(!empty($employee_type)){
                    
                    $items_arr['employee_type'] = $employee_type[0]-> employee_type;
                }
                
                
                $wage  = DB::select( DB::raw("select rate, start_date from gpg_employee_wage where gpg_employee_id = '".$items_arr['id']."' and type = '".($items_arr['salaried']==1?'s':'h')."' and start_date <= '".date("Y-m-d")."' order by start_date desc limit 0,1"));
                
                if(!empty($wage)){
                    if($wage[0]->rate > 0){
                        
                        $items_arr['employee_rate'] = $wage[0]-> rate;
                        $items_arr['employee_rate_start_date'] = $wage[0]-> start_date;
                        
                    }
                    
                }
                
                $burden = DB::select(DB::raw("select burden, start_date from gpg_employee_burden where gpg_employee_id = '".$items_arr['id']."' and start_date <= '".date("Y-m-d")."' order by start_date desc limit 0,1"));
                
                if(!empty($burden)){
                    
                    $items_arr['burden'] = $burden[0]-> burden;
                    $items_arr['burden_start_date'] = $burden[0]-> start_date;
                    
                }
                
                $commission = DB::select(DB::raw("select estimate_commission, sales_commission, contract_sales_commission, start_date from gpg_employee_commission where gpg_employee_id = '".$items_arr['id']."' and start_date <= '".date("Y-m-d")."' order by start_date desc limit 0,1"));
                
                 if(!empty($commission)){
                     if ($commission[0]->estimate_commission>0 || $commission[0]->sales_commission>0 || $commission[0]->contract_sales_commission>0) {
                        
                         $items_arr['estimate_commission'] = $commission[0]->estimate_commission;
                         $items_arr['sales_commission'] = $commission[0]->sales_commission;
                         $items_arr['contract_sales_commission'] = $commission[0]->contract_sales_commission;
                         $items_arr['commission_start_date'] = $commission[0]->start_date;
                         
                         
                     }
                 }
                 
//                echo "<pre>";
//                print_r($items_arr);
//                exit;
                $results->items[] = $items_arr;  	
            }
	  
	  return $results;
        
        }
        
        public function updateVendorPermissions(){
            
            $id = Input::get("id");
            
            if (Input::get('update')!="") {
                $pSting = "";
                $pChk = Input::get('pChk');

                for ($i=0; $i<count($pChk); $i++) $pSting.=$pChk[$i].",";
                
                $data = array(
                    'perm' => $pSting,
                );
                         
                DB::table('gpg_vendor')
                    ->where('id','=', $id)
                    ->update($data);


                return Redirect::to('employees/index')->withSuccess('Permissions have been recorded successfully');          
            }
            
        }
        public function getAjaxPermissionHTML(){
            
            $id = Input::get("id");
                
            $urow = DB::select( DB::raw("select * from gpg_vendor where id = '$id'"));
            
            if($urow!="") {
                $full_name=$urow[0]->name;
            
                $chkPerm = $urow[0]->perm;

                $prm = explode(",",$chkPerm);

                $perm['bill'] = "Show Billing Info. Panel";
                $perm['jobsite'] = "Show Job Site Info. Panel";
                $perm['other'] = "Show Other Info. Panel";
                $perm['scope'] = "Show Scope of Work Panel";
                $perm['workdone'] = "Show Actual Work Completed Panel";
                $perm['milestones'] = "Show Project Milestones Panel";
                $perm['recommendation'] = "Show Recommendation Panel";
            } 
            
           
           
            $params = array('id' => $id,'_DateFormat' => $this->default_date_format() ,'_DefaultCurrency'=> $this->default_currency(),'urow' => $urow);
            return View::make('employees.ajax_perm_details', $params);
        }
        
        protected function default_currency(){
		$default_currency = array();
        $default_currency = Generic::application_constants();
        return $default_currency['_DefaultCurrency'];
	}
        
        protected function default_date_format(){
            return Config::get('settings.DB_DATE_FORMAT');
	}
        
        public function editEmployee(){
            
            $id = Input::get('id');
            
            if(isset($_POST) && !empty($_POST)){
            
               $allInputs = Input::except('_token');
               
               Input::flash();
  
               $valid = true;
               
                $uname              = Input::get("uname");
                $pwd                = Input::get("pwd");
                $repwd              = Input::get("repwd");
                $realname           = Input::get("realname");
                $location           = Input::get("loc_name");
                $email              = Input::get("email");
                $email_pwd          = Input::get("email_pwd");
                $phone              = Input::get("phone");
                $etype              = Input::get("etype");
                $status             = Input::get("status");
                $regpay             = Input::get("regpay");
                $salaried           = Input::get("salaried");
                $exclude_oh         = Input::get("exclude_oh");
                $grossSal           = Input::get("grossSal");
                $accRate            = Input::get("accRate");
                $DOB                = date("Y-m-d",strtotime(Input::get("DOB")));
                $id                 = Input::get("id");
                $oldpass            = Input::get("oldpass");
                $newpass            = Input::get("newpass");
                $action             = Input::get("action");
                $eFront             = Input::get("eFront");
                $st_sal_type        = Input::get("st_sal_type");
                $hireDate           = date("Y-m-d",strtotime(Input::get("hireDate")));
                $starting_salary    = Input::get("starting_salary");
                $minimum_salary     = Input::get("minimum_salary");
                $higher_salary      = Input::get("higher_salary");
                $tDate              = (Input::get("tDate")!=""?date("Y-m-d",strtotime(Input::get("tDate"))):"");
                $db_name = (@Input::get("db_uname"))? Input::get("db_uname"): "";
               
                if ($status=="A") $tDate="";
                    $filename = "";
              
                if (is_array($eFront)) $eFront = implode (",",$eFront);
                
                
                
                $file = Input::file('photo');
                
                
                
                if($db_name != $uname ){    
                
                    $rules = array(
                        'uname' => 'unique:gpg_employee,login',
                    );
                } else {
                    $rules = array(
                    );
                }
                
                $input = Input::all();
                
                $validation = Validator::make($input, $rules);

                if ($validation->fails()){
                    //validation fails to send response with validation errors
                    // print $validator object to see each validation errors and display validation errors in your views
                    return Redirect::to('employees/addNewEmployee')->withErrors($validation);
                }
                
                if (!empty($file)){
                    
                    if (in_array($file->getClientOriginalExtension(), array('jpeg','jpg'))) {
                            $ext1 = explode(".",$file->getClientOriginalName());
                            $ext2 = end($ext1);
                            $filename = rand(11111,99999)."_employee.".$ext2;
                            $destinationPath = public_path().'/img/';
                            $uploadSuccess = $file->move($destinationPath, $filename);

                    }
                }
                
		
                
                
                
               $data = array(
                    'GPG_employee_type_id' => $etype,
                    'gpg_employee_location_id'=> $location,
                    'login'=> $uname,
                    'pwd'=> md5($pwd),
                    'name'=> $realname,
                    'email'=> $email,
                    'email_pwd'=> $email_pwd,
                    'phone'=> $phone,
                    'created_on'=> 'now()',
                    'modified_on'=> 'now()',
                    'pic'=> $filename,
                    'status'=> $status,
                    'dob'=> $DOB,
                    'reg_pay'=> $regpay,
                    'salaried'=> $salaried,
                    'salary'=> $grossSal,
                    'accural_rate'=> $accRate,
                    'frontend'=> $eFront,
                    'hire_date'=> $hireDate,
                    'starting_salary'=> $starting_salary,
                    //'minimum_salary'=> $minimum_salary,
                    //'higher_salary'=> $higher_salary,
                    'start_salary_type'=> $st_sal_type
               );
               
               
                
                

                if (DB::table('gpg_employee')->where('id','=', $id)->update($data)) {
                    
                    if($starting_salary!="")
                    {

                            
                        
                        $lastInsertedId = $id;
                             
                            $salary_data = array(
                                
                                'gpg_employee_id' => $lastInsertedId,
                                'rate' => $starting_salary,
                                'start_date' => $hireDate,
                                'type' => $st_sal_type,
                                'modified_on' => 'now()'
                                
                                
                            );
                            
                            DB::table('gpg_employee_wage')->where('id','=', $lastInsertedId)->update($salary_data);
                            
                    }
                }
                
               return Redirect::to('employees/index')->withSuccess('Employee upldated successfully'); 
               
           }
           
            $modules = Generic::modules();
           
            $employeeTypesObj = DB::table('gpg_employee_type')->get();
            
            $employeeTypesList = array();
            
            
            $GpgEmployeeData = GpgEmployee::find($id);
            
            //echo "<pre>";
            //print_r($GpgEmployeeData);
            //exit;
            
            foreach($employeeTypesObj as $key => $employeeType){
                 $employeeTypesList[$employeeType->type_id] = $employeeType->type;
            }
            
            $params = array('left_menu' => $modules,'employeeTypesList' => $employeeTypesList);
            
            return View::make('employees.addNewEmployee',compact('GpgEmployeeData'), $params);
            
        }
        public function addNewEmployee(){
            $allInputs = "";
           
           if(isset($_POST) && !empty($_POST)){
            
            
                
               $allInputs = Input::except('_token');
               
               Input::flash();
  
               $valid = true;
               
                $uname              = Input::get("uname");
                $pwd                = Input::get("pwd");
                $repwd              = Input::get("repwd");
                $realname           = Input::get("realname");
                $location           = Input::get("loc_name");
                $email              = Input::get("email");
                $email_pwd          = Input::get("email_pwd");
                $phone              = Input::get("phone");
                $etype              = Input::get("etype");
                $status             = Input::get("status");
                $regpay             = Input::get("regpay");
                $salaried           = Input::get("salaried");
                $exclude_oh         = Input::get("exclude_oh");
                $grossSal           = Input::get("grossSal");
                $accRate            = Input::get("accRate");
                $DOB                = date("Y-m-d",strtotime(Input::get("DOB")));
                $id                 = Input::get("id");
                $oldpass            = Input::get("oldpass");
                $newpass            = Input::get("newpass");
                $action             = Input::get("action");
                $eFront             = Input::get("eFront");
                $st_sal_type        = Input::get("st_sal_type");
                $hireDate           = date("Y-m-d",strtotime(Input::get("hireDate")));
                $starting_salary    = Input::get("starting_salary");
                $minimum_salary     = Input::get("minimum_salary");
                $higher_salary      = Input::get("higher_salary");
                $tDate              = (Input::get("tDate")!=""?date("Y-m-d",strtotime(Input::get("tDate"))):"");
               
                if ($status=="A") $tDate="";
                    $filename = "";
              
                if (is_array($eFront)) $eFront = implode (",",$eFront);
                
                
                
                $file = Input::file('photo');
                
                
                    
                
                $rules = array(
                    'uname' => 'required | unique:gpg_employee,login',
                    'pass'  => 'required|max:20',
                    'repass' => 'required|max:20|same:pass' ,
                );
                
                
                $input = Input::all();
                
                $validation = Validator::make($input, $rules);

                if ($validation->fails()){
                    //validation fails to send response with validation errors
                    // print $validator object to see each validation errors and display validation errors in your views
                    return Redirect::to('employees/addNewEmployee')->withErrors($validation);
                }
                
                if (!empty($file)){
                    
                    if (in_array($file->getClientOriginalExtension(), array('jpeg','jpg'))) {
                            $ext1 = explode(".",$file->getClientOriginalName());
                            $ext2 = end($ext1);
                            $filename = rand(11111,99999)."_employee.".$ext2;
                            $destinationPath = public_path().'/img/';
                            $uploadSuccess = $file->move($destinationPath, $filename);

                    }
                }
                
		
                
                
                
               $data = array(
                    'GPG_employee_type_id' => $etype,
                    'gpg_employee_location_id'=> $location,
                    'login'=> $uname,
                    'pwd'=> md5($pwd),
                    'name'=> $realname,
                    'email'=> $email,
                    'email_pwd'=> $email_pwd,
                    'phone'=> $phone,
                    'created_on'=> 'now()',
                    'modified_on'=> 'now()',
                    'pic'=> $filename,
                    'status'=> $status,
                    'dob'=> $DOB,
                    'reg_pay'=> $regpay,
                    'salaried'=> $salaried,
                    'salary'=> $grossSal,
                    'accural_rate'=> $accRate,
                    'frontend'=> $eFront,
                    'hire_date'=> $hireDate,
                    'starting_salary'=> $starting_salary,
                    //'minimum_salary'=> $minimum_salary,
                    //'higher_salary'=> $higher_salary,
                    'start_salary_type'=> $st_sal_type
               );
               
               
                

                if (DB::table('gpg_employee')->insert($data)) {
                    
                    if($starting_salary!="")
                    {

                            $lastInsertedId = DB::table('gpg_employee')->max('id');
                             
                            $salary_data = array(
                                
                                'gpg_employee_id' => $lastInsertedId,
                                'rate' => $starting_salary,
                                'start_date' => $hireDate,
                                'type' => $st_sal_type,
                                'created_on' => 'now()',
                                'modified_on' => 'now()'
                                
                                
                            );
                            
                            DB::table('gpg_employee_wage')->insert($salary_data);
                    }
                }
                
               return Redirect::to('employees/index')->withSuccess('Employee is created successfully');
               
           }
           
            $modules = Generic::modules();
           
            $employeeTypesObj = DB::table('gpg_employee_type')->get();
            
            $employeeTypesList = array();
            
            foreach($employeeTypesObj as $key => $employeeType){
                 $employeeTypesList[$employeeType->type_id] = $employeeType->type;
            }
            
            $params = array('left_menu' => $modules,'employeeTypesList' => $employeeTypesList);
            
            return View::make('employees.addNewEmployee', $params);
        
        }
        
        public function create(){
            $allInputs = "";
           
           if(isset($_POST) && !empty($_POST)){
               
               $allInputs = Input::except('_token');
               
               Input::flash();
  
                $valid = true;
                
                $cname = Input::get("cname");
                
                
                
                if($cname == ""){
                    
                    $valid = false;
                }
                
                $rules = array(
                    'cname' => 'unique:gpg_employee_type,type',
                );
                
                $input = Input::all();
                
                $validation = Validator::make($input, $rules);

                if ($validation->fails()){
                    //validation fails to send response with validation errors
                    // print $validator object to see each validation errors and display validation errors in your views
                    return Redirect::to('employees/create')->withErrors($validation);
                }
        
               $data = array(
                   'type' => $cname,
                   
               );
               
               DB::table('gpg_employee_type')->insert($data);
               
               return Redirect::to('employees/index')->withSuccess('Employee Type is created successfully');
               
           }
           
            $modules = Generic::modules();
           
            
            $params = array('left_menu' => $modules);
            
            return View::make('employees.create', $params);
        
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
            
            $GpgEmployeeTypeData = GpgEmployeeType::find($id);
            
            $modules = Generic::modules();
           
            
            $params = array('left_menu' => $modules);
            
            return View::make('employees.create', compact('GpgEmployeeTypeData'),$params);
        
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
            $type = Input::get("cname");
            
            $allInputs = Input::except('_token');
               
            Input::flash();
  
            $valid = true;

            $cname = Input::get("cname");
            
            $data = array(
                   'type' => $cname,
                   
            );
            
            
            DB::table('gpg_employee_type')
                    ->where('type_id','=', $id)
                    ->update($data);
            
            return Redirect::to('employees/listEmployeeTypes')->withSuccess('Employee type has been updated successfully');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
            DB::table('gpg_employee_type')->where('type_id','=',$id)->delete();  
            return Redirect::to('employees/listEmployeeTypes')->withSuccess('Employee type has been deleted successfully');
	
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
                 $queryPart = " gpg_vendor_id = '$Id' order by location_name";


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
                    $queryPart = "gpg_vendor_id = '$Id', location_name = '$data' , ";
                    $checkQuery = " AND gpg_vendor_id = '$Id'";
                    
                    $dataInsert = array(
                        'location_name' => $data,
                        'gpg_vendor_id'=> $Id,
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
                     ->where('gpg_vendor_id','=',$Id)
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
    
    public function manageDeductions(){
        $id = Input::get('id'); 
        
        $action = Input::get('action');
        
        $pw_wages_rates_type = array( '1' => "basic hourly rate", '2' => "health and welfare", '3' => "pension", '4' => "vacations", '5' => "training", '6' => "other payments");
        
        if ($action=="u_deductions") {
        
            DB::table('gpg_employee_deduction')->where('gpg_employee_id','=',$id)->delete();  
            $query = 0;
            foreach($pw_wages_rates_type as $key => $val)
            {
                    $ded_val = 0;
                    if(Input::get("deduction_value_".$key))
                    {
                            $ded_val = Input::get("deduction_value_".$key);
                    }
                    

                    $data = array(
                        'gpg_employee_id' => $id, 
                         'pw_wage_rate_type' => $key,
                        'rate' => $ded_val,
                        'created_on' => 'now()',
                        'modified_on' => 'now()',
                     );

                    if(DB::table('gpg_employee_deduction')->insert($data)) {
                       $query = 1;     
                        
                    }
                    
            }
            
            if($query == 1){
               
                return Redirect::to('employees')->withSuccess('Deduction recorded successfully');
            }
		
        } 
      
            $emp_row = DB::select( DB::raw("SELECT * FROM gpg_employee WHERE id = $id"));
            if ( !empty($emp_row) ) {

                $ures = DB::select( DB::raw("select * from gpg_employee_deduction where gpg_employee_id = '$id'"));
                $arrvals = array( );
                
                
                foreach($ures as $key => $val ) {
                    if ( $val->rate != 0 )
                        $arrvals[$val->pw_wage_rate_type] = $val->rate;
                }
                
                $full_name = $emp_row[0]->name;
            }
       

        
        
        $params = array('pw_wages_rates_type' => $pw_wages_rates_type,'full_name' => $full_name,'arrvals' => $arrvals,'id' =>$id );
        return View::make('employees.ajaxManageDeduction', $params);
    }
    
    public function changeEmployeePassword(){
        
        $id = Input::get("id");
        
        $action = Input::get("action");
        
        if ($action=="u_uppass") {
            
            $newpass = Input::get("newpass");
            
            $query = "UPDATE gpg_employee SET  WHERE id = '$id'";
            
            $data = array(
                'pwd' => md5($newpass), 
                'modified_on' => 'now()'
            );
            
            DB::table('gpg_employee')
                    ->where('id','=', $id)
                    ->update($data);
            
            return Redirect::to('employees')->withSuccess('Password changed successfully');
            
         } 
            
        $urow = DB::select( DB::raw("SELECT * FROM gpg_employee WHERE id = $id"));
        
        $full_name ="N/A";
        
        if($urow!="") {
          $full_name=$urow[0]->name;
        } 
        
        $params = array('full_name' => $full_name,'id' =>$id );
        return View::make('employees.ajaxChangePassword', $params);
    }
    
    public function setEmployeeBurden(){
        
        $id = Input::get("id");
        
        $action = Input::get("action");
        
        if ($action=="u_burden") {
            
            $burden = Input::get("burden");
            $startDate = date("Y-m-d",strtotime(Input::get("startDate")));
	
            $query = "INSERT INTO gpg_employee_burden() VALUES()";
            
            $data = array(
                'gpg_employee_id' => $id,
                'burden' => $burden,
                'start_date' => $startDate,
                'created_on' => 'now()',
                'modified_on' => 'now()'
            );
            
             if(DB::table('gpg_employee_burden')->insert($data)) {
                 return Redirect::to('employees')->withSuccess('Burden recorded successfully');
             }
            
         } 
            
        $urow = DB::select( DB::raw("SELECT * FROM gpg_employee WHERE id = $id"));
        
        $query = "select burden, start_date from gpg_employee_burden where gpg_employee_id = '$id' and start_date <= '".date('Y-m-d')."' order by start_date desc limit 0,1";
        
        $burden = DB::select( DB::raw($query ));
        
        $full_name ="N/A";
        
        if($urow!="") {
          $full_name=$urow[0]->name;
        } 
        
        $params = array('full_name' => $full_name,'id' =>$id,'burden' => $burden );
        return View::make('employees.ajaxSetBurden', $params);
        
    }
    
    public function setEmployeeCommission(){
        
        $id = Input::get("id");
        
        $action = Input::get("action");
        
        if ($action=="u_commission") {
            
            $sales_commission = Input::get("sales_commission");
            $estimate_commission = Input::get("estimate_commission");
            $contract_sales_commission = Input::get("contract_sales_commission");
            $startDate = date("Y-m-d",strtotime(Input::get("startDate")));
           
            $data = array(
                'gpg_employee_id' => $id,
                'estimate_commission' => $estimate_commission,
                'start_date' => $startDate,
                'sales_commission' => $sales_commission,
                'contract_sales_commission' => $contract_sales_commission,
                'created_on' => 'now()',
                'modified_on' => 'now()',
            );
            
             if(DB::table('gpg_employee_commission')->insert($data)) {
                 return Redirect::to('employees')->withSuccess('Commission recorded successfully');
             }
            
         } 
            
        $urow = DB::select( DB::raw("SELECT * FROM gpg_employee WHERE id = $id"));
        
        $query = "select estimate_commission, sales_commission, contract_sales_commission,  start_date from gpg_employee_commission where gpg_employee_id = '".$id."' and start_date <= '".date('Y-m-d')."' order by start_date desc limit 0,1";
        
        $commission = DB::select( DB::raw($query ));
        
        $full_name ="N/A";
        
        if($urow!="") {
          $full_name=$urow[0]->name;
        } 
        
        $params = array('full_name' => $full_name,'id' =>$id,'commission' => $commission );
        return View::make('employees.ajaxSetCommission', $params);       
    }
    
    public function setEmployeeRate(){
       
        $id = Input::get("id");
        
        $type = Input::get("type");
        
        $action = Input::get("action");
        
        if ($action=="u_rate") {
            
            $type = Input::get("type");
            $rateSalary = Input::get("rateSalary");
            $startDate = date("Y-m-d",strtotime(Input::get("startDate")));


            $query = "INSERT INTO gpg_employee_wage(gpg_employee_id,rate,start_date,type,created_on, modified_on) VALUES('$id','$rateSalary','$startDate','$type', now(), now())";
	
           
            $data = array(
                'gpg_employee_id' => $id,
                'rate' => $rateSalary,
                'start_date' => $startDate,
                'type' => $type,
                'created_on' => 'now()',
                'modified_on' => 'now()',
            );
            
             if(DB::table('gpg_employee_wage')->insert($data)) {
                 return Redirect::to('employees')->withSuccess('Rate/Salary recorded successfully');
             }
            
         } 
            
        $urow = DB::select( DB::raw("SELECT * FROM gpg_employee WHERE id = $id"));
        
        $query = "select rate, start_date from gpg_employee_wage where gpg_employee_id = '$id' and type = '$type' and start_date <= '".date("Y-m-d")."' order by start_date desc limit 0,1";
        
        $rate = DB::select( DB::raw($query ));
        
        $full_name ="N/A";
        
        if($urow!="") {
          $full_name=$urow[0]->name;
        } 
        
        $params = array('full_name' => $full_name,'id' =>$id,'type' => $type,'rate' => $rate );
        return View::make('employees.ajaxSetRate', $params);   
        
    }
    
    public function viewEmployeeRecord(){
        $id = Input::get("id");
            
        $employee = DB::select( DB::raw("SELECT * FROM gpg_employee WHERE id = $id"));
        
        $params = array('employee' => $employee,'id' =>$id);
        return View::make('employees.ajaxViewRecord', $params);  
    }
    
    public function deleteEmployee(){
        $path = realpath('.');
        
        $id = Input::get("id");
                
        $getImage = DB::select( DB::raw("select * from gpg_employee where id = '$id'"));
        
        DB::table('gpg_job_assigned_hist')->where('GPG_employee_Id','=',$id)->delete();  
        
       
        DB::table('gpg_leaveapp')->where('GPG_employee_id','=',$id)->delete();  
        
        
        DB::delete(DB::raw("DELETE FROM b USING gpg_timesheet AS a,gpg_timesheet_detail AS b where a.id = b.GPG_timesheet_id and a.GPG_employee_Id= '$id'"));
        
        DB::table('gpg_timesheet')->where('GPG_employee_id','=',$id)->delete();  
        
        //DB::update(DB::raw("UPDATE gpg_job set assigned_to = NULL , status = 'N' WHERE assigned_to= '$id'"));

        $del_message = DB::table('gpg_employee')->where('id','=',$id)->delete();  

        if ($del_message){
           @unlink($path."/images/".$getImage[0]->pic);
           
           return Redirect::to('employees')->withSuccess('Employee deleted successfully');
        }
        
        
    }
    
    
    public function employeeWageList(){
        
        $modules = Generic::modules();
        $page = Input::get('page', 1);
        $data = $this->getWageByPage($page, 100);
        $query_data = Paginator::make($data->items, $data->totalItems, 100);

        $task_types = array();

        $params = array('left_menu' => $modules, 'query_data'=>$query_data,'_DefaultCurrency'=> $this->default_currency());

        return View::make('employees.wageList', $params);
    }
    
     public function getWageByPage($page = 1, $limit = 100){
            
        $results = new \StdClass;
        $results->page = $page;
        $results->limit = $limit;
        $results->totalItems = 0;
        $results->items = array();
        $items_arr = array();
        $start = $limit * ($page - 1);

        $_DB_DATE_FORMAT = $this->default_date_format();
        
        $SDate = Input::get("SDate");
        $EDate = Input::get("EDate");
        $Filter = Input::get("Filter");
        $FVal = trim(Input::get("FVal"));
        $language = Input::get("language");
        $status = Input::get("status");
        $FVal_stats = Input::get("FVal_stats");
        $DSQL = "";
        $DQ2 = " order by emp_name, start_date desc ";
        
        if ($SDate!="" || $EDate!="") {

            if ($SDate!="" && $EDate =="") {
                  $DSQL.= " AND DATE_FORMAT(start_date,'%Y-%m-%d') = '".date($_DB_DATE_FORMAT,strtotime($SDate))."'" ;    
                } elseif ($SDate == "" && $EDate != "") {
                  $DSQL.= " AND start_date <= '".date($_DB_DATE_FORMAT." 23:59:59",strtotime($EDate))."'" ;
                } elseif ($SDate != "" && $EDate != "") {
                  $DSQL.= " AND (start_date >= '".date($_DB_DATE_FORMAT." 00:00:00",strtotime($SDate))."' 
                            AND start_date <= '".date($_DB_DATE_FORMAT." 23:59:59",strtotime($EDate))."')" ; 
                }
        }
        if ($Filter!="" && $FVal!="" && $Filter=="emp_name") {
           $DSQL.= " AND (select id from gpg_employee where name like '$FVal') = gpg_employee_id "; 
        }
        elseif($Filter!="" &&  $Filter=="emp_status" && $FVal_stats !="")
        {
                $DSQL.= " AND gpg_employee_id IN (SELECT id FROM gpg_employee WHERE status = '".$FVal_stats."')";
        }
        
        //echo "select count(a.id) as total_count from gpg_employee_wage a WHERE 1 $DSQL";exit;
        $query_count = DB::select( DB::raw("select count(a.id) as total_count from gpg_employee_wage a WHERE 1 $DSQL"));
            

        $results->totalItems = $query_count[0]->total_count;

        //echo "select * from gpg_employee WHERE 1 $DSQL $DQ2 limit $start,$limit";
        //exit;
        $query_d = DB::select( DB::raw("select *, (select name from gpg_employee where id = gpg_employee_id) as emp_name, if(start_date<='".date("Y-m-d")."','current','') as cur from gpg_employee_wage WHERE 1 $DSQL $DQ2 limit $start,$limit"));

        foreach ($query_d as $key => $value) {
            foreach ($value as $key1 => $value1) {
                    $items_arr[$key1] = $value1;
            }
            
            
            $results->items[] = $items_arr;  	
        }

        
      return $results;

    }
    
    public function deleteWageRate(){
        
        $id = Input::get("id");
        
        DB::table('gpg_employee_wage')->where('id','=', $id)->delete();  
        
        return Redirect::to('employees/wageLogList')->withSuccess('Wage rate/salary deleted successfully');
        
    }
    
    public function employeeCommissionList(){
        
        $modules = Generic::modules();
        $page = Input::get('page', 1);
        $data = $this->getComissionByPage($page, 100);
        $query_data = Paginator::make($data->items, $data->totalItems, 100);

        $task_types = array();

        $params = array('left_menu' => $modules, 'query_data'=>$query_data,'_DefaultCurrency'=> $this->default_currency());

        return View::make('employees.commissionLog', $params);
    }
    
     public function getComissionByPage($page = 1, $limit = 100){
            
        $results = new \StdClass;
        $results->page = $page;
        $results->limit = $limit;
        $results->totalItems = 0;
        $results->items = array();
        $items_arr = array();
        $start = $limit * ($page - 1);

        $_DB_DATE_FORMAT = $this->default_date_format();
        
        $SDate = Input::get("SDate");
        $EDate = Input::get("EDate");
        $Filter = Input::get("Filter");
        $FVal = trim(Input::get("FVal"));
        $language = Input::get("language");
        $status = Input::get("status");
        $DSQL = "";
        $DQ2 = " order by emp_name, start_date desc ";
        
        if ($SDate!="" || $EDate!="") {

            if ($SDate!="" && $EDate =="") {
                  $DSQL.= " AND DATE_FORMAT(start_date,'%Y-%m-%d') = '".date($_DB_DATE_FORMAT,strtotime($SDate))."'" ;    
                } elseif ($SDate == "" && $EDate != "") {
                  $DSQL.= " AND start_date <= '".date($_DB_DATE_FORMAT." 23:59:59",strtotime($EDate))."'" ;
                } elseif ($SDate != "" && $EDate != "") {
                  $DSQL.= " AND (start_date >= '".date($_DB_DATE_FORMAT." 00:00:00",strtotime($SDate))."' 
                            AND start_date <= '".date($_DB_DATE_FORMAT." 23:59:59",strtotime($EDate))."')" ; 
                }
        }
        if ($Filter!="" && $FVal!="" ) {
           $DSQL.= " AND (select id from gpg_employee where name like '$FVal' limit 1) = gpg_employee_id "; 
        }
       
        $query_count = DB::select( DB::raw("select count(a.id) as total_count from gpg_employee_commission a WHERE 1 $DSQL"));
            

        $results->totalItems = $query_count[0]->total_count;

        $query_d = DB::select( DB::raw("select *, (select name from gpg_employee where id = gpg_employee_id) as emp_name, if(start_date<='".date("Y-m-d")."','current','') as cur from gpg_employee_commission WHERE 1 $DSQL $DQ2 limit $start,$limit"));

        foreach ($query_d as $key => $value) {
            foreach ($value as $key1 => $value1) {
                    $items_arr[$key1] = $value1;
            }
            
            
            $results->items[] = $items_arr;  	
        }

        
      return $results;

    }
    
    public function deleteWageCommission(){
      
       $id = Input::get("id");
        
       DB::table('gpg_employee_commission')->where('id','=', $id)->delete();  
        
       return Redirect::to('employees/wageCommissionList')->withSuccess('Wage commission deleted successfully');
    }
    
    public function employeeBurdunList(){
        
        $modules = Generic::modules();
        $page = Input::get('page', 1);
        $data = $this->getBurdenByPage($page, 100);
        $query_data = Paginator::make($data->items, $data->totalItems, 100);

        $task_types = array();

        $params = array('left_menu' => $modules, 'query_data'=>$query_data,'_DefaultCurrency'=> $this->default_currency());

        return View::make('employees.burdenList', $params);
    }
    
     public function getBurdenByPage($page = 1, $limit = 100){
            
        $results = new \StdClass;
        $results->page = $page;
        $results->limit = $limit;
        $results->totalItems = 0;
        $results->items = array();
        $items_arr = array();
        $start = $limit * ($page - 1);

        $_DB_DATE_FORMAT = $this->default_date_format();
        
        $SDate = Input::get("SDate");
        $EDate = Input::get("EDate");
        $Filter = Input::get("Filter");
        $FVal = trim(Input::get("FVal"));
        $language = Input::get("language");
        $status = Input::get("status");
        $DSQL = "";
        $DQ2 = " order by emp_name, start_date desc ";
        
        if ($SDate!="" || $EDate!="") {

            if ($SDate!="" && $EDate =="") {
                  $DSQL.= " AND DATE_FORMAT(start_date,'%Y-%m-%d') = '".date($_DB_DATE_FORMAT,strtotime($SDate))."'" ;    
                } elseif ($SDate == "" && $EDate != "") {
                  $DSQL.= " AND start_date <= '".date($_DB_DATE_FORMAT." 23:59:59",strtotime($EDate))."'" ;
                } elseif ($SDate != "" && $EDate != "") {
                  $DSQL.= " AND (start_date >= '".date($_DB_DATE_FORMAT." 00:00:00",strtotime($SDate))."' 
                            AND start_date <= '".date($_DB_DATE_FORMAT." 23:59:59",strtotime($EDate))."')" ; 
                }
        }
        if ($Filter!="" && $FVal!="" ) {
           $DSQL.= " AND (select id from gpg_employee where name like '$FVal') = gpg_employee_id "; 
        }
        
        
        $query_count = DB::select( DB::raw("select count(a.id) as total_count from gpg_employee_burden a WHERE 1 $DSQL"));
            

        $results->totalItems = $query_count[0]->total_count;

        $query_d = DB::select( DB::raw("select *, (select name from gpg_employee where id = gpg_employee_id) as emp_name, if(start_date<='".date("Y-m-d")."','current','') as cur from gpg_employee_burden WHERE 1 $DSQL $DQ2 limit $start,$limit"));

        foreach ($query_d as $key => $value) {
            foreach ($value as $key1 => $value1) {
                    $items_arr[$key1] = $value1;
            }
            
            
            $results->items[] = $items_arr;  	
        }

        
      return $results;

    }
    
     public function deleteBurden(){
      
       $id = Input::get("id");
        
       DB::table('gpg_employee_burden')->where('id','=', $id)->delete();  
        
       return Redirect::to('employees/burdunList')->withSuccess('Burden deleted successfully');
    }
    
    
     public function employeeJobHistoryList(){
        
        $modules = Generic::modules();
        $id = Request::segment(3);
         //echo $id;exit; 
        $page = Input::get('page', 1);
        $data = $this->getHistoryByPage($page, 100,$id);
        
        $query_data = Paginator::make($data->items, $data->totalItems, 100);
        
        
       
        $employeeinfo = DB::select( DB::raw("select * from gpg_employee where id = '$id'"));
        
        
        $task_types = array();

        $params = array('left_menu' => $modules,'employeeinfo' => $employeeinfo, 'query_data'=>$query_data,'_DefaultCurrency'=> $this->default_currency());

        return View::make('employees.jobHistoryList', $params);
    }
    
     public function getHistoryByPage($page = 1, $limit = 100,$id){
            
        $results = new \StdClass;
        $results->page = $page;
        $results->limit = $limit;
        $results->totalItems = 0;
        $results->items = array();
        $items_arr = array();
        $start = $limit * ($page - 1);

        $_DB_DATE_FORMAT = $this->default_date_format();
        
        
        $SDate = Input::get("SDate");
        $EDate = Input::get("EDate");
        $Filter = Input::get("Filter");
        $FVal = Input::get("FVal");
        $language = Input::get("language");
        $status = Input::get("status");
        $DSQL = "";
        $DQ2 = " order by date desc ";
        if ($SDate!="" || $EDate!="") {

            if ($SDate!="" && $EDate =="") {
                  $DSQL.= " AND DATE_FORMAT(created_on,'%Y-%m-%d') = '".date(DB_DATE_FORMAT,strtotime($SDate))."'" ;    
                } elseif ($SDate == "" && $EDate != "") {
                  $DSQL.= " AND created_on <= '".date(DB_DATE_FORMAT." 23:59:59",strtotime($EDate))."'" ;
                } elseif ($SDate != "" && $EDate != "") {
                  $DSQL.= " AND (created_on >= '".date(DB_DATE_FORMAT." 00:00:00",strtotime($SDate))."' 
                            AND created_on <= '".date(DB_DATE_FORMAT." 23:59:59",strtotime($EDate))."')" ; 
                }
        }
        if ($Filter!="" && ($FVal!="" || $language!="" || $status!="")) {

           if ($Filter !="status" and $Filter!="new_member") 
           $DSQL.= " AND $Filter like '$FVal'"; 
           elseif ($Filter =="status") $DSQL.= " AND $Filter = '$status'"; 
           elseif ($Filter =="new_member") { 
               $DQ2= " order by created_on desc ";
                }	

        }
        
        
        $query_count = DB::select( DB::raw("select count(a.id) as total_count from gpg_timesheet a,gpg_timesheet_detail b WHERE a.id = b.GPG_timesheet_id  and a.GPG_employee_Id = '$id' $DSQL  group by a.id"));
        $employeeinfo = DB::select( DB::raw("select * from gpg_employee where id = '$id'"));
        
        
        if(!empty($query_count)){
            $results->totalItems = $query_count[0]->total_count;
        }

        $query_d = DB::select( DB::raw("select *,count(if(b.complete_flag='',NULL,b.complete_flag)) as job_done, count(b.id) as t_jobs,SEC_TO_TIME(sum(time_to_sec(TIMEDIFF(b.time_out,b.time_in)))) as t_jobs_time, a.id as timesheet_id from gpg_timesheet a,gpg_timesheet_detail b WHERE a.id = b.GPG_timesheet_id and a.GPG_employee_Id = '$id'  $DSQL  group by a.id $DQ2 limit $start,$limit"));

        foreach ($query_d as $key => $value) {
            foreach ($value as $key1 => $value1) {
                    $items_arr[$key1] = $value1;
            }
            
           //echo ;exit;
            $wages  = DB::select( DB::raw("select *, if(b.job_number!='NULL',1,0) as prevail from gpg_timesheet_detail a LEFT JOIN gpg_job_rates b on (a.gpg_task_type = b.gpg_task_type and a.job_num = b.job_number and b.status = 'A' and b.GPG_employee_type_id = ".$employeeinfo[0]->GPG_employee_type_id.") where GPG_timesheet_id = '".$items_arr['timesheet_id']."' order by a.time_in"));
            $temp = array();
            if(!empty($wages)){
                foreach($wages as $key2 => $wage){

                    $temp[] = $wage;

                }

            }
            $items_arr['wages_array'] = $temp;
                    
            $results->items[] = $items_arr;  	
        }
       

        
      return $results;

    }
    
    public static function get_time_difference( $start, $end )
{
	
    $uts['start']      =    strtotime( $start );
    $uts['end']        =    strtotime( $end );
	//echo $uts['end'] ."==". $uts['start'];
	//echo "<br>". strtotime(date('H:i:s'));
    if( $uts['start']!==-1 && $uts['end']!==-1 )
    {
		
        if( $uts['end'] >= $uts['start'] )
        {
            $diff    =    $uts['end'] - $uts['start'];
            if( $days=intval((floor($diff/86400))) )
                $diff = $diff % 86400;
            if( $hours=intval((floor($diff/3600))) )
                $diff = $diff % 3600;
            if( $minutes=intval((floor($diff/60))) )
                $diff = $diff % 60;
            $diff    =    intval( $diff );            
            return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
        }
        else
        {
			
            $uts['start']      =    strtotime( $start );
   			$uts['end']        =    strtotime( $end );
			$abc = $uts['end']+86400;
			$diff    =    ($abc - $uts['start']);
            if( $days=intval((floor($diff/86400))) )
                $diff = $diff % 86400;
            if( $hours=intval((floor($diff/3600))) )
                $diff = $diff % 3600;
            if( $minutes=intval((floor($diff/60))) )
                $diff = $diff % 60;
            $diff    =    intval( $diff );            
            return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
        }
    }
    else
    {
        trigger_error( "Invalid date/time data detected", E_USER_WARNING );
    }
    return( false );
}

public static function convertTime($vtime){
   if ($vtime!="") {
   $ptr = ":";
   //$v1 = split($ptr,$vtime);
   $v1 = explode($ptr,$vtime);
   $vtime = $v1[0]+($v1[1]/60);
   }
   return round($vtime,2);
}
    
    
    
}