   <table class="table table-bordered table-striped table-condensed cf" >
              <thead class="cf">
              <tr>
                  <th>Job Number</th>
                  <th>Zone Index</th>
                  <th>FSW Status</th>
                  <th >Customer</th>
                  <th >Jobsite</th>
                  <th >Salesman</th>
                  <th >Fbomb</th>
                  <th >Quoted Amount</th>
                  <th >Date Quoted</th>
                  <th >Date Won</th>
                  <th >Date Parts Ordered</th>
                  <th >Days Ordered</th>
                  <th >Date Parts Received</th>
                  <th >Days Received</th>
                  <th >Date job Scheduled</th>
                  <th >Notes</th>
              </tr>
              </thead>
              <tbody class="cf">
               @foreach($query_data as $row)
                <tr>
                  <td>{{ HTML::link('job/service_job_list', $row['jobNum'] , array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                  <td><?php
                  if(isset($row['zone_index_id'])){
                    $zi_query = DB::table('gpg_settings')->where('id','=',$row['zone_index_id'])->pluck('value');
                    echo @$zi_query;
                  }else{
                    echo "-";
                  }
                  ?></td>
                  <td>{{(isset($FSWStatusArray[$row['fwsStatus']])?$FSWStatusArray[$row['fwsStatus']]:'-')}}</td>
                  <td>{{(isset($row['jobCustomer'])?$row['jobCustomer']:'-')}}</td>
                  <td>{{(isset($row['jobSite'])?$row['jobSite']:'-')}}</td>
                  <td>{{(isset($row['jobEmployee'])?$row['jobEmployee']:'-')}}</td>
                  <td>{{ HTML::link('job/service_job_list', $row['fNum'] , array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                  <td>{{'$'.number_format($row['fQoueAmt'],2)}}</td>
                  <td>{{(!empty($row['fQouteDate'])?date('m/d/Y',strtotime($row['fQouteDate'])):'-')}}</td>
                  <td>{{(!empty($row['fDateWon'])?date('m/d/Y',strtotime($row['fDateWon'])):'-')}}</td>
                  <td>{{(!empty($row['jobDatePartsOrderd'])?date('m/d/Y',strtotime($row['jobDatePartsOrderd'])):'-')}}</td>
                  <td>
                    <?php if(!empty($row['jobDatePartsOrderd']) && !empty($row['jobDatePartsRecieved'])) {
                            $datetime1 = new DateTime($row['jobDatePartsOrderd']);
                            $datetime2 = new DateTime($row['jobDatePartsRecieved']);
                            $interval = $datetime1->diff($datetime2);
                            echo $interval->format('%R%a days');
                      } elseif(!empty($row['jobDatePartsOrderd']) && empty($row['jobDatePartsRecieved'])) {
                            $datetime1 = new DateTime($row['jobDatePartsOrderd']);
                            $datetime2 = new DateTime(date('Y-m-d'));
                            $interval = $datetime1->diff($datetime2);
                            echo $interval->format('%R%a days');
                      }?>
                  </td>
                  <td>{{(!empty($row['jobDatePartsRecieved'])?date('m/d/Y',strtotime($row['jobDatePartsRecieved'])):'-')}}</td>
                  <td><?php if(!empty($row['jobDatePartsRecieved'])) {
                          $datetime1 = new DateTime($row['jobDatePartsRecieved']);
                          $datetime2 = new DateTime(date('Y-m-d'));
                          $interval = $datetime1->diff($datetime2);
                          echo $interval->format('%R%a days');
                      }else echo "-"; 
                      ?>
                  </td>
                  <td>{{(!empty($row['jobDateSchduled'])?date('m/d/Y',strtotime($row['jobDateSchduled'])):'-')}}</td>
                  <td>
                    <?php $notesRs = DB::select(DB::raw("SELECT *,(select name from gpg_employee where id = entered_by) as enterdBy FROM gpg_job_note WHERE gpg_job_id = '".$row['jobID']."' ORDER BY dated"));
                          foreach ($notesRs as $key => $value) {
                            $notesRow = (array)$value;
                            echo (!empty($notesRow['dated'])?date('m/d/Y',strtotime($notesRow['dated'])):'')."@".$notesRow['enterdBy'].": ".$notesRow['notes']."<br>";                         
                          }
                          if (empty($notesRs)) echo "-";
                    ?>
                  </td>
                </tr>
               @endforeach
              </tbody>