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