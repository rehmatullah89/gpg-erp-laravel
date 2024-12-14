@extends("layouts/dashboard_master")
@section('content')
 
@stop
@section('dashboard_panels')
    <!-- page start--> 
<?php
    $action = "add";  
    $breadCrumb = "ADD A NEW VENDOR";
    if(isset($GpgVendorData) && !empty($GpgVendorData)){
         $action = "update";
         $breadCrumb = "UPDATE VENDOR";
    }
?>
    
    <div class="row">
      <div class="col-sm-12">
    <section class="panel">
    <header class="panel-heading">
     
        {{$breadCrumb}}
      
    </header>
    <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
    <section class="panel">
        <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
            <i>  Vendor Information: </i>
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
        @if($action == 'add')
            {{ Form::open(array('before' => 'csrf' ,'url'=>route('vendors.create'), 'id'=>'customerForm', 'files'=>true, 'method' => 'post')) }}
        @elseif($action == 'update')
            {{ Form::open(array('before' => 'csrf' ,'url'=>URL::route('vendors.update', array('id'=>$GpgVendorData->id)), 'id'=>'customerForm', 'files'=>true, 'method' => 'put')) }}
            <input type="hidden" name="old_name" value="{{@$GpgVendorData->name}}">
        @endif
        
          <section id="no-more-tables" style="padding:10px;">
          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
            <tbody>
                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Name*:</th>
                      <td>
                          {{ Form::text('name',@$GpgVendorData->name, array('class' => 'form-control', 'id' => 'name', 'required','onkeyUp'=>'populateLogin(this.value)')) }}
                      </td>
                  </tr>
                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Email Address:</th>
                      <td>
                          {{ Form::text('email_add',@$GpgVendorData->email_add, array('class' => 'form-control', 'id' => 'email_add', '')) }}
                      </td>
                  </tr>
                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Status:</th>
                      <td style="padding-left:2px;">
                          {{Form::select('status', array('A' => 'Active','B' => 'Blocked'), @$GpgVendorData->status, ['id' => 'status', 'class'=>'form-control l-bot6'])}}
                      </td>
                  </tr>

                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Type: <?php if(isset($_POST['type'])){ echo $_POST['type'];}?></th>
                      <td style="padding-left:2px;">
                          {{Form::select('venType', array('' => 'Vendor','S' => 'Subcontractor'),  @$GpgVendorData->ven_type, ['id' => 'venType','onchange' => 'toggleLogin(this);', 'class'=>'form-control l-bot6'])}}
                      </td>
                  </tr>
                  <tr id="loginFrm" style="display: none">
                      <th></th>
                      <td>
                          <table>
                              <tr>
                          <th style="text-align:center; vertical-align:middle; font-weight: bold;">Login Name:</th>
                          <td>
                              {{ Form::text('login',@$GpgVendorData->login, array('class' => 'form-control', 'id' => 'login', 'readonly' =>'readonly')) }}
                              @if($action == "update")
                              <input type='checkbox' name='defaultpwd' id="defaultpwd" value="1" onclick="chnagePasswordToggle()"> Change Password.
                              @endif
                          </td>
                        </tr>

                        <tr <?php echo (($action == 'update')? 'style="display:none"': '') ?> id="tr_pass">
                          <th style="text-align:center; vertical-align:middle; font-weight: bold;">Password:*</th>
                          <td>
                              
                              {{ Form::password('pass', array('class' => 'form-control','id'=>'pass')) }}
                          </td>
                        </tr>
                        <tr <?php echo (($action == 'update')? 'style= "display:none"': '') ?> id="tr_re_pass">
                          <th style="text-align:center; vertical-align:middle; font-weight: bold;">Re-Password:*</th>
                          <td>
                              {{ Form::password('repass', array('class' => 'form-control','id'=>'repass')) }}<br>
                              
                          </td>
                        </tr>
                          </table>
                      </td>
                  </tr>
                  

                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Address 1:</th>
                      <td style="padding-left:2px;">
                          {{ Form::textarea('address1', @$GpgVendorData->address,['class'=>'form-control','id'=> 'address1']) }}
                      </td>
                  </tr>

                   <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Address 2:</th>
                      <td style="padding-left:2px;">
                          {{ Form::textarea('address2', @$GpgVendorData->address2,['class'=>'form-control','id'=> 'address2']) }}
                      </td>
                  </tr>

                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">City:</th>
                      <td>
                          {{ Form::text('city',@$GpgVendorData->city, array('class' => 'form-control', 'id' => 'city', '')) }}
                      </td>
                  </tr>

                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">State:</th>
                      <td>
                          {{ Form::text('state',@$GpgVendorData->state, array('class' => 'form-control', 'id' => 'state', '')) }}
                      </td>
                  </tr>

                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Zip Code:</th>
                      <td>
                          {{ Form::text('zip_code',@$GpgVendorData->zipcode, array('class' => 'form-control', 'id' => 'zip_code', '')) }}
                      </td>
                  </tr>

                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Phone Number:</th>
                      <td>
                          {{ Form::text('phone_no',@$GpgVendorData->phone_no, array('class' => 'form-control', 'id' => 'phone_no', '')) }}
                      </td>
                  </tr>

                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Recommendations:</th>
                      <td>
                          {{ Form::textarea('recommendation', @$GpgVendorData->recommendation,['class'=>'form-control','id'=> 'recommendation']) }}
                      </td>
                  </tr>


                  <tr>
                      <td>&nbsp;</td>   
                      <td >
                          <br><br>
                          @if($action == 'add')
                            {{Form::submit("Add Vendor", array('class' => 'btn btn-success', 'style'=>'margin-top:-15px;'))}}
                          @else
                            {{Form::submit("Update Vendor", array('class' => 'btn btn-success', 'style'=>'margin-top:-15px;'))}}
                          @endif  
                      </td>
                  </tr>

              <br/>

            </section>
        {{ Form::close() }}
    </section>
<!-- ////////////////////////////////////////// -->
 </section>
 </div>
 </div>
 <!-- page end-->


      <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
      <script src="{{asset('js/common-scripts.js')}}"></script>
      <script type="text/javascript">
            $( document ).ready(function() {
                var vall = $('#venType').val();
                if(vall == 'S')
                {
                   $('#loginFrm').show();
//                   $('#pass').attr('required','required');
//               $('#repass').attr('required','required');
//               $('#repass').attr('equalTo','#pass'); 
               
                } else{
                     $('#loginFrm').hide();
                }
            });
   
   
          function toggleLogin(obj) {
            if (obj.value=='S'){
               $('#loginFrm').show();
               
               $('#pass').attr('required','required');
               $('#repass').attr('required','required');
               $('#repass').attr('equalTo','#pass'); 
               
               loginVal = $('#name').val();
               populateLogin(loginVal);
               
            } else{
                
                $('#loginFrm').hide();
                $('#pass').removeAttr('required');
                $('#repass').removeAttr('required');
                $('#repass').removeAttr('equalTo'); 
                $('#login').val('');
            } 
                
            //else DG('loginFrm').style.display = 'none';
            
	}
        function populateLogin(strVal){
            var login = strVal.toLowerCase();
            //login = login.replace(" ","");
            login = login.replace('"',"");
            login = login.replace('/',"");
            login = login.replace('$',"");
            login = login.replace('.',"");
            login = login.replace('%',"");
            
            $('#login').val(login );
        }
        
        function chnagePasswordToggle(){
            
            if ($('#defaultpwd').is(':checked')){
               
                $('#tr_pass').show();
                $('#tr_re_pass').show();
                $('#pass').attr('required','required');
                $('#repass').attr('required','required');
                $('#repass').attr('equalTo','#pass'); 
               
            } else {
                $('#tr_pass').hide();
                $('#tr_re_pass').hide();
                $('#pass').removeAttr('required');
                $('#repass').removeAttr('required');
                $('#repass').removeAttr('equalTo');   
            } 
        }
        
      </script>    
@stop