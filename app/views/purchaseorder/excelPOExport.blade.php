                  <table class="table table-bordered table-striped table-condensed cf">
                        <thead class="cf">
                          <tr>
                            <th style="text-align:center;" data-title="">Actions</th>
                            <th style="text-align:center;" data-title="">Po#</th>
                            <th style="text-align:center;" data-title="">Date</th>
                            <th style="text-align:center;" data-title="">Po Items</th>
                            <th style="text-align:center;" data-title="">Job#</th>
                            <th style="text-align:center;" data-title="">Customer</th>
                            <th style="text-align:center;" data-title="">GL Code</th>
                            <th style="text-align:center;" data-title="">Form of Payment</th>
                            <th style="text-align:center;" data-title="">Vendor</th>
                            <th style="text-align:center;" data-title="">Requested By</th>
                            <th style="text-align:center;" data-title="">PO Writer</th>
                            <th style="text-align:center;" data-title="">Quoted Amt for PO </th>
                            <th style="text-align:center;" data-title="">PO Amount to Date </th>
                            <th style="text-align:center;" data-title="">Estimated Receipt Date</th>
                            <th style="text-align:center;" data-title="">Sales/Order # </th>
                            <th style="text-align:center;" data-title="">PO Note </th>
                            <th style="text-align:center;" data-title="">Attachments</th>
                          </tr>
                        </thead>
                      <tbody>
                      @foreach($query_data as $getPORow)
                       <tr>
                        <td>
                          <a href="#myModal" class='btn btn-primary btn-xs' data-toggle='modal' name="edit_fields" id="{{$getPORow['id']}}" paytype="{{$getPORow['payment_form']}}" vendor="{{$getPORow['GPG_vendor_id']}}" requester="{{$getPORow['request_by_id']}}"  writer="{{$getPORow['po_writer_id']}}"><i class="fa fa-pencil-square-o"></i></a>
                          {{ Form::open(array('method' => 'DELETE','id'=>'myForm'.$getPORow['id'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('purchaseorder.destroy',$getPORow['id']))) }}
                          {{ Form::button('<i class="fa fa-trash-o"></i>', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$getPORow['id'].'").submit()')) }}
                          {{ Form::close() }}
                        </td>
                        <td>{{$getPORow['id']}}</td>
                        <td>{{date('m/d/Y',strtotime($getPORow['po_date']))}}</td>
                        <td>{{'PO Items'}}</td>
                        <td><?php
                            $data = explode('~~',$getPORow['glCode_jobNum']);
                            if (isset($data[1]) && !empty($data[1]))
                              echo  wordwrap($data[1], 10, "<br \> \n", 1);
                            else
                              echo '-';  
                        ?></td>
                        <td>
                          <?php
                              $cus_name = "";
                              if(isset($data[1]) && !empty($data[1]) && strlen($data[1])>0){
                                $cus_name = DB::table('gpg_customer')->join('gpg_job', 'gpg_job.GPG_customer_id', '=', 'gpg_customer.id')->where('job_num','=',$data[1])->pluck('name');
                                 echo $cus_name;
                              }else
                                echo "-";
                          ?>
                        </td>
                        <td title="{{$data[0]}}">{{(isset($data[0]) && !empty($data[0])?substr($data[0],0,20).'...':'-')}}</td>
                        <td>{{isset($payTypeArray[$getPORow['payment_form']])?$payTypeArray[$getPORow['payment_form']]:'-'}}</td>
                        <td title="{{$getPORow['poVendor']}}">{{substr($getPORow['poVendor'],0,15).'...'}}</td>
                        <td>{{$getPORow['poRequest']}}</td>
                        <td>{{$getPORow['poWriter']}}</td>
                        <td>{{'$'.number_format($getPORow['po_quoted_amount'],2)}}</td>
                        <td>{{'$'.(!isset($getPORow['amount_to_date'])?'0.00':number_format($getPORow['amount_to_date'],2))}}</td>
                        <td>{{($getPORow['po_est_recpt_date']?date('m/d/Y',strtotime($getPORow['po_est_recpt_date'])):'')}}</td>
                        <td title="{{$getPORow['sales_order_number']}}">{{substr($getPORow['sales_order_number'],0,20).'...'}}</td>
                        <td title="{{$getPORow['po_note']}}">{{substr($getPORow['po_note'],0,20).'...'}}</td>
                        <td>{{HTML::link('#myModal4', 'Manage Files' , array('class' => 'btn btn-link','data-toggle'=>'modal','name'=>'manage_files','id'=>$getPORow['id']))}}</td>
                       </tr>
                      @endforeach 
                      </tbody>
                  </table>