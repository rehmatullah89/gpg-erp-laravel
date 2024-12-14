<table class="table table-bordered table-striped table-condensed cf" >
              <thead class="cf">
              <tr>
                  <th>Type</th>
                  <th>Date</th>
                  <th>Num</th>
                  <th>Name</th>
                  <th>Source Name</th>
                  <th>Memo</th>
                  <th>Account</th>
                  <th>Split</th>
                  <th>Amount</th>
              </tr>
              </thead>
              <tbody class="cf">
                @foreach($query_data as $gpg_job_cost)
                  @if(!empty($gpg_job_cost))
                  <tr>
                    <td>{{@$gpg_job_cost['type']}}</td>
                    <td>{{@$gpg_job_cost['date']}}</td>
                    <td>{{@$gpg_job_cost['num']}}</td>
                    <td>{{@$gpg_job_cost['name']}}</td>
                    <td>{{@$gpg_job_cost['source_name']}}</td>
                    <td>{{@$gpg_job_cost['memo']}}</td>
                    <td>{{@$gpg_job_cost['account']}}</td>
                    <td>{{@$gpg_job_cost['split']}}</td>
                    <td>{{@$gpg_job_cost['amount']}}</td>
                  </tr>
                  @endif
                @endforeach
              </tbody>
              </table>