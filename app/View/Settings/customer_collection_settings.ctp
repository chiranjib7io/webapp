<script>
function select_market(bid) {
    var url = '<?php echo $this->Html->url('ajax_market_list/');?>'+bid;
    $.post( url, function( data ) {
      $( "#kendralist" ).html( data );
      
    });
}
function select_customer(bid) {
    var url = '<?php echo $this->Html->url('ajax_customer_list/');?>'+bid;
    $.post( url, function( data ) {
      $( "#custlist" ).html( data );
      
    });
}
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Change Customer Loan Collection Date
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Customer Collection Settings</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
 
              <div class="box box-danger col-xs-12" style="padding-top:20px; padding-bottom:20px;">
					<span style="color:red"><?php echo $this->Session->flash(); ?></span>
                    <div class="box-header with-border" style="border-top:none; padding-top:20px;padding-bottom:20px;">
							 
						<form action="" method="post" accept-charset="utf-8" enctype="multipart/form-data">		
                                <div class="form-group col-md-6 col-sm-12">
                                <label for="Branch">Branch</label>
								<?php echo $this->Form->input("branch_id", array(
    										'options' => $branch_list,
    										'default' => $cust_data['Customer']['branch_id'],
    										'label'=>false,
                                            'empty' => 'Select Branch',
                                            
                                            'required' => 'required',
    										'class'	=> 'form-control',
                                            'onChange'=>"select_market(this.value)"
    									));
								?>
								</div>
								<div class="form-group col-md-6 col-sm-12" id="kendralist">	
                                <label for="Kendra">Market</label>
								<?php echo $this->Form->input("market_id", array(
										'options' => $market_list,
										'default' => $cust_data['Customer']['market_id'],
										'label'=>false,
                                        'empty' => 'Select Market',
                                        'required' => 'required',
										'class'	=> 'form-control',
                                        'onChange'=>"select_customer(this.value)"
										
									));
                                if(!empty($branch_data)){
                                    echo $this->Form->input('UploadReport.region_id', array('type' => 'hidden','value'=>$branch_data['Region']['id'],'label'=>false));
                                    echo $this->Form->input('UploadReport.branch_id', array('type' => 'hidden','value'=>$branch_data['Branch']['id'],'label'=>false));
								}
                                ?>
								</div>
                                
                                <div class="form-group col-md-6 col-sm-12" id="custlist">	
                                <label for="Kendra">Customer</label>
								<?php echo $this->Form->input("customer_id", array(
										'options' => $cust_list,
										'default' => $cust_data['Customer']['id'],
										'label'=>false,
                                        'empty' => 'Select Customer',
                                        'required' => 'required',
										'class'	=> 'form-control'
										
									));
                                if(!empty($branch_data)){
                                    echo $this->Form->input('UploadReport.region_id', array('type' => 'hidden','value'=>$branch_data['Region']['id'],'label'=>false));
                                    echo $this->Form->input('UploadReport.branch_id', array('type' => 'hidden','value'=>$branch_data['Branch']['id'],'label'=>false));
								}
                                ?>
								</div>
                                
                                <div class="form-group col-md-6 col-sm-12" id="kendralist">
                                <label for="Kendra">Payment Date Start From</label>	
								<input type="date" name="payment_change_date" class="form-control"  required="required"/>
								
								</div>
                                <div class="form-group col-md-6 col-sm-12" id="kendralist">
                                <label for="Kendra">Change all Collection date</label>	
								<?php echo $this->Form->input("all_date", array(
    										'options' => array('1'=>'Yes','0'=>'No'),
    										'default' => '1',
    										'label'=>false,
    										'class'	=> 'form-control'
    									));
								?>
								
								</div>
								<div class="input-group col-xs-12" style="text-align:center">
									<input type="submit" class="btn btn-success" value="Submit" >
								</div>
								<?php echo $this->Form->end(); ?> 
                        </div><!-- /.box-header -->
                
              </div><!-- /.box -->

            </div>
          </div><!-- /.row -->
          
          <div class="row">
            <div class="col-xs-12">
              
            </div><!-- /.col -->
          </div><!-- /.row -->

        </section><!-- /.content -->
        
        