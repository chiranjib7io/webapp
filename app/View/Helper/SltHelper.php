<?php

/* /app/View/Helper/LinkHelper.php (using other helpers) */
App::uses('AppHelper', 'View/Helper');

class SltHelper extends AppHelper {
    
	public function count_branch_data($bid){
		App::import("Model", "Customer");  
		App::import("Model", "Market");  
		$Customer_model = new Customer();  
		$Market_model = new Market();
		$total_customer=$Customer_model->find('count', array('conditions'=> array('Customer.branch_id'=>$bid, 'Customer.status'=>1)));  
		$total_market=$Market_model->find('count', array('conditions'=> array('Market.branch_id'=>$bid, 'Market.status'=>1)));
		$branch_data=array('total_customer'=>$total_customer, 'total_market'=>$total_market);
		return $branch_data;
	}
	
	public function count_region_data($rid){
		App::import("Model", "Customer");  
		App::import("Model", "Market");  
		App::import("Model", "Branch");  
		$Customer_model = new Customer();  
		$Market_model = new Market();
		$Branch_model = new Branch();
		$total_customer=$Customer_model->find('count', array('conditions'=> array('Customer.region_id'=>$rid, 'Customer.status'=>1)));  
		$total_market=$Market_model->find('count', array('conditions'=> array('Market.region_id'=>$rid, 'Market.status'=>1)));
		$total_branch=$Branch_model->find('count', array('conditions'=> array('Branch.region_id'=>$rid, 'Branch.status'=>1)));
		$region_data=array('total_branch'=>$total_branch, 'total_customer'=>$total_customer, 'total_market'=>$total_market);
		return $region_data;
	}
    
    public function get_day_name($timestamp) {

        $date = date('d/m/Y', $timestamp);
    
        if($date == date('d/m/Y')) {
          $date = 'Today';
        } 
        else if($date == date('d/m/Y',time() - (24 * 60 * 60))) {
          $date = 'Yesterday';
        }
        return $date;
    }
    
    
	
	// Genaral function for making a JSON file
    function prepare_json($response, $remove_null = 1)  {
        $json = json_encode($response, true);
        if ($remove_null == 1) {
            $json = preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $json);
        }
        /* disconnect with db */
        //App::import('Model', 'ConnectionManager');
        //$ds = ConnectionManager::getDataSource('default');
        //$ds->disconnect();
        return $json;
    }

    // Upload Type ID Array Start
    function id_proof_name()  {
        $upload_type = array(
            "Voter Card" => "Voter Card",
            "Aadhar Card" => "Aadhar Card",
            "Ration Card" => "Ration Card",
            "PAN Card" => "PAN Card",
            "Driving License" => "Driving License",
            "Passport" => "Passport",
            "Panchayat Certificate" => "Panchayat Certificate"
		);
        return $upload_type;
    }
    // Upload Type ID Array End
	
	
	// Relationship Type Array Start
    function relationship_type()
    {
        $reletion_type = array(
            "Husband" => "Husband",
            "Wife" => "Wife",
            "Father" => "Father",
            "Mother" => "Mother",
            "Son" => "Son",
            "Daughter" => "Daughter",
            "Brother" => "Brother",
            "Sister" => "Sister",
            "Uncle" => "Uncle",
            "Anut" => "Anut",
            "Grand Father" => "Grand Father",
            "Grand Mother" => "Grand Mother",
            "Grand Son" => "Grand Son",
            "Grand Daughter" => "Grand Daughter"
		);
        return $reletion_type;
    }
    // Relationship Type Array End

    // Saving Type Array Function Start
	function saving_types()  {
        $saving_type = array(
            "Daily" => "Daily Deposit",
            "Weekly" => "Weekly Deposit",
            "Monthly" => "Monthly Deposit",
            "Fixed" => "One Time Deposit (FD)",
            "MIS" => "Monthly Income Scheme (MIS)"
		);
        return $saving_type;
    }
	// Saving Type Array Function End
    // Saving Interest Type Array Function Start
	function saving_interest_types()  {
        $saving_type = array(
            "Flat" => "Flat Interest",
            "Simple" => "Simple Interest",
            "Compound" => "Compound Interest",
            "Quarterly Compound" => "Quarterly Compound Interest"
		);
        return $saving_type;
    }
	// Saving Interest Type Array Function End
    
    // Loan Interest Type Array Function Start
	function loan_interest_types()  {
        $saving_type = array(
            "Fixed" => "Fixed Interest",
            "Reducing" => "Reducing Interest"
		);
        return $saving_type;
    }
	// Loan Interest Type Array Function End
	
    function multi_array_search($search_for, $search_in) {
        foreach ($search_in as $key=>$element) {
            if ( ($element === $search_for) || (is_array($element) && $this->multi_array_search($search_for, $element)) ){
                return $key;
            }
        }
        return -1;
    }
    
    function array_find_deep($array, $search, $keys = array())
    {
        foreach($array as $key => $value) {
            if (is_array($value)) {
                $sub = $this->array_find_deep($value, $search, array_merge($keys, array($key)));
                if (count($sub)) {
                    return $sub;
                }
            } elseif ($value === $search) {
                return array_merge($keys, array($key));
            }
        }
    
        return array();
    }
    // Loan Type Array Function Start
	function loan_types()  {
        $loan_type = array(
            "Group" => "Group Loan",
            "Saving" => "Saving Loan",
            "Other" => "Other Loan",
            "Personal" => "Personal Loan",
            "Education" => "Education Loan"
		);
        return $loan_type;
    }
	// Loan Type Array Function End
	
	// Loan Period Type Array Function Start
	function loan_period_types()  {
        $loan_period_type = array(
            "Day" => "Day",
            "Week" => "Week",
            "Month" => "Month"
		);
        return $loan_period_type;
    }
	// Loan Period Type Array Function End
	
	// Loan Risk Type Array Function Start
	function loan_risk_type()  {
        $risk_type = array(
            "Risk" => "Risk Loan",
            "Risk_Free" => "Risk Free Loan"
		);
        return $risk_type;
    }
	// Loan Risk Type Array Function End
    
    // Loan Status array function start
    function loan_status_array()  {
        $loan_status_type = array(
            0 => "Deleted",
            1 => "Pending",
            2 => "Approved",
            3 => "Disbursement",
            4 => "Refused",
            5 => "Abandoned",
            6 => "Cleared"
		);
        return $loan_status_type;
    }
    // Loan Status array function end
    
    // Caste  Array Start
    function caste_list()
    {
        $caste = array(
            "General" => "General",
            "SC" => "SC",
            "ST" => "ST",
            "OBC" => "OBC",
            "Other" => "Other",
            
		);
        return $caste;
    }
    // Caste  Array End
    
    // Caste  Array Start
    function loan_purpose_list()
    {
        $purpose = array(
            "Agriculture" => "Agriculture",
            "Business" => "Business",
            "Consumption" => "Consumption",
            "Development" => "Development",
            "Education" => "Education",
            "Other" => "Other",
            
		);
        return $purpose;
    }
    // Caste  Array End
    
    // Find the date difference function Start
    public function date_difference($date1,$date2){
       
        $diff = abs(strtotime($date2) - strtotime($date1));
        
        $years = floor($diff / (365*60*60*24));
        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
        return $days;
    }
    // Find the date difference function End
    
    //************** For Expenses *********************************//
    public function get_exp_by_date($org_id,$ldgr_id,$date){
        App::import("Model", "IncomeExpenditure");  
		$IncomeExpenditure_model = new IncomeExpenditure(); 
        $trans_date= date("Y-m-d",strtotime($date));
        $exp_arr = $IncomeExpenditure_model->find('all', array('fields' => array('sum(debit_amount) as exp'), 'conditions' => array('IncomeExpenditure.account_ledger_id'=>$ldgr_id,'IncomeExpenditure.organization_id' => $org_id,'IncomeExpenditure.transaction_date'=>$trans_date)));
        return $exp_arr;
    }
    
    public function get_total_exp_by_date($org_id,$date){
        App::import("Model", "IncomeExpenditure");  
		$IncomeExpenditure_model = new IncomeExpenditure(); 
        $trans_date= date("Y-m-d",strtotime($date));
        $exp_arr = $IncomeExpenditure_model->find('all', array('fields' => array('sum(debit_amount) as exp'), 'conditions' => array('IncomeExpenditure.organization_id' => $org_id,'IncomeExpenditure.transaction_date'=>$trans_date)));
        return $exp_arr;
    }
    
    public function get_exp_by_month_year($org_id,$ldgr_id,$month,$year){
        App::import("Model", "IncomeExpenditure");  
		$IncomeExpenditure_model = new IncomeExpenditure(); 
        $exp_arr = $IncomeExpenditure_model->find('all', array('fields' => array('sum(debit_amount) as exp'), 'conditions' => array('IncomeExpenditure.account_ledger_id'=>$ldgr_id,'IncomeExpenditure.organization_id' => $org_id,'MONTH(IncomeExpenditure.transaction_date)'=>$month,'YEAR(IncomeExpenditure.transaction_date)'=>$year)));
        return $exp_arr;
    }
    
    public function get_total_exp_by_month_year($org_id,$month,$year){
        App::import("Model", "IncomeExpenditure");  
		$IncomeExpenditure_model = new IncomeExpenditure(); 
        $exp_arr = $IncomeExpenditure_model->find('all', array('fields' => array('sum(debit_amount) as exp'), 'conditions' => array('IncomeExpenditure.organization_id' => $org_id,'IncomeExpenditure.account_ledger_id !='=>16,'MONTH(IncomeExpenditure.transaction_date)'=>$month,'YEAR(IncomeExpenditure.transaction_date)'=>$year)));
        return $exp_arr;
    }
    
    public function get_exp_by_user($org_id,$ldgr_id,$user_id,$date){
        App::import("Model", "IncomeExpenditure");  
		$IncomeExpenditure_model = new IncomeExpenditure(); 
        $trans_date= date("Y-m-d",strtotime($date));
        $exp_arr = $IncomeExpenditure_model->find('all', array('fields' => array('sum(debit_amount) as exp'), 'conditions' => array('IncomeExpenditure.account_ledger_id'=>$ldgr_id,'IncomeExpenditure.organization_id' => $org_id,'IncomeExpenditure.user_id' => $user_id,'IncomeExpenditure.transaction_date'=>$trans_date)));
        return $exp_arr;
    }
    
    public function get_total_exp_by_user($org_id,$user_id,$date){
        App::import("Model", "IncomeExpenditure");  
		$IncomeExpenditure_model = new IncomeExpenditure(); 
        $trans_date= date("Y-m-d",strtotime($date));
        $exp_arr = $IncomeExpenditure_model->find('all', array('fields' => array('sum(debit_amount) as exp'), 'conditions' => array('IncomeExpenditure.organization_id' => $org_id,'IncomeExpenditure.account_ledger_id !='=>16,'IncomeExpenditure.user_id' => $user_id,'IncomeExpenditure.transaction_date'=>$trans_date)));
        return $exp_arr;
    }
    
    //************** For Income *********************************//
    public function get_income_by_date($org_id,$ldgr_id,$date){
        App::import("Model", "IncomeExpenditure");  
		$IncomeExpenditure_model = new IncomeExpenditure(); 
        $trans_date= date("Y-m-d",strtotime($date));
        $exp_arr = $IncomeExpenditure_model->find('all', array('fields' => array('sum(credit_amount) as exp'), 'conditions' => array('IncomeExpenditure.account_ledger_id'=>$ldgr_id,'IncomeExpenditure.organization_id' => $org_id,'IncomeExpenditure.transaction_date'=>$trans_date)));
        return $exp_arr;
    }
    
    public function get_total_income_by_date($org_id,$date){
        App::import("Model", "IncomeExpenditure");  
		$IncomeExpenditure_model = new IncomeExpenditure(); 
        $trans_date= date("Y-m-d",strtotime($date));
        $exp_arr = $IncomeExpenditure_model->find('all', array('fields' => array('sum(credit_amount) as exp'), 'conditions' => array('IncomeExpenditure.organization_id' => $org_id,'IncomeExpenditure.transaction_date'=>$trans_date)));
        return $exp_arr;
    }
    
    public function get_income_by_month_year($org_id,$ldgr_id,$month,$year){
        App::import("Model", "IncomeExpenditure");  
		$IncomeExpenditure_model = new IncomeExpenditure(); 
        $exp_arr = $IncomeExpenditure_model->find('all', array('fields' => array('sum(credit_amount) as exp'), 'conditions' => array('IncomeExpenditure.account_ledger_id'=>$ldgr_id,'IncomeExpenditure.organization_id' => $org_id,'MONTH(IncomeExpenditure.transaction_date)'=>$month,'YEAR(IncomeExpenditure.transaction_date)'=>$year)));
        return $exp_arr;
    }
    
    public function get_total_income_by_month_year($org_id,$month,$year){
        App::import("Model", "IncomeExpenditure");  
		$IncomeExpenditure_model = new IncomeExpenditure(); 
        $exp_arr = $IncomeExpenditure_model->find('all', array('fields' => array('sum(credit_amount) as exp'), 'conditions' => array('IncomeExpenditure.organization_id' => $org_id,'IncomeExpenditure.account_ledger_id !='=>16,'MONTH(IncomeExpenditure.transaction_date)'=>$month,'YEAR(IncomeExpenditure.transaction_date)'=>$year)));
        return $exp_arr;
    }
    
    public function get_income_by_user($org_id,$ldgr_id,$user_id,$date){
        App::import("Model", "IncomeExpenditure");  
		$IncomeExpenditure_model = new IncomeExpenditure(); 
        $trans_date= date("Y-m-d",strtotime($date));
        $exp_arr = $IncomeExpenditure_model->find('all', array('fields' => array('sum(credit_amount) as exp'), 'conditions' => array('IncomeExpenditure.account_ledger_id'=>$ldgr_id,'IncomeExpenditure.organization_id' => $org_id,'IncomeExpenditure.user_id' => $user_id,'IncomeExpenditure.transaction_date'=>$trans_date)));
        return $exp_arr;
    }
    
    public function get_total_income_by_user($org_id,$user_id,$date){
        App::import("Model", "IncomeExpenditure");  
		$IncomeExpenditure_model = new IncomeExpenditure(); 
        $trans_date= date("Y-m-d",strtotime($date));
        $exp_arr = $IncomeExpenditure_model->find('all', array('fields' => array('sum(credit_amount) as exp'), 'conditions' => array('IncomeExpenditure.organization_id' => $org_id,'IncomeExpenditure.account_ledger_id !='=>16,'IncomeExpenditure.user_id' => $user_id,'IncomeExpenditure.transaction_date'=>$trans_date)));
        return $exp_arr;
    }
    public function get_pending_loans($user_id)
    {
        App::import("Model","Loan");
        
        $loan_data=(new Loan())->find("count",array("conditions"=>array("Loan.loan_status_id"=>1,"Loan.user_id"=>$user_id)));
           
        return $loan_data;
    }
     public function getTotalInterestPaid($loan_id)
    {
        App::import("Model","LoanTransaction");
        
      $loan_trans_model=  new LoanTransaction();
  $total=$loan_trans_model->find("all",array("fields"=>array("sum(LoanTransaction.insta_interest_paid) as totalIntPaid"),"conditions"=>array("LoanTransaction.loan_id"=>$loan_id)));
return $total['LoanTransaction']['totalIntPaid'];
        
    }
    
}


?>