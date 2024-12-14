	@extends("layouts/login_master") <!-- name of mastar template -->
	
	@section("login_content") 		<!-- defining the section for master template file -->
		{{Form::open(array('url'=>'loginaction','method'=>'post','class'=>'form-signin'))}}
		<h2 class="form-signin-heading">sign in now</h2>
		
		<div class="login-wrap">
			@if(isset($validation_error))
				<div class="error">{{$validation_error}}</div>
			@endif
			@if(isset($messages))
				{{$messages->first('username', '<div class="error">:message</div>');}}
			@endif
			{{Form::text('username','',array('class'=>'form-control','placeholder'=>'User ID','autofocus'))}}
			@if(isset($messages))
				{{$messages->first('userpass', '<div class="error">:message</div>');}}
			@endif
			{{Form::password('userpass',array('class'=>'form-control','placeholder'=>'Password'))}}
			
			{{Form::submit('Sign In',array('class'=>'btn btn-lg btn-login btn-block'))}}
		</div>
		{{Form::close()}}
    @stop