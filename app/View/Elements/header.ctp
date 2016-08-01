<header class="main-header">
        <a href="<?= $this->Html->url('/login') ?>" class="logo"><img src="<?php echo $this->webroot; ?>asset/dist/img/logo.png"></a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
		  
          <div class="navbar-custom-menu">
		  <!-- Back button --
                <div class="btn-header transparent" style="">
                </div>
                <!-- End Back button -->
			<ul class="nav navbar-nav">
				<?php
					$url = $this->here;
					$expl = explode('/',$url);
					//echo end($expl);
					if(end($expl)!='dashboard' && end($expl)!='lo_dashboard'){
                ?>
            	<li><button class="btn btn-default" onclick="goBack()" style="margin-top:8px;">Go Back</button></li>
				<?php } ?>
                <li><a href="<?= $this->Html->url('/logout') ?>">Logout</a></li>
            </ul>
          
        </nav>
      </header>
	  <?php //pr($userData); die; ?>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
<?php if($userData['user_type_id'] != 6) { ?>
			<li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
			<li class="treeview">
              <a href="#">
                <i class="fa fa-pencil-square-o"></i>
                <span>Create</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
				<?php if($userData['user_type_id'] == 2) { ?>
                    <li><a href="<?= $this->Html->url('/save_region') ?>"><i class="fa fa-circle-o"></i> Create Region </a></li>
                    <li><a href="<?= $this->Html->url('/create_branch') ?>"><i class="fa fa-circle-o"></i> Create Branch </a></li>
					<li><a href="<?= $this->Html->url('/save_market') ?>"><i class="fa fa-circle-o"></i> Create Market </a></li>
					<li><a href="<?= $this->Html->url('/create_employee') ?>"><i class="fa fa-circle-o"></i> Create Employee</a></li>
				<?php } ?>
				<?php if($userData['user_type_id'] == 2 || $userData['user_type_id'] == 4) { ?>
					<li><a href="<?= $this->Html->url('/save_kendra') ?>"><i class="fa fa-circle-o"></i> Create Group </a></li>
				<?php } ?>
                <li><a href="<?= $this->Html->url('/save_customer') ?>"><i class="fa fa-circle-o"></i> Create Customer</a></li>
                <li><a href="<?= $this->Html->url('/saving_create') ?>"><i class="fa fa-circle-o"></i> Create Saving Account</a></li>
				<li><a href="<?= $this->Html->url('/loan_create') ?>"><i class="fa fa-circle-o"></i> Create Loan Account</a></li>
                
              </ul>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-newspaper-o"></i>
                <span>Summary</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
			  <?php if($userData['user_type_id'] == 2 || $userData['user_type_id'] == 4) { ?>
					<li><a href="<?= $this->Html->url('/stat_loan_officers')//$this->Html->url('/loan_officer_details') ?>"><i class="fa fa-circle-o"></i> Loan Officer Wise</a></li>
					<li><a href="<?= $this->Html->url('/branch_loan_details') ?>"><i class="fa fa-circle-o"></i> Branch Wise</a></li>
				<?php } ?>
                <li><a href="<?= $this->Html->url('/market_loan_details') ?>"><i class="fa fa-circle-o"></i> Market Wise</a></li>
                <li><a href="<?= $this->Html->url('/kendra_loan_details') ?>"><i class="fa fa-circle-o"></i> Kendra Wise</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-list"></i>
                <span>List</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
			  <?php if($userData['user_type_id'] == 2 || $userData['user_type_id'] == 4) { ?>
                    <li><a href="<?= $this->Html->url('/region_list') ?>"><i class="fa fa-circle-o"></i> Region List</a></li>
                    <li><a href="<?= $this->Html->url('/branch_list') ?>"><i class="fa fa-circle-o"></i> Branch List</a></li>
					<li><a href="<?= $this->Html->url('/employee_list') ?>"><i class="fa fa-circle-o"></i> Employee List</a></li>
				<?php } ?>
				<li><a href="<?= $this->Html->url('/market_list') ?>"><i class="fa fa-circle-o"></i> Market List</a></li>
                <li><a href="<?= $this->Html->url('/customer_list') ?>"><i class="fa fa-circle-o"></i> Customer List</a></li>
              </ul>
            </li>
			<?php if($userData['user_type_id'] == 2 || 4) { ?>
				<li><a href="<?= $this->Html->url('/bulk_loan_release') ?>"><i class="fa fa-indent"></i>Loan Release</a></li>
                	<li><a href="<?= $this->Html->url('/pending_loan') ?>"><i class="fa fa-indent"></i>Pending Loan(<?=$this->Slt->get_pending_loans($userData['id']);?>)</a></li>
			<?php
             }
                
             ?>
           
           
            <li class="treeview">
              <a href="#">
                <i class="fa fa-th"></i> <span>Collection</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
               <!-- <li><a href="<?= $this->Html->url('/bulk_loan_collection') ?>"><i class="fa fa-circle-o"></i> Bulk Loan Collection</a></li>
                <li><a href="<?= $this->Html->url('/loan_overdue_payment') ?>"><i class="fa fa-circle-o"></i> Overdue Collection</a></li>
                <li><a href="<?= $this->Html->url('/loan_prepayment') ?>"><i class="fa fa-circle-o"></i> Loan Prepayment</a></li>
                <li><a href="<?= $this->Html->url('/bulk_saving_collection') ?>"><i class="fa fa-circle-o"></i>Bulk Saving Collection</a></li> -->
                
                <li><a href="<?= $this->Html->url('/bulk_loan_collections') ?>"><i class="fa fa-circle-o"></i> Bulk Loan Collection</a></li>
                <li><a href="<?= $this->Html->url('/amount_collection') ?>"><i class="fa fa-circle-o"></i> Amount Collection</a></li>
                <li><a href="<?= $this->Html->url('/daily_saving_deposit') ?>"><i class="fa fa-circle-o"></i> Saving Collection</a></li>
                <li><a href="<?= $this->Html->url('/daily_loan_collection') ?>"><i class="fa fa-circle-o"></i> Loan Installment Collection</a></li>
                
              </ul>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-eject"></i> <span>Withdrawal</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?= $this->Html->url('/security_deposite_return') ?>"><i class="fa fa-circle-o"></i> Security Deposit</a></li>
                <li><a href="<?= $this->Html->url('/amount_withdraw') ?>"><i class="fa fa-circle-o"></i> Amount Withdraw</a></li>
              </ul>
            </li>
			<li class="treeview">
              <a href="#">
                <i class="fa fa-file-text-o"></i> <span>Plan</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?= $this->Html->url('/create_saving_plan') ?>"><i class="fa fa-circle-o"></i>Create Saving Plan</a></li>
                <li><a href="<?= $this->Html->url('/create_loan_plan') ?>"><i class="fa fa-circle-o"></i> Create Loan Plan</a></li>
                <li><a href="<?= $this->Html->url('/plan_list') ?>"><i class="fa fa-circle-o"></i> Plan List</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-gears"></i> <span>Settings</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
			  <?php if($userData['user_type_id'] == 2) { ?>
					<li><a href="<?= $this->Html->url('/organization_edit') ?>"><i class="fa fa-circle-o"></i> Edit An Organization</a></li>
				<!--	<li><a href="<?= $this->Html->url('/loan_setting') ?>"><i class="fa fa-circle-o"></i>Loan Setting</a></li>
                    <li><a href="<?= $this->Html->url('/collection_settings') ?>"><i class="fa fa-circle-o"></i>Collection Date Settings</a></li> -->
                    
                    <li><a href="<?= $this->Html->url('/organizations/income_expense_name_list') ?>"><i class="fa fa-indent"></i>Income and Expense name manage</a></li>
				<?php } ?>
                <li><a href="<?= $this->Html->url('/change_password') ?>"><i class="fa fa-circle-o"></i> Change Password</a></li>
              </ul>
            </li>
			<!-- Other Extra Menu fro Add CSV File and Delete Data -->
			<li class="treeview">
              <a href="#">
                <i class="fa fa-file-text-o"></i> <span>Extra</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?= $this->Html->url('/csv_upload') ?>"><i class="fa fa-circle-o"></i>Upload CSV File</a></li>
                <li><a href="<?= $this->Html->url('/delete_kendra_data') ?>"><i class="fa fa-circle-o"></i> Delete Kendra Wise CSV Data</a></li>
                <li><a href="<?= $this->Html->url('/delete_transaction') ?>"><i class="fa fa-circle-o"></i> Delete Transaction</a></li>
              </ul>
            </li>
<?php }else{
?>
            <li class="treeview active">
              <a href="#">
                <i class="fa fa-file-text-o"></i> <span>Data Entry</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?= $this->Html->url('/dataentries/save_customer') ?>"><i class="fa fa-circle-o"></i>Customer Entry</a></li>
                <li><a href="<?= $this->Html->url('/dataentries/loan_payment') ?>"><i class="fa fa-circle-o"></i> Single All Entry</a></li>
                <li><a href="<?= $this->Html->url('/dataentries/saving_payment') ?>"><i class="fa fa-circle-o"></i> Single Saving Entry</a></li>
                <li><a href="<?= $this->Html->url('/dataentries/bulk_entry') ?>"><i class="fa fa-circle-o"></i> Bulk Loan Entry</a></li>
                <li><a href="<?= $this->Html->url('/dataentries/bulk_savings_entry') ?>"><i class="fa fa-circle-o"></i> Bulk Saving Entry</a></li>
                <li><a href="<?= $this->Html->url('/dataentries/daily_saving_deposit') ?>"><i class="fa fa-circle-o"></i> Bulk Saving Deposit</a></li>
                <li><a href="<?= $this->Html->url('/dataentries/daily_loan_collection') ?>"><i class="fa fa-circle-o"></i> Bulk Loan Collection</a></li>
                <li><a href="<?= $this->Html->url('/dataentries/single_amount_collection') ?>"><i class="fa fa-circle-o"></i> Single Collection</a></li>
                <li><a href="<?= $this->Html->url('/dataentries/loan_upload_from_file') ?>"><i class="fa fa-circle-o"></i> File Upload Entry for Loan</a></li>
                
              </ul>
            </li>
<?php
    }
?>
<?php if($userData['user_type_id'] == 2 || 4 ) { ?>
            <li><a href="<?= $this->Html->url('/organizations/daily_income_expenditure') ?>"><i class="fa fa-indent"></i>Income/Expenditure Entry</a></li>
            
            <li class="treeview">
              <a href="#">
                <i class="fa fa-file-text-o"></i> <span>Reports</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?= $this->Html->url('/reports/receipt_payment_report') ?>"><i class="fa fa-circle-o"></i>Income & Expenditure Report</a></li>
                <li><a href="<?= $this->Html->url('/reports/co_receipt_payment_report') ?>"><i class="fa fa-circle-o"></i>Credit Oficer wise Income & Expenditure Report</a></li>
                <li><a href="<?= $this->Html->url('/reports/profit_loss_report') ?>"><i class="fa fa-circle-o"></i>Profit & Loss Report</a></li>
                 <li><a href="<?= $this->Html->url('/reports/maturity_report') ?>"><i class="fa fa-circle-o"></i>Maturity Report</a></li> -->
             <!--   <li><a href="<?= $this->Html->url('/reports/co_general_report') ?>"><i class="fa fa-circle-o"></i>Credit Oficer General Report</a></li> -->
              </ul>
            </li>
<?php } ?>
            
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>