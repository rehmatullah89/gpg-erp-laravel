                   <table class="table table-bordered table-striped table-condensed cf" >
                      <tbody>
                        <?php $colcount=0;?>
                        @foreach($query_data as $row)
                          <?php $colcount++;?>
                          <tr height="40px">
                            <td align="center" ><strong>{{Form::button('<i class="fa fa-plus"></i>', array('class' => 'btn btn-success btn-xs', 'onclick'=>'toggleCustomerInfo('.$colcount.','.((trim($row['gpg_customer_id']) != '')?$row['gpg_customer_id']:0).')'))}}</strong></td>
                            <td bgcolor="#FFFFCC" width="100px"><strong>{{$row['gpg_customer_id']}}</strong></td>
                            <td bgcolor="#FFFFCC"><strong>{{$row['cus_name']==""?"&lt;no name&gt;":$row['cus_name']}}</strong></td>
                            <td bgcolor="#FFFFCC" width="50px">{{$row['tot_contact']}}</td>
                          </tr>
                            <tr id="hideme_{{$colcount}}" bgcolor="#FFFFCC"><td colspan="6">
                              <!-- orgin Start -->
                              <?php
                              if($row['tot_contact']>4)
                              {
                                $info_res = DB::select(DB::raw("SELECT id, status, type_of_sale, (SELECT NAME FROM gpg_employee WHERE id = gpg_employee_id) as emp_name, contact_info FROM gpg_sales_tracking WHERE gpg_customer_id = '".$row['gpg_customer_id']."'"));
                              ?>
                              <table bordercolor="#cccccc" cellspacing="1" width="100%" border="1" style="border-collapse:collapse">
                                <thead>                              
                                  <tr height="35px">
                                    <td bgcolor="#F2F2F2"><strong>Lead Id</strong></td>
                                    <td bgcolor="#F2F2F2"><strong>Lead Status</strong></td>
                                    <td bgcolor="#F2F2F2"><strong>Type of Sale</strong></td>
                                    <td bgcolor="#F2F2F2"><strong>Employee</strong></td>
                                    <td bgcolor="#F2F2F2"><strong>Contact Info</strong></td>
                                  </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($info_res as $key => $value1) {
                                    $arr_row = (array)$value1;
                                  ?>
                                <tr>
                                    <td bgcolor="#FFF">{{$arr_row['id']}}</td>
                                    <td bgcolor="#FFF">{{$arr_row['status']}}</td>
                                    <td bgcolor="#FFF">{{$arr_row['type_of_sale']}}</td>
                                    <td bgcolor="#FFF">{{$arr_row['emp_name']}}</td>
                                    <td bgcolor="">
                                        <table bordercolor="#cccccc" cellspacing="1" width="100%" border="1" style="border-collapse:collapse">
                                        <tr>
                                          <td bgcolor='#FFF'><?php 
                                            if(preg_match("/#@@#/",$arr_row['contact_info'])){
                                              $arr_row['contact_info'] = str_replace("::","</td><td bgcolor='#FFF'>",$arr_row['contact_info']);
                                              $dat = explode("#@@#",$arr_row['contact_info']);
                                              echo implode("</td></tr><tr><td bgcolor='#FFF'>",$dat);
                                            }else{
                                              echo $arr_row['contact_info'];
                                            }
                                            ?>
                                            </td>
                                          </tr>
                                          </table>
                                    </td>
                                </tr>
                              <?php
                                }// end while
                              ?>
                            </tbody>
                            </table>
                            <?php
                            } //endif
                            else{
                              if(preg_match("/~@@@~/",$row['cnt_info'])) {?>
                                <table bordercolor="#cccccc" cellspacing="1" width="100%" border="1" style="border-collapse:collapse">
                                  <tr height="35px">
                                    <td bgcolor="#F2F2F2"><strong>Lead Id</strong></td>
                                    <td bgcolor="#F2F2F2"><strong>Lead Status</strong></td>
                                    <td bgcolor="#F2F2F2"><strong>Type of Sale</strong></td>
                                    <td bgcolor="#F2F2F2"><strong>Employee</strong></td>
                                    <td bgcolor="#F2F2F2"><strong>Contact Info</strong></td>
                                  </tr><?php
                                  foreach(explode("~@@@~",$row['cnt_info']) as $value) {
                                    $data = explode("--@--",$value);
                                  ?>
                                  <tr>
                                    <td bgcolor="#FFF"><?php echo str_replace(",","",@$data[0]);?></td>
                                    <td bgcolor="#FFF"><?php echo @$data[1];?></td>
                                    <td bgcolor="#FFF"><?php echo @$data[2];?></td>
                                    <td bgcolor="#FFF"><?php echo @$data[3];?></td>
                                    <td bgcolor="">
                                      <table bordercolor="#cccccc" cellspacing="1" width="100%" border="1" style="border-collapse:collapse">
                                      <tr>
                                        <td bgcolor='#FFF'><?php
                                        if(preg_match("/#@@#/",@$data[4])) {
                                          @$data[4] = str_replace("::","</td><td bgcolor='#FFF'>",@$data[4]);
                                          $dat = explode("#@@#",@$data[4]);
                                          echo implode("</td></tr><tr><td bgcolor='#FFF'>",$dat);
                                        }else{
                                          echo @$data[4];
                                        } ?>
                                        </td>
                                      </tr>
                                      </table>
                                    </td>
                                  </tr><?php } ?>
                              </table>
                              <?php }else{ ?>
                              <table bordercolor="#cccccc" cellspacing="1" width="100%" border="1" style="border-collapse:collapse">
                                  <tr>
                                  <td bgcolor="#FFF"><?php echo $row['cnt_info']?></td>
                                  </tr>
                              </table>
                              <?php }
                            }?>
                       <!-- orgin End -->  
                      </td>
                    </tr> 
                    @endforeach
                    </tbody>
                  </table>