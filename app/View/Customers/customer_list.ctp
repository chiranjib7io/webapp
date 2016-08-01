<script type="text/javascript" language="javascript" >
			$(document).ready(function() {
				var dataTable = $('#customerListingTable').DataTable( {
					"processing": true,
					"serverSide": true,
					"ajax":{
						url :"<?php echo $this->Html->url('/Customers/ajaxCustomerList/'); ?>", // json datasource
						type: "post",  // method  , by default get
						error: function(){  // error handling
							//$(".employee-grid-error").html("");
							//$("#customerListingTable").append('<tbody class="employee-grid-error"><tr><th colspan="12">No data found in the server</th></tr></tbody>');
							//$("#employee-grid_processing").css("display","none");
							
						}
					}
				} );
				
				
			} );
</script>

<script type="text/javascript">
 
    
$(function () {
    
    
                      
    
    $('#branchList').change(function() {                
        var id = $(this).find(':selected').data('id');
        var url= '<?php echo $this->Html->url('/Customers/ajaxKendraList/'); ?>'+id;
		$.post( url, function( data ) {
		 $( "#kendraList" ).html( data );
		});                    
    });
    
});
        
          
      
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Search Customer
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Search Customer</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
 
              <div class="box box-danger col-xs-12" style="padding-top:20px; padding-bottom:20px; display:none;">
                <!--<div class="box-header">
                  <h3 class="box-title">Search Customer Form</h3>
                </div>-->
                <div class="box-body col-md-4 col-sm-4">
                    <div class="form-group">
                        <label>Branch</label>
                        <select class="form-control select_filter" data-column="2" onchange="" id="branchList">
                            <option value="" data-id="">All Branches</option>
                            <?php
                            foreach($branches_data as $k=>$branch){
                            ?>
                            <option value="<?=$branch?>" data-id="<?=$k?>"><?=$branch?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div><!-- /.form group -->
                </div><!-- /.box-body -->
                
                <div class="box-body col-md-4 col-sm-4">
                    <div class="form-group">
                        <label>Kendra</label>
                        <select class="form-control select_filter" data-column="3" id="kendraList">
                            <option value="">Select Kendra</option>
                        </select>
                    </div><!-- /.form group -->
                </div><!-- /.box-body -->
                
                <div class="box-body col-md-4 col-sm-4">
                
                	<div class="form-group">
                      <label>Customer Name</label>
                      <input type="text" class="form-control column_filter" placeholder="Enter Customer Name" id="col1_filter" data-column="1">
                    </div><!-- /.form group -->

                </div><!-- /.box-body -->
                
                <div class="box-footer" align="center">
                    <button type="button" class="btn btn-primary btn-lg" onclick="$('#customerListingTableDiv').show()"><i class="fa fa-search"></i> Search</button> 
                </div>
                
              </div><!-- /.box -->

            </div>
          </div><!-- /.row -->
          
          <div class="row">
            <div class="col-xs-12">
              <div class="box col-xs-12" id="customerListingTableDiv">
                <div class="box-header">
                  <h3 class="box-title">Customer List</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                
                    <table id="customerListingTable" class="table table-bordered ">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer Name</th>
                                <th>Branch Name</th>
                                <th>Market/Area Name</th>
                                <th>Credit Officer Name</th>                                
                                <th>Loan Amount</th>
                                <th>Loan Maturity</th>
                                <th>Saving Amount</th>
                                <th>Saving Maturity</th>
                                <th>Overdue amount</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="12" class="dataTables_empty">Loading data from server...</td>
                            </tr>
                        </tbody>
                        
                    </table>
                    
                    
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->

        </section><!-- /.content -->
        
        