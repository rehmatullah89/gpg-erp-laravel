            <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                    <thead>
                      <tr>
                        <th>SR #</th>
                        <th>Fixture ID</th>
                        <th>Fixture Name</th>
                        <th>Quantity Used</th>
                        <th>Quotes</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $data = array();
                    foreach ($query_data as $key => $row){
                      $data[$row['id']]['name'] = $row['fixture_name'];
                      if(!isset($data[$row['id']]['total']))
                        $data[$row['id']]['total'] = 0;
                        $data[$row['id']]['total'] += $row['pro_qty_used'];
                        $data[$row['id']]['jobs'][$row['job_num']] = $row['pro_qty_used']."_".$row['gjeqid']."_".$row['occurence']."_".$row['cus_name'];
                    } // end foreach
                    $srno = 0;
                    if(sizeof($data)>0){
                      foreach($data as $key => $value){
                        $bgcolor = "#FFFFCC";
                        $srno++;
                        if($srno%2==0)
                          $bgcolor = "#FFFFFF";
                        ?>
                        <tr bgcolor="<?php echo $bgcolor?>" height="25px">
                          <td>{{$srno}}</td>
                          <td>{{$key}}</td>
                          <td>{{$value['name']}}</td>
                          <td>{{$value['total']}}</td>
                          <td title="{{print_r($value['jobs'])}}"><?php
                            foreach($value['jobs'] as $key2 => $value2){
                              $temp_arr = explode("_",$value2);
                              ?>
                              {{ HTML::link('quote/elec_quote_list',$key2.'-'.@$temp_arr[3], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}
                              <br/>
                              <div  id="details_<?php echo $key2?>_<?php echo $srno?>" style="display:none;">
                                <table bgcolor="#EEEEEE" border="1px soild #000000" cellspacing="0" cellpadding="4" style="border-collapse:collapse" width="200px">
                                  <tr>
                                    <td colspan="2" align="center"><strong><?php echo $key2?></strong></td>
                                  </tr>
                                  <tr>
                                    <td bgcolor="#FFFFCC">Quantity</td>
                                    <td bgcolor="#FFFFFF"><?php echo $temp_arr[0]?></td>
                                  </tr>
                                  <tr>
                                    <td bgcolor="#FFFFCC">Occurence</td>
                                    <td bgcolor="#FFFFFF"><?php echo $temp_arr[2]?> time(s)</td>
                                  </tr>
                                </table>
                              </div>
                            <?php }?>
                          </td>
                        </tr>
                        <tr bgcolor="<?php echo $bgcolor?>" height="25px" bordercolor="#000000" style="border:1px solid #000000;display:none;" >
                          <td colspan="4">
                            <table width="400px" style="border:1px solid #000000" cellspacing="0" bgcolor="<?php echo $bgcolor?>">
                              <tr height="30px">
                                <td align="center" style="border:1px solid #cccccc;"><strong>Total Quantity</strong></td>
                                <td align="center" style="border:1px solid #cccccc;"><strong>Occurence</strong></td>
                              </tr>
                              <?php 
                                foreach($value['jobs'] as $key2 => $value2){
                                  $temp_arr = explode("_",$value2);
                                  ?>
                                  <tr height="25px">
                                    <td style="border:1px solid #999999;"><?php echo $temp_arr[0]?></td>
                                    <td style="border:1px solid #999999;"><?php echo $temp_arr[2]?></td>
                                  </tr>
                                  <?php }?>
                            </table>
                          </div>
                         </td>
                        </tr>
                        <?php }
                        }?>
                    </tbody>  
                  </table> 