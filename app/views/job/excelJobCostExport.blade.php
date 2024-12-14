 <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                                      <thead class="cf">
                                      <tr>
                                          <th style="text-align:center;">Cost Job Num</th>
                                          <th style="text-align:center;">Customer</th>
                                          <th style="text-align:center;">Regarding</th>
                                          <th style="text-align:center;">Cost Date </th>
                                          <th style="text-align:center;">Cost Amount</th>
                                          <th style="text-align:center;">Job Records </th>
                                          <th style="text-align:center;">Job Invoice Date</th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                        @foreach($query_data as $row)
                                          <tr>
                                            <td>{{$row->cost_job_num}}</td>
                                            <td>{{$row->job_customer}}</td>
                                            <td><?php $str=substr( $row->job_task,0,25); if(strlen( $row->job_task)>25) $str=$str."..."; echo $str;?></td>
                                            <td>{{($row->cost_date!=''?date('m/d/Y',strtotime($row->cost_date)):"-")}}</td>
                                            <td>{{'$'.number_format($row->cost_amount,2)}}</td>
                                            <td>{{(!empty($row->job_job_num)?$row->job_job_num:'<font color="#c10000"><strong>Job Not Exists</strong></font>')}}</td>
                                            <td><?php
                                                if (isset($inv_amt_date[$row->job_id]) && !empty($inv_amt_date[$row->job_id]['invoice_date']) && !empty($inv_amt_date[$row->job_id]['invoice_amount'])) {
                                                  echo '<font color="#c10000">'.(!empty($inv_amt_date[$row->job_id]['invoice_date'])?date('m/d/Y',strtotime($inv_amt_date[$row->job_id]['invoice_date'])):'').' ['.'$'.number_format($inv_amt_date[$row->job_id]['invoice_amount'],2).'] Not In Range</font>';   
                                                }else
                                                  echo '<font color="#c10000">Not Invoiced</font>';
                                            ?></td>
                                          </tr>
                                        @endforeach
                                      </tbody>
                                  </table>