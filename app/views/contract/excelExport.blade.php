<table>
  <thead>
    <tr>
      <th>Created Date</th>
      <th>Customer</th>
      <th>Location</th>
      <th>Sales Person</th>
      <th>Attached Contract Number</th>
      <th>Lead Id</th>
      <th>Contract Number</th>
      <th>Contract Type</th>
      <th>Type</th>
      <th>Make</th>
      <th>Model</th>
      <th>Serial Num</th>
      <th>KW</th>
      <th>Engine Make</th>
      <th>Engine Model</th>
      <th>Quoted Amount</th>
      <th>Calculated Amount</th>
      <th>Contact Name</th>
      <th>Contact Phone</th>
      <th>Generator KW</th>
      <th>Status</th>
      <th>Date Won</th>      
      <th>Invoice Amount</th>
      <th>Sales Tax</th>
      <th>Labor Cost</th>
      <th>Marerial Cost</th>
      <th>Total Cost</th>
      <th>Net Margin</th>
      <th>Comm. Owed</th>
      <th>Comm. Paid</th>
      <th>Date Comm. Paid</th>
      <th>Comm. Balance</th>
      <th>Downloads</th>
    </tr>
  </thead>
  <tbody>
    @foreach($results as $key=>$getRow)
    <tr>      
      <td>{{ ($getRow->created_on != '-' ? date('m/d/Y',strtotime($getRow->created_on)) : '-') }}</td>
      <td>{{ $getRow->customer }}</td>
      <td>{{ $getRow->eqp_location }}</td>
      <td>{{ $getRow->salesPerson }}</td>
      <td>{{ (!empty($getRow->GPG_attach_contract_number)) ? $getRow->GPG_attach_contract_number : '' }}</td>
      <td>{{ $getRow->attachLeadId }}</td>
      <td>{{ $getRow->job_num }}</td>
      <td>{{ (!empty($getRow->consum_contract_type)) ? $CONTRACT_TYPE[$getRow->consum_contract_type] : '' }}</td>
      <td>{{ $getRow->eqp_type }}</td>
      <td>{{ $getRow->eqp_make }}</td>
      <td>{{ $getRow->eqp_model }}</td>
      <td>{{ $getRow->eqp_serial }}</td>
      <td>{{ $getRow->eqp_kw }}</td>
      <td>{{ $getRow->engMake }}</td>
      <td>{{ $getRow->engModel }}</td>
      <td>{{ Generic::currency_format($getRow->manual_amount) }}</td>
      <td>{{ Generic::currency_format($getRow->grand_list_total) }}</td>
      <td>{{ $getRow->pri_contact_name }}</td>
      <td>{{ $getRow->pri_contact_phone }}</td>
      <td>{{ $getRow->gen_kw }}</td>
      <td>{{ ($getRow->is_renewed == 1 ? "Renewed - " : "") }}{{ $getRow->consum_contract_status }}</td>
      <td>{{ ($getRow->date_job_won != '-' ? date('m/d/Y',strtotime($getRow->date_job_won)) : '-') }}</td>      
      <td>{{ Generic::currency_format($getRow->invoice_amount) }}</td>
      <td>{{ Generic::currency_format($getRow->invoice_tax) }}</td>
      <td>{{ Generic::currency_format($getRow->labor_cost) }}</td>
      <td>{{ Generic::currency_format($getRow->material_cost) }}</td>
      <td>{{ Generic::currency_format($getRow->material_cost+$getRow->labor_cost) }}</td>
      <td>{{ Generic::currency_format($getRow->net_margin) }} <strong>[{{ $netMarginPercent = number_format(@($getRow->net_margin/$getRow->invoice_amount)*100,2) }}%]</strong></td>
      <td>{{ Generic::currency_format($getRow->comm_owed) }}</td>
      <td>{{ Generic::currency_format($getRow->comm_amount) }}</td>
      <td>{{ ($getRow->comm_date != '-' ? date('m/d/Y',strtotime($getRow->comm_date)) : '-') }}</td>
      <td>{{ Generic::currency_format($getRow->comm_balance) }}</td>      
      <td>{{ Generic::show_consum_contract_attactments($getRow->id) }}</td>
    </tr>
    @endforeach
  </tbody>
</table>