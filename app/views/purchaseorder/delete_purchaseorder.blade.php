@extends("layouts/dashboard_master")
@section('content')
  <section>
    
  </section>
@stop
@section('dashboard_panels')
              <!-- page start-->
              <div class="row">
                <div class="col-sm-12">
              <section class="panel">
              <header class="panel-heading">
                 Soft Deleted Purchase Orders
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                       <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b><i>View/ Edit/ Delete: Purchase Orders. </i></b>
                          </header>
              </section>
             <!-- ////////////////////////////////////////// -->
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <thead class="cf">
                          <tr>
                            <th style="text-align:center;">Actions</th>
                            <th style="text-align:center;">Po#</th>
                            <th style="text-align:center;">Date</th>
                            <th style="text-align:center;">PO Items</th>
                            <th style="text-align:center;">Job#</th>
                            <th style="text-align:center;">GL Code</th>
                            <th style="text-align:center;">Form of Payment</th>
                            <th style="text-align:center;">Vendor</th>
                            <th style="text-align:center;">Requested By</th>
                            <th style="text-align:center;">PO Writer</th>
                            <th style="text-align:center;">Quoted Amt for PO </th>
                            <th style="text-align:center;">PO Amount to Date </th>
                            <th style="text-align:center;">Sales/Order # </th>
                          </tr>
                        </thead>
                      <tbody>
                        @foreach($getPORows as $getPORow)
                          <tr>
                            <td>
                              <a href="#myModal" class='btn btn-primary btn-xs' data-toggle='modal' name="edit_fields" id="{{$getPORow['id']}}" paytype="{{$getPORow['payment_form']}}" vendor="{{$getPORow['GPG_vendor_id']}}" requester="{{$getPORow['request_by_id']}}"  writer="{{$getPORow['po_writer_id']}}"><i class="fa fa-pencil-square-o"></i></a>
                              {{ Form::open(array('method' => 'DELETE','id'=>'myForm'.$getPORow['id'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('purchaseorder.destroy',$getPORow['id']))) }}
                              {{ Form::button('<i class="fa fa-trash-o"></i>', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$getPORow['id'].'").submit()')) }}
                              {{ Form::close() }}
                              {{ Form::button('<i class="fa fa-refresh fa-spin"></i>', array('name'=>'restore_item','id'=>$getPORow['id'],'title'=>'Restore PO','style'=>'display:inline;','class' => 'btn btn-success btn-xs','onclick'=>'confirmRestore()')) }}
                            </td>
                            <td>{{$getPORow['id']}}</td>
                            <td>{{date('m/d/Y',strtotime($getPORow['po_date']))}}</td>
                            <td>{{ HTML::link('purchaseorder/po_item_form/'.$getPORow['id'],'PO Items', array('target'=>'_blank','class'=>'btn btn-link btn-xs', 'id'=>$getPORow['id']))}}</td>
                            <td>
                              <?php
                            $data = explode('~~',$getPORow['glCode_jobNum']);
                            if (isset($data[1]) && !empty($data[1]))
                              echo  wordwrap($data[1], 10, "<br \> \n", 1);
                            else
                              echo '-';  
                            ?>
                            </td>
                            <td>{{(isset($data[0]) && !empty($data[0])?substr($data[0],0,20).'...':'-')}}</td>
                            <td>{{isset($payTypeArray[$getPORow['payment_form']])?$payTypeArray[$getPORow['payment_form']]:'-'}}</td>
                            <td title="{{$getPORow['poVendor']}}">{{substr($getPORow['poVendor'],0,15).'...'}}</td>
                            <td>{{$getPORow['poRequest']}}</td>
                            <td>{{isset($getPORow['poWriter'])?$getPORow['poWriter']:'-'}}</td>
                            <td>{{'$'.number_format($getPORow['po_quoted_amount'],2)}}</td>
                            <td>{{'$'.(!isset($getPORow['amount_to_date'])?'0.00':number_format($getPORow['amount_to_date'],2))}}</td>
                            <td title="{{$getPORow['sales_order_number']}}">{{substr($getPORow['sales_order_number'],0,20).'...'}}</td>
                          </tr>
                        @endforeach
                      </tbody>
                  </table>
              </section>
            </div>
          </div>
         </section>
        </div>
        </div>
        <!-- Modal -->
                          <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            {{ Form::open(array('before' => 'csrf','id'=>'submit_poinfo_form','url'=>route('purchaseorder/updatepoInfo'), 'files'=>true, 'method' => 'post')) }}    
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                              <h4 class="modal-title">Edit Purchase order# [<i id="purchaseno"></i>]:<b id="JobNum"></b></h4>
                                          </div>
                                          <div class="modal-body">
                                            <div class="form-group">
                                              <section id="no-more-tables">
                                                <table class="table table-bordered table-striped table-condensed cf" align="center">
                                                <tbody class="cf">
                                                  <tr>
                                                    <th>Form of Payment:</th><td>{{Form::select('form_of_payment',array(''=>'ALL')+$payTypeArray, null, ['id' => 'form_of_payment', 'class'=>'form-control m-bot15'])}}</td>
                                                  </tr>
                                                  <tr>
                                                    <th>Vendor:</th><td>{{Form::select('opt_vendor', array(''=>'ALL')+$vendors, null, ['id' => 'opt_vendor', 'class'=>'form-control m-bot15'])}}</td>
                                                  </tr>
                                                  <tr>
                                                    <th>Requested By:</th><td>{{Form::select('opt_requester',array(''=>'ALL')+$emps, null, ['id' => 'opt_requester', 'class'=>'form-control m-bot15'])}}</td>
                                                  </tr>
                                                  <tr>
                                                    <th>PO Writer:</th><td>{{Form::select('opt_writer',array(''=>'ALL')+$emps, null, ['id' => 'opt_writer', 'class'=>'form-control m-bot15'])}}</td>
                                                    <input type="hidden" name="poid" value="" id="poid">
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
                        <!-- modal -->
              <!-- page end-->
    <script type="text/javascript">
      $('.default-date-picker').datepicker({
          format: 'yyyy-mm-dd',
          minDate: new Date()
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

    $('#save_modal_info').click(function(){
      $('#submit_poinfo_form').submit();
    });
    $('button[name=restore_item]').click(function(){
      var id = $(this).attr('id');
      var conf = confirm('Are You sure You want to restore this PO!');
      if (conf){
         $.ajax({
            url: "{{URL('ajax/restorePO')}}",
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
      }else{
        return false;
      }
    });
    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>   
@stop