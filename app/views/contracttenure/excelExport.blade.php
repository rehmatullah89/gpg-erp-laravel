<table>
  <thead>
    <tr>
      <td>Contract Number</td>
      <td>Customer Name</td>
      <td>Start Date</td>
      <td>End Date</td>
    </tr>
  </thead>
  <tbody>
    @foreach($results as $key=>$getRow)
    <tr>
      <td>{{ $getRow->job_num }}</td>
      <td>{{ $getRow->cus_name }}</td>
      <td>{{ ($getRow->consum_contract_start_date != '-' ? date(Config::get('settings._DateFormat'),strtotime($getRow->consum_contract_start_date)) : '-') }}</td>
      <td>{{ ($getRow->consum_contract_end_date != '-' ? date(Config::get('settings._DateFormat'),strtotime($getRow->consum_contract_end_date)) : '-') }}</td>
    </tr>
    @endforeach
  </tbody>
</table>