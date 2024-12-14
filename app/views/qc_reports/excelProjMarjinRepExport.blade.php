              <table class="table table-bordered table-striped table-condensed cf" >
              <thead class="cf">
              <tr>
                  <th>Serial</th>
                  <th>Quoted Date</th>
                  <th>Job Number</th>
                  <th>Quoted Amount</th>
                  <th>Projected Margin</th>
                  <th>Status </th>
                  <th>Date Won</th>
              </tr>
              </thead>
              <tbody class="cf">
                <?php 
                  $SrNo = 0;
                  foreach($query_data as $key => $row)
                  {
                    $SrNo++;
                    ?>
                    <tr bgcolor="<?php echo $row['date_job_won'] == ' - '? '#FFFFFF':'#DDF0FF' ?>">
                      <td height="30">{{$SrNo}}</td>            
                      <td>{{$row['created_on']}}</td>
                      <td>{{ HTML::link('quote/'.(preg_match("/E/i",$row['job_num'])?"elec_quote_list":(preg_match("/HS/i",$row['job_num'])?'shop_work_quote_list':((preg_match("/M/i",$row['job_num'])) ? "grassivy_quote_list" : ((preg_match("/J/i",$row['job_num']) ? 'specialproject_quote_list' : 'field_service_work_list'))))),$row['job_num'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                      <td>{{'$'.number_format($row['quoted_amount'],2)}}</td>
                      <td>{{'$'.number_format($row['projected_margin'],2)}}</td>
                      <td>{{$row['status']}}</td>
                      <td>{{$row['date_job_won']}}</td>         
                    </tr>
                <?php }?>  
              </tbody>
              </table>