@extends("layouts/dashboard_master")

@section('dashboard_panels')
<section class="panel">
  <header class="panel-heading">
    CONTRACT SERVICE TYPE<span class="tools pull-right"> <a href="javascript:;" class="fa fa-chevron-down"></a></span>
  </header>
  <div class="panel-body">
    <div class="adv-table editable-table ">
      <div class="clearfix">
        <div class="btn-group">
          <button id="editable-listing_new" class="btn green">
          Add New <i class="fa fa-plus"></i>
          </button>
        </div>
      </div>
      <div class="space15"></div>
      <table class="table table-striped table-hover table-bordered" id="editable-listing">
        <thead>
          <tr>
            <th>#</th>
            <th>Service Type</th>
            <th>Edit</th>
            <th>Delete</th>
          </tr>
        </thead>
        <tbody>
          @foreach($results as $getRow)
            <tr class="">
              <td>{{ $getRow['id'] }}</td>
              <td>{{ $getRow['service_type'] }}</td>
              <td><a class="edit" href="javascript:;">Edit</a></td>
              <td><a class="delete" href="javascript:;">Delete</a></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</section>

  <link rel="stylesheet" href="{{asset('assets/data-tables/DT_bootstrap.css')}}" />
  
  <script src="{{asset('js/jquery-migrate-1.2.1.min.js') }}"></script>
  <script src="{{asset('js/jquery.nicescroll.js') }}"></script>
  <script src="{{asset('js/common-scripts.js') }}"></script>
  <script> base_url = "{{ URL::to('/') }}" </script>
  <script src="{{asset('js/editable-contract-service-type.js') }}"></script>
  <script>
    jQuery(document).ready(function() {
        EditableTable.init();
    });
  </script>
@stop