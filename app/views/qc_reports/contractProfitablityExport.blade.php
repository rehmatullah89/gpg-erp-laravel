<table>
<?
$SDate =  Input::get("SDate");
$qpart =  Input::get("qpart");
$EDate =  Input::get("EDate");
$regard = Input::get("regard");
$regard = explode('~@@~', $regard);
$c_num = Input::get("c_num");
$c_num_end = Input::get("c_num_end");
$export_type = Input::get("export_type");
echo "string:".$export_type;
if($export_type==1)
{
    $row_start = 4;
    $row_search_data = 1;

    echo '<tr><td></td><td></td>
	<td>Start Date</td>
    <td>'.($SDate==""?"":date('m/d/Y',strtotime($SDate))).'</td>
    <td>End Date</td>
    <td>'.($EDate==""?"":date('m/d/Y',strtotime($EDate))).'</td>
    </tr>';

    foreach($arr_data as $k => $v)
    {
        $main_str = "";
        $sub_data = "";
        $sum_inv_amnt = 0;
        $sum_cost_to_date = 0;
        $total_material_cost = 0;
        $total_labor_cost = 0;
        $dat_row_start = 0;
        $dat_row_end = 0;
        $fmla_inv = "";
        $fmla_cst = "";
        $total_rows = 0;
        $short = 0;
        $sub_child_data = "";
        $start_total = $total_rows;
        if(sizeof($v)==0){
            $sub_child_data  .= '<tr class="mydat"><td colspan="7" bgcolor="#FFFFCC">NO DATA</td></tr>';
        }
        else{
            foreach($v as $k1=> $v1){
                $total_rows++;
                $sub_child_data  .= '<tr class="mydat">
                    <th width="20px" bgcolor="#FFC1C1">&nbsp;</th>
                    <th colspan="8" align="left">'.$k1.'</th>
                </tr>';
                $dat_row_start  = $total_rows + 3;
                foreach($v1 as $k2 => $v2)
                {
                    $total_rows++;
                    $sub_child_data  .= '<tr class="mydat">
                        <td width="20px" >&nbsp;</td>
                        <td width="20px" >&nbsp;</td>';
                    $sum_inv_amnt+= $v2['inv_amnt'];
                    $sum_cost_to_date += $v2['cost_to_date'];
                    $total_material_cost += $v2['material_cost'];
                    $total_labor_cost += $v2['labor_cost'];
                    $sub_child_data  .= '<td>'.$v2['regarding'].'</td>
											<td align="right"  class="amount_class">'.($v2['inv_amnt']).'</td>
											<td class="amount_class">'.($v2['material_cost']).'</td>
											<td class="amount_class">'.($v2['labor_cost']).'</td>
											<td class="amount_class">'.($v2['cost_to_date']).'</td>
											<td class="amount_class">'.($total_rows+2).'</td>
											<td class="total_percent">'.($total_rows+2).'</td>
										</tr>';

                }
                $dat_row_end = $total_rows +2;
                $fmla_inv.= $dat_row_start+$dat_row_end;
                $fmla_cst.= $dat_row_start+$dat_row_end;

            }
            //
        }
        $sub_data  .= '<tr>
        <td colspan="8"><table width="100%">
        <tr class="mydathead">
            <th width="20px" >&nbsp;</th>
            <th width="20px" >&nbsp;</th>
            <th>Regarding</th>
            <th>Sum of Invd. Amount Net</th>
            <th>Material Cost</th>
            <th>Labor Cost</th>
            <th>Sum of Cost to Date</th>
            <th>Sum of Gross Profit / (Loss)</th>
            <th>Sum of % Margin</th>
        </tr>';
        $sub_data .= '<tr bgcolor="#FFFFCC">
            <td width="20px" >&nbsp;</td>
            <td width="20px" >&nbsp;</td>
			<td></td>
			<td >'.substr($fmla_inv,1,strlen($fmla_inv)).'</td>
            <td >'.$total_material_cost.'</td>
            <td >'.$total_labor_cost.'</td>
            <td >'.substr($fmla_cst,1,strlen($fmla_cst)).'</td>
            <td >'.($start_total+2).'</td>';
        $sub_data .= '<td class="total_percent_bold">'.($start_total+2).'</td>';
        $sub_data .='</tr>';
        $sub_data  .=  $sub_child_data.'</table></td></tr>';

        $main_str .= '<tr><th colspan="3" align="left" >'.$k.'</th>
        </tr>'.$sub_data;
        echo $main_str;
        $total_rows+=3;
    }
}
elseif($export_type==2)
{
    $row_start = 4;
    $row_search_data = 1;

    echo '<tr>
	<td>Start Date</td>
    <td>'.($SDate==""?"-":date('m/d/Y',strtotime($SDate))).'</td>
    <td>End Date</td>
    <td>'.($EDate==""?"-":date('m/d/Y',strtotime($EDate))).'</td>
</tr><tr class="mydathead">
            <th width="20px" >Contract Number</th>
            <th>Sum of Inv\'d Amount Net</th>
            <th>Material Cost</th>
            <th>Labor Cost</th>
            <th>Sum of Cost to Date</th>
            <th>Sum of Gross Profit / (Loss)</th>
            <th>Sum of % Margin</th>
        </tr>';
    $start_total = 0;
    foreach($arr_data as $k => $v)
    {
        $main_str = "";
        $sub_data = "";
        $sum_inv_amnt = 0;
        $sum_cost_to_date = 0;
        $total_material_cost = 0;
        $total_labor_cost = 0;

        $dat_row_start = 0;
        $dat_row_end = 0;
        $fmla_inv = "";
        $fmla_cst = "";
        $total_rows = 0;


        $short = 0;
        $sub_child_data = "";
        $start_total++;
        if(sizeof($v)==0){
            $sub_child_data  .= '<tr class="mydat"><td colspan="7" bgcolor="#FFFFCC">NO DATA</td></tr>';
        }
        else{
            foreach($v as $k1=> $v1)
            {
                $total_rows++;
                $dat_row_start  = $total_rows + 3;
                foreach($v1 as $k2 => $v2)
                {
                    $total_rows++;
                    $sum_inv_amnt+= $v2['inv_amnt'];
                    $sum_cost_to_date += $v2['cost_to_date'];
                    $total_material_cost += $v2['material_cost'];
                    $total_labor_cost += $v2['labor_cost'];
                }
                $dat_row_end = $total_rows +2;
            }
            $fmla_inv= $sum_inv_amnt;
            $fmla_cst= $sum_cost_to_date;
        }
        $sub_data  .= '';
        $sub_data .= '<tr><th align="left" bgcolor="#FFFFCC">'.$k.'</th>
			<td>'.($fmla_inv).'</td>
			<td>'.$total_material_cost.'</td>
            <td>'.$total_labor_cost.'</td>
            <td>'.$fmla_cst.'</td>
            <td>'.($start_total+2).'</td>
			<td class="total_percent_bold">'.($start_total+2).'</td></tr>';
        $sub_data  .=  $sub_child_data.'';
        $main_str .= '
        '.$sub_data;
        echo $main_str;
        $total_rows+=3;
    }
} else if($export_type == 3){
    $html = '';
    $query = DB::select(DB::raw("SELECT
          total_wage AS labor_cost,
          (SELECT CONCAT(gpg_employee.id,'##',NAME,'##',date) FROM gpg_timesheet,gpg_employee WHERE gpg_timesheet.GPG_employee_Id = gpg_employee.id AND gpg_timesheet.id = gpg_timesheet_detail.GPG_timesheet_id) AS e_id,
          gpg_timesheet_detail.time_diff_dec AS total_hr,
          gpg_job.job_num,
          gpg_job.task,
          gpg_job.contract_number
          FROM
          gpg_timesheet_detail,
          gpg_job
          WHERE gpg_timesheet_detail.job_num = gpg_job.job_num
          ".$querypart."
          ORDER BY gpg_job.contract_number,gpg_job.task,2"));
    if(count($query) > 0){
        ?>
        <tr>
            <td  height="25" align="center" bgcolor="#CCCCCC">Contract No.</td>
            <td  align="center" bgcolor="#CCCCCC">Regard</td>
            <td  align="center" bgcolor="#CCCCCC">Job No.</td>
            <td  align="center" bgcolor="#CCCCCC">Date</td>
            <td  align="center" bgcolor="#CCCCCC">Employee Name</td>
            <td  align="center" bgcolor="#CCCCCC">Total Hours</td>
            <td  align="center" bgcolor="#FFC1C1">Labor Cost</td>
        </tr>
        <?php
        $totalHours = 0;
        $amount = 0;
        foreach ($query as $key => $value2) {
            $rowData = (array)$value2;
            $employee = explode('##',$rowData['e_id']);
            ?>
            <tr>
                <td align="center" height="20" bgcolor="#FFFFFF"><?php echo $rowData['contract_number'] ?></td>
                <td align="center" height="20" bgcolor="#FFFFFF"><?php echo $rowData['task'] ?></td>
                <td align="center" height="20" bgcolor="#FFFFFF"><?php echo $rowData['job_num'] ?></td>
                <td align="center" height="20" bgcolor="#FFFFFF"><?php echo date('m/d/Y',strtotime($employee[2])) ?></td>
                <td align="center" bgcolor="#FFFFFF"><?php echo $employee[1] ?></td>
                <td align="center" bgcolor="#FFFFFF"><?php echo $rowData['total_hr'] ?></td>
                <td bgcolor="#FFC1C1" align="center" class="amount_class"><?
                    $amount = (!empty($rowData['labor_cost']) ? $rowData['labor_cost'] : 0);
                    echo $amount;
                    $total += $amount;
                    $totalHours += $rowData['total_hr'];
                    ?>
                </td>
            </tr>
        <?php
        }
        ?>
        <tr>
            <td height="25" bgcolor="#FFFFCC" style="font-weight: bold;" colspan="5" align="right">T O T A L</td>
            <td align="center" bgcolor="#FFFFCC" style="font-weight: bold;"><?php echo $totalHours; ?></td>
            <td align="center" bgcolor="#FFFFCC"><?php echo $total; ?></td>
        </tr>
    <?php
    }
}
?>
</table>