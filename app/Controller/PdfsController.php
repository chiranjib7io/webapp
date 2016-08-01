<?php
// This is PDF controller. In this controller all function are realeated with the PDF document which will send by mail.
App::uses('CakeEmail', 'Network/Email');
class PdfsController extends AppController {
	// List of models which are used in the PDF controller
    var $uses = array('User','Organization','Region','Branch','Kendra','Customer','Loan','Saving','Idproof','LogRecord','Country', 'Setting', 'Fee','Product','Order');
	
	// This is a blank index function for safe navigation
    public function index() {
        
    }
	
	// Create an PDF file function start
	public function create_pdf(){
        $users = $this->Customer->find('all');
        $this->set(compact('users'));
        $this->layout = '/pdf/default';
        $this->render('/Pdfs/my_pdf_view');
    }
	// Create an PDF file function end
    
	// Downloadable PDF function start
    public function download_pdf($filename) {
        $this->viewClass = 'Media';
        $params = array(
            'id' => $filename,
            'name' => $filename,
            'download' => true,
            'extension' => 'pdf',
            'path' => APP . 'files/pdf' . DS
        );
        $this->set($params);
    }
	// Downloadable PDF function end
    
	// Making Branch Manager Report in PDF function start
   	public function branch_manager_report($branch_id=''){
		$this->layout = '/pdf/default';
		$start_date = date("Y-m-d",strtotime("-7 days"));
        $end_date = date("Y-m-d");
		$branch_data = $this->dashboard_branch_table($branch_id,$start_date,$end_date);
	    $this->set('branch_data', $branch_data);      
        //Email the pdf 
        $email = 'chiran.dey301086@gmail.com';//$branch_data['Branch']['contact_email'];
        $message = $branch_data;
        $subject ='Report from Microfinance for Branch '.$branch_data['Branch']['branch_name'];
        $name = $branch_data['Branch']['manager_name'];
        $from = 'chiranjib.dey@7io.co';
        $template = 'report_template';
        $this->_email($email, $message, $subject ,$name, $from, '', $template);
        //Emailpdf end 
	}
    // Making Branch Manager Report in PDF function end
}
// END of PDF controller
?>