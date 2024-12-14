                  <table class="table table-bordered table-striped table-condensed cf">
                        <thead class="cf">
                          <tr>
                            <th style="text-align:center;" class="classmy" data-title="">Comp. Date</th>
                            <th style="text-align:center;" data-title="">Closing Date</th>
                            <th style="text-align:center;" data-title="">Created Date</th>
                            <th style="text-align:center;" data-title="">Schedule Date</th>
                            <th style="text-align:center;" data-title="">Job Number</th>
                            <th style="text-align:center;" data-title="">Job Type</th>
                            <th style="text-align:center;" data-title="">Job Name/Location</th>
                            <th style="text-align:center;" data-title="">Technicians</th>
                            <th style="text-align:center;" data-title="">Customer</th>
                            <th style="text-align:center;" data-title="">Contract Amount</th>
                            <th style="text-align:center;" data-title="">Invoice Date</th>
                            <th style="text-align:center;" data-title="">Inv'd Amount</th>
                            <th style="text-align:center;" data-title="">Tax</th>
                            <th style="text-align:center;" data-title="">Inv'd Amount Net</th>
                            <th style="text-align:center;" data-title="">Invoice#</th>
                            <th style="text-align:center;" data-title="">Material Cost</th>
                            <th style="text-align:center;" data-title="">Labor Cost</th>
                            <th style="text-align:center;" data-title="">Cost to Date</th>
                            <th style="text-align:center;" data-title="">Sales Person</th>
                            <th style="text-align:center;" data-title="">Estimator</th>
                            <th style="text-align:center;" data-title="">Job Status</th>
                            <th style="text-align:center;" data-title="">Download</th>
                          </tr>
                        </thead>
                      <tbody>
                        @foreach($query_data as $data)
                          <tr>
                            <td data-title="Comp. Date:"  style="padding-bottom:9px;">
                              {{($data['date_completion']!='-'? date('m/d/Y',strtotime($data['date_completion'])): '-')}}  
                            </td>
                            <td data-title="Closing Date:"  style="padding-bottom:9px;">
                              {{($data['closing_date']!='-'? date('m/d/Y',strtotime($data['closing_date'])): '-')}}  
                            </td>
                            <td data-title="Date Created:"  style="padding-bottom:9px;">
                              {{($data['created_on']!=''? date('m/d/Y',strtotime($data['created_on'])): '-')}}  
                            </td>
                            <td data-title="Schedule Date:"  style="padding-bottom:9px;">
                              {{($data['schedule_date']!='-'? date('m/d/Y',strtotime($data['schedule_date'])): '-')}}
                            </td>

                            <td data-title="Job Num:"  style="padding-bottom:9px;">{{$data['job_num']}}</td>
                            <td data-title="Job Type:"  style="padding-bottom:9px;">{{$data['elec_job_type']}}</td>
                            <td data-title="Location:"  style="padding-bottom:9px;">{{$data['location']}}</td>
                            <td data-title="Technician:" title="{{$data['technicians']}}">{{$data['technicians']}}</td>
                            <td data-title="customer Name:"  style="padding-bottom:9px;">{{$data['customer_name']}}</td>
                            <td data-title="Contract Amt:"  style="padding-bottom:9px;">{{$data['contract_amount']}}</td>
                            <td data-title="Invoice Date:"  style="padding-bottom:9px;">
                            @if(!empty($data['invoice_data']))
                              <?php $invoiceData = explode("#~#",$data['invoice_data']); ?>
                              @if($invoiceData[0] != '-')
                                <?php $val_this = ($invoiceData[4]>1?'Multiple':($invoiceData[2]!=''?date('m/d/Y',strtotime($invoiceData[2])):'-')); ?>
                              {{$val_this}}  
                              @else
                                {{'--'}}  
                              @endif
                            @endif
                            </td>
                            <td data-title="Inv'd Amount:"  style="padding-bottom:9px;">
                            @if(!empty($data['invoice_data']))
                              <?php $invoiceData = explode("#~#",$data['invoice_data']); ?>
                              @if($invoiceData[0] != '-')
                                <?php $val_this = '$'.number_format($invoiceData[1],2);?>
                                {{ $val_this}} 
                              @else
                                {{'$0.00'}}
                              @endif
                            @endif
                            </td>
                            <td data-title="Tax:"  style="padding-bottom:9px;">
                            @if(!empty($data['invoice_data']))
                              <?php $invoiceData = explode("#~#",$data['invoice_data']); ?>
                              @if($invoiceData[0] != '-')
                                <?php $val_this =  '$'.number_format($invoiceData[3],2); ?> 
                                {{$val_this}}
                              @else
                                {{'$0.00'}}
                              @endif
                            @endif
                            </td>
                            <td data-title="Inv'd Amount Net:"  style="padding-bottom:9px;">
                            @if(!empty($data['invoice_data']))
                              <?php $invoiceData = explode("#~#",$data['invoice_data']); ?>
                              @if($invoiceData[0] != '-')
                                 <?php $val_this =  '$'.number_format($invoiceData[1] - $invoiceData[3],2); ?> 
                                {{$val_this}}
                              @else
                                {{'$0.00'}}
                              @endif
                            @endif 
                            </td>
                            <td data-title="Inv Data:"  style="padding-bottom:9px;">
                             @if(!empty($data['invoice_data']))
                              <?php $invoiceData = explode("#~#",$data['invoice_data']); ?>
                              @if($invoiceData[0] != '-')
                              <?php $val_this =  preg_replace("/,/",", ",($invoiceData[4]>1?"Multiple":$invoiceData[0])); ?> 
                                {{$val_this}}
                              @else
                                {{'--'}}
                              @endif
                            @endif  
                            </td>
                            <td data-title="Material Cost:"  style="padding-bottom:9px;">
                             @if($data['material_cost'] != '-')
                                {{'$'.number_format($data['material_cost'],2)}}
                              @else
                                {{'$0.00'}}
                              @endif 
                            </td>
                            <td data-title="Labor Cost:"  style="padding-bottom:9px;">
                              @if($data['labor_cost'] != '-')
                                {{'$'.number_format($data['labor_cost'],2)}}
                              @else
                                {{'$0.00'}}
                              @endif
                            </td>
                            <td data-title="Cost to Date:"  style="padding-bottom:9px;">
                               @if($data['labor_cost'] != '-')
                                {{'$'.number_format((double)$data['cost_to_dat'],2)}}
                              @else
                                {{'$0.00'}}
                              @endif
                            </td>
                            <td  style="padding-bottom:9px;" data-title="Sales Person:">{{$data['sales_person_name']}}</td>
                            <td  style="padding-bottom:9px;" data-title="Sales Person:">{{$data['estimator_name']}}</td>
                            <td  style="padding-bottom:9px;" data-title="Sales Person:">{{($data['complete']==1?"Completed":"-")}}</td>
                            <td  style="padding-bottom:9px;" data-title="Attachments:">
                            @if(isset($files_arr[$data['tracking_id']]))
                              {{$files_arr[$data['tracking_id']]}}
                            @else
                              {{'-'}}  
                            @endif
                            </td>
                          </tr>
                        @endforeach  
                      </tbody>
                  </table>