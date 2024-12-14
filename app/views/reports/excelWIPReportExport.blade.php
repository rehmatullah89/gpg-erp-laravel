<table class="table table-bordered table-striped table-condensed cf" >
              <thead class="cf">
              <tr>
                  <th>Created Date</th>
                  <th>Job Number</th>
                  <th>Customer</th>
                  <th>Class</th>
                  <th>Contractor</th>
                  <th>Total Revised Contract Amt</th>
                  <th>Est. Total Cost at Comp.</th>
                  <th>Est. Profit at Comp.</th>
                  <th>Material Cost</th>
                  <th>Labor Cost</th>
                  <th>Cost Incured to Date</th>
                  <th>% COMP</th>
                  <th>Profit to Date</th>
                  <th>Amt Eearned to Date</th>
                  <th>Amt Billed to Date</th>
                  <th>Cost in Excess of Billings</th>
                  <th>Billings in Excess of Cost</th>
                  <th>Est. Cost to Comp.</th>
                  <th>Contract Balance</th>
              </tr>
              </thead>
              <tbody class="cf">
               @foreach($query_data as $getRow)
                <tr>
                  <td>{{($getRow['created_on']!=""?date('m/d/Y',strtotime($getRow['created_on'])):"-")}}</td>
                  <td>{{ HTML::link('job/job_form/'.$getRow['id'].'/'.$getRow['job_num'].'', $getRow['job_num'] , array('target'=>'_blank','class'=>'btn btn-link btn-xs', 'id'=>$getRow['id'],'j_num'=>$getRow['job_num']))}} </td>
                  <td title="{{$getRow['customer_name']}}">{{substr($getRow['customer_name'],0,20).'..'}}</td>
                  <td>{{($getRow['GPG_job_type_id']==5?"Electrical":"Service")}}</td>
                  <td>{{"Global Power Group, Inc"}}</td>
                  <td>{{'$'.number_format($totRevisedContractAmt = ($getRow["contract_amount"]!=0?$getRow["contract_amount"]:$getRow["fixed_price"]),2)}}</td>
                  <td>{{'$'.number_format($estTotalCost = $getRow["budgeted_material"]+$getRow["budgeted_labor"],2)}}</td>
                  <td>{{'$'.number_format($estProfit = $totRevisedContractAmt-$estTotalCost,2)}}</td>
                  <td>{{'$'.number_format($getRow["material_cost"],2)}}</td>
                  <td>{{'$'.number_format($getRow["labor_cost"],2)}}</td>
                  <td>{{'$'.number_format($costIncuredToDate =$getRow["material_cost"]+$getRow["labor_cost"],2)}}</td>
                  <td>{{'$'.round(number_format(($percComp =@($costIncuredToDate/$estTotalCost))*100,2))."%"}}</td>
                  <td>{{'$'.number_format($profitToDate = $percComp*$estProfit,2)}}</td>
                  <td>{{'$'.number_format($amtEarnedToDate = $profitToDate + $costIncuredToDate,2)}}</td>
                  <td>{{'$'.number_format($amtBilledToDate = $getRow["inv_amount"],2)}}</td>
                  <td>{{(($amtEarnedToDate-$amtBilledToDate)>0?'$'.number_format($amtEarnedToDate-$amtBilledToDate,2):'$'.number_format(0,0))}}</td>
                  <td>{{(($amtBilledToDate-$amtEarnedToDate)>0?'$'.number_format($amtBilledToDate-$amtEarnedToDate,2):'$'.number_format(0,0))}}</td>
                  <td>{{'$'.number_format($estCostToComplete = $estTotalCost-$costIncuredToDate,2)}}</td>
                  <td>{{'$'.number_format($contractBalance = $totRevisedContractAmt - $amtBilledToDate,2)}}</td>
                </tr>
               @endforeach
              </tbody>
              </table>
            </section>
            <section id="no-more-table" >
              <table class="table table-bordered table-striped table-condensed cf" >
              <thead class="cf">
              <tr>
                <th>Revised Contract Amount</th>
                <th>Est. Total Cost at Completion</th>
                <th>Est. Profit at Completion</th>
                <th>Mat. Cost</th>
                <th>Labor Cost</th>
                <th>Cost Incured to Date</th>
                <th>Profit to Date</th>
                <th>Amt Eearned to Date</th>
                <th>Amt Billed to Date</th>
                <th>Cost in Excess of Billings</th>
                <th>Billings in Excess of Cost</th>
                <th>Est. Cost to Complete</th>
                <th>Contract Balance</th>
              </tr>
              </thead>
              <tbody>
                <?php
                    $totContarctAmt =0;
                    $totEstimateCost=0;
                    $totEstimateProfitAtComp=0;
                    $totMatCost=0;
                    $totLabCost=0;
                    $totCostIncuredToDate=0;
                    $totProfitToDate=0;
                    $totAmtEarnedToDate=0;
                    $totAmtBilledToDate=0;
                    $totCostinExcessofBillings=0;
                    $totBillingsinExcessofCost=0;
                    $totEstCostToComplete=0;
                    $totContractBalance=0;

                  foreach ($totalsArr as $key => $getRow) {
                    $revisedContractAmt = ($getRow["contract_amount"]!=0?$getRow["contract_amount"]:$getRow["fixed_price"]);
                    $totContarctAmt += $revisedContractAmt;
                    $estCost = $getRow["budgeted_material"] + $getRow["budgeted_labor"];
                    $totEstimateCost += $estCost;
                    $estProfit = $revisedContractAmt-$estCost;
                    $totEstimateProfitAtComp += $estProfit;
                    $totMatCost += $getRow["material_cost"];
                    $totLabCost += $getRow["labor_cost"] ;
                    $costIncuredToDate = $getRow["material_cost"] + $getRow["labor_cost"];
                    $totCostIncuredToDate += $costIncuredToDate;
                    $percComp =@($costIncuredToDate/$estCost);
                    $profitToDate = $percComp * $estProfit;
                    $totProfitToDate += $profitToDate;
                    $amtEarnedToDate = $profitToDate + $costIncuredToDate;
                    $totAmtEarnedToDate += $amtEarnedToDate;
                    $amtBilledToDate = $getRow["inv_amount"];
                    $totAmtBilledToDate += $amtBilledToDate;
                    $totCostinExcessofBillings += (($amtEarnedToDate-$amtBilledToDate)>0?$amtEarnedToDate-$amtBilledToDate:0);
                    $totBillingsinExcessofCost += (($amtBilledToDate-$amtEarnedToDate)>0?$amtBilledToDate-$amtEarnedToDate:0);
                    $totEstCostToComplete += ($estCost-$costIncuredToDate);
                    $totContractBalance += ($revisedContractAmt - $amtBilledToDate);
                  }
                ?>
               <tr>
                  <td><strong>{{ '$'.number_format($totContarctAmt,2) }}</strong></td> 
                  <td><strong>{{ '$'.number_format($totEstimateCost,2) }}</strong></td> 
                  <td><strong>{{ '$'.number_format($totEstimateProfitAtComp,2) }}</strong></td> 
                  <td><strong>{{ '$'.number_format($totMatCost,2) }}</strong></td>
                  <td><strong>{{ '$'.number_format($totLabCost,2) }}</strong></td>
                  <td><strong>{{ '$'.number_format($totCostIncuredToDate,2) }}</strong></td>
                  <td><strong>{{ '$'.number_format($totProfitToDate,2) }}</strong></td>
                  <td><strong>{{ '$'.number_format($totAmtEarnedToDate,2) }}</strong></td>
                  <td><strong>{{ '$'.number_format($totAmtBilledToDate,2) }}</strong></td>
                  <td><strong>{{ '$'.number_format($totCostinExcessofBillings,2) }}</strong></td>
                  <td><strong>{{ '$'.number_format($totBillingsinExcessofCost,2) }}</strong></td>
                  <td><strong>{{ '$'.number_format($totEstCostToComplete,2) }}</strong></td>
                  <td><strong>{{ '$'.number_format($totContractBalance,2) }}</strong></td>
                  </tr>
              </tbody>
              </table>