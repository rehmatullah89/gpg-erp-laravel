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
                  View Employee Details
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <div class="panel-body">
              <div class="adv-table">
               <?php $name = ''; 
                    $date = ''; 
                    $dob = '';
                    $email = '';
                    $status = '';
                    $phone = '';
                    $type = '';
                    $GPG_employee_Id = '';
              ?>
              @foreach($timesheet as $key=>$value) 
                 <?php  
                  if($key == 'name')
                     $name = $value;
                  else if($key == 'date')      
                     $date = $value; 
                  else if($key == 'dob')      
                     $dob = $value;
                  else if($key == 'email')      
                     $email = $value; 
                  else if($key == 'status')      
                     $status = $value; 
                  else if($key == 'phone')      
                     $phone = $value; 
                  else if($key == 'type')      
                     $type = $value;
                  else if($key == 'GPG_employee_Id')      
                     $GPG_employee_Id = $value; 
                  else if($key == 'job_num')      
                     $job_num = $value;     
                  else if($key == 'time_in')      
                     $time_in = $value;     
                  else if($key == 'time_out')      
                     $time_out = $value;  
                  else if($key == 'labor_rate')      
                     $labor_rate = $value;
                  else if($key == 'complete_flag')      
                     $complete_flag = $value;
                  else if($key == 'frontend')      
                     $frontend = $value;
                 ?>
              @endforeach
              
         
              <!-- page start-->
              <div class="row">
                  <aside class="profile-nav col-lg-3">
                      <section class="panel">
                          <div class="user-heading round">
                              <a href="#">
                                  <img src="{{asset('img/noavatar.png')}}" alt="">
                              </a>
                              <h1>{{$name}}</h1>
                              <p>{{$email}}</p>
                          </div>

                        
                      </section>
                  </aside>
                  <aside class="profile-info col-lg-9">
                     
                      <section class="panel">
                          <div class="bio-graph-heading">
                              <b>Employee personal and time sheet scheduled detailed Information</b>
                          </div>
                          <div class="panel-body bio-graph-info">
                              <h1><b>Bio Graph</b></h1>
                              <div class="row">
                                  <div class="bio-row">
                                      <p><span>Employee Name </span>: {{$name}}</p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span>Employee ID </span>: {{$GPG_employee_Id}}</p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span>Employee Status </span>: {{$status}}</p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span>Birthday</span>: {{$dob}}</p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span>Employee Type </span>: {{$type}}</p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span>Email </span>: {{$email}}</p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span>Phone </span>: {{$phone}}</p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span>Mod. Rights: </span>: {{$frontend}}</p>
                                  </div>
                              </div><br/>
                              <h1><b>Time Sheet Details</b></h1>
                              <div class="row">
                                  <div class="bio-row">
                                      <p><span>Job Number </span>: {{$job_num}}</p>
                                  </div>
                                 <div class="bio-row">
                                      <p><span>Job Date </span>: {{$date}}</p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span>Time In  </span>: {{$time_in}}</p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span>Time Out</span>: {{$time_out}}</p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span>Labor Rate </span>:  ${{$labor_rate}}</p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span>Job Status </span>: <?php if (empty($complete_flag) || $complete_flag == '0') {
                                        echo "Not Completed";
                                      }else
                                        echo "Completed";?></p>
                                  </div>
                              </div>
                          </div>
                      </section>
                  </aside>
              </div>
              <!-- page end-->
               </div>
              </div>
              </section>
              </div>
              </div>
              
              <!-- page end-->
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>
@stop