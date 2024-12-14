	@extends("layouts/login_master") <!-- name of mastar template -->
	
	@section("login_content") 		<!-- defining the section for master template file -->
	{{ Form::open(array('before' => 'csrf' ,'url'=>route('change_pass'), 'files'=>true, 'method' => 'post','class'=>'form-signin')) }}
		<h2 class="form-signin-heading">Change Your Password here!</h2>	
		<div class="login-wrap">
			@if(isset($validation_error))
				<div class="error">{{$validation_error}}</div>
			@endif
			<b>Old Password:</b><br/> {{Form::password('oldpass',array('class'=>'form-control','required'))}}
			<b>New Password:</b> {{Form::password('newpass',array('class'=>'form-control','required'))}}
			<b>Repeat Password:</b> {{Form::password('repass',array('class'=>'form-control','required'))}}
			<input type="hidden" name="user_id" value="{{Auth::user()->ad_id}}">
			{{Form::submit('Submit',array('class'=>'btn btn-md btn-success btn-block'))}}
			<a class='btn btn-md btn-danger btn-block' href="{{ URL::previous() }}">Go Back</a>
		</div>
		{{Form::close()}}
    @stop