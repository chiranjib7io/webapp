<script>
function load_customer(kid){
    var url = '<?php echo $this->Html->url('ajax_customer_list_saving/');?>'+kid;
    $.post( url, function( data ) {
      $( "#customer" ).html( data );
      
      $( "#custname" ).html( '<strong>Customer Name:</strong>');
      $( "#loan_number" ).html( '<strong>Loan Number:</strong>');
      $( "#last_paid" ).html( '<strong>Last paid date:</strong>');
      $( "#realize" ).html( '<strong>Total Amount Realize:</strong>');
      $( "#prepay_amt" ).html( '<strong class="text-green">Total Prepayment Amount: </strong>');
      $( "#repay_total" ).html( '<strong>Loan Repay Total:</strong>');
      $( "#loanid" ).val('');
      $( "#prepayamt" ).val('');
      $('#paybtn').hide();
    });
}
</script>

<!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                Create Saving
                <!--<small class="text-green">Success Message</small>
                <small class="text-danger">Waring Message</small>-->
              </h1>
              <ol class="breadcrumb">
               <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Create Loan</li>
              </ol>
            </section>
            
            <!-- Main content -->
            <section class="content">
              <div class="row">
                <div class="col-xs-12">
                	<div class="box box-primary" style="float:left">
                        <div class="box-header with-border" style="border-top:none; padding-top:20px;padding-bottom:20px;">
                          <?PHP echo $this->Form->create('Loan', array('method' => 'post')); ?>
                          		                            
                            	<div class="input select form-group col-md-4 col-sm-12">
                                	<label>Select Market</label>
                                    <?php echo $this->Form->input("market_id", array(
                                        'options' => $market_list,
                                        'default' => '',
                                        'empty' => 'Select Market',
                                        'required' => 'required',
                                        'onChange'=>'load_customer(this.value)',
                                        'label'=>false,
                      		            'class'	=> 'form-control'
                                    ));
                                
                                    ?>
                                </div>
                               <div class="input select form-group col-md-4 col-sm-12" id="customer">
                                    <label>Select Customer</label>
                               		<select name="" class="form-control" required="required" id="">
                                    <option id="0">Select Customer</option>
                                    </select>
                                </div> 
                               <div class="input select form-group col-md-4 col-sm-12">
									</br>
									<label>&nbsp;</label>
                                    <input type="submit" class="btn btn-sm bg-navy" value="Create Saving">
                                </div>                          
                               <?php echo $this->Form->end(); ?> 
                            
                        </div><!-- /.box-header -->
                    </div>
                </div>
              </div><!-- /.row -->
            
            </section><!-- /.content -->