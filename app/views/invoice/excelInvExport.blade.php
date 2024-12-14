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
                            <td>{{'pdf'}}</td>
                            <td>{{$getRow['job_num']}}</td>
                            <td>{{$getRow['cusName']}}</td>
                            <td><?php $str=substr($getRow['location'],0,25); if(strlen($getRow['location'])>25) $str=$str."..."; else echo '-'; echo substr($str,0,20);?></div><div id="locationHiddenDIV_<?=$getRow['job_num']?>" style="display:none;"><? echo nl2br($getRow['location'])?></td>
                            <td><?php echo nl2br($getRow['address1']).' '.$getRow['city'].' '.$getRow['state'].' '.$getRow['zip'].' '.$getRow['phone'].'-'?></td>
                            <td>{{($getRow['schedule_date']!=""?date('m/d/Y',strtotime($getRow['schedule_date'])):"-")}}</td>
                            <td>{{($getRow['date_return']!=""?date('m/d/Y',strtotime($getRow['date_return'])):"-")}}</td>
                            <td>{{$getRow['form_type']}}</td>
                            <td>{{isset($rentalStatus[$getRow['rental_status']])?$rentalStatus[$getRow['rental_status']]:0}}</td>
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