<script type="text/javascript">  
$(function () {
    
    
    var table = $('#kendraListingTable').DataTable( {
                    "scrollX": false,
                    "jQueryUI": false,
                    "ordering": true,
                    "info":     true,
                   // "ajax": "<?=$this->base.'/kendras/ajax_kendra_list/'?>",
                    "deferRender": true
                });// table end

});
     
</script>
            <!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                Region List
                <!--<small class="text-green">Success Message</small>
                <small class="text-danger">Waring Message</small>-->
              </h1>
              <ol class="breadcrumb">
                <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Region List</li>
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
                            <table class="table table-bordered" id="kendraListingTable">
                              <thead>
                                <tr>
                                    <th>#</th>
                                   
                                    <th>Region Name</th>
                                    <th>Manager Name</th>
                                    <th>Total Branch</th>
                                    <th>Total Market</th>
                                    <th>Total Customer</th>
                                    
                                    <th></th>
                                </tr>
                              </thead>
                              <tbody>
							  <?php
                              
								 if(!empty($region_data)){
									foreach($region_data as $k=>$region){	
										$kendra_link='/save_region/'. $region['Region']['id'];
										$edit_link='<a href="'. $this->Html->url($kendra_link) .'"> Edit </a>';
                                        $count_data = $this->Slt->count_region_data($region['Region']['id']);
                                        //pr($count_data);die;
                                        
							  ?>
                                  <tr>
                                    <td><?=$k+1?></td>
                                    
                                    <td><?=$region['Region']['region_name']?></td>
                                    <td><?=$region['User']['fullname']?></td>
                                    <td><?=$count_data['total_branch']?></td>
                                    <td><?=$count_data['total_customer']?></td>
                                    <td><?=$count_data['total_market']?></td>
                                    
									<td><?= $edit_link ?></td>
								  </tr>
                                   <?php
										}
									}else{
									?>    
										<tr>
											<td colspan="7">No Result Found</td>
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