             <table class="table table-bordered table-striped table-condensed cf" >
              <thead class="cf">
                <tr>
                  <th>#id</th>
                  <th>Customer Name </th>
                  <th>Electrial</th>
                  <th>Grassivy</th>
                  <th>Special Project</th>
                  <th>Service</th>
                  <th>Rental</th>
                  <th>Shop</th>
                  <th>Sales Total</th>
               </tr>
              <tbody class="cf">
              <?php 
                $colcount=0;
                foreach ($query_data as $key => $val){
                  $gpg_inv_amt = 0;
                  $sh_inv_amt =0;
                  $rnt_inv_amt =0;
                  $ser_inv_amt =0;
                  $jobCount = 0;
                ?>
                <tr  bgcolor="#FFFFFF">
                  <td align="center" ><strong>{{$key}}</strong></td>
                  <td height="30" nowrap="nowrap" >{{$val['name']}}</td>
                  <?php 
                    $str= '';
                    $jobCount =   count(@$val['gpg_job']);
                    if (isset($val['gpg_job']))
                    foreach ($val['gpg_job'] as $job_key => $job_val){ 
                      $invData = explode('~~',$job_val);
                      $gpg_inv_amt = $gpg_inv_amt + $invData[0];
                      $str .= '&#13; Job Num:'.$job_key.'&#13; Invoicev #:'.$invData[1].'&#13; Inv Amt Net:'.'$'.number_format((double)$job_val,2);  
                    }
                  ?>
                  <td align="center" title="{{$str}}">{{ HTML::link('job/elec_job_list',($jobCount >=1)?number_format($gpg_inv_amt,2):'-', array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                   <?php 
                    $str= '';
                    $gpg_inv_amt = 0;
                    $jobCount =   count(@$val['gpg_grassivy_job']);
                    if (isset($val['gpg_grassivy_job']))
                    foreach ($val['gpg_grassivy_job'] as $job_key => $job_val){ 
                      $invData = explode('~~',$job_val);
                      $gpg_inv_amt = $gpg_inv_amt + $invData[0];
                      $str .= '&#13; Job Num:'.$job_key.'&#13; Invoicev #:'.$invData[1].'&#13; Inv Amt Net:'.'$'.number_format((double)$job_val,2);  
                    }
                  ?>
                  <td align="center" title="{{$str}}">{{ HTML::link('job/grassivyJobList',($jobCount >=1)?number_format($gpg_inv_amt,2):'-', array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                  <?php 
                    $str= '';
                    $gpg_inv_amt = 0;
                    $jobCount =   count(@$val['gpg_special_project_job']);
                    if (isset($val['gpg_special_project_job']))
                    foreach ($val['gpg_special_project_job'] as $job_key => $job_val){ 
                      $invData = explode('~~',$job_val);
                      $gpg_inv_amt = $gpg_inv_amt + $invData[0];
                      $str .= '&#13; Job Num:'.$job_key.'&#13; Invoicev #:'.$invData[1].'&#13; Inv Amt Net:'.'$'.number_format((double)$job_val,2);  
                    }
                  ?>
                  <td align="center" title="{{$str}}">{{ HTML::link('job/specialProjectJobList',($jobCount >=1)?number_format($gpg_inv_amt,2):'-', array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                   <?php 
                    $str= '';
                    $ser_inv_amt = 0;
                    $jobCount =   count(@$val['service_job']);
                    if (isset($val['service_job']))
                    foreach ($val['service_job'] as $job_key => $job_val){ 
                      $invData = explode('~~',$job_val);
                      $ser_inv_amt = $ser_inv_amt + $invData[0];
                      $str .= '&#13; Job Num:'.$job_key.'&#13; Invoicev #:'.$invData[1].'&#13; Inv Amt Net:'.'$'.number_format((double)$job_val,2);  
                    }
                  ?>
                  <td align="center" title="{{$str}}">{{ HTML::link('job/service_job_list',($jobCount >=1)?number_format($ser_inv_amt,2):'-', array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                  <?php 
                    $str= '';
                    $rnt_inv_amt = 0;
                    $jobCount =   count(@$val['rnt_job']);
                    if (isset($val['rnt_job']))
                    foreach ($val['rnt_job'] as $job_key => $job_val){ 
                      $invData = explode('~~',$job_val);
                      $rnt_inv_amt = $rnt_inv_amt + $invData[0];
                      $str .= '&#13; Job Num:'.$job_key.'&#13; Invoicev #:'.$invData[1].'&#13; Inv Amt Net:'.'$'.number_format((double)$job_val,2);  
                    }
                  ?>
                  <td align="center" title="{{$str}}">{{ HTML::link('invoice',($jobCount >=1)?number_format($rnt_inv_amt,2):'-', array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                   <?php 
                    $str= '';
                    $sh_inv_amt = 0;
                    $jobCount =   count(@$val['sh_job']);
                    if (isset($val['sh_job']))
                    foreach ($val['sh_job'] as $job_key => $job_val){ 
                      $invData = explode('~~',$job_val);
                      $sh_inv_amt = $sh_inv_amt + $invData[0];
                      $str .= '&#13; Job Num:'.$job_key.'&#13; Invoicev #:'.$invData[1].'&#13; Inv Amt Net:'.'$'.number_format((double)$job_val,2);  
                    }
                  ?>
                  <td align="center" title="{{$str}}">{{ HTML::link('job/shopWorkJobList',($jobCount >=1)?number_format($sh_inv_amt,2):'-', array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                  <td colspan="6"><?php echo '<strong>'.'$'.number_format($sh_inv_amt+$ser_inv_amt+$gpg_inv_amt+$rnt_inv_amt,2).'</strong>'; ?></td>
                </tr>
                <?php }?>
            </tbody>
          </table>