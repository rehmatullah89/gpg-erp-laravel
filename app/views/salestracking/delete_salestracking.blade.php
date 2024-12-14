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
                DELETED SALES TRACKING  
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
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
                            <th style="text-align:center;" data-title="">Prospect / CustomerName</th>
                            <th style="text-align:center;" data-title="">Date Entered</th>
                            <th style="text-align:center;" data-title="">Location</th>
                            <th style="text-align:center;" data-title="">Type of Sale</th>
                            <th style="text-align:center;" data-title="">Quote/Opportunity Name</th>
                            <th style="text-align:center;" data-title="">Projected Sale Price</th>
                            <th style="text-align:center;" data-title="">Including Tax [Y/N]</th>
                            <th style="text-align:center;" data-title="">Labor Costs</th>
                            <th style="text-align:center;" data-title="">Rental Price </th>
                            <th style="text-align:center;" data-title="">Material Costs</th>
                            <th style="text-align:center;" data-title="">Total Cost</th>
                            <th style="text-align:center;" data-title="">Dollars Won </th>
                            <th style="text-align:center;" data-title="">Status</th>
                            <th style="text-align:center;" data-title="">Status Date Change</th>
                            <th style="text-align:center;" data-title="">W/O#  </th>
                            <th style="text-align:center;" data-title="">Invoice#</th>
                            <th style="text-align:center;" data-title="">Expected Date to close sale</th>
                            <th style="text-align:center;" data-title="">Sales Person</th>
                            <th style="text-align:center;" data-title="">Need to Subcontact</th>
                            <th style="text-align:center;" data-title="">If Yes? Name of contractor</th>
                            <th style="text-align:center;" data-title="">Attachments</th>
                          </tr>
                        </thead>
                      <tbody>
                       @foreach($query_data as $getSalesRow)
                          <tr>
                            <td>
                            <a id="{{$getSalesRow['id']}}" name="restore_st_row" data-toggle="modal" class="btn btn-primary btn-xs" href="#myModal4" title="Restore"><i class="fa fa-repeat"></i></a>
                              {{ Form::open(array('method' => 'DELETE','id'=>'myForm'.$getSalesRow['id'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('salestracking.destroy',$getSalesRow['id']))) }}
                              {{ Form::button('<i class="fa fa-trash-o"></i>', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$getSalesRow['id'].'").submit()')) }}
                              {{ Form::close() }}
                            </td>
                            <td>{{$getSalesRow['id']}}</td>
                            <td>{{isset($gpg_customers[$getSalesRow['gpg_customer_id']])?$gpg_customers[$getSalesRow['gpg_customer_id']]:'-'}}</td>
                            <td>{{date('m/d/Y',strtotime($getSalesRow['enter_date']))}}</td>
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
                            <td>{{isset($getSalesRow['labor_cost'])?$getSalesRow['labor_cost']:'-'}}</td>
                            <td>{{isset($getSalesRow['rental_cost'])?$getSalesRow['rental_cost']:'-'}}</td>
                            <td>{{isset($getSalesRow['material_cost'])?$getSalesRow['material_cost']:'-'}}</td>
                            <td>-</td>
                            <td>{{isset($getSalesRow['dollar_won'])?$getSalesRow['dollar_won']:'-'}}</td>
                            <td>{{isset($getSalesRow['status'])?$getSalesRow['status']:'-'}}</td>
                            <td>{{($getSalesRow['status_change_date']!=""?date('m/d/Y',strtotime($getSalesRow['status_change_date'])):"-")}}</td>
                            <td>{{isset($getSalesRow['w_o_number'])?$getSalesRow['w_o_number']:'-'}}</td>
                            <td>{{isset($getSalesRow['invoice_number'])?$getSalesRow['invoice_number']:'-'}}</td>
                            <td>{{($getSalesRow['close_date']!=""?date('m/d/Y',strtotime($getSalesRow['close_date'])):"-")}}</td>
                            <td>{{isset($getSalesRow['sales_person_name'])?$getSalesRow['sales_person_name']:'-'}}</td>
                            <td><input name="subContact_check" type="checkbox" id="subContact_{{$getSalesRow['id']}}" value="1" <?php echo ($getSalesRow['subcontact']==1?"checked=\"checked\"":"") ?> disabled="disabled" /></td>
                            <td>{{isset($getSalesRow['subcontact_name'])?$getSalesRow['subcontact_name']:'-'}}</td>  
                            <td>{{HTML::link('#myModal2', 'Manage Files' , array('class' => 'btn btn-link btn-xs','data-toggle'=>'modal','name'=>'manage_files','id'=>$getSalesRow['id']))}}</td>
                          </tr>
                       @endforeach
                      </tbody>
                  </table>
                  <br/>{{ $query_data->appends(array_filter(Input::except('_token')))->links() }}
                </section>
              </div>
              </div>
              </section>
              </div>
              </div>
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
              <!-- page end-->
    <script type="text/javascript">
      $('.default-date-picker').datepicker({
          format: 'yyyy-mm-dd'
      });
      $('.timepicker-default').timepicker();
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

 $('a[name=restore_st_row]').click(function(){
    var id = $(this).attr('id');
    $.ajax({
      url: "{{URL('ajax/restoreSTR')}}",
        data: {
          'id' : id
        },
        success: function (data) {
        if (data == 1){     
          alert("Restored Successfully!");
          location.reload();
        }
      },
    });
 });
</script>
<script src="{{asset('js/jquery.nicescroll.js')}}"></script>
<script src="{{asset('js/common-scripts.js')}}"></script> 
@stop