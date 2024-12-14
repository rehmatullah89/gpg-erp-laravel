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
                SERVICE FIELD WORK LISTING
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                </header>
                 <?php $uriSegment = Request::segment(2);?> 
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('job/'.$uriSegment), 'files'=>true, 'method' => 'post')) }}
                 <div style="margin:10px; color:red; cursor:pointer;" id="togglerButton">Show / Hide Search Box <i id="toggle_div_plus" class='fa fa-plus'></i></div>
                  <section id="no-more-tables" style="padding:10px;" mySection="hide_n_show">
                          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                            <tbody>
                              <tr>
                                  <td data-title="Date Created Start:">
                                    {{Form::label('CreatedSDate', 'Date Created Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('CreatedSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'CreatedSDate')) }}
                                  </td>
                                  <td data-title="Date Created End:">
                                    {{Form::label('CreatedEDate', 'Date Created End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('CreatedEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'CreatedEDate')) }}
                                  </td>
                                  <td data-title="Date Job Won Start:">
                                    {{Form::label('JobWonSDate', 'Date Job Won Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('JobWonSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'JobWonSDate')) }}
                                  </td>
                                  <td data-title="Date Job Won End:">
                                    {{Form::label('JobWonEDate', 'Date Job Won End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('JobWonEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'JobWonEDate')) }}
                                  </td>
                                  <td data-title="Invoice Date Start:">
                                    {{Form::label('InvoiceSDate', 'Invoice Date Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('InvoiceSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'InvoiceSDate')) }}
                                  </td>
                                  <td data-title="Invoice Date End:">
                                    {{Form::label('InvoiceEDate', 'Invoice Date End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('InvoiceEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'InvoiceEDate')) }}
                                  </td>
                                  <td data-title="Quote Number:">
                                    {{Form::label('optJobNumber', 'Quote Number:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('optJobNumber','', array('class' => 'form-control', 'id' => 'optJobNumber')) }}
                                  </td>
                                  <td data-title="Attached Job Number:">
                                    {{Form::label('optAttJobNumber', 'Attached Job Number:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('optAttJobNumber','', array('class' => 'form-control', 'id' => 'optAttJobNumber')) }}
                                  </td>
                                </tr>
                                <tr>
                                <td>
                                  <span class="smallblack">
                                  {{ Form::checkbox('ignoreCostDate','1','', array('id'=>'ignoreCostDate','class' => 'input-group','style'=>'display:inline;')) }}
                                  Ignore Date stamp  on Material Cost and Labor Cost.<br />
                                  {{ Form::checkbox('ignoreInvoiceDate','1','', array('id'=>'ignoreInvoiceDate','class' => 'input-group','style'=>'display:inline;')) }}
                                  Ignore Date stamp  on Invoice Amount.
                                </td>
                                <td data-title="Sales Person:">
                                    {{Form::label('optEmployee', 'Sales Person:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optEmployee', $salesp_arr, null, ['id' => 'optEmployee', 'class'=>'form-control m-bot15'])}}
                                </td>
                                <td data-title="Customer:">
                                    {{Form::label('optCustomer', 'Customer:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optCustomer', $cust_arr, null, ['id' => 'optCustomer', 'class'=>'form-control m-bot15'])}}
                                </td>
                                <td data-title="Job Status:">
                                    {{Form::label('optJobStatus', 'Job Status:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optJobStatus', array(''=>'ALL',"completed"=>"Have been Completed","notcompleted"=>"Jobs Not Completed","invoiced"=>"Have been Invoiced","comp_inv"=>"Have been Invoiced and Completed","not_comp_inv"=>"Have been Invoiced but Not Completed","not_invoiced"=>"Have Not been Invoiced","completed_not_invoiced"=>"Completed but Have Not been Invoiced","completed_not_closed"=>"Completed Not Closed","closed_not_completed"=>"Closed Not Completed"), null, ['id' => 'optJobStatus', 'class'=>'form-control m-bot15'])}}
                                </td>
                                <td data-title="Quote Status:">
                                    {{Form::label('optStatus', 'Quote Status:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optStatus', array(''=>'ALL',"Quote"=>"Quote","Won"=>"Won","Dead"=>"Dead","Lost"=>"Lost"), null, ['id' => 'optStatus', 'class'=>'form-control m-bot15'])}}
                                </td>
                                <td data-title="Sort By:">
                                    {{Form::label('optSort', 'Sort By:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optSort', array(''=>'ALL',"customerAndDate"=>"Customer and Date","salespersonAndDate"=>"Salesperson and Date"), null, ['id' => 'optSort', 'class'=>'form-control m-bot15'])}}
                                </td>
                                <td data-title="Attached Jobs:">
                                    {{Form::label('optAttachedJob', 'Attached Jobs:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optAttachedJob', array(''=>'ALL',"customerAndDate"=>"Customer and Date","salespersonAndDate"=>"Salesperson and Date"), null, ['id' => 'optAttachedJob', 'class'=>'form-control m-bot15'])}}
                                </td>
                                <td data-title="Quote/Opportunity Name:">
                                    {{Form::label('optOpportunity', 'Quote/Opportunity Name:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optOpportunity',$quote_op_arr, null, ['id' => 'optOpportunity', 'class'=>'form-control m-bot15'])}}
                                </td>
                                </tr>
                              </tbody>
                          </table>
                                    <br/>
                                  {{Form::submit('Submit', array('class' => 'btn btn-info', 'style'=>'margin-top:-15px;'))}}
                                  {{Form::button('Reset', array('class' => 'btn btn-danger', 'style'=>'margin-top:-15px;', 'id'=>'reset_search_form'))}} 
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
                            <th style="text-align:center;" data-title="">Delete</th>
                            <th style="text-align:center;" data-title="">Created Date</th>
                            <th style="text-align:center;" data-title="">Customer</th>
                            <th style="text-align:center;" data-title="">Location</th>
                            <th style="text-align:center;" data-title="">Salesperson</th>
                            <th style="text-align:center;" data-title="">Quote/Opportunity Name</th>
                            <th style="text-align:center;" data-title="">Lead Id</th>
                            <th style="text-align:center;" data-title="">Quote Number</th>
                            <th style="text-align:center;" data-title="">Quoted Amount</th>
                            <th style="text-align:center;" data-title="">Quoted Material Cost</th>
                            <th style="text-align:center;" data-title="">Quoted Labor Cost</th>
                            <th style="text-align:center;" data-title="">Projected Margin</th>
                            <th style="text-align:center;" data-title="">Index 1</th>
                            <th style="text-align:center;" data-title="">Contact Person</th>
                            <th style="text-align:center;" data-title="">Scope of Work</th>
                            <th style="text-align:center;" data-title="">Status</th>
                            <th style="text-align:center;" data-title="">Date Job Won</th>
                            <th style="text-align:center;" data-title="">Job Number</th>
                            <th style="text-align:center;" data-title="">Invoice Date</th>
                            <th style="text-align:center;" data-title="">Invoice Number</th>
                            <th style="text-align:center;" data-title="">Invoice Amount</th>
                            <th style="text-align:center;" data-title="">Sales Tax</th>
                            <th style="text-align:center;" data-title="">Labor Cost</th>
                            <th style="text-align:center;" data-title="">Marerial Cost</th>
                            <th style="text-align:center;" data-title="">Total Cost</th>
                            <th style="text-align:center;" data-title="">Net Margin</th>
                            <th style="text-align:center;" data-title="">Index 2</th>
                            <th style="text-align:center;" data-title="">Comm. Owed</th>
                            <th style="text-align:center;" data-title="">Comm. Paid</th>
                            <th style="text-align:center;" data-title="">Date Comm. Paid</th>
                            <th style="text-align:center;" data-title="">Comm. Balance</th>
                            <th style="text-align:center;" data-title="">Manage Attachments</th>
                          </tr>
                        </thead>
                      <tbody>
                        @foreach($query_data as $getRow)
                          <tr>
                            <td data-title="Del:" style="padding-bottom:7.7px">{{ Form::checkbox('delChk[]',$getRow['id'],'', array('id'=>'delChk[]','class' => 'input-group')) }}</td>
                            <td style="padding-bottom:7.7px">{{($getRow['created_on']!=""?date('m/d/Y',strtotime($getRow['created_on'])):"-")}}</td>
                            <td style="padding-bottom:7.7px" title="{{$getRow['customer']}}">{{($getRow['customer']!=''?substr($getRow['customer'],0,20):'-')}}</td>
                            <td style="padding-bottom:7.7px" title="{{$getRow['eqp_location']}}">{{($getRow['eqp_location']!=''?substr($getRow['eqp_location'],0,20):'-')}}</td>
                            <td style="padding-bottom:7.7px">{{($getRow['salesPerson']!=''?$getRow['salesPerson']:'-')}}</td>
                            <td style="padding-bottom:7.7px">{{$getRow['opportunity_name']}}</td>
                            <td style="padding-bottom:7.7px">{{($getRow['attachLeadId'] !=''?$getRow['attachLeadId']:'-')}}</td>
                            <td style="padding-bottom:7.7px">{{ HTML::link('job/work_order_frm/'.$getRow['id'].'/'.$getRow['job_num'].'', $getRow['job_num'] , array('target'=>'_blank','class'=>'btn btn-link btn-xs', 'id'=>$getRow['id'],'j_num'=>$getRow['job_num']))}}</td>
                            <td style="padding-bottom:7.7px">{{($getRow['grand_list_total'] !=''?'$'.number_format($getRow['grand_list_total'],2):'-')}}</td>
                            <td style="padding-bottom:7.7px">{{($getRow['freight'] !=''?'$'.number_format($getRow['mat_cost_total']+$getRow['comp_cost_total']+($getRow['mat_cost_total']*($getRow['tax_amount']*.01))+$getRow['freight'],2):'-')}}</td>
                            <td style="padding-bottom:7.7px">{{($getRow['mileage'] !=''?'$'.number_format($getRow['labor_cost_total']+($getRow['sub_cost_total']*($getRow['hazmat']*.01))+$getRow['mileage'],2):'-')}}</td>
                            <td style="padding-bottom:7.7px">{{'$'.number_format($getRow['grand_list_total']-$getRow['grand_cost_total'],2)}}</td>
                            <td style="padding-bottom:7.7px">{{($getRow['sums_sum'] !=''?$getRow['sums_sum']:'-')}}</td>
                            <td style="padding-bottom:7.7px">{{($getRow['main_contact_name'] !=''?$getRow['main_contact_name']:'-')}}</td>
                            <td style="padding-bottom:7.7px"><?php $str=substr( $getRow['task'],0,25); if(strlen( $getRow['task'])>25) echo $str=$str."...";  if(empty($getRow['task'])) echo '-';?></td>
                            
                            <td style="padding-bottom:7.7px">{{($getRow['field_service_work_status'] !=''?$getRow['field_service_work_status']:'-')}}</td>
                            <td style="padding-bottom:7.7px">{{($getRow['date_job_won']!=""?date('m/d/Y',strtotime($getRow['date_job_won'])):"-")}}</td>
                            <td style="padding-bottom:7.7px">{{($getRow['GPG_attach_job_num'] !=''?$getRow['GPG_attach_job_num']:'-')}}</td>
                            <td style="padding-bottom:7.7px">
                            <?php
                                $invoiceData=array();
                                if(isset($getRow['attachJobRes']['invoice_data'])){
                                  $invoiceData = preg_split("/#~#/",$getRow['attachJobRes']['invoice_data']);
                                  echo ($invoiceData[4]>1?"Multiple":($invoiceData[2]!=''?date('m/d/Y',strtotime($invoiceData[2])):"-"));
                                }else{
                                  echo "-";
                                }
                            ?>
                            </td>
                            <td style="padding-bottom:7.7px">
                            <?php
                                if(isset($invoiceData[0]) && isset($invoiceData[4])){
                                  echo ($invoiceData[4]>1?"Multiple":$invoiceData[0]);
                                }else{
                                  echo "-";
                                }
                              ?>
                            </td>
                            <td style="padding-bottom:7.7px">
                            <?php
                              if(isset($invoiceData[1])){
                                echo '$'.number_format($invoiceData[1],2);
                              }else{
                                echo "-";
                              }           
                              ?>
                            </td>   
                            <td style="padding-bottom:7.7px">
                            <?php
                              if(isset($invoiceData[3])){
                                echo number_format($invoiceData[3],2);
                              }else{
                                echo "-";
                              }
                            ?> 
                            </td>  
                            <td style="padding-bottom:7.7px">
                             <?php 
                              if(isset($getRow['attachJobRes']['labor_cost'])){
                                echo '$'.number_format($getRow['attachJobRes']['labor_cost'],2);
                              }else{
                                echo "-";
                              }
                              ?> 
                            </td>
                            <td style="padding-bottom:7.7px">
                              <?php 
                              if(isset($getRow['attachJobRes']['material_cost'])){
                                echo '$'.number_format(doubleval($getRow['attachJobRes']['material_cost']),2);
                              }else{
                                echo "-";
                              }
                              ?>
                            </td> 
                            <td style="padding-bottom:7.7px">
                              <?php
                               $totalCost = 0;
                                if(isset($getRow['attachJobRes']['material_cost']) && isset($getRow['attachJobRes']['labor_cost'])){
                                  $totalCost = $getRow['attachJobRes']['material_cost']+$getRow['attachJobRes']['labor_cost'];
                                  echo '$'.number_format(doubleval($totalCost),2);
                                }else{
                                  echo "-";
                                }
                              ?>
                            </td>
                            <td style="padding-bottom:7.7px">
                              <?php 
                                if(isset($invoiceData[1]) && isset($invoiceData[3])){
                                  echo '$'.number_format($netMargin = $invoiceData[1]-$invoiceData[3]-$totalCost,2);
                                }else
                                  echo "-";
                              ?>
                            </td> 
                            <td style="padding-bottom:7.7px">{{$getRow['time_diff_sum']}}</td>
                            <td style="padding-bottom:7.7px">{{(isset($getRow['attachJobRes']['sales_commission']) && $getRow['attachJobRes']['sales_commission'] !=''?number_format($commOwed =  $saleCom = (($netMargin*(isset($getRow['attachJobRes']['sales_commission'])?$getRow['attachJobRes']['sales_commission']:0))/100),2):'-')}}</td>
                            <td style="padding-bottom:7.7px">{{$getRow['time_diff_sum']}}</td>
                            <td style="padding-bottom:7.7px">{{(isset($commData['amt'])?'$'.number_format($commData['amt'],2):'$0.0')}}</td>
                            <td style="padding-bottom:7.7px">{{(isset($commData['comm_date'])?date('d/m/Y',strtotime($commData['comm_date'])):'-')}}</td>
                            <td style="padding-bottom:7.7px">{{(isset($commData['amt'])?'$'.number_format($commOwed-$commData['amt'],2):'$0.0')}}</td>
                            <td  data-title="Attachments:" style="padding-bottom:7.7px;">{{HTML::link('#myModal4', 'Manage Files' , array('class' => 'btn btn-link btn-xs','data-toggle'=>'modal','name'=>'manage_files','id'=>$getRow['id'],'job_num'=>$getRow['job_num']))}}</td>
                          </tr>
                        @endforeach
                      </tbody>
                  </table>
                </section>
                 {{ Form::button('Delete Selected Jobs', array('class' => 'btn btn-danger', 'id'=>'delete_records'))}}<br/>
                  {{ $query_data->appends(array_filter(Input::except('_token')))->links() }}  
              </div>
              </div>
            </div>
            </div>
          </div>  
              <!-- page end-->
          <!-- Modal# -->
           <div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                      <h4 class="modal-title">ATTACHMENT MANAGEMENT</h4>
                      </div>
                    <div class="modal-body">
                    {{ Form::open(array('before' => 'csrf' ,'id'=>'submit_file_form','url'=>route('job/manageFSWFiles'),'files'=>true, 'method' => 'post')) }}   {{Form::hidden('fjob_id','',array('id' => 'change_job_id' ))}} {{Form::hidden('fjob_num','',array('id' => 'change_job_num' ))}}     <div class="form-group">
                                               <section id="no-more-tables"  style="padding:10px;">
                                               <table class="table table-bordered table-striped table-condensed cf">
                                                <thead class="cf">
                                                  <tr><th>#</th><th>Category Name </th><th>Action</th></tr>
                                                </thead>
                                                <tbody class="cf" id="display_quote_files">
                                                </tbody>
                                                </table>
                                              </section> 
                  <div style="display: inline;">
                   {{ Form::file('attachment', array('style'=>'float: left !important; display:inline !important; width:50%;' ,'id' => 'attachment')) }}
                          </div> </div>
                   {{Form::close()}}
                  <div class="btn-group" style="padding:20px;">
                    {{Form::button('Submit', array('class' => 'btn btn-success', 'id'=>'submit_attachments'))}}
                   {{Form::button('Cancel', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                  </div>
                </div>
              </div>
            </div>
        </div>
        <!-- modal# end-->     
    <script type="text/javascript">
      $('.default-date-picker').datepicker({
          format: 'yyyy-mm-dd'
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
              $('#CreatedSDate').val("");
              $('#CreatedEDate').val("");
              $('#JobWonSDate').val("");
              $('#JobWonEDate').val("");
              $('#InvoiceSDate').val("");
              $('#InvoiceEDate').val("");
              $('#optJobNumber').val("");
              $('#optAttJobNumber').val("");
              $('#ignoreCostDate').val("");
              $('#ignoreInvoiceDate').val("");
              $('#optEmployee').val("");
              $('#optCustomer').val("");
              $('#optJobStatus').val("");
              $('#optStatus').val("");
              $('#optSort').val("");
              $('#optAttachedJob').val("");
              $('#optOpportunity').val("");
      });
    
      $("#delete_records").click(function(){
          checkboxes = document.getElementsByName('delChk[]');
          var count =0;
          for(var i=0, n=checkboxes.length;i<n;i++) {
              if (checkboxes[i].checked == 1){
                count++;
              }
          }
          if (count > 0){
            var result = confirm("Are you sure! you want to delete this/these: "+count+" jobs ....?");
          }else{
              alert("No Item Selected");            
          }
          if (result){         
            for(var i=0, n=checkboxes.length;i<n;i++) {
                if (checkboxes[i].checked == 1){
                    $.ajax({
                        url: "{{URL('ajax/deleteSFW')}}",
                        data: {
                          'id' : $(checkboxes[i]).val()
                        },
                        success: function (data) {
                          if (data == 1){     
                            alert("Deleted Successfully!");
                            location.reload();
                          }
                          else
                            alert('Error while deleting record(s)!')
                      },
                    });
                }
            }
          }
      });

      $('a[name=manage_files]').click(function(){
        var job_num = $(this).attr('job_num');
        var job_id = $(this).attr('id');
        $('#change_job_id').val(job_id);
        $('#change_job_num').val(job_num);
        $.ajax({
              url: "{{URL('ajax/getFSWFiles')}}",
              data: {
                'id' : job_id,
                'num': job_num
              },
            success: function (data) {
              $('#display_quote_files').html(data);
               $('a[name=del_fsw_file]').click(function(){
                var result = confirm("Are you sure! you want to delete....?");
                if(result){
                  $.ajax({
                        url: "{{URL('ajax/deleteFSWFiles')}}",
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
    </script>

    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop