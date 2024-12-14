<?php
 $reportMonthStart = Input::get('mStart');
 if (empty($reportMonthStart))
    $reportMonthStart = date('01');
$reportMonthEnd = Input::get('mEnd');
  if (empty($reportMonthEnd))
    $reportMonthEnd = date('m');
?>
<table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                                      <thead class="cf">
                                      <tr>
                                          <th style="text-align:center;">SalesPerson</th>
                                          <th style="text-align:center;">Type</th>
                                          <th style="text-align:center;">Status</th>
                                          <?php
                                           $reportYearStart = Input::get('yStart');
                                           if (empty($reportYearStart))
                                            $reportYearStart = date('Y');
                                            $reportYearEnd = Input::get('yStart');
                                           if (empty($reportYearEnd))
                                            $reportYearEnd = date('Y'); 
                                           $monthCount = 12-$reportMonthStart + $reportMonthEnd + (($reportYearEnd-$reportYearStart-1)*12);
                                           $monCont = $reportMonthStart;
                                           $yearCont = $reportYearStart;
                                           $dateArray = array();
                                           for($i=0;$i<=$monthCount;$i++){
                                            $dateArray[$i] = date("m-Y",strtotime($yearCont.'-'.$monCont.'-01'));
                                            echo "<th align='center'><strong>".$dateArray[$i]."</strong><br>Count<br>Amount</th>";
                                            if ($monCont>=12) {
                                               $monCont-=12; 
                                               $yearCont++;
                                               }
                                            $monCont++;  
                                           }
                                          ?>
                                          <th style="text-align:center;"><strong>Total</strong><br>Count<br>Amount</th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                      <?php 
                                        $preEmp = '';
                                        $preType = '';
                                        $preStatus = '';
                                        $fg=true; 
                                        foreach($query_data as $key => $value) { 
                                         ?>
                                         <tr>
                                        <?php
                                         $preType = '';
                                         echo "<td align= 'center' >".$key."</td>";
                                         foreach($value as $ke => $val) {
                                           $preStatus = ''; 
                                           echo (($preType==$ke or !empty($preType))?"<tr><td align= 'center'>-</td>":"");  
                                           echo "<td align= 'center'>".(($preType!=$ke or empty($preType))?ucwords($ke):"")."</td>";
                                           foreach($val as $k => $v) {
                                            $totAmt = 0;
                                            $totCount = 0;
                                            $lastEmpID = '';  
                                            echo (($preStatus==$k or !empty($preStatus))?"<tr><td align= 'center'>-</td><td align= 'center'>-</td>":"");
                                            echo "<td align= 'center'>".ucwords($k)."</td>";
                                            reset($dateArray);
                                           foreach ($dateArray as $dateKey => $dateVal) {
                                            $dateSp = explode('-',$dateVal);
                                            echo "<td align= 'center' height='40'>".(isset($v[$dateVal]['count']) && $v[$dateVal]['count']!=''?HTML::link('quote/elec_quote_list', number_format($v[$dateVal]['amount'],2) , array('target'=>'_blank','class'=>'btn btn-link')):"-")."</td>";
                                            if (!isset($v[$dateVal]['amount']))
                                               $v[$dateVal]['amount'] =0; 
                                            $totAmt = $totAmt+$v[$dateVal]['amount'];
                                            if (!isset($v[$dateVal]['count']))
                                              $v[$dateVal]['count'] =0;
                                            $totCount = $totCount+$v[$dateVal]['count'];
                                              if (isset($v[$dateVal]['employeeID']))
                                                $lastEmpID = $v[$dateVal]['employeeID'];
                                              }

                                            echo "<td align= 'center' height='40'>".($totCount!=''?HTML::link('quote/elec_quote_list', number_format($totAmt,2) , array('target'=>'_blank','class'=>'btn btn-link')):"-")."</td></tr>"; $preStatus = $k; }  echo "</tr>"; $preType = $ke; 
                                           } 
                                           echo "</tr>"; 
                                           $preEmp = $key; $fg=!$fg; 
                                          } ?>
                                      </tbody>
                                  </table>