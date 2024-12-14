<table class="table table-bordered table-striped table-condensed cf" >
              <thead class="cf">
              <tr>
                  <th>Date Opened</th>
                  <th>Job Number</th>
                  <th>Contract Number</th>
                  <th >Reason</th>
                  <th >Reschedule Date</th>
                  <th >Customer</th>
                  <th >Notes</th>
                  <th >Count Days</th>
              </tr>
              </thead>
              <tbody class="cf">
                @foreach($query_data as $row)
                  <tr>
                    <td>{{($row['created_on']!=""?date('m/d/Y',strtotime($row['created_on'])):"-")}}</td>
                    <td>{{ HTML::link('job/service_job_list', $row['job_num'] , array('target'=>'_blank','class'=>'btn btn-link'))}} </td>
                    <td>{{ HTML::link('job/service_job_list', $row['contract_number']!=""?$row['contract_number']:"-" , array('target'=>'_blank','class'=>'btn btn-link'))}} </td>
                    <td title="{{$row['sub_task']}}">{{$row['task']}}</td>
                    <td>{{($row['schedule_date']!=""?date('m/d/Y',strtotime($row['schedule_date'])):"-")}}</td>
                    <td>{{$row['cus_name']}}</td>
                    <td>
                      <?php $notesRs = DB::select(DB::raw("SELECT *,(select name from gpg_employee where id = entered_by) as enterdBy FROM gpg_job_note WHERE gpg_job_id = '".$row['jobID']."' ORDER BY dated"));
                        foreach ($notesRs as $key => $notesRow) {
                          echo (!empty($notesRow->dated)?date('m/d/Y',strtotime($notesRow->dated)):'')."@".$notesRow->enterdBy.": ".$notesRow->notes."<br>";
                        }
                      ?>
                    </td>
                    <td>{{$row['count_days']}}</td>
                  </tr>
                @endforeach
              </tbody>
              </table>