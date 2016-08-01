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
                Kendra Loan Collection Sheet on <?=date("d-M-Y",strtotime($due_date))?>
              </h1>
            </section>

        	<!-- Main content -->
            <section class="content">
            <?PHP echo $this->Form->create('LoanTransaction', array('method' => 'post')); ?>
              <!-- Small boxes (Stat box) -->
              <div class="row">
              	<div class="col-md-12">
                	
                    <div class="box box-danger col-xs-12" style="padding-top:20px; padding-bottom:20px;">
                        <div class="box-header with-border">
                          <h3 class="box-title"><?php echo $kendra_data['Kendra']['kendra_name']; ?> || <?php echo $kendra_data['Organization']['organization_name']; ?></h3>
                        </div><!-- /.box-header -->
                        
                     <?php
                     $total_amount=0;
                     foreach($kendra_data['Transaction'] as $k=>$data_row)
                     {
                        //$total_amount += $data_row['LoanTransaction']['total_installment'];
                        $total_amount =0;
                     ?>   
                        <div class="col-lg-2 col-sm-4">
                          <!-- small box -->
                          <div class="client-box-success" id="custname<?=$data_row['Customer']['id']?>">
                            <div class="inner">
                              <p><?=$data_row['Customer']['cust_fname']?> <?=$data_row['Customer']['cust_lname']?></p>
                              <input type="text" class="form-control" id="box<?=$data_row['Customer']['id']?>" name="cust_arr[<?=$data_row['Customer']['id']?>]" value="0" required="required">
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
              	<div class="col-xs-12"> 
                    <div class="box no-border" style="padding-top:20px; padding-bottom:20px; float:left">   
                      <div class="col-xs-6">
                            <a href="javascript: window.history.back()"><button type="button" class="btn btn-danger btn-lg">Cancel</button></a>
                        </div>
                      <div class="col-xs-6">
                      
                            
                            <?php echo $this->Form->input('LoanTransaction.kendra_id', array('type' => 'hidden','value'=>$kendra_data['Kendra']['id'],'label'=>false)); ?>
                            <?php echo $this->Form->input('LoanTransaction.insta_due_on', array('type' => 'hidden','value'=>$due_date,'label'=>false)); ?>
                            <button type="submit" class="btn btn-success btn-lg  pull-right">Pay now</button>
                      
                        </div>
                    </div>
                </div>
    		  </div>
              <?php echo $this->Form->end(); ?> 
            </section><!-- /.content -->
            
        </div> 