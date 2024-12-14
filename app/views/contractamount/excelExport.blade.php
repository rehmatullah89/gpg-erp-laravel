<table>
  <thead>
    <tr>
      <td>Customer</td>
      <td>Contract Number</td>
      <td>Contract Prefix</td>
      <td>Contract Digits</td>
      <td>Location</td>
      <td>Type</td>
      <td>Start Date</td>
      <td>Expiration Date</td>
      <td>Price per Year</td>
      <td>Billing Cycle</td>
      <td>Price per Visit</td>
      <td>Sales Person</td>
    </tr>
  </thead>
  <tbody>
    @foreach($results as $key=>$getRow)
    <tr>      
      <td>{{ $getRow->customer }}</td>
      <td>{{ $getRow->contract_number }}</td>
      <td>{{ $getRow->contract_prefix }}</td>
      <td>{{ $getRow->contract_digits }}</td>
      <td>{{ $getRow->location }}</td>
      <td>{{ $getRow->type }}</td>
      <td>{{ ($getRow->start_date != '-' ? date(Config::get('settings._DateFormat'),strtotime($getRow->start_date)) : '-') }}</td>
      <td>{{ ($getRow->end_date != '-' ? date(Config::get('settings._DateFormat'),strtotime($getRow->end_date)) : '-') }}</td>
      <td>{{ Generic::currency_format($getRow->price_per_year) }}</td>
      <td>{{ $getRow->billing_cycle }}</td>
      <td>{{ Generic::currency_format($getRow->price_per_visit) }}</td>
      <td>{{ $getRow->salesPerson }}</td>
    </tr>
    @endforeach
  </tbody>
</table>