@extends("layouts/dashboard_master")
@section('content')
 
@stop
@section('dashboard_panels')
    <!-- page start--> 
<?php
    $action = "add";  
    $breadCrumb = "ADD A NEW CUSTOMER";
    if(isset($GpgCustomerData) && !empty($GpgCustomerData)){
         $action = "update";
         $breadCrumb = "UPDATE CUSTOMER";
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
            <i>  Customer Information: </i>
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
            {{ Form::open(array('before' => 'csrf' ,'url'=>route('customers.create'), 'id'=>'customerForm', 'files'=>true, 'method' => 'post')) }}
        @elseif($action == 'update')
            {{ Form::open(array('before' => 'csrf' ,'url'=>URL::route('customers.update', array('id'=>$GpgCustomerData->id)), 'id'=>'customerForm', 'files'=>true, 'method' => 'put')) }}
            <input type="hidden" name="old_name" value="{{@$GpgCustomerData->name}}">
        @endif
        <input type="hidden" name="name_1" value="12345">
          <section id="no-more-tables" style="padding:10px;">
          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
            <tbody>
                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Name*:</th>
                      <td>
                          {{ Form::text('name',@$GpgCustomerData->name, array('class' => 'form-control', 'id' => 'name', 'required','onkeyUp'=>'populateLogin(this.value)')) }}
                      </td>
                  </tr>
                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Email Address*:</th>
                      <td>
                          {{ Form::text('email_add',@$GpgCustomerData->email_add, array('class' => 'form-control', 'id' => 'email_add', '')) }}
                      </td>
                  </tr>
                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Status:</th>
                      <td style="padding-left:2px;">
                          {{Form::select('status', array('A' => 'Active','B' => 'Blocked'), @$GpgCustomerData->status, ['id' => 'status', 'class'=>'form-control l-bot6'])}}
                      </td>
                  </tr>

                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Type: <?php if(isset($_POST['type'])){ echo $_POST['type'];}?></th>
                      <td style="padding-left:2px;">
                          {{Form::select('cusType', array('' => 'Customer','C' => 'Natl Acct & Prop Mgr.'),  @$GpgCustomerData->cus_type, ['id' => 'cusType','onchange' => 'toggleLogin(this);', 'class'=>'form-control l-bot6'])}}
                      </td>
                  </tr>
                  <tr id="loginFrm" style="display: none">
                      <th></th>
                      <td>
                          <table>
                              <tr>
                          <th style="text-align:center; vertical-align:middle; font-weight: bold;">Login Name:</th>
                          <td>
                              {{ Form::text('login',@$GpgCustomerData->login, array('class' => 'form-control', 'id' => 'login', 'readonly' =>'readonly')) }}
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
                          {{ Form::textarea('address1', @$GpgCustomerData->address,['class'=>'form-control','id'=> 'address1']) }}
                      </td>
                  </tr>

                   <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Address 2:</th>
                      <td style="padding-left:2px;">
                          {{ Form::textarea('address2', @$GpgCustomerData->address2,['class'=>'form-control','id'=> 'address2']) }}
                      </td>
                  </tr>

                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">City:</th>
                      <td>
                          {{ Form::text('city',@$GpgCustomerData->city, array('class' => 'form-control', 'id' => 'city', '')) }}
                      </td>
                  </tr>

                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">State:</th>
                      <td>
                          {{ Form::text('state',@$GpgCustomerData->state, array('class' => 'form-control', 'id' => 'state', '')) }}
                      </td>
                  </tr>

                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Zip Code:</th>
                      <td>
                          {{ Form::text('zip_code',@$GpgCustomerData->zipcode, array('class' => 'form-control', 'id' => 'zip_code', '')) }}
                      </td>
                  </tr>

                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Phone Number:</th>
                      <td>
                          {{ Form::text('phone_no',@$GpgCustomerData->phone_no, array('class' => 'form-control', 'id' => 'phone_no', '')) }}
                      </td>
                  </tr>

                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Attn:</th>
                      <td>
                          {{ Form::text('attn',@$GpgCustomerData->attn, array('class' => 'form-control', 'id' => 'attn', '')) }}
                      </td>
                  </tr>


                  <tr>
                      <td>&nbsp;</td>   
                      <td >
                          <br><br>
                          @if($action == 'add')
                            {{Form::submit("Add Customer", array('class' => 'btn btn-success', 'style'=>'margin-top:-15px;'))}}
                          @else
                            {{Form::submit("Update Customer", array('class' => 'btn btn-success', 'style'=>'margin-top:-15px;'))}}
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
                var vall = $('#cusType').val();
                if(vall == 'C')
                {
                   $('#loginFrm').show();
                   
                   //$('#pass').attr('required','required');
                   //$('#repass').attr('required','required');
                   //$('#repass').attr('equalTo','#pass'); 
               
                } else{
                     $('#loginFrm').hide();
                }
            });
   
   
          function toggleLogin(obj) {
            if (obj.value=='C'){
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
            login = login.replace(" ","");
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