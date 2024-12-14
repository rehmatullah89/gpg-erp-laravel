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
                 REQUEST FOR INFORMATION LISTING 
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                      <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b><i>Listing for requested information</i></b>
                      </header>
                             {{ Form::open(array('before' => 'csrf' ,'url'=>route('rfi/search'), 'files'=>true, 'method' => 'post')) }}
                                  <section id="no-more-tables" style="padding:10px;">
                                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                                  <tbody>
                                  <tr>
                                     <td>Job Number:</td>  
                                     <td>{{ Form::text('jobNum','', array('class' => 'form-control', 'id' => 'jobNum')) }}</td> 
                                     <td>{{Form::submit('Submit', array('class' => 'btn btn-info'))}}</td>
                                  </tr>
                                  </tbody>
                                  </table>
                                  </section>
                               {{ Form::close() }} 
                </section> 
             <!-- ////////////////////////////////////////// -->
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
              <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                                      <thead class="cf">
                                      <tr>
                                          <th style="text-align:center;">Job Number</th>
                                          <th style="text-align:center;">Requested By</th>
                                          <th style="text-align:center;">Requested To</th>
                                          <th style="text-align:center;">Title</th>
                                          <th style="text-align:center;">Latest Comment</th>
                                          <th style="text-align:center;">RFI Status</th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                        @foreach($query_data as $getRow)
                                        <tr>
                                         <td>
                                          {{ HTML::link('rfi/request_for_info/'.$getRow['id'], (isset($getRow['job_num'])?$getRow['job_num']:'-'), array('target'=>'_blank','class'=>'btn btn-link', 'id'=>$getRow['id']))}} 
                                         </td>  
                                         <td>{{$getRow['fname']}}</td>  
                                         <td>{{$getRow['rtname']}}</td>  
                                         <td>{{$getRow['title']}}</td>  
                                         <td>{{date('m/d/Y'.' h:i:s A',strtotime($getRow['latest_comm_date']))}}</td>  
                                         <td>{{($getRow['status']==1?"<strong>Closed</strong>":"Open")}}</td>  
                                        </tr>
                                        @endforeach
                                      </tbody>
                                  </table>
                           {{ $query_data->appends(array_filter(Input::except('_token')))->links() }}
              </section>
              </div>
              </div>
              </section>
              </div>
              </div>
              <!-- page end-->
<script type="text/javascript">
    $('#jobNum').focus(function() {  
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
@stop