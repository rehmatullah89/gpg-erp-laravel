<?php

class ContractTenureController extends \BaseController {
	
	public function index() {
		// 
	}

	public function contractTenureList() {

		$data['left_menu'] = Generic::modules();
		
		# fill search and filters dropdowns
		$data['customersCombo']   = Gpg_customer::orderBy('name', 'asc')->where('status','=', 'A')->lists('name', 'id');
		$data['typesCombo']       = Gpg_contract_amt_list::groupBy('type')->lists('type', 'type');
		$data['deletePermission'] = (Generic::chkModulePerm('contract', 'contract_amt_list', array(1))) ? ['disabled'=>'disabled'] : [];

		$data['results'] = $this->getByPage();

		# flash input fields to re-populate (state maintain) search form
		Input::flash();

		return View::make('contracttenure.contractTenureList', $data);

	}

	public function getByPage()
	{
		$results             = new \StdClass;		
		$defaultDBDateFormat = Config::get('settings.DB_DATE_FORMAT');

		$jobnumber = Input::get("jobnumber");
		$SSDate    = Input::get("SSDate");
		$SEDate    = Input::get("SEDate");
		$ESDate    = Input::get("ESDate");
		$EEDate    = Input::get("EEDate");
		
		$DSQL="";
		if ($SSDate!="" && $SEDate =="") {
	  		$DSQL.= " AND DATE_FORMAT(consum_contract_start_date,'%Y-%m-%d') = '".date($defaultDBDateFormat,strtotime($SSDate))."'" ;    
		} elseif ($SSDate == "" && $SEDate != "") {
			$DSQL.= " AND consum_contract_start_date <= '".date($defaultDBDateFormat,strtotime($SEDate))."'" ;
		} elseif ($SSDate != "" && $SEDate != "") {
			$DSQL.= " AND (consum_contract_start_date >= '".date($defaultDBDateFormat,strtotime($SSDate))."' 
		    		AND consum_contract_start_date <= '".date($defaultDBDateFormat,strtotime($SEDate))."')" ; 
		}
			

		if ($ESDate!="" && $EEDate =="") {
			$DSQL.= " AND DATE_FORMAT(consum_contract_end_date,'%Y-%m-%d') = '".date($defaultDBDateFormat,strtotime($ESDate))."'" ;    
		} elseif ($ESDate == "" && $EEDate != "") {
			$DSQL.= " AND consum_contract_end_date <= '".date($defaultDBDateFormat,strtotime($EEDate))."'" ;
		} elseif ($ESDate != "" && $EEDate != "") {
			$DSQL.= " AND (consum_contract_end_date >= '".date($defaultDBDateFormat,strtotime($ESDate))."' 
		        	AND consum_contract_end_date <= '".date($defaultDBDateFormat,strtotime($EEDate))."')" ; 
		}
			
		if(strlen($jobnumber)>0)
			$DSQL .= " AND job_num LIKE '".$jobnumber."%' ";


		$query = DB::select(DB::raw("SELECT *, 
										(SELECT name FROM gpg_customer WHERE id = gpg_customer_id) AS cus_name
									FROM gpg_consum_contract
									WHERE consum_contract_start_date IS NOT NULL
									AND consum_contract_end_date IS NOT NULL
									AND consum_contract_end_date > NOW()
									".$DSQL."
									ORDER BY consum_contract_end_date ASC"));
		
		return $query;
	}

	public function excelExport(){
		ini_set('memory_limit', '-1');
	    Excel::create('Contract Tenure List', function($excel) {
		    $excel->sheet('Contract Tenure List', function($sheet) {

			    $sheet->setStyle(array(
	    			'td' => array(
	    				'background' => 'blue'
	    			)
				));
			    		   		
		  		$data['results'] = $this->getByPage();

		        $sheet->loadView('contracttenure.excelExport', $data);
	    	});
		})->export('xls');
 	}

}
