           
            <!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                Loan Officer Summary
              </h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Loan Officer Summary</li>
              </ol>
            </section>
            
            <!-- Main content -->
            <section class="content">
              <div class="row"> 
                  <div class="col-xs-12">
				  	<div class="row">
                        
                  		<div class="col-md-4 col-sm-12">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">No. of Officer <br> &nbsp;</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <h3 align="center"><?=$lo_officer_summary['no_of_officer']?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                        </div><!-- /.col -->
                  		<div class="col-md-4 col-sm-12">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Average Loan amount <br>per officer</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <h3 align="center"><?=$this->Number->currency($lo_officer_summary['avg_loan_per_officer'], '',array('places'=>0))?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                        </div><!-- /.col -->
                  		<div class="col-md-4 col-sm-12">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Average Customer <br>per officer</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <h3 align="center"><?=$lo_officer_summary['avg_customer_per_officer']?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                        </div><!-- /.col -->
                        <div class="clear"></div>
                        
                  		<div class="col-md-6 col-sm-12">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Avg. collection amount per officer</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <h3 align="center"><?=$this->Number->currency($lo_officer_summary['avg_collection_per_officer'], '',array('places'=>0))?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                        </div><!-- /.col -->
                  		<div class="col-md-6 col-sm-12">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Avg. New Customer per officer</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <h3 align="center"><?=$lo_officer_summary['avg_new_customer_per_officer']?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                        </div><!-- /.col -->
                        <div class="clear"></div>
                        
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-body">
                                  <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                      <tr>
                                      	<th>Loan Officer Name</th>
                                        <th>Branch Name</th>
                                        <th>No. of Kendra</th>
                                        <th>No. of Customer</th>
                                        <th>No. of New Customer</th>
                                        <th>Total Loan Amount</th>
                                        <th>Collection Percentage</th>
                                        <th>Loan in Market</th>
                                        <th>Details</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                               <?php
                               foreach($lo_officer_array as $lo_row) 
                               {
                                ?>
                                    
                                      <tr>
                                      	<td><?=$lo_row['User']['first_name']." ".$lo_row['User']['last_name']?></td>
                                        <td><?=$lo_row['Branch']['branch_name']?></td>
                                        <td><?=$lo_row['kendra_no']?></td>
                                        <td><?=$lo_row['customer_no']?></td>
                                        <td><?=$lo_row['new_customer_no']?></td>
                                        <td><?=$this->Number->currency($lo_row['total_loan'], '',array('places'=>0))?></td>
                                        <td><?=$lo_row['collection_percentage']?> %</td>
                                        <td><?=$this->Number->currency($lo_row['loan_in_market'], '',array('places'=>0))?></td>
                                        <td><a href="<?= $this->Html->url('/loan_officer_details/'.$lo_row['User']['id']) ?>" class="btn btn-sm btn-success">Details</a></td>
                                      </tr>
                                <?php
                                }
                                ?>
                                    </tbody>
                                    
                                    
                                  </table>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                          </div><!-- /.col -->
                   
                	</div>          
                </div><!-- /.col -->            
              </div><!-- /.row -->
            
            </section><!-- /.content -->
