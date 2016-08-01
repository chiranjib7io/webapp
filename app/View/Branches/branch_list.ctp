            <!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                Branch List
                <!--<small class="text-green">Success Message</small>
                <small class="text-danger">Waring Message</small>-->
              </h1>
              <ol class="breadcrumb">
              <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Branch List</li>
              </ol>
            </section>
            
            <!-- Main content -->
            <section class="content">
              <div class="row">
              	<div class="col-xs-12">
                	<div class="box no-border">
                        <!--<div class="box-header no-border">
                          <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">STATISTICS</h2>
                        </div>--><!-- /.box-header -->
                        <div class="box-body" style="padding-top:15px; padding-bottom:15px;">
                          <div class="table-responsive">
                            <table class="table no-margin kendra_list">
                              <thead>
                                <tr>
                                  <th>Branch Name</th>
                                  <th>Total Market</th>
                                  <th>Total Member</th>
                                  <th>Contact Number</th>
                                  <th>Email ID</th>
                                  <th>Branch Manager</th>
                                  <th>Market List</th>
                                  <th>Loan Details</th>
                                  <th></th>
                                </tr>
                              </thead>
                              <tbody>
							  <?php
								 if(!empty($branch_list)){
									foreach($branch_list as $kbranch=>$branch){
										//$total_kendra=count($branch['Market']);
										//$total_member=count($branch['Customer']);
										$branch_count=$this->Slt->count_branch_data($branch['Branch']['id']);
										//pr($branch_count);die;
										$total_kendra=$branch_count['total_market'];
										$total_member=$branch_count['total_customer'];
										
										$kendra_link='N/A';
										$loan_link='N/A';
										
										if($total_kendra!=0){
											$ken_link='/kendra_list/'. $branch['Branch']['id'];
											$kendra_link='<a href="'. $this->Html->url($ken_link) .'"> Market List </a>';
										}
										if($total_member!=0){
											$lo_link='/branch_loan_details/'. $branch['Branch']['id'];
											$loan_link='<a href="'. $this->Html->url($lo_link) .'"> Loan Details </a>';
										}
										$branch_link='/edit_branch/'. $branch['Branch']['id'];
										$edit_link='<a href="'. $this->Html->url($branch_link) .'"> Edit </a>';
							  ?>
                                  <tr>
                                      <td><?= $branch['Branch']['branch_name'] ?></td>
                                      <td><?= $total_kendra ?></td>
                                      <td><?= $total_member ?></td>
                                      <td><?= $branch['Branch']['phone_no'] ?></td>
                                      <td><?= $branch['Branch']['contact_email'] ?></td>
                                      <td><?= $branch['User']['first_name'] ?> <?= $branch['User']['last_name'] ?></td>
                                      <td><?= $kendra_link ?></td>
                                      <td><?= $loan_link ?></td>
									  <td><?= $edit_link ?></td>
                                  </tr>
                                   <?php
										}
									}else{
									?>    
										<tr>
											<td colspan="8">No Result Found</td>
										</tr>
									<?php
									}
									?>
									
                              </tbody>
                            </table>
                          </div><!-- /.table-responsive -->
                        </div><!-- /.box-body -->
                      </div><!-- /.box -->
                </div><!-- /.col -->
              </div><!-- /.row -->
            
            </section><!-- /.content -->