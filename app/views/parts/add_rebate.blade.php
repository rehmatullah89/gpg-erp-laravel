@extends("layouts/dashboard_master")

@section('dashboard_panels')
<section class="panel">
  <header class="panel-heading">
    MANAGE REBATE
    <span class="tools pull-right"> <a href="javascript:;" class="fa fa-chevron-down"></a></span>
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
            <td>#id</td>
            <th>Measure</th>
            <th>Description</th>
            <th>Amount</th>
            <th>Type</th>
            <th>Year</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
         @foreach($query_data as $rabate_detailRow)
          <tr>
            <td>{{$rabate_detailRow['id']}}</td>
            <td>{{$rabate_detailRow['rebate_measure']}}</td>
            <td>{{$rabate_detailRow['rebate_description']}}</td>
            <td>{{$rabate_detailRow['rebate_amount']}}</td>
            <td>{{$rabate_detailRow['rebate_type']}}</td>
            <td>{{$rabate_detailRow['rebate_start_year']}}</td>
            <td><a class="edit" href="javascript:;">Edit</a></td>
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
  <script src="{{asset('js/editable-rebate.js') }}"></script>
  <script>
    jQuery(document).ready(function() {
        EditableTable.init();
    });
  </script>
@stop