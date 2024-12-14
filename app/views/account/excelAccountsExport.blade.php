<table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <thead class="cf">
                          <tr>
                            <th style="text-align:center;">#</th>
                            <th style="text-align:center;">Full Name</th>
                            <th style="text-align:center;">Email</th>
                            <th style="text-align:center;">Country</th>
                            <th style="text-align:center;">Login</th>
                            <th style="text-align:center;">Admin Type</th>
                            <th style="text-align:center;">Allowed Employees</th>
                          </tr>
                        </thead>
                      <tbody>
                        @foreach($query_data as $row)
                          <tr>
                          <td height="30" bgcolor="#FFFFFF">{{$row['ad_id']}}</td>
                          <td bgcolor="#FFFFFF">{{$row['fname']." ".$row['lname']}}</td>
                          <td bgcolor="#FFFFFF">{{$row['email']}}</td>
                          <td bgcolor="#FFFFFF">{{@DB::table('gpg_country')->where('country_id','=',$row['country_id'])->pluck('country')}}</td>
                          <td bgcolor="#FFFFFF">{{$row['uname']}}</td>
                          <td bgcolor="#FFFFFF" align="center"><?php
                          $perVal = DB::select(DB::raw("select count(a.id) as mod_count, count(b.GPG_module_id) as perm_count from gpg_module a left join gpg_mod_perm b on (a.id = b.GPG_module_id and b.GPG_ad_acc_id = '".$row['ad_id']."')"));
                          if($perVal[0]->mod_count==$perVal[0]->perm_count || $row['uname']=="admin") 
                            echo "<strong>Super Admin (All Rights)</strong>";
                          else {
                            $modsPerm = DB::select(DB::raw("select a.module_name from gpg_module a , gpg_mod_perm b where a.id = b.GPG_module_id and b.GPG_ad_acc_id = '".$row['ad_id']."'"));
                            foreach ($modsPerm as $key => $modsRows) {
                              echo "<span class=\"smallGray\">".$modsRows->module_name.", </span>";      
                            }
                          } ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php
                          $emp_row = DB::select(DB::raw("select * from gpg_ad_acc where ad_id = '".$row['ad_id']."'"));
                          $allowed_emps = explode(",",$emp_row[0]->allowed_employees);
                          for($i=0; $i<count($allowed_emps)-1; $i++){
                            $allowed_emp_name = DB::table('gpg_employee')->where('id','=',$allowed_emps[$i])->pluck('NAME');
                            echo '<span class="smallGray">'.$allowed_emp_name.', </span>';
                          }?>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                  </table>