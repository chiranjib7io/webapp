<?php
App::uses('AuthComponent', 'Controller/Component');

class Wallet extends AppModel {
	
    var $belongsTo = array(

        'BankerLoan' => array(
			'className'    	=> 'BankerLoan',
			'foriegnKey'	=> 'banker_loan_id'
		)
	
	);
    var $hasMany = array(

        'WalletTransaction' => array(
			'className'    	=> 'WalletTransaction',
			'foriegnKey'	=> 'wallet_id'
		),
        'Loan' => array(
			'className'    	=> 'Loan',
			'foriegnKey'	=> 'wallet_id'
		)
	
	);

}

?>