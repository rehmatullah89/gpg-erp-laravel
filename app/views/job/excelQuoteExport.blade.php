                  <table class="table table-bordered table-striped table-condensed cf">
                        <thead class="cf">
                          <tr>
                            <th style="text-align:center;" >Delete</th>
                            <th style="text-align:center;" >Created Date</th>
                            <th style="text-align:center;" >Customer</th>
                            <th style="text-align:center;" >Location</th>
                            <th style="text-align:center;" >Sales Person</th>
                            <th style="text-align:center;" >Lead Id </th>
                            <th style="text-align:center;" >&nbsp; Quote Number </th>
                            <th style="text-align:center;" >Job Type </th>
                            <th style="text-align:center;" >Quoted Amount</th>
                            <th style="text-align:center;" >Quoted Material Cost</th>
                            <th style="text-align:center;" >Quoted Labor Cost</th>
                            <th style="text-align:center;" >Projected Margin </th>
                            <th style="text-align:center;" >Index 1 </th>
                            <th style="text-align:center;" >Scope of Work </th>
                            <th style="text-align:center;" >&nbsp; Status</th>
                            <th style="text-align:center;" >Status</th>
                            <th style="text-align:center;" >Stage</th>
                            <th style="text-align:center;" >Probability </th>
                            <th style="text-align:center;" >Estimated Close Date </th>
                            <th style="text-align:center;" >Date Job Won </th>
                            <th style="text-align:center;" >Job Number </th>
                            <th style="text-align:center;" >Invoice Date</th>
                            <th style="text-align:center;" >Invoice Number  </th>
                            <th style="text-align:center;" >Invoice Amount   </th>
                            <th style="text-align:center;" >Sales Tax </th>
                            <th style="text-align:center;" >Labor Cost </th>
                            <th style="text-align:center;" >Marerial Cost </th>
                            <th style="text-align:center;" >Total Cost </th>
                            <th style="text-align:center;" >Net Margin </th>
                            <th style="text-align:center;" >Inedx 2 </th>
                            <th style="text-align:center;" >Comm. Owed </th>
                            <th style="text-align:center;" >Comm. Paid </th>
                            <th style="text-align:center;" >Date Comm. Paid </th>
                            <th style="text-align:center;" >Comm. Balance </th>
                            <th style="text-align:center;" data-title="">Attachments</th>
                          </tr>
                        </thead>
                      <tbody class="cf">
                      @foreach($query_data as $getRow)
                        <tr>
                          <td  data-title="Delete:" style="padding-bottom:8.2px;">{{ Form::checkbox('delChk[]',$getRow['id'],'', array('id'=>'delChk[]','class' => 'input-group')) }}</td>
                          <td  data-title="Created Date:" style="padding-bottom:8.2px;">{{date('m/d/Y',strtotime($getRow['created_on']))}}</td>
                          <td  data-title="Customer:" style="padding-bottom:8.2px;" style="white-space: nowrap !important;">{{$getRow['customer']}}</td>
                          <td  data-title="Location:" style="padding-bottom:8.2px;">{{(isset($getRow['eqp_location'])?$getRow['eqp_location']:(isset($getRow['location'])?$getRow['location']:'-'))}}</td>
                          <td  data-title="Sales Person:" style="padding-bottom:8.2px;">{{$getRow['salesPerson']}}</td>
                          <td  data-title="Lead Id:" style="padding-bottom:8.2px;">{{$getRow['gpg_sales_tracking_id']}}</td>
                          <td  data-title="Electrical Quote Number:" style="padding-bottom:8.2px;">
                         {{$getRow['job_num']}}
                          </td>
                          <td  data-title="Job Type:" style="padding-bottom:8.2px;">{{(isset($getRow['quote_type'])?$getRow['quote_type']:'-')}}</td>
                          <td  data-title="Quoted Amount:" style="padding-bottom:8.2px;">{{'$'.number_format((isset($getRow['grand_total'])?$getRow['grand_total']:$getRow['grand_list_total'])+(isset($getRow['subquote_total_cost'])?$getRow['subquote_total_cost']:0),2)}}</td>
                          <td  data-title="Quoted Material Cost:" style="padding-bottom:8.2px;">{{'$'.number_format((isset($getRow['grand_total_material'])?$getRow['grand_total_material']:$getRow['mat_cost_total'])+(isset($getRow['subquote_material_cost'])?$getRow['subquote_material_cost']:$getRow['comp_cost_total'])+(isset($getRow['freight'])?$getRow['freight']:0)+((isset($getRow['mat_cost_total'])?$getRow['mat_cost_total']:0)*((isset($getRow['tax_amount'])?$getRow['tax_amount']*.01:0))),2)}}</td>
                          <td  data-title="Quoted Labor Cost:" style="padding-bottom:8.2px;">{{'$'.number_format((isset($getRow['grand_total_labor'])?$getRow['grand_total_labor']:$getRow['labor_cost_total'])+(isset($getRow['subquote_labor_cost'])?$getRow['subquote_labor_cost']:0)+((isset($getRow['sub_cost_total'])?$getRow['sub_cost_total']:0)*((isset($getRow['hazmat'])?$getRow['hazmat']:0)*.01))+(isset($getRow['mileage'])?$getRow['mileage']:0),2)}}</td>
                          <td  data-title="Projected Margin:" style="padding-bottom:8.2px;">{{'$'.number_format((isset($getRow['margin_gross_total'])?$getRow['margin_gross_total']:0)+(isset($getRow['subquote_material_margin'])?$getRow['subquote_material_margin']:$getRow['grand_list_total'])-(isset($getRow['grand_cost_total'])?$getRow['grand_cost_total']:0),2)}}</td>
                          <td  data-title="Index 1:" style="padding-bottom:8.2px;">{{isset($getRow['labor_quantity'])?$getRow['labor_quantity']:$getRow['labor']}}</td>
                          <td  data-title="Scope of Work:" title="{{(isset($getRow['scope_of_work'])?$getRow['scope_of_work']:0)}}" style="padding-bottom:8.2px;"><?php $str=substr((isset($getRow['scope_of_work'])?$getRow['scope_of_work']:$getRow['task']),0,25); if(isset($getRow['scope_of_work']) && strlen( $getRow['scope_of_work'])>25) $str=$str."..."; if(isset($getRow['task']) && strlen( $getRow['task'])>25) $str=$str."..."; echo $str;?></td>
                          <td  data-title="Electrical Status:" title="{{$getRow['quote_status']}}" style="padding-bottom:8.2px;"><?php $str=substr( $getRow['quote_status'],0,25); if(strlen( $getRow['quote_status'])>25) $str=$str."..."; echo $str;?></td>
                          <td  data-title="Status:" style="padding-bottom:8.2px;">{{(isset($getRow['job_type_status'])?$getRow['job_type_status']:'-')}}</td>
                          <td  data-title="Stage:" style="padding-bottom:8.2px;">{{(isset($getRow['qote_stage_id'])?$getRow['qote_stage_id']:'-')}}</td>
                          <td  data-title="Probability:" style="padding-bottom:8.2px;">{{(isset($getRow['probability']) && $getRow['probability']=='-'?'0%':(isset($getRow['probability'])?$getRow['probability']:0)."%")}}</td>
                          <td  data-title="Estimated Close Date:" style="padding-bottom:8.2px;">{{(isset($getRow['estimated_close_date']) && $getRow['estimated_close_date']== '-'?'-':date('m/d/Y',strtotime((isset($getRow['estimated_close_date'])?$getRow['estimated_close_date']:date('Y-m-d')))))}}</td>
                          <td  data-title="Date Job Won:" style="padding-bottom:8.2px;">{{($getRow['date_job_won']!="-"?date('d/m/Y',strtotime($getRow['date_job_won'])):"-")}}</td>
                          <td  data-title="Job Number:" style="padding-bottom:8.2px;">
                          @if(isset($getRow['GPG_attach_job_num']))
                          {{$getRow['GPG_attach_job_num']}}
                          @else
                          {{'-'}}
                          @endif
                          </td>
                          <td  data-title="Invoice Date:" style="padding-bottom:8.2px;">
                          <?php $invoiceData = array();?>
                          @if(!empty($getRow['attachJobRes']))
                            @if($getRow['attachJobRes']['invoice_data'] != '1' && !empty($getRow['attachJobRes']['invoice_data']))
                              <?php $invoiceData = @explode("#~#",$getRow['attachJobRes']['invoice_data']); ?>
                              {{@($invoiceData[4]>1?"Multiple":($invoiceData[2]!=''?date('m/d/Y',strtotime($invoiceData[2])):"-"))}}
                            @else
                              {{'-'}}  
                            @endif
                          @else
                          {{'-'}}  
                          @endif
                          </td>
                          <td  data-title="Invoice Number:" style="padding-bottom:8.2px;">
                          @if(!empty($invoiceData))
                            {{@($invoiceData[4]>1?"Multiple":$invoiceData[0])}}
                          @else
                            {{'-'}}  
                          @endif
                          </td>
                          <td  data-title="Invoice Amount:" style="padding-bottom:8.2px;">
                          @if(!empty($invoiceData))
                            {{'$'.@number_format($invoiceData[1],2)}}
                          @else
                            {{'-'}}  
                          @endif
                          </td>
                          <td  data-title="Sales Tax :" style="padding-bottom:8.2px;">
                          @if(!empty($invoiceData))
                            {{'$'.@number_format($invoiceData[3],2)}}
                          @else
                            {{'-'}}  
                          @endif
                          </td>
                          <td  data-title="Labor Cost:" style="padding-bottom:8.2px;">
                          @if(!empty($getRow['attachJobRes']))
                            {{'$'.@number_format($getRow['attachJobRes']['labor_cost'],2)}}
                          @else
                            {{'-'}}  
                          @endif
                          </td>
                          <td  data-title="Marerial Cost:" style="padding-bottom:8.2px;">
                          @if(!empty($getRow['attachJobRes']))
                            {{'$'.@number_format($getRow['attachJobRes']['material_cost'],2)}}
                          @else
                            {{'-'}}  
                          @endif
                          </td>
                          <td  data-title="Total Cost:" style="padding-bottom:8.2px;">
                          @if(!empty($getRow['attachJobRes']))
                          <?php $totalCost=0;?>
                          {{'$'.@number_format($totalCost = $getRow['attachJobRes']['material_cost']+$getRow['attachJobRes']['labor_cost'],2)}}
                          @else
                          <?php $totalCost=0;?>
                            {{'-'}}  
                          @endif
                          </td>
                          <td  data-title="Net MarginInedx 2:" style="padding-bottom:8.2px;">
                          @if(!empty($invoiceData) || isset($totalCost))
                          <?php $netMargin=0;?>
                          {{'$'.@number_format($netMargin = $invoiceData[1]-$invoiceData[3]-$totalCost,2)}}</td>
                          @else
                            <?php $netMargin=0;?>
                            {{'-'}}  
                          @endif
                          <td  data-title="Index2:" style="padding-bottom:8.2px;">
                          @if($getRow['time_diff_dec'] != '-' && !empty($getRow['time_diff_dec']))  
                          {{$getRow['time_diff_dec']}}
                          @else
                          {{'$0.00'}}
                          @endif
                          </td>
                          <td  data-title="Comm. Owed:" style="padding-bottom:8.2px;">
                          @if(!empty($getRow['commData']))
                          {{'$'.number_format($getRow['commData']['amt'],2)}}
                          @else
                          {{'-'}}
                          @endif
                          </td>
                          <td  data-title="Comm. Paid:" style="padding-bottom:8.2px;">
                          @if(!empty($getRow['attachJobRes']))
                          {{'$'.number_format($commOwed =  $saleCom = (@($netMargin*$getRow['attachJobRes']['sales_commission'])/100),2)}}
                          @else
                            {{'$0.00'}}  
                          @endif
                          </td>
                          <td  data-title="Date Comm. Paid:" style="padding-bottom:8.2px;">
                          @if(!empty($getRow['commData']))
                          {{($getRow['commData']['comm_date']!=""?date('m/d/Y',strtotime($getRow['commData']['comm_date'])):"-")}}
                          @else
                          {{'-'}}
                          @endif
                          </td>
                          <td  data-title="Comm. Balance:" style="padding-bottom:8.2px;">
                          @if(!empty($getRow['commData']))  
                          {{'$'.number_format($commOwed - $getRow['commData']['amt'],2)}}</td>
                          @else
                          {{'$0.00'}}
                          @endif
                          <td  data-title="Attachments:" style="padding-bottom:8.2px;">{{HTML::link('#myModal4', 'Manage Files' , array('class' => 'btn btn-link','data-toggle'=>'modal','name'=>'manage_files','id'=>$getRow['id'],'job_num'=>$getRow['job_num']))}}</td>
                         </tr>
                      @endforeach
                      </tbody>
                  </table>