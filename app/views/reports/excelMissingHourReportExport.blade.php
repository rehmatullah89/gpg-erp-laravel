<table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                    <thead>
                      <tr>
                        <th>Employee Name</th>
                        <th>Employee Type </th>
                        <th>Missing Hours Date</th>
                      </tr>
                    </thead>
                    <tbody>
                     <?php
                      $SDateCreated = Input::get("SDateCreatedMissing");
                      $EDateCreated = Input::get("EDateCreatedMissing");
                      $dbDateStart = date('Y-m-d',strtotime($SDateCreated));
                      $dbDateEnd = date('Y-m-d',strtotime($EDateCreated));
                      $colcount=0;
                      foreach ($query_data as $key => $EmployeeJob_row){
                          $THurs =0;
                          $missingDates = array();
                          for ($i=1; $i<=$tDays; $i++) {  
                              $DayInfo_arr = DB::select(DB::raw("select ADDDATE('".$dbDateStart."', INTERVAL ".($i-1)." DAY) as t_day"));
                              $DayInfo = '';
                              if (!empty($DayInfo_arr) && isset($DayInfo_arr[0]->t_day))
                                $DayInfo = $DayInfo_arr[0]->t_day;
                              if (date("D",strtotime($DayInfo))!="Sat" && date("D",strtotime($DayInfo))!="Sun") {
                                $chkHurs = @$summaryDatesArr[$DayInfo][$EmployeeJob_row['empId']]+@$holidayArr[$DayInfo][$EmployeeJob_row['empId']]+@$leavesArr[$DayInfo][$EmployeeJob_row['empId']];              
                                $offDay = @$summaryDatesArr[$DayInfo][$EmployeeJob_row['empId']];
                                if ($chkHurs=='' && empty($offDay) && $DayInfo >= $EmployeeJob_row['empCreatedDate'])   
                                  $missingDates[] = date('m/d/Y',strtotime($DayInfo)); 
                              }
                          } 
                          if (count($missingDates)>0) {
                             $colcount++;
                          ?>
                          <tr  bgcolor="<? echo ($colcount%2==0?"#FFFFCC":"#FFFFFF"); ?>">
                          <td height="30" valign="top" >&nbsp;<? echo $EmployeeJob_row['empName'] ?></td>
                          <td valign="top" >&nbsp;<? echo $EmployeeJob_row['emp_type'] ?></td>
                          <td ><font color="#c10000" style="font-weight:bold;"><? 
                                  for ($feC =0; $feC<count($missingDates); $feC++) {
                                    echo "<p>".$missingDates[$feC]."</p>"; 
                                  }
                          ?></font>
                          </td>
                      </tr>
                      <? 
                        unset($missingDates);
                        } 
              }
              ?>
                    </tbody>
                  </table>