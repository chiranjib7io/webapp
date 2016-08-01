    <link href="<?php //echo $this->webroot; ?>asset/plugins/datepicker/bootstrap-combined.min.css" rel="stylesheet"/>
    <script src="<?php echo $this->webroot; ?>asset/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
	<link href="<?php echo $this->webroot; ?>asset/plugins/datepicker/datepicker3.css" rel="stylesheet"/>
	<script type="text/javascript">
		
      $(function(){
          $("#datepicker").datepicker({
    		     format: 'dd-mm-yyyy'
    		});
        
          
      });     
    
    function get_ledger(typ_id){
        $.get("<?=$this->Html->url('ajax_ledger_list/');?>"+typ_id,function(data){
            
            $("#ledger_name").html(data);
            
        });  
    }
    
    function get_credit_officer(brnch_id){
        $.get("<?=$this->Html->url('ajax_credit_officer_list/');?>"+brnch_id,function(data){
            
            $("#credit_officer").html(data);
            
        });  
    }
	</script>
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Daily Income/Expenditure
			<!--<small class="text-green">Success Message</small>
			<small class="text-danger">Waring Message</small>-->
		</h1>
		<ol class="breadcrumb">
			<li>
				<a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a>
			</li>
			<li class="active">
				Daily Income/Expenditure
			</li>
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
					<?php echo $this->Form->create(); ?>
						<div class="box-body col-md-3 col-sm-4 ">
							<div class="form-group">
								<label for="Name">
									Select Branch
								</label>
								<?=$this->Form->input('', array('type' => 'select','name'=>'branch_id' ,'options' => $bm_list, 'onchange'=>'get_credit_officer(this.value)' , 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'default'=>'',
									'empty' => 'Select Branch'));?>
							</div>
						</div>
                        
                        <div class="box-body col-md-3 col-sm-4 ">
							<div class="form-group">
								<label for="Name">
									Select Credit Officer
								</label>
								<?=$this->Form->input('', array('type' => 'select','id'=>'credit_officer','name'=>'user_id' ,'options' => array(), 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'default'=>'',
									'empty' => 'Select Credit Officer'));?>
							</div>
						</div>
						
                        <div class="box-body col-md-3 col-sm-4 ">
							<div class="form-group">
								<label>
									Amount Type
								</label>
								<?=$this->Form->input('', array('type' => 'select','name'=>'amount_type','onchange'=>'get_ledger(this.value)' ,'options' => array(0=>'Expense',1=>'Income'), 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'default'=>'',
									'empty' => 'Select Income/Expense'));?>
							</div>
						</div>
                        
                        <div class="box-body col-md-3 col-sm-4 ">
							<div class="form-group">
								<label>
									Income/Expenditure Name
								</label>
								<?=$this->Form->input('', array('type' => 'select','id'=>'ledger_name','name'=>'account_ledger_id' , 'options' => array(), 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'default'=>'',
									'empty' => 'Select Account Ledger'));?>
							</div>
						</div>
                        
                        
                        <div class="box-body col-md-3 col-sm-4 ">
							<div class="form-group">
								<label>
									Amount
								</label>
								<input id="amt" type="number" class="form-control" name="amount" required="required" value="" min="1" />
							</div>
						</div>
                        
                        			
									
						<div class="box-body col-md-3 col-sm-4 ">
							<div class="form-group">
								<label>
									Date
								</label>
								<input id="datepicker" type="text" class="form-control" readonly="readonly" name="date" required="required" value="<?=date("d-m-Y ")?>" />
							</div>
						</div>
                                    
						<div class="box-body col-md-3 col-sm-4 ">
							<div class="form-group">
                                <br />
								<button type="submit" class=" btn btn-primary btn-lg">
									Submit
								</button>
							</div>
						</div>
					</form>
				<!-- /.box-body -->
				</div>
			<!-- /.box -->
			</div>
		</div>
	<!-- /.row -->
	</section>
<!-- /.content -->