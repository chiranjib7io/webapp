<?php
App::uses('AuthComponent', 'Controller/Component');

class Saving extends AppModel {
	
    
    
	var $belongsTo = array(
        
        'Currency' => array(
			'className'    	=> 'Currency',
			'foriegnKey'	=> 'currency_id'
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
			'foriegnKey'	=> 'market_id'
		),
        'Market' => array(
			'className'    	=> 'Market',
			'foriegnKey'	=> 'market_id'
		),
        'User' => array(
			'className'    	=> 'User',
			'foriegnKey'	=> 'user_id'
		),
        'Customer' => array(
			'className'    	=> 'Customer',
			'foriegnKey'	=> 'customer_id'
		),
        'Account' => array(
			'className'    	=> 'Account',
			'foriegnKey'	=> 'account_id'
		)
	);
    
    
    
    public $hasAndBelongsToMany = array(
        'GurranterOf' =>
            array(
                'className' => 'Loan',
                'joinTable' => 'loans_savings',
                'foreignKey' => 'saving_id',
                'associationForeignKey' => 'loan_id',
                'unique' => true,
                
            )
    );
    

}

?>