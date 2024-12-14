@extends("layouts/dashboard_master")
@section('content')
  <section>
    
  </section>
@stop
@section('dashboard_panels')
<style type="text/css">
   #flip-scroll .cf:after { visibility: hidden; display: block; font-size: 0; content: " "; clear: both; height: 0; }
    #flip-scroll * html .cf { zoom: 1; }
    #flip-scroll *:first-child+html .cf { zoom: 1; }
    #flip-scroll table { width: 100%; border-collapse: collapse; border-spacing: 0; }

    #flip-scroll th,
    #flip-scroll td { margin: 0; vertical-align: top; }
    #flip-scroll th { text-align: left; }
    #flip-scroll table { display: block; position: relative; width: 100%; }
    #flip-scroll thead { display: block; float: left; }
    #flip-scroll tbody { display: block; width: auto; position: relative; overflow-x: auto; white-space: nowrap; }
    #flip-scroll thead tr { display: block; }
    #flip-scroll th { display: block; text-align: right; }
    #flip-scroll tbody tr { display: inline-block; vertical-align: top; }
    #flip-scroll td { display: block; min-height: 1.25em; text-align: left; }


    /* sort out borders */

    #flip-scroll th { border-bottom: 0; border-left: 0; }
    #flip-scroll td { border-left: 0; border-right: 0; border-bottom: 0; }
    #flip-scroll tbody tr { border-left: 1px solid #babcbf; }
    #flip-scroll th:last-child,
    #flip-scroll td:last-child { border-bottom: 1px solid #babcbf; }
</style>
<?php //header('Content-Type: application/pdf'); ?>
              <!-- page start-->
          <div class="row">
            <div class="col-sm-12">
              <section class="panel">
              <header class="panel-heading">
                 SALES TRACKING  
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b>Search By:<i>Dates and fields for sales tracking data.</i></b>
                </header>
                 <?php $uriSegment = Request::segment(2);?> 
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('salestracking/index'), 'files'=>true, 'method' => 'post')) }}
                 <div style="margin:10px; color:red; cursor:pointer;" id="togglerButton">Show / Hide Search Box <i id="toggle_div_plus" class='fa fa-plus'></i></div>
                  <section id="no-more-tables" style="padding:10px;" mySection="hide_n_show">
                          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                            <tbody>
                              <tr>
                                  <td data-title="Date Entered Start:">
                                    {{Form::label('SDate', 'Date Entered Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('SDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'SDate')) }}
                                  </td><td data-title="Date Entered End:">
                                    {{Form::label('EDate', 'Date Entered End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('EDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'EDate')) }}
                                  </td>
                                  <td data-title="Status Date Change Start:">
                                    {{Form::label('StatusSDate', 'Status Date Change Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('StatusSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'StatusSDate')) }}
                                  </td>
                                  <td data-title="Status Date Change End:">
                                    {{Form::label('StatusEDate', 'Status Date Change End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('StatusEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'StatusEDate')) }}
                                  </td>
                                  <td data-title="Activity Date Start:">
                                    {{Form::label('ActivitySDate', 'Activity Date Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('ActivitySDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'ActivitySDate')) }}
                                  </td>
                                  <td data-title="Activity Date End:">
                                    {{Form::label('ActivityEDate', 'Activity Date End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('ActivityEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'ActivityEDate')) }}
                                  </td>
                                  <td data-title="Expected Sale Close SDate:">
                                    {{Form::label('CloseSDate', 'Expected Sale Close SDate:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('CloseSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'CloseSDate')) }}
                                  </td>
                                  <td data-title="Expected Sale Close EDate:">
                                    {{Form::label('CloseEDate', 'Expected Sale Close EDate:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('CloseEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'CloseEDate')) }}
                                  </td>
                                </tr>
                                <tr> <!-- 2nd Row-->
                                  <td data-title="Lead Number Start:">
                                    {{Form::label('LeadNumberStart', 'Lead Number Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('LeadNumberStart','', array('class' => 'form-control', 'id' => 'LeadNumberStart')) }}
                                  </td>
                                  <td data-title="Lead Number End:">
                                    {{Form::label('LeadNumberEnd', 'Lead Number End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('LeadNumberEnd','', array('class' => 'form-control', 'id' => 'LeadNumberEnd')) }}
                                  </td>
                                  <td data-title="Job Number:">
                                    {{Form::label('jobNumber', 'Job Number:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('jobNumber','', array('class' => 'form-control', 'id' => 'jobNumber')) }}
                                  </td>
                                  <td data-title="Sales Person:">
                                    {{Form::label('optEmployee', 'Sales Person:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optEmployee', $salesp_arr, null, ['id' => 'optEmployee', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Status:">
                                    {{Form::label('optStatus', 'Status:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optStatus',array(''=>'ALL','Contact'=>'Contact','Quote'=>'Quote','Won'=>'Won','Lost'=>'Lost','Dead'=>'Dead'), null, ['id' => 'optStatus', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Sort By:">
                                    {{Form::label('optSort', 'Sort By:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optSort',array('customer_name'=>'Customer Name','id'=>'Lead Id','id desc'=>'Lead Id Desc','enter_date'=>'Date Entered','enter_date desc'=>'Date Entered Desc'),'customer_name', ['id' => 'optSort', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td colspan="2">
                                    {{Form::submit('Submit', array('class' => 'btn btn-info'))}}
                                    {{Form::button('Reset', array('class' => 'btn btn-danger', 'id'=>'reset_search_form'))}} 
                                  </td>
                                </tr>
                            </tbody>
                          </table>
                      </section>
                               {{ Form::close() }}
              </section>
              </section>
              </div>
                <div class="row">
            <div class="col-sm-12">
               <!-- ////////////////////////////////////////// -->
              <div class="panel">
              <div class="adv-table">
              <section id="flip-scroll" >
                  <table class="table table-bordered table-striped table-condensed cf">
                        <thead class="cf">
                          <tr>
                            <th style="text-align:center;" data-title="">Action</th>
                            <th style="text-align:center;" data-title="">Lead</th>
                            <th style="text-align:center;" data-title="">Quote Attached</th>
                            <th style="text-align:center;" data-title="">Prospect / CustomerName</th>
                            <th style="text-align:center;" data-title="">Date Entered</th>
                            <th style="text-align:center;" data-title="">Renewal Status</th>
                            <th style="text-align:center;" data-title="">Location</th>
                            <th style="text-align:center;" data-title="">Type of Sale</th>
                            <th style="text-align:center;" data-title="">Quote/Opportunity Name</th>
                            <th style="text-align:center;" data-title="">Projected Sale Price</th>
                            <th style="text-align:center;" data-title="">Including Tax [Y/N]</th>
                            <th style="text-align:center;" data-title="">Status</th>
                            <th style="text-align:center;" data-title="">Activity Date</th>
                            <th style="text-align:center;" data-title="">Activity</th>
                            <th style="text-align:center;" data-title="">Labor Costs</th>
                            <th style="text-align:center;" data-title="">Rental Price </th>
                            <th style="text-align:center;" data-title="">Material Costs</th>
                            <th style="text-align:center;" data-title="">Total Cost</th>
                            <th style="text-align:center;" data-title="">Dollars Won </th>
                            <th style="text-align:center;" data-title="">Status Date Change</th>
                            <th style="text-align:center;" data-title="">W/O#  </th>
                            <th style="text-align:center;" data-title="">Invoice#</th>
                            <th style="text-align:center;" data-title="">Expected Date to close sale</th>
                            <th style="text-align:center;" data-title="">Sales Person</th>
                            <th style="text-align:center;" data-title="">Contact Info</th>
                            <th style="text-align:center;" data-title="">Contact Info F.List</th>
                            <th style="text-align:center;" data-title="">Need to Subcontact</th>
                            <th style="text-align:center;" data-title="">If Yes? Name of contractor</th>
                            <th style="text-align:center;" data-title="">Attach Email</th>
                            <th style="text-align:center;" data-title="">Manage Attachments</th>
                          </tr>
                        </thead>
                      <tbody>
                       @foreach($query_data as $getSalesRow)
                          <tr>
                            <td>
                            <a id="{{$getSalesRow['id']}}" name="edit_st_row" data-toggle="modal" class="btn btn-primary btn-xs" href="#myModal4"><i class="fa fa-pencil-square-o"></i></a>
                              {{ Form::open(array('method' => 'DELETE','id'=>'myForm'.$getSalesRow['id'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('salestracking.destroy',$getSalesRow['id']))) }}
                              {{ Form::button('<i class="fa fa-trash-o"></i>', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$getSalesRow['id'].'").submit()')) }}
                              {{ Form::close() }}
                            </td>
                            <td>{{$getSalesRow['id']}}</td>
                            <td>
                              <?php
                                $quoteNum = '';
                                switch ($getSalesRow['type_of_sale']) {
                                    case 'PMcontract':
                                        $qry = DB::select(DB::raw("select concat(a.id,'~~',a.job_num) as res from gpg_consum_contract a, gpg_sales_tracking_consum_contract b where a.id = b.gpg_consum_contract_id and b.gpg_sales_tracking_id = '".$getSalesRow['id']."'"));
                                        $qouteNumPtr = explode('~~',@$qry[0]->res);
                                        if(isset($qouteNumPtr['1'])){ ?>
                                        {{ HTML::link('job/job_electrical_quote_frm/'.$qouteNumPtr['0'].'/'.$qouteNumPtr['1'].'',$qouteNumPtr['1'], array('target'=>'_blank','class'=>'btn btn-link'))}} 
                                        <?php
                                        }else
                                          echo "-";
                                        break;
                                    case 'ServiceQuote':
                                       $qry2 = DB::select(DB::raw("select a.id,a.job_num as job_num from gpg_shop_work_quote a, gpg_sales_tracking_shop_work_quote b where a.id = b.gpg_shop_work_quote_id and b.gpg_sales_tracking_id = '".$getSalesRow['id']."'"));
                                       $quoteID = @$qry2[0]->id;
                                       $quoteNum = @$qry2[0]->job_num;
                                       if(isset($quoteNum)){ ?>
                                        {{ HTML::link('job/job_electrical_quote_frm/'.$quoteID.'/'. $quoteNum.'', $quoteNum, array('target'=>'_blank','class'=>'btn btn-link'))}} 
                                        <?php
                                        }else
                                          echo "-";
                                        break;
                                    case 'Electrical':
                                    case 'Generators':
                                        $qr3 = DB::select(DB::raw("SELECT a.id,a.job_num FROM gpg_job_electrical_quote a, gpg_sales_tracking_job_electrical_quote b WHERE a.id = b.gpg_job_electrical_quote_id AND b.gpg_sales_tracking_id = '".$getSalesRow['id']."' ORDER BY a.electrical_status DESC ,a.job_num ASC LIMIT 1 "));
                                        $quoteID_arr = @$qr3[0]->id;
                                        $quoteNum_arr = @$qr3[0]->job_num;
                                        if(isset($quoteNum_arr)){ ?>
                                        {{ HTML::link('job/job_electrical_quote_frm/'.$quoteID_arr.'/'.$quoteNum_arr.'',$quoteNum_arr, array('target'=>'_blank','class'=>'btn btn-link'))}} 
                                        <?php
                                        }else
                                          echo "-";
                                        break;
                                    case 'Shop':
                                        $qr4 = DB::select(DB::raw("select a.id,a.job_num from gpg_shop_work_quote a, gpg_sales_tracking_shop_work_quote b where a.id = b.gpg_shop_work_quote_id and b.gpg_sales_tracking_id = '".$getSalesRow['id']."'"));
                                        $quoteID = @$qr4[0]->id;
                                        $quoteNum = @$qr4[0]->job_num;
                                        if(isset($quoteNum)){ ?>
                                        {{ HTML::link('job/job_electrical_quote_frm/'.$quoteID.'/'.$quoteNum.'',$quoteNum, array('target'=>'_blank','class'=>'btn btn-link'))}} 
                                        <?php
                                        }else
                                          echo "-";
                                        break;
                                    case 'Grassivy':
                                        $qr5 = DB::select(DB::raw("SELECT a.id,a.job_num FROM gpg_job_grassivy_quote a, gpg_sales_tracking_job_grassivy_quote b WHERE a.id = b.gpg_job_grassivy_quote_id AND b.gpg_sales_tracking_id = '".$getSalesRow['id']."' ORDER BY a.grassivy_status DESC ,a.job_num ASC LIMIT 1 "));
                                        $quoteID_arr = @$qr5[0]->job_num;
                                        $quoteNum_arr = @$qr5[0]->job_num;
                                        if(isset($quoteNum_arr)){ ?>
                                        {{ HTML::link('job/job_electrical_quote_frm/'.$quoteID_arr.'/'.$quoteNum_arr.'',$quoteNum_arr, array('target'=>'_blank','class'=>'btn btn-link'))}} 
                                        <?php
                                        }else
                                          echo "-";
                                        break;
                                    case 'Special_Project':
                                        $qr6 = DB::select(DB::raw("SELECT a.id,a.job_num FROM gpg_job_special_project_quote a, gpg_sales_tracking_job_special_project_quote b WHERE a.id = b.gpg_job_special_project_quote_id AND b.gpg_sales_tracking_id = '".$getSalesRow['id']."' ORDER BY a.special_project_status DESC ,a.job_num ASC LIMIT 1 "));
                                        $quoteID_arr = @$qr6[0]->job_num;
                                        $quoteNum_arr = @$qr6[0]->job_num;
                                        if(isset($quoteNum_arr)){ ?>
                                        {{ HTML::link('job/job_electrical_quote_frm/'.$quoteID_arr.'/'.$quoteNum_arr.'',$quoteNum_arr, array('target'=>'_blank','class'=>'btn btn-link'))}} 
                                        <?php
                                        }else
                                          echo "-";
                                        break;
                                    default:
                                        echo '-';
                                        break;
                                }
                              ?>
                            </td>
                            <td>{{isset($gpg_customers[$getSalesRow['gpg_customer_id']])?$gpg_customers[$getSalesRow['gpg_customer_id']]:'-'}}</td>
                            <td>{{date('m/d/Y',strtotime($getSalesRow['enter_date']))}}</td>
                            <td>
                              <?php if($getSalesRow['parent_id'] != '' || !empty($getSalesRow['parent_id'])){ 
                                        echo 'Renewal';
                                    } else{
                                       $isThisParent = DB::table('gpg_sales_tracking')->where('parent_id','=',(int)$getSalesRow['id'])->pluck('id');                                   
                                      if(!empty($isThisParent)){
                                        echo "Invoice Renewal";
                                      }else
                                        echo "-";
                                    }
                              ?>
                            </td>
                            <td>{{isset($getSalesRow['location'])?$getSalesRow['location']:'-'}}</td>
                            <td>{{isset($saleTypeArray[$getSalesRow['type_of_sale']])?$saleTypeArray[$getSalesRow['type_of_sale']]:'-'}}</td>
                            <td>{{isset($getSalesRow['opportunity_name'])?$getSalesRow['opportunity_name']:'-'}}</td>
                            <td>
                              @if($getSalesRow['type_of_sale']=="PMcontract" && @$qouteNumPtr['1']!="")
                                <?php echo '-';?>
                              @else
                              {{!empty($getSalesRow['projected_sale_price'])?$getSalesRow['projected_sale_price']:'-'}}
                              @endif
                            </td>
                            <td><input name="includeTax_check" type="checkbox" id="includeTax_{{$getSalesRow['id']}}" value="1" <?php echo ($getSalesRow['include_tax']==1?"checked=\"checked\"":"") ?>  disabled="disabled" /></td>
                            <td>{{isset($getSalesRow['status'])?$getSalesRow['status']:'-'}}</td>
                            <td>
                              <?php  $activityData = explode("#~#",$getSalesRow['activity_data']);
                              echo (@$activityData[1]!=""?date('m/d/Y',strtotime($activityData[1])):"-"); ?>
                            </td>
                            <td title="{{@$activityData[0]}}">{{htmlentities(str_replace("\r\n","<br>",substr($activityData[0],0,20))).'...'}}
                             {{HTML::link('#myModal', 'Edit' , array('class' => 'btn btn-link btn-xs','data-toggle'=>'modal','name'=>'edit_activity','id'=>$getSalesRow['id'],'emp'=>$getSalesRow['gpg_customer_id']))}}
                            </td>
                            <td>{{isset($getSalesRow['labor_cost'])?$getSalesRow['labor_cost']:'-'}}</td>
                            <td>{{isset($getSalesRow['rental_cost'])?$getSalesRow['rental_cost']:'-'}}</td>
                            <td>{{isset($getSalesRow['material_cost'])?$getSalesRow['material_cost']:'-'}}</td>
                            <td>-</td>
                            <td>{{isset($getSalesRow['dollar_won'])?$getSalesRow['dollar_won']:'-'}}</td>
                            <td>{{($getSalesRow['status_change_date']!=""?date('m/d/Y',strtotime($getSalesRow['status_change_date'])):"-")}}</td>
                            <?php $attachedJobNumber = $getSalesRow['job_number'] ;
                                if($attachedJobNumber == "" && $getSalesRow['type_of_sale'] == "Shop"){

                                }?>  
                            <td>{{isset($getSalesRow['w_o_number'])?$getSalesRow['w_o_number']:'-'}}</td>
                            <td>{{isset($getSalesRow['invoice_number'])?$getSalesRow['invoice_number']:'-'}}</td>
                            <td>{{($getSalesRow['close_date']!=""?date('m/d/Y',strtotime($getSalesRow['close_date'])):"-")}}</td>
                            <td>{{isset($getSalesRow['sales_person_name'])?$getSalesRow['sales_person_name']:'-'}}</td>
                            <td>
                              <?php $sp = explode('#@@#',$getSalesRow['contact_info']);
                                    if (preg_match('/First Name::/i',$sp[0]))
                                      $cInfo = str_replace('First Name::','',$sp[0]).' '.str_replace('Last Name::','',$sp[1]);
                                    else
                                      $cInfo = $getSalesRow['contact_info'];
                                      echo !empty($cInfo)?$cInfo:'-';
                                    ?>
                            </td>
                            <td>
                               <?php if(is_object($quoteNum) && $quoteNum->job_num!=""){
                                     echo $quoteNum->main_contact_name;
                                }else
                                  echo '-';
                                ?>
                            </td>
                            <td><input name="subContact_check" type="checkbox" id="subContact_{{$getSalesRow['id']}}" value="1" <?php echo ($getSalesRow['subcontact']==1?"checked=\"checked\"":"") ?> disabled="disabled" /></td>
                            <td>{{isset($getSalesRow['subcontact_name'])?$getSalesRow['subcontact_name']:'-'}}</td>  
                            <td>{{ HTML::link('#myModal3','Email', array('class' => 'btn btn-link btn-xs','data-toggle'=>'modal','id'=>$getSalesRow['id'],'name'=>'emailModal'))}} </td>
                            <td>{{HTML::link('#myModal2', 'Manage Files' , array('class' => 'btn btn-link btn-xs','data-toggle'=>'modal','name'=>'manage_files','id'=>$getSalesRow['id']))}}</td>
                          </tr>
                       @endforeach
                      </tbody>
                  </table>
                  {{ HTML::link("job/excelExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}                   
                  <br/>{{ $query_data->appends(array_filter(Input::except('_token')))->links() }}
                </section>
                        <!-- Modal -->
                          <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                {{ Form::open(array('before' => 'csrf' ,'url'=>route('salestracking/updateCSTModal'), 'files'=>true,'id'=>'submit_stm', 'method' => 'post')) }}
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                              <h4 class="modal-title"> FOLLOWUP DETAILS [Lead: <span id="show_fd" style="display:inline;"></span>]</h4>
                                              <input type="hidden" name="sales_id" id="sales_id">
                                          </div>
                                          <div class="modal-body">
                                            <section id="no-more-tables" >
                                              <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                                                  <tbody class="cf">
                                                      <tr>
                                                        <th>Contact Person:</th><td>{{ Form::select('contactPerson',array(''=>'Select Contact Person')+$salesp_arr,'', array('class' => 'form-control', 'id' => 'contactPerson')) }}</td>
                                                      </tr>
                                                       <tr>
                                                        <th>Location:</th><td>{{ Form::text('location','', array('class' => 'form-control', 'id' => 'location')) }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th>Activity Date:</th><td>{{ Form::text('CDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'CDate')) }}</td>
                                                      </tr>
                                                       <tr>
                                                        <th>Activity Time:</th><td>{{ Form::text('timeTotal','', array('class' => 'form-control timepicker-default', 'id' => 'timeTotal')) }}</td>
                                                      </tr>
                                                       <tr>
                                                        <th>Activity:</th><td>{{ Form::textarea('contactDetails','', array('class' => 'form-control', 'id' => 'contactDetails')) }}</td>
                                                      </tr>
                                                  </tbody>  
                                              </table>
                                            </section>  
                                          </div>
                                          <div class="btn-group" style="padding:20px;">
                                          {{Form::button('Save', array('class' => 'btn btn-success','data-dismiss'=>'modal','id'=>'save_data'))}}
                                          {{Form::button('Close', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                                      </div>
                                  </div>
                              </div>
                              {{Form::close()}}
                          </div>
                        <!-- modal -->
                      <!-- Modal#2 -->
                     <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                <h4 class="modal-title">ATTACHMENT MANAGEMENT</h4>
                                </div>
                              <div class="modal-body">
                              {{ Form::open(array('before' => 'csrf' ,'id'=>'submit_file_form','url'=>route('salestracking/manageSTFiles'),'files'=>true, 'method' => 'post')) }}   {{Form::hidden('fjob_id','',array('id' => 'change_job_id' ))}}
                                <div class="form-group">
                                    <section id="no-more-tables"  style="padding:10px;">
                                      <table class="table table-bordered table-striped table-condensed cf">
                                        <thead class="cf">
                                          <tr><th>#</th><th>File Name</th><th>Action</th></tr>
                                        </thead>
                                        <tbody class="cf" id="display_quote_files">
                                          </tbody>
                                        </table>
                                    </section> 
                                  <div style="display: inline;">
                                   {{ Form::file('attachment', array('style'=>'float: left !important; display:inline !important; width:50%;' ,'id' => 'attachment')) }}
                                  </div> 
                                </div>
                             {{Form::close()}}
                            <div class="btn-group" style="padding:20px;">
                              {{Form::button('Submit', array('class' => 'btn btn-success', 'id'=>'submit_attachments'))}}
                             {{Form::button('Cancel', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                            </div>
                          </div>
                        </div>
                      </div>
                  </div>
              <!-- modal#2 end--> 
               <!-- Modal#3 -->
                          <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                              <h4 class="modal-title">Email</h4>
                                          </div>
                                          <div class="modal-body">
                                              <section id="no-more-tables">
                                              <table class="table table-bordered table-striped table-condensed cf">
                                              <tbody>
                                                <tr><th><b>From</b></th><td>{{ Form::text('from_user','', array('class' => 'form-control', 'id' => 'from_user','readOnly')) }}</td></tr>
                                                <tr><th><b>To</b></th><td>{{ Form::text('to_user','', array('class' => 'form-control', 'id' => 'to_user','readOnly')) }}</td></tr>
                                                <tr><th><b>Date</b></th><td>{{ Form::text('to_date','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'to_date','readOnly')) }}</td></tr>
                                                <tr><th><b>Subject</b></th><td>{{ Form::text('subject_email','', array('class' => 'form-control', 'id' => 'subject_email','readOnly')) }}</td></tr>
                                                <tr><th><b>Content</b></th><td>{{ Form::textArea('content_email','', array('class' => 'form-control', 'id' => 'content_email','readOnly')) }}</td></tr>
                                              </tbody>  
                                              </table>
                                            </section>                                        
                                         </div>
                                       <div class="btn-group" style="padding:20px;">
                                          {{Form::button('Close', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                                        </div>   
                                  </div>
                              </div>
                          </div>
                        <!-- modal#3 end -->
                        <!-- Modal#4 -->
                          <div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                {{ Form::open(array('before' => 'csrf' ,'url'=>route('salestracking/updateSTRModal'), 'files'=>true,'id'=>'submit_rst', 'method' => 'post')) }}
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                              <h4 class="modal-title"> FOLLOWUP DETAILS [Lead: <span id="show_fd" style="display:inline;"></span>]</h4>
                                              <input type="hidden" name="row_id" id="row_id">
                                          </div>
                                          <div class="modal-body">
                                            <section id="no-more-tables" >
                                              <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                                                  <tbody class="cf">
                                                      <tr>
                                                        <th>Prospect / CustomerName:</th><td>{{ Form::select('cusName',array(''=>'Select Contact Person')+$gpg_customers,'', array('class' => 'form-control', 'id' => 'cusName')) }}</td>
                                                      </tr>
                                                       <tr>
                                                        <th>Date Entered:</th><td>{{ Form::text('enterDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'enterDate')) }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th>Location:</th><td>{{ Form::text('location_field','', array('class' => 'form-control', 'id' => 'location_field')) }}</td>
                                                      </tr>
                                                       <tr>
                                                        <th>Type of Sale:</th><td>{{ Form::select('saleType',array(''=>'SaleType','PMcontract'=>'PM contract','Rental'=>'Rental','ServiceQuote'=>'Service Quote','Electrical'=>'Electrical','Generators'=>'Generators','Shop'=>'Shop','Permits'=>'Permits','OtherDesc'=>'Other: Desc','Grassivy'=>'Grassivy','Special_Project'=>'Special Project'),'', array('class' => 'form-control', 'id' => 'saleType')) }}</td>
                                                      </tr>
                                                       <tr>
                                                        <th>Quote/Opportunity Name:</th><td>{{ Form::text('opName','', array('class' => 'form-control', 'id' => 'opName')) }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th>Projected Sale Price:</th><td>{{ Form::text('pSale','', array('class' => 'form-control', 'id' => 'pSale')) }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th>Including Tax [Y/N]:</th><td>{{ Form::checkbox('includeTax','', array('class' => 'form-control', 'id' => 'includeTax')) }}</td>
                                                      </tr>
                                                       <tr>
                                                        <th>Status:</th><td>{{ Form::select('leadStatus',array(''=>'Status','Contact'=>'Contact','Quote'=>'Quote','Won'=>'Won','Lost'=>'Lost','Dead'=>'Dead'),'', array('class' => 'form-control', 'id' => 'leadStatus')) }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th>Labor Costs:</th><td>{{ Form::text('laborCost','', array('class' => 'form-control', 'id' => 'laborCost')) }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th>Rental Price:</th><td>{{ Form::text('rentCost','', array('class' => 'form-control', 'id' => 'rentCost')) }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th>Material Costs:</th><td>{{ Form::text('matCost','', array('class' => 'form-control', 'id' => 'matCost')) }}</td>
                                                      </tr>
                                                       <tr>
                                                        <th>Total Cost:</th><td>{{ Form::text('tCost','', array('class' => 'form-control', 'id' => 'tCost')) }}</td>
                                                      </tr>
                                                      </tr>
                                                      <tr>
                                                        <th>Dollars Won :</th><td>{{ Form::text('won','', array('class' => 'form-control', 'id' => 'won')) }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th>Invoice#:</th><td>{{ Form::text('invoice','', array('class' => 'form-control', 'id' => 'invoice')) }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th>Expected Date to close sale:</th><td>{{ Form::text('closeDate','', array('class' => 'form-control  form-control-inline input-medium default-date-picker', 'id' => 'closeDate')) }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th>Sales Person:</th><td>{{ Form::select('salesPerson',array(''=>'Select Sales Person')+$salesp_arr,'', array('class' => 'form-control', 'id' => 'salesPerson')) }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th>Contact Info:</th><td>{{ Form::text('contactInfoLabel','', array('class' => 'form-control', 'id' => 'contactInfoLabel')) }}</td>
                                                      </tr>
                                                       <tr>
                                                        <th>Need to Subcontact:</th><td>{{ Form::checkbox('subContact','', array('class' => 'form-control', 'id' => 'subContact')) }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th>If Yes Name of contractor:</th><td>{{ Form::text('subConName','', array('class' => 'form-control', 'id' => 'subConName')) }}</td>
                                                      </tr>
                                                  </tbody>  
                                              </table>
                                            </section>  
                                          </div>
                                          <div class="btn-group" style="padding:20px;">
                                          {{Form::button('Save', array('class' => 'btn btn-success','data-dismiss'=>'modal','id'=>'update_data'))}}
                                          {{Form::button('Close', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                                      </div>
                                  </div>
                              </div>
                              {{Form::close()}}
                          </div>
                        <!-- modal#4 -->
              </div>
              </div>
              </section>
              </div>
              </div>
              <!-- page end-->
    <script type="text/javascript">
      $('.default-date-picker').datepicker({
          format: 'yyyy-mm-dd'
      });
      $('.timepicker-default').timepicker();
      $('a[name=modalInfo]').on('click',function(){
        $('#JobNum').html($(this).attr('job_Nm'));
        $.ajax({
            url: "{{URL('ajax/getInvoiceInfo')}}",
              data: {
               'job_id' : $(this).attr('id')
              },
            success: function (data) {
              $('#display_invoice_info').html(data);
            },
        });
      });

      $("section[mysection=hide_n_show]").hide();
      $('#togglerButton').click(function(){
         $("section[mysection=hide_n_show]").toggle("slow");
         if ($('#toggle_div_plus').attr("class") == "fa fa-plus")
            $('#toggle_div_plus').removeClass('fa fa-plus').addClass('fa fa-minus');
         else 
            $('#toggle_div_plus').removeClass('fa fa-minus').addClass('fa fa-plus');
      }); 

      $('#reset_search_form').click(function(){
              $('#SDate').val("");
              $('#EDate').val("");
              $('#StatusSDate').val("");
              $('#StatusEDate').val("");
              $('#ActivitySDate').val("");
              $('#ActivityEDate').val("");
              $('#CloseSDate').val("");
              $('#CloseEDate').val("");
              $('#LeadNumberStart').val("");
              $('#LeadNumberEnd').val("");
              $('#jobNumber').val("");
              $('#optEmployee').val("");
              $('#optStatus').val("");
              $('#optSort').val("");
      });
$('a[name=edit_activity]').click(function(){
  var id = $(this).attr('id');
  var emp = $(this).attr('emp');
  $('#show_fd').html(id);
  $('#contactPerson').val(emp);
  $('#sales_id').val(id);
  $.ajax({
    url: "{{URL('ajax/getContactSTInfo')}}",
      data: {
        'id' : id
      },
      success: function (data) {
        $('#contactDetails').val(data.contact_note);
        $('#location').val(data.location);
        $('#timeTotal').val(data.time);
      },
  });
});
$('#save_data').click(function(){
  $('#submit_stm').submit();
});
 $('a[name=manage_files]').click(function(){
        var job_id = $(this).attr('id');
        $('#change_job_id').val(job_id);
        $.ajax({
              url: "{{URL('ajax/getSTFiles')}}",
              data: {
                'id' : job_id
              },
            success: function (data) {
              $('#display_quote_files').html(data);
               $('a[name=del_quote_file]').click(function(){
                var result = confirm("Are you sure! you want to delete....?");
                if(result){
                  $.ajax({
                        url: "{{URL('ajax/deleteSTFile')}}",
                        data: {
                          'id' : $(this).attr('id')
                        },
                        success: function (data) {
                          if (data == 1){     
                            alert("Deleted Successfully!");
                            location.reload();
                          }
                      },
                  });
                }  
               });
            },
        });
      });
 $('#submit_attachments').click(function(){
    $('#submit_file_form').submit();
  });
 $('a[name=edit_st_row]').click(function(){
    var id = $(this).attr('id');
    $('#row_id').val(id);
    $.ajax({
        url: "{{URL('ajax/getSTRowData')}}",
          data: {
            'id' : $(this).attr('id')
          },
          success: function (data) {
            $('#cusName').val(data.gpg_customer_id);
            $('#enterDate').val(data.enter_date);
            $('#location_field').val(data.location);
            $('#saleType').val(data.sale_type_val);
            $('#opName').val(data.opportunity_name);
            $('#pSale').val(data.projected_sale_price);
            $('#includeTax').val(data.include_tax);
            $('#leadStatus').val(data.status);
            $('#laborCost').val(data.labor_cost);
            $('#rentCost').val(data.rental_cost);
            $('#matCost').val(data.material_cost);
            $('#tCost').val(data.material_cost);
            $('#invoice').val(data.invoice_number);
            $('#contactInfoLabel').val(data.contact_info);
            $('#contactInfoLabel').val(data.contact_info);
            $('#subContact').val(data.subcontact);
            $('#subConName').val(data.subcontact_name);
        },
    });
 });
 $('#update_data').click(function(){
    $('#submit_rst').submit();
 });
</script>
@stop