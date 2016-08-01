<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
 
 // App Controller Start
class AppController extends Controller
{
    // Extension Controller
	
    
   
    var $uses = array(
        'User',
        'Organization',
        'Region',
        'Branch',
        'Kendra',
        'Customer',
        'Loan',
        'Saving',
        'Idproof',
        'LogRecord',
        'Country',
        'LoanStatus',
        'SavingsTransaction',
        'LoanTransaction',
        'IncomeExpenditure',
        'ExtraAmount',
        'Setting',
        'Fee',
        'Plan',
        'Order',
        'Account',
        'Market');
    // added the debug toolkit
    // sessions support
    // authorization for login and logut redirect
    public $components = array(
        'DebugKit.Toolbar',
        'Session',
        'Email',
        'Auth' => array(
            'loginRedirect' => array('controller' => 'users', 'action' => 'index'),
            'logoutRedirect' => array('controller' => 'users', 'action' => 'login'),
            'authError' => 'You must be logged in to view this page.',
            'loginError' => 'Invalid Username or Password entered, please try again.')
        );
			
	public $helpers = array('Form', 'Html', 'Js', 'Time', 'Number','Slt');

    // only allow the login controllers only
    public function beforeFilter()
    {
        $this->Auth->allow('login');
        //echo Configure::version();;
    }
    public function beforeRender()
    {
        $this->set('userData', $this->Auth->user());  
    }

    public function isAuthorized($user) {
        // Here is where we should verify the role and give access based on role
        return true;
    }
    // This function is for Detect the device of the user
    function detectDevice() {
        $userAgent = $_SERVER["HTTP_USER_AGENT"];
        $devicesTypes = array(
            "computer" => array(
                "msie 10",
                "msie 9",
                "msie 8",
                "windows.*firefox",
                "windows.*chrome",
                "x11.*chrome",
                "x11.*firefox",
                "macintosh.*chrome",
                "macintosh.*firefox",
                "opera"),
            "tablet" => array(
                "tablet",
                "android",
                "ipad",
                "tablet.*firefox"),
            "mobile" => array(
                "mobile ",
                "android.*mobile",
                "iphone",
                "ipod",
                "opera mobi",
                "opera mini"),
            "bot" => array(
                "googlebot",
                "mediapartners-google",
                "adsbot-google",
                "duckduckbot",
                "msnbot",
                "bingbot",
                "ask",
                "facebook",
                "yahoo",
                "addthis"));
        foreach ($devicesTypes as $deviceType => $devices) {
            foreach ($devices as $device) {
                if (preg_match("/" . $device . "/i", $userAgent)) {
                    $deviceName = $deviceType;
                }
            }
        }
        return ucfirst($deviceName);
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
    
    function getLastQuery()
    {
        $dbo = ConnectionManager::getDataSource('default');
        $logs = $dbo->getLog();
        $lastLog = end($logs['log']);
        return $lastLog['query'];
    }

	function create_account_number() {
        $microTime = microtime();
        list($a_dec, $a_sec) = explode(" ", $microTime);
        $dec_hex = dechex($a_dec * 1000000);
        $sec_hex = dechex($a_sec);
        $this->ensure_length($dec_hex, 5);
        $this->ensure_length($sec_hex, 6);
        $guid = "";
        $guid .= $dec_hex;
        $guid .= $this->create_guid_section(3);
        $guid .= '-';
        $guid .= $this->create_guid_section(4);
        $guid .= '-';
        $guid .= $this->create_guid_section(4);
        $guid .= '-';
        $guid .= $this->create_guid_section(4);
        $guid .= '-';
        $guid .= $sec_hex;
        $guid .= $this->create_guid_section(6);
        return $guid;
    }
    function ensure_length(&$string, $length) {
        $strlen = strlen($string);
        if ($strlen < $length) {
            $string = str_pad($string, $length, "0");
        } else if ($strlen > $length) {
            $string = substr($string, 0, $length);
        }
    }
    function create_guid_section($characters) {
        $return = "";
        for ($i = 0; $i < $characters; $i++) {
            $return .= dechex(mt_rand(0, 15));
        }
        return $return;
    }
    function calculate_interest($investment=0,$month=12,$rate=9,$interest_type='Quarterly Compound'){
        $arr = array('principal'=>$investment,'interest'=>0,'total_amount'=>0);
        $accumulated = 0;
        $year=$month/12;
        switch ($interest_type) {
            case 'Flat':
                $interest=($investment*$rate/100)*$year;
                $arr['principal'] = $investment;
                $arr['interest'] = $interest;
                $arr['interest_per_month'] = $interest/$month;
                $arr['total_amount'] = $investment+$interest;
                break;
            case 'WFlat':
                $week = $year*52;
                $interest=($investment*$week)*($rate/100)*$year;
                $arr['principal'] = ($investment*$week);
                $arr['interest'] = $interest;
                $arr['total_amount'] = ($investment*$week)+$interest;
                break;
            case 'Simple':
                
                $amount=$investment*30;
                $cum_val=0;
                for($i=1; $i<=$month; $i++){
                	$cum_val=$cum_val+$amount*$i;
                }
                $accumulated=$cum_val*$rate/100/12;
                
                $arr['principal'] = $amount*$month;
                $arr['interest'] = $accumulated;
                $arr['total_amount'] = $accumulated+($amount*$month);
                break;
            case 'Compound':
                $accumulated = $investment*(pow($rate/400+1,$month/3)-1)*(1200/$rate+2);
                $arr['principal'] = $investment*$month;
                $arr['interest'] = $accumulated - ($investment*$month);
                $arr['total_amount'] = $accumulated;
                break;
            
            default:
                // Quarterly Compound
                $n=4;
                $r=$rate/100;
                $accumulated = $investment*(pow((1+($r/$n)),($month/3)));
                $arr['principal'] = $investment;
                $arr['interest'] = $accumulated - $investment;
                $arr['total_amount'] = $accumulated;
                 
        }
        return $arr;
    }
    
    //$this->calculate_maturity_amount(96.15,12,15,'Weekly');
    function calculate_maturity_amount($investment=0,$month=12,$rate=9,$plan_type='Fixed'){
        $accumulated=array();
        
        switch ($plan_type) {
            case 'Weekly':
                $accumulated=$this->calculate_interest($investment,$month,$rate,$interest_type='WFlat');
                break;
            case 'Daily':
                
                $accumulated = $this->calculate_interest($investment,$month,$rate,$interest_type='Simple');
                break;
            case 'Monthly':
                $accumulated = $this->calculate_interest($investment,$month,$rate,$interest_type='Compound');
                
                break;
            case 'MIS':
                $accumulated = $this->calculate_interest($investment,$month,$rate,$interest_type='Flat');
                break;
           
            default:
                // Fixed Deposit
                $accumulated = $this->calculate_interest($investment,$month,$rate,$interest_type='Quarterly Compound');
                 
        }
        return $accumulated;
        
    }
    // Create all loan instalment 
    public function create_loan_transaction($loan_id = '', $start_date)
    {
        $loan_data = $this->Loan->find('first', array('conditions' => array('Loan.id' =>
                    $loan_id)));

        $loan_principal = $loan_data['Loan']['loan_principal'];
        $loan_interest = $loan_data['Loan']['loan_interest'];
        $inst_amount = $loan_data['Loan']['loan_rate'];
        $repaytotal_amount = $loan_data['Loan']['loan_repay_total'];
        $currency = $loan_data['Loan']['currency'];
        $period_unit = $loan_data['Loan']['loan_period_unit'];
        $loan_period = $loan_data['Loan']['loan_period'];
        $loan_type = $loan_data['Loan']['interest_type'];
        $loan_no = $loan_data['Loan']['loan_number'];
        $loan_dateout = $loan_data['Loan']['loan_dateout'];
        $loan_account_id = $loan_data['Loan']['account_id'];
        //Create the EMI amount
        $e = 1;
        $temp_principal = $loan_principal;
        $start_date = date("Y-m-d", strtotime($start_date));
        $temp_start_date = $start_date;
        while ($e <= $loan_period) {
            if ($loan_type == 'Fixed' || $loan_type == 'FIXED') {

                $intr = round($loan_principal * $loan_interest / 100);
                //$instal_principal = round($loan_principal / $loan_period);
                $instal_interest = round($intr / $loan_period);
                $instal_principal = $inst_amount-$instal_interest;

            } else {
                if ($period_unit == 'WEEK') {
                    $l = 1 / 52;
                    $i = $loan_interest / 100 * $l;
                    $instal_interest = $temp_principal * $i;
                    $instal_principal = round($inst_amount - $instal_interest);
                    $temp_principal = $temp_principal - $instal_principal;

                } else {  // MONTH
                    $l = 1 / 12;
                    $i = $loan_interest / 100 * $l;
                    $instal_interest = round($temp_principal * $i);
                    $instal_principal = round($inst_amount - $instal_interest);
                    $temp_principal = $temp_principal - $instal_principal;
                }
            }
            if($e==1){
                $arr = array(
                    
                    'Loan.overdue_principal' => $instal_principal,
                    'Loan.overdue_interest' => $instal_interest,
                    
                );
            }
            //Insert into LoanTransaction
            $trans_data['LoanTransaction']['account_id'] = $loan_account_id;
            $trans_data['LoanTransaction']['loan_id'] = $loan_data['Loan']['id'];
            $trans_data['LoanTransaction']['insta_no'] = $e;
            $trans_data['LoanTransaction']['insta_due_on'] = $start_date;
            $trans_data['LoanTransaction']['total_installment'] = $loan_data['Loan']['loan_rate'];
            $trans_data['LoanTransaction']['insta_principal_due'] = $instal_principal;
            $trans_data['LoanTransaction']['insta_interest_due'] = $instal_interest;
            $trans_data['LoanTransaction']['customer_id'] = $loan_data['Loan']['customer_id'];
            $trans_data['LoanTransaction']['organization_id'] = $loan_data['Loan']['organization_id'];
            $trans_data['LoanTransaction']['region_id'] = $loan_data['Loan']['region_id'];
            $trans_data['LoanTransaction']['branch_id'] = $loan_data['Loan']['branch_id'];
            $trans_data['LoanTransaction']['market_id'] = $loan_data['Loan']['market_id'];
            $trans_data['LoanTransaction']['kendra_id'] = $loan_data['Loan']['kendra_id'];
            $trans_data['LoanTransaction']['created_on'] = date("Y-m-d H:i:s");
            $this->LoanTransaction->clear();
            $this->LoanTransaction->save($trans_data);
            
            

            if ($period_unit == 'WEEK') {

                $date = strtotime($start_date);
                $date = strtotime("+1 Week", $date);

            } else {

                $date = strtotime($start_date);
                $date = strtotime("+1 month", $date);
            }
            $start_date = date("Y-m-d", $date);
            // Loan transaction end

            $e++;
        }
        //die;
        
        //Update Loan Table
        if(strtotime($temp_start_date)< strtotime(date("Y-m-d"))){
            $this->Loan->clear();
            $this->Loan->updateAll(
                $arr, 
                array('Loan.id' => $loan_data['Loan']['id']));
            
        }
        

    }
	// Create loan instalment end
    
    // Customer Saving Summary Function
    public function customer_saving_summary($cust_id)
    {
        $cust_data = $this->Customer->find('first', array('
			conditions' => array('Customer.id' => $cust_id)));
        $savingdetails = array();

        $saving_data = $this->Saving->find('all', array('conditions' => array('Saving.customer_id' => $cust_id, 'Saving.status' => 1)));
        //pr($saving_data); die;
        
        if (!empty($saving_data)) {
			//pr($saving_data);die;
            foreach ($saving_data as $kcust => $saving_row) {
                $saving_cust = $saving_row['Saving'];
                $saving_id = $saving_cust['id'];
				//pr($saving_cust);die;
				if($saving_cust['account_id']== 0){
					$saving_number= 'No Set';
				} else {
					
					$saving_number= $saving_row['Account']['account_number'];
				}
				$saving_plan= json_decode($saving_cust['plan'], true);
                $savingdetails[$kcust]['saving_id'] = $saving_cust['id'];
				$savingdetails[$kcust]['account_id'] = $saving_cust['account_id'];
				$savingdetails[$kcust]['account_number'] = $saving_number;
                $savingdetails[$kcust]['savings_amount'] = $saving_cust['savings_amount'];
                $savingdetails[$kcust]['current_balance'] = $saving_cust['current_balance'];
                $savingdetails[$kcust]['deposit_interval'] = $saving_cust['deposit_interval']; // in days
                $savingdetails[$kcust]['savings_term'] = $saving_cust['savings_term']; // in month
                $savingdetails[$kcust]['interest_type'] = $saving_cust['interest_type']; 
                $savingdetails[$kcust]['saving_type'] = $saving_plan['saving']['saving_type']; 
                $savingdetails[$kcust]['maturity_amount'] = $saving_cust['maturity_amount']; 
                $savingdetails[$kcust]['maturity_date'] = $saving_cust['maturity_date']; 
                $savingdetails[$kcust]['savings_date'] = $saving_cust['savings_date']; 
                
                $full_val=$this->calculate_interest($saving_cust['savings_amount'],$saving_cust['savings_term'],$saving_cust['interest_rate'],$interest_type=$saving_plan['saving']['interest_type']);
				
                
                $savingdetails[$kcust]['maturity_interest'] = $full_val['interest']; 
                $savingdetails[$kcust]['last_paid_date'] = $saving_cust['modified_on'];
				                
                
                $till_date = $this->calculate_interest_check_today($saving_cust['id']); 
                //pr($till_date);die;
                $savingdetails[$kcust]['interest_till_date'] = round($till_date['interest'],2);
				//pr($savingdetails);die;
            }
        }
        return $savingdetails;
    }
	// Customer Saving Summery End
	
	// Calculate month difference function start
	public function cal_month($date1,$date2){
		//$date1 = '2000-01-25';
		//$date2 = '2010-02-20';
		$ts1 = strtotime($date1);
		$ts2 = strtotime($date2);
		$year1 = date('Y', $ts1);
		$year2 = date('Y', $ts2);
		$month1 = date('m', $ts1);
		$month2 = date('m', $ts2);
		$diff = (($year2 - $year1) * 12) + ($month2 - $month1);
		return $diff;
	}
    
    public function get_loan_snapshot_data($loan_id,$date='')
    {
        if($date==''){
           $date =date("Y-m-d");
        }
        $this->Loan->unBindModel(array(
        'belongsTo' => array('Organization', 'Region','Branch','Market','Kendra')    
        ));
        
        $loan_data=$this->Loan->find("first", array('conditions'=>array('Loan.id'=>$loan_id)));
        $account_id = $loan_data['Loan']['account_id'];
        
        $trans_id=$this->LoanTransaction->find("first", array('fields'=>array('max(LoanTransaction.id) as trans_id'),'conditions'=>array('LoanTransaction.account_id'=>$account_id,'LoanTransaction.insta_interest_paid >'=>0), "recursive"=> -1));
        $trans_id_data = $this->LoanTransaction->findById($trans_id[0]['trans_id']);
        
        $trans_paid_arr = $trans_id_data['LoanTransaction'];
        $realise_amount = $loan_data['Loan']['loan_repay_total']-$trans_paid_arr['current_outstanding'];
        $loan_overdue = $this->LoanTransaction->find('all',array('fields'=>array('SUM(LoanTransaction.total_installment) as total_realisable'),'conditions'=>array('LoanTransaction.account_id'=>$account_id,'LoanTransaction.insta_due_on <='=>$date,'LoanTransaction.insta_due_on !='=>'0000-00-00')));
        $realisable_amount = $loan_overdue[0][0]['total_realisable'];
        $current_overdue = $realisable_amount - $realise_amount;
        
        $arr['realise_amount']=$realise_amount;
        $arr['realisable_amount']=$realisable_amount;
        $arr['current_overdue']=$current_overdue;
        $arr['last_paid_date']=$trans_paid_arr['insta_paid_on'];
        $loan_data['overdue'] = $arr;
        
        return $loan_data;
    }
    
    public function get_loan_transaction_data($account_id,$limit='',$order='asc'){
        $this->LoanTransaction->bindModel(
            array('belongsTo' => array(
                    'User' => array(
                        'className' => 'User',
                        'foreign_key'=>'user_id'
                    )
                )
            )
        );
        $loan_trans1 = $this->LoanTransaction->find('all',array('conditions'=>array('LoanTransaction.account_id'=>$account_id,'LoanTransaction.insta_paid_on !='=>'0000-00-00'),'order'=>array("LoanTransaction.insta_paid_on $order,LoanTransaction.id")));
        $this->LoanTransaction->bindModel(
            array('belongsTo' => array(
                    'User' => array(
                        'className' => 'User',
                        'foreign_key'=>'user_id'
                    )
                )
            )
        );
        $loan_trans2 = $this->LoanTransaction->find('all',array('conditions'=>array('LoanTransaction.account_id'=>$account_id,'LoanTransaction.insta_paid_on'=>'0000-00-00'),'order'=>array("LoanTransaction.insta_no $order"),'limit'=>$limit));
        $loan_trans = array_merge($loan_trans1,$loan_trans2);
        return $loan_trans;
    }
    
    // Loan installment collection
    public function loan_installment_collection($account_id,$repay_amount=0,$transaction_date,$notes='',$fine=0){
        
        $current_date_time=date("Y-m-d H:i:s");
        // Saving Saving Table Data
	   $loan_data=$this->Loan->find("first", array('conditions'=>array('Loan.account_id'=>$account_id), "recursive"=> -1));
       $loan_repay_interest = 0;
       $loan_repay_principal = 0;
       $loan_repay_interest_due=0;
       $loan_repay_principal_due = 0;
       $temp_repay_amount = $repay_amount;
       $temp_fine = $fine;
       
       $total_prici_paid =0;
       $total_int_paid =0;
       
       $overdue_principal_amount = 0;
       $overdue_interest_amount = 0;
       
       $today_overdue_principal =0;
       $today_overdue_interest =0;
       $overdue_paid = 0;
       $prepayment = 0;
            
       if($repay_amount>0){
            $paid_data=$this->LoanTransaction->find("all", array('fields'=>array('sum(LoanTransaction.insta_principal_paid+LoanTransaction.insta_interest_paid) as total_paid_amount'),'conditions'=>array('LoanTransaction.account_id'=>$account_id), "recursive"=> -1));
            $due_amount = $loan_data['Loan']['loan_repay_total']-$paid_data[0][0]['total_paid_amount'];
            
            
            $trans_id=$this->LoanTransaction->find("first", array('fields'=>array('max(LoanTransaction.id) as trans_id'),'conditions'=>array('LoanTransaction.account_id'=>$account_id,'LoanTransaction.insta_interest_paid >'=>0), "recursive"=> -1));
            $trans_id_data = $this->LoanTransaction->findById($trans_id[0]['trans_id']);
            
            $trans_paid_arr = $trans_id_data['LoanTransaction'];
            
                        
            $trans_id_data3=$this->LoanTransaction->find("first", array('fields'=>array('LoanTransaction.*'),'conditions'=>array('LoanTransaction.account_id'=>$account_id,'LoanTransaction.insta_due_on'=>$transaction_date,'LoanTransaction.insta_interest_paid'=>0), "recursive"=> -1));
            
            
            $outstanding_amount = $trans_paid_arr['current_outstanding']-$repay_amount;
            
            // Inerest paid Calculation
            if($repay_amount>=$trans_paid_arr['insta_interest_due']){
                $loan_repay_interest = $trans_paid_arr['insta_interest_due'];
                $repay_amount = intval(intval($repay_amount) - intval($loan_repay_interest));
            }else{
                $loan_repay_interest = $repay_amount;
                $today_overdue_interest = $trans_paid_arr['insta_interest_due']-$loan_repay_interest;
                $repay_amount = 0;
            }
            
            // Principal paid Calculation
            if($repay_amount>=$trans_paid_arr['insta_principal_due']){
                $loan_repay_principal = $trans_paid_arr['insta_principal_due'];
                $repay_amount = intval(intval($repay_amount) - intval($loan_repay_principal));
                
            }else{
                $loan_repay_principal = $repay_amount;
                $today_overdue_principal = $trans_paid_arr['insta_principal_due']-$loan_repay_principal;
                $repay_amount = 0;
            }
            
            
            
            // Overdue Calculation Start
            if($temp_repay_amount==$loan_data['Loan']['loan_rate']){
                $overdue_principal_amount = $trans_paid_arr['overdue_principal'];
                $overdue_interest_amount = $trans_paid_arr['overdue_interest'];
            }else{
                if($repay_amount>=($trans_paid_arr['overdue_interest']+$today_overdue_interest)){
                    $overdue_interest_amount = 0;
                    $repay_amount = intval(intval($repay_amount) - intval($trans_paid_arr['overdue_interest']+$today_overdue_interest));
                    $overdue_paid = ($trans_paid_arr['overdue_interest']+$today_overdue_interest);
                }else{
                    
                    $overdue_interest_amount = ($trans_paid_arr['overdue_interest']+$today_overdue_interest)- $repay_amount;
                    
                    $overdue_paid = $repay_amount;
                    $repay_amount = 0;
                }
                
                
                if($repay_amount>=($trans_paid_arr['overdue_principal']+$today_overdue_principal)){
                    $overdue_principal_amount = 0;
                    $overdue_paid += ($trans_paid_arr['overdue_principal']+$today_overdue_principal);
                    $repay_amount = intval(intval($repay_amount) - intval($trans_paid_arr['overdue_principal']+$today_overdue_principal));
                }else{
                    $overdue_principal_amount = ($trans_paid_arr['overdue_principal']+$today_overdue_principal)-$repay_amount;
                    $overdue_paid += $repay_amount;
                    $repay_amount = 0;
                }
                
                if($overdue_principal_amount<0){
                    $overdue_principal_amount = 0;
                }
                if($overdue_interest_amount<0){
                    $overdue_interest_amount = 0;
                }
            }
            if($repay_amount>0){
                $prepayment = $repay_amount;
            }
             
            // Overdue Calculation End
            
           
           
            // Saving Loan Transaction Data
            $save_trans['LoanTransaction']['current_outstanding']=$outstanding_amount;
            $save_trans['LoanTransaction']['is_due']=($repay_amount<$loan_data['Loan']['loan_rate'])?1:0;
             
            if($fine>0){
                $save_trans['LoanTransaction']['insta_fined'] = $fine;
                $fine=0;
            }else{
                $save_trans['LoanTransaction']['insta_fined'] = 0;
            }
            if($notes!='') {
                $save_trans['LoanTransaction']['notes'] = $notes;
                $notes='';
            }else{
                $save_trans['LoanTransaction']['notes'] = '';
            }
            //echo $save_trans['LoanTransaction']['insta_fined'];die;
            
            
            
            if(!empty($trans_id_data3)){

                $this->LoanTransaction->clear();
                $this->LoanTransaction->updateAll(
                array(
                    'LoanTransaction.insta_paid_on' => "'".$transaction_date."'",
                    'LoanTransaction.insta_principal_paid' => $loan_repay_principal,
                    'LoanTransaction.insta_interest_paid' => $loan_repay_interest,
                    'LoanTransaction.current_outstanding' => $outstanding_amount,
                    'LoanTransaction.overdue_principal' => $overdue_principal_amount,
                    'LoanTransaction.overdue_interest' => $overdue_interest_amount,
                    'LoanTransaction.overdue_paid' => $overdue_paid,
                    'LoanTransaction.prepayment' => $prepayment,
                    'LoanTransaction.insta_fined' => $save_trans['LoanTransaction']['insta_fined'],
                    'LoanTransaction.notes' => "'".$save_trans['LoanTransaction']['notes']."'",
                    'LoanTransaction.modified_on' => "'".$current_date_time."'",
                    'LoanTransaction.user_id' => $this->Auth->user('id'),
                    'LoanTransaction.is_due' => $save_trans['LoanTransaction']['is_due'],
                    'LoanTransaction.is_delay' => 0
                ), 
                array('LoanTransaction.id' => $trans_id_data3['LoanTransaction']['id']));
            }else{
                $trans_data['LoanTransaction']['insta_paid_on'] = date("Y-m-d",strtotime($transaction_date));
                $trans_data['LoanTransaction']['insta_principal_paid'] = $loan_repay_principal;
                $trans_data['LoanTransaction']['insta_interest_paid'] = $loan_repay_interest;
                $trans_data['LoanTransaction']['current_outstanding'] = $outstanding_amount;
                $trans_data['LoanTransaction']['overdue_principal'] = $overdue_principal_amount;
                $trans_data['LoanTransaction']['overdue_interest'] = $overdue_interest_amount;
                $trans_data['LoanTransaction']['overdue_paid'] = $overdue_paid;
                $trans_data['LoanTransaction']['prepayment'] = $prepayment;
                $trans_data['LoanTransaction']['insta_fined'] = $save_trans['LoanTransaction']['insta_fined'];
                $trans_data['LoanTransaction']['notes'] = !empty($save_trans['LoanTransaction']['notes'])?"'".$save_trans['LoanTransaction']['notes']."'":'';
                $trans_data['LoanTransaction']['modified_on'] = "'".$current_date_time."'";
                $trans_data['LoanTransaction']['is_due'] = $save_trans['LoanTransaction']['is_due'];
                $trans_data['LoanTransaction']['user_id'] = $this->Auth->user('id');
                $trans_data['LoanTransaction']['is_delay'] = 1;
                
                $trans_data['LoanTransaction']['account_id'] = $account_id;
                $trans_data['LoanTransaction']['loan_id'] = $loan_data['Loan']['id'];
                $trans_data['LoanTransaction']['insta_no'] = 0;
                $trans_data['LoanTransaction']['insta_due_on'] = '0000-00-00';
                $trans_data['LoanTransaction']['total_installment'] = $loan_data['Loan']['loan_rate'];
                $trans_data['LoanTransaction']['insta_principal_due'] = $trans_paid_arr['insta_principal_due'];
                $trans_data['LoanTransaction']['insta_interest_due'] = $trans_paid_arr['insta_interest_due'];
                $trans_data['LoanTransaction']['customer_id'] = $loan_data['Loan']['customer_id'];
                $trans_data['LoanTransaction']['organization_id'] = $loan_data['Loan']['organization_id'];
                $trans_data['LoanTransaction']['region_id'] = $loan_data['Loan']['region_id'];
                $trans_data['LoanTransaction']['branch_id'] = $loan_data['Loan']['branch_id'];
                $trans_data['LoanTransaction']['market_id'] = $loan_data['Loan']['market_id'];
                $trans_data['LoanTransaction']['kendra_id'] = $loan_data['Loan']['kendra_id'];
                $trans_data['LoanTransaction']['created_on'] = date("Y-m-d H:i:s");
                //pr($trans_data);die;
                $this->LoanTransaction->clear();
                $this->LoanTransaction->save($trans_data);
                
            }
            
            //Update Loan Table
            $this->Loan->clear();
            $this->Loan->updateAll(
                array(
                    
                    'Loan.current_outstanding' => $outstanding_amount,
                    'Loan.overdue_principal' => $overdue_principal_amount,
                    'Loan.overdue_interest' => $overdue_interest_amount,
                    
                ), 
                array('Loan.id' => $loan_data['Loan']['id']));
            
       }
       
       $total_prici_paid = $loan_repay_principal+((($trans_paid_arr['overdue_principal']-$overdue_principal_amount)<0)?0:($trans_paid_arr['overdue_principal']-$overdue_principal_amount));
       $total_int_paid = $loan_repay_interest+((($trans_paid_arr['overdue_interest']-$overdue_interest_amount)<0)?0:($trans_paid_arr['overdue_interest']-$overdue_interest_amount));
       //Saving Income Expenditure Data 
	   $exp_data['IncomeExpenditure']['account_ledger_id']=2;
       $exp_data['IncomeExpenditure']['account_id']=$account_id;
	   $exp_data['IncomeExpenditure']['credit_amount']=$total_prici_paid;
	   $exp_data['IncomeExpenditure']['transaction_date']=$transaction_date;
	   $exp_data['IncomeExpenditure']['balance']=$total_prici_paid;
	   $exp_data['IncomeExpenditure']['organization_id']=$loan_data['Loan']['organization_id'];
	   $exp_data['IncomeExpenditure']['region_id']=$loan_data['Loan']['region_id'];
	   $exp_data['IncomeExpenditure']['branch_id']=$loan_data['Loan']['branch_id'];
	   $exp_data['IncomeExpenditure']['market_id']=$loan_data['Loan']['market_id'];
	   $exp_data['IncomeExpenditure']['created_on']=$current_date_time;
	   $exp_data['IncomeExpenditure']['user_id']=$loan_data['Loan']['user_id'];
       $this->IncomeExpenditure->clear();
	   $this->IncomeExpenditure->save($exp_data);
       $exp_data= array();
       //Saving Income Expenditure Data 
	   $exp_data['IncomeExpenditure']['account_ledger_id']=7;
       $exp_data['IncomeExpenditure']['account_id']=$account_id;
	   $exp_data['IncomeExpenditure']['credit_amount']=$total_int_paid;
	   $exp_data['IncomeExpenditure']['transaction_date']=$transaction_date;
	   $exp_data['IncomeExpenditure']['balance']=$total_int_paid;
	   $exp_data['IncomeExpenditure']['organization_id']=$loan_data['Loan']['organization_id'];
	   $exp_data['IncomeExpenditure']['region_id']=$loan_data['Loan']['region_id'];
	   $exp_data['IncomeExpenditure']['branch_id']=$loan_data['Loan']['branch_id'];
	   $exp_data['IncomeExpenditure']['market_id']=$loan_data['Loan']['market_id'];
	   $exp_data['IncomeExpenditure']['created_on']=$current_date_time;
	   $exp_data['IncomeExpenditure']['user_id']=$loan_data['Loan']['user_id'];
       $this->IncomeExpenditure->clear();
	   $this->IncomeExpenditure->save($exp_data);
       
       if($temp_fine>0){
            $fine_data['IncomeExpenditure']['account_ledger_id']=3;
           $fine_data['IncomeExpenditure']['account_id']=$account_id;
    	   $fine_data['IncomeExpenditure']['credit_amount']=$temp_fine;
    	   $fine_data['IncomeExpenditure']['transaction_date']=$transaction_date;
    	   $fine_data['IncomeExpenditure']['balance']=$temp_fine;
    	   $fine_data['IncomeExpenditure']['organization_id']=$loan_data['Loan']['organization_id'];
    	   $fine_data['IncomeExpenditure']['region_id']=$loan_data['Loan']['region_id'];
    	   $fine_data['IncomeExpenditure']['branch_id']=$loan_data['Loan']['branch_id'];
    	   $fine_data['IncomeExpenditure']['market_id']=$loan_data['Loan']['market_id'];
    	   $fine_data['IncomeExpenditure']['created_on']=$current_date_time;
    	   $fine_data['IncomeExpenditure']['user_id']=$loan_data['Loan']['user_id'];
           $this->IncomeExpenditure->clear();
    	   $this->IncomeExpenditure->save($fine_data);
       }
       $this->check_loan_payments_and_clear($account_id);
       //pr($exp_data);
       return $outstanding_amount;
    }
    
    // Function for check if all the loan instalment is paid then the loan will closed START
    public function check_loan_payments_and_clear($account_id)
    {
        $loan_trans = $this->LoanTransaction->find('all', array('fields'=>array('SUM(LoanTransaction.insta_principal_paid + LoanTransaction.insta_interest_paid) as insta_realise','SUM(LoanTransaction.insta_principal_due + LoanTransaction.insta_interest_due) as insta_realisable'),'conditions' => array
                ('LoanTransaction.account_id' => $account_id)));
        $loan_data = $this->Loan->find('first', array('conditions' => array
                ('Loan.account_id' => $account_id)));
        
        $trans_id=$this->LoanTransaction->find("first", array('fields'=>array('max(LoanTransaction.id) as trans_id'),'conditions'=>array('LoanTransaction.account_id'=>$account_id,'LoanTransaction.insta_interest_paid >'=>0), "recursive"=> -1));
        $trans_id_data = $this->LoanTransaction->findById($trans_id[0]['trans_id']);
        
        $trans_paid_arr = $trans_id_data['LoanTransaction'];
                
        if ($trans_paid_arr['current_outstanding'] == 0) {
            
            $this->Loan->clear();
            $this->Loan->updateAll(
                array(
                    'Loan.loan_status_id' => 6,
                    'Loan.modified_on' => date("Y-m-d h:i:s"),
                ), 
                array('Loan.id' => $loan_data['Loan']['id']));
        }
    }
	// Function for check if all the loan instalment is paid then the loan will closed END
    
    public function saving_amount_collection($account_id,$saving_amount,$transaction_data,$notes='',$fine=0,$user_id=''){
        
        $current_date_time=date("Y-m-d H:i:s");
        if($user_id==''){
            $user_id = $this->Auth->user('id');
        }
        
        $account_data=$this->Account->find("first", array('conditions'=>array('Account.id'=>$account_id), "recursive"=> -1));
	   $customer_id=$account_data['Account']['customer_id'];
	   $organization_id=$account_data['Account']['organization_id'];
	   $region_id=$account_data['Account']['region_id'];
	   $branch_id=$account_data['Account']['branch_id'];
	   $market_id=$account_data['Account']['market_id'];
	   $plan_amount=$account_data['Account']['plan_amount'];
	   $interest_rate=$account_data['Account']['interest_rate'];
	   $exces_interest=$account_data['Account']['exces_interest'];
       
       $temp_fine = $fine;
	   // Saving Saving Table Data
	   $saving_data=$this->Saving->find("first", array('conditions'=>array('Saving.account_id'=>$account_id), "recursive"=> -1));
	   $save_data['Saving']['id']=$saving_data['Saving']['id'];
	   $save_data['Saving']['current_balance']=$saving_data['Saving']['current_balance']+$saving_amount;
       $save_data['Saving']['modified_on'] = $current_date_time;
	   $this->Saving->save($save_data);
	   // Saving Saving Transaction Data
	   $trans_data['SavingsTransaction']['account_id']=$account_id;
	   $trans_data['SavingsTransaction']['saving_id']=$saving_data['Saving']['id'];
	   $trans_data['SavingsTransaction']['transaction_on']=$transaction_data;
	   $trans_data['SavingsTransaction']['amount']=$saving_amount;
        $trans_data['SavingsTransaction']['notes']=$notes;
        $trans_data['SavingsTransaction']['fined']=$fine;
	   $trans_data['SavingsTransaction']['transaction_type']='CREDIT';
	   $trans_data['SavingsTransaction']['balance']=$save_data['Saving']['current_balance'];
	   $trans_data['SavingsTransaction']['customer_id']=$customer_id;
	   $trans_data['SavingsTransaction']['organization_id']=$organization_id;
	   $trans_data['SavingsTransaction']['region_id']=$region_id;
	   $trans_data['SavingsTransaction']['branch_id']=$branch_id;
	   $trans_data['SavingsTransaction']['market_id']=$market_id;
	   $trans_data['SavingsTransaction']['created_on']=$current_date_time;
	   $trans_data['SavingsTransaction']['user_id']=$user_id;
	   $this->SavingsTransaction->save($trans_data);
	   //Saving Income Expenditure Data 
	   $exp_data['IncomeExpenditure']['account_ledger_id']=1;
       $exp_data['IncomeExpenditure']['account_id']=$account_id;
	   $exp_data['IncomeExpenditure']['credit_amount']=$saving_amount;
	   $exp_data['IncomeExpenditure']['transaction_date']=$transaction_data;
	   $exp_data['IncomeExpenditure']['balance']=$save_data['Saving']['current_balance'];
	   $exp_data['IncomeExpenditure']['organization_id']=$organization_id;
	   $exp_data['IncomeExpenditure']['region_id']=$region_id;
	   $exp_data['IncomeExpenditure']['branch_id']=$branch_id;
	   $exp_data['IncomeExpenditure']['market_id']=$market_id;
	   $exp_data['IncomeExpenditure']['created_on']=$current_date_time;
	   $exp_data['IncomeExpenditure']['user_id']=$account_data['Account']['user_id'];
	   $this->IncomeExpenditure->save($exp_data);
       
       if($temp_fine>0){
           $fine_data['IncomeExpenditure']['account_ledger_id']=3;
           $fine_data['IncomeExpenditure']['account_id']=$account_id;
    	   $fine_data['IncomeExpenditure']['credit_amount']=$temp_fine;
    	   $fine_data['IncomeExpenditure']['transaction_date']=$transaction_data;
    	   $fine_data['IncomeExpenditure']['balance']=$temp_fine;
    	   $fine_data['IncomeExpenditure']['organization_id']=$organization_id;
    	   $fine_data['IncomeExpenditure']['region_id']=$region_id;
    	   $fine_data['IncomeExpenditure']['branch_id']=$branch_id;
    	   $fine_data['IncomeExpenditure']['market_id']=$market_id;
    	   $fine_data['IncomeExpenditure']['created_on']=$current_date_time;
    	   $fine_data['IncomeExpenditure']['user_id']=$account_data['Account']['user_id'];
           $this->IncomeExpenditure->clear();
    	   $this->IncomeExpenditure->save($fine_data);
       }
	   //Saving Extra Income Data
	   if($saving_amount>$plan_amount){
		   $extra_data['ExtraAmount']['account_id']=$account_id;
		   $extra_data['ExtraAmount']['customer_id']=$customer_id;
		   $extra_data['ExtraAmount']['amount']=$saving_amount-$plan_amount;
		   $extra_data['ExtraAmount']['paid_on']=$transaction_data;
		   $extra_data['ExtraAmount']['interest_rate']=$exces_interest;
		   $extra_data['ExtraAmount']['created_on']=$current_date_time;
		   $extra_data['ExtraAmount']['modified_on']=$current_date_time;
		   $this->ExtraAmount->save($extra_data);
	   }
       return $save_data['Saving']['current_balance'];
    }
    
    public function saving_amount_withdraw($account_id,$saving_amount,$transaction_data){
        
        $current_date_time=date("Y-m-d H:i:s");
        
        $account_data=$this->Account->find("first", array('conditions'=>array('Account.id'=>$account_id), "recursive"=> -1));
	   $customer_id=$account_data['Account']['customer_id'];
	   $organization_id=$account_data['Account']['organization_id'];
	   $region_id=$account_data['Account']['region_id'];
	   $branch_id=$account_data['Account']['branch_id'];
	   $market_id=$account_data['Account']['market_id'];
	   $plan_amount=$account_data['Account']['plan_amount'];
	   $interest_rate=$account_data['Account']['interest_rate'];
	   $exces_interest=$account_data['Account']['exces_interest'];
	   // Saving Saving Table Data
	   $saving_data=$this->Saving->find("first", array('conditions'=>array('Saving.account_id'=>$account_id), "recursive"=> -1));
	   $this->request->data['Saving']['id']=$saving_data['Saving']['id'];
	   $this->request->data['Saving']['current_balance']=$saving_data['Saving']['current_balance']-$saving_amount;
       $this->request->data['Saving']['modified_on'] = $current_date_time;
	   $this->Saving->save($this->request->data);
	   // Saving Saving Transaction Data
	   $this->request->data['SavingsTransaction']['account_id']=$account_id;
	   $this->request->data['SavingsTransaction']['saving_id']=$saving_data['Saving']['id'];
	   $this->request->data['SavingsTransaction']['transaction_on']=$transaction_data;
	   $this->request->data['SavingsTransaction']['amount']=$saving_amount;
	   $this->request->data['SavingsTransaction']['transaction_type']='DEBIT';
	   $this->request->data['SavingsTransaction']['balance']=$this->request->data['Saving']['current_balance'];
	   $this->request->data['SavingsTransaction']['customer_id']=$customer_id;
	   $this->request->data['SavingsTransaction']['organization_id']=$organization_id;
	   $this->request->data['SavingsTransaction']['region_id']=$region_id;
	   $this->request->data['SavingsTransaction']['branch_id']=$branch_id;
	   $this->request->data['SavingsTransaction']['market_id']=$market_id;
	   $this->request->data['SavingsTransaction']['created_on']=$current_date_time;
	   $this->request->data['SavingsTransaction']['user_id']=$this->Auth->user('id');
	   $this->SavingsTransaction->save($this->request->data);
	   //Saving Income Expenditure Data 
	   $this->request->data['IncomeExpenditure']['account_ledger_id']=1;
       $this->request->data['IncomeExpenditure']['account_id']=$account_id;
	   $this->request->data['IncomeExpenditure']['debit_amount']=$saving_amount;
	   $this->request->data['IncomeExpenditure']['transaction_date']=$transaction_data;
	   $this->request->data['IncomeExpenditure']['balance']=$this->request->data['Saving']['current_balance'];
	   $this->request->data['IncomeExpenditure']['organization_id']=$organization_id;
	   $this->request->data['IncomeExpenditure']['region_id']=$region_id;
	   $this->request->data['IncomeExpenditure']['branch_id']=$branch_id;
	   $this->request->data['IncomeExpenditure']['market_id']=$market_id;
	   $this->request->data['IncomeExpenditure']['created_on']=$current_date_time;
	   $this->request->data['IncomeExpenditure']['user_id']=$account_data['Account']['user_id'];
	   $this->IncomeExpenditure->save($this->request->data);
	   //Saving Extra Income Data
	   
       return $this->request->data['Saving']['current_balance'];
    }
    
	
	// Calculate Interest till now function start
	function calculate_interest_till($investment=0,$month=12,$rate=9,$interest_type='Quarterly Compound'){
        $arr = array('principal'=>$investment,'interest'=>0,'total_amount'=>0);
        $accumulated = 0;
        $year=$month/12;
        switch ($interest_type) {
            case 'Flat':
                $interest=($investment*$rate/100)*$year;
                $arr['principal'] = $investment;
                $arr['interest'] = $interest;
                $arr['interest_per_month'] = $interest/$month;
                $arr['total_amount'] = $investment+$interest;
                break;
            case 'WFlat':
                $week = $year*52;
                $interest=($investment*$week)*($rate/100)*$year;
                $arr['principal'] = ($investment*$week);
                $arr['interest'] = $interest;
                $arr['total_amount'] = ($investment*$week)+$interest;
                break;
            case 'Simple':
                
                $cum_val=$investment;
                $accumulated=$cum_val*$rate/100/12;
                
                $arr['principal'] = $cum_val;
                $arr['interest'] = $accumulated;
                $arr['total_amount'] = $cum_val;
                break;
            case 'Compound':
                $accumulated = $investment*(pow($rate/400+1,$month/3)-1)*(1200/$rate+2);
                $arr['principal'] = $investment*$month;
                $arr['interest'] = $accumulated - ($investment*$month);
                $arr['total_amount'] = $accumulated;
                break;
            
            default:
                // Quarterly Compound
                $n=4;
                $r=$rate/100;
                $accumulated = $investment*(pow((1+($r/$n)),($month/3)));
                $arr['principal'] = $investment;
                $arr['interest'] = $accumulated - $investment;
                $arr['total_amount'] = $accumulated;
                 
        }
        return $arr;
    }
	// Calculate Interest till now function end
    
    function calculate_interest_check_today($saving_id=''){
        $saving_data = $this->Saving->findById($saving_id);
        $accum_arr = $this->SavingsTransaction->find('all',array('fields'=>array('SUM(SavingsTransaction.balance) as cummulative_bal'),'conditions'=>array('SavingsTransaction.saving_id'=>$saving_id)));
        
        $investment = $saving_data['Saving']['savings_amount'];
        $accumulated = $accum_arr[0][0]['cummulative_bal'];
        $interest_type = $saving_data['Saving']['interest_type'];
        $date1 = $saving_data['Saving']['savings_date'];
        $rate = $saving_data['Saving']['interest_rate'];
        $date2 = date("Y-m-d");
        $month = $this->cal_month($date1, $date2);
        $arr = array('principal'=>$investment,'interest'=>0,'total_amount'=>0);
        //pr($accumulated);die;
        $year=$month/12;
        switch ($interest_type) {
            case 'Flat':
                $interest=($investment*$rate/100)*$year;
                $arr['principal'] = $saving_data['Saving']['current_balance'];
                $arr['interest'] = $interest;
                $arr['interest_per_month'] = $interest/$month;
                $arr['total_amount'] = $arr['principal']+$interest;
                break;
            case 'WFlat':
                $week = $year*52;
                $interest=($saving_data['Saving']['current_balance'])*($rate/100)*$year;
                $arr['principal'] = $saving_data['Saving']['current_balance'];
                $arr['interest'] = $interest;
                $arr['total_amount'] = $saving_data['Saving']['current_balance']+$interest;
                break;
            case 'Simple':
                
                $cum_val=$accumulated;
                $interst=$cum_val*$rate/100/12;
                
                $arr['principal'] = $saving_data['Saving']['current_balance'];
                $arr['interest'] = $interst;
                $arr['total_amount'] = $arr['principal']+$interst;
                break;
            case 'Compound':
                $accumulat = $investment*(pow($rate/400+1,$month/3)-1)*(1200/$rate+2);
                $arr['principal'] = $saving_data['Saving']['current_balance'];
                $arr['interest'] = $accumulat - $saving_data['Saving']['current_balance'];
                $arr['total_amount'] = $accumulat;
                break;
            
            default:
                // Quarterly Compound
                $n=4;
                $r=$rate/100;
                $accumulat = $investment*(pow((1+($r/$n)),($month/3)));
                $arr['principal'] = $saving_data['Saving']['current_balance'];
                $arr['interest'] = $accumulat - $saving_data['Saving']['current_balance'];
                $arr['total_amount'] = $accumulat;
                 
        }
        return $arr;
    }
    
    
    
    
    // overdue details by customer
    function customer_overdue_details($cust_id=''){
        $max_date = $this->LoanTransaction->find('all',array(
				'fields'=>array(
					'MAX(LoanTransaction.insta_paid_on) as max_date'
				),
				'conditions'=>array(
					
					'LoanTransaction.insta_paid_on !='=> '0000-00-00',
					 'Loan.loan_status_id'=>3,
					 'Loan.organization_id'=>$this->Auth->user('organization_id')
				),
				'joins' =>
							  array(
								array(
									'table' => 'loans',
									'alias' => 'Loan',
									'type' => 'inner',
									'foreignKey' => true,
									'conditions'=> array('Loan.id = LoanTransaction.loan_id')
				)),
			));
         $final_date=$max_date[0][0]['max_date']; 
         
        $loan_overdue = $this->LoanTransaction->find('all', 
                    array(
                        'fields' => array(
                            'MAX(LoanTransaction.insta_no) as max_insta_no', 
                            'SUM(LoanTransaction.insta_principal_paid + LoanTransaction.insta_interest_paid) as insta_realise',
                            'SUM(LoanTransaction.insta_principal_due + LoanTransaction.insta_interest_due) as insta_realisable'),
                        'conditions' => array(
                            'LoanTransaction.insta_due_on <=' => $final_date,
                            'Loan.loan_status_id'=>3,
                            'Loan.customer_id'=>$cust_id),
                        'joins' =>
							  array(
								array(
									'table' => 'loans',
									'alias' => 'Loan',
									'type' => 'inner',
									'foreignKey' => true,
									'conditions'=> array('Loan.id = LoanTransaction.loan_id')
						         )
                                )
                    )
                    );
        return $loan_overdue;
    }
    
    // overdue details by Branch
    function branch_overdue_details($branch_id='',$start_date='',$end_date=''){
        
        if($start_date=='' && $end_date==''){
            $max_date = $this->LoanTransaction->find('all',array(
    				'fields'=>array(
    					'MAX(LoanTransaction.insta_paid_on) as max_date'
    				),
    				'conditions'=>array(
    					
    					'LoanTransaction.insta_paid_on !='=> '0000-00-00',
    					 'Loan.loan_status_id'=>3,
    					 'Loan.organization_id'=>$this->Auth->user('organization_id')
    				),
    				'joins' =>
    							  array(
    								array(
    									'table' => 'loans',
    									'alias' => 'Loan',
    									'type' => 'inner',
    									'foreignKey' => true,
    									'conditions'=> array('Loan.id = LoanTransaction.loan_id')
    				)),
    			));
             $final_date=$max_date[0][0]['max_date']; 
             
             $condi = array(
                            'LoanTransaction.insta_due_on <= '=>$final_date,
                            'Loan.loan_status_id'=>3,
                            'Loan.branch_id'=>$branch_id);
                            
         }else{
            $condi = array(
                            'LoanTransaction.insta_due_on BETWEEN \''.$start_date.'\' AND \''.$end_date .'\'',
                            'Loan.loan_status_id'=>3,
                            'Loan.branch_id'=>$branch_id);
         }
         
        $loan_overdue = $this->LoanTransaction->find('all', 
                    array(
                        'fields' => array(
                            
                            '(SUM(LoanTransaction.insta_principal_paid)+SUM(LoanTransaction.insta_interest_paid)+SUM(LoanTransaction.overdue_paid)+SUM(LoanTransaction.prepayment)) as insta_realise',
 
                            'SUM(LoanTransaction.total_installment) as insta_realisable',
                            'SUM(LoanTransaction.insta_principal_due) as realizable_principal_amount',
                            'SUM(LoanTransaction.insta_interest_due) as realizable_interest_amount'
                            
                            
                            ),
                            
                        'conditions' => $condi,
                        'joins' =>
							  array(
								array(
									'table' => 'loans',
									'alias' => 'Loan',
									'type' => 'inner',
									'foreignKey' => true,
									'conditions'=> array('Loan.id = LoanTransaction.loan_id')
						         )
                                )
                    )
                    );
        return $loan_overdue;
    }

    
    // overdue details by Market
    function market_overdue_details($market_id='',$start_date='',$end_date=''){
        
        if($start_date=='' && $end_date==''){
            $max_date = $this->LoanTransaction->find('all',array(
    				'fields'=>array(
    					'MAX(LoanTransaction.insta_paid_on) as max_date'
    				),
    				'conditions'=>array(
    					
    					'LoanTransaction.insta_paid_on !='=> '0000-00-00',
    					 'Loan.loan_status_id'=>3,
    					 'Loan.organization_id'=>$this->Auth->user('organization_id')
    				),
    				'joins' =>
    							  array(
    								array(
    									'table' => 'loans',
    									'alias' => 'Loan',
    									'type' => 'inner',
    									'foreignKey' => true,
    									'conditions'=> array('Loan.id = LoanTransaction.loan_id')
    				)),
    			));
             $final_date=$max_date[0][0]['max_date']; 
             
             $condi = array(
                            'LoanTransaction.insta_due_on <= '=>$final_date,
                            'Loan.loan_status_id'=>3,
                            'Loan.market_id'=>$market_id);
                            
         }else{
            $condi = array(
                            'LoanTransaction.insta_due_on BETWEEN \''.$start_date.'\' AND \''.$end_date .'\'',
                            'Loan.loan_status_id'=>3,
                            'Loan.market_id'=>$market_id);
         }
         
        $loan_overdue = $this->LoanTransaction->find('all', 
                    array(
                        'fields' => array(
                            
                            '(SUM(LoanTransaction.insta_principal_paid)+SUM(LoanTransaction.insta_interest_paid)+SUM(LoanTransaction.overdue_paid)+SUM(LoanTransaction.prepayment)) as insta_realise',
 
                            'SUM(LoanTransaction.total_installment) as insta_realisable',
                            'SUM(LoanTransaction.insta_principal_due) as realizable_principal_amount',
                            'SUM(LoanTransaction.insta_interest_due) as realizable_interest_amount'
                           
                            
                            ),
                        'conditions' => $condi,
                        'joins' =>
							  array(
								array(
									'table' => 'loans',
									'alias' => 'Loan',
									'type' => 'inner',
									'foreignKey' => true,
									'conditions'=> array('Loan.id = LoanTransaction.loan_id')
						         )
                                )
                    )
                    );
        return $loan_overdue;
    }

    // Loan Officer Wise Details START
    public function credit_officer_general_report($user_id = '')
    {
        $branchLoanSummary = array();
        $loanOfficerSummary = array();
        $loanArray = array();
        if ($user_id != '') {
            $user_data = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
            //pr($user_data);die;
            if (!empty($user_data)) {
                $organizationArray = $user_data['Organization'];
                $branchArray = $user_data['Branch'];
                $marketArray = $user_data['Market'];
                $userArray = $user_data['User'];
            }
            $max_date = $this->LoanTransaction->find('all',array(
					'fields'=>array(
						'MAX(LoanTransaction.insta_paid_on) as max_date'
					),
					'conditions'=>array(
						
						'LoanTransaction.insta_paid_on !='=> '0000-00-00',
						 'Loan.loan_status_id'=>3,
						 'Loan.user_id'=>$user_id
					),
					'joins' =>
						  array(
							array(
								'table' => 'loans',
								'alias' => 'Loan',
								'type' => 'inner',
								'foreignKey' => true,
								'conditions'=> array('Loan.id = LoanTransaction.loan_id')
					)),
				));
				$last_update_date=date("Y-m-d",strtotime($max_date[0][0]['max_date']));
                
				$due_loan = $this->LoanTransaction->find('all',array(
					'fields'=>array(
                        'COUNT(distinct(LoanTransaction.loan_id)) as no_of_loan',
						'SUM(LoanTransaction.insta_principal_due) as due_balance',
                        'SUM(LoanTransaction.insta_principal_paid) as paid_balance'
					),
					'conditions'=>array(
						 'Loan.loan_status_id'=>3,
						  'Loan.user_id'=>$user_id,
                          'LoanTransaction.insta_due_on <'=> $last_update_date
					),
					'joins' =>
								  array(
									array(
										'table' => 'loans',
										'alias' => 'Loan',
										'type' => 'inner',
										'foreignKey' => true,
										'conditions'=> array('Loan.id = LoanTransaction.loan_id')
							)),
						));
                        
				$loan_payment = $this->LoanTransaction->find('all', array(
                        'fields' => array(
                            'SUM(LoanTransaction.total_installment) as total_installment',
                            'SUM(LoanTransaction.insta_principal_paid + LoanTransaction.insta_interest_paid) as total_installment_paid',
                            'SUM(LoanTransaction.insta_principal_paid) as total_principal_paid',
                            'SUM(LoanTransaction.insta_interest_paid) as total_interest_paid'
						),
					   'conditions' => array(
                            'Loan.user_id'=>$user_id,
                            'Loan.loan_status_id' => 3,
                            'Loan.status' => 1,
                            
                            'LoanTransaction.insta_due_on <'=> $last_update_date),
                        'joins' => array(array(
                                'table' => 'loans',
                                'alias' => 'Loan',
                                'type' => 'inner',
                                'foreignKey' => true,
                                'conditions' => array('Loan.id = LoanTransaction.loan_id')))));  
                                            
				$loan_data = $this->Loan->find('all', array(
                        'fields' => array(
                            'SUM(Loan.loan_principal) as total_loan'),   
                        'conditions' => array(
                            'Loan.user_id'=>$user_id,
                            'Loan.loan_status_id' => 3,
                            'Loan.status' => 1
                        )));
                $total_loan = $loan_data[0][0]['total_loan'];             
                $total_realiable = $loan_payment[0][0]['total_installment'];
                $total_realized = $loan_payment[0][0]['total_installment_paid'];   
                $total_overdue = $total_realiable-$total_realized;
                
                $total_loan_market = $total_loan - $loan_payment[0][0]['total_principal_paid'];
                $branchLoanSummary=array();
                $loanOfficerSummary['organization_details'] = $organizationArray;
                $loanOfficerSummary['user_details'] = $userArray;
                $loanOfficerSummary['branch_details'] = $branchArray;
                $loanOfficerSummary['market_details'] = $marketArray;
                $loanOfficerSummary['total_kendra'] = $this->Kendra->find('count', array('conditions' => array('Kendra.user_id'=>$user_id,'Kendra.status'=>1) ));
                $loanOfficerSummary['total_cuatomer'] = $this->Customer->find('count', array('conditions' => array('Customer.user_id'=>$user_id,'Customer.status'=>1) ));
                $loanOfficerSummary['total_overdue'] = $total_overdue;
                
                $loanOfficerSummary['total_loan'] = $total_loan;
                $loanOfficerSummary['total_loan_market'] = $total_loan_market;
                $loanOfficerSummary['total_realizable'] = $total_realiable;
                $loanOfficerSummary['total_relaized'] = $total_realized;
                $loanOfficerSummary['total_principal_paid'] = $loan_payment[0][0]['total_principal_paid'];
                $loanOfficerSummary['total_interest_paid'] = $loan_payment[0][0]['total_interest_paid'];
                $loanOfficerSummary['percentage_paid'] = ($total_realiable>0)?round(($total_realized/$total_realiable*100),2):0;
                $loanOfficerSummary['pending_application'] = $this->Loan->find('count',array('conditions'=>array('Loan.user_id'=>$user_id,'Loan.loan_status_id'=>1)));
                
                $loan_payment_list = $this->LoanTransaction->find('all', array(
                    'fields' => array(
                        'LoanTransaction.insta_due_on',
                        'LoanTransaction.insta_paid_on',
                        'SUM(LoanTransaction.total_installment) as total_installment',
                        'SUM(LoanTransaction.insta_principal_paid) as total_principal_paid',
                        'SUM(LoanTransaction.insta_interest_paid) as total_interest_paid',
						 
						),
                    'conditions' => array(
                        'Loan.loan_status_id' => 3,
                        'Loan.status' => 1,
                        'Loan.user_id' => $user_id,
                        'LoanTransaction.insta_due_on <'=> $last_update_date),
                    'joins' => array(array(
                            'table' => 'loans',
                            'alias' => 'Loan',
                            'type' => 'inner',
                            'foreignKey' => true,
                            'conditions' => array('Loan.id = LoanTransaction.loan_id'))),
                    'order' => array('LoanTransaction.insta_due_on DESC'),
                    'group' => 'LoanTransaction.insta_due_on'));
                $loanOfficerSummary['loan_table'] = $loan_payment_list;
                $loanOfficerSummary['data_status'] = 1;
        } else {
            $loanOfficerSummary['data_status'] = 0;
        }
        //pr($loanOfficerSummary);die;
        return $loanOfficerSummary;
        
        
    }
	// Loan Officer Wise Details End
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
                                                                                                










































    // Get Settings and Fees values of an Organization
    function get_organization_settings_fees($oid)
    {

        $fee_data = $this->Fee->find('list', array('fields' => array('fee_short',
                    'fee_value'), 'conditions' => array('Fee.organization_id' => $oid)));
        $set_data = $this->Setting->find('list', array('fields' => array('set_short',
                    'set_value'), 'conditions' => array('Setting.organization_id' => $oid)));

        $data['Fee'] = $fee_data;
        $data['Setting'] = $set_data;
        return $data;
    }
	
	// Get Organization Setting Details
    function get_organization_settings_fees_for_app($oid)
    {
        $this->Organization->unBindModel(array('hasMany' => array(
                'Loan',
                'User',
                'Region',
                'Branch',
                'Kendra',
                'Customer')));
        $fee_data = $this->Organization->find('first', array('conditions' => array('Organization.id' =>
                    $oid)));

        return $fee_data;
    }

	// General email sending function
    public function _email($email, $message, $subject = 'Message from Microfinance.', $name = '', $from = '', $attachment='', $template = 'rext_template',$layout='report_msg')
    {
        //$this->autoRender=false;
        //$ms = $message;
        //$ms = wordwrap($ms, 70);
                
        if ($name == '') {
            $name = $email;
        }

        if ($from == '') {
            $from = 'Microfinance <' . ADMIN_EMAIL . '>';
        }
        
        $this->Email->from = $from;
        $this->Email->to = $email;
        $this->set('branch_data', $message);
        $this->Email->subject = $subject;
        $this->Email->layout = $layout;
        $this->Email->template = $template;
		$this->Email->additionalParams="-f$email";
        $this->Email->sendAs = 'html';
        //send mail
        try {

            if ($this->Email->send()) {
                return true;
            } else {
                return false;
            }
        }
        catch (phpmailerException $e) {
            return false;

        }
        catch (exception $e) {
            return false;
        }
    }
	// Email Function end


	
	// Create EMI for the product (This is same as loan transaction)
    public function create_order_emi_transaction($order_id = '', $start_date)
    {
        $order_data = $this->Order->find('first', array('conditions' => array('Order.id' =>
                    $order_id)));

        $loan_principal = $order_data['Order']['order_amount'];
        $loan_interest = $order_data['Order']['emi_interest'];
        $inst_amount = $order_data['Order']['emi_rate'];
        $repaytotal_amount = $order_data['Order']['repay_total'];
        $currency = $order_data['Order']['currency'];
        $period_unit = $order_data['Order']['emi_period_interval_day'];
        $loan_period = $order_data['Order']['emi_period'];
        $loan_type = $order_data['Order']['emi_type'];
        $loan_no = $order_data['Order']['order_number'];
        $loan_dateout = $order_data['Order']['order_dateout'];

        //Create the EMI amount
        $e = 1;
        $temp_principal = $loan_principal;
        $start_date = date("Y-m-d", strtotime($start_date));
        while ($e <= $loan_period) {
            if ($loan_type == 'FIXED') {

                $intr = round($loan_principal * $loan_interest / 100);
                $instal_principal = round($loan_principal / $loan_period);
                $instal_interest = round($intr / $loan_period);

            } elseif ($loan_type == 'REDUCE') {
                if ($period_unit == 'WEEK') {
                    $l = 1 / 52;
                    $i = $loan_interest / 100 * $l;
                    $instal_interest = $temp_principal * $i;
                    $instal_principal = round($inst_amount - $instal_interest);
                    $temp_principal = $temp_principal - $instal_principal;

                } else {
                    $l = 1 / 12;
                    $i = $loan_interest / 100 * $l;
                    $instal_interest = round($temp_principal * $i);
                    $instal_principal = round($inst_amount - $instal_interest);
                    $temp_principal = $temp_principal - $instal_principal;
                }
            } else {
                $instal_principal = round($inst_amount);
                $instal_interest = 0;
            }
            //Insert into LoanTransaction
            $trans_data['LoanTransaction']['order_id'] = $order_id;
            $trans_data['LoanTransaction']['insta_no'] = $e;
            $trans_data['LoanTransaction']['insta_due_on'] = $start_date;
            $trans_data['LoanTransaction']['total_installment'] = $inst_amount;
            $trans_data['LoanTransaction']['insta_principal_due'] = $instal_principal;
            $trans_data['LoanTransaction']['insta_interest_due'] = $instal_interest;
            $trans_data['LoanTransaction']['customer_id'] = $order_data['Order']['customer_id'];
            $trans_data['LoanTransaction']['organization_id'] = $order_data['Order']['organization_id'];
            $trans_data['LoanTransaction']['region_id'] = $order_data['Order']['region_id'];
            $trans_data['LoanTransaction']['branch_id'] = $order_data['Order']['branch_id'];
            $trans_data['LoanTransaction']['kendra_id'] = $order_data['Order']['kendra_id'];
            $trans_data['LoanTransaction']['created_on'] = date("Y-m-d H:i:s");
            $this->LoanTransaction->clear();
            $this->LoanTransaction->save($trans_data);
            $date = strtotime($start_date);
            $date = strtotime("+$period_unit days", $date);
            $start_date = date("Y-m-d", $date);
            // Loan transaction end
            $e++;
        }
        //die;
    }
	// Product EMI function END

    // Customer Loan Summary Function
    public function customer_loan_summary($cust_id)
    {
        $cust_data = $this->Customer->find('first', array('
			conditions' => array('Customer.id' => $cust_id)));
        $loandetails = array();

        $loan_data = $this->Loan->find('all', array('fields'=>array('Loan.id'),'conditions' => array('Loan.customer_id' =>
                    $cust_id, 'Loan.status' => 1), 'order' =>
                "FIELD(Loan.loan_status_id, '3','2','1','6','4','5')"));
        // Maximum updates Date from database 
        $max_date = $this->LoanTransaction->find('all',array(
				'fields'=>array(
					'MAX(LoanTransaction.insta_paid_on) as max_date'
				),
				'conditions'=>array(
					
					'LoanTransaction.insta_paid_on !='=> '0000-00-00',
					 'Loan.loan_status_id'=>3,
					 'Loan.organization_id'=>$this->Auth->user('organization_id')
				),
				'joins' =>
							  array(
								array(
									'table' => 'loans',
									'alias' => 'Loan',
									'type' => 'inner',
									'foreignKey' => true,
									'conditions'=> array('Loan.id = LoanTransaction.loan_id')
				)),
			));
			$final_date=$max_date[0][0]['max_date'];
        if (!empty($loan_data)) {
			//pr($loan_data);die;
            foreach ($loan_data as $kcust => $loan_row) {
                $loan_cust = $loan_row['Loan'];
                $loan_id = $loan_cust['id'];
                
                $loan_snap =$this->get_loan_snapshot_data($loan_id,$final_date);
                
				//pr($loan_snap);die;
				if($loan_snap['Loan']['account_id']== 0){
					$loan_number= 'No Set';
				} else {
					
					$loan_number= $loan_snap['Account']['account_number'];
				}
				
				
                $loandetails[$kcust]['loan_id'] = $loan_cust['id'];
				$loandetails[$kcust]['account_id'] = $loan_snap['Loan']['account_id'];
				$loandetails[$kcust]['account_number'] = $loan_number;
                $loandetails[$kcust]['total_overdue'] = $loan_snap['overdue']['current_overdue'];
                $loandetails[$kcust]['overdue_no'] = round($loandetails[$kcust]['total_overdue']/$loan_snap['Loan']['loan_rate']);
                $loandetails[$kcust]['last_paid_date'] = $loan_snap['overdue']['last_paid_date'];
                $loandetails[$kcust]['total_installment_no'] = $loan_snap['Loan']['loan_period'];
                $loandetails[$kcust]['paid_installment'] = intval(($loan_snap['Loan']['loan_repay_total']-$loan_snap['Loan']['current_outstanding'])/$loan_snap['Loan']['loan_rate']);
                $loandetails[$kcust]['installment_amount'] = $loan_snap['Loan']['loan_rate'];
                $loandetails[$kcust]['loan_repay_total'] = $loan_snap['Loan']['loan_repay_total'];
                
                $loandetails[$kcust]['loan_due_balance'] = $loan_snap['Loan']['current_outstanding'];
                $loandetails[$kcust]['total_paid_amount'] = $loan_snap['Loan']['loan_repay_total']-$loan_snap['Loan']['current_outstanding'];
                $loandetails[$kcust]['loan_principal'] = $loan_snap['Loan']['loan_principal'];
                $loandetails[$kcust]['paid_amount'] = $loan_snap['Loan']['loan_repay_total']-$loan_snap['Loan']['current_outstanding'];
                $loandetails[$kcust]['loan_dateout'] = $loan_snap['Loan']['loan_dateout'];
                $loandetails[$kcust]['loan_date'] = $loan_snap['Loan']['loan_date'];
                $loandetails[$kcust]['loan_number'] = $loan_snap['Loan']['loan_number'];
                $loandetails[$kcust]['loan_purpose'] = $loan_snap['Loan']['loan_purpose'];
                $loandetails[$kcust]['loan_type'] = $loan_snap['Loan']['loan_type'];
                $loandetails[$kcust]['interest_type'] = $loan_snap['Loan']['interest_type'];
                $loandetails[$kcust]['interest_rate'] = $loan_snap['Loan']['loan_interest'];
                $loandetails[$kcust]['loan_period'] = $loan_snap['Loan']['loan_period'].$loan_snap['Loan']['loan_period_unit'];
                
                
                
                $loandetails[$kcust]['total_realized'] = $loan_snap['overdue']['realise_amount'];
                $loandetails[$kcust]['total_realiable'] = $loan_snap['overdue']['realisable_amount'];
                $loandetails[$kcust]['percentage_paid'] = ($loandetails[$kcust]['total_realiable'] >
                    0) ? round(($loandetails[$kcust]['total_realized'] * 100 / $loandetails[$kcust]['total_realiable']),
                    2) : '0';
                $loandetails[$kcust]['loan_status'] = $loan_snap['LoanStatus']['status_name'];
                $loandetails[$kcust]['loan_status_no'] = $loan_snap['Loan']['loan_status_id'];
                $loandetails[$kcust]['security_diposit'] = $loan_snap['Loan']['security_fee'];
                $loandetails[$kcust]['loan_by'] = $loan_snap['User']['fullname'];
                
            }
        }
        return $loandetails;
    }
	// Customer Loan Summery End
	
	// Customer Account Details function start
	public function customer_account_details($account_id){
		$account_details=$this->Account->find('first', array('conditions' => array('Account.id' =>$account_id)));
		return $account_details;
	}
	// Customer Account Details function end
	
    // Loan status Name by value START
    public function loan_status_name($status_id)
    {
        $status_array = $this->LoanStatus->find('first', array('conditions' => array('LoanStatus.id' =>
                    $status_id)));
        $status_name = $status_array['LoanStatus']['status_name'];
        //pr($status_array);die;
        return $status_name;
    }
    // Loan status Name by Value END

    // Status Name Function START
    function status_name_array()
    {
        $upload_type = array(
            1 => "Active",
            2 => "Not Active",
            3 => "Closed");
        return $upload_type;
    }
    // Status Name Function END


    // Loan Summary of a Single Loan
    public function loan_summary($loan_id)
    {
        $loan_data = $this->Loan->find('first', array('conditions' => array('Loan.id' =>
                    $loan_id)));
        //pr($loan_data);
        $loan_overdue = $this->LoanTransaction->find('all', array('fields' => array('SUM(LoanTransaction.total_installment) as total_overdue'), 'conditions' => array(
                'LoanTransaction.loan_id' => $loan_id,
                'LoanTransaction.insta_due_on <=' => date("Y-m-d"),
                'LoanTransaction.insta_principal_paid' => 0)));
        $loan_paid = $this->LoanTransaction->find('all', array('fields' => array(
                'COUNT(LoanTransaction.id) as instalment_paid_no',
                'SUM(LoanTransaction.total_installment) as paid_amount',
                'MAX(insta_paid_on) as last_paid_date'), 'conditions' => array('LoanTransaction.loan_id' =>
                    $loan_id, 'LoanTransaction.insta_principal_paid >' => 0)));
        
        $loan_actual_paid = $this->LoanTransaction->find('all', array('fields' => array(
                'SUM(LoanTransaction.insta_principal_paid) as principal_paid_amount',
                'SUM(LoanTransaction.insta_interest_paid) as interest_paid_amount'), 
                'conditions' => array('LoanTransaction.loan_id' =>$loan_id)));

        $loandetails['loan_id'] = $loan_id;
        $loandetails['total_overdue'] = $loan_overdue[0][0]['total_overdue'];
        $loandetails['overdue_no'] = 0;
        $loandetails['last_paid_date'] = $loan_paid[0][0]['last_paid_date'];
        $loandetails['total_installment_no'] = $loan_data['Loan']['loan_period'];
        $loandetails['paid_installment'] = $loan_paid[0][0]['instalment_paid_no'];
        $loandetails['installment_amount'] = $loan_data['Loan']['loan_rate'];
        $loandetails['loan_due_balance'] = 0;
        $loandetails['loan_principal'] = $loan_data['Loan']['loan_principal'];
        $loandetails['paid_amount'] = $loan_data['Loan']['loan_rate'] * $loandetails['paid_installment'];
        $loandetails['loan_dateout'] = $loan_data['Loan']['loan_dateout'];
        $loandetails['loan_date'] = $loan_data['Loan']['loan_date'];
        $loandetails['loan_number'] = $loan_data['Loan']['loan_number'];
        $loandetails['loan_purpose'] = $loan_data['Loan']['loan_purpose'];
        $loandetails['loan_interest'] = $loan_data['Loan']['loan_interest'];
        $loandetails['loan_repay_total'] = $loan_data['Loan']['loan_repay_total'];
        $loandetails['currency'] = $loan_data['Loan']['currency'];
        $loandetails['loan_period_unit'] = $loan_data['Loan']['loan_period_unit'];
        $loandetails['loan_type'] = $loan_data['Loan']['loan_type'];
        $loandetails['total_realiable'] = $loandetails['total_overdue'] + $loan_paid[0][0]['paid_amount'];
        $loandetails['total_realized'] = $loan_paid[0][0]['paid_amount'];
        $loandetails['percentage_paid'] = ($loandetails['total_realiable'] > 0) ? round(($loandetails['total_realized'] *
            100 / $loandetails['total_realiable']), 2) : '0';
        
        $loandetails['principal_paid_amount'] = $loan_actual_paid[0][0]['principal_paid_amount'];
        $loandetails['interest_paid_amount'] = $loan_actual_paid[0][0]['interest_paid_amount'];

        return $loandetails;
    }
	// Single Loan Summery end
	
    // Single Order Summary of an order Start
    public function order_summary($order_id)
    {
        $loan_data = $this->Order->find('first', array('conditions' => array('Order.id' =>
                    $order_id)));
        //pr($loan_data);die;
        $loan_overdue = $this->LoanTransaction->find('all', array('fields' => array('SUM(LoanTransaction.total_installment) as total_overdue',
                    'COUNT(LoanTransaction.id) as overdue_no'), 'conditions' => array(
                'LoanTransaction.order_id' => $order_id,
                'LoanTransaction.insta_due_on <=' => date("Y-m-d"),
                'LoanTransaction.insta_principal_paid' => 0)));
        $loan_paid = $this->LoanTransaction->find('all', array('fields' => array('COUNT(LoanTransaction.id) as instalment_paid_no',
                    'MAX(insta_paid_on) as last_paid_date'), 'conditions' => array('LoanTransaction.order_id' =>
                    $order_id, 'LoanTransaction.insta_due_on <=' => date("Y-m-d"))));

        $loandetails['order_id'] = $order_id;
        $loandetails['total_overdue'] = $loan_overdue[0][0]['total_overdue'];
        $loandetails['overdue_no'] = $loan_overdue[0][0]['overdue_no'];
        $loandetails['last_paid_date'] = $loan_paid[0][0]['last_paid_date'];
        $loandetails['total_installment_no'] = $loan_data['Order']['emi_period'];
        $loandetails['paid_installment'] = $loan_paid[0][0]['instalment_paid_no'] - $loan_overdue[0][0]['overdue_no'];
        $loandetails['installment_amount'] = $loan_data['Order']['emi_rate'];
        $loandetails['emi_due_balance'] = $loan_data['Order']['emi_rate'] * ($loan_data['Order']['emi_period'] -
            $loan_paid[0][0]['instalment_paid_no'] - $loan_overdue[0][0]['overdue_no']);
        $loandetails['order_amount'] = $loan_data['Order']['order_amount'];
        $loandetails['paid_amount'] = $loan_data['Order']['emi_rate'] * $loandetails['paid_installment'];
        $loandetails['order_dateout'] = $loan_data['Order']['order_dateout'];
        $loandetails['order_date'] = $loan_data['Order']['order_date'];
        $loandetails['order_number'] = $loan_data['Order']['order_number'];
        $loandetails['product'] = $loan_data['Product']['product_name'];
        $loandetails['product_number'] = $loan_data['Product']['product_number'];
        $loandetails['oreder_status'] = $loan_data['OrderStatus']['status_name'];

        if ($loan_data['Order']['order_issued'] == 1) {
            if ($loan_data['Order']['is_emi'] == 1) {
                $payment_type = 'Installment';
            } else {
                $payment_type = 'Full Paid';
            }
        } else {
            $payment_type = 'N/A';
        }
        $loandetails['payment_type'] = $payment_type;
        $loandetails['currency'] = $loan_data['Order']['currency'];
        $loandetails['emi_period_unit'] = $loan_data['Order']['emi_period_interval_day'];
        $loandetails['emi_type'] = $loan_data['Order']['emi_type'];
        $loandetails['emi_start_date'] = ($loan_data['Order']['emi_start_date'] !=
            '0000-00-00') ? date("d-M-Y", strtotime($loan_data['Order']['emi_start_date'])) :
            'N/A';
        $loandetails['emi_interest'] = $loan_data['Order']['emi_interest'];
        return $loandetails;
    }
	// Single Order Summery of an order End

    //Order Summary of a customer Start
    public function customer_order_summary($cust_id)
    {
        $cust_data = $this->Order->find('all', array('conditions' => array('Order.customer_id' =>
                    $cust_id), 'order' => "FIELD(Order.order_status_id, '3','2','1','4','5','6')"));
        //pr($cust_data); die;
        $loandetails = array();
        if (!empty($cust_data)) {
            foreach ($cust_data as $kcust => $loan_cust) {
                $order_id = $loan_cust['Order']['id'];
                $loandetails[] = $this->order_summary($order_id);
            }
        }
        return $loandetails;
    }
	// Order summery of an customer end
	
    // kendra Details  of a single kendra
    public function kendra_details($kendra_id)
    {
        $outputarr = array();
        $this->Kendra->unBindModel(array('hasMany' => array('Loan', 'Customer')));
        $kendra_data = $this->Kendra->find('first', array('conditions' => array('Kendra.id' =>
                    $kendra_id)));      
        $this->Customer->unBindModel(array(
        'hasMany' => array('Loan', 'Idproof','SavingsTransaction','Order'),
        'belongsTo' => array('Organization', 'Region','Branch','Kendra','User','Country'),
        'hasOne' => array('Savings')        
        ));
        $kendra_data['Customer'] = $this->Customer->find('all', array('conditions' =>
                array('Customer.kendra_id' => $kendra_id, 'Customer.status' => 1)));
        $this->Loan->unBindModel(array('hasMany' => array('LoanTransaction')));
        $kendra_data['Loan'] = $this->Loan->find('all', array('conditions' => array(
                'Loan.kendra_id' => $kendra_id,
                'Loan.loan_status_id' => 3,
                'Loan.status' => 1)));
        $loan_sum_data = $this->Loan->find('all', array('fields' => array('SUM(Loan.loan_principal) as total_loan'),
                'conditions' => array(
                'Loan.kendra_id' => $kendra_id,
                'Loan.loan_status_id' => 3,
                'Loan.status' => 1)));
        $kendra_data['Kendra']['total_loan'] = $loan_sum_data[0][0]['total_loan'];

        $lt_data = $this->LoanTransaction->find('all', array(
            'fields' => array(
                    '(SUM(LoanTransaction.insta_principal_paid)+SUM(LoanTransaction.insta_interest_paid)+SUM(LoanTransaction.overdue_paid)+SUM(LoanTransaction.prepayment)) as realise_amount',
                    'SUM(LoanTransaction.insta_principal_paid) as total_principal_paid',
                    'SUM(LoanTransaction.insta_interest_paid) as total_interest_paid'
                    ),
            'conditions' => array(
                'Loan.kendra_id' => $kendra_id,
                'Loan.loan_status_id' => 3,
                'Loan.status' => 1),
            'joins' => array(array(
                    'table' => 'loans',
                    'alias' => 'Loan',
                    'type' => 'inner',
                    'foreignKey' => true,
                    'conditions' => array('Loan.id = LoanTransaction.loan_id')))));
        
        $loan_overdue = $this->LoanTransaction->find('all', 
                    array(
                        'fields' => array(
                            'SUM(LoanTransaction.total_installment) as total_installment',
                            'SUM(LoanTransaction.insta_principal_due) as realizable_principal_amount',
                            'SUM(LoanTransaction.insta_interest_due) as realizable_interest_amount',
                            
                            ),
                        'conditions' => array(
                            'LoanTransaction.insta_due_on <=' => date("Y-m-d"),
                            'Loan.loan_status_id'=>3,
                            'Loan.kendra_id'=>$kendra_id),
                        'joins' =>
							  array(
								array(
									'table' => 'loans',
									'alias' => 'Loan',
									'type' => 'inner',
									'foreignKey' => true,
									'conditions'=> array('Loan.id = LoanTransaction.loan_id')
						         )
                                )
                    )
                    );
                
        $kendra_data['Kendra']['total_loan_repayment'] = $lt_data[0][0]['realise_amount'] ;
        $kendra_data['Kendra']['total_loan_in_market'] = $loan_sum_data[0][0]['total_loan'] - $lt_data[0][0]['realise_amount']-$loan_overdue[0][0]['realizable_interest_amount'];
        $kendra_data['Kendra']['total_loan_in_market'] = ($kendra_data['Kendra']['total_loan_in_market']<0)?0:$kendra_data['Kendra']['total_loan_in_market'];
        
        $kendra_data['Kendra']['total_realisable'] = $loan_overdue[0][0]['total_installment'];
        $kendra_data['Kendra']['total_realise'] = $lt_data[0][0]['realise_amount'];
        $kendra_data['Kendra']['total_overdue'] = $loan_overdue[0][0]['total_installment'] - $lt_data[0][0]['realise_amount'];
        $kendra_data['Kendra']['total_overdue'] = ($kendra_data['Kendra']['total_overdue']<0)?0:$kendra_data['Kendra']['total_overdue'];
        
        $kendra_data['Kendra']['percent_paid'] = ($kendra_data['Kendra']['total_realisable'] >
            0) ? round(($kendra_data['Kendra']['total_realise'] / $kendra_data['Kendra']['total_realisable'] *
            100), 2) : 0;
        return $kendra_data;
    }
	// Kendra Details of a kendra End
    public function market_details($market_id)
    {
        $outputarr = array();
        $this->Kendra->unBindModel(array('hasMany' => array('Loan', 'Customer')));
        $kendra_data = $this->Market->findById($market_id);      
        $this->Customer->unBindModel(array(
        'hasMany' => array('Loan', 'Idproof','SavingsTransaction','Order'),
        'belongsTo' => array('Organization', 'Region','Branch','Kendra','User','Country'),
        'hasOne' => array('Savings')        
        ));
        $kendra_data['Customer'] = $this->Customer->find('all', array('conditions' =>
                array('Customer.market_id' => $market_id, 'Customer.status' => 1)));
        $this->Loan->unBindModel(array('hasMany' => array('LoanTransaction')));
        $kendra_data['Loan'] = $this->Loan->find('all', array('conditions' => array(
                'Loan.market_id' => $market_id,
                'Loan.loan_status_id' => 3,
                'Loan.status' => 1)));
        $loan_sum_data = $this->Loan->find('all', array('fields' => array('SUM(Loan.loan_principal) as total_loan'),
                'conditions' => array(
                'Loan.market_id' => $market_id,
                'Loan.loan_status_id' => 3,
                'Loan.status' => 1)));
        $kendra_data['Market']['total_loan'] = $loan_sum_data[0][0]['total_loan'];

        $lt_data = $this->LoanTransaction->find('all', array(
            'fields' => array('SUM(LoanTransaction.insta_principal_paid) as total_principal_paid',
                    'SUM(LoanTransaction.insta_interest_paid) as total_interest_paid'),
            'conditions' => array(
                'Loan.market_id' => $market_id,
                'Loan.loan_status_id' => 3,
                'Loan.status' => 1),
            'joins' => array(array(
                    'table' => 'loans',
                    'alias' => 'Loan',
                    'type' => 'inner',
                    'foreignKey' => true,
                    'conditions' => array('Loan.id = LoanTransaction.loan_id')))));
                    
        $kendra_data['Market']['total_loan_repayment'] = $lt_data[0][0]['total_principal_paid'] + $lt_data[0][0]['total_interest_paid'];
        $kendra_data['Market']['total_loan_in_market'] = $loan_sum_data[0][0]['total_loan'] - $lt_data[0][0]['total_principal_paid'];
        $kendra_data['Market']['total_loan_in_market'] = ($kendra_data['Market']['total_loan_in_market']<0)?0:$kendra_data['Market']['total_loan_in_market'];
        $loan_overdue = $this->LoanTransaction->find('all', array(
            'fields' => array(
                'SUM(LoanTransaction.total_installment) as total_installment',
                'SUM(LoanTransaction.insta_principal_paid) as total_principal_paid',
                'SUM(LoanTransaction.insta_interest_paid) as total_interest_paid'),
            'conditions' => array(
                'Loan.market_id' => $market_id,
                'Loan.loan_status_id' => 3,
                'Loan.status' => 1,
                'LoanTransaction.insta_due_on <=' => date("Y-m-d")),
            'joins' => array(array(
                    'table' => 'loans',
                    'alias' => 'Loan',
                    'type' => 'inner',
                    'foreignKey' => true,
                    'conditions' => array('Loan.id = LoanTransaction.loan_id')))));
        $kendra_data['Market']['total_realisable'] = $loan_overdue[0][0]['total_installment'];
        $kendra_data['Market']['total_realise'] = $loan_overdue[0][0]['total_principal_paid'] +
            $loan_overdue[0][0]['total_interest_paid'];
        $kendra_data['Market']['total_overdue'] = $kendra_data['Market']['total_realisable'] -
            $kendra_data['Market']['total_realise'];
        $kendra_data['Market']['percent_paid'] = ($kendra_data['Market']['total_realisable'] >
            0) ? round(($kendra_data['Market']['total_realise'] / $kendra_data['Market']['total_realisable'] *
            100), 2) : 0;
        return $kendra_data;
    }

    // branch Details of a branch Start
    public function branch_details($kendra_id)
    {
        $outputarr = array();
        $this->Kendra->unBindModel(array('hasMany' => array('Loan', 'Customer')));
        $kendra_data = $this->Branch->find('first', array('conditions' => array('Branch.id' =>
                    $kendra_id)));                  
        $this->Customer->unBindModel(array(
        'hasMany' => array('Loan', 'Idproof','SavingsTransaction','Order'),
        'belongsTo' => array('Organization', 'Region','Branch','Kendra','User','Country'),
        'hasOne' => array('Savings')       
        ));
        
        $this->Customer->bindModel(array(
        			'belongsTo' => array(
        				'Market' => array(
        					'foreignKey' => 'market_id',
        					'type'=>'INNER',
        					'conditions' => array('Market.id = Customer.market_id')
        				),
        				'Branch' => array(
        					'foreignKey' => 'branch_id',
        					'type'=>'INNER',
        					'conditions' => array('Branch.id = Market.branch_id')
        				),
        			)
        		));
        $kendra_data['Customer'] = $this->Customer->find('all', array('conditions' =>
                array('Branch.id' => $kendra_id, 'Customer.status' => 1)));
        $this->Loan->unBindModel(array('hasMany' => array('LoanTransaction')));
        $kendra_data['Loan'] = $this->Loan->find('all', array('conditions' => array(
                'Loan.branch_id' => $kendra_id,
                'Loan.loan_status_id' => 3,
                'Loan.status' => 1)));
        $loan_sum_data = $this->Loan->find('all', array('fields' => array('SUM(Loan.loan_principal) as total_loan'),
                'conditions' => array(
                'Loan.branch_id' => $kendra_id,
                'Loan.loan_status_id' => 3,
                'Loan.status' => 1)));
        $kendra_data['Branch']['total_loan'] = $loan_sum_data[0][0]['total_loan'];
        $lt_data = $this->LoanTransaction->find('all', array(
            'fields' => array('SUM(LoanTransaction.insta_principal_paid) as total_principal_paid',
                    'SUM(LoanTransaction.insta_interest_paid) as total_interest_paid'),
            'conditions' => array(
                'Loan.branch_id' => $kendra_id,
                'Loan.loan_status_id' => 3,
                'Loan.status' => 1),
            'joins' => array(array(
                    'table' => 'loans',
                    'alias' => 'Loan',
                    'type' => 'inner',
                    'foreignKey' => true,
                    'conditions' => array('Loan.id = LoanTransaction.loan_id')))));
        $kendra_data['Branch']['total_loan_repayment'] = $lt_data[0][0]['total_principal_paid'] +
				$lt_data[0][0]['total_interest_paid'];
        $kendra_data['Branch']['total_loan_in_market'] = $loan_sum_data[0][0]['total_loan'] -
				$kendra_data['Branch']['total_loan_repayment'];
        $loan_overdue = $this->LoanTransaction->find('all', array(
            'fields' => array(
                'SUM(LoanTransaction.total_installment) as total_installment',
                'SUM(LoanTransaction.insta_principal_paid) as total_principal_paid',
                'SUM(LoanTransaction.insta_interest_paid) as total_interest_paid'),
            'conditions' => array(
                'Loan.branch_id' => $kendra_id,
                'Loan.loan_status_id' => 3,
                'Loan.status' => 1,
                'LoanTransaction.insta_due_on <=' => date("Y-m-d")),
            'joins' => array(array(
                    'table' => 'loans',
                    'alias' => 'Loan',
                    'type' => 'inner',
                    'foreignKey' => true,
                    'conditions' => array('Loan.id = LoanTransaction.loan_id')))));
        $kendra_data['Branch']['total_realisable'] = ($loan_overdue[0][0]['total_installment'] ==
            '' ? 0 : $loan_overdue[0][0]['total_installment']);
        $realizable_amount = ($loan_overdue[0][0]['total_principal_paid'] + $loan_overdue[0][0]['total_interest_paid']);
        $kendra_data['Branch']['total_realise'] = ($realizable_amount == '' ? 0 : $realizable_amount);
        $kendra_data['Branch']['total_overdue'] = $kendra_data['Branch']['total_realisable'] -
            $kendra_data['Branch']['total_realise'];
        if ($kendra_data['Branch']['total_realisable'] != 0) {
            $kendra_data['Branch']['percent_paid'] = round(($kendra_data['Branch']['total_realise'] /
                $kendra_data['Branch']['total_realisable'] * 100), 2);
        } else {
            $kendra_data['Branch']['percent_paid'] = 0;
        }
        return $kendra_data;
    }
	// Branch Details of a single branch
	
	// Bulk Collection of loan instalment function Start
    public function loan_amount_collection($kid, $due_date, $insta_no='', $cust_arr)
    {
		//$cust_arr: This is the array of customers. Based on $kid or Kendra id the customer array is created
        foreach ($cust_arr as $cust_id => $repay_total) {
            $this->Loan->unBindModel(array('hasMany' => array('LoanTransaction')));
            $loan_data = $this->Loan->find('first', array('fields' => array(
                    'Loan.id',
                    'Loan.loan_principal',
                    'Loan.loan_repay_total'), 'conditions' => array(
                    'Loan.customer_id' => $cust_id,
                    'Loan.kendra_id' => $kid,
                    'Loan.loan_status_id' => 3,
                    'Loan.status' => 1)));
            if($due_date!=''){
                $loan_trans = $this->LoanTransaction->find('first', array('conditions' => array
                    (
                    'LoanTransaction.loan_id' => $loan_data['Loan']['id'],
                    'LoanTransaction.insta_due_on' => $due_date)
                    ));
            }else{
                $loan_trans = $this->LoanTransaction->find('first', array('conditions' => array
                    (
                    'LoanTransaction.loan_id' => $loan_data['Loan']['id'],
                    'LoanTransaction.insta_no' => $insta_no)
                    ));
            }
            $trns_arr['LoanTransaction']['id'] = $loan_trans['LoanTransaction']['id'];
            $trns_arr['LoanTransaction']['insta_paid_on'] = date("Y-m-d");
            $trns_arr['LoanTransaction']['insta_principal_paid'] = $loan_trans['LoanTransaction']['insta_principal_due'];
            $trns_arr['LoanTransaction']['insta_interest_paid'] = $loan_trans['LoanTransaction']['insta_interest_due'];
            $trns_arr['LoanTransaction']['current_outstanding'] = $loan_data['Loan']['loan_repay_total'] - ($loan_trans['LoanTransaction']['total_installment'] *
                $insta_no);
            $trns_arr['LoanTransaction']['modified_on'] = date("Y-m-d");
            $trns_arr['LoanTransaction']['user_id'] = $this->Auth->user('id');
            $this->LoanTransaction->clear();
            $this->LoanTransaction->save($trns_arr);
        }
		// If all instalment paid then this function will closed the loan
        $this->check_loan_payments_and_clear2($loan_data['Loan']['id']); // Change loan status if all instalment paid
    }
	// Loan instalment paid function end 

	// Function for check if all the loan instalment is paid then the loan will closed START
    public function check_loan_payments_and_clear2($loan_id)
    {
        $loan_trans = $this->LoanTransaction->find('count', array('conditions' => array
                ('LoanTransaction.loan_id' => $loan_id, 'LoanTransaction.insta_principal_paid' =>
                    0)));
        if ($loan_trans == 0) {
            //$this->Loan->id = $loan_id;
            $data_arr['Loan']['id'] = $loan_id;
            $data_arr['Loan']['loan_status_id'] = 6;
            $data_arr['Loan']['modified_on'] = date("Y-m-d h:i:s");
            $this->Loan->clear();
            $this->Loan->save($data_arr);
        }
    }
	// Function for check if all the loan instalment is paid then the loan will closed END

	// Bulk collection of Saving amount function start
    public function savings_amount_collection($kid, $due_date, $cust_arr)
    {
        $user_id = $this->Auth->user('id');
        foreach ($cust_arr as $cust_id => $repay_total) {
            $this->Loan->unBindModel(array('hasMany' => array('LoanTransaction')));
            $saving_data = $this->Saving->find('first', array('conditions' => array(
                    'Saving.customer_id' => $cust_id,
                    'Saving.kendra_id' => $kid,
                    'Saving.status' => 1)));
            $organization_id = $saving_data['Organization']['id'];
            
            $savings_amt = $repay_total;
            $savings_arr['Saving']['id'] = $saving_data['Saving']['id'];
            $savings_arr['Saving']['current_balance'] = $saving_data['Saving']['current_balance'] +
                $savings_amt;
            $savings_arr['Saving']['modified_on'] = date('Y-m-d H:i:s');
            $savings_arr['Saving']['user_id'] = $user_id;
            $savings_arr['SavingsTransaction']['saving_id'] = $saving_data['Saving']['id'];
            $savings_arr['SavingsTransaction']['transaction_on'] = $due_date;
            $savings_arr['SavingsTransaction']['amount'] = $savings_amt;
            $savings_arr['SavingsTransaction']['transaction_type'] = 'Deposite';
            $savings_arr['SavingsTransaction']['balance'] = $savings_arr['Saving']['current_balance'];
            $savings_arr['SavingsTransaction']['customer_id'] = $cust_id;
            $savings_arr['SavingsTransaction']['organization_id'] = $organization_id;
            
            $savings_arr['SavingsTransaction']['created_on'] = date("Y-m-d");
            $savings_arr['SavingsTransaction']['user_id'] = $this->Auth->user('id');
            if ($savings_amt != 0 || $savings_amt != '') {
                $this->Saving->clear();
                $this->SavingsTransaction->clear();
                $this->Saving->save($savings_arr);
                $this->SavingsTransaction->save($savings_arr);
            }
        }
    }
	// Bulk collection of Saving amount function End

    // Loan Officer Wise Details START
    public function app_loan_officer_details($user_id = '')
    {
        $branchLoanSummary = array();
        $loanOfficerSummary = array();
        $loanArray = array();
        if ($user_id != '') {
            $user_data = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
            //pr($user_data);die;
            if (!empty($user_data)) {
                $organizationArray = $user_data['Organization'];
                $branchArray = $user_data['Branch'];
                $userArray = $user_data['User'];
            }
            $max_date = $this->LoanTransaction->find('all',array(
					'fields'=>array(
						'MAX(LoanTransaction.insta_paid_on) as max_date'
					),
					'conditions'=>array(
						
						'LoanTransaction.insta_paid_on !='=> '0000-00-00',
						 'Loan.loan_status_id'=>3,
						 'Loan.user_id'=>$user_id
					),
					'joins' =>
								  array(
									array(
										'table' => 'loans',
										'alias' => 'Loan',
										'type' => 'inner',
										'foreignKey' => true,
										'conditions'=> array('Loan.id = LoanTransaction.loan_id')
					)),
				));
				$last_update_date=date("Y-m-d",strtotime($max_date[0][0]['max_date']));
                
                
                
                
                $loan_realise = $this->LoanTransaction->find('all',array(
        			'fields'=>array(
        				'(SUM(LoanTransaction.insta_principal_paid)+SUM(LoanTransaction.insta_interest_paid)+SUM(LoanTransaction.overdue_paid)+SUM(LoanTransaction.prepayment)) as realized_amount',
        			),
        			'conditions'=>array(
        				
        				 'Loan.user_id'=>$user_id,
        				 'Loan.loan_status_id'=>3,
        				 'LoanTransaction.insta_paid_on <'=>$last_update_date,
                         'LoanTransaction.insta_interest_paid >'=>0,
        				),
        			
        				'joins' =>
        						  array(
        							array(
        								'table' => 'loans',
        								'alias' => 'Loan',
        								'type' => 'inner',
        								'foreignKey' => true,
        								'conditions'=> array('Loan.id = LoanTransaction.loan_id')
        							 )
        						)
        				
        		));
                
                $loan_realisable = $this->LoanTransaction->find('all',array(
        			'fields'=>array(
        				'SUM(LoanTransaction.total_installment) as realizable_amount',
                        'SUM(LoanTransaction.insta_principal_due) as realizable_principal_amount',
                        'SUM(LoanTransaction.insta_interest_due) as realizable_interest_amount'
        			),
        			'conditions'=>array(
        				
        				 'Loan.user_id'=>$user_id,
        				 'Loan.loan_status_id'=>3,
        				 'LoanTransaction.insta_due_on <='=>$last_update_date,
                         
        				),
        			
        				'joins' =>
        						  array(
        							array(
        								'table' => 'loans',
        								'alias' => 'Loan',
        								'type' => 'inner',
        								'foreignKey' => true,
        								'conditions'=> array('Loan.id = LoanTransaction.loan_id')
        							 )
        						)
        				
        		));
                
                
                
               
                                            
				$loan_data = $this->Loan->find('all', array(
                        'fields' => array(
                            'SUM(Loan.loan_principal) as total_loan'),   
                        'conditions' => array(
                            'Loan.user_id'=>$user_id,
                            'Loan.loan_status_id' => 3,
                            'Loan.status' => 1
                        )));
                $total_loan = $loan_data[0][0]['total_loan'];             
                $total_realiable = $loan_realisable[0][0]['realizable_amount'];
                $total_realized = $loan_realise[0][0]['realized_amount'];   
                $total_overdue = $total_realiable-$total_realized;
                
                $total_loan_market = $total_loan - $loan_realise[0][0]['realized_amount']-$loan_realisable[0][0]['realizable_interest_amount'];	;
                $branchLoanSummary=array();
                $loanOfficerSummary['organization_details'] = $organizationArray;
                $loanOfficerSummary['user_details'] = $userArray;
                $loanOfficerSummary['branch_details'] = $branchArray;
                $loanOfficerSummary['total_kendra'] = $this->Kendra->find('count', array('conditions' => array('Kendra.user_id'=>$user_id,'Kendra.status'=>1) ));
                $loanOfficerSummary['total_cuatomer'] = $this->Customer->find('count', array('conditions' => array('Customer.user_id'=>$user_id,'Customer.status'=>1) ));
                $loanOfficerSummary['total_overdue'] = $total_overdue;
                
                $loanOfficerSummary['total_loan'] = $total_loan;
                $loanOfficerSummary['total_loan_market'] = $total_loan_market;
                $loanOfficerSummary['total_realizable'] = $total_realiable;
                $loanOfficerSummary['total_relaized'] = $total_realized;
                $loanOfficerSummary['percentage_paid'] = ($total_realiable>0)?round(($total_realized/$total_realiable*100),2):0;
                $loan_payment_list = $this->LoanTransaction->find('all', array(
                    'fields' => array(
                        'LoanTransaction.insta_due_on',
                        'LoanTransaction.insta_paid_on',
                        'SUM(LoanTransaction.total_installment) as total_installment',
                        'SUM(LoanTransaction.insta_principal_paid) as total_principal_paid',
                        'SUM(LoanTransaction.insta_interest_paid) as total_interest_paid',
                        'SUM(LoanTransaction.overdue_paid) as total_overdue_paid',
                        'SUM(LoanTransaction.prepayment) as total_prepayment',
						 
						),
                    'conditions' => array(
                        'Loan.loan_status_id' => 3,
                        'Loan.status' => 1,
                        'Loan.user_id' => $user_id,
                        'LoanTransaction.insta_due_on <'=> $last_update_date),
                    'joins' => array(array(
                            'table' => 'loans',
                            'alias' => 'Loan',
                            'type' => 'inner',
                            'foreignKey' => true,
                            'conditions' => array('Loan.id = LoanTransaction.loan_id'))),
                    'order' => array('LoanTransaction.insta_paid_on DESC'),
                    'group' => 'LoanTransaction.insta_paid_on'));
                $loanOfficerSummary['loan_table'] = $loan_payment_list;
                $loanOfficerSummary['data_status'] = 1;
        } else {
            $loanOfficerSummary['data_status'] = 0;
        }
        return $loanOfficerSummary;
    }
	// Loan Officer Wise Details End
    
	// Loan Officer wise details for the Dashboard START
    public function app_loan_officer_details2($user_id = '')
    {
        $branchLoanSummary = array();
        $loanOfficerSummary = array();
        $loanArray = array();
        if ($user_id != '') {
            $user_data = $this->User->find('first', array('conditions' => array('User.id' =>
                        $user_id)));
            if (!empty($user_data['Kendra'])) {
                $organizationArray = $user_data['Organization'];
                $branchArray = $user_data['Branch'];
                $userArray = $user_data['User'];

                $kendraArray = $user_data['Kendra'];
                $customerArray = $user_data['Customer'];
            }
            $user_loan_data = $this->Loan->find('all', array('conditions' => array(
                    'Loan.user_id' => $user_id,
                    'Loan.loan_status_id' => 3,
                    'Loan.status' => 1)));
            if (!empty($user_loan_data)) {
                $loanArray = $user_loan_data;
                $loanDetailsArray = array();
                foreach ($loanArray as $kbranch => $branchloandetails) {
                    if (!empty($loanArray)) {
                        $loan_id = $branchloandetails['Loan']['id'];
                        $summary = $this->loan_summary($loan_id);
                        $loanDetailsArray[] = $summary;
                    }
                }
                $total_overdue = 0;
                $overdue_no = 0;
                $total_loan = 0;
                $total_loan_market = 0;
                $total_realizable = 0;
                $total_relaized = 0;
                $percentage_paid = 0;
                $number_of_loop = 0;
                if (!empty($loanDetailsArray)) {
                    foreach ($loanDetailsArray as $kloande => $loandetails) {
                        if ($loandetails['last_paid_date'] != '') {
                            $total_overdue = $total_overdue + $loandetails['total_overdue'];
                            $overdue_no = $overdue_no + $loandetails['overdue_no'];
                            $total_loan = $total_loan + $loandetails['loan_repay_total'];
                            $total_loan_market = $total_loan_market + $loandetails['loan_due_balance'];
                            $total_realizable = $total_realizable + $loandetails['total_realiable'];
                            $total_relaized = $total_relaized + $loandetails['total_realized'];
                            $percentage_paid = $percentage_paid + $loandetails['percentage_paid'];
                            $number_of_loop = $number_of_loop + 1;
                        }
                    }
                }
                $loanOfficerSummary['organization_details'] = $organizationArray;
                $loanOfficerSummary['user_details'] = $userArray;
                $loanOfficerSummary['branch_details'] = $branchArray;
                $loanOfficerSummary['total_kendra'] = count($kendraArray);
                $loanOfficerSummary['total_cuatomer'] = count($customerArray);
                $loanOfficerSummary['total_overdue'] = $total_overdue;
                $loanOfficerSummary['overdue_no'] = $overdue_no;
                $loanOfficerSummary['total_loan'] = $total_loan;
                $loanOfficerSummary['total_loan_market'] = $total_loan_market;
                $loanOfficerSummary['total_realizable'] = $total_realizable;
                $loanOfficerSummary['total_relaized'] = $total_relaized;
                $loanOfficerSummary['percentage_paid'] = round(($percentage_paid / $number_of_loop),
                    2);
                $loan_payment_list = $this->LoanTransaction->find('all', array(
                    'fields' => array(
                        'LoanTransaction.insta_due_on',
                        'LoanTransaction.insta_paid_on',
                        'SUM(LoanTransaction.total_installment) as total_installment',
                        'SUM(LoanTransaction.insta_principal_paid) as total_principal_paid',
                        'SUM(LoanTransaction.insta_interest_paid) as total_interest_paid'),
                    'conditions' => array(
                        'Loan.loan_status_id' => 3,
                        'Loan.status' => 1,
                        'Loan.user_id' => $user_id),
                    'joins' => array(array(
                            'table' => 'loans',
                            'alias' => 'Loan',
                            'type' => 'inner',
                            'foreignKey' => true,
                            'conditions' => array('Loan.id = LoanTransaction.loan_id'))),
                    'group' => 'LoanTransaction.insta_due_on'));
                $loanOfficerSummary['loan_table'] = $loan_payment_list;
                $loanOfficerSummary['data_status'] = 1;
            } else {
                $loanOfficerSummary['data_status'] = 0;
            }
        } else {
            $loanOfficerSummary['data_status'] = 0;
        }
        return $loanOfficerSummary;
    }
	// Loan Officer wise details for the Dashboard End


    // Loan Summary for Dashboard START
    public function dash_loan_summary($loan_id, $start_date, $end_date)
    {
        $loan_data = $this->Loan->find('first', array('conditions' => array('Loan.id' =>
                    $loan_id)));
        $loan_overdue = $this->LoanTransaction->find('all', array('fields' => array('SUM(LoanTransaction.total_installment) as total_overdue',
                    'COUNT(LoanTransaction.id) as overdue_no'), 'conditions' => array(
                'LoanTransaction.loan_id' => $loan_id,
                'LoanTransaction.insta_due_on BETWEEN \'' . $start_date . '\' AND \'' . $end_date .
                    '\'',
                'LoanTransaction.insta_principal_paid' => 0)));
        $loan_paid = $this->LoanTransaction->find('all', array('fields' => array(
                'COUNT(LoanTransaction.id) as instalment_paid_no',
                'SUM(LoanTransaction.total_installment) as paid_amount',
                'MAX(insta_paid_on) as last_paid_date'), 'conditions' => array(
                'LoanTransaction.loan_id' => $loan_id,
                'LoanTransaction.insta_principal_paid >' => 0,
                'LoanTransaction.insta_due_on BETWEEN \'' . $start_date . '\' AND \'' . $end_date .
                    '\'')));
        $loandetails['loan_id'] = $loan_id;
        $loandetails['total_overdue'] = $loan_overdue[0][0]['total_overdue'];
        $loandetails['overdue_no'] = $loan_overdue[0][0]['overdue_no'];
        $loandetails['last_paid_date'] = $loan_paid[0][0]['last_paid_date'];
        $loandetails['total_installment_no'] = $loan_data['Loan']['loan_period'];
        $loandetails['paid_installment'] = $loan_paid[0][0]['instalment_paid_no'];
        $loandetails['installment_amount'] = $loan_data['Loan']['loan_rate'];
        $loandetails['loan_due_balance'] = $loan_data['Loan']['loan_rate'] * ($loan_data['Loan']['loan_period'] -
            $loan_paid[0][0]['instalment_paid_no'] - $loan_overdue[0][0]['overdue_no']);
        $loandetails['loan_principal'] = $loan_data['Loan']['loan_principal'];
        $loandetails['paid_amount'] = $loan_data['Loan']['loan_rate'] * $loandetails['paid_installment'];
        $loandetails['loan_dateout'] = $loan_data['Loan']['loan_dateout'];
        $loandetails['loan_date'] = $loan_data['Loan']['loan_date'];
        $loandetails['loan_number'] = $loan_data['Loan']['loan_number'];
        $loandetails['loan_purpose'] = $loan_data['Loan']['loan_purpose'];
        $loandetails['loan_interest'] = $loan_data['Loan']['loan_interest'];
        $loandetails['loan_repay_total'] = $loan_data['Loan']['loan_repay_total'];
        $loandetails['currency'] = $loan_data['Loan']['currency'];
        $loandetails['loan_period_unit'] = $loan_data['Loan']['loan_period_unit'];
        $loandetails['loan_type'] = $loan_data['Loan']['loan_type'];
        $loandetails['total_realiable'] = $loandetails['total_overdue'] + $loan_paid[0][0]['paid_amount'];
        $loandetails['total_realized'] = $loan_paid[0][0]['paid_amount'];
        $loandetails['percentage_paid'] = ($loandetails['total_realiable'] > 0) ? round(($loandetails['total_realized'] *
            100 / $loandetails['total_realiable']), 2) : '0';
        return $loandetails;
    }
    // Loan Summery for Dashboard End

    // Dashboard Summery of Branch Wise Start
    function dashboard_branch_table($bkey, $start_date, $end_date)
    {
        $branch_data_full = array();
        $branch_data = $this->dashboard_branch_details($bkey, $start_date, $end_date);
        $kendra_list = $this->Kendra->find('list', array('fields' => array('id',
                    'kendra_name'), 'conditions' => array('Kendra.status' => 1, 'Kendra.branch_id' =>$bkey)));	
                    		
        $branch_data['Branch']['total_kendra'] = count($branch_data['Kendra']);
        $branch_data['Branch']['total_customer'] = count($branch_data['Customer']);
        $branch_data['Branch']['organization_name'] = $branch_data['Organization']['organization_name'];
        $branch_data['Branch']['manager_name'] = $branch_data['User']['first_name'] .
            ' ' . $branch_data['User']['last_name'];
        $branch_data['Branch']['manager_email'] = $branch_data['User']['email'];
        if (!empty($branch_data['Loan'])) {
            unset($branch_data['Organization']);
            unset($branch_data['Region']);
            unset($branch_data['User']);
            unset($branch_data['Kendra']);
            unset($branch_data['Customer']);
            unset($branch_data['Loan']);
            // Kendra Table Details Start
            foreach ($kendra_list as $kkey => $dkendra) {
                $kendra_data = $this->dashboard_kendra_table($kkey, $start_date, $end_date);
                $kendra_data_full[$kkey] = $kendra_data;
            }
            // Kendra Table Details End
            $branch_data_full['Branch'] = $branch_data['Branch'];
            $branch_data_full['Kendra'] = $kendra_data_full;
        }
        return $branch_data_full;
    }
    // Dashboard Summery of Branch Wise End

    // Dashboard Summery table of Kendra Wise Start
    function dashboard_kendra_table($kkey, $start_date, $end_date)
    {
        $kendra_data = $this->dashboard_kendra_details($kkey, $start_date, $end_date);
        $kendra_data['Kendra']['total_customer'] = count($kendra_data['Customer']);
        $kendra_data['Kendra']['organization_name'] = $kendra_data['Organization']['organization_name'];
        $kendra_data['Kendra']['branch_name'] = $kendra_data['Branch']['branch_name'];
        $kendra_data['Kendra']['loan_officer_name'] = $kendra_data['User']['first_name'] .
            ' ' . $kendra_data['User']['last_name'];
        unset($kendra_data['Organization']);
        unset($kendra_data['Region']);
        unset($kendra_data['User']);
        unset($kendra_data['Customer']);
        unset($kendra_data['Loan']);
        unset($kendra_data['Branch']);
        return $kendra_data;
    }
    // Dashboard Summery table of Kendra wise End

    // branch Details for a specific time period START
    public function dashboard_branch_details($kendra_id, $start_date, $end_date)
    {
        $outputarr = array();
        $this->Branch->unBindModel(array('hasMany' => array('Loan', 'Customer')));
        $kendra_data = $this->Branch->find('first', array('conditions' => array('Branch.id' =>
                    $kendra_id)));  
                            
        $this->Customer->unBindModel(array(
        'hasMany' => array('Loan', 'Idproof','SavingsTransaction','Order'),
        'belongsTo' => array('Organization', 'Region','Branch','Kendra','User','Country'),
        'hasOne' => array('Savings')
        ));  
        
                 
        $kendra_data['Customer'] = $this->Customer->find('all', array(
                            'conditions' =>array('Customer.branch_id' => $kendra_id, 'Customer.status' => 1)));       
        $this->Loan->unBindModel(array('hasMany' => array('LoanTransaction')));
        $kendra_data['Loan'] = $this->Loan->find('all', array(
                    'conditions' => array(
                        'Loan.branch_id' => $kendra_id,
                        'Loan.loan_status_id' => 3,
                        'Loan.status' => 1)
        ));
        $loan_sum_data = $this->Loan->find('all', array('fields' => array('SUM(Loan.loan_principal) as total_loan'),
                'conditions' => array(
                            'Loan.branch_id' => $kendra_id,
                            'Loan.loan_status_id' => 3,
                            'Loan.status' => 1)
                ));
        $kendra_data['Branch']['total_loan'] = $loan_sum_data[0][0]['total_loan'];
        
        
        $kendra_data['Kendra'] = $this->Kendra->find('all', array(
                'conditions' => array(
                            'Kendra.branch_id' => $kendra_id,
                            'Kendra.status' => 1)
                ));
        
        
        $lt_data = $this->LoanTransaction->find('all', array(
            'fields' => array('SUM(LoanTransaction.insta_principal_paid) as total_principal_paid',
                    'SUM(LoanTransaction.insta_interest_paid) as total_interest_paid'),
            'conditions' => array(
                'Loan.branch_id' => $kendra_id,
                'Loan.loan_status_id' => 3,
                'Loan.status' => 1),
            'joins' => array(array(
                    'table' => 'loans',
                    'alias' => 'Loan',
                    'type' => 'inner',
                    'foreignKey' => true,
                    'conditions' => array('Loan.id = LoanTransaction.loan_id')))));
			//pr($lt_data); die;
        $kendra_data['Branch']['total_loan_repayment'] = $lt_data[0][0]['total_principal_paid'] +
            $lt_data[0][0]['total_interest_paid'];
        $kendra_data['Branch']['total_loan_in_market'] = $loan_sum_data[0][0]['total_loan'] -
            $kendra_data['Branch']['total_loan_repayment'];
        $loan_overdue = $this->LoanTransaction->find('all', array(
            'fields' => array(
                'SUM(LoanTransaction.total_installment) as total_installment',
                'SUM(LoanTransaction.insta_principal_paid) as total_principal_paid',
                'SUM(LoanTransaction.insta_interest_paid) as total_interest_paid'),
            'conditions' => array(
                'Loan.branch_id' => $kendra_id,
                'Loan.loan_status_id' => 3,
                'Loan.status' => 1,
                'LoanTransaction.insta_due_on BETWEEN \'' . $start_date . '\' AND \'' . $end_date .
                    '\''),
            'joins' =>array(
    					array(
    						'table' => 'loans',
    						'alias' => 'Loan',
    						'type' => 'inner',
    						'foreignKey' => true,
    						'conditions'=> array('Loan.id = LoanTransaction.loan_id')
    					 )
    				)
                    
            ));
        $kendra_data['Branch']['total_realisable'] = ($loan_overdue[0][0]['total_installment'] ==
            '' ? 0 : $loan_overdue[0][0]['total_installment']);
        $realizable_amount = ($loan_overdue[0][0]['total_principal_paid'] + $loan_overdue[0][0]['total_interest_paid']);
        $kendra_data['Branch']['total_realise'] = ($realizable_amount == '' ? 0 : $realizable_amount);
        $kendra_data['Branch']['total_overdue'] = $kendra_data['Branch']['total_realisable'] -
            $kendra_data['Branch']['total_realise'];
        if ($kendra_data['Branch']['total_realisable'] != 0) {
            $kendra_data['Branch']['percent_paid'] = round(($kendra_data['Branch']['total_realise'] /
                $kendra_data['Branch']['total_realisable'] * 100), 2);
        } else {
            $kendra_data['Branch']['percent_paid'] = 0;
        }
        return $kendra_data;
    }
    // branch Details for a specific time period END

    // kendra Details for a specific time period Start
    public function dashboard_kendra_details($kendra_id, $start_date, $end_date)
    {
        $outputarr = array();
        $this->Kendra->unBindModel(array('hasMany' => array('Loan', 'Customer')));
        $kendra_data = $this->Kendra->find('first', array('conditions' => array('Kendra.id' =>
                    $kendra_id)));
        $this->Customer->unBindModel(array(
        'hasMany' => array('Loan', 'Idproof','SavingsTransaction','Order'),
        'belongsTo' => array('Organization', 'Region','Branch','Kendra','User','Country'),
        'hasOne' => array('Savings')
        )); 
        $kendra_data['Customer'] = $this->Customer->find('all', array('conditions' =>
                array('Customer.kendra_id' => $kendra_id, 'Customer.status' => 1)));
        $this->Loan->unBindModel(array('hasMany' => array('LoanTransaction')));
        $kendra_data['Loan'] = $this->Loan->find('all', array('conditions' => array(
                'Loan.kendra_id' => $kendra_id,
                'Loan.loan_status_id' => 3,
                'Loan.status' => 1)));
        $loan_sum_data = $this->Loan->find('all', array('fields' => array('SUM(Loan.loan_principal) as total_loan'),
                'conditions' => array(
                'Loan.kendra_id' => $kendra_id,
                'Loan.loan_status_id' => 3,
                'Loan.status' => 1)));
        $kendra_data['Kendra']['total_loan'] = $loan_sum_data[0][0]['total_loan'];
        $lt_data = $this->LoanTransaction->find('all', array(
            'fields' => array('SUM(LoanTransaction.insta_principal_paid) as total_principal_paid',
                    'SUM(LoanTransaction.insta_interest_paid) as total_interest_paid'),
            'conditions' => array(
                'Loan.kendra_id' => $kendra_id,
                'Loan.loan_status_id' => 3,
                'Loan.status' => 1),
            'joins' => array(array(
                    'table' => 'loans',
                    'alias' => 'Loan',
                    'type' => 'inner',
                    'foreignKey' => true,
                    'conditions' => array('Loan.id = LoanTransaction.loan_id')))));
        $kendra_data['Kendra']['total_loan_repayment'] = $lt_data[0][0]['total_principal_paid'] +
            $lt_data[0][0]['total_interest_paid'];
        $kendra_data['Kendra']['total_loan_in_market'] = $loan_sum_data[0][0]['total_loan'] -
            $kendra_data['Kendra']['total_loan_repayment'];
        $loan_overdue = $this->LoanTransaction->find('all', array(
            'fields' => array(
                'SUM(LoanTransaction.total_installment) as total_installment',
                'SUM(LoanTransaction.insta_principal_paid) as total_principal_paid',
                'SUM(LoanTransaction.insta_interest_paid) as total_interest_paid'),
            'conditions' => array(
                'Loan.kendra_id' => $kendra_id,
                'Loan.loan_status_id' => 3,
                'Loan.status' => 1,
                'LoanTransaction.insta_due_on BETWEEN \'' . $start_date . '\' AND \'' . $end_date .
                    '\''),
            'joins' => array(array(
                    'table' => 'loans',
                    'alias' => 'Loan',
                    'type' => 'inner',
                    'foreignKey' => true,
                    'conditions' => array('Loan.id = LoanTransaction.loan_id')))));
        $kendra_data['Kendra']['total_realisable'] = $loan_overdue[0][0]['total_installment'];
        $kendra_data['Kendra']['total_realise'] = $loan_overdue[0][0]['total_principal_paid'] +
            $loan_overdue[0][0]['total_interest_paid'];
        $kendra_data['Kendra']['total_overdue'] = $kendra_data['Kendra']['total_realisable'] -
            $kendra_data['Kendra']['total_realise'];
        $kendra_data['Kendra']['percent_paid'] = ($kendra_data['Kendra']['total_realisable'] >
            0) ? round(($kendra_data['Kendra']['total_realise'] / $kendra_data['Kendra']['total_realisable'] *
            100), 2) : 0;
        return $kendra_data;
    }
    // kendra Details for a specific time period END

    // Find the date difference function Start
    public function date_differ($date1,$date2){
       
        $diff = abs(strtotime($date2) - strtotime($date1));
        
        $years = floor($diff / (365*60*60*24));
        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
        return $days;
    }
    // Find the date difference function End
	
    // Delete all data of a customer which are uploaded through CSV Start
	public function delete_customer($customer_id, $cascade = true, $callbacks = false){
		$customers_data = $this->Customer->find('all',array('conditions'=>array('Customer.status'=>1, 'Customer.id' => $customer_id)));
		$this->Customer->deleteAll(['Customer.id' => $customer_id, 'Customer.upload_type' => 'CSV'], $cascade,	$callbacks);
		$this->Loan->deleteAll(['Loan.customer_id' => $customer_id, 'Loan.upload_type' => 'CSV'], $cascade,	$callbacks);
		$this->LoanTransaction->deleteAll(['LoanTransaction.customer_id' => $customer_id, 'LoanTransaction.upload_type' => 'CSV'], $cascade,	$callbacks);
		$this->Saving->deleteAll(['Saving.customer_id' => $customer_id, 'Saving.upload_type' => 'CSV'], $cascade,	$callbacks);
		$this->SavingsTransaction->deleteAll(['SavingsTransaction.customer_id' => $customer_id, 'SavingsTransaction.upload_type' => 'CSV'], $cascade,	$callbacks);	
		$this->Session->setFlash(__('Customer Delete Done'));
	}
    // Delete all data of a customer which are uploaded through CSV End
	
	// Product Order Collection Amount function start
    public function order_amount_collection($ord_id,$due_on,$trans_id=0,$amt=0)
    {
            $this->Order->unBindModel(array('hasMany' => array('LoanTransaction')));
            $order_data = $this->Order->find('first', 
            array(
            'fields' => array(
                    'Order.id',
                    'Order.repay_total'
                    ), 
            'conditions' => array(
                    'Order.id' => $ord_id,
                    'Order.status' => 1
                    )
            ));
            if($trans_id>0){
                $condiarr = array(
                    'LoanTransaction.id' => $trans_id
                    );
            }else{
                $condiarr = array(
                    'LoanTransaction.order_id' => $ord_id,
                    'LoanTransaction.insta_due_on' => $due_on
                    );
            }       
            $loan_trans = $this->LoanTransaction->find('first', 
                            array(
                                'conditions' => $condiarr
                            ));         
            $total_repay_amt = $this->LoanTransaction->find('all',array('fields'=>array('SUM(LoanTransaction.insta_principal_paid) as total_paid'),'conditions'=>array('LoanTransaction.order_id'=>$ord_id,'LoanTransaction.insta_principal_paid >'=>0)));      
            if($amt==0){
                $amt = $loan_trans['LoanTransaction']['insta_principal_due'];
            }
            $trns_arr['LoanTransaction']['id'] = $loan_trans['LoanTransaction']['id'];
            $trns_arr['LoanTransaction']['insta_paid_on'] = date("Y-m-d");
            $trns_arr['LoanTransaction']['insta_principal_paid'] = $loan_trans['LoanTransaction']['insta_principal_due'];
            $trns_arr['LoanTransaction']['current_outstanding'] = $order_data['Order']['repay_total'] - ($total_repay_amt[0][0]['total_paid'] + $amt);
            $trns_arr['LoanTransaction']['modified_on'] = date("Y-m-d");
            $trns_arr['LoanTransaction']['user_id'] = $this->Auth->user('id');
            $this->LoanTransaction->clear();
            $this->LoanTransaction->save($trns_arr);
            $this->check_order_payments_and_clear($order_data['Order']['id']); // Change order status if all installment paid
    }
    // Product Order Collection Amount function End
	
	// If all the payment clear for the order of a product then close the order function start
    public function check_order_payments_and_clear($order_id)
    {
        $loan_trans = $this->LoanTransaction->find('count', array('conditions' => array
                ('LoanTransaction.order_id' => $order_id, 'LoanTransaction.insta_principal_paid' =>
                    0)));
        if ($loan_trans == 0) {
            $this->Order->id = $order_id;
            $this->Order->saveField('order_status_id', 4);
        }
    }
    // If all the payment clear for the order of a product then close the order function end
	
	// Delete an transition of a particular date function start 
    public function delete_trans($kendra_id, $trans_date){
        $data['insta_paid_on']='0000-00-00';
        $data['insta_principal_paid']='0';
        $data['insta_interest_paid']='0';
        $data['current_outstanding']='0';
        $this->LoanTransaction->updateAll($data,array('LoanTransaction.kendra_id' => $kendra_id,'LoanTransaction.insta_due_on'=>$trans_date));
    }
	// Delete an transition of a particular date function end 
    
	// Best kendra and Branch with details function start
    public function best_details(){
		$organization_id=$this->Auth->user('organization_id');
		$max_date = $this->LoanTransaction->find('all',array(
			'fields'=>array(
				'MAX(LoanTransaction.insta_paid_on) as max_date'
			),
			'conditions'=>array(
				
				'LoanTransaction.insta_paid_on !='=> '0000-00-00',
				 'Loan.loan_status_id'=>3,
				 'Loan.organization_id'=>$organization_id
			),
			'joins' =>
						  array(
							array(
								'table' => 'loans',
								'alias' => 'Loan',
								'type' => 'inner',
								'foreignKey' => true,
								'conditions'=> array('Loan.id = LoanTransaction.loan_id')
			)),
		));
		$end_date=$max_date[0][0]['max_date'];
		$min_date = $this->LoanTransaction->find('all',array(
			'fields'=>array(
				'MIN(LoanTransaction.insta_paid_on) as max_date'
			),
			'conditions'=>array(
			
				 'Loan.loan_status_id'=>3,
				 'Loan.organization_id'=>$organization_id
			),
			'joins' =>
						  array(
							array(
								'table' => 'loans',
								'alias' => 'Loan',
								'type' => 'inner',
								'foreignKey' => true,
								'conditions'=> array('Loan.id = LoanTransaction.loan_id')
			)),
		));
		$start_date=$min_date[0][0]['max_date'];
		// Branch Details
		$branch_list=$this->Branch->find('all',array(
			'fields'=>array(
				'Branch.id as branch_id',
				'Branch.branch_name as branch_name',
			),
			'conditions'=>array(
				 'Branch.organization_id'=>$organization_id
			)
			));
			$test_val=0;
			foreach ($branch_list as $bk=> $barr){
			$branch_id=$barr['Branch']['branch_id'];
			$branchloan[$bk]['branch_id']=$barr['Branch']['branch_id'];
			$branchloan[$bk]['branch_name']=$barr['Branch']['branch_name'];
			$branch_data= $this->Branch->find('first',array('conditions'=>array('Branch.id'=>$branch_id)));
				if(!empty($barr['Loan'])){
				$organizationArray=$branch_data['Organization'];
				 $branchArray=$branch_data['Branch'];
				 $branchManagerArray=$branch_data['User'];
				 $loanArray=$branch_data['Loan'];
				 $kendraArray=$branch_data['Kendra'];
				 $customerArray=$branch_data['Customer'];	 
					$loan_overdue = $this->LoanTransaction->find('all', array(
                        'fields' => array(
                            'SUM(LoanTransaction.total_installment) as total_overdue',
                            
                            'COUNT(LoanTransaction.id) as overdue_no'),
                            
                        'conditions' => array(
                            'Loan.branch_id'=>$branch_id,
                            'Loan.loan_status_id' => 3,
                            'Loan.status' => 1,
                            'LoanTransaction.insta_principal_paid '=> 0,
                            'LoanTransaction.insta_due_on <'=> $end_date),
                        'joins' => array(array(
                                'table' => 'loans',
                                'alias' => 'Loan',
                                'type' => 'inner',
                                'foreignKey' => true,
                                'conditions' => array('Loan.id = LoanTransaction.loan_id')))));
					$loan_payment = $this->LoanTransaction->find('all', array(
                        'fields' => array(
                            'SUM(LoanTransaction.total_installment) as total_installment',
                            'SUM(LoanTransaction.insta_principal_paid + LoanTransaction.insta_interest_paid) as total_installment_paid',
                            'SUM(LoanTransaction.insta_principal_paid) as total_principal_paid',
                            'SUM(LoanTransaction.insta_interest_paid) as total_interest_paid'),
                            
                        'conditions' => array(
                            'Loan.branch_id'=>$branch_id,
                            'Loan.loan_status_id' => 3,
                            'Loan.status' => 1,
                            'LoanTransaction.insta_due_on <'=> $end_date),
                        'joins' => array(array(
                                'table' => 'loans',
                                'alias' => 'Loan',
                                'type' => 'inner',
                                'foreignKey' => true,
                                'conditions' => array('Loan.id = LoanTransaction.loan_id')))));      
            $loan_data = $this->Loan->find('all', array(
                        'fields' => array(
                            'SUM(Loan.loan_principal) as total_loan'),
                        'conditions' => array(
                            'Loan.branch_id'=>$branch_id,
                            'Loan.loan_status_id' => 3,
                            'Loan.status' => 1
                        )));
                $total_loan = $loan_data[0][0]['total_loan'];               
                $total_realiable = $loan_payment[0][0]['total_installment'];
                $total_realized = $loan_payment[0][0]['total_installment_paid'];  
                $total_overdue = $loan_overdue[0][0]['total_overdue'];
                $overdue_no = $loan_overdue[0][0]['overdue_no']; 
                $total_loan_market = $total_loan - $loan_payment[0][0]['total_principal_paid'];
			   $branchLoanSummary['total_kendra']=count($kendraArray);
			   $branchLoanSummary['total_cuatomer']=count($customerArray);
			   $branchLoanSummary['total_loan']=$total_loan;
			   $branchLoanSummary['percentage_paid']=round(($total_realized/$total_realiable*100),2);
			   $loan_officer=2;
			   $branch_val= $total_loan/($branchLoanSummary['total_cuatomer']*(101- $branchLoanSummary['percentage_paid'])*$loan_officer);
			   $branchLoanSummary['branch_val']=$branch_val;
			} else {
			   $branchLoanSummary['total_kendra']=0;
			   $branchLoanSummary['total_cuatomer']=0;
			   $branchLoanSummary['total_loan']=0;
			   $branchLoanSummary['percentage_paid']=0;
			   $branchLoanSummary['branch_val']=0;
			}
			if($branchLoanSummary['branch_val']>$test_val){
				$best_branch=$branchloan[$bk]['branch_name'];
				$branch_val=$branchLoanSummary['branch_val'];
				$test_val=$branchLoanSummary['branch_val'];
			}
			$branchloan[$bk]['total_customer']= $branchLoanSummary['total_cuatomer'];
			$branchloan[$bk]['total_loan']= $branchLoanSummary['total_loan'];
			$branchloan[$bk]['percentage_paid']= $branchLoanSummary['percentage_paid'];
		}
		// Kendra wise calculation
		$this->Kendra->unBindModel(array(
		'belongsTo' => array(
                'Organization',
                'Region',
                'User',
                'Branch'
               ),
		'hasMany' => array(
                'Loan',
				'Customer'
               ),
				));
		$kendra_list=$this->Kendra->find('all',array(
    			'fields'=>array(
    				'Kendra.id as kendra_id',
    				'Kendra.kendra_name as kendra_name',
    			),
    			'conditions'=>array(
    				 'Kendra.organization_id'=>$organization_id
    			)
			));
			$best_kendra_name='';
			$worst_kendra_name='';
			$best_kendra_value=0;
			$worst_kendra_value=10000000000;
			$kendra_val_array=array();
			foreach($kendra_list as $k=>$karr){
				$kendra_id=$karr['Kendra']['kendra_id'];
				$kendra_val=$this->dashboard_kendra_details($kendra_id, $start_date, $end_date);
				//pr($kendra_val); die;
				$total_loan=$kendra_val['Kendra']['total_loan'];
				$percent_paid=$kendra_val['Kendra']['percent_paid'];
				$total_customer=count($kendra_val['Customer']);
				$loan_officer=1;
				$best_val=$total_loan/($total_customer*(101-$percent_paid)*$loan_officer);
                    $kendra_val_array[$k]['kendra_id']=$karr['Kendra']['kendra_id'];
					$kendra_val_array[$k]['best_kendra']=$karr['Kendra']['kendra_name'];
					$kendra_val_array[$k]['best_kendra_val']=$best_val;
				if( $best_val>$best_kendra_value) {
					$best_kendra_name=$kendra_id=$karr['Kendra']['kendra_name'];
					$best_kendra_value=$best_val;
				}
				if( $best_val<$worst_kendra_value) {
					$worst_kendra_name=$kendra_id=$karr['Kendra']['kendra_name'];
					$worst_kendra_value=$best_val;
				}
			}
			$return_array=array();
			$return_array['best_branch']=$best_branch;
			$return_array['best_branch_val']=$branch_val;
			$return_array['best_kendra']=$best_kendra_name;
			$return_array['best_kendra_val']=$best_kendra_value;
			$return_array['worst_kendra_name']=$worst_kendra_name;
			$return_array['worst_kendra_value']=$worst_kendra_value;
			$return_array['full_val']=Set::sort($kendra_val_array, '{n}.best_kendra_val', 'desc');
			return $return_array;
	}
	// Best kendra and Branch with details function end

	// Branch details in a list for a specific time period function start
    public function branch_details_list($start_date,$end_date){
        $this->Branch->unBindModel(array(
		
    		'hasMany' => array(
                    'Customer',
    				'Loan'
            ),
				));
        $branch_list= $this->Branch->find('all',array('conditions'=>array('Branch.organization_id'=>$this->Auth->user('organization_id'))));
        $this->Customer->unBindModel(array(
		'belongsTo' => array(
                'Organization',
                'Region',
                'User',
                'Country',
               ),
		'hasOne' => array(
                'Savings',
               ),
		'hasMany' => array(
                'Loan',
				'Idproof',
				'SavingsTransaction',
				'Order'
               ),
				));  
        $this->Customer->bindModel(array(
        			'belongsTo' => array(
        				'Market' => array(
        					'foreignKey' => 'market_id',
        					'type'=>'INNER',
        					'conditions' => array('Market.id = Customer.market_id')
        				),
        				'Branch' => array(
        					'foreignKey' => 'branch_id',
        					'type'=>'INNER',
        					'conditions' => array('Branch.id = Market.branch_id')
        				),
        			)
        		));    
        $total_customer= $this->Customer->find('count',array('conditions'=>array('Branch.id'=>$branch_id,'Customer.status'=>1)));   
    }
	// Branch details in a list for a specific time period function end
    
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
    
   public function check_duplicate_customer_by_idproof($id_no,$id_type)
   {
        $data=$this->Customer->find('first',array("fields"=>array('Customer.*'),"conditions"=>array('Customer.id_proof REGEXP \'"id_proof_no":"'.$id_no.'","id_proof_type":"'.$id_type.'"\'')));
        return $data;
    }
	
	
}
// App controller END
?>