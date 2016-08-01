<script type="text/javascript">
function filterGlobal () {
    $('#customerListingTable').DataTable().search(
        $('#global_filter').val()
    ).draw();
}
 
function filterColumn ( i ) {
    $('#customerListingTable').DataTable().column( i ).search(
        $('#col'+i+'_filter').val()
    ).draw();
}
  
function selectFilterColumn ( i , val) {
    $('#customerListingTable').DataTable().column( i ).search( val ).draw();
} 
    
    
    
$(function () {
    
    
    var table = $('#customerListingTable').DataTable( {
                    "scrollX": false,
                    "jQueryUI": false,
                    "ordering": true,
                    "info":     true 
                });// table end
                
    //***************************SEARCH SECTION************************************************************/
    			$('input.global_filter').on( 'keyup click', function () {
    				filterGlobal();
    			} );
    		 
    			$('input.column_filter').on( 'keyup click', function () {
    				filterColumn( $(this).attr('data-column') );
    			} );
    			
    			$('.select_filter').on( 'change', function () {
    				selectFilterColumn($(this).attr('data-column') , this.value);			  
    			});       
                
    //*****************************************************************************************************/                        
    
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
            Search Active Customer
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Search Active Customer</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
 
              <div class="box box-danger col-xs-12" style="padding-top:20px; padding-bottom:20px;">
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
                            <option value="<?=$branch['Branch']['branch_name']?>" data-id="<?=$branch['Branch']['id']?>"><?=$branch['Branch']['branch_name']?></option>
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
              <div class="box col-xs-12" id="customerListingTableDiv" style="display: none;">
                <div class="box-header">
                  <h3 class="box-title">Customer List</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                
                    <table id="customerListingTable" class="table table-bordered ">
                        <thead>
                            <tr>
                                <th>Customer Id</th>
                                <th>Customer Name</th>
                                <th>Branch Name</th>
                                <th>Kendra Name</th>                                
                                <th>Member Joining Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(!empty($customers_data)){
                            foreach($customers_data as $k2=>$customer){
                        ?>
                            <tr>
								<?php 
										$customer_url= $this->base.'/customer_details/'.$customer['Customer']['id'];
                                    ?>
							
                                <td><a href="<?=$customer_url?>" style="color:inherit"><?="#".$customer['Customer']['id']?></a></td>
                                <td><?=$customer['Customer']['cust_fname'].'&nbsp;'.$customer['Customer']['cust_lname']?></td>
                                <td><?=$customer['Branch']['branch_name']?></td>
                                <td><?=$customer['Kendra']['kendra_name'].' ( '.$customer['Kendra']['kendra_number'].' ) '?></td>                                
                                <td><?=date('d-M-Y',strtotime($customer['Customer']['created_on']))?></td>
                            </tr>
                        <?php
                            }
                        }else{
                        ?>    
                            <tr>
                                <td colspan="5">No Result Found</td>
                            </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                    
                    
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->

        </section><!-- /.content -->
        
        