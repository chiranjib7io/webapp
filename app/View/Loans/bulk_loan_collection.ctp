<script>
function load_insta(kid){
    var url = '<?php echo $this->Html->url('ajax_duedate_list/');?>'+kid;
    $.post( url, function( data ) {
      $( "#duedate" ).html( data );
    });
}
</script>

            <!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                Bulk Loan Collection
                <!--<small class="text-green">Success Message</small>
                <small class="text-danger">Waring Message</small>-->
              </h1>
              <ol class="breadcrumb">
               <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Bulk Loan Collection</li>
              </ol>
            </section>
            
            <!-- Main content -->
            <section class="content">
              <div class="row">
                <div class="col-md-12">
                
                	<div class="box box-danger col-xs-12" style="padding-top:20px; padding-bottom:20px;">
                        <div class="box-header">
                          <h3 class="box-title">Select your Kendra for Loan Collection</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body col-md-12">
                        <?PHP echo $this->Form->create('Loan', array('method' => 'post')); ?>	
                			<div class="col-md-6 col-sm-12"> 	 
                              <div class="form-group">
                                  <label>Kendra Name</label>
                                  <?php echo $this->Form->input("Loan.kendra_id", array(
                                        'options' => $kendra_list,
                                        'default' => '',
                                        'onChange'=>'load_insta(this.value)',
                                        'label'=>false,
                      		            'class'	=> 'form-control'
                                    ));
                                
                                    ?>
                                  
                              </div><!-- /.form group -->
                          	</div>
                            <div class="col-md-6 col-sm-12"> 
                              <div class="form-group" id="duedate">
                                  <label for="CollectionDate">Collection Date</label>
                                  <?php
                                  echo $this->Form->input("Loan.insta_due_on", array(
                                        'options' => $loan_payment_list,
                                        'default' => '',
                                        'label'=>false,
                                        'required' => 'required',
                        	            'class'	=> 'form-control'
                                    ));
                                  ?>
                              </div><!-- /.form group -->
                           </div>
                           <div class="col-md-4"> 
                           	  <div class="form-group">
                            	<button type="submit" class="btn btn-primary btn-md" style="margin-top:8px;">Payment</button>
                              </div>
                           </div>
                          <?php echo $this->Form->end(); ?>
                        </div><!-- /.box-body -->
                      </div>            
                </div>
              </div><!-- /.row -->
            
            </section><!-- /.content -->
            
