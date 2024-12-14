            <table class="table table-bordered table-striped table-condensed cf">
                  @if($type == 'labor')
                  <thead class="cf">
                  <tr><th colspan="4">Labor Group by:{{$viewby}}</th><th colspan="2"></th><th colspan="3">Budgeted Labor Cost:{{ $jobTblRow['budgeted_labor'] }}</th><th colspan="3">Budgeted Hours:{{ $jobTblRow['budgeted_hours']}}</th><th colspan="2">Hours Left:{{$jobTblRow['budgeted_hours'] }}</th></tr>
                  <tr><th>Tech</th><th>Type</th><th>Date</th><th>Time In</th><th>Time Out</th><th>Total</th><th>Total in decimal</th><th>Reg</th><th>OT</th><th>DT</th><th>Reg $</th><th>OT $</th><th>DT $</th><th>Total $</th></tr>
                  </thead>
                  @elseif($type == 'jobcost')
                  <thead class="cf">
                  <tr><th colspan="3"> Job Cost(s) Sort By:{{$viewby}}</th><th>&nbsp;</th><th colspan="3">Budgeted Material Cost:{{$jobTblRow['budgeted_material']}}</th><th colspan="3">Budgeted Material Cost </th></tr>
                  <tr><th>Type</th><th>Date</th><th>Num</th><th>Name</th><th>Source Name</th><th>Memo</th><th>Account</th><th>Clr</th><th>Split</th><th>Amount</th></tr>  
                  </thead>
                  @elseif($type == 'jobpo')
                  <thead class="cf">
                  <tr><th colspan="12">Purchase Order(s) Sort By: {{$viewby}}</th></tr>  
                  <tr><th>Po#</th><th>Date</th><th>Job#/GL Code</th><th>Form of Payment</th><th>Vendor</th><th>Req. By</th><th>PO Writer</th><th>Quoted Amt</th><th>Amt to Date</th><th>Estimated Receipt Date</th><th>Sales/Ord.#</th><th>Note</th></tr>
                  </thead>
                  @elseif($type == 'jobpo_detail')
                  <tr><th colspan="10">Purchase Order Detail(s) Sort By:  {{$viewby}}</th></tr>
                  <tr><th>Po#</th><th>Po Date</th><th>Item#</th><th>Item Date</th><th>Job#/GL Code</th><th>Description</th><th>Qty</th><th>Rate</th><th>Amount</th><th>Received</th></tr>
                  @endif
                  <tbody class="cf">
                  {{$display_data}}
                  </tbody>
            </table>