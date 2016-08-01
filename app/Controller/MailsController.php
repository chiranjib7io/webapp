<?php
// This is mail controller. Mail sending option set here.
App::uses('CakeEmail', 'Network/Email');

class MailsController extends AppController {
		// List of models which are used in the mail controller 
		var $uses = array('User','Organization','Region','Branch','Kendra','Customer','Loan','Saving','Idproof','LogRecord','Country', 'Setting', 'Fee');
	
	// This is a blank index function for safe navigation
    public function index() {
		
    }
	
	// Send organization mail with the details function start
	public function organization_mail(){
		$start_date = date("Y-m-d",strtotime("-7 days"));
        $end_date = date("Y-m-d");
		$organization_data=$this->User->find('all', array('conditions' => array('User.user_type_id' => 2)));
		foreach ($organization_data as $korg=> $org_data){
			$organization_id=$org_data['Organization']['id'];
			$org_amdin_email=$org_data['User']['email'];
			$branch_by_org=$this->Branch->find('all', array('conditions' => array('Branch.organization_id' => $organization_id)));
			if(!empty($branch_by_org)){
				foreach($branch_by_org as $kbranch => $brn_data){
					if(count($brn_data['Loan'])> 0){
						$branch_id=$brn_data['Branch']['id'];
						$this->branch_manager_report($branch_id,$start_date,$end_date);
					}
				}
			}
		}
	}
	// Send organization mail with the details function end
	
	// Prepare report and mail for branch manager wise function start
	public function branch_manager_report($branch_id='',$start_date,$end_date){
		$this->autoRender= false;
		$branch_data = $this->dashboard_branch_table($branch_id,$start_date,$end_date);
        $organization_data_admin=$this->User->find('first', array('conditions' => array('User.user_type_id' => 2, 'User.organization_id'=> $branch_data['Branch']['organization_id'])));
		$org_amdin_email=$organization_data_admin['User']['email'];
        //Email the pdf 
		$email = $branch_data['Branch']['manager_email'];
        $message = $branch_data;
        $subject ='Report from Microfinance for Branch '.$branch_data['Branch']['branch_name'] .' of '. $start_date;
        $name = $branch_data['Branch']['manager_name'];
        $admin_name = $organization_data_admin['User']['first_name'].' '. $organization_data_admin['User']['last_name'];
        $from = 'chiranjib.dey@7io.co';
        $template = 'report_template';
        $layout = 'report_msg';
        $this->_email($email, $message, $subject ,$name, $from, '', $template,$layout);
        $this->_email($org_amdin_email, $message, $subject ,$admin_name, $from, '',$template,$layout);
        //Emailpdf end
	}
    // Prepare report and mail for branch manager wise function end
}
// END of Mail controller
?>