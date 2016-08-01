<?php
App::uses('AuthComponent', 'Controller/Component');

class Order extends AppModel {
	
    
    
	var $belongsTo = array(
        'OrderStatus' => array(
			'className'    	=> 'OrderStatus',
			'foriegnKey'	=> 'order_status_id'
		),
        'Product' => array(
			'className'    	=> 'Product',
			'foriegnKey'	=> 'product_id'
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
		)
			
	);
    
    var $hasMany = array(

        'LoanTransaction' => array(
			'className'    	=> 'LoanTransaction',
			'foriegnKey'	=> 'order_id'
		)
	
	);

}

?>