<div class="col-sm-12">
                    	<table class="display table-hover table table-bordered table-striped">
                          <thead>
                          <tr>
                              <th rowspan="2">Job Type</th>
                              <th rowspan="2">Added</th>
                              <th rowspan="2">Completed</th>
                              <th colspan="2" style="text-align:center;">Open</th>
                              <th rowspan="2">Allocated Hr.</th>
                          </tr>
                          <tr>
                              <th>Jobs</th>
                              <th>Contracts</th>
                          </tr>
                          </thead>
                          <tbody>
                          <?php
						  
                          foreach($jobs_report_panel_data as $p_data){
						  
						  ?>
                            <tr>
                                <td><?php echo $p_data['title'] ?></td>
                                <td class="center-text"><?php echo $p_data['added']; ?></td>
                                <td class="center-text"><?php echo $p_data['completed']; ?></td>
                                <td class="center-text"><?php echo $p_data['open']; ?></td>
                                <td class="center-text"><?php echo $p_data['contract']; ?></td>
                                <td class="center-text"><?php echo $p_data['hours']; ?></td>
                            </tr>
                          <?php
						  }
						  ?>
                          </tbody>
                        </table>
                    </div>
                    <div class="col-sm-12">
                    	<p class="text-left">
							Of the ones "Completed" = <a href="/"><?php echo $job_totals['complete_total']; ?></a><br />
                            How many have been invoiced = <a href="/"><?php echo $job_totals['jobs_invoiced']; ?></a><br />
                            How many have not been invoiced = <a href="/"><?php echo $job_totals['jobs_not_invoiced']; ?></a>
                        </p>
                    </div>
                    <input type="hidden" name="selected_month" id="selected_month" value="<?php echo $selection_array['month']; ?>" />
                    <input type="hidden" name="selected_year" id="selected_year" value="<?php echo $selection_array['year']; ?>" />