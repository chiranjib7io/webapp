<?php
App::uses('AuthComponent', 'Controller/Component');

class Loan extends AppModel {
	
    
    
	var $belongsTo = array(
        'LoanStatus' => array(
			'className'    	=> 'LoanStatus',
			'foriegnKey'	=> 'loan_status_id'
		),
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
			'foriegnKey'	=> 'branch_id'
		),
        'Market' => array(
			'className'    	=> 'Market',
			'foriegnKey'	=> 'market_id'
		),
        'Kendra' => array(
			'className'    	=> 'Kendra',
			'foriegnKey'	=> 'kendra_id'
		),
        'Customer' => array(
			'className'    	=> 'Customer',
			'foriegnKey'	=> 'customer_id'
		),
        'User' => array(
			'className'    	=> 'User',
			'foriegnKey'	=> 'user_id'
		),
        'Account' => array(
			'className'    	=> 'Account',
			'foriegnKey'	=> 'account_id'
		)
			
	);
    
   
    var $hasOne = array(

        'Insurance' => array(
			'className'    	=> 'Insurance',
			'foriegnKey'	=> 'loan_id'
		)
	
	);
    
    public $hasAndBelongsToMany = array(
        'Gurranter' => array(
            'className' => 'Saving',
        )
    );
    
}

?>