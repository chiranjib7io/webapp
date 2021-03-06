<script type="text/javascript">
			function calc_rate(feerate=0){
			     //alert(feerate);
				var amount, interest, instal, rate, loantype, interval, intervaltype, LoanRiskfundFee, LoanProcessingFee;
				LoanRiskfundFee = document.getElementById("riskfundfee").value;
				LoanProcessingFee = document.getElementById("processfee").value;
				feerate = $('#secfee').val();
				
				
				amount = (document.getElementById("LoanLoanPrincipal").value * 1);
				interest = (document.getElementById("LoanLoanInterest").value * 1);
				instal = (document.getElementById("LoanLoanPeriod").value * 1);
                loantype = document.getElementById("LoanInterestType").value;
                
                riskfund_amt=amount *LoanRiskfundFee/100;
                process_amt=amount *LoanProcessingFee/100;
				
                
                if(loantype=='Fixed'){
                    repaytotal = Math.round((amount+(amount*interest/100)));
    				rate = repaytotal / instal;
                }else{
                    intervaltype = document.getElementById("LoanLoanPeriodUnit").value;
                    if(intervaltype=='WEEK'){
                        interval = 52;
                    }else{
                        interval = 12;
                    }
                    r = interest/interval/100;
                    rate = amount*r*(Math.pow((1+r),instal))/(Math.pow((1+r),instal)-1);
                    repaytotal = Math.round(rate*instal);
    				
                }
				sec_fee = (amount*feerate/100);
				document.getElementById("LoanLoanRepayTotal").value = repaytotal;
				document.getElementById("LoanLoanRate").value = Math.round(rate);
                document.getElementById("LoanSecurityFee").value = Math.round(sec_fee);
				document.getElementById("LoanRiskfundFee").value = Math.round(riskfund_amt);
				document.getElementById("LoanProcessingFee").value = Math.round(process_amt);
			}
            
   function load_plan(plan){
        if(plan!=''){
            var obj = jQuery.parseJSON( plan );
            var amount, interest, instal, rate, loantype, interval, intervaltype, LoanRiskfundFee, LoanProcessingFee, repaytotal, feerate;
            console.log(obj);
            $("#LoanLoanPrincipal").val(obj.min_amount);
            $("#LoanLoanPrincipal").attr( "min", obj.min_amount );
            $("#LoanLoanInterest").val(obj.interest_rate);
            $("#LoanInterestType").val(obj.interest_type);
            $("#LoanLoanType").val(obj.loan_type);
            $('#secfee').val(obj.min_save_per);
            feerate = obj.min_save_per;
            
            
            amount = (obj.min_amount * 1);
			interest = (obj.interest_rate * 1);
			instal = ($("#LoanLoanPeriod").val() * 1);
            
            LoanRiskfundFee = $("#riskfundfee").val();
			LoanProcessingFee = $("#processfee").val();
            
            riskfund_amt=amount *LoanRiskfundFee/100;
            process_amt=amount *LoanProcessingFee/100;
			
            
            if(obj.interest_type=='Fixed'){
                repaytotal = Math.round((amount+(amount*interest/100)));
				rate = repaytotal / instal;
                
            }else{
                intervaltype = $("#LoanLoanPeriodUnit").val();
                if(intervaltype=='WEEK'){
                    interval = 52;
                }else{
                    interval = 12;
                }
                r = interest/interval/100;
                rate = amount*r*(Math.pow((1+r),instal))/(Math.pow((1+r),instal)-1);
                repaytotal = Math.round(rate*instal);
				
            }
			sec_fee = (amount*feerate/100);
            //console.log(repaytotal);
			$("#LoanLoanRepayTotal").val(repaytotal);
			$("#LoanLoanRate").val(Math.round(rate));
            $("#LoanSecurityFee").val(Math.round(sec_fee));
			$("#LoanRiskfundFee").val(Math.round(riskfund_amt));
			$("#LoanProcessingFee").val(Math.round(process_amt));
            
        }else{
            $("#LoanLoanPrincipal").val(0);
            $("#LoanLoanPrincipal").attr( "min", 0);
            $("#LoanLoanInterest").val(0);
            $("#LoanInterestType").val('');
            $("#LoanLoanType").val('');
            $("#LoanLoanRepayTotal").val(0);
			$("#LoanLoanRate").val(0);
            $("#LoanSecurityFee").val(0);
			$("#LoanRiskfundFee").val(0);
			$("#LoanProcessingFee").val(0);
        }
    }
   </script>
   
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Create New Loan
            <small class="text-green"><?=$this->Session->flash()?></small>
            
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Create New Loan</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
 
              <div class="box box-danger col-xs-12" style="padding-top:20px; padding-bottom:20px;">
                <div class="box-header">
                  <h3 class="box-title">Customer Name: <?= $cust_data['Customer']['fullname']?></h3>
                </div>
                <?PHP echo $this->Form->create('Loan', array('method' => 'post')); ?>
                
                <?php echo $this->Form->input('Loan.currency', array('type' => 'hidden','value'=>$org_data['Setting']['CUR'],'label'=>false)); ?>
                
                
                <?php echo $this->Form->input('Loan.loan_type', array('type' => 'hidden','value'=>$org_data['Setting']['LT'],'label'=>false)); ?>
                
                
                <?php echo $this->Form->input('Loan.organization_id', array('type' => 'hidden','value'=>$cust_data['Organization']['id'],'label'=>false)); ?>
                <?php //echo $this->Form->input('Loan.region_id', array('type' => 'hidden','value'=>$cust_data['Region']['id'],'label'=>false)); ?>
                <?php echo $this->Form->input('Loan.region_id', array('type' => 'hidden','value'=>$cust_data['Region']['id'],'label'=>false)); ?>
                <?php echo $this->Form->input('Loan.branch_id', array('type' => 'hidden','value'=>$cust_data['Branch']['id'],'label'=>false)); ?>
                <?php echo $this->Form->input('Loan.market_id', array('type' => 'hidden','value'=>$cust_data['Market']['id'],'label'=>false)); ?>
                <?php echo $this->Form->input('Loan.customer_id', array('type' => 'hidden','value'=>$cust_data['Customer']['id'],'label'=>false)); ?>
                
                
                <div class="box-body col-md-6 col-sm-12">
                    
                    <div class="form-group">
                        <label for="Saving Account Number">Loan Account Number</label>
                        <?php echo $this->Form->input('Account.account_number', array('type' => 'text','class'=>'form-control','label'=>false,'placeholder'=>'Enter Account Number', 'required'=>'required')); ?>
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                        <label for="LoanPrincipal">Loan Principal</label>
                        <?php echo $this->Form->input('Loan.loan_principal', array('type' => 'number','onChange'=>'calc_rate()','placeholder'=>'Enter Loan Principal','class'=>'form-control','required'=>'required','label'=>false)); ?>
                        <input type="hidden" id="secfee" value="<?=$org_data['Fee']['SEC']?>" /> 
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                        <label for="LoanInterest">Loan Interest</label>
                        <?php echo $this->Form->input('Loan.loan_interest', array('type' => 'text','value'=>$org_data['Fee']['LIR'],'class'=>'form-control','label'=>false, 'required'=>'required')); ?>
                        
                    </div><!-- /.form group -->

                    <div class="form-group">
                      <label for="NoOfPeriod">Payment Interval</label>
                      <?php echo $this->Form->input("Loan.loan_period_unit", array(
                            'options' => array('WEEK'=>'Weekly','MONTH'=> 'Monthly'),
                            'default' => $org_data['Setting']['LPT'],
                            'onChange'=>'calc_rate()',
                            'label'=>false,
          		            'class'	=> 'form-control'
                        ));
                    
                        ?>
                      
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="NoOfPeriod">No. of Period</label>
                      <?php echo $this->Form->input('Loan.loan_period', array('type' => 'number','onChange'=>'calc_rate()','placeholder'=>'Enter No. of '.$org_data['Setting']['LPT'],'value'=>$org_data['Setting']['MNLPD'],'min'=>$org_data['Setting']['MNLPD'],'max'=>$org_data['Setting']['MXLPD'],'class'=>'form-control','required'=>'required','label'=>false)); ?>
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="LoanRepayAmount">Loan Repay Amount</label>
                      <?php echo $this->Form->input('Loan.loan_repay_total', array('type' => 'text','value'=>0,'class'=>'form-control','label'=>false,'readonly'=>'readonly')); ?>
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="LoanRepayAmount">Loan Security Amount</label>
                      <?php echo $this->Form->input('Loan.minimum_savings_amount', array('type' => 'text','value'=>0,'class'=>'form-control','label'=>false,'readonly'=>'readonly')); ?>
                      
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="Garanter1">Loan Application Form Fee</label>
                      <?php echo $this->Form->input('Loan.loan_fee', array('type' => 'text','value'=>$org_data['Fee']['LAF'],'class'=>'form-control','label'=>false,'readonly'=>'readonly')); ?>
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="Garanter1">Admission Fee</label>
                      <?php echo $this->Form->input('Loan.admission_fee', array('type' => 'text','value'=>$org_data['Fee']['ADMF'],'class'=>'form-control','label'=>false,'readonly'=>'readonly')); ?>
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="Garanter1">Processing Fee</label>
                      <?php echo $this->Form->input('Loan.processing_fee', array('type' => 'text','value'=>$org_data['Fee']['PROF'],'class'=>'form-control','label'=>false,'readonly'=>'readonly')); ?>
                       <input type="hidden" id="processfee" value="<?=$org_data['Fee']['PROF']?>" /> 
                    </div><!-- /.form group -->
                    
                   <div class="form-group">
                        <label for="Saving Account Number">Nominee Name</label>
                        <?php echo $this->Form->input('Loan.nominee_name', array('type' => 'text','class'=>'form-control','label'=>false,'placeholder'=>'Enter Nominee Name')); ?>
                    </div><!-- /.form group -->
					
                    
                    
					<div class="form-group">
                        <label for="Saving Date">Age of Nominee</label>
						<input type="text" class="form-control" id="savings_nominee" name="data[Loan][nominee_age]" placeholder="Enter Nominee age">
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label>Relationship with Nominee</label>
						<?php
							echo $this->Form->input('Loan.nominee_relationship', array('type' => 'select', 'options' => $this->Slt->relationship_type(), 'class'=>'form-control', 'label'=>false, 'empty' => 'Select Relationship'));
						?>
                    </div><!-- /.form group -->

                </div><!-- /.box-body -->
                
                <div class="box-body col-md-6 col-sm-12">
                    <div class="form-group">
                      <label>Plan Type</label>
						<?php
							echo $this->Form->input('Loan.plan', array('type' => 'select', 'options' => $plan_data, 'class'=>'form-control', 'label'=>false, 'onchange'=>'load_plan(this.value)' , 'required'=>'required', 'empty' => 'Select Plan'));
						?>
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label>Interest Type</label>
						<?php
							echo $this->Form->input('Loan.interest_type', array('type' => 'select', 'options' => $this->Slt->loan_interest_types(), 'class'=>'form-control', 'label'=>false,'onChange'=>'calc_rate()', 'required'=>'required', 'empty' => 'Select Interest Type'));
						?>
                    </div><!-- /.form group -->
                    
					 <div class="form-group">
                      <label for="Garanter1">Riskfund Fee</label>
                      <?php echo $this->Form->input('Loan.riskfund_fee', array('type' => 'text','value'=>$org_data['Fee']['RSKF'],'class'=>'form-control','label'=>false,'readonly'=>'readonly')); ?>
                        <input type="hidden" id="riskfundfee" value="<?=$org_data['Fee']['RSKF']?>" />
                    </div><!-- /.form group -->
					
                    <div class="form-group">
                      <label for="Garanter1">Gurranter 1 Name</label>
                      <?php echo $this->Form->input('Loan.loan_guranter1', array('type' => 'text','placeholder'=>'Enter Garanter 1 Name','class'=>'form-control','label'=>false)); ?>
                    </div><!-- /.form group -->
                                    
                    <div class="form-group">
                      <label for="Garanter1ContactNo">Gurranter 1 Account No.</label>
                      <?php echo $this->Form->input('Loan.loan_guranter1_account_no', array('type' => 'text','placeholder'=>'Enter Garanter 1 Contact No.','class'=>'form-control','label'=>false)); ?>
                      
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="Garanter1ContactNo">Gurranter 1 Amount</label>
                      <?php echo $this->Form->input('Loan.loan_guranter1_amount', array('type' => 'text','placeholder'=>'Enter Garanter 1 Contact No.','class'=>'form-control','label'=>false)); ?>
                      
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="Garanter1">Gurranter 2 Name</label>
                      <?php echo $this->Form->input('Loan.loan_guranter2', array('type' => 'text','placeholder'=>'Enter Garanter 1 Name','class'=>'form-control','label'=>false)); ?>
                    </div><!-- /.form group -->
                                    
                    <div class="form-group">
                      <label for="Garanter1ContactNo">Gurranter 2 Account No.</label>
                      <?php echo $this->Form->input('Loan.loan_guranter2_account_no', array('type' => 'text','placeholder'=>'Enter Garanter 1 Contact No.','class'=>'form-control','label'=>false)); ?>
                      
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="Garanter1ContactNo">Gurranter 2 Amount</label>
                      <?php echo $this->Form->input('Loan.loan_guranter2_amount', array('type' => 'text','placeholder'=>'Enter Garanter 1 Contact No.','class'=>'form-control','label'=>false)); ?>
                      
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="LoanPurpose">Loan Purpose</label>
                      <?php echo $this->Form->input('Loan.loan_purpose', array('type' => 'textarea','style'=>'height:108px; resize:none','placeholder'=>'Enter Loan Purpose','class'=>'form-control','required'=>'required','label'=>false)); ?>
                      
                    </div><!-- /.form group -->
                   
                   <div class="form-group">
                      <label for="LoanApplicationDate">Loan Installment amount</label>
                      <?php echo $this->Form->input('Loan.loan_rate', array('type' => 'text','value'=>0,'class'=>'form-control','label'=>false,'readonly'=>'readonly')); ?>
                      
                    </div><!-- /.form group --> 
                    
                    
                    <div class="form-group">
                      <label for="LoanApplicationDate">Loan Application Date</label>
                      <input type="date" class="form-control" id="cust_dob" name="data[Loan][loan_date]" placeholder="Enter Loan Application Date" required="required">
                      
                    </div><!-- /.form group -->

                </div><!-- /.box-body -->
                
                <div class="box-footer" align="right">
                    <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                </div>
                <?php echo $this->Form->end(); ?>
              </div><!-- /.box -->

            </div>
          </div><!-- /.row -->

        </section><!-- /.content -->