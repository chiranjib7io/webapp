<section class="content" style="background:#ecf0f5;width:100%;display:block;overflow: auto;">
	<div class="col-xs-12">
		<!-- Content Header (Page header) -->
		<section class="content-header">
		  <h1>
			Overdue List
		  </h1>
		  <ol class="breadcrumb">
			<li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Overdue List</li>
		  </ol>
		</section>
	
		<!-- Main content -->
		<section class="content">
			<div class="row">
			
				<div class="col-sm-6">
					<div class="chart_box">
						<div class="summery_box">
							<div class="summery_box_header">
							<?php
								$all_count=count($loan_overdue_all);
								$thirty_count=count($loan_overdue_thirty);
								$sixty_count=count($loan_overdue_sixty);
								$ninety_count=count($loan_overdue_ninety);
								
							?>
								<h2 class="summery_box_title" style="text-align:center;display:block;line-height:22px;">Total Overdue List (<?= $all_count ?>)</h2>
							</div><!-- /.summery_box_header -->
							<div class="summery_box_body">
								<div class="table-responsive">
									<table class="table no-margin">
										<thead>
											<tr>
												<td>Name</td>
												<td>Branch</td>
												<td>Group</td>
												<td>Amount</td>
												<td>Date</td>
											</tr>
										</thead>
										<tbody>
										<!-- This table is Start from Here -->
										<?php
											if($all_count>0){
												foreach ($loan_overdue_all as $k_all => $data_all){
										?>
										<tr>
												<td><?= $data_all['Customer']['cust_fname'].' '.$data_all['Customer']['cust_lname']?> </td>
												<td><?= $data_all['Branch']['branch_name'] ?></td>
												<td><?= $data_all['Kendra']['kendra_name'] ?></td>
												<td><?= $data_all['LoanTransaction']['total_installment'] ?></td>
												<!--<td><?= date("d-m-Y", strtotime($data_all['LoanTransaction']['insta_due_on'])) ?></td>-->
												<td><?php echo $this->Slt->get_day_name(strtotime($data_all['LoanTransaction']['insta_due_on'])); ?></td> 
											</tr>
												
										<?php
												}
												} else {
										?>
										<tr>
												<td colspan="5">No Data Found</td>
										</tr>
										<?php
												}								
										?>
										
											
											
										
										<!-- This table is  End Here -->
										</tbody>
									</table>
								</div><!-- /.table-responsive -->
							</div><!-- /.summery_box_body -->
						</div><!-- /.box -->
					</div>
				</div>
				
				<div class="col-sm-6">
					<div class="chart_box" style="margin-bottom:10px;">
						<div class="summery_box">
							<div class="summery_box_header">
								<h2 class="summery_box_title" style="text-align:center;display:block;line-height:22px;">30 days Overdue List (<?= $thirty_count ?>)</h2>
							</div><!-- /.summery_box_header -->
							<div class="summery_box_body">
								<div class="table-responsive">
									<table class="table no-margin">
										<thead>
											<tr>
												<td>Name</td>
												<td>Branch</td>
												<td>Group</td>
												<td>Amount</td>
											</tr>
										</thead>
										<tbody>
										<!-- This table is Start from Here -->
											<?php
											if($thirty_count>0){
												foreach ($loan_overdue_thirty as $k_thirty => $data_thirty){
										?>
										<tr>
												<td><?= $data_thirty['Customer']['cust_fname'].' '.$data_thirty['Customer']['cust_lname']?> </td>
												<td><?= $data_thirty['Branch']['branch_name'] ?></td>
												<td><?= $data_thirty['Kendra']['kendra_name'] ?></td>
												<td><?= $data_thirty['LoanTransaction']['total_installment'] ?></td>
											</tr>
												
										<?php
												}
												} else {
										?>
										<tr>
												<td colspan="4">No Data Found</td>
										</tr>
										<?php
												}								
										?>
										<!-- This table is  End Here -->
										</tbody>
									</table>
								</div><!-- /.table-responsive -->
							</div><!-- /.summery_box_body -->
						</div><!-- /.box -->
					</div>
					
					<div class="chart_box" style="margin-bottom:10px;">
						<div class="summery_box">
							<div class="summery_box_header">
								<h2 class="summery_box_title" style="text-align:center;display:block;line-height:22px;">60 days Overdue List (<?= $sixty_count ?>)</h2>
							</div><!-- /.summery_box_header -->
							<div class="summery_box_body">
								<div class="table-responsive">
									<table class="table no-margin">
										<thead>
											<tr>
												<td>Name</td>
												<td>Branch</td>
												<td>Group</td>
												<td>Amount</td>
											</tr>
										</thead>
										<tbody>
										<!-- This table is Start from Here -->
											<?php
											if($sixty_count>0){
												foreach ($loan_overdue_sixty as $k_sixty => $data_sixty){
										?>
										<tr>
												<td><?= $data_sixty['Customer']['cust_fname'].' '.$data_sixty['Customer']['cust_lname']?> </td>
												<td><?= $data_sixty['Branch']['branch_name'] ?></td>
												<td><?= $data_sixty['Kendra']['kendra_name'] ?></td>
												<td><?= $data_sixty['LoanTransaction']['total_installment'] ?></td>
											</tr>
												
										<?php
												}
												} else {
										?>
										<tr>
												<td colspan="4">No Data Found</td>
										</tr>
										<?php
												}								
										?>
										<!-- This table is  End Here -->
										</tbody>
									</table>
								</div><!-- /.table-responsive -->
							</div><!-- /.summery_box_body -->
						</div><!-- /.box -->
					</div>
					
					<div class="chart_box" style="margin-bottom:10px;">
						<div class="summery_box">
							<div class="summery_box_header">
								<h2 class="summery_box_title" style="text-align:center;display:block;line-height:22px;">90 days Overdue List (<?= $ninety_count ?>)</h2>
							</div><!-- /.summery_box_header -->
							<div class="summery_box_body">
								<div class="table-responsive">
									<table class="table no-margin">
										<thead>
											<tr>
												<td>Name</td>
												<td>Branch</td>
												<td>Group</td>
												<td>Amount</td>
											</tr>
										</thead>
										<tbody>
										<!-- This table is Start from Here -->
											<?php
											if($ninety_count>0){
												foreach ($loan_overdue_ninety as $k_ninety => $data_ninety){
										?>
										<tr>
												<td><?= $data_ninety['Customer']['cust_fname'].' '.$data_ninety['Customer']['cust_lname']?> </td>
												<td><?= $data_ninety['Branch']['branch_name'] ?></td>
												<td><?= $data_ninety['Kendra']['kendra_name'] ?></td>
												<td><?= $data_ninety['LoanTransaction']['total_installment'] ?></td>
											</tr>
												
										<?php
												}
												} else {
										?>
										<tr>
												<td colspan="4">No Data Found</td>
										</tr>
										<?php
												}								
										?>
										<!-- This table is  End Here -->
										</tbody>
									</table>
								</div><!-- /.table-responsive -->
							</div><!-- /.summery_box_body -->
						</div><!-- /.box -->
					</div>
				
				</div>	
						
			</div>
		</section><!-- /.content -->
			
	</div> 
</div>	