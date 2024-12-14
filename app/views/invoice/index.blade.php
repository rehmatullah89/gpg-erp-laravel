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
 <div class="row">
            <div class="col-sm-12">
              <section class="panel">
                <header class="panel-heading">    
                    RENTALS LISTING 
                  <span class="tools pull-right">
                  <a href="javascript:;" class="fa fa-chevron-down"></a>
                  </span>
                </header>
              </section>
                <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b><i>RENTALS AND MANAGEMENT</i></b>
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
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('invoice/index'),'files'=>true, 'method' => 'post'))}}
                 <div style="margin:10px; color:red; cursor:pointer;" id="togglerButton">Show / Hide Search Box <i id="toggle_div_plus" class='fa fa-plus'></i></div>
                  <section id="no-more-tables" style="padding:10px;" mySection="hide_n_show">
                          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                            <tbody>
                              <tr>
                                  <td data-title="Invoice Date Start:">
                                    {{Form::label('InvoiceSDate', 'Invoice Date Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('InvoiceSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'InvoiceSDate')) }}
                                  </td>
                                  <td data-title="Invoice End Date:">
                                    {{Form::label('InvoiceEDate', 'Invoice End Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('InvoiceEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'InvoiceEDate')) }}
                                  </td>
                                  <td data-title="Created Date Start:">
                                    {{Form::label('CreatedSDate', 'Created Date Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('CreatedSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'CreatedSDate')) }}
                                  </td><td data-title="Created Date End:">
                                    {{Form::label('CreatedEDate', 'Created Date End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('CreatedEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'CreatedEDate')) }}
                                  </td>
                                   <td data-title="Out Date Start:">
                                    {{Form::label('OutSDate', 'Out Date Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('OutSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'OutSDate')) }}
                                  </td>
                                  <td data-title="Out Date End:">
                                    {{Form::label('OutEDate', 'Out Date End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('OutEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'OutEDate')) }}
                                  </td>
                                  <td data-title="Return Date Start:">
                                    {{Form::label('ReturnSDate', 'Return Date Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('ReturnSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'ReturnSDate')) }}
                                  </td>
                                  <td data-title="Return Date End:">
                                    {{Form::label('ReturnEDate', 'Return Date End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('ReturnEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'ReturnEDate')) }}
                                  </td>
                                  <td data-title="Approved Date Start:">
                                    {{Form::label('ApprovedSDate', 'Approved Date Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('ApprovedSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'ApprovedSDate')) }}
                                  </td>
                                 </tr>
                                <tr> <!-- 4th Row-->
                                   <td data-title="Approved End Date:">
                                    {{Form::label('ApprovedEDate', 'Approved End Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('ApprovedEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'ApprovedEDate')) }}
                                  </td>
                                  <td data-title="Quote/Invoice # Start:">
                                    {{Form::label('SQuoteInvoiceNumber', 'Quote/Invoice # Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('SQuoteInvoiceNumber','', array('class' => 'form-control', 'id' => 'SQuoteInvoiceNumber')) }}
                                  </td>
                                  <td data-title="Quote/Invoice # End :">
                                    {{Form::label('EQuoteInvoiceNumber', 'Quote/Invoice # End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('EQuoteInvoiceNumber','', array('class' => 'form-control', 'id' => 'EQuoteInvoiceNumber')) }}
                                  </td>
                                   <td data-title="Status:">
                                    {{Form::label('optStatus', 'Status:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optStatus', array(''=>'ALL',"QUOTE"=>"Quotes","INVOICE"=>"Invoices"), null, ['id' => 'optStatus', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                   <td data-title="Rental Status:">
                                    {{Form::label('rental_status', 'Rental Status:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('rental_status', array(''=>'ALL',"1"=>"Quote","2"=>"Won","3"=>"In Process","4"=>"Complete","5"=>"Invoiced","6"=>"Lost"), null, ['id' => 'rental_status', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Approved By:">
                                    {{Form::label('optEmployee', 'Approved By:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optEmployee', $salesp_arr, null, ['id' => 'optEmployee', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Customer:">
                                    {{Form::label('optCustomer', 'Customer:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optCustomer', $cust_arr, null, ['id' => 'optCustomer', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td colspan="2" align="center">
                                    {{Form::submit('Submit', array('class' => 'btn btn-info'))}}
                                  {{Form::button('Reset', array('class' => 'btn btn-danger', 'id'=>'reset_search_form'))}}
                                  </td>                                             
                                </tr>
                               </tbody>
                          </table>
                    </section>
                               {{ Form::close() }}
              </section>
             <!-- ////////////////////////////////////////// -->
              <div class="panel">
               <div class="panel-body">
              <div class="adv-table">
              <section id="flip-scroll" >
                  <table class="table table-bordered table-striped table-condensed cf">
                        <thead class="cf">
                          <tr>
                            <th style="text-align:center;" >Delete</th>
                            <th style="text-align:center;" >Comp.</th>
                            <th style="text-align:center;" >Compd. Date</th>
                            <th style="text-align:center;" >CheckOut / Checkin</th>
                            <th style="text-align:center;" >Quote/Invoice#</th>
                            <th style="text-align:center;" >Customer</th>
                            <th style="text-align:center;" >Location</th>
                            <th style="text-align:center;" >Job Site Address</th>
                            <th style="text-align:center;" >Date Out</th>
                            <th style="text-align:center;" >Date Return</th>
                            <th style="text-align:center;" >Status</th>
                            <th style="text-align:center;" >Rental Status</th>
                            <th style="text-align:center;" ># of Eqp. on rent</th>
                            <th style="text-align:center;" >Eqp Status</th>
                            <th style="text-align:center;" >Sales Person</th>
                            <th style="text-align:center;" >Total Amt. Billed </th>
                            <th style="text-align:center;" >Sales Tax</th>
                            <th style="text-align:center;" >Invoice# </th>
                            <th style="text-align:center;" >Invoice Amount</th>
                            <th style="text-align:center;" >Invoice Date</th>
                            <th style="text-align:center;" >Approved Date </th>
                            <th style="text-align:center;" >Approved By  </th>
                          </tr>
                        </thead>
                      <tbody class="cf">
                        @foreach($query_data as $getRow)
                          <tr>
                            <td>
                              {{ Form::open(array('method' => 'DELETE','id'=>'myForm'.$getRow['id'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('invoice.destroy', $getRow['id']))) }}
                              {{ Form::button('<i class="fa fa-trash-o"></i>', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$getRow['id'].'").submit()')) }}
                              {{ Form::close() }}
                            </td>
                            <td><input type="checkbox" name="recdCheck_Box" id="{{$getRow['id']}}" <?php if($getRow['complete']==1) echo 'checked';?>></td>
                            <td>{{(!empty($getRow['date_completion'])?date('m/d/Y',strtotime($getRow['date_completion'])):'-')}}</td>
                            <td>{{ HTML::link('invoice/getInvPdffile/'.$getRow['id'].'/'.$getRow['GPG_customer_id'], 'pdf' , array('class'=>'btn btn-danger btn-xs'))}}</td>
                            <td>{{$getRow['job_num']}}</td>
                            <td>{{$getRow['cusName']}}</td>
                            <td><?php $str=substr($getRow['location'],0,25); if(strlen($getRow['location'])>25) $str=$str."..."; else echo '-'; echo substr($str,0,20);?></div><div id="locationHiddenDIV_<?=$getRow['job_num']?>" style="display:none;"><? echo nl2br($getRow['location'])?></td>
                            <td><?php echo nl2br($getRow['address1']).' '.$getRow['city'].' '.$getRow['state'].' '.$getRow['zip'].' '.$getRow['phone'].'-'?></td>
                            <td>{{($getRow['schedule_date']!=""?date('m/d/Y',strtotime($getRow['schedule_date'])):"-")}}</td>
                            <td>{{($getRow['date_return']!=""?date('m/d/Y',strtotime($getRow['date_return'])):"-")}}</td>
                            <td>{{$getRow['form_type']}}</td>
                            <td>{{$rentalStatus[$getRow['rental_status']]}}</td>
                            <td>{{'$'.number_format($getRow['eqp_count'])}}</td>
                            <td>{{isset($getRow['check_out_in'])?$getRow['check_out_in']:'-'}}</td>
                            <td>{{isset($getRow['GPG_employee_id'])?$getRow['GPG_employee_id']:'-'}}</td>
                            <td>{{'$'.number_format(isset($getRow['total_charges'])?$getRow['total_charges']:0,2)}}</td>
                            <td>{{'$'.number_format(isset($getRow['tax_amount'])?$getRow['tax_amount']:0,2)}}</td>
                            <td><?php
                               $invoiceData = explode("#~#",$getRow['invoice_data']);
                                if (isset($invoiceData[0]) && !empty($invoiceData[0]))
                                  echo $invoiceData[0];
                                else
                                  echo "-"; 
                              ?>
                            </td>
                            <td>{{'$'.number_format(isset($invoiceData[1])?$invoiceData[1]:0,2)}}</td>
                            <td>{{(isset($invoiceData[2]) && $invoiceData[2]!=""?date('m/d/Y',strtotime($invoiceData[2])):"-")}}</td>
                            <td>{{((isset($getRow['date_approved']) && $getRow['date_approved']!="")?date('m/d/Y',strtotime($getRow['date_approved'])):"-")}}</td>
                            <td>{{isset($getRow['approved_by'])?$getRow['approved_by']:'-'}}</td>
                          </tr>
                        @endforeach
                      </tbody>
                  </table>
                   {{ HTML::link("invoice/excelInvExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}                   
                </section>   
               {{ $query_data->appends(Input::all())->links() }}
              </div>
              </div>     
              </div>
            </div>

</div>
 <!-- Modal# -->
           <div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                      <h4 class="modal-title">ATTACHMENT MANAGEMENT</h4>
                      </div>
                    <div class="modal-body">
                 {{ Form::open(array('before' => 'csrf' ,'id'=>'submit_file_form','url'=>route('quote/manageQuoteFiles'),'files'=>true, 'method' => 'post')) }}   {{Form::hidden('fjob_id','',array('id' => 'change_job_id' ))}} {{Form::hidden('fjob_num','',array('id' => 'change_job_num' ))}}     <div class="form-group">
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
        <!-- Modal -->
                          <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            {{ Form::open(array('before' => 'csrf','id'=>'submit_poinfo_form','url'=>route('invoice/postDateCompletion'), 'files'=>true, 'method' => 'post')) }}    
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                              <h4 class="modal-title">Enter Completion Date</h4>
                                          </div>
                                          <div class="modal-body">
                                            <div class="form-group">
                                              <section id="no-more-tables">
                                                <table class="table table-bordered table-striped table-condensed cf" align="center">
                                                <tbody class="cf">
                                                  <tr>
                                                    <input type="hidden" name="post_id" id="post_id" value="">
                                                    <th>Comp. Date:</th><td>{{Form::text('completionDate',date('Y-m-d'), ['id' => 'completionDate', 'class'=>'form-control form-control-inline input-medium default-date-picker'])}}</td>
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
    
      $('#submit_attachments').click(function(){
        $('#submit_file_form').submit();
      });
    
      $('#reset_search_form').click(function(){
              $('#InvoiceSDate').val("");
              $('#InvoiceEDate').val("");
              $('#CreatedSDate').val("");
              $('#CreatedEDate').val("");
              $('#OutSDate').val("");
              $('#OutEDate').val("");
              $('#ReturnSDate').val("");
              $('#ReturnEDate').val("");
              $('#ApprovedSDate').val("");
              $('#ApprovedEDate').val("");
              $('#SQuoteInvoiceNumber').val("");
              $('#EQuoteInvoiceNumber').val("");
              $('#optStatus').val("");
              $('#rental_status').val("");
              $('#optEmployee').val("");
              $('#optCustomer').val("");
      });

      $('a[name=manage_files]').click(function(){
        var job_num = $(this).attr('job_num');
        var job_id = $(this).attr('id');
        $('#change_job_id').val(job_id);
        $('#change_job_num').val(job_num);
        $.ajax({
              url: "{{URL('ajax/getQuoteFiles')}}",
              data: {
                'id' : job_id,
                'num': job_num
              },
            success: function (data) {
              $('#display_quote_files').html(data);
               $('a[name=del_quote_file]').click(function(){
                var result = confirm("Are you sure! you want to delete....?");
                if(result){
                  $.ajax({
                        url: "{{URL('ajax/deleteQuoteFile')}}",
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

     $("input[type='checkbox'][name=recdCheck_Box]").click(function(){
        var id = $(this).attr('id');
        if($(this).is(":checked")){
           $('a[name=manage_po_data]').click();
           $('#post_id').val(id);
        }else{
           var conf = confirm('Are you sure to remove the Complete Status for this Job?');
            if (conf){
              $.ajax({
                    url: "{{URL('ajax/delDateCompletion')}}",
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
      $('#submit_poinfo_form').submit();
    });
  </script>    
@stop