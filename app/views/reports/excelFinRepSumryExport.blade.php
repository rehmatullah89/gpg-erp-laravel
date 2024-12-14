           <?php
                                         $reportYear = Input::get('y');
                                         if(empty($reportYear))
                                            $reportYear = date('Y');
                                      ?>
                 <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                  <tbody>
                    <tr bgcolor="#F2F2F2">
                      <?php 
                      $elecYearInvoiceTotal =0;
                      for ($i=0; $i<=12; $i++) { ?>
                        <td width="<?php echo ($i==0?200:105); ?>" height="30" align="center" class="smallblack"><?php echo ($i>0?date('F',strtotime($reportYear.'-'.$i.'-01')):'') ?></td>
                      <?php } ?>
                        <td width="150" align="center" class="smallblack" ><strong>Totals</strong></td>
                        <td width="150" align="center" class="smallblack" ><strong>Year Totals</strong></td>
                    </tr>
                    <?php 
                      for ($i=1; $i<=21; $i++) { 
                        $totalLine = 0;
                    ?>
                    <tr bgcolor="<?php echo ((($i>=4 && $i<=6) || ($i>=10 && $i<=12) || ($i>=16 && $i<=18))?"#FFFFCC":"#FFFFFF") ?>" >
                    <td height="35" class="smallblack" align="center"><?php echo $valuesMonth['label_'.$i] ?></td> 
                    <?php for ($j=1; $j<=12; $j++) { 
                      $spl = explode('~',$valuesMonth['ref_'.$i]);
                    ?>
                    <td class="smallblack"  align="center" ><?php  
                      echo HTML::link('job/financial_report/','$'.number_format(@$valuesMonth[$j][$spl[0]][$spl[1]],2), array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                      $totalLine += @$valuesMonth[$j][$spl[0]][$spl[1]];
                    if($j==18){
                      $elecYearInvoiceTotal = @$valuesMonth[$j][$spl[0]]['year'][$spl[1]];
                      }
                    ?>
                    </td>
                    <?php } ?>
                    <td align="center" class="smallblack" ><?php echo '$'.number_format($totalLine,2)?></td>
                    <td align="center" class="smallblack" >
                    <?php echo HTML::link('job/financial_report/','$'.number_format(@$elecYearInvoiceTotal,2), array('target'=>'_blank','class'=>'btn btn-link btn-xs')); ?></td>
                  </tr>
                    <?php if ($i==18) { ?>
                  <tr bgcolor="#F2F2F2">
                    <td  colspan="15" height="30" align="left" class="smallblack"><strong>&nbsp;&nbsp;TOTALS</strong></td>
                  </tr>
                  <?php } ?>
                  <?php } ?>
                   
                  </tbody>
                </table>