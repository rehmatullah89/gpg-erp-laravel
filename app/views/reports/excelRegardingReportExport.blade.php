              <table class="table table-bordered table-striped table-condensed cf" >
              <thead class="cf">
              <tr>
                  <th>Customer</th>
                  <th>Fuel Delivery</th>
                  <th>Coolant Flush </th>
                  <th> Fuel Polish   </th>
                  <th>Fuel Sample</th>
                  <th>Load Bank Test</th>
                  <th>Permit Fine </th>
                  <th>Repair </th>
                  <th>Service Engine-Fire Pump </th>
                  <th>Service Generator </th>
                  <th>Total </th>
              </tr>
              </thead>
              <tbody class="cf">
                <?php 
                  $colcount=0;
                  $grandTot = array();
                  foreach($query_data as $key=>$value) {
                    $tot = 0;
                    $entry=0;
                    $entry2=0;
                    $colcount++;
                    echo "<tr  bgcolor=\"".($colcount%2==0?"#FFFFCC":"#FFFFFF")."\">";
                    echo "<td height=\"35\" align = \"center\"  nowrap>&nbsp;".$key."&nbsp;</td>";
                    foreach($regardingArray as $key1=>$value1) {
                      if (!isset($grandTot[$value1]["tot"]) && $entry==0){
                        $grandTot[$value1]["tot"] =0;
                        $entry++;
                      }
                      if (!isset($value[$value1]["cnt"]) && $entry2==0){
                        $value[$value1]["cnt"] =0;
                        $entry2++;
                      }
                      echo "<td  align = \"center\"  nowrap>".HTML::link('job/service_job_list',isset($value[$value1]["cnt"])?$value[$value1]["cnt"]:'-', array('class'=>'btn btn-link btn-xs'))."</td>";
                      @$grandTot[$value1]["tot"] = @$grandTot[$value1]["tot"] + @$value[$value1]["cnt"];
                      $tot = $tot + @$value[$value1]["cnt"];
                    }
                    echo "<td height=\"35\" align = \"center\"  nowrap>&nbsp;<strong>".$tot."</strong>&nbsp;</td>";
                    echo "</tr>";
                  }     
                ?>
              </tbody>
              </table>