<?php

class ConsumeContractController extends \BaseController {
	
	public function index() {
		// 
	}

	public function equipmentInfoList()
	{		
		$data['left_menu'] = Generic::modules();
		
		# fill search and filters dropdowns
		$data['customersCombo']   = Gpg_customer::orderBy('name', 'asc')->where('status','=', 'A')->lists('name', 'id');
		$data['consumeContractCombo']   = Gpg_contract::orderBy('job_num', 'asc')->lists('job_num', 'id');
		$data['deletePermission'] = (Generic::chkModulePerm('contract', 'equipment_info_index', array(1))) ? ['disabled'=>'disabled'] : [];

		$page            = Input::get('page', 1);
		$pageLimit       = Config::get('settings.DEFAULT_PAGE_LIMIT');
		$results         = $this->getByPage($page, $pageLimit);
		$data['results'] = Paginator::make($results->items, $results->totalItems, $pageLimit);

		# flash input fields to re-populate (state maintain) search form
		Input::flash();

		return View::make('consumecontract.equipmentInfoList', $data);		
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

		$SDate       = Input::get("SDate");
		$EDate       = Input::get("EDate");
		$optCustomer = Input::get("optCustomer");
		$optLocation = Input::get("optLocation");
		$optContract = Input::get("optContract");
		$DSQL = "";
		$DQ2  = " order by id desc ";

		if ($SDate != "" || $EDate != "") {		  
		    if ($SDate != "" && $EDate == "") {
			  $DSQL.= " AND DATE_FORMAT(created_on,'%Y-%m-%d') = '".date($defaultDBDateFormat,strtotime($SDate))."'" ;    
			} elseif ($SDate == "" && $EDate != "") {
			  $DSQL.= " AND created_on <= '".date($defaultDBDateFormat." 23:59:59",strtotime($EDate))."'" ;
			} elseif ($SDate != "" && $EDate != "") {
			  $DSQL.= " AND (created_on >= '".date($defaultDBDateFormat." 00:00:00",strtotime($SDate))."' 
			            AND created_on <= '".date($defaultDBDateFormat." 23:59:59",strtotime($EDate))."')" ; 
			}
		}

		if($optCustomer!='')  $DSQL.= "AND gpg_customer_id = '$optCustomer'";
		if($optLocation!='')  $DSQL.= "AND location = '$optLocation'";
		if($optContract!='')  $DSQL.= "AND gpg_consum_contract_id = '$optContract'";

		$query_count = DB::select(DB::raw("SELECT COUNT(id) AS count FROM gpg_consum_contract_equipment WHERE 1 $DSQL"));

		$query = DB::select(DB::raw("SELECT *,
									(SELECT name FROM gpg_customer WHERE id = gpg_customer_id ) as cusName, 
									(SELECT job_num FROM gpg_consum_contract WHERE id = gpg_consum_contract_id ) as consumContract 
								FROM gpg_consum_contract_equipment 
								WHERE 1 
								$DSQL $DQ2 
								LIMIT $start, $limit")); 

		if (isset($query_count[0]->count)){
			$results->totalItems = $query_count[0]->count;
			$results->items = $query;
		}

		return $results;

	}

	public function editEquipmentInfo($id) {
		$data['left_menu']       = Generic::modules();

		if (Request::isMethod('post')) {
			$inputFields = Input::except('_token');			
			$isUpdate = DB::table('gpg_consum_contract_equipment')
          					->where('id','=', $id)
          					->update($inputFields);
		}
		
		$data['customersCombo']  = Gpg_customer::orderBy('name', 'asc')->where('status','=', 'A')->lists('name', 'id');
		$data['equipmentInfoId'] =  $id;

		$data['data'] =  Gpg_consum_contract_equipment::find($id);
		$selectedCCId = $data['data']->find($id);

		$data['consumeContractCombo'] = Gpg_contract::orderBy('job_num', 'asc')
													->whereRaw('id not in (SELECT gpg_consum_contract_id 
																			FROM gpg_consum_contract_equipment 
																			WHERE gpg_consum_contract.id = gpg_consum_contract_id AND 
																			gpg_consum_contract.id <> '. $selectedCCId['gpg_consum_contract_id'] .')')
													->lists('job_num', 'id');
		
		return View::make('consumecontract.editEquipmentInfo', $data);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function deleteConsumeContracts()
	{		
      	$selectedIds = implode(',', Input::get('selectedIds'));
		
		$result = DB::table('gpg_consum_contract_equipment')->whereRaw("id IN ($selectedIds)")->delete();

      	if($result)
      		DB::table("gpg_field_service_work")
      			->whereRaw("gpg_consum_contract_equipment_id IN ($selectedIds)")
      			->update(array('gpg_consum_contract_equipment_id' => null));

		return 1;
	}


}
