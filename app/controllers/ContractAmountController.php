<?php

class ContractAmountController extends \BaseController {
	
	public function index() {
		// 
	}

	public function contractAmountList() {

		$data['left_menu'] = Generic::modules();
		
		# fill search and filters dropdowns
		$data['customersCombo']   = Gpg_customer::orderBy('name', 'asc')->where('status','=', 'A')->lists('name', 'id');
		$data['typesCombo']       = Gpg_contract_amt_list::groupBy('type')->lists('type', 'type');
		$data['deletePermission'] = (Generic::chkModulePerm('contract', 'contract_amt_list', array(1))) ? ['disabled'=>'disabled'] : [];

		$page            = Input::get('page', 1);
		$pageLimit       = Config::get('settings.DEFAULT_PAGE_LIMIT');
		$results         = $this->getByPage($page, $pageLimit);
		Debugbar::info($results);
		$data['results'] = Paginator::make($results->items, $results->totalItems, $pageLimit);

		# flash input fields to re-populate (state maintain) search form
		Input::flash();

		return View::make('contractamount.contractAmountList', $data);

	}

	public function getByPage($page = 1, $limit = null)
	{
		$results             = new \StdClass;
		$results->page       = $page;
		$results->totalItems = 0;
		$results->items      = array();		
		$defaultDBDateFormat = Config::get('settings.DB_DATE_FORMAT');

		$limitRange = '';
		if($limit != null) {
			$results->limit = $limit;
			$start          = $limit * ($page - 1);			
			$limitRange     = 'LIMIT ' . $start . ', ' . $limit;
		}

		$StartSDate      = Input::get("StartSDate");
		$StartEDate      = Input::get("StartEDate");
		$ExpirationSDate = Input::get("ExpirationSDate");
		$ExpirationEDate = Input::get("ExpirationEDate");
		$SContractNumber = Input::get("SContractNumber");
		$EContractNumber = Input::get("EContractNumber");
		$optCustomer     = Input::get("optCustomer");		
		$optType         = Input::get("optType");		
		
		$queryPart       = "";

		if ($StartSDate!="" and $StartEDate!="") {
			$queryPart .= " AND start_date >= '".date($defaultDBDateFormat,strtotime($StartSDate))."' AND start_date <= '".date($defaultDBDateFormat,strtotime($StartEDate))."'";
		}
		elseif ($StartSDate!="") 
		{ 
			$queryPart .= " AND start_date = '".date($defaultDBDateFormat,strtotime($StartSDate))."'";
		}

		if ($ExpirationSDate!="" and $ExpirationEDate!="") {
			$queryPart .= " AND end_date >= '".date($defaultDBDateFormat,strtotime($ExpirationSDate))."' AND end_date <= '".date($defaultDBDateFormat,strtotime($ExpirationEDate))."'";
		}
		elseif ($ExpirationSDate!="") 
		{ 
			$queryPart .= " AND end_date = '".date($defaultDBDateFormat,strtotime($ExpirationSDate))."'";
		}

		if ($optCustomer!="") $queryPart .= " AND gpg_customer_id = '$optCustomer' ";		
		if ($optType!="") $queryPart .= " AND type = '$optType' ";
		if ($SContractNumber!="" and $EContractNumber!="") $queryPart .= " AND contract_number >= '".$SContractNumber."' AND contract_number <= '".$EContractNumber."' ";
		elseif ($SContractNumber!="") $queryPart .= " AND contract_number = '".$SContractNumber."'";

		$queryPart .= " order by created_on desc"; 
		
		$query_count = DB::select(DB::raw("SELECT count(id) AS count FROM gpg_contract_amt_list WHERE 1 $queryPart"));

		$query = DB::select(DB::raw("SELECT *,
										(SELECT name FROM gpg_customer WHERE id = gpg_customer_id) as customer, 
										(select name FROM gpg_employee WHERE id = gpg_employee_id) as salesPerson 
									FROM 
										gpg_contract_amt_list 
									WHERE 1 
										$queryPart  
										$limitRange"));

		if (isset($query_count[0]->count)){
			$results->totalItems = $query_count[0]->count;
			$results->items = $query;
		}

		return $results;
	}

	public function excelExport(){
		ini_set('memory_limit', '-1');
	    Excel::create('Contract Amount List', function($excel) {
		    $excel->sheet('Contract Amount List', function($sheet) {

			    $sheet->setStyle(array(
	    			'td' => array(
	    				'background' => 'blue'
	    			)
				));	
			    
				$pageLimit             = Config::get('settings.DEFAULT_PAGE_LIMIT');

				$page = Input::get('page', 1);
		   		$results = $this->getByPage($page);
		  		$data['results'] = Paginator::make($results->items, $results->totalItems, $pageLimit);

		        $sheet->loadView('contractamount.excelExport', $data);
	    	});
		})->export('xls');
 	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function deleteContractAmount()
	{		
      	$selectedIds = implode(',', Input::get('selectedIds'));		
		$result = DB::table('gpg_contract_amt_list')->whereRaw("id IN ($selectedIds)")->delete();
		return 1;
	}

}
