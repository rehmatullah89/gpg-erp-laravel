 <table class="table table-bordered table-striped table-condensed cf" >
                <tbody>
                  <tr>
                    <td colspan="<?php echo sizeof($arr_eng)+1?>" bgcolor="#FFFFFF" height="30px"></td>
                  </tr>
                  <tr>
                    <td width="200px" height="30" bgcolor="#EEEEEE"><div align="center"><strong>kW Range</strong></div></td>
                    <?php
                    foreach($arr_eng as $key => $value) {
                    ?>
                      <td width="400px" height="30" bgcolor="#EEEEEE"><div align="center"><strong><?php echo str_replace("~","<br>",$value)?></strong></div></td>
                    <? } ?>
                  </tr>
                  <? 
                  $bgcolor = "#FFFFFF";
                  $srno = 1;
                  if(sizeof($kw_array)>0) {
                    foreach($kw_array as $key => $value) {
                      $srno++;
                  ?>
                    <tr bgcolor="<?php echo $bgcolor?>" height="25px">
                      <td id="<?php echo $srno?>_0_cell"><?php echo $value["range"];?></td><?
                      $sr_no_col = 0;
                      $inv_sum = 0;
                      foreach($value["engines"] as $keys => $values) {
                        $inv_sum = 0;
                        $sr_no_col++;
                        $temp_gen_name_arr = explode('~',$keys);                
                        $str_contracts = ""; 
                        if(is_array(@$values['contract_num'])) {
                          foreach(@$values['contract_num'] as $cont_nums => $cont_nums2) {
                            $inv_sum0 = DB::select(DB::raw("SELECT SUM(gpg_job_invoice_info.invoice_amount) as invc_sum
                                        FROM
                                        gpg_job gj,
                                        gpg_job_invoice_info
                                        WHERE
                                        gpg_job_invoice_info.job_num = gj.job_num
                                        AND gj.contract_number = '".$cont_nums."'
                                        AND (gj.job_num LIKE 'BO%' OR gj.job_num LIKE 'PM%')"));
                            $inv_sum += @$inv_sum0[0]->invc_sum;
                            $str_contracts .= $cont_nums."_";
                          }
                          $str_contracts = substr($str_contracts,0,strlen($str_contracts)-1);
                        }
                        $total_sum = number_format(@$values["sum_cost"]+@$values["sum_contract"]+$inv_sum+@$values["lab_sum"]+@$values["mat_sum"],2);
                    ?>
                    <td id="<?php echo $srno."_".$sr_no_col?>_cell" align="center"  onmousemove="<? if($total_sum>0){?>DG('divTaskDetail').innerHTML = DG('div<?php echo $srno?>_<?php echo $sr_no_col?>').innerHTML;showDiv();<? }?>cross_highlight(this);" onmouseout="closeDiv();cross_highlight_clear(this)">
                    {{ HTML::link('job/service_job_list',($total_sum>0?$total_sum:""), array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}} 
                    <div style="display:none" id="div<?php echo $srno?>_<?php echo $sr_no_col?>">
                    <table width="100%">
                      <tr>
                        <td><strong>Generator</strong></td><td><strong><?php echo strlen($temp_gen_name_arr[0])==0?"-":$temp_gen_name_arr[0]?></strong></td>
                      </tr>
                      <tr>
                        <td><strong>Engine</strong></td><td><strong><?php echo strlen($temp_gen_name_arr[1])==0?"-":$temp_gen_name_arr[1]?></strong></td>
                      </tr>
                      <tr>
                        <td><strong>kW Range</strong></td><td><strong><?php echo $value["range"]?></strong></td>
                      </tr>
                      <tr>
                        <td>Cost to Date</td><td><?php echo number_format(@$values["sum_cost"],2)?></td>
                      </tr>
                      <tr>
                        <td>Contract Amount</td><td><?php echo number_format(@$values["sum_contract"],2)?></td>
                      </tr>
                      <tr>
                        <td>Invoice</td><td><?php echo number_format($inv_sum,2)?></td>
                      </tr>
                      <tr>
                        <td width="50%">Labor Cost</td><td width="50%"><?php echo number_format(@$values["lab_sum"],2)?></td>
                      </tr>
                      <tr>
                        <td>Material Cost</td><td><?php echo number_format(@$values["mat_sum"],2)?></td>
                      </tr>
                    </table>
                  </div>
                  </td><? }?>
                  </tr> <? } } ?>
                </tbody>
              </table>