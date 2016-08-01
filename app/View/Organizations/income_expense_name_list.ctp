<script type="text/javascript" language="javascript" >
			$(document).ready(function() {
				var dataTable = $('#customerListingTable').DataTable( {
					"processing": true,
					"serverSide": false,
					
				} );
				
				
			} );
</script>


<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Income and Expenditure Name List
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Income and Expenditure Name List</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
 
              <a href="<?= $this->Html->url('/organizations/save_ledger/') ?>"><button type="button" class="btn btn-primary btn-lg" >Add Ledger name</button></a>

            </div>
          </div><!-- /.row -->
          
          <div class="row">
            <div class="col-xs-12">
              <div class="box col-xs-12" id="customerListingTableDiv">
                <div class="box-header">
                  <h3 class="box-title"></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                
                    <table id="customerListingTable" class="table table-bordered ">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Ledger Name</th>
                                <th>Type</th>
                                <th>Ledger Group Name</th>
                                <th>Status</th>
                                <th>Action</th>                                
                                
                            </tr>
                        </thead>
                        <tbody>
                    <?php
                    foreach($ldgr_list as $k=>$ldgr){    
                    ?>
                            <tr>
                                <td><?=$k+1?></td>
                                <td><?=$ldgr['AccountLedger']['ledger_name']?></td>
                                <td><?=($ldgr['AccountLedger']['account_type'])?'Income':'Expense'?></td>
                                <td><?=$ldgr['AccountLedgerGroup']['ledger_group_name']?></td>
                                <td><?=($ldgr['AccountLedger']['status'])?'Active':'Inactive'?></td>
                                <td><a href="<?= $this->Html->url('/organizations/save_ledger/'.$ldgr['AccountLedger']['id']) ?>">Edit</a></td>
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
        
        