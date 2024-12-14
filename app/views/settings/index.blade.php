@extends("layouts/dashboard_master")
@section('content')
  <section>
    
  </section>
@stop
@section('dashboard_panels')
              <!-- page start-->
            {{ Form::open(array('before' => 'csrf' ,'url'=>route('settings/index'), 'id'=>'frmid1', 'files'=>true, 'method' => 'post')) }}  
            <div class="row">
              <div class="col-sm-12">
                   <section class="panel">
                        <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                          <b><i>Update required* settings! </i></b>
                        </header>
                          @if (isset($errors) && ($errors->any()))
                              <div class="alert alert-danger">
                                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                                  <h4>Error</h4>
                                     <ul>
                                      {{ implode('', $errors->all('<li class="error">:message</li>')) }}
                                     </ul>
                              </div>
                          @endif
                          @if(@Session::has('success'))
                              <div class="alert alert-success alert-block">
                              <button type="button" class="close" data-dismiss="alert">&times;</button>
                                 <h4>Success</h4>
                                  <ul>
                                  {{ Session::get('success') }}
                                 </ul>
                              </div>
                        @endif
              </section>
              </div>
            </div>  
            <div class="row">
              <div class="col-sm-6">
              <section class="panel">
              <header class="panel-heading">
                  GENERAL SETTINGS 
              </header>
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <tbody class="cf">
                          <tr><th style="text-align:center;">Default Currency Sign:</th><td>{{ Form::text('_DefaultCurrency',$setval['_DefaultCurrency'], array('class' => 'form-control dpd1', 'id' => '_DefaultCurrency', 'required')) }}</td></tr>
                          <tr><th style="text-align:center;">Date Format: </th><td>{{ Form::select('_DateFormat',array('m/d/Y'=>'mon/day/YEAR','d/m/Y'=>'mon/day/YEAR'),$setval['_DateFormat'], array('class' => 'form-control dpd1', 'id' => '_DateFormat')) }}</td></tr>
                          <tr><th style="text-align:center;">Items Per Page: </th><td>{{ Form::text('_ItemsPerPageAdmin',$setval['_ItemsPerPageAdmin'], array('class' => 'form-control dpd1', 'id' => '_ItemsPerPageAdmin')) }}</td></tr>
                          <tr><th style="text-align:center;">Month Duration: </th><td>{{ Form::text('_MonthDuration',$setval['_MonthDuration'], array('class' => 'form-control dpd1', 'id' => '_MonthDuration')) }}</td></tr>
                          <tr><th style="text-align:center;">Allowed Extentions (Image Upload): </th><td>{{ Form::text('_ImgExt',$setval['_ImgExt'], array('class' => 'form-control dpd1', 'id' => '_ImgExt')) }}</td></tr>
                          <tr><th style="text-align:center;">Accural Rates Vacation: </th><td>{{ Form::text('_AccRates',$setval['_AccRates'], array('class' => 'form-control dpd1', 'id' => '_AccRates')) }}</td></tr>
                          <tr><th style="text-align:center;">Accural Rates Sick: </th><td>{{ Form::text('_AccRatesSick',$setval['_AccRatesSick'], array('class' => 'form-control dpd1', 'id' => '_AccRatesSick')) }}</td></tr>
                          <tr><th style="text-align:center;">Report Date Range: </th><td>{{ Form::select('_ReportDateRange',array('oneday'=>'One Day','oneweek'=>'One Week','twoweeks'=>'Two Weeks','onemonth'=>'One Month','twomonths'=>'Two Months'),$setval['_ReportDateRange'], array('class' => 'form-control dpd1', 'id' => '_ReportDateRange')) }}</td></tr>
                          <tr><th style="text-align:center;">Website Logo: </th><td>{{ Form::file('Logo','', array('class' => 'form-control dpd1', 'id' => 'Logo')) }}<input type="checkbox" name="nologo">Click to hide logo!</td></tr>
                        </tbody>
                  </table>                                
              </section>
             </div>
            </div>
          </section>
         </div>
           <div class="col-sm-6">
              <section class="panel">
              <header class="panel-heading">
                  Authentication for filters and excel exports
              </header>
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <tbody class="cf">
                          <tr><th style="text-align:center;">Archive Data Password:</th><td>{{ Form::password('ArchiveDataPwd', array('class' => 'form-control','id'=>'ArchiveDataPwd')) }}</td></tr>
                          <tr><th style="text-align:center;">Export Data Password: </th><td>{{ Form::password('export_pwd', array('class' => 'form-control', 'id' => 'export_pwd')) }}</td></tr>
                          <tr><th style="text-align:center;">Work Order Form Override: </th><td>{{ Form::password('fbomb_pwd', array('class' => 'form-control', 'id' => 'fbomb_pwd')) }}</td></tr>
                        </tbody>
                  </table>                                
              </section>
              <header class="panel-heading">
                  ELECTRICAL QUOTE STAGE SETTINGS
              </header>
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <tbody class="cf">
                          <tr><th style="text-align:center;">Electrical Quote Stage:</th><td>{{ Form::text('_ElectricalQuoteStage_1',@$setval['_ElectricalQuoteStage_1'], array('class' => 'form-control', 'id' => '_ElectricalQuoteStage_1')) }}</td></tr>
                        </tbody>
                  </table>                                
              </section>
              <header class="panel-heading">
                  SALARY SETTINGS
              </header>
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <tbody class="cf">
                          <tr><th style="text-align:center;">Tax Deduction:</th><td>{{ Form::text('_TaxDeduction',@$setval['_TaxDeduction'], array('class' => 'form-control', 'id' => '_TaxDeduction')) }}</td></tr>
                        </tbody>
                  </table>                                
              </section>
              <header class="panel-heading">
                  TIME SHEET LOCK MANAGMENT
              </header>
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <tbody class="cf">
                          <tr><th style="text-align:center;">Lock Days:</th><td>{{ Form::text('_timeSheetLockFrom',@$setval['_timeSheetLockFrom'], array('class' => 'form-control', 'id' => '_timeSheetLockFrom')) }}</td></tr>
                        </tbody>
                  </table>                                
              </section>
              </div>
              </div>
          </section>
         </div>
        </div>
        <div class="row">
              <div class="col-sm-6">
              <section class="panel">
              <header class="panel-heading">
                  FIELD SERVICE WORK SETTINGS
              </header>
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <tbody class="cf">
                          <tr><th style="text-align:center;">Shop Labor Hours Cost Rate:</th><td>{{ Form::text('_ShopLaborCostRate',@$setval['_ShopLaborCostRate'], array('class' => 'form-control dpd1', 'id' => '_ShopLaborCostRate')) }}</td></tr>
                          <tr><th style="text-align:center;">Shop Labor Hours List Rate: </th><td>{{ Form::text('_ShopLaborListRate',@$setval['_ShopLaborListRate'], array('class' => 'form-control dpd1', 'id' => '_ShopLaborListRate')) }}</td></tr>
                          <tr><th style="text-align:center;">Contract Hours Cost Rate: </th><td>{{ Form::text('_ContractCostRate',@$setval['_ContractCostRate'], array('class' => 'form-control dpd1', 'id' => '_ContractCostRate')) }}</td></tr>
                          <tr><th style="text-align:center;">Contract Hours List Rate: </th><td>{{ Form::text('_ContractListRate',@$setval['_ContractListRate'], array('class' => 'form-control dpd1', 'id' => '_ContractListRate')) }}</td></tr>
                          <tr><th style="text-align:center;">Load Bank Hours Cost Rate: </th><td>{{ Form::text('_LoadBankCostRate',@$setval['_LoadBankCostRate'], array('class' => 'form-control dpd1', 'id' => '_LoadBankCostRate')) }}</td></tr>
                          <tr><th style="text-align:center;">Load Bank Hours List Rate: </th><td>{{ Form::text('_LoadBankListRate',@$setval['_LoadBankListRate'], array('class' => 'form-control dpd1', 'id' => '_LoadBankListRate')) }}</td></tr>
                          <tr><th style="text-align:center;">Over Time Hours Cost Rate: </th><td>{{ Form::text('_OverTimeCostRate',@$setval['_OverTimeCostRate'], array('class' => 'form-control dpd1', 'id' => '_OverTimeCostRate')) }}</td></tr>
                          <tr><th style="text-align:center;">Over Time Hours List Rate: </th><td>{{ Form::text('_OverTimeListRate',@$setval['_OverTimeListRate'], array('class' => 'form-control dpd1', 'id' => '_OverTimeListRate')) }}</td></tr>
                          <tr><th style="text-align:center;">Sub Con Hours Cost Rate: </th><td>{{ Form::text('_SubConCostRate',@$setval['_SubConCostRate'], array('class' => 'form-control dpd1', 'id' => '_SubConCostRate')) }}</td></tr>
                          <tr><th style="text-align:center;">Sub Con Hours List Rate: </th><td>{{ Form::text('_SubConListRate',@$setval['_SubConListRate'], array('class' => 'form-control dpd1', 'id' => '_SubConListRate')) }}</td></tr>
                          <tr><th style="text-align:center;">Field Service Work Hazmat: </th><td>{{ Form::text('_FieldServiceWorkHazmat',@$setval['_FieldServiceWorkHazmat'], array('class' => 'form-control dpd1', 'id' => '_FieldServiceWorkHazmat')) }}</td></tr>
                          <tr><th style="text-align:center;">Field Service Work Tax: </th><td>{{ Form::text('_FieldServiceWorkTax',@$setval['_FieldServiceWorkTax'], array('class' => 'form-control dpd1', 'id' => '_FieldServiceWorkTax')) }}</td></tr>
                        </tbody>
                  </table>                                
              </section>
             </div>
            </div>
          </section>
         </div>
         <div class="col-sm-6">
              <section class="panel">
              <header class="panel-heading">
                  JOB ELECTRICAL SUBQUOTE SETTINGS
              </header>
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <tbody class="cf">
                          <tr><th style="text-align:center;">Annual Energy Multiplier:</th><td>{{ Form::text('_AnnualEnergyCost',@$setval['_AnnualEnergyCost'], array('class' => 'form-control dpd1', 'id' => '_AnnualEnergyCost')) }}</td></tr>
                          <tr><th style="text-align:center;">Material Mark Up: </th><td>{{ Form::text('_MaterialMarkUp',@$setval['_MaterialMarkUp'], array('class' => 'form-control dpd1', 'id' => '_MaterialMarkUp')) }}</td></tr>
                          <tr><th style="text-align:center;">Labor Hours Multiplier: </th><td>{{ Form::text('_LaborHoursMultiplier',@$setval['_LaborHoursMultiplier'], array('class' => 'form-control dpd1', 'id' => '_LaborHoursMultiplier')) }}</td></tr>
                          <tr><th style="text-align:center;">Labor Rate: </th><td>{{ Form::text('_LaborRate',@$setval['_LaborRate'], array('class' => 'form-control dpd1', 'id' => '_LaborRate')) }}</td></tr>
                          <tr><th style="text-align:center;">Incentive Rate: </th><td>{{ Form::text('_IncentiveRate',@$setval['_IncentiveRate'], array('class' => 'form-control dpd1', 'id' => '_IncentiveRate')) }}</td></tr>
                          <tr><th style="text-align:center;">Incentive Rate kW: </th><td>{{ Form::text('_IncentiveRateKw',@$setval['_IncentiveRateKw'], array('class' => 'form-control dpd1', 'id' => '_IncentiveRateKw')) }}</td></tr>
                          <tr><th style="text-align:center;">Applicable Sales Tax: </th><td>{{ Form::text('_AppSalesTax',@$setval['_AppSalesTax'], array('class' => 'form-control dpd1', 'id' => '_AppSalesTax')) }}</td></tr>
                          <tr><th style="text-align:center;">Qualifying kW Reduction Constant: </th><td>{{ Form::text('_ReductionConstant',@$setval['_ReductionConstant'], array('class' => 'form-control dpd1', 'id' => '_ReductionConstant')) }}</td></tr>
                          <tr><th style="text-align:center;">Total SDGE Incentive with OBF Constant: </th><td>{{ Form::text('_IncentiveObf',@$setval['_IncentiveObf'], array('class' => 'form-control dpd1', 'id' => '_IncentiveObf')) }}</td></tr>
                        </tbody>
                  </table>                                
              </section>
               <header class="panel-heading">
                  ELECTRICAL QUOTE PDF SETTINGS
              </header>
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <tbody class="cf">
                          <tr><td colspan="2" style="text-align:center;">{{ Form::textarea('_TermsAndConditions',@$setval['_TermsAndConditions'], array('class' => 'form-control dpd1', 'id' => '_TermsAndConditions','size'=>'20x3')) }}</td></tr>
                        </tbody>
                  </table>                                
              </section>
             </div>
            </div>
          </section>
         </div>
      </div>   
        <div class="row">
            <div class="col-sm-6">
              <section class="panel">
              <header class="panel-heading">
                  CONSUM CONTRACT SETTINGS
              </header>
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <tbody class="cf">
                          <tr><th style="text-align:center;">Labor Rate:</th><td>{{ Form::text('_ContractLaborRate',@$setval['_ContractLaborRate'], array('class' => 'form-control dpd1', 'id' => '_ContractLaborRate')) }}</td></tr>
                          <tr><th style="text-align:center;">Equipment Rate: </th><td>{{ Form::text('_ContractEqpRate',@$setval['_ContractEqpRate'], array('class' => 'form-control dpd1', 'id' => '_ContractEqpRate')) }}</td></tr>
                          <tr><th style="text-align:center;">Material Rate: </th><td>{{ Form::text('_ContractMaterialRate',@$setval['_ContractMaterialRate'], array('class' => 'form-control dpd1', 'id' => '_ContractMaterialRate')) }}</td></tr>
                          <tr><th style="text-align:center;">Contract Terms and Conditions: </th><td>{{ Form::textarea('_ContractTermsAndConditions',@$setval['_ContractTermsAndConditions'], array('class' => 'form-control dpd1', 'id' => '_ContractTermsAndConditions')) }}</td></tr>
                        </tbody>
                  </table>                                
              </section>
             </div>
            </div>
          </section>
         </div>
         <div class="col-sm-6">
              <section class="panel">
              <header class="panel-heading">
                  SERVICE JOB ALLOCATED HOURS SETTING
              </header>
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <tbody class="cf">
                          <tr><th style="text-align:center;">Allocated Hour Constant:</th><td>{{ Form::file('_AllocatedPmHours','', array('class' => 'form-control dpd1', 'id' => '_AllocatedPmHours')) }}<sub>Upload Tab delimited File only</sub></td></tr>
                          @foreach($alloc_arr as $akey=>$aval)
                            <tr><th style="text-align:center;">{{$akey}}</th><td>{{$aval}}</td></tr>
                          @endforeach
                        </tbody>
                  </table>                                
              </section>
             </div>
            </div>
          </section>
         </div>
      </div>
        <div class="row">
            <div class="col-sm-6">
              <section class="panel">
              <header class="panel-heading">
                 CONTACT SETTINGS
              </header>
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <tbody class="cf">
                          <tr><th style="text-align:center;">Company Email:</th><td>{{ Form::text('_CompanyEmail',@$setval['_CompanyEmail'], array('class' => 'form-control dpd1', 'id' => '_CompanyEmail')) }}</td></tr>
                          <tr><th style="text-align:center;">Admin Email: </th><td>{{ Form::text('_AdminEmail',@$setval['_AdminEmail'], array('class' => 'form-control dpd1', 'id' => '_AdminEmail')) }}</td></tr>
                          <tr><th style="text-align:center;">Contact Info: </th><td>{{ Form::textarea('_ContactInfo',@$setval['_ContactInfo'], array('class' => 'form-control dpd1', 'id' => '_ContactInfo','size'=>'20x4')) }}</td></tr>
                          <?php $c=1;?>
                          @foreach($con_arr as $res)
                            <tr><th style="text-align:center;">&nbsp; </th><td>{{ Form::textarea('_ContactInfo_'.$c,$res, array('class' => 'form-control dpd1', 'id' => '_ContactInfo_'.$c,'size'=>'20x4')) }}</td></tr> 
                            <?php $c++;?>
                          @endforeach
                        </tbody>
                  </table>                                
              </section>
               <header class="panel-heading">
                 Tags for GL-Codes [<i id="add_new_glcode_index" class="fa fa-plus" style="color:silver;" title="Create New Row"></i>]
              </header>
                <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;" id="glcode_table">
                      <tbody class="cf">
                        <?php $t=1;?>
                        @if(empty($tag_arr))
                          <tr><td>{{ Form::text('_gl_tags_index_1',@$setval['_gl_tags_index_1'], array('class' => 'form-control dpd1', 'id' => '_gl_tags_index_1')) }}</td></tr>
                        @else
                        @foreach($tag_arr as $tl)
                          <tr><td>{{ Form::text('_gl_tags_index_'.$t,$tl, array('class' => 'form-control dpd1', 'id' => '_gl_tags_index_'.$t)) }}</td></tr>
                           <?php $t++;?>
                        @endforeach
                        @endif
                        <input type="hidden" name="glcode_count" id="glcode_count" value="{{$t}}">
                      </tbody>    
                  </table>                                
              </section>
             </div>
            </div>
          </section>
         </div>
         <div class="col-sm-6">
              <section class="panel">
              <header class="panel-heading">
                 ZONE INDEXS [<i id="add_new_zone_index" class="fa fa-plus" style="color:silver;" title="Create New Row"></i>]
              </header>
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;" id="zone_table">
                        <tbody class="cf">
                        <?php $z=1;?>
                        @if(empty($zon_arr))
                          <tr><td>{{ Form::text('_zone_index_1',@$setval['_zone_index_1'], array('class' => 'form-control dpd1', 'id' => '_zone_index_1')) }}</td></tr>
                        @else
                        @foreach($zon_arr as $vl)
                          <tr><td>{{ Form::text('_zone_index_'.$z,$vl, array('class' => 'form-control dpd1', 'id' => '_zone_index_'.$z)) }}</td></tr>
                           <?php $z++;?>
                        @endforeach
                        @endif
                        <input type="hidden" name="zone_count" id="zone_count" value="{{$z}}">
                        </tbody>
                  </table>                                
              </section>
             </div>
            </div>
          </section>
         </div>
      </div>
        <div class="row">
            <div class="col-sm-12">
              <section class="panel">
              <header class="panel-heading">
                  SCOPE OF WORK TEMPLATES [<i id="add_new_scop_work" class="fa fa-plus" style="color:silver;" title="Create New Row"></i>]
              </header>
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;" id="scop_work_table">
                        <tbody class="cf">
                          <?php $tp=1;?>
                          @foreach($templt_arr as $k=>$v)
                          <tr><th style="text-align:center;">{{ Form::text('scope_template_name_'.$tp,$k, array('class' => 'form-control dpd1', 'id' => 'scope_template_name_'.$tp)) }}</th><td>{{ Form::textarea('scope_template_'.$tp,$v, array('class' => 'form-control dpd1', 'id' => 'scope_template_'.$tp,'size'=>'20x2')) }}</td></tr>
                           <?php $tp++;?>  
                          @endforeach
                        </tbody>
                        <input type="hidden" name="scop_work_count" id="scop_work_count" value="{{$tp}}">
                  </table>                                
              </section>
             </div>
            </div>
          </section>
         </div>
      </div>
      <div class="row">
            <div class="col-sm-12">
              <section class="panel">
              <header class="panel-heading">
                  Parts Vendor Cost Update Permissions
              </header>
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <tbody class="cf">
                         <?php $counter=1;
                          $allowed_emps = explode(",",$setval["_parts_allow_emps"]);
                         ?>
                         <tr>
                         @foreach($ad_acc_arr as $emp_row)
                          <td>
                            <input type="checkbox" name="_parts_allow_emps[]" value="<?php echo $emp_row['ad_id']?>" <?php echo in_array($emp_row['ad_id'],$allowed_emps)? "checked=\"checked\"" : "" ?> id="parts_allowed_emp_<?php echo $emp_row['ad_id'];?>" />&nbsp;<?php echo $emp_row['fname']." ".$emp_row['lname']; ?>
                          </td>
                          <?php 
                            if($counter%8 == 0)
                              echo "</tr><tr>";
                          $counter++;?>
                         @endforeach
                        </tr>
                        </tbody>
                  </table>                                
              </section>
             </div>
            </div>
          </section>
         </div>
      </div>
       <div class="row">
            <div class="col-sm-12">
              <section class="panel">
              <header class="panel-heading">
                 Time Card override employees
              </header>
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                      <tbody class="cf">
                        <?php $counter=1;
                          $allowed_emps = explode(",",@$setval["_time_card_allow_emps"]);
                         ?>
                         <tr>
                         @foreach($ad_acc_arr as $emp_row)
                          <td>
                            <input type="checkbox" name="_time_card_allow_emps[]" value="<?php echo $emp_row['ad_id']?>" <?php echo in_array($emp_row['ad_id'],$allowed_emps)? "checked=\"checked\"" : "" ?> id="parts_allowed_emp_<?php echo $emp_row['ad_id'];?>" />&nbsp;<?php echo $emp_row['fname']." ".$emp_row['lname']; ?>
                          </td>
                          <?php 
                            if($counter%8 == 0)
                              echo "</tr><tr>";
                          $counter++;?>
                         @endforeach
                        </tr>
                      </tbody>
                  </table>                                
              </section>
             </div>
            </div>
          </section>
         </div>
      </div>
       {{ Form::submit('Update Settings', array('class' => 'btn btn-success')) }} 
       {{ Form::close() }}
       
        <!-- page end-->
<script type="text/javascript">
  var zoneCount = $('#zone_count').val();
  $('#add_new_zone_index').click(function(){
    if(zoneCount == 1)
      zoneCount =2;
    var str = '<tr><td><input type="text" value="" name="_zone_index_'+zoneCount+'" id="_zone_index_'+zoneCount+'" class="form-control dpd1"></td></tr>'; 
    $('#zone_table  > tbody:last').append(str);
    zoneCount = parseInt(zoneCount) + parseInt("1");
    $('#zone_count').val(zoneCount);
  });
////////////////////
  var glcodeCount = $('#glcode_count').val();
  $('#add_new_glcode_index').click(function(){
    if(glcodeCount == 1)
      glcodeCount =2;
    var str = '<tr><td><input type="text" value="" name="_gl_tags_index_'+glcodeCount+'" id="_gl_tags_index_'+glcodeCount+'" class="form-control dpd1"></td></tr>'; 
    $('#glcode_table  > tbody:last').append(str);
    glcodeCount = parseInt(glcodeCount) + parseInt("1");
    $('#glcode_count').val(glcodeCount);
  });  
//////////////////////
var scopCount = $('#scop_work_count').val();
  $('#add_new_scop_work').click(function(){
    if(scopCount == 1)
      scopCount =2;
    var str = '<tr><th style="text-align:center;"><input type="text" value="" name="scope_template_name_'+scopCount+'" id="scope_template_name_'+scopCount+'" class="form-control dpd1"></th><td><textarea rows="2" cols="20" name="scope_template_'+scopCount+'" id="scope_template_'+scopCount+'" class="form-control dpd1"></textarea></td></tr>'; 
    $('#scop_work_table  > tbody:last').append(str);
    scopCount = parseInt(scopCount) + parseInt("1");
    $('#scop_work_count').val(scopCount);
  });  
</script>
<?php 
 $uriSegment = Request::segment(2);
 if ($uriSegment == 'index') { ?>
  <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
  <script src="{{asset('js/common-scripts.js')}}"></script> 
<?php } ?>
@stop