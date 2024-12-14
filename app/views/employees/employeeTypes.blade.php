@extends("layouts/dashboard_master")
@section('content')
  <section>
    
  </section>
@stop
@section('dashboard_panels')
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$sdate = "";
$edate = "";
?>
 <header class="panel-heading">
    Employees Types Management
    <span class="tools pull-right">
       <a href="javascript:;" class="fa fa-chevron-down"></a>
    </span>
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
<div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                                      <thead class="cf">

                <tr>
                    <th style="text-align:center;">ID#</th>
                    <th style="text-align:center;">Name</th>
                    <th style="text-align:center;">Action</th>
                </tr>
                                      </thead>
                                      <tbody>
                <?php 
                foreach($query_data as $data){ ?>
                <tr>
                    <td data-title="#ID:">{{ $data['type_id'] }}</td>
                  <td data-title="Real Name:">{{($data['type'] != "")? strtoupper($data['type']): "-"}}</td>
                  
                  
                  <td data-title="Action:">
                  <a data-toggle="modal" style="display:inline;" href="{{URL::route('employees.edit', array('id'=>$data['type_id']))}}">
                  {{Form::button('<i class="fa fa-pencil"></i>', array('class' => 'btn btn-primary btn-xs'))}} 
                  </a>                                        
                  {{ Form::open(array('method' => 'DELETE', 'id'=>'myForm'.$data['type_id'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('employees.destroy', $data['type_id']))) }}
                    {{ Form::button('<i class="fa fa-trash-o"></i>', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$data['type_id'].'").submit()')) }}
                  {{ Form::close() }}
                  </td>
                </tr>
                
                <?php }?>
               
                    </tbody>
                </table>
                {{ $query_data->links() }}
              </section>
              </div>
              </div>

<script src="{{asset('js/jquery.nicescroll.js')}}"></script>
<script src="{{asset('js/common-scripts.js')}}"></script>

@stop       
