<?php
/*
A generic class for functions to be called recursively in the system.
*/

Class Generic{
	/* Returns the modules the user is allowed access to
	*/
	public static function modules(){
		//$admin_id = Session::get('gpg_admin_id');
		$admin_id = Auth::id();
		$all_modules = array();
		if($admin_id==1) // Super admin
		{
			//$modules = Gpg_module::all();
			$modules = Gpg_module::orderBy('position', 'asc')->get();
		}
		else
		{
			$modules = Gpg_ad_acc::find($admin_id)->modules()->get();
		}
		foreach($modules as $module){
			 if($module->parent!=0 && !isset($all_modules[0][$module->parent])){
				$mod = $modules->find($module->parent);
			 	$all_modules[$mod->parent][$mod->id] = $mod;
			 }
			 else{
			 	$all_modules[$module->parent][$module->id] = $module;
			 }
		}
		// ksort($all_modules);
		return $all_modules;
	}

	public static function set_font_awesome($index){
		$modules_fonts = array('1'=>'fa-cogs', // admin settings
								'2'=>'fa-cogs', // website settings
								'3'=>'fa-user', // employee admin
								'4'=>'fa-male', // customer admin
								'5'=>'fa-check', // Jobs admin
								'6'=>'fa-clock-o', // timehseet admin
								'7'=>'fa-usd', // wages admin
								'8'=>'fa-plane', // holiday admin
								'9'=>'fa-copy',//Reports
								'10'=>'fa-cloud-upload',//bulk job uploader
								'11'=>'fa-cloud-download',//excel report generation
								'12'=>'fa-bars',//Sales tracking admin
								'40'=>'fa-money',//Purchase Order Admin
								'43'=>'fa-users',//Vendors Admin
								'46'=>'fa-barcode',//GL Codes admin
								'49'=>'fa-wrench',//Equipment Admin
								'54'=>'fa-truck',//rentals admin
								'67'=>'fa-building-o',//Department Admin
								'70'=>'fa-check-square',//Task admin
								'86'=>'fa-credit-card',//expense admin
								'100'=>'fa-gear',//Parts admin
								'109'=>'fa-pencil-square-o',//contract admin
								'131'=>'fa-suitcase',//asset admin
								'139'=>'fa-spinner',//Activity admin
								'162'=>'fa-bar-chart-o',//QC Reports
								'189'=>'fa-envelope',//emails admin
								'194'=>'fa-dashboard',//Dashboard
								'174'=>'fa-comments-o',//RFI
								);
		return $modules_fonts[$index];
	}


	// all the constants of the application coming form gpg_settings table
	public static function application_constants(){
		$settings = Gpg_settings::all();
		$config_array = array();
		foreach($settings as $setting){
			$config_array[$setting->name] = $setting->value;
		}
		return $config_array;
	}

	public static function currency_format($value) {
		return Config::get('settings._DefaultCurrency') . number_format($value, 2);
	}

	public static function show_consum_contract_attactments($consumContractId, $adPath=NULL) {
		$attachmentString = "";		
		$results = DB::select(DB::raw("SELECT filename, displayname FROM gpg_consum_contract_attachment WHERE gpg_consum_contract_id = '$consumContractId'"));

		foreach($results as $key=>$row) {			
			$displayName = wordwrap($row->displayname,40, "\n",1);
			$attachmentString .= link_to_route('contract/downloadFile', $row->filename, array('file'=>$row->filename));
		}

		return $attachmentString;
	}

	/**
	* chkModulePerm()
	* This function is for checking delete permission in any module. 
	* @param mixed|folder
	* @param mixed|page
	* @param mixed|action
	* @return True or False
	*/
	public static function chkModulePerm($folder, $page, $action = NULL) {
		$admin   = Session::get("ADMIN");
		$modules = Session::get("MODULES") . "#";
		$getClientModuleAction = array();
		
		$getClientModule     = DB::select(DB::raw("SELECT id FROM gpg_module WHERE folder_name = '$folder'"));
		$getClientModulePage = DB::select(DB::raw("SELECT id FROM gpg_module WHERE folder_name = '$page' AND parent = '".$getClientModule[0]->id."'"));

		if (count($action)) {
			$getClientModuleAction = DB::select(DB::raw("SELECT id FROM gpg_module WHERE folder_name = 'delete' AND parent = '".$getClientModulePage[0]->id."'"));
		}
		
		if ($admin==1 || (preg_match("#".$getClientModule[0]->id."#",$modules) && preg_match("#".$getClientModulePage[0]->id."#",$modules) && preg_match("#".$getClientModuleAction[0]->id."#",$modules))) 
			return true;
		else 
			return false; 
	}

	/*
	 * Method to strip tags globally.
	 */
	public static function globalXssClean()
	{
	    // Recursive cleaning for array [] inputs, not just strings.
	    $sanitized = static::arrayStripTags(Input::get());
	    Input::merge($sanitized);
	}
	 
	public static function arrayStripTags($array)
	{
	    $result = array();
	 
	    foreach ($array as $key => $value) {
	        // Don't allow tags on key either, maybe useful for dynamic forms.
	        $key = strip_tags($key);
	 
	        // If the value is an array, we will just recurse back into the
	        // function to keep stripping the tags out of the array,
	        // otherwise we will set the stripped value.
	        if (is_array($value)) {
	            $result[$key] = static::arrayStripTags($value);
	        } else {
	            // I am using strip_tags(), you may use htmlentities(),
	            // also I am doing trim() here, you may remove it, if you wish.
	            $result[$key] = trim(strip_tags($value));
	        }
	    }
	 
	    return $result;
	}

}
