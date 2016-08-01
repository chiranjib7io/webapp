<?php
// This is kendra controller. All the function related with a kendra is written here.
App::uses('CakeEmail', 'Network/Email');
class MarketsController extends AppController
{
	// List of models which are used in the kendra controller 
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
        'Setting',
        'Fee');
	
	// This is a blank index function for safe navigation
    public function index()
    {

    }
	public function market_summary($market_id='')
    {
        
    }
	
}
// End of Kendra controller
?>