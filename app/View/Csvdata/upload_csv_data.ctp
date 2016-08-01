<script>
function select_kendra(bid) {
    var url = '<?php echo $this->Html->url('ajax_kendra_list/');?>'+bid;
    $.post( url, function( data ) {
      $( "#kendralist" ).html( data );
      
    });
}
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Upload CSV Data
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Upload CSV Data</li>
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
								<?php echo $this->Form->input("UploadReport.branch_id", array(
    										'options' => $branch_list,
    										'default' => $userData['branch_id'],
    										'label'=>false,
                                            'empty' => 'Select Branch',
                                            
                                            'required' => 'required',
    										'class'	=> 'form-control',
                                            'onChange'=>"select_kendra(this.value)"
    									));
								?>
								</div>
								<div class="form-group col-md-6 col-sm-12" id="kendralist">	
								<?php echo $this->Form->input("UploadReport.kendra_id", array(
										'options' => $kendra_list,
										'default' => '',
										'label'=>false,
                                        'empty' => 'Select Kendra',
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
								<input type="file" name="upl"  required="required"/>
								
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
        
        