              <table class="table table-bordered table-striped table-condensed cf" >
              <thead class="cf">
              <tr>
                  <th>id#</th>
                  <th>Type</th>
                  <th>Specs</th>
                  <th >Part #</th>
                  <th >Manufacturer</th>
                  <th >Cost</th>
                  <th >List</th>
                  <th >Margin %</th>
                  <th >Vendor</th>
                  <th >Job Num</th>
                  <th >Quantity</th>
                  <th >Cost</th>
                  <th >List</th>
                  <th >Margin %</th>
              </tr>
              </thead>
              <tbody class="cf">
                <?php 
                  $pre = '';
                  $fg=false; ?>
                @foreach($query_data as $row)
                  <?php 
                    if ($pre!=$row['part_number']) $fg = !$fg;
                  ?>
                  <tr>
                  @if($pre!=$row['part_number'])
                      <td>{{$row['id']}}</td>
                      <td>{{$row['material_type']}}</td>
                      <td>{{$row['description']}}</td>
                      <td>{{$row['part_number']}}</td>
                      <td>{{$row['manufacturer']}}</td>
                      <td>{{'$'.number_format($row['cost'],2)}}</td>
                      <td>{{'$'.number_format($row['list'],2)}}</td>
                      <td>{{$row['margin']}}</td>
                      <td><?php if(!empty($row['gpg_vendor_id'])) { echo DB::table('gpg_vendor')->where('status','=','A')->where('id','=',$row['gpg_vendor_id'])->pluck('name');}?></td>
                  @else
                      <td colspan="9" ><strong>-</strong></td>
                  @endif
                      <td>{{$row['attachJobNum']}}</td>
                      <td>{{$row['attachQuantity']}}</td>
                      <td>{{'$'.number_format($row['attachCostPrice'],2)}}</td>
                      <td>{{'$'.number_format($row['attachListPrice'],2)}}</td>
                      <td>{{number_format($row['attachMargin'],2)}}</td>
                  <?php $pre = $row['part_number']; ?>
                  </tr>
                @endforeach
              </tbody>