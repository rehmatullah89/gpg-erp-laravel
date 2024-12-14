@extends("layouts/dashboard_master")
@section('content')
  <section>
    
  </section>
@stop
@section('dashboard_panels')
              <!-- page start-->
              <div class="row">
                <div class="col-sm-12">
              <section class="panel">
              <header class="panel-heading">
                CREATE NEW ACCOUNT 
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b><i>Fill/Select required* fields to create new account and employee's module rights. </i></b>
                </header>
                 @if (isset($errors) && ($errors->any()))
                              <div class="alert alert-danger">
                                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                                  <h4>Error</h4>
                                     <ul>
                                      {{ implode('', $errors->all('<li class="error">:message</li>')) }}
                                     </ul>
                              </div>
                          @endif
                          @if(@Session::has('success'))
                              <div class="alert alert-success alert-block">
                              <button type="button" class="close" data-dismiss="alert">&times;</button>
                                 <h4>Success</h4>
                                  <ul>
                                  {{ Session::get('success') }}
                                 </ul>
                              </div>
                          @endif
              </section>
             <!-- ////////////////////////////////////////// -->
             {{ Form::open(array('before' => 'csrf' ,'url'=>route('account.store'), 'id'=>'frmid1', 'files'=>true, 'method' => 'post')) }}
              <?php 
                $mCount = 0;
              ?>
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                  <tr>
                    <td><div align="center">
                      <TABLE class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <TBODY>
                          <TR>
                            <TD width="26%" align="right" >User Name*:&nbsp;&nbsp;&nbsp;</TD>
                            <TD width="74%" >
                              <div align="left">
                              <INPUT name="uname" class="form-control" id="uname" value="<?php  echo Input::get("uname") ?>" required>
                              e.g. john</div></TD>
                          </TR>
                          <TR>
                            <TD width="26%" align="right">Password*:&nbsp;&nbsp;&nbsp;</TD>
                            <TD width="74%">
                              <div align="left">
                              <INPUT name="pwd" type="password" class="form-control" id="pwd" value="<?php  echo Input::get("pwd") ?>" required>
                              (min 6 characters)      </div></TD>
                          </TR>
                          <TR>
                            <TD width="26%" align="right">Re-enter Password*:&nbsp;&nbsp;&nbsp;</TD>
                            <TD width="74%">
                              <div align="left">
                              <INPUT name="repwd" type="password" class="form-control" id="repwd" value="<?php  echo Input::get("repass") ?>" required>
                              </div></TD>
                          </TR>
                          <tr>
                            <td align="right" valign="top">Set Moduler Permissions*:&nbsp;&nbsp;&nbsp;</td>
                            <td><table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                          <tr>
                            <td bgcolor="#E8E8E8"><table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                          <tr>
                            <td colspan="4" bgcolor="#F5F5F5" align="left"><input type="checkbox" id="ModAll" value="<?php  echo isset($modRow['module_name'])?$modRow['module_name']:'';?>" onchange="javascript:ModCheckAll();" onclick="javascript:ModCheckAll();" /> &nbsp;<strong>SELECT ALL MODULES</strong></td>
                          </tr><tr>
                            <?php  $modQuery = DB::select(DB::raw("select * from gpg_module where parent = 0")); 
                              foreach ($modQuery as $key => $value1) {
                              $modRow = (array)$value1;
                              if (++$mCount%2) { echo "</tr><tr>"; }
                              ?>
                              <td width="1" align="left" valign="top" bgcolor="#FFFFFF"><input type="checkbox" value="<?php  echo $modRow['id'] ?>" id="modPerm_<?php  echo $mCount ?>" name="modPerm[]" onclick="javascript:ModCheckAllSub(<?php  echo $mCount ?>);" onchange="javascript:ModCheckAllSub(<?php  echo $mCount ?>);"/></td>
                              <td width="50%" align="left" valign="top" bgcolor="#FFFFFF">&nbsp;<?php  echo $modRow['module_name']; 
                              $modSubQuery = DB::select(DB::raw("select * from gpg_module where parent = '".$modRow['id']."'"));  
                              $mmCount = 0;
                              foreach ($modSubQuery as $key => $value2) {
                                $modSubRow = (array)$value2;
                                ++$mmCount;
                              ?><br><input type="checkbox" value="<?php  echo $modSubRow['id'] ?>" id="modSubPerm_<?php  echo $mCount ?>_<?php  echo $mmCount ?>" name="modPerm[]"/>&nbsp;<?php  echo $modSubRow['module_name']; 
                              $modSubSubQuery = DB::select(DB::raw("select * from gpg_module where parent = '".$modSubRow['id']."'"));  
                              foreach ($modSubSubQuery as $key => $value3) {
                                $modSubSubRow = (array)$value3;
                                ++$mmCount;
                              ?><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" value="<?php  echo $modSubSubRow['id'] ?>" id="modSubPerm_<?php  echo $mCount ?>_<?php  echo $mmCount ?>" name="modPerm[]"/>&nbsp;<?php  echo $modSubSubRow['module_name']; 
                              }
                            } ?></td><?php  
                          } ?>
                          <input type="hidden" name="modCount" id="modCount" value="<?php  echo $mCount;?>">
                          </tr>
                        </table></td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <TR>
                  <TD width="26%" align="right"></TD>
                  <TD width="74%"><div align="left" style="border:1px solid #eee;">
                    <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                      <tr>
                        <?php
                          $columns = 5;
                        ?>
                      <td style="background:#F5F5F5;"><input type="checkbox" name="emp_select_all" onclick="checkAllEmployees(this)" value="select_emp_all"  /> Select All</td>
                      <td align="center" style="background:#F5F5F5; padding:5px;" colspan="<?php echo $columns-1?>"><strong>Assigned Employees</strong></td>
                      <?php
                        $emp_result = DB::select(DB::raw("SELECT * FROM gpg_employee WHERE  concat(',',frontend,',') like '%,sales,%' ORDER BY NAME ASC "));
                        $counter = -1;      
                        foreach ($emp_result as $key => $value4) {
                          $emp_row = (array)$value4;
                          $counter++;
                          if($counter%$columns==0){
                            echo "</tr><tr>";
                          }
                        ?>
                      <td id="emp_check">
                        <input type="checkbox" name="allow_emps[]" value="<?php echo $emp_row['id']?>" id="allowed_emp_<?php echo $emp_row['id'];?>" /><?php echo $emp_row['name']; ?>
                      </td>
                      <?php }?>
                    </tr>
                  </table>
                </div></TD>
              </TR>
              <TR>
                <TD width="26%" align="right">First Name: &nbsp;&nbsp;</TD>
                <TD width="74%">
                  <div align="left">
                  <INPUT name="fname" class="form-control" id="fname" value="<?php  echo Input::get("fname") ?>">      
                </div></TD>
              </TR>
              <TR>
                <TD width="26%" align="right">Last Name:&nbsp;&nbsp;&nbsp; </TD>
                <TD width="74%">
                  <div align="left">
                  <INPUT name="lname" class="form-control" id="lname" value="<?php  echo Input::get("lname") ?>">      
                  </div></TD>
              </TR>
              <TR>
                <TD width="26%" align="right">Email*:&nbsp;&nbsp;&nbsp;</TD>
                <TD width="74%">
                  <div align="left">
                  <INPUT name="email" class="form-control" id="email" value="<?php  echo Input::get("email") ?>" size="35" required>
                  e.g. somebody@somedomain.com</div></TD>
              </TR>
              <TR>
                <TD width="26%" align="right">Password:&nbsp;&nbsp;&nbsp;</TD>
                <TD width="74%">
                  <div align="left">
                  <INPUT type="password" class="form-control" name="email_pwd" id="email_pwd" value="" size="35"></div></TD>
              </TR>
              <TR>
                <TD width="26%" align="right">Country:&nbsp;&nbsp;&nbsp;</TD>
                <TD width="74%">
                <div align="left">
                  {{Form::select('country',$countries, null, ['id' => 'country', 'class'=>'form-control m-bot15'])}}
                </div></TD>
              </TR>
              <TR>
                <TD width="26%" align="right">Phone#:&nbsp;&nbsp;&nbsp;</TD>
                <TD width="74%">
                  <div align="left">
                  <INPUT name="phone" class="form-control" id="phone" value="<?php  echo Input::get("phone") ?>"> 
                  </div></TD>
              </TR>
              <TR>
                <TD colSpan=2><DIV align=center><FONT size=4>&nbsp;</FONT> </DIV></TD>
              </TR>
              <TR>
                <TD colSpan=2><DIV align=center>
                <INPUT type="submit" class="btn btn-success" value="Create Account" name="Submit">
                </DIV></TD>
              </TR>    
            </TBODY>
          </TABLE>
            </div></td>
            </tr>
         </table>                   
              </section>
              </div>
              </div>
              {{ Form::close() }}
              </section>
              </div>
              </div>
              <!-- page end-->
    <script type="text/javascript">
      $('.default-date-picker').datepicker({
          format: 'yyyy-mm-dd',
          minDate: new Date()
      });
      function checkAllEmployees(obj){
        var emp_check = document.getElementsByName("allow_emps[]");
        if(obj.checked == true){
          for(var i=0; i<emp_check.length; i++){
            emp_check[i].checked = true;
          }
        }else{
          for(var i=0; i<emp_check.length; i++){
            emp_check[i].checked = false;
          }
        }
      }
      function ModCheckAll() {
        for (i=0; i<document.getElementById('modCount').value; i++) {
          if (document.getElementById('ModAll').checked) document.getElementById('modPerm_' + (i+1)).checked = true;
          else document.getElementById('modPerm_' + (i+1)).checked = false;
          ModCheckAllSub((i+1))
        }
      }
      function ModCheckAllSub(subId) {
        var count = 1;
        while (1) { 
          if (!document.getElementById('modSubPerm_' + subId + '_' + count)) break;
          if (document.getElementById('modPerm_' + subId).checked) document.getElementById('modSubPerm_' + subId + '_' + count).checked = true;
          else document.getElementById('modSubPerm_' + subId + '_' + count).checked = false; 
          count++;
        }
      }
    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>
@stop