<?php
App::uses('AuthComponent', 'Controller/Component');

class IncomeExpenditure extends AppModel {
	
	var $belongsTo = array(
		'Organization' => array(
			'className'    	=> 'Organization',
			'foriegnKey'	=> 'organization_id'
		),
        'AccountLedger' => array(
			'className'    	=> 'AccountLedger',
			'foriegnKey'	=> 'account_ledger_id'
		),
        'User' => array(
			'className'    	=> 'User',
			'foriegnKey'	=> 'user_id'
		)
			
	);
    
    

}

?>