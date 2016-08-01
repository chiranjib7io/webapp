<?php
App::uses('AuthComponent', 'Controller/Component');

class Account extends AppModel {
	
    
    
	var $belongsTo = array(
        
        
		'Organization' => array(
			'className'    	=> 'Organization',
			'foriegnKey'	=> 'organization_id'
		),
        'Region' => array(
			'className'    	=> 'Region',
			'foriegnKey'	=> 'region_id'
		),
        'Branch' => array(
			'className'    	=> 'Branch',
			'foriegnKey'	=> 'market_id'
		),
        'Market' => array(
			'className'    	=> 'Market',
			'foriegnKey'	=> 'market_id'
		),
        'Kendra' => array(
			'className'    	=> 'Kendra',
			'foriegnKey'	=> 'kendra_id'
		),
        'User' => array(
			'className'    	=> 'User',
			'foriegnKey'	=> 'user_id'
		),
        'Customer' => array(
			'className'    	=> 'Customer',
			'foriegnKey'	=> 'customer_id'
		)
	);
    
    
    
    public $hasOne = array(
        'Saving' =>array(
			'className'    	=> 'Saving',
			'foriegnKey'	=> 'account_id'
		),
        'Loan' =>array(
			'className'    	=> 'Loan',
			'foriegnKey'	=> 'account_id'
		),
    );
    

}

?>