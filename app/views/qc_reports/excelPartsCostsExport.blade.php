<table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                    <thead>
                      <tr>
                        <th>Sr.No</th>
                        <th>Job Number</th>
                        <th>Part Type</th>
                        <th>Part Number</th>
                        <th>Serial Number</th>
                        <th>Spec Number</th>
                        <th>Part Cost</th>
                        <th>Cost in Job</th>
                        <th>Part List</th>
                        <th>List in Job</th>
                        <th>Part Margin</th>
                        <th>Margin in Job</th>
                        <th>Changed on</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                      $count =0;
                      if(count($query_data)>0)
                      {
                        $SrNo = 0;
                        foreach ($query_data as $key => $arr) {
                          $SrNo++;
                        ?>
                          <tr>
                            <td width="20" height="25" align="center" >{{++$count}}</td>
                            <td align="center" bgcolor="#FFFFCC">
                            {{ HTML::link('job/field_service_work_list', $arr['job_num'] , array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}
                            </td>
                            <td align="center" bgcolor="#FFFFCC">{{$arr['part_type']}}</td>
                            <td align="center" bgcolor="#FFFFCC">
                            {{ HTML::link('parts', $arr['part_number'] , array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}  
                            </td>
                            <td align="center" bgcolor="#FFFFCC">{{$arr['serial_number']}}</td>
                            <td align="center" bgcolor="#FFFFCC">{{$arr['spec_number']}}</td>
                            <td align="center" bgcolor="#FFFFCC">{{number_format($arr['mat_cost'],2)}}</td>
                            <td align="center" bgcolor="<?php echo $arr['cp']==1?"#FFC1C1":"#FFFFCC"?>" >{{number_format($arr['job_mat_cost'],2)}}</td>
                            <td align="center" bgcolor="#FFFFCC">{{number_format($arr['mat_list'],2)}}</td>
                            <td align="center" bgcolor="<?php echo $arr['lp']==1?"#FFC1C1":"#FFFFCC"?>">{{number_format($arr['job_mat_list'],2)}}</td>
                            <td align="center" bgcolor="#FFFFCC">{{number_format($arr['mat_margin'],2)}}</td>
                            <td align="center" bgcolor="<?php echo $arr['mp']==1?"#FFC1C1":"#FFFFCC"?>">{{number_format($arr['job_mat_margin'],2)}}</td>
                            <td align="center" bgcolor="#FFFFCC">{{date('m/d/Y',strtotime($arr['modified_on']))}}</td>
                          </tr>
                        <?php }
                      }?>
                    </tbody>  
                  </table>  