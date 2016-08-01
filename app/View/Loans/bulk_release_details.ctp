<script>
function change_divclass(id){
    var NAME = document.getElementById("custname"+id);
    var currentClass = NAME.className;
    if (currentClass == "client-box-success") { // Check the current class name
        NAME.className = "client-box-due";   // Set other class name
    } else {
        NAME.className = "client-box-success";  // Otherwise, use `second_name`
    }
}
</script>

<div class="container">
            <!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                Kendra Loan Release Sheet on <?=date("d-M-Y")?>
              </h1>
            </section>

        	<!-- Main content -->
            <section class="content">
            <?PHP echo $this->Form->create('Loan', array('method' => 'post')); ?>
              <!-- Small boxes (Stat box) -->
              <div class="row">
              	<div class="col-md-12">
                	
                    <div class="box box-danger col-xs-12" style="padding-top:20px; padding-bottom:20px;">
                        <div class="box-header with-border">
                          <h3 class="box-title"><?php echo $kendra_data['Kendra']['kendra_name']; ?> || <?php echo $kendra_data['Organization']['organization_name']; ?></h3>
                        </div><!-- /.box-header -->
                        
                     <?php
                     $total_amount=0;
                     foreach($loan_data as $k=>$data_row)
                     {
                        $total_amount += $data_row['Loan']['loan_principal'];
                     ?>   
                        <div class="col-lg-2 col-md-4 sm-6" style="padding-top:10px;">
                          <!-- small box client-box-due-->
                          <div id="custname<?=$data_row['Loan']['id']?>" class="client-box-success">
                            <div class="inner">
                              <p><?=$data_row['Customer']['cust_fname']?> <?=$data_row['Customer']['cust_lname']?></p>
                              <h3><?=$this->Number->currency($data_row['Loan']['loan_principal'],'',array('places'=>0))?></h3>
                            </div>
                            <div class="icon">
                              <input id="box<?=$data_row['Loan']['id']?>" type="checkbox" name="loan_arr[<?=$data_row['Loan']['id']?>]" value="<?=$data_row['Loan']['loan_principal']?>" checked="checked" />
                              <label for="box<?=$data_row['Loan']['id']?>" onclick="change_divclass('<?=$data_row['Loan']['id']?>')" ></label>
                            </div>
                          </div>
                        </div><!-- ./col -->
                     <?php
                     }
                     ?>   
                        
                        
                    </div>
				</div>                
              </div><!-- /.row -->
              
              <div class="row">
                  <div class="col-md-5 col-sm-12">
                      <!-- TABLE: LATEST ORDERS -->
                      <div class="box box-success">
                        <div class="box-header with-border">
                          <h2 class="box-title" style="text-align:center;display:block;line-height:22px;">SUMMARY</h2>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                          <div class="table-responsive">
                            <table class="table no-margin">
                              <thead>
                                <tr>
                                  <td>Total Loan in Market (Kendra)</td>
                                  <td><?php echo $this->Number->currency($kendra_data['Kendra']['total_loan_in_market'],'',array('places'=>0)); ?></td>
                                </tr>
                                
                                <tr>
                                  <td>Installment Start date</td>
                                  <td><input type="date" class="form-control" id="loan_dateout" name="data[insta_start]" placeholder="Enter Installment Start Date" required="required"></td>
                                </tr>
                              </thead>
                            </table>
                          </div><!-- /.table-responsive -->
                        </div><!-- /.box-body -->
                      </div><!-- /.box -->
                  </div><!-- /.col -->
                  <div class="col-md-7 col-sm-12">
                  	  <div class="box box-warning">
                        <div class="box-header with-border">
                          <h3 class="box-title" style="text-align:center;display:block;line-height:22px;">TOTAL LOAN AMOUNT</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                          	<h1 class="heading" align="center" id="totloan"><?=$this->Number->currency($total_amount,'Rs.',array('places'=>0))?></h1>
                        </div><!-- /.box-body -->
                      </div><!-- /.box -->
                  </div><!-- /.col -->              
              </div><!-- /.row -->
              <div class="row">
              	<div class="col-xs-12"> 
                    <div class="box no-border" style="padding-top:20px; padding-bottom:20px; float:left">   
                      <div class="col-xs-6">
                            <a href="javascript: window.history.back()"><button type="button" class="btn btn-danger btn-lg">Cancel</button></a>
                        </div>
                      <div class="col-xs-6">
                      
                            <?php echo $this->Form->input('Loan.loan_status_id', array('type' => 'hidden','value'=>3,'label'=>false)); ?>
                            <?php
                            if($total_amount>0){
                            ?>
                            <button type="submit" id="relbtn" class="btn btn-success btn-lg  pull-right">Release Loan</button>
                      <?php } ?>
                        </div>
                    </div>
                </div>
    		  </div>
              <?php echo $this->Form->end(); ?> 
            </section><!-- /.content -->
            
        </div> 