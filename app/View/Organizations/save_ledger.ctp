
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Manage Account Ledger
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Manage Account Ledger</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
 
              <div class="box box-danger col-xs-12" style="padding-top:20px; padding-bottom:20px;">
                <?php echo $this->Form->create('organizations',array('action'=>'save_ledger',$ldgr_id)); ?>
				<?php echo $this->Form->input('AccountLedger.id', array('type' => 'hidden','label'=>false)); ?>		
						<span style="color:red"><?php echo $this->Session->flash(); ?></span>
                <div class="box-body col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="Name">Ledger Name</label>
                        <?php echo $this->Form->input('AccountLedger.ledger_name', array('type' => 'text','placeholder'=>'Enter Ledger Name','class'=>'form-control','label'=>false,'required'=>'required')); ?>
                        
                    </div><!-- /.form group -->
                    
                   <div class="form-group">
                      <label>Custom Entry</label>
                      <?php
						echo $this->Form->input('AccountLedger.custom_entry', array('type' => 'select', 'options' => array('1'=>'Yes','0'=>'No'), 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'empty' => 'Select Option'));
					  ?>
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label>Included in Profit & Loss?</label>
                      <?php
						echo $this->Form->input('AccountLedger.is_pl_report', array('type' => 'select', 'options' => array('1'=>'Yes','0'=>'No'), 'class'=>'form-control', 'label'=>false, 'required'=>'required'));
					  ?>
                    </div><!-- /.form group -->
				   

                </div><!-- /.box-body -->
                
                <div class="box-body col-md-6 col-sm-12">
                
                    <div class="form-group">
                      <label>Account Type</label>
                      <?php
						echo $this->Form->input('AccountLedger.account_type', array('type' => 'select', 'options' => array('0'=>'Expense','1'=>'Income'), 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'empty' => 'Select Option'));
					  ?>
                    </div><!-- /.form group -->
					
                    <div class="form-group">
                      <label>Status</label>
                      <?php
						echo $this->Form->input('AccountLedger.status', array('type' => 'select', 'options' => array('1'=>'Active','0'=>'Inactive'), 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'empty' => 'Select Status'));
					  ?>
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label>Ledger Group</label>
                      <?php
						echo $this->Form->input('AccountLedger.account_ledger_group_id', array('type' => 'select', 'options' => $ledger_groups, 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'empty' => 'Select Ledger Group'));
					  ?>
                    </div><!-- /.form group -->

                </div><!-- /.box-body -->
                
                <div class="box-body col-md-12 col-sm-12">
                    
                    <div class="box-footer" align="right">
                        <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                    </div>
                </div>

              </div><!-- /.box -->
			  
			  </form>

            </div>
          </div><!-- /.row -->

        </section><!-- /.content -->