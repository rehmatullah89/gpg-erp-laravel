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
              PURCHASE ORDER RECEIVED
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b><i>Purchase Order Received List View</i></b>
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
                </header>
                 <?php $uriSegment = Request::segment(2);?> 
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('purchaseorder/index_recd'), 'files'=>true, 'method' => 'post')) }}
                 <div style="margin:10px; color:red; cursor:pointer;" id="togglerButton">Show / Hide Search Box <i id="toggle_div_plus" class='fa fa-plus'></i></div>
                  <section id="no-more-tables" style="padding:10px;" mySection="hide_n_show">
                          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                            <tbody>
                              <tr>
                                  <td data-title="Date Start:">
                                    {{Form::label('SDate', 'Date Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('SDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'SDate')) }}
                                  </td><td data-title="Date End:">
                                    {{Form::label('EDate', ' Date End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('EDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'EDate')) }}
                                  </td>
                                  <td data-title="Received Date Start:">
                                    {{Form::label('recdSDate', 'Received Date Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('recdSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'recdSDate')) }}
                                  </td><td data-title="Received Date End:">
                                    {{Form::label('recdEDate', 'Received Date End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('recdEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'recdEDate')) }}
                                  </td>
                                  <td data-title="Est. Receipt Date Start:">
                                    {{Form::label('estReceiptSDate', 'Est. Receipt Date Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('estReceiptSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'estReceiptSDate')) }}
                                  </td><td data-title="Est. Receipt Date End:">
                                    {{Form::label('estReceiptEDate', 'Est. Receipt Date End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('estReceiptEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'estReceiptEDate')) }}
                                  </td>
                                  <td data-title="PO Number Start:">
                                    {{Form::label('SPONumber', 'PO Number Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('SPONumber','', array('class' => 'form-control', 'id' => 'SPONumber')) }}
                                  </td><td data-title="PO Number End:">
                                    {{Form::label('EPONumber', 'Job Number End(Opt):', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('EPONumber','', array('class' => 'form-control', 'id' => 'EPONumber')) }}
                                  </td>
                                  <td data-title="Job Number:">
                                    {{Form::label('optJobNum', 'Job Number:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('optJobNum','', array('class' => 'form-control', 'id' => 'optJobNum')) }}
                                  </td>
                              </tr>
                              <tr>
                                  <td data-title="Department:">
                                    {{Form::label('optPODept', 'Department:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optPODept',array(''=>'Select Department ','1'=>'Electrical','2'=>'Service'), null, ['id' => 'optPODept', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="PO's Filter:">
                                    {{Form::label('optPORecd', 'PO,s Filter:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optPORecd',array(''=>'Select PO,s Filter','1'=>'PO,s received','2'=>'PO,s not recieved'), null, ['id' => 'optPORecd', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Sort By:">
                                    {{Form::label('optSort', 'Sort By:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optSort',array('id'=>'PO Number','po_date'=>'PO Date'), null, ['id' => 'optSort', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="GL Code:">
                                    {{Form::label('optGLCode', 'GL Code:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optGLCode', array(''=>'ALL')+$gcodeArr, null, ['id' => 'optGLCode', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Form of Payment:">
                                    {{Form::label('optPayForm', 'Form of Paymen:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optPayForm',array(''=>'ALL')+$payTypeArray, null, ['id' => 'optPayForm', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Vendor:">
                                    {{Form::label('optVendor', 'Vendor:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optVendor', array(''=>'ALL')+$vendors, null, ['id' => 'optVendor', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Requested By:">
                                    {{Form::label('optRequestBy', 'Requested By:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optRequestBy',array(''=>'ALL')+$emps, null, ['id' => 'optRequestBy', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="PO Writer:">
                                    {{Form::label('optPOWriter', 'PO Writer:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optPOWriter',array(''=>'ALL')+$emps, null, ['id' => 'optPOWriter', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Price filter:">
                                    {{Form::label('priceFilter', 'Price filter:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('priceFilter',array(''=>'All','1'=>'PO Amount to Date is greater','2'=>'Quoted amount is greater'), null, ['id' => 'priceFilter', 'class'=>'form-control m-bot15'])}}
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
                            <th style="text-align:center;" data-title="">Recd?</th>
                            <th style="text-align:center;" data-title="">Date Rec</th>
                            <th style="text-align:center;" data-title="">Po#</th>
                            <th style="text-align:center;" data-title="">Date</th>
                            <th style="text-align:center;" data-title="">Po Items</th>
                            <th style="text-align:center;" data-title="">Job#</th>
                            <th style="text-align:center;" data-title="">GL Code</th>
                            <th style="text-align:center;" data-title="">Quoted Amt for PO </th>
                            <th style="text-align:center;" data-title="">PO Amount to Date </th>
                            <th style="text-align:center;" data-title="">Estimated Receipt Date</th>
                            <th style="text-align:center;" data-title="">Form of Payment</th>
                            <th style="text-align:center;" data-title="">Vendor</th>
                            <th style="text-align:center;" data-title="">Requested By</th>
                            <th style="text-align:center;" data-title="">PO Writer</th>
                            <th style="text-align:center;" data-title="">Sales/Order # </th>
                            <th style="text-align:center;" data-title="">PO Note </th>
                            <th style="text-align:center;" data-title="">Attachments</th>
                          </tr>
                        </thead>
                      <tbody>
                      @foreach($query_data as $getPORow)
                       <tr>
                        <td>
                        <input type="checkbox" name="recdCheck_Box" id="{{$getPORow['id']}}" <?php if($getPORow['po_recd']==1) echo 'checked';?> >
                        </td>
                        <td>{{date('m/d/Y',strtotime($getPORow['po_recd_date']))}}</td>
                        <td>{{$getPORow['id']}}</td>
                        <td>{{date('m/d/Y',strtotime($getPORow['po_date']))}}</td>
                        <td>{{ HTML::link('purchaseorder/po_item_form/'.$getPORow['id'],'PO Items', array('target'=>'_blank','class'=>'btn btn-link btn-xs', 'id'=>$getPORow['id']))}}</td>
                        <td><?php
                            $data = explode('~~',$getPORow['glCode_jobNum']);
                            if (isset($data[1]) && !empty($data[1]))
                              echo  wordwrap($data[1], 10, "<br \> \n", 1);
                            else
                              echo '-';  
                        ?></td>
                        <td title="{{$data[0]}}">{{(isset($data[0]) && !empty($data[0])?substr($data[0],0,20).'...':'-')}}</td>
                        <td>{{'$'.number_format($getPORow['po_quoted_amount'],2)}}</td>
                        <td>{{'$'.(!isset($getPORow['amount_to_date'])?'0.00':number_format($getPORow['amount_to_date'],2))}}</td>
                        <td>{{($getPORow['po_est_recpt_date']?date('m/d/Y',strtotime($getPORow['po_est_recpt_date'])):'-')}}</td>
                        <td>{{isset($payTypeArray[$getPORow['payment_form']])?$payTypeArray[$getPORow['payment_form']]:'-'}}</td>
                        <td title="{{$getPORow['poVendor']}}">{{substr($getPORow['poVendor'],0,15).'...'}}</td>
                        <td>{{$getPORow['poRequest']}}</td>
                        <td>{{isset($getPORow['poWriter'])?$getPORow['poWriter']:'-'}}</td>
                        <td title="{{$getPORow['sales_order_number']}}">{{substr($getPORow['sales_order_number'],0,20).'...'}}</td>
                        <td title="{{$getPORow['po_note']}}">{{substr($getPORow['po_note'],0,20).'...'}}</td>
                        <td>{{HTML::link('#myModal4', 'Manage Files' , array('class' => 'btn btn-link','data-toggle'=>'modal','name'=>'manage_files','id'=>$getPORow['id']))}}</td>
                       </tr>
                      @endforeach 
                      </tbody>
                  </table>
                  {{ HTML::link("purchaseorder/excelRPOExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}                   
                  {{ HTML::link('purchaseorder/po_item_form/0','Add New Purchase Order', array('target'=>'_blank','class'=>'btn btn-danger'))}}
                  {{ $query_data->appends(array_filter(Input::except('_token')))->links() }}
                </section>
                        <!-- Modal -->
                          <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            {{ Form::open(array('before' => 'csrf','id'=>'submit_poinfo_form','url'=>route('purchaseorder/updatePOAmtDate'), 'files'=>true, 'method' => 'post')) }}    
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                              <h4 class="modal-title">PO Amount to Date: [<b id="po_amt_orig"></b>]</h4>
                                          </div>
                                          <div class="modal-body">
                                            <div class="form-group">
                                              <section id="no-more-tables">
                                                <table class="table table-bordered table-striped table-condensed cf" align="center">
                                                <tbody class="cf">
                                                  <tr>
                                                    <input type="hidden" name="orig_po_amt" id="orig_po_amt" value="">
                                                    <input type="hidden" name="hist_po_amt" id="hist_po_amt" value="">
                                                    <input type="hidden" name="post_po_id" id="post_po_id" value="">
                                                    <th>Enter Amt to Date:</th><td>{{Form::text('amt_to_date','', ['id' => 'amt_to_date', 'class'=>'form-control m-bot15'])}}</td>
                                                  </tr>
                                                  <tr>
                                                    <th>Vendor Invoice#:</th><td>{{Form::text('ven_invoice_num','', ['id' => 'ven_invoice_num', 'class'=>'form-control m-bot15'])}}</td>
                                                  </tr>
                                                  </tbody>
                                                </table>
                                            </div>
                                          </div>
                                          <div class="btn-group" style="padding:20px;">
                                          {{Form::button('Save', array('class' => 'btn btn-success','data-dismiss'=>'modal','id'=>'save_modal_info'))}}
                                          {{Form::button('Close', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                                      </div>
                                  </div>
                              </div>
                            {{Form::close()}}  
                          </div>
  {{HTML::link('#myModal', '' , array('data-toggle'=>'modal','name'=>'manage_po_data'))}}
          <!-- modal -->
          <!-- Modal4 -->
           <div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                      <h4 class="modal-title">ATTACHMENT MANAGEMENT</h4>
                      </div>
                    <div class="modal-body">
                 {{ Form::open(array('before' => 'csrf' ,'id'=>'submit_file_form','url'=>route('purchaseorder/managePOFiles'),'files'=>true, 'method' => 'post')) }}   {{Form::hidden('fjob_id','',array('id' => 'change_job_id' ))}}
                 <div class="form-group">
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
        <!-- modal4 end--> 
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
              $('#recdSDate').val("");
              $('#recdEDate').val("");
              $('#optPODept').val("");
              $('#estReceiptSDate').val("");
              $('#estReceiptEDate').val("");
              $('#SPONumber').val("");
              $('#EPONumber').val("");
              $('#optJobNum').val("");
              $('#VendorInvNum').val("");
              $('#poQutAmount').val("");
              $('#poitemdesc').val("");
              $('#optPORecd').val("");
              $('#optSort').val("");
              $('#optGLCode').val("");
              $('#optPayForm').val("");
              $('#optVendor').val("");
              $('#optRequestBy').val("");
              $('#optPOWriter').val("");
              $('#priceFilter').val("");             
      });
    $('a[name=edit_fields]').click(function(){
      var id = $(this).attr('id');
      var paytype = $(this).attr('paytype');
      var vendor = $(this).attr('vendor');
      var requester = $(this).attr('requester');
      var writer = $(this).attr('writer');
      $('#purchaseno').html(id);
      $('#form_of_payment').val(paytype);
      $('#opt_vendor').val(vendor);
      $('#opt_requester').val(requester);
      $('#opt_writer').val(writer);
      $('#poid').val(id);
    });

    $('a[name=manage_files]').click(function(){
        var job_id = $(this).attr('id');
        $('#change_job_id').val(job_id);
        $.ajax({
              url: "{{URL('ajax/getPOFiles')}}",
              data: {
                'id' : job_id
              },
            success: function (data) {
              $('#display_quote_files').html(data);
               $('a[name=del_quote_file]').click(function(){
                var result = confirm("Are you sure! you want to delete....?");
                if(result){
                  $.ajax({
                        url: "{{URL('ajax/deletePOFile')}}",
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

     $("input[type='checkbox'][name=recdCheck_Box]").click(function(){
        var id = $(this).attr('id');
        if($(this).is(":checked")){
            $.ajax({
                    url: "{{URL('ajax/getPOAmt')}}",
                    data: {
                      'id' : id
                    },
                      success: function (data) {
                      $('#post_po_id').val(id);  
                      $('#orig_po_amt').val(data.amt);
                      $('#po_amt_orig').html(data.amt);
                      $('#hist_po_amt').val(data.hist_amt);
                      $('a[name=manage_po_data]').click();
                  },
            });
        }else{
           var conf = confirm('Are you Sure you want to DELETE the Received PO Date & Amount');
            if (conf){
              $.ajax({
                    url: "{{URL('ajax/delPOAmtDate')}}",
                    data: {
                      'id' : id
                    },
                      success: function (data) {
                      if (data == 1){     
                            alert("Deleted Successfully!");
                            //location.reload();
                      }
                  },
              });
            }else{
              return false;
            }
        }
    });

  $('#save_modal_info').click(function(){
     var amt = $('#orig_po_amt').val();
     var hamt = $('#hist_po_amt').val();
     var fieldval = $('#amt_to_date').val();
     var venVal = $('#ven_invoice_num').val();
     if(fieldval == '' && venVal == ''){
      alert('Please Fill required Fields!');
      return false;
     }else{
      var total_val = parseInt(hamt)+parseInt(fieldval);
      if (total_val>amt){
        alert('PO Amt to Date Cannot be Greater then Remaining Amt for PO');
        return false;
      }else{
         $('#submit_poinfo_form').submit();
      }
     }
  });
    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop