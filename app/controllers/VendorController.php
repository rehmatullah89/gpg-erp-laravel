<?php

class VendorController extends BaseController {

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
            
            return View::make('vendors.index', $params);
                
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
                   $getImage = mysql_fetch_array(mysql_query("select * from gpg_vendor where id = '$id'"));

                   $del_message = mysql_query("DELETE FROM gpg_vendor where id = '$id'");
                if ($del_message){
                   @unlink($path."images/memberpic/".$getImage['pic']);
                   $_REQUEST['MSG_STR']="Message : Vendor is deleted successfully";
                } 
             }

            $SDate = Input::get("SDate");
            $EDate = Input::get("EDate");
            $Filter = Input::get("Filter");
            $FVal = trim(Input::get("FVal"));
            $language = Input::get("language");
            $status = Input::get("status");
            $DSQL = "";
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
            if ($Filter!="" && ($FVal!="" || $language!="" || $status!="")) {

               if ($Filter !="ven_type"){
                    $DSQL.= " AND $Filter like '$FVal'"; 
               } elseif($Filter =="ven_type")  {
                   if($status !=""){
                      $DSQL.= " AND $Filter = 'S' AND status = '$status'"; 
                   }else {
                       $DSQL.= " AND $Filter = 'S'"; 
                   }
               }


            }
             
            //$query_count = DB::select( DB::raw('SELECT count(gpg_vendor.id) as total_count FROM gpg_vendor'));
            
            $query_count = DB::select( DB::raw("select COUNT(a.id) as total_count from gpg_vendor a WHERE 1 $DSQL $DQ2"));
            

            $results->totalItems = $query_count[0]->total_count;
            
            $query_d = DB::select( DB::raw("select * from gpg_vendor WHERE 1 $DSQL $DQ2 limit $start,$limit"));
            
            foreach ($query_d as $key => $value) {
                  foreach ($value as $key1 => $value1) {
                          $items_arr[$key1] = $value1;

                  }

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


                return Redirect::to('vendors/index')->withSuccess('Permissions have been recorded successfully');          
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
            return View::make('vendors.ajax_perm_details', $params);
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
                $venType = Input::get("venType");
                $email_add = Input::get("email_add");
                $phone_no = Input::get("phone_no");
                $address1 = Input::get("address1");
                $address2 = Input::get("address2");
                $city = Input::get("city");
                $state = Input::get("state");
                $zip_code = Input::get("zip_code");
                $recommendation = Input::get("recommendation");
                $status = Input::get("status");
                $newpass = Input::get("newpass");
                $id = Input::get("id");
                $action = Input::get("action");
                
                $filename = "";
                
                if($email_add == ""){
                    
                    $valid = false;
                }
                
                $rules = array(
                    'email_add' => 'between:5,100 | email ',
                    'name' => 'unique:gpg_vendor,name',
                    'login' => 'unique:gpg_vendor,login',
                    'pass'  => ($venType == 'S')? 'required|max:20': '',
                    'repass' => ($venType == 'S')? 'required|max:20|same:pass': '' ,
                );
                
                //$rules = array('gpg_vendor' => 'unique:users,email');
                
                $input = Input::all();
                
                $validation = Validator::make($input, $rules);

                if ($validation->fails()){
                    //validation fails to send response with validation errors
                    // print $validator object to see each validation errors and display validation errors in your views
                    return Redirect::to('vendors/create')->withErrors($validation);
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
                   'recommendation' => $recommendation,
                   'created_on' => (date('Y-m-d H:is')),
                   'modified_on' => '',
                   'status' => $status,
                   'ven_type'=> $venType, 
                   'login'=> $login,
                   'pwd'=> md5($pass),
                   'perm' => 'all'
                   
               );
               
               DB::table('gpg_vendor')->insert($data);
               
               return Redirect::to('vendors/index')->withSuccess('Vendor has been created successfully');
               
           }
           
            $modules = Generic::modules();
           
            
            $params = array('left_menu' => $modules);
            
            return View::make('vendors.create', $params);
        
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
            
            $GpgVendorData = GpgVendor::find($id);
            
            $modules = Generic::modules();
           
            
            $params = array('left_menu' => $modules);
            
            return View::make('vendors.create', compact('GpgVendorData'),$params);
        
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
            $venType = Input::get("venType");
            $email_add = Input::get("email_add");
            $phone_no = Input::get("phone_no");
            $address1 = Input::get("address1");
            $address2 = Input::get("address2");
            $city = Input::get("city");
            $state = Input::get("state");
            $zip_code = Input::get("zip_code");
            $recommendation = Input::get("recommendation");
            $status = Input::get("status");
            $newpass = Input::get("newpass");
            $action = Input::get("action");
            $old_name = Input::get("old_name");
            $defaultpwd = Input::get("defaultpwd");
            
            if($venType == "S"){
                $login = Input::get("login");
            }
            
            if($old_name == $name){
                
                $rules = array(
                    'email_add' => 'between:5,100 | email ',
                    'name' => 'required',
                    'login' => 'required',
                    'pass'  => ($venType == 'S' && $defaultpwd == 1)? 'required|max:20': '',
                    'repass' => ($venType == 'S' && $defaultpwd == 1)? 'required|max:20|same:pass': '' ,
                );
                
            }else{
                
                 $rules = array(
                    'email_add' => 'between:5,100 | email ',
                    'name' => 'required | unique:gpg_vendor,name',
                    'login' => 'unique:gpg_vendor,login',
                    'pass'  => ($venType == 'S' && $defaultpwd == 1 )? 'required|max:20': '',
                    'repass' => ($venType == 'S' && $defaultpwd == 1)? 'required|max:20|same:pass': '' ,
                );
            }
            

            //$rules = array('gpg_vendor' => 'unique:users,email');

            $input = Input::all();

            $validation = Validator::make($input, $rules);

            if ($validation->fails()){
                //validation fails to send response with validation errors
                // print $validator object to see each validation errors and display validation errors in your views
                return Redirect::to('vendors/'.$id.'/edit')->withErrors($validation);
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
                   'recommendation' => $recommendation,
                   'created_on' => (date('Y-m-d H:is')),
                   'modified_on' => '',
                   'status' => $status,
                   'ven_type'=> $venType, 
                   'login'=> $login,
                   'pwd'=> $pass,                  
                   
               );
            
            
            DB::table('gpg_vendor')
                    ->where('id','=', $id)
                    ->update($data);
            
            
            return Redirect::to('vendors/index')->withSuccess('Vendor has been updated successfully');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
            DB::table('gpg_vendor')->where('id','=',$id)->delete();  
            return Redirect::to('vendors/index')->withSuccess('Vendor has been deleted successfully');
	
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

}