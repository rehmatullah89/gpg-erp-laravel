<table class="table table-bordered table-striped table-condensed cf" >
              <thead class="cf">
              <tr>
                  <th>Lead ID</th>
                  <th>Customer</th>
                  <th >Location</th>
                  <th >Sales Person</th>
                  <th>Created Date</th>
                  <th >Days As of Today</th>
              </tr>
              </thead>
              <tbody class="cf">
                @foreach($query_data as $salesTrackingRow)
                  <tr>
                    <td>{{$salesTrackingRow['id']}}</td>
                    <td>{{$salesTrackingRow['customer']}}</td>
                    <td>{{$salesTrackingRow['location']}}</td>
                    <td>{{$salesTrackingRow['salesPerson']}}</td>
                    <td>{{date('m/d/Y',strtotime($salesTrackingRow['enter_date']))}}</td>
                    <td>{{$salesTrackingRow['daysAsofToday']}}</td>
                  </tr>
                @endforeach
              </tbody>