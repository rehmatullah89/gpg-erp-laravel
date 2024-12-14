    <?php
                                    $m = Input::get('m');
                                    if(empty($m))
                                      $m = date('m');
                                    $y = Input::get('y');
                                    if(empty($y))
                                      $y = date('Y');
                                    $prevY = $y;
                                    $nextY = $y;
                                    if ($m<=1) { $prevM=12; $prevY--; }
                                      else $prevM = $m-1;
                                    if ($m>=12) { $nextM=1; $nextY++; }
                                    else $nextM = $m+1;                                   
                                    $currentDate = $m.'/01/'.$y;
                                    $check_date= Input::get("check_date");
                                    $curMonth = date('m',strtotime($currentDate));
                                    $curMonthCap = date('M',strtotime($currentDate));
                                    $curYear = date('Y',strtotime($currentDate));
                                    $preYear = $curYear-1;
                                    
                  for($i=1; $i<=$curMonth; $i++ ){
                    $SDateThisMonth = date('01-'.$i."-".$curYear);
                    $SDateThisMonthDB = date('Y-m-d',strtotime($SDateThisMonth));
                    $EDateThisMonth=date('t-m-Y',strtotime($SDateThisMonth));
                    $EDateThisMonthDB = date('Y-m-d',strtotime($EDateThisMonth));
                    $curMonthCap = date('M',strtotime($EDateThisMonth));
                    $queryPart1='';
                    if($check_date!=1){
                      $queryPartInvoice = "and gpg_job_invoice_info.invoice_date >= '$SDateThisMonthDB' and gpg_job_invoice_info.invoice_date <= '$EDateThisMonthDB' ";
                      $queryPart1="  and created_on >= '$SDateThisMonthDB 00:00:00' and created_on <= '$EDateThisMonthDB 23:59:59' ";
                      $queryPartRental="  and created_on >= '$SDateThisMonthDB 00:00:00' and created_on <= '$EDateThisMonthDB 23:59:59' ";
                    }else{
                        $queryPartInvoice = "and gpg_job_invoice_info.invoice_date >= '$SDateThisMonthDB' and gpg_job_invoice_info.invoice_date <= '$EDateThisMonthDB' ";
                        $queryPart1=" AND (select gpg_job_invoice_info.gpg_job_id from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id and gpg_job_invoice_info.invoice_date >= '$SDateThisMonthDB' and gpg_job_invoice_info.invoice_date <= '$EDateThisMonthDB' limit 0,1)  ";
                        $queryPartRental="  and invoice_date >= '$SDateThisMonthDB 00:00:00' and invoice_date <= '$EDateThisMonthDB 23:59:59' ";
                    }

                ?>
                <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                  <thead class="cf">
                    <tr>
                      <td width="4%" height="30"   align="center" bgcolor="#EEEEEE"><strong><?php echo $curYear?></strong></td>
                      <td width="20%"  align="center" bgcolor="#EEEEEE"><strong>Service Jobs</strong></td>
                      <td width="18%"  align="center" bgcolor="#EEEEEE"><strong>Rental Jobs</strong></td>        
                      <td width="20%"  align="center" bgcolor="#EEEEEE"><strong>Electrical Jobs</strong></td>
                      <td width="19%"  align="center" bgcolor="#EEEEEE"><strong>Grassivy Jobs</strong></td>
                      <td width="19%"  align="center" bgcolor="#EEEEEE"><strong>Special Project Jobs</strong></td>
                    </tr>
                  </thead>
                  <tbody>
                   <tr>
                     <td align="center" bgcolor="#FFFFFF"  ><strong><?php echo $curMonthCap?></strong></td>
                     <td  bgcolor="#FFFFFF"  >
                     <table border="0" width="100%" cellspacing="2" cellpadding="2">
                       <tr>
                       <td style="text-align:left;">Added</td>
                       <td align="center" ><?php $qry = DB::select(DB::raw("select count(id) as t_id from gpg_job where GPG_job_type_id='4' and job_num not like 'RNT%' $queryPart1"));
                          if (!empty($qry) && isset($qry[0]->t_id)){
                            echo HTML::link('job/service_job_list/', $qry[0]->t_id , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('job/service_job_list/',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                       ?></td>
                       </tr>
                       <tr>
                       <td style="text-align:left;">Completed</td>
                       <td align="center"><?php $qry = DB::select(DB::raw("select count(id) as t_id from gpg_job where GPG_job_type_id='4' and job_num not like 'RNT%'and complete='1' $queryPart1"));
                           if (!empty($qry) && isset($qry[0]->t_id)){
                             echo HTML::link('job/service_job_list/', $qry[0]->t_id , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('job/service_job_list/',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                       ?></td>
                       </tr>
                       <tr>
                       <td style="text-align:left;">Ending Balance</td>
                       <td align="center"><?php $qry = DB::select(DB::raw("select count(id) as t_id from gpg_job where GPG_job_type_id='4' and job_num not like 'RNT%'and ifnull(complete,0)<>'1' $queryPart1"));
                           if (!empty($qry) && isset($qry[0]->t_id)){
                             echo HTML::link('job/service_job_list/', $qry[0]->t_id , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('job/service_job_list/',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                       ?></td>
                       </tr>
                       <tr>
                       <td style="text-align:left;">Invoice Amount  Billed</td>
                       <td align="center"><?php $qry = DB::select(DB::raw("select sum((select sum(gpg_job_invoice_info.invoice_amount)  from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id=gpg_job.id $queryPartInvoice)) from gpg_job where GPG_job_type_id='4' and job_num not like 'RNT%'$queryPart1"));
                           if (!empty($qry) && isset($qry[0]->t_id)){
                             echo HTML::link('job/service_job_list/', number_format($qry[0]->t_id,2) , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('job/service_job_list/',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                       ?></td>
                       </tr>
                       <tr>
                       <td style="text-align:left;">Completed Service Jobs invoiced</td>
                       <td align="center"><?php $qry = DB::select(DB::raw("select count(id) as t_id from gpg_job where GPG_job_type_id='4' and job_num not like 'RNT%'AND complete = '1' AND (select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id limit 0,1)  $queryPart1"));
                           if (!empty($qry) && isset($qry[0]->t_id)){
                             echo HTML::link('job/service_job_list/', $qry[0]->t_id , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('job/service_job_list/',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                       ?></td>
                       </tr>
                       <tr>
                       <td style="text-align:left;">Completed Service Jobs not invoiced</td>
                         <td align="center"><?php $qry = DB::select(DB::raw("select count(id) as t_id from gpg_job where GPG_job_type_id='4' and job_num not like 'RNT%'AND complete = '1' AND if((select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id limit 0,1)>0,0,1) $queryPart1"));
                             if (!empty($qry) && isset($qry[0]->t_id)){
                             echo HTML::link('job/service_job_list/', $qry[0]->t_id , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('job/service_job_list/',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                         ?></td>
                       </tr>
                       </table>
                     </td>
                     <td  bgcolor="#FFFFFF"  >
                     <table border="0" width="100%" cellspacing="2" cellpadding="2">
                       <tr>
                       <td style="text-align:left;">Added</td>
                       <td align="center" ><?php $qry = DB::select(DB::raw("select count(id) as t_id from gpg_job where GPG_job_type_id='4' and job_num like 'RNT%' $queryPart1"));
                           if (!empty($qry) && isset($qry[0]->t_id)){
                            echo HTML::link('invoice', $qry[0]->t_id , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('invoice',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                       ?></td>
                       </tr>
                       <tr>
                       <td style="text-align:left;">Completed</td>
                       <td align="center"><?php $qry = DB::select(DB::raw("select count(id) as t_id from gpg_job where GPG_job_type_id='4' and job_num like 'RNT%'and rental_status = '4' $queryPart1"));
                           if (!empty($qry) && isset($qry[0]->t_id)){
                             echo HTML::link('invoice', $qry[0]->t_id , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('invoice',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                       ?></td>
                       </tr>
                       <tr>
                       <td style="text-align:left;">Ending Balance</td>
                       <td align="center"><?php $qry = DB::select(DB::raw("select count(id) as t_id from gpg_job where GPG_job_type_id='4' and job_num like 'RNT%' AND ifnull(rental_status,0) not in ('4','5') $queryPart1"));
                         if (!empty($qry) && isset($qry[0]->t_id)){
                             echo HTML::link('invoice', $qry[0]->t_id , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('invoice',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                       ?></td>
                       </tr>
                       <tr>
                       <td style="text-align:left;">Invoice Amount  Billed</td>
                       <td align="center"><?php $qry = DB::select(DB::raw("select sum((select gpg_job_invoice_info.invoice_amount  from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id=gpg_job.id limit 0,1)) as t_id from gpg_job where GPG_job_type_id='4' and job_num like 'RNT%' $queryPart1"));
                           if (!empty($qry) && isset($qry[0]->t_id)){
                             echo HTML::link('invoice', number_format($qry[0]->t_id,2) , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('invoice',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                       ?></td>
                       </tr>
                       <tr>
                       <td style="text-align:left;">Completed Rental Jobs invoiced</td>
                       <td align="center"><?php $qry = DB::select(DB::raw("select count(id) as t_id from gpg_job where GPG_job_type_id='4' and job_num like 'RNT%' AND rental_status in ('4','5') AND (select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id limit 0,1)  $queryPart1"));
                           if (!empty($qry) && isset($qry[0]->t_id)){
                             echo HTML::link('invoice', $qry[0]->t_id , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('invoice',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                       ?></td>
                       </tr>
                       <tr>
                       <td style="text-align:left;">Completed Rental Jobs not invoiced</td>
                         <td align="center"><?php $qry = DB::select(DB::raw("select count(id) as t_id from gpg_job where GPG_job_type_id='4' and job_num like 'RNT%' AND rental_status = '4' AND if((select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id limit 0,1)>0,0,1) $queryPart1"));
                               if (!empty($qry) && isset($qry[0]->t_id)){
                             echo HTML::link('invoice', $qry[0]->t_id , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('invoice',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                         ?></td>
                       </tr>
                       </table>
                     </td>
                    <td  bgcolor="#FFFFFF"  >
                     <table border="0" width="100%" cellspacing="2" cellpadding="2">
                       <tr>
                       <td style="text-align:left;">Added</td>
                       <td align="center" ><?php $qry = DB::select(DB::raw("select count(id) as t_id from gpg_job where job_num like 'GPG%' AND GPG_job_type_id=5 $queryPart1"));
                           if (!empty($qry) && isset($qry[0]->t_id)){
                             echo HTML::link('job/elec_job_list', $qry[0]->t_id , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('job/elec_job_list',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                       ?></td>
                       </tr>
                       <tr>
                       <td style="text-align:left;">Completed</td>
                       <td align="center"><?php $qry = DB::select(DB::raw("select count(id) as t_id from gpg_job where job_num like 'GPG%' AND GPG_job_type_id='5' and complete='1' $queryPart1"));
                           if (!empty($qry) && isset($qry[0]->t_id)){
                            echo HTML::link('job/elec_job_list', $qry[0]->t_id , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('job/elec_job_list',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                       ?></td>
                       </tr>
                        <tr>
                       <td style="text-align:left;">Ending Balance</td>
                       <td align="center"><?php $qry = DB::select(DB::raw("select count(id) as t_id from gpg_job where job_num like 'GPG%' AND GPG_job_type_id='5' and ifnull(complete,0)<>'1' $queryPart1"));
                           if (!empty($qry) && isset($qry[0]->t_id)){
                            echo HTML::link('job/elec_job_list', $qry[0]->t_id , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('job/elec_job_list',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                       ?></td>
                       </tr>
                       <tr>
                       <td style="text-align:left;">Invoice Amount  Billed</td>
                       <td align="center"><?php $qry = DB::select(DB::raw("select sum((select sum(gpg_job_invoice_info.invoice_amount)  from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id=gpg_job.id  $queryPartInvoice)) as t_id from gpg_job where job_num like 'GPG%' AND GPG_job_type_id='5' $queryPart1"));
                         if (!empty($qry) && isset($qry[0]->t_id)){
                            echo HTML::link('job/elec_job_list', number_format($qry[0]->t_id,2) , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('job/elec_job_list',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                       ?></td>
                       </tr>
                       <tr>
                       <td> Completed Electrical Jobs invoiced</td>
                       <td align="center"><?php $qry = DB::select(DB::raw("select count(id) as t_id from gpg_job where job_num like 'GPG%' AND  GPG_job_type_id='5' AND complete = '1' AND (select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id limit 0,1) $queryPart1"));
                           if (!empty($qry) && isset($qry[0]->t_id)){
                            echo HTML::link('job/elec_job_list', $qry[0]->t_id , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('job/elec_job_list',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                       ?></td>
                       </tr>
                       <tr>
                       <td style="text-align:left;">Completed Electrical Jobs not invoiced</td>
                       <td align="center"><?php $qry = DB::select(DB::raw("select count(id) as t_id from gpg_job where job_num like 'GPG%' AND GPG_job_type_id='5' AND complete = '1' AND if((select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id limit 0,1)>0,0,1) $queryPart1"));
                           if (!empty($qry) && isset($qry[0]->t_id)){
                            echo HTML::link('job/elec_job_list', $qry[0]->t_id , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('job/elec_job_list',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                       ?></td>
                       </tr>
                       </table>
                       </td>
                       <td  bgcolor="#FFFFFF"  >
                        <table border="0" width="100%" cellspacing="2" cellpadding="2">
                       <tr>
                       <td style="text-align:left;">Added</td>
                       <td align="center" ><?php $qry = DB::select(DB::raw("select count(id) as t_id from gpg_job where job_num like 'IG%' AND GPG_job_type_id=5 $queryPart1"));
                           if (!empty($qry) && isset($qry[0]->t_id)){
                             echo HTML::link('job/grassivyJobList', $qry[0]->t_id , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('job/grassivyJobList',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                       ?></td>
                       </tr>
                       <tr>
                       <td style="text-align:left;">Completed</td>
                       <td align="center"><?php $qry = DB::select(DB::raw("select count(id) as t_id from gpg_job where job_num like 'IG%' AND GPG_job_type_id='5' and complete='1' $queryPart1"));
                           if (!empty($qry) && isset($qry[0]->t_id)){
                             echo HTML::link('job/grassivyJobList', $qry[0]->t_id , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('job/grassivyJobList',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                       ?></td>
                       </tr>
                       <tr>
                       <td style="text-align:left;">Ending Balance</td>
                       <td align="center"><?php $qry = DB::select(DB::raw("select count(id) as t_id from gpg_job where job_num like 'IG%' AND GPG_job_type_id='5' and ifnull(complete,0)<>'1' $queryPart1"));
                           if (!empty($qry) && isset($qry[0]->t_id)){
                             echo HTML::link('job/grassivyJobList', $qry[0]->t_id , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('job/grassivyJobList',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                       ?></td>
                       </tr>
                       <tr>
                       <td style="text-align:left;">Invoice Amount  Billed</td>
                       <td align="center"><?php $qry = DB::select(DB::raw("select sum((select sum(gpg_job_invoice_info.invoice_amount)  from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id=gpg_job.id  $queryPartInvoice)) as t_id from gpg_job where job_num like 'IG%' AND GPG_job_type_id='5' $queryPart1"));
                           if (!empty($qry) && isset($qry[0]->t_id)){
                             echo HTML::link('job/grassivyJobList', number_format($qry[0]->t_id,2) , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('job/grassivyJobList',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                       ?></td>
                       </tr>
                       <tr>
                       <td> Completed Electrical Jobs invoiced</td>
                       <td align="center"><?php $qry = DB::select(DB::raw("select count(id) as t_id from gpg_job where job_num like 'IG%' AND  GPG_job_type_id='5' AND complete = '1' AND (select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id limit 0,1) $queryPart1"));
                           if (!empty($qry) && isset($qry[0]->t_id)){
                             echo HTML::link('job/grassivyJobList', $qry[0]->t_id , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('job/grassivyJobList',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                       ?></td>
                       </tr>
                       <tr>
                       <td style="text-align:left;">Completed Electrical Jobs not invoiced</td>
                         <td align="center"><?php $qry = DB::select(DB::raw("select count(id) as t_id from gpg_job where  job_num like 'IG%' AND GPG_job_type_id='5' AND complete = '1' AND if((select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id limit 0,1)>0,0,1) $queryPart1"));
                             if (!empty($qry) && isset($qry[0]->t_id)){
                             echo HTML::link('job/grassivyJobList', $qry[0]->t_id , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('job/grassivyJobList',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                         ?></td>
                       </tr>
                       </table>
                        </td>
                        <td  bgcolor="#FFFFFF"  >
                        <table border="0" width="100%" cellspacing="2" cellpadding="2">
                       <tr>
                       <td style="text-align:left;">Added</td>
                       <td align="center" ><?php $qry = DB::select(DB::raw("select count(id) as t_id from gpg_job where job_num like 'LK%' AND GPG_job_type_id=5 $queryPart1"));
                           if (!empty($qry) && isset($qry[0]->t_id)){
                             echo HTML::link('job/specialProjectJobList', $qry[0]->t_id , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('job/specialProjectJobList',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                       ?></td>
                       </tr>
                       <tr>
                       <td style="text-align:left;">Completed</td>
                       <td align="center"><?php $qry = DB::select(DB::raw("select count(id) as t_id from gpg_job where job_num like 'LK%' AND GPG_job_type_id='5' and complete='1' $queryPart1"));
                           if (!empty($qry) && isset($qry[0]->t_id)){
                             echo HTML::link('job/specialProjectJobList', $qry[0]->t_id , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('job/specialProjectJobList',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                       ?></td>
                       </tr>
                        <tr>
                       <td style="text-align:left;">Ending Balance</td>
                       <td align="center"><?php $qry = DB::select(DB::raw("select count(id) as t_id from gpg_job where job_num like 'LK%' AND GPG_job_type_id='5' and ifnull(complete,0)<>'1' $queryPart1"));
                           if (!empty($qry) && isset($qry[0]->t_id)){
                             echo HTML::link('job/specialProjectJobList', $qry[0]->t_id , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('job/specialProjectJobList',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                       ?></td>
                       </tr>
                       <tr>
                       <td style="text-align:left;">Invoice Amount  Billed</td>
                       <td align="center"><?php $qry = DB::select(DB::raw("select sum((select sum(gpg_job_invoice_info.invoice_amount)  from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id=gpg_job.id  $queryPartInvoice)) as t_id from gpg_job where job_num like 'LK%' AND GPG_job_type_id='5' $queryPart1"));
                           if (!empty($qry) && isset($qry[0]->t_id)){
                             echo HTML::link('job/specialProjectJobList', number_format($qry[0]->t_id,2) , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('job/specialProjectJobList',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                       ?></td>
                       </tr>
                       <tr>
                       <td> Completed Electrical Jobs invoiced</td>
                       <td align="center"><?php $qry = DB::select(DB::raw("select count(id) as t_id from gpg_job where job_num like 'LK%' AND  GPG_job_type_id='5' AND complete = '1' AND (select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id limit 0,1) $queryPart1"));
                           if (!empty($qry) && isset($qry[0]->t_id)){
                             echo HTML::link('job/specialProjectJobList', $qry[0]->t_id , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('job/specialProjectJobList',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                       ?></td>
                       </tr>
                       <tr>
                       <td style="text-align:left;">Completed Electrical Jobs not invoiced</td>
                        <td align="center"><?php $qry = DB::select(DB::raw("select count(id) as t_id from gpg_job where  job_num like 'LK%' AND GPG_job_type_id='5' AND complete = '1' AND if((select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id limit 0,1)>0,0,1) $queryPart1"));
                           if (!empty($qry) && isset($qry[0]->t_id)){
                             echo HTML::link('job/specialProjectJobList', $qry[0]->t_id , array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                          }else
                            echo HTML::link('job/specialProjectJobList',0, array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                        ?></td>
                       </tr>
                       </table>
                     </td>
                  </tr>
                  </tbody>
                </table>
                <?php }?>