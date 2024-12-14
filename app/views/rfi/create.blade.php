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
               REQUEST FOR INFORMATION
            
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                        <i>  Add here requested Information: </i>
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
                            {{ Form::open(array('before' => 'csrf' ,'url'=>route('rfi.store'), 'files'=>true, 'method' => 'post')) }}
                            <section id="no-more-tables" style="padding:10px;">
                                <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                                  <thead>
                                    <tr>
                                    <th>Title.*</th><th>Job Number</th><th>Requested To</th><th>Complete</th><th>Comment</th><th>Attach Image</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <input type="hidden" name="counter" id="id_counter" value="0">
                                    <tr>
                                    <td data-title="Title:">{{Form::text('title_0','', ['id'=>'title_0', 'class'=>'form-control m-bot12','required'])}}</td>
                                    <td data-title="Job Num:">{{Form::text('JobNumber_0','', ['id'=>'JobNumber_0', 'class'=>'form-control m-bot12','required'])}}</td>
                                    <td data-title="Request To:">{{Form::select('RequestToId_0',$emps,'', ['id'=>'RequestToId_0', 'class'=>'form-control m-bot12'])}}</td>
                                    <td data-title="Status:">{{Form::checkbox('rfiStatus_0', '0',array('id'=>'rfiStatus_0'))}}</td>
                                    <td data-title="Text:">{{ Form::textarea('rfi_0', null, ['size' => '30x5','class'=>'form-control m-bot12','required']) }}</td>
                                    <td data-title="File:">{{Form::file('fileToUpload_0','', ['id'=>'fileToUpload_0', 'class'=>'form-control m-bot12'])}}</td>
                                    </tr>  
                                    </tbody>
                                    </table>
                                    {{Form::button("Create New Line", array('id'=>'add_new_line','class' => 'btn btn-warning'))}}
                                    {{Form::button("Remove Line", array('id'=>'remove_row','class' => 'btn btn-danger'))}}
                                    {{Form::submit("Create RFI", array('class' => 'btn btn-success'))}}
                                  </section>
                            {{ Form::close() }}
                            </section>
             <!-- ////////////////////////////////////////// -->
           
              </section>
              </div>
              </div>
              <!-- page end-->
<script type="text/javascript">
  count=0;
  $('#add_new_line').click(function(){
    count = parseInt(count) + parseInt("1");
    var str = '<tr><td data-title="Title:"><input type="text" value="" name="title_'+count+'" class="form-control m-bot12" id="title_'+count+'" required></td>';
        str += '<td data-title="Job Num:"><input type="text" value="" name="JobNumber_'+count+'" class="form-control m-bot12" id="JobNumber_'+count+'" required></td>';
        str += '<td data-title="Request To:"><select name="RequestToId_'+count+'" class="form-control m-bot12" id="RequestToId_'+count+'">'+document.getElementById('RequestToId_0').innerHTML+'</select></td>';
        str += '<td data-title="Status:"><input type="checkbox" value="'+count+'" name="rfiStatus_'+count+'" checked="checked"></td>';
        str += '<td data-title="Text:"><textarea rows="5" cols="3'+count+'" name="rfi_'+count+'" class="form-control m-bot12" required></textarea></td>';
        str += '<td data-title="File:"><input type="file" name="fileToUpload_'+count+'"></td></tr>';
     $('#mytable > tbody:last').append(str);
       $('#JobNumber_'+count).focus(function() {  
        $(this).autocomplete({
        source: function (request, response) {
        $("span.ui-helper-hidden-accessible").before("<br/>");  
        $.ajax({
          url: "{{URL('ajax/getJobNumberAutocomplete')}}",
          data: {
          JobNumber: this.term
        },
        success: function (data) {
        response( $.map( data, function( item ) {
        return {
          label: item.name,
          value: item.id
        };
        }));
       },
      });
      },
     });
    });

    $('#id_counter').val(count);    
  });
  
  $('#remove_row').click( function(){
    if (count>1){
        $('#mytable > tbody > tr:last').remove();
        count=count-1;
        $('#id_counter').val(count);
      }
  }); 

  $('#JobNumber_0').focus(function() {  
        $(this).autocomplete({
        source: function (request, response) {
        $("span.ui-helper-hidden-accessible").before("<br/>");  
        $.ajax({
          url: "{{URL('ajax/getJobNumberAutocomplete')}}",
          data: {
          JobNumber: this.term
        },
        success: function (data) {
        response( $.map( data, function( item ) {
        return {
          label: item.name,
          value: item.id
        };
        }));
       },
      });
      },
     });
    });    
</script>
<script src="{{asset('js/jquery.nicescroll.js')}}"></script>
<script src="{{asset('js/common-scripts.js')}}"></script>
@stop