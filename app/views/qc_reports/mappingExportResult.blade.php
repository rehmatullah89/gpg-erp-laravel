<table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
<thead>
	<?php 
		$check = 0;
		foreach ($query_data as $key0 => $value0) {
			if($check == 0){
				$check =1;
				echo "<tr>";
				foreach ($value0 as $key => $value) {
					echo "<td><b>".ucfirst($key)."</b></td>";		
				}
				echo "</tr>";
			}
		}
		?>
</thead>
<tbody>	
		<?php 
		foreach ($query_data as $key1 => $value1) {
			echo "<tr>";
			foreach ($value1 as $key2 => $value2) {
				echo "<td>".$value2."</td>";		
			}
			echo "</tr>";
		}
		?>
</tbody>
</table>
    