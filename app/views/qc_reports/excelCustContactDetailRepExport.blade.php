                <table class="table table-bordered table-striped table-condensed cf" >
                <thead class="cf">
                  <tr>
                    <th style="text-align:center;" colspan="2"></th>
                    <th style="text-align:center;" colspan="8">Customer</th>
                    <th style="text-align:center;" colspan="7">Location</th>
                  </tr>
                  <tr bgcolor="#F2F2F2">
                    <th>Quote #</th>
                    <th>Job #</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th>Address2</th>
                    <th>Email</th>
                    <th>Telephone</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Zip</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th>Email</th>
                    <th>Telephone</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Zip</th>
                 </tr>
                </thead>
              <tbody class="cf">
              <?php
                if($customer_flag){
                foreach($query_data as $cust_name=>$cust_data){
                  if(isset($cust_data['field_job']) || isset($cust_data['shop_job']) || isset($cust_data['elec_job']) || isset($cust_data['grassivy_job']) || isset($cust_data['special_project_job']) ){
                    echo '<tr bgcolor="#F2F2F2" class="tablehead" height="30px"><td  colspan="17" align="left"><strong>'.$cust_name.'</strong></td></tr>';
                    if(isset($cust_data['field_job']) && is_array($cust_data['field_job'])){
                      foreach($cust_data['field_job'] as $job_num => $val){
              ?>
                <tr bgcolor="#FFF" class="tablehead" height="30px">
                      <td align="center">
                      {{ HTML::link('job/field_service_work_list', $cust_data['field_job'][$job_num]['quote_no'] == "" ? "-" : $cust_data['field_job'][$job_num]['quote_no'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}
                      </td>
                      <td align="center">
                      {{ HTML::link('job/service_job_list',$cust_data['field_job'][$job_num]['job_no'] == "" ? "-": $cust_data['field_job'][$job_num]['job_no'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}  
                      </td>
                      <td align="center"><?php echo $cust_name == ""? "-" : $cust_name;?></td>
                      <td align="center"><?php echo trim($cust_data['customer_address']) == "" ? "-" : @$cust_data['customer_address']; ?></td>
                      <td align="center"><?php echo trim($cust_data['customer_address2']) == "" ? "-" : @$cust_data['customer_address2']; ?></td>
                      <td align="center"><?php echo @$cust_data['customer_email']=="" ? "-" : @$cust_data['customer_email']; ?></td>
                      <td align="center"><?php echo @$cust_data['customer_phone'] == "" ? "-": @$cust_data['customer_phone'] ; ?></td>
                      <td align="center"><?php echo @$cust_data['customer_city'] == "" ? "-": @$cust_data['customer_city']; ?></td>
                      <td align="center"><?php echo @$cust_data['customer_state'] == "" ? "-" : @$cust_data['customer_state']; ?></td>
                      <td align="center"><?php echo @$cust_data['customer_zip'] == "" ? "-": @$cust_data['customer_zip']; ?></td>
                      <td align="center"><?php echo @$cust_data['field_job'][$job_num]['project_name'] == "" ? "-" : @$cust_data['field_job'][$job_num]['project_name'];?></td>
                      <td align="center"><?php echo @$cust_data['field_job'][$job_num]['address'] == "" ? "-" : @$cust_data['field_job'][$job_num]['address'];?></td>
                      <td align="center"><?php echo @$cust_data['field_job'][$job_num]['project_email'] == "" ? "-" : @$cust_data['field_job'][$job_num]['project_email'];?></td>
                      <td align="center"><?php echo @$cust_data['field_job'][$job_num]['project_phone'] == "" ? "-" :@$cust_data['field_job'][$job_num]['project_phone'];?></td>
                      <td align="center"><?php echo @$cust_data['field_job'][$job_num]['project_city'] == "" ? "-" :@$cust_data['field_job'][$job_num]['project_city'];?></td>
                      <td align="center"><?php echo @$cust_data['field_job'][$job_num]['project_state'] == "" ? "-" :@$cust_data['field_job'][$job_num]['project_state'];?></td>
                      <td align="center"><?php echo @$cust_data['field_job'][$job_num]['project_zip'] == "" ? "-" :@$cust_data['field_job'][$job_num]['project_zip'];?></td>
                    </tr>
              <?php
                      }
                    }
                    if(isset($cust_data['shop_job']) && is_array($cust_data['shop_job'])){
                      foreach($cust_data['shop_job'] as $job_num => $val){
              ?>
              <tr bgcolor="#FFF" class="tablehead" height="30px">
                      <td align="center">
                      {{ HTML::link('quote/shop_work_quote_list',$cust_data['shop_job'][$job_num]['quote_no'] == "" ? "-" : $cust_data['shop_job'][$job_num]['quote_no'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}  
                      </td>
                      <td align="center">
                      {{ HTML::link('job/service_job_list',$cust_data['shop_job'][$job_num]['job_no'] == "" ? "-": $cust_data['shop_job'][$job_num]['job_no'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}    
                      </td>
                      <td align="center"><?php echo $cust_name == ""? "-" : $cust_name;?></td>
                      <td align="center"><?php echo trim($cust_data['customer_address']) == "" ? "-" : $cust_data['customer_address']; ?></td>
                      <td align="center"><?php echo trim($cust_data['customer_address2']) == "" ? "-" : $cust_data['customer_address2']; ?></td>
                      <td align="center"><?php echo $cust_data['customer_email']=="" ? "-" : $cust_data['customer_email']; ?></td>
                      <td align="center"><?php echo $cust_data['customer_phone'] == "" ? "-": $cust_data['customer_phone'] ; ?></td>
                      <td align="center"><?php echo $cust_data['customer_city'] == "" ? "-": $cust_data['customer_city']; ?></td>
                      <td align="center"><?php echo $cust_data['customer_state'] == "" ? "-" : $cust_data['customer_state']; ?></td>
                      <td align="center"><?php echo $cust_data['customer_zip'] == "" ? "-": $cust_data['customer_zip']; ?></td>
                      <td align="center"><?php echo @$cust_data['shop_job'][$job_num]['project_name'] == "" ? "-" : @$cust_data['shop_job'][$job_num]['project_name'];?></td>
                      <td align="center"><?php echo @$cust_data['shop_job'][$job_num]['address'] == "" ? "-" : @$cust_data['shop_job'][$job_num]['address'];?></td>
                      <td align="center"><?php echo @$cust_data['shop_job'][$job_num]['project_email'] == "" ? "-" : @$cust_data['shop_job'][$job_num]['project_email'];?></td>
                      <td align="center"><?php echo @$cust_data['shop_job'][$job_num]['project_phone'] == "" ? "-" :@$cust_data['shop_job'][$job_num]['project_phone'];?></td>
                      <td align="center"><?php echo @$cust_data['shop_job'][$job_num]['project_city'] == "" ? "-" :@$cust_data['shop_job'][$job_num]['project_city'];?></td>
                      <td align="center"><?php echo @$cust_data['shop_job'][$job_num]['project_state'] == "" ? "-" :@$cust_data['shop_job'][$job_num]['project_state'];?></td>
                      <td align="center"><?php echo @$cust_data['shop_job'][$job_num]['project_zip'] == "" ? "-" :@$cust_data['shop_job'][$job_num]['project_zip'];?></td>
                    </tr>
              <?php
                      }
                    }
                    if(isset($cust_data['elec_job']) && is_array($cust_data['elec_job'])){
                      foreach($cust_data['elec_job'] as $job_num => $val){
            ?>
              <tr bgcolor="#FFF" class="tablehead" height="30px">
                      <td align="center">
                      {{ HTML::link('quote/elec_quote_list',$cust_data['elec_job'][$job_num]['quote_no'] == "" ? "-" : $cust_data['elec_job'][$job_num]['quote_no'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}      
                      </td>
                      <td align="center">
                      {{ HTML::link('job/elec_job_list',$cust_data['elec_job'][$job_num]['job_no'] == "" ? "-": $cust_data['elec_job'][$job_num]['job_no'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}  
                      </td>
                      <td align="center">{{$cust_name == ""? "-" : $cust_name}}</td>
                      <td align="center">{{trim($cust_data['customer_address']) == "" ? "-" : $cust_data['customer_address']}}</td>
                      <td align="center">{{trim($cust_data['customer_address2']) == "" ? "-" : $cust_data['customer_address2']}}</td>
                      <td align="center">{{$cust_data['customer_email']=="" ? "-" : $cust_data['customer_email']}}</td>
                      <td align="center">{{$cust_data['customer_phone'] == "" ? "-": $cust_data['customer_phone']}}</td>
                      <td align="center">{{$cust_data['customer_city'] == "" ? "-": $cust_data['customer_city']}}</td>
                      <td align="center">{{$cust_data['customer_state'] == "" ? "-" : $cust_data['customer_state']}}</td>
                      <td align="center">{{$cust_data['customer_zip'] == "" ? "-": $cust_data['customer_zip']}}</td>
                      <td align="center">{{$cust_data['elec_job'][$job_num]['project_name'] == "" ? "-" : $cust_data['elec_job'][$job_num]['project_name']}}</td>
                      <td align="center">{{$cust_data['elec_job'][$job_num]['address'] == "" ? "-" : $cust_data['elec_job'][$job_num]['address']}}</td>
                      <td align="center">{{$cust_data['elec_job'][$job_num]['project_email'] == "" ? "-" : $cust_data['elec_job'][$job_num]['project_email']}}</td>
                      <td align="center">{{$cust_data['elec_job'][$job_num]['project_phone'] == "" ? "-" :$cust_data['elec_job'][$job_num]['project_phone']}}</td>
                      <td align="center">{{$cust_data['elec_job'][$job_num]['project_city'] == "" ? "-" :$cust_data['elec_job'][$job_num]['project_city']}}</td>
                      <td align="center">{{$cust_data['elec_job'][$job_num]['project_state'] == "" ? "-" :$cust_data['elec_job'][$job_num]['project_state']}}</td>
                      <td align="center">{{$cust_data['elec_job'][$job_num]['project_zip'] == "" ? "-" :$cust_data['elec_job'][$job_num]['project_zip']}}</td>
                    </tr>
             <?php      }
                              }
                 if(isset($cust_data['grassivy_job']) && is_array($cust_data['grassivy_job'])){
                  foreach($cust_data['grassivy_job'] as $job_num => $val){
            ?>
              <tr bgcolor="#FFF" class="tablehead" height="30px">
                      <td align="center">
                      {{ HTML::link('quote/grassivy_quote_list',$cust_data['grassivy_job'][$job_num]['quote_no'] == "" ? "-" : $cust_data['grassivy_job'][$job_num]['quote_no'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}    
                      </td>
                      <td align="center">
                      {{ HTML::link('job/grassivyJobList',$cust_data['grassivy_job'][$job_num]['job_no'] == "" ? "-": $cust_data['grassivy_job'][$job_num]['job_no'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}      
                      </td>
                      <td align="center"><?php echo $cust_name == ""? "-" : $cust_name;?></td>
                      <td align="center"><?php echo trim($cust_data['customer_address']) == "" ? "-" : $cust_data['customer_address']; ?></td>
                      <td align="center"><?php echo trim($cust_data['customer_address2']) == "" ? "-" : $cust_data['customer_address2']; ?></td>
                      <td align="center"><?php echo $cust_data['customer_email']=="" ? "-" : $cust_data['customer_email']; ?></td>
                      <td align="center"><?php echo $cust_data['customer_phone'] == "" ? "-": $cust_data['customer_phone'] ; ?></td>
                      <td align="center"><?php echo $cust_data['customer_city'] == "" ? "-": $cust_data['customer_city']; ?></td>
                      <td align="center"><?php echo $cust_data['customer_state'] == "" ? "-" : $cust_data['customer_state']; ?></td>
                      <td align="center"><?php echo $cust_data['customer_zip'] == "" ? "-": $cust_data['customer_zip']; ?></td>
                      <td align="center"><?php echo $cust_data['grassivy_job'][$job_num]['project_name'] == "" ? "-" : $cust_data['grassivy_job'][$job_num]['project_name'];?></td>
                      <td align="center"><?php echo $cust_data['grassivy_job'][$job_num]['address'] == "" ? "-" : $cust_data['grassivy_job'][$job_num]['address'];?></td>
                      <td align="center"><?php echo $cust_data['grassivy_job'][$job_num]['project_email'] == "" ? "-" : $cust_data['grassivy_job'][$job_num]['project_email'];?></td>
                      <td align="center"><?php echo $cust_data['grassivy_job'][$job_num]['project_phone'] == "" ? "-" :$cust_data['grassivy_job'][$job_num]['project_phone'];?></td>
                      <td align="center"><?php echo $cust_data['grassivy_job'][$job_num]['project_city'] == "" ? "-" :$cust_data['grassivy_job'][$job_num]['project_city'];?></td>
                      <td align="center"><?php echo $cust_data['grassivy_job'][$job_num]['project_state'] == "" ? "-" :$cust_data['grassivy_job'][$job_num]['project_state'];?></td>
                      <td align="center"><?php echo $cust_data['grassivy_job'][$job_num]['project_zip'] == "" ? "-" :$cust_data['grassivy_job'][$job_num]['project_zip'];?></td>
                    </tr>
             <?php      }
                              }
                  
            if(isset($cust_data['special_project_job']) && is_array($cust_data['special_project_job'])){
              foreach($cust_data['special_project_job'] as $job_num => $val){
            ?>
              <tr bgcolor="#FFF" class="tablehead" height="30px">
                      <td align="center">
                      {{ HTML::link('quote/specialproject_quote_list',$cust_data['special_project_job'][$job_num]['quote_no'] == "" ? "-" : $cust_data['special_project_job'][$job_num]['quote_no'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}  
                      </td>
                      <td align="center">
                      {{ HTML::link('job/specialProjectJobList',$cust_data['special_project_job'][$job_num]['job_no'] == "" ? "-": $cust_data['special_project_job'][$job_num]['job_no'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}    
                      </td>
                      <td align="center"><?php echo $cust_name == ""? "-" : $cust_name;?></td>
                      <td align="center"><?php echo trim($cust_data['customer_address']) == "" ? "-" : $cust_data['customer_address']; ?></td>
                      <td align="center"><?php echo trim($cust_data['customer_address2']) == "" ? "-" : $cust_data['customer_address2']; ?></td>
                      <td align="center"><?php echo $cust_data['customer_email']=="" ? "-" : $cust_data['customer_email']; ?></td>
                      <td align="center"><?php echo $cust_data['customer_phone'] == "" ? "-": $cust_data['customer_phone'] ; ?></td>
                      <td align="center"><?php echo $cust_data['customer_city'] == "" ? "-": $cust_data['customer_city']; ?></td>
                      <td align="center"><?php echo $cust_data['customer_state'] == "" ? "-" : $cust_data['customer_state']; ?></td>
                      <td align="center"><?php echo $cust_data['customer_zip'] == "" ? "-": $cust_data['customer_zip']; ?></td>
                      <td align="center"><?php echo $cust_data['special_project_job'][$job_num]['project_name'] == "" ? "-" : $cust_data['special_project_job'][$job_num]['project_name'];?></td>
                      <td align="center"><?php echo $cust_data['special_project_job'][$job_num]['address'] == "" ? "-" : $cust_data['special_project_job'][$job_num]['address'];?></td>
                      <td align="center"><?php echo $cust_data['special_project_job'][$job_num]['project_email'] == "" ? "-" : $cust_data['special_project_job'][$job_num]['project_email'];?></td>
                      <td align="center"><?php echo $cust_data['special_project_job'][$job_num]['project_phone'] == "" ? "-" :$cust_data['special_project_job'][$job_num]['project_phone'];?></td>
                      <td align="center"><?php echo $cust_data['special_project_job'][$job_num]['project_city'] == "" ? "-" :$cust_data['special_project_job'][$job_num]['project_city'];?></td>
                      <td align="center"><?php echo $cust_data['special_project_job'][$job_num]['project_state'] == "" ? "-" :$cust_data['special_project_job'][$job_num]['project_state'];?></td>
                      <td align="center"><?php echo $cust_data['special_project_job'][$job_num]['project_zip'] == "" ? "-" :$cust_data['special_project_job'][$job_num]['project_zip'];?></td>
                    </tr>
             <?php    }
                      }
              ?>
                    <tr>
                    </tr>
                    <?php
                  }
                  }
              }else{
                  foreach ($query_data as $key => $value3) {
                    $row =  (array)$value3;
              ?>
                    <tr bgcolor="#FFF" class="tablehead" height="30px">
                      <td align="center">
                      <?php
                      if(substr($row['quote_no'],0,1)=="F"){?>
                      {{ HTML::link('job/field_service_work_list',trim($row['quote_no']) == "" ? "-" : $row['quote_no'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}  
                    <?php
                      }else if(substr($row['quote_no'],0,1)=="H"){
                    ?>
                      {{ HTML::link('quote/shop_work_quote_list',trim($row['quote_no']) == "" ? "-" : $row['quote_no'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}   
              <?php
              }else if(substr($row['quote_no'],0,1)=="E"){ ?>
                      {{ HTML::link('quote/elec_quote_list',trim($row['quote_no']) == "" ? "-" : $row['quote_no'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}   
                      <?php }
              else if(substr($row['quote_no'],0,1)=="M"){ ?>
                      {{ HTML::link('quote/grassivy_quote_list',trim($row['quote_no']) == "" ? "-" : $row['quote_no'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}  
                    <?php }
              else if(substr($row['quote_no'],0,1)=="J"){ ?>
                      {{ HTML::link('quote/specialproject_quote_list',trim($row['quote_no']) == "" ? "-" : $row['quote_no'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}  
                      <?php }?>
                      </td>
                      <td align="center">
                      <?php
                      if(substr($row['quote_no'],0,1)=="E"){?>
                      {{ HTML::link('job/elec_job_list',trim($row['quote_no']) == "" ? "-" : $row['quote_no'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}} 
              <?php
              }
              elseif(substr($row['quote_no'],0,1)=="M"){?>
                    {{ HTML::link('job/grassivyJobList',trim($row['job_no']) == "" ? "-" : $row['job_no'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}} 
              <?php
              }
              elseif(substr($row['quote_no'],0,1)=="J"){?>
                    {{ HTML::link('job/specialProjectJobList',trim($row['job_no']) == "" ? "-" : $row['job_no'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}} 
              <?php
              }
              else{
              ?>
                 {{ HTML::link('job/service_job_list',trim($row['job_no']) == "" ? "-" : $row['job_no'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}} 
              <?php
              }
                      ?>  
                      </td>
                      <td align="center"><?php echo trim(@$row['customer_name']) == "" ? "-" : @$row['customer_name'];?></td>
                      <td align="center"><?php echo trim(@$row['customer_address'] )== "" ? "-" : @$row['customer_address'];?></td>
                      <td align="center" ><?php echo trim(@$row['customer_address2']) == "" ? "-" : @$row['customer_address2'];?></td>
                      <td align="center"><?php echo trim(@$row['customer_email']) == "" ? "-" : @$row['customer_email'];?></td>
                      <td align="center"><?php echo trim(@$row['customer_phone']) == "" ? "-" :@$row['customer_phone'];?></td>
                      <td align="center"><?php echo trim(@$row['customer_city']) == "" ? "-" : @$row['customer_city'];?></td>
                      <td align="center"><?php echo trim(@$row['customer_state']) == "" ? "-" : @$row['customer_state'];?></td>
                      <td align="center"><?php echo trim(@$row['customer_zip']) == "" ? "-" : @$row['customer_zip'];?></td>
                      <td align="center"><?php echo trim(@$row['project_name']) == "" ? "-" :@$row['project_name'];?></td>
                      <td align="center"><?php echo trim(@$row['address']) == "" ? "-" :@$row['address'];?></td>
                      <td align="center"><?php echo trim(@$row['project_email']) == "" ? "-" :@$row['project_email'];?></td>
                      <td align="center"><?php echo trim(@$row['project_phone']) == "" ? "-" :@$row['project_phone'];?></td>
                      <td align="center"><?php echo trim(@$row['project_city']) == "" ? "-" :@$row['project_city'];?></td>
                      <td align="center"><?php echo trim(@$row['project_state']) == "" ? "-" : @$row['project_state'];?></td>
                      <td align="center"><?php echo trim(@$row['project_zip']) == "" ? "-" :@$row['project_zip'] ;?></td>
                    </tr>
              <?php
                }
              }
              ?>
              </tbody>
          </table>