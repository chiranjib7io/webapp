<?php

// This is customer controller. All the functions related with customer is listed here.
App::uses('CakeEmail', 'Network/Email');
class CustomersController extends AppController
{
    // List of models which are used in the customer controller
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
        'Market');

    public $components = array('Paginator');

    // Load all the kendras based on a branch in ajax function start
    public function ajaxKendraList($id)
    {
        $data = $this->Kendra->find('list', array('fields' => array('id', 'kendra_name'),
                'conditions' => array('Kendra.branch_id' => $id, 'status' => 1)));
        $this->set('kendraList', $data);
        $this->set('id', $id);
        $this->layout = 'ajax';
    }
    // Load all the kendras based on a branch in ajax function end

    // List of all customers of a organization function start
    public function customer_list()
    {
        $this->set('title', 'Customer List');
        // Import a number helper
        App::import('Helper', 'Number');
        $Number = new NumberHelper(new View(null));
        $organisation_id = $this->Auth->User('organization_id');
        $branches_data = $this->Branch->find('list', array(
            'fields' => array('Branch.id', 'Branch.branch_name'),
            'conditions' => array('Branch.status' => 1, ),
            'recursive' => -1));
        $this->set('branches_data', $branches_data);

        $this->layout = 'panel_layout';
    }
    // List of all customers of a organization function end

    function ajaxCustomerList()
    {
        $this->layout = 'ajax';
        $this->autoRender = false;
        App::import('Helper', 'Number');
        $Number = new NumberHelper(new View(null));
        $requestData = $_REQUEST;
        $aColumns = array(
            "Customer.cust_fname",
            "Branch.branch_name",
            "Market.market_name",
            "User.first_name",
            "Loan.loan_principal",
            "Loan.maturity_date",
            "Saving.current_balance",
            "Saving.maturity_date");


        /*
        * Paging
        */
        $sLimit = "";
        if (isset($requestData['start']) && $requestData['length'] != '-1')
        {
            $sLimit = "LIMIT " . intval($requestData['start']) . ", " . intval($requestData['length']);

        }

        /*
        * Filtering
        * NOTE this does not match the built-in DataTables filtering which does it
        * word by word on any field. It's possible to do here, but concerned about efficiency
        * on very large tables, and MySQL's regex functionality is very limited
        */
        $sWhere = "";
        if (isset($requestData['search']['value']) && $requestData['search']['value'] !=
            "")
        {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++)
            {
                $sWhere .= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($requestData['search']['value']) .
                    "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }

        /*
        * Ordering
        */
        $sOrder = "";
        if (isset($requestData['order'][0]['column']) and $requestData['order'][0]['column'] >
            0)
        {
            $sOrder = "ORDER BY  ";
            if ($requestData['columns'][$requestData['order'][0]['column']]['orderable'] ==
                "true")
            {
                $sOrder .= $aColumns[$requestData['order'][0]['column'] - 1] . " " . ($requestData['order'][0]['dir']
                    === 'asc' ? 'asc' : 'desc') . ", ";
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY")
            {
                $sOrder = "";
            }
        }


        /*
        * SQL queries
        * Get data to display
        */

        $sQuery = "SELECT * ,concat(Customer.cust_fname,' ',Customer.cust_lname) as fullname
            FROM 7cherryio_customers as Customer 
            left join 7cherryio_branches as Branch on (Customer.branch_id=Branch.id) 
            left join 7cherryio_markets as Market on (Customer.market_id=Market.id) 
            left join 7cherryio_users as User on (Customer.user_id=User.id) 
            left join 7cherryio_loans as Loan on (Customer.id=Loan.customer_id) 
            left join 7cherryio_savings as Saving on (Customer.id=Saving.customer_id) 
            $sWhere
            Group by Customer.id
            $sOrder
            $sLimit
            ";
        //echo $sQuery;
        $this->Customer->unBindModel(array(
            'belongsTo' => array('Organization', 'Region'),

            'hasMany' => array('Saving', 'Loan'),
            'hasAndBelongsToMany' => array('MemberOf'),
            ));
        $customers_data = $this->Customer->query($sQuery);

        $sQuery2 = "SELECT count(*) as count
            FROM 7cherryio_customers as Customer 
            left join 7cherryio_branches as Branch on (Customer.branch_id=Branch.id) 
            left join 7cherryio_markets as Market on (Customer.market_id=Market.id) 
            left join 7cherryio_users as User on (Customer.user_id=User.id) 
            left join 7cherryio_loans as Loan on (Customer.id=Loan.customer_id) 
            left join 7cherryio_savings as Saving on (Customer.id=Saving.customer_id)
            Where Customer.status = 1 Group by Customer.id";
        $total_no = $this->Customer->query($sQuery2);
        $totalFiltered = $total_customers = $total_no[0][0]['count'];
        //pr($total_no);die;
        $table_data = array();
        if (!empty($customers_data))
        {
            foreach ($customers_data as $k2 => $customer)
            {
                //pr($customer); die;
                $loan_count = $this->Loan->find('first', array(
                    'fields' => array('Loan.loan_principal', 'Loan.maturity_date'),
                    'conditions' => array(
                        'Loan.loan_status_id' => 3,
                        'Loan.customer_id' => $customer['Customer']['id'],
                        ),
                    'recursive' => -1));
                $saving_count = $this->Saving->find('first', array(
                    'fields' => array('Saving.current_balance', 'Saving.maturity_date'),
                    'conditions' => array(
                        'Saving.status' => 1,
                        'Saving.customer_id' => $customer['Customer']['id'],
                        ),
                    'recursive' => -1));

                $table_data[$k2][0] = $k2 + 1;
                //$table_data[$k2][1] = $customer['Customer']['cust_fname'].' '.$customer['Customer']['cust_lname'];
                $table_data[$k2][1] = $customer[0]['fullname'];
                $table_data[$k2][2] = (!empty($customer['Branch']['branch_name'])) ? $customer['Branch']['branch_name'] :
                    '-';
                $table_data[$k2][3] = (!empty($customer['Market']['market_name'])) ? $customer['Market']['market_name'] :
                    '-';
                $table_data[$k2][4] = $customer['User']['first_name'] . ' ' . $customer['User']['last_name'];
                if (!empty($loan_count['Loan']['loan_principal']))
                {
                    $loan_overdue_detail = $this->customer_overdue_details($customer['Customer']['id']);

                    $table_data[$k2][5] = (!empty($loan_count['Loan']['loan_principal'])) ? $Number->
                        currency($loan_count['Loan']['loan_principal'], '', array('places' => 0)) :
                        'No Active loan';
                    $table_data[$k2][6] = date("d-m-Y", strtotime($loan_count['Loan']['maturity_date']));
                    $table_data[$k2][9] = $loan_overdue_detail[0][0]['insta_realisable'] - $loan_overdue_detail[0][0]['insta_realise'];
                } else
                {
                    $table_data[$k2][5] = 'No Active loan';
                    $table_data[$k2][6] = '-';
                    $table_data[$k2][9] = '0';
                }
                if (!empty($saving_count['Saving']['current_balance']))
                {
                    $table_data[$k2][7] = (!empty($saving_count['Saving']['current_balance'])) ? $Number->
                        currency($saving_count['Saving']['current_balance'], '', array('places' => 0)) :
                        'No Active loan';
                    $table_data[$k2][8] = date("d-m-Y", strtotime($saving_count['Saving']['maturity_date']));
                } else
                {
                    $table_data[$k2][7] = 'No Active Saving';
                    $table_data[$k2][8] = '-';
                }

                $customer_link = $this->base . '/save_customer/' . $customer['Customer']['id'];
                $edit_link = '<a href="' . $customer_link . '"> Edit </a>';
                $customer_url = $this->base . '/customer_details/' . $customer['Customer']['id'];
                $customer_view_link = '/customer_details/' . $customer['Customer']['id'];
                $view_link = '<a target="_BLANK" href="' . $customer_url . '"> View </a>';
                $table_data[$k2][10] = $view_link;
                $table_data[$k2][11] = $edit_link;
            }
        }

        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($total_customers), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $table_data // total data array
                );

        echo $this->prepare_json($json_data);


    }

    // Edit a customer function start
    public function edit($cid = '')
    {
        $this->layout = 'panel_layout';
        $this->set('title', 'Update Customer');
        //$identity_type=$this->id_proof_name();
        //$this->set('identity_type', $identity_type);
        //$relationship_type=$this->relationship_type();
        //$this->set('relationship_type', $relationship_type);
        $user_data = $this->User->find('all', array('conditions' => array('User.id' => $this->
                    Auth->user('id'))));
        $branch_list = $this->Branch->find('list', array('fields' => array('id',
                    'branch_name'), 'conditions' => array('Branch.organization_id' => $this->Auth->
                    user('organization_id'))));
        $kendra_list = $this->Kendra->find('list', array('fields' => array('id',
                    'kendra_name'), 'conditions' => array('organization_id' => $this->Auth->user('organization_id'))));
        $this->set('user_data', $user_data);
        $this->set('branch_list', $branch_list);
        $this->set('kendra_list', $kendra_list);
        $cust_data = $this->Customer->findById($cid);
        $this->set('cust_data', $cust_data);
        if ($this->request->is(array('post', 'put')))
        {
            $this->request->data['Customer']['modified_on'] = date("Y-m-d H:i:s");
            $this->Customer->id = $cid;
            if ($this->Customer->save($this->request->data))
            {
                $file_path = $this->Session->read('Idproof.filepath');
                if (!empty($file_path))
                {
                    $this->Idproof->updateAll(array(
                        'Idproof.id_proof_type' => "'" . $this->request->data['Idproof']['id_proof_type'] .
                            "'",
                        'Idproof.id_proof_no' => "'" . $this->request->data['Idproof']['id_proof_no'] .
                            "'",
                        'Idproof.id_proof_pic' => "'" . $file_path . "'"), array('Idproof.customer_id' =>
                            $cid));
                    $this->Session->delete('file_path');
                } else
                {
                    $this->Idproof->updateAll(array(
                        'Idproof.id_proof_type' => "'" . $this->request->data['Idproof']['id_proof_type'] .
                            "'",
                        'Idproof.id_proof_no' => "'" . $this->request->data['Idproof']['id_proof_no'] .
                            "'",
                        'Idproof.id_proof_pic' => "'" . $cust_data['Idproof'][0]['id_proof_pic'] . "'"),
                        array('Idproof.customer_id' => $cid));
                }
                $this->Session->setFlash(__('Your Customer has been updated.'));
                return $this->redirect('/customer_edit/' . $cid);
            }
            $this->Session->setFlash(__('Unable to update your Customer.'));
        }
        if (!$this->request->data)
        {
            $this->request->data = $cust_data;
        }
    }
    // Edit a customer function end

    // Amount Collection view start
    public function amount_collection($account_no = '')
    {
        $this->layout = 'panel_layout';
        $this->set('title', 'Collection Page');
        $account_data = array();
        $loan_data[0][0] = array();
        if ($this->request->is('post'))
        {
            //pr($this->request->data); die;
            $account_no = $this->request->data['Account']['account_number'];
        } // IF Post End

        if ($account_no != '')
        {
            $this->request->data['Account']['account_number'] = $account_no;
            $account_data = $this->Account->find('first', array('conditions' => array('Account.account_number' =>
                        $account_no), ));
            if (!empty($account_data))
            {
                if (!empty($account_data['Loan']['id']))
                {
                    $loan_data = $this->LoanTransaction->find('all', array('fields' => array(
                            'sum(LoanTransaction.insta_principal_paid + LoanTransaction.insta_interest_paid) as amount_paid',
                            
                            'sum(LoanTransaction.insta_principal_paid) as principal_paid',
                            'sum(LoanTransaction.insta_interest_paid) as interest_paid'), 'conditions' =>
                            array('LoanTransaction.loan_id' => $account_data['Loan']['id'])));
                    $trans_id=$this->LoanTransaction->find("first", array('fields'=>array('max(LoanTransaction.id) as trans_id'),'conditions'=>array('LoanTransaction.loan_id'=>$account_data['Loan']['id'],'LoanTransaction.insta_interest_paid >'=>0), "recursive"=> -1));
                    $trans_id_data = $this->LoanTransaction->findById($trans_id[0]['trans_id']);
                    
                    $loan_data[0][0]['current_outstanding'] = $trans_id_data['LoanTransaction']['current_outstanding'];
                            
                    $insta_amount = $account_data['Loan']['loan_rate'];
                }
                if (!empty($account_data['Saving']['min_deposit_amount']))
                {
                    $insta_amount = $account_data['Saving']['min_deposit_amount'];
                }

                $this->request->data['Account']['amount'] = $insta_amount;
            } else
            {
                $this->Session->setFlash(__('Account number not exist. Please check!'));
            }
        }

        //pr($loan_data);die;
        //pr($account_data);die;
        $this->set('loan_data', $loan_data[0][0]);
        $this->set('account_data', $account_data);

    }
    // Amount Collection view End

    public function ajax_save_collection_amount()
    {
        $this->layout = 'ajax';
        $this->autoRender = false;
        $balance = 0;
        if ($this->request->data['Account']['account_type'] == 'Loan')
        {
            $account_id = $this->request->data['Account']['account_id'];
            $repay_amount = $this->request->data['Account']['amount'];
            $transaction_date = $this->request->data['date'];
            $notes = $this->request->data['note'];
            $fine = $this->request->data['Account']['fine'];
            //echo $account_id.'-'.$repay_amount.'-'.$transaction_date.'-'.$fine;die;
            $balance = $this->loan_installment_collection($account_id, $repay_amount, $transaction_date,
                $notes, $fine);
        }
        if ($this->request->data['Account']['account_type'] == 'Saving')
        {
            $account_id = $this->request->data['Account']['account_id'];
            $repay_amount = $this->request->data['Account']['amount'];
            $transaction_date = $this->request->data['date'];
            $notes = $this->request->data['note'];
            $fine = $this->request->data['Account']['fine'];

            $balance = $this->saving_amount_collection($account_id, $repay_amount, $transaction_date,
                $notes, $fine);
        }
        echo $balance;
    }


    // Amount Withdraw view start
    public function amount_withdraw($account_no = '')
    {
        $this->layout = 'panel_layout';
        $this->set('title', 'Withdraw Page');
        $account_data = array();
        $till_date = array();

        if ($this->request->is('post'))
        {
            //pr($this->request->data); die;
            $account_no = $this->request->data['Account']['account_number'];
        } // IF Post End

        if ($account_no != '')
        {
            $this->request->data['Account']['account_number'] = $account_no;
            $account_data = $this->Account->find('first', array('conditions' => array('Account.account_number' =>
                        $account_no, 'Account.account_type !=' => 'LOAN'), ));

            if (!empty($account_data))
            {

                if (!empty($account_data['Saving']['min_deposit_amount']))
                {
                    $insta_amount = $account_data['Saving']['min_deposit_amount'];
                }

                $this->request->data['Account']['amount'] = $insta_amount;
                //pr($account_data);die;
                if ($account_data['Account']['account_type'] != 'SAVING_Daily')
                {
                    $amount = $account_data['Saving']['savings_amount'];
                } else
                {
                    $amount1 = $this->SavingsTransaction->find("all", array('fields' => array('SUM(SavingsTransaction.balance) as qmbal'),
                            'conditions' => array('SavingsTransaction.saving_id' => $account_data['Saving']['id'])));
                    $amount = $amount1[0][0]['qmbal'];
                }
                $date1 = $account_data['Saving']['savings_date'];
                $date2 = date("Y-m-d");
                $month = $this->cal_month($date1, $date2);
                $till_date = $this->calculate_interest_check_today($account_data['Saving']['id']);
                
                //pr($till_date);die;
            } else
            {
                $this->Session->setFlash(__('Account number not exist. Please check!'));
            }
        }

        //pr($loan_data);die;
        //pr($account_data);die;
        $this->set('till_date', $till_date);
        $this->set('account_data', $account_data);

    }
    // Amount Withdraw view End

    public function ajax_save_withdraw_amount()
    {
        $this->layout = 'ajax';
        $this->autoRender = false;
        $balance = 0;

        if ($this->request->data['Account']['account_type'] == 'Saving')
        {
            $account_id = $this->request->data['Account']['account_id'];
            $amount = $this->request->data['Account']['amount'];
            $transaction_date = $this->request->data['date'];

            $balance = $this->saving_amount_withdraw($account_id, $amount, $transaction_date);
        }
        echo $balance;
    }

    // Create new customer function start
    public function save_customer($emp_id = '')
    {
        $this->layout = 'panel_layout';
        $this->set('title', 'Manage Customer');
        $org_data = $this->get_organization_settings_fees($this->Auth->user('organization_id'));

        if ($this->Auth->user('user_type_id') == 2)
        {
            $branch_list = $this->Branch->find('list', array('fields' => array('id',
                        'branch_name'), 'conditions' => array('Branch.organization_id' => $this->Auth->
                        user('organization_id'))));
            $kendra_list = $this->Kendra->find('list', array('fields' => array('id',
                        'kendra_name'), 'conditions' => array('Kendra.organization_id' => $this->Auth->
                        user('organization_id'))));
            $market_list = $this->Market->find('list', array('fields' => array('id',
                        'market_name'), 'conditions' => array('Market.organization_id' => $this->Auth->
                        user('organization_id'))));
            $co_list = $this->User->find('list', array('fields' => array('id',
                        'fullname'), 'conditions' => array('User.user_type_id'=>5,'User.organization_id' => $this->Auth->
                        user('organization_id'))));
        } else
        {
            $branch_list = $this->Branch->find('list', array('fields' => array('id',
                        'branch_name'), 'conditions' => array('Branch.user_id' => $this->Auth->user('id'))));
            $kendra_list = $this->Kendra->find('list', array('fields' => array('id',
                        'kendra_name'), 'conditions' => array('Kendra.user_id' => $this->Auth->user('id'))));
            $market_list = $this->Market->find('list', array('fields' => array('id',
                        'market_name'), 'conditions' => array('Market.user_id' => $this->Auth->user('id'))));
            $co_list = $this->User->find('list', array('fields' => array('id','fullname'), 'conditions' => array('User.id'=>$this->Auth->
                        user('organization_id'))));
        }
        array_unshift($kendra_list, "Not Applicable");
        $this->set('market_list', $market_list);
        $this->set('kendra_list', $kendra_list);
        $this->set('co_list', $co_list);

        $identity_type = $this->id_proof_name();
        $this->set('identity_type', $identity_type);
        $this->set('emp_id', $emp_id);
        $emp_data = array();
        if ($emp_id != '')
        {
            $emp_data = $this->Customer->findById($emp_id);
            if (!$this->request->data)
            {
                $this->request->data = $emp_data;
            }
        }
        $this->set('emp_data', $emp_data);
        if ($this->request->is('post'))
        {
            //IMAGE UPLOAD SECTION START
            if (!empty($_FILES['customer_image']['name']))
            {
                //    $_FILES['customer_image']['name'];
                $file = $_FILES['customer_image'];
                $ext = substr(strtolower(strrchr($file['name'], '.')), 1);
                $arr_ext = array(
                    'jpg',
                    'jpeg',
                    'gif',
                    'png');
                $image_name = "CUSTOMER_" . rand(1, 1000000000) . '_' . $file['name'];
                if (in_array($ext, $arr_ext))
                {
                    move_uploaded_file($file['tmp_name'], WWW_ROOT . 'customerImages/' . $image_name);
                    //prepare the filename for database entry
                    $this->request->data['Customer']['customer_image'] = $image_name;
                }
            }
            //END IMAGE UPLOAD SECTION


            //pr($this->request->data);//die;
            $mkt_data = $this->Market->findById($this->request->data['Customer']['market_id']);
            $this->request->data['Customer']['region_id'] = $mkt_data['Market']['region_id'];
            $this->request->data['Customer']['branch_id'] = $mkt_data['Market']['branch_id'];
                
            if ($emp_id != '')
            {
                $this->request->data['Customer']['modified_on'] = date("Y-m-d H:i:s");

                $this->Customer->id = $emp_id;
            } else
            {
                
                $this->request->data['Customer']['organization_id'] = $this->Auth->user('organization_id');
                $this->request->data['Customer']['created_on'] = date("Y-m-d H:i:s");
                $this->Customer->create();
            }

            //prepare idproof data
            if (!empty($this->request->data['idproof']))
            {
                $idproof = $this->request->data['idproof'];
                $idproof_arr = array();
                foreach ($idproof['id_proof_no'] as $k => $v)
                {
                    $idproof_arr[$k]['id_proof_no'] = $v;
                    $idproof_arr[$k]['id_proof_type'] = $idproof['id_proof_type'][$k];

                }
                $this->request->data['Customer']['id_proof'] = json_encode($idproof_arr);
            }
            // end idproof data prepare


            //pr($this->request->data);die;

            if ($this->Customer->save($this->request->data))
            {
                $last_insert_user = $this->User->getLastInsertId();
                if ($emp_id == '')
                {

                    $this->Session->setFlash(__('The Customer has been Created'));
                    $emp_id = $last_insert_user;

                } else
                {
                    $this->Session->setFlash(__('The Customer has been Saved'));

                }

                return $this->redirect('/save_customer/' . $emp_id);

            } else
            {
                $this->Session->setFlash(__('The Customer could not be created. Please, try again.'));
            }

        }
    }

    public function ajax_idproof_row()
    {
        $this->layout = 'ajax';
        $identity_type = $this->id_proof_name();
        $this->set('identity_type', $identity_type);
    }


    // List of all customers of a organization function start
    public function customer_list2()
    {
        $this->set('title', 'Customer List');
        App::import('Helper', 'Number');
        $Number = new NumberHelper(new View(null));
        $organisation_id = $this->Auth->User('organization_id');
        $branches_data = $this->Branch->find('list', array(
            'fields' => array('Branch.id', 'Branch.branch_name'),
            'conditions' => array('Branch.status' => 1, ),
            'recursive' => 1));
        $this->set('branches_data', $branches_data);
        $this->Customer->virtualFields = array('full_name' =>
                "CONCAT(Customer.cust_fname, ' ',Customer.cust_lname)");
        $customers_data = $this->Customer->find('list', array('fields' => array('Customer.id',
                    'Customer.full_name'), 'conditions' => array('Customer.status' => 1)));
        $max_date = $this->LoanTransaction->find('all', array(
            'fields' => array('MAX(LoanTransaction.insta_paid_on) as max_date'),
            'conditions' => array(
                'LoanTransaction.order_id' => 0,
                'LoanTransaction.insta_paid_on !=' => '0000-00-00',
                'Loan.loan_status_id' => 3,
                'Loan.organization_id' => $this->Auth->user('organization_id')),
            'joins' => array(array(
                    'table' => 'loans',
                    'alias' => 'Loan',
                    'type' => 'inner',
                    'foreignKey' => true,
                    'conditions' => array('Loan.id = LoanTransaction.loan_id'))),
            ));
        $final_date = $max_date[0][0]['max_date'];
        $cust_data = array();
        $table_data = array();
        if (!empty($customers_data))
        {
            $i = 0;
            foreach ($customers_data as $k2 => $customer)
            {
                $cust_id = $k2;
                $this->Customer->unBindModel(array(
                    'belongsTo' => array(
                        'Organization',
                        'Region',
                        'Branch',
                        'Market',
                        'User',

                        ),

                    'hasMany' => array(
                        'Saving',
                        'Loan',
                        'Idproof'),
                    ));
                $cust_single = $this->Customer->find('first', array('conditions' => array('Customer.id' =>
                            $cust_id)));
                $loan_overdue = $this->LoanTransaction->find('all', array(
                    'fields' => array('SUM(LoanTransaction.total_installment) as total_overdue',
                            'COUNT(LoanTransaction.id) as overdue_no'),
                    'conditions' => array(
                        'LoanTransaction.insta_due_on <=' => $final_date,
                        'Loan.loan_status_id' => 3,
                        'Loan.customer_id' => $cust_id,
                        'LoanTransaction.insta_principal_paid' => 0),
                    'joins' => array(array(
                            'table' => 'loans',
                            'alias' => 'Loan',
                            'type' => 'inner',
                            'foreignKey' => true,
                            'conditions' => array('Loan.id = LoanTransaction.loan_id')))));
                $loan_paid = $this->LoanTransaction->find('all', array(
                    'fields' => array(
                        'SUM(LoanTransaction.total_installment) as total_repay_amt',
                        'COUNT(LoanTransaction.id) as instalment_paid_no',
                        'MAX(LoanTransaction.insta_paid_on) as last_paid_date',
                        'Loan.*'),
                    'conditions' => array(
                        'LoanTransaction.insta_due_on <=' => $final_date,
                        'Loan.loan_status_id' => 3,
                        'Loan.customer_id' => $cust_id,
                        'LoanTransaction.insta_principal_paid !=' => 0),
                    'joins' => array(array(
                            'table' => 'loans',
                            'alias' => 'Loan',
                            'type' => 'inner',
                            'foreignKey' => true,
                            'conditions' => array('Loan.id = LoanTransaction.loan_id')))));
                $customer_link = $this->base . '/customer_edit/' . $cust_id;
                $edit_link = '<a href="' . $customer_link . '"> Edit </a>';
                $customer_url = $this->base . '/customer_details/' . $cust_id;
                $customer_view_link = '/customer_details/' . $cust_id;
                $view_link = '<a target="_BLANK" href="' . $customer_url . '"> View </a>';
                $table_data[$i][0] = $i + 1;
                $table_data[$i][1] = $customer;
                $table_data[$i][2] = $cust_single['Branch']['branch_name'];
                $table_data[$i][3] = $cust_single['Kendra']['kendra_name'];
                $table_data[$i][4] = ($loan_paid[0]['Loan']['loan_principal'] > 0) ? $Number->currency($loan_paid[0]['Loan']['loan_principal'], '', array('places' => 0)) : 'No Current loan';
                $table_data[$i][5] = $loan_paid[0][0]['instalment_paid_no'];
                $table_data[$i][6] = $loan_paid[0][0]['last_paid_date'];
                $table_data[$i][7] = $loan_overdue[0][0]['overdue_no'];
                $table_data[$i][8] = $view_link;
                $table_data[$i][9] = $edit_link;
                $i = $i + 1;
            }
        }
        $summary_data['table_val'] = $this->prepare_json($table_data);
        ;
        $this->set('customers_data', $summary_data);
        $this->layout = 'panel_layout';
    }
    // List of all customers of a organization function end

    // List of all customers of a organization function start
    public function customer_list_old()
    {
        $this->set('title', 'Customer List');
        // Import a number helper
        App::import('Helper', 'Number');
        $Number = new NumberHelper(new View(null));
        $organisation_id = $this->Auth->User('organization_id');
        $branches_data = $this->Branch->find('list', array(
            'fields' => array('Branch.id', 'Branch.branch_name'),
            'conditions' => array('Branch.status' => 1, ),
            'recursive' => 1));
        $this->set('branches_data', $branches_data);
        $this->Customer->unBindModel(array(
            'belongsTo' => array(
                'Organization',
                'Region',
                'Branch',
                'Market',
                'User',

                ),

            'hasMany' => array(
                'Saving',
                'Loan',
                'Idproof'),
            ));
        $customers_data = $this->Customer->find('all', array('conditions' => array('Customer.status' =>
                    1)));
        // Calculate last updated date from the database
        $max_date = $this->LoanTransaction->find('all', array(
            'fields' => array('MAX(LoanTransaction.insta_paid_on) as max_date'),
            'conditions' => array(

                'LoanTransaction.insta_paid_on !=' => '0000-00-00',
                'Loan.loan_status_id' => 3,
                'Loan.organization_id' => $this->Auth->user('organization_id')),
            'joins' => array(array(
                    'table' => 'loans',
                    'alias' => 'Loan',
                    'type' => 'inner',
                    'foreignKey' => true,
                    'conditions' => array('Loan.id = LoanTransaction.loan_id'))),
            ));
        $final_date = $max_date[0][0]['max_date'];
        $cust_data = array();
        $table_data = array();
        if (!empty($customers_data))
        {
            foreach ($customers_data as $k2 => $customer)
            {
                $loan_count = $this->Loan->find('first', array(
                    'fields' => array('Loan.loan_principal'),
                    'conditions' => array(
                        'Loan.loan_status_id' => 3,
                        'Loan.customer_id' => $customer['Customer']['id'],
                        ),
                    'recursive' => -1));
                $table_data[$k2][0] = $k2 + 1;
                $table_data[$k2][1] = $customer['Customer']['cust_fname'] . ' ' . $customer['Customer']['cust_lname'];
                $table_data[$k2][2] = $customer['Branch']['branch_name'];
                $table_data[$k2][3] = $customer['Market']['market_name'];
                if (!empty($loan_count['Loan']['loan_principal']))
                {
                    $loan_overdue = $this->LoanTransaction->find('all', array(
                        'fields' => array('COUNT(LoanTransaction.id) as overdue_no'),
                        'conditions' => array(
                            'LoanTransaction.insta_due_on <=' => $final_date,
                            'Loan.loan_status_id' => 3,
                            'Loan.customer_id' => $customer['Customer']['id'],
                            'LoanTransaction.insta_principal_paid' => 0),
                        'joins' => array(array(
                                'table' => 'loans',
                                'alias' => 'Loan',
                                'type' => 'inner',
                                'foreignKey' => true,
                                'conditions' => array('Loan.id = LoanTransaction.loan_id')))));
                    $loan_paid = $this->LoanTransaction->find('all', array(
                        'fields' => array(
                            'COUNT(LoanTransaction.id) as instalment_paid_no',
                            'MAX(LoanTransaction.insta_paid_on) as last_paid_date',
                            'Loan.*'),
                        'conditions' => array(
                            'LoanTransaction.insta_due_on <=' => $final_date,
                            'Loan.loan_status_id' => 3,
                            'Loan.customer_id' => $customer['Customer']['id'],
                            'LoanTransaction.insta_principal_paid !=' => 0),
                        'joins' => array(array(
                                'table' => 'loans',
                                'alias' => 'Loan',
                                'type' => 'inner',
                                'foreignKey' => true,
                                'conditions' => array('Loan.id = LoanTransaction.loan_id')))));
                    $table_data[$k2][4] = (!empty($loan_count['Loan']['loan_principal'])) ? $Number->
                        currency($loan_count['Loan']['loan_principal'], '', array('places' => 0)) :
                        'No Active loan';
                    $table_data[$k2][5] = $loan_paid[0][0]['instalment_paid_no'];
                    $table_data[$k2][6] = $loan_paid[0][0]['last_paid_date'];
                    $table_data[$k2][7] = $loan_overdue[0][0]['overdue_no'];
                } else
                {
                    $table_data[$k2][4] = 'No Active loan';
                    $table_data[$k2][5] = '0';
                    $table_data[$k2][6] = '-';
                    $table_data[$k2][7] = '0';
                }
                $customer_link = $this->base . '/customer_edit/' . $customer['Customer']['id'];
                $edit_link = '<a href="' . $customer_link . '"> Edit </a>';
                $customer_url = $this->base . '/customer_details/' . $customer['Customer']['id'];
                $customer_view_link = '/customer_details/' . $customer['Customer']['id'];
                $view_link = '<a target="_BLANK" href="' . $customer_url . '"> View </a>';
                $table_data[$k2][8] = $view_link;
                $table_data[$k2][9] = $edit_link;
            }
        }
        $summary_data['table_val'] = $this->prepare_json($table_data);
        ;
        $this->set('customers_data', $summary_data);
        $this->layout = 'panel_layout';
    }
    // List of all customers of a organization function end

    // List of all active customers of a organization function start
    public function active_customer_list()
    {
        $this->set('title', 'Active Customer List');
        $organisation_id = $this->Auth->User('organization_id');
        $branches_data = $this->Branch->find('all', array('conditions' => array('Branch.status' =>
                    1, ), 'recursive' => 1));
        $this->set('branches_data', $branches_data);
        $customers_data = $this->Customer->find('all', array('conditions' => array('Customer.status' =>
                    1, 'Loan.loan_status_id' => 3), 'joins' => array(array(
                    'table' => 'loans',
                    'alias' => 'Loan',
                    'type' => 'inner',
                    'foreignKey' => true,
                    'conditions' => array('Customer.id = Loan.customer_id', 'Loan.loan_status_id=3')))));

        $this->set('customers_data', $customers_data);
        $this->layout = 'panel_layout';
    }
    // List of all active customers of a organization function end

    // List of all pending customers of a organization function start
    public function pending_customer_list()
    {
        $this->set('title', 'Inactive Customer List');
        $organisation_id = $this->Auth->User('organization_id');
        $branches_data = $this->Branch->find('all', array('conditions' => array('Branch.status' =>
                    1, ), 'recursive' => 1));
        $this->set('branches_data', $branches_data);
        $customers_data = $this->Customer->find('all', array('conditions' => array('Customer.status' =>
                    1, 'Loan.loan_status_id' => 1), 'joins' => array(array(
                    'table' => 'loans',
                    'alias' => 'Loan',
                    'type' => 'inner',
                    'foreignKey' => true,
                    'conditions' => array('Customer.id = Loan.customer_id', 'Loan.loan_status_id=1')))));
        $this->set('customers_data', $customers_data);
        $this->layout = 'panel_layout';
    }
    // List of all pending customers of a organization function end

    // Create new customer function start
    public function add()
    {
        $this->layout = 'panel_layout';
        $this->set('title', 'Create Customer');
        $org_data = $this->get_organization_settings_fees($this->Auth->user('organization_id'));
        $user_data = $this->User->find('all', array('conditions' => array('User.id' => $this->
                    Auth->user('id'))));

        if ($this->Auth->user('user_type_id') == 2)
        {
            $branch_list = $this->Branch->find('list', array('fields' => array('id',
                        'branch_name'), 'conditions' => array('Branch.organization_id' => $this->Auth->
                        user('organization_id'))));
            $kendra_list = $this->Kendra->find('list', array('fields' => array('id',
                        'kendra_name'), 'conditions' => array('Kendra.organization_id' => $this->Auth->
                        user('organization_id'))));
            $market_list = $this->Market->find('list', array('fields' => array('id',
                        'market_name'), 'conditions' => array('Market.organization_id' => $this->Auth->
                        user('organization_id'))));
        } else
        {
            $branch_list = $this->Branch->find('list', array('fields' => array('id',
                        'branch_name'), 'conditions' => array('Branch.user_id' => $this->Auth->user('id'))));
            $kendra_list = $this->Kendra->find('list', array('fields' => array('id',
                        'kendra_name'), 'conditions' => array('Kendra.user_id' => $this->Auth->user('id'))));
            $market_list = $this->Market->find('list', array('fields' => array('id',
                        'market_name'), 'conditions' => array('Market.user_id' => $this->Auth->user('id'))));
        }

        $this->set('org_data', $org_data);
        $this->set('user_data', $user_data);
        $this->set('branch_list', $branch_list);

        $this->set('kendra_list', $kendra_list);

        $this->set('market_list', $market_list);
        // ADD data to the database
        if ($this->request->is('post'))
        {
            $kendra_id = $this->request->data['Customer']['kendra_id'];
            $kendra_full_data = $this->Kendra->find('first', array('conditions' => array('Kendra.id' =>
                        $kendra_id)));
            $this->request->data['Customer']['user_id'] = $this->Auth->user('id');
            $this->request->data['Customer']['created_on'] = date("Y-m-d");
            $this->request->data['Customer']['modified_on'] = date("Y-m-d");
            $this->request->data['Customer']['branch_id'] = $kendra_full_data['Branch']['id'];
            $this->request->data['Customer']['region_id'] = $kendra_full_data['Region']['id'];
            $this->request->data['Customer']['organization_id'] = $kendra_full_data['Organization']['id'];
            $this->request->data['Customer']['cust_sex'] = 'Female';
            $this->Customer->create();
            if ($this->Customer->save($this->request->data))
            {
                $last_insert_Customer = $this->Customer->getLastInsertId();
                $this->request->data['Idproof']['customer_id'] = $last_insert_Customer;
                $this->Idproof->save($this->request->data);
                $this->request->data['Saving']['customer_id'] = $last_insert_Customer;
                $this->request->data['Saving']['organization_id'] = $kendra_full_data['Organization']['id'];
                $this->request->data['Saving']['branch_id'] = $kendra_full_data['Branch']['id'];
                $this->request->data['Saving']['currency_id'] = 1;
                $this->request->data['Saving']['kendra_id'] = $kendra_id;
                $this->request->data['Saving']['savings_date'] = date("Y-m-d");
                $this->request->data['Saving']['created_on'] = date("Y-m-d");
                $this->request->data['Saving']['modified_on'] = date("Y-m-d");
                $this->request->data['Saving']['user_id'] = $this->Auth->user('id');
                $this->Saving->save($this->request->data);
                $this->Session->setFlash(__('The user has been created'));
                $this->redirect(array('action' => 'add'));
            } else
            {
                $this->Session->setFlash(__('The user could not be created. Please, try again.'));
            }
        }
    }
    // Create new customer function end

    // Edit a customer function start
    public function edit_old($cid = '')
    {
        $this->layout = 'panel_layout';
        $this->set('title', 'Update Customer');
        $identity_type = $this->id_proof_name();
        $this->set('identity_type', $identity_type);
        $relationship_type = $this->relationship_type();
        $this->set('relationship_type', $relationship_type);
        $user_data = $this->User->find('all', array('conditions' => array('User.id' => $this->
                    Auth->user('id'))));
        $branch_list = $this->Branch->find('list', array('fields' => array('id',
                    'branch_name'), 'conditions' => array('Branch.organization_id' => $this->Auth->
                    user('organization_id'))));
        $kendra_list = $this->Kendra->find('list', array('fields' => array('id',
                    'kendra_name'), 'conditions' => array('organization_id' => $this->Auth->user('organization_id'))));
        $this->set('user_data', $user_data);
        $this->set('branch_list', $branch_list);
        $this->set('kendra_list', $kendra_list);
        $cust_data = $this->Customer->findById($cid);
        $this->set('cust_data', $cust_data);
        if ($this->request->is(array('post', 'put')))
        {
            $this->request->data['Customer']['modified_on'] = date("Y-m-d H:i:s");
            $this->Customer->id = $cid;
            if ($this->Customer->save($this->request->data))
            {
                $file_path = $this->Session->read('Idproof.filepath');
                if (!empty($file_path))
                {
                    $this->Idproof->updateAll(array(
                        'Idproof.id_proof_type' => "'" . $this->request->data['Idproof']['id_proof_type'] .
                            "'",
                        'Idproof.id_proof_no' => "'" . $this->request->data['Idproof']['id_proof_no'] .
                            "'",
                        'Idproof.id_proof_pic' => "'" . $file_path . "'"), array('Idproof.customer_id' =>
                            $cid));
                    $this->Session->delete('file_path');
                } else
                {
                    $this->Idproof->updateAll(array(
                        'Idproof.id_proof_type' => "'" . $this->request->data['Idproof']['id_proof_type'] .
                            "'",
                        'Idproof.id_proof_no' => "'" . $this->request->data['Idproof']['id_proof_no'] .
                            "'",
                        'Idproof.id_proof_pic' => "'" . $cust_data['Idproof'][0]['id_proof_pic'] . "'"),
                        array('Idproof.customer_id' => $cid));
                }
                $this->Session->setFlash(__('Your Customer has been updated.'));
                return $this->redirect('/customer_edit/' . $cid);
            }
            $this->Session->setFlash(__('Unable to update your Customer.'));
        }
        if (!$this->request->data)
        {
            $this->request->data = $cust_data;
        }
    }
    // Edit a customer function end

    // Customer details function start
    public function customer_details($cust_id = '')
    {
        $this->layout = 'panel_layout';
        $this->set('title', 'Customer Details');
        $org_data = $this->get_organization_settings_fees($this->Auth->user('organization_id'));
        
        $cust_data = $this->Customer->find('first', array('conditions' => array('Customer.id' =>
                    $cust_id)));
        
        
        // Count Loan Status if there is any active loan or not
        $loan_active = $this->Loan->find('count', array('conditions' => array('Loan.customer_id' =>
                    $cust_id, 'Loan.loan_status_id' => 3)));
        $this->set('loan_active', $loan_active);
        // Calculate loan summery of a customer
        $loan_summery = $this->customer_loan_summary($cust_id);
        $saving_summery = $this->customer_saving_summary($cust_id);
        $status_array = $this->status_name_array();
        // Order summery of a customer
        
        $this->set('cust_data', $cust_data);
        $this->set('loan_summary', $loan_summery);
        $saving_summery = !empty($saving_summery[0])?$saving_summery[0]:array();
        $this->set('saving_summery', $saving_summery);
        
        $loan_trans= $this->get_loan_transaction_data($loan_summery[0]['account_id'],5,'desc');
        $this->set('loan_trans', $loan_trans);
        //pr($loan_trans);die;
    }
    // Customer Details function end

    // Security deposit return of a customer function start
    public function security_deposite_return($kid = '', $cid = '')
    {
        $this->layout = 'panel_layout';
        $this->set('title', 'Security Deposit Return');
        $this->set('kendra_id', $kid);
        $this->set('customer_id', $cid);
        if ($this->Auth->user('user_type_id') == 2)
        {
            $kendra_list = $this->Kendra->find('list', array('fields' => array('id',
                        'kendra_name'), 'conditions' => array('Kendra.organization_id' => $this->Auth->
                        user('organization_id'))));
            if ($kid == '')
                $loans_list = $this->Loan->find('all', array('conditions' => array(
                        'Loan.loan_status_id' => 6,
                        'Loan.is_security_fee_returned' => 0,
                        'Loan.organization_id' => $this->Auth->user('organization_id'))));
            else
                $loans_list = $this->Loan->find('all', array('conditions' => array(
                        'Loan.loan_status_id' => 6,
                        'Loan.is_security_fee_returned' => 0,
                        'Loan.kendra_id' => $kid)));
        }
        if ($this->Auth->user('user_type_id') == 5)
        {
            $kendra_list = $this->Kendra->find('list', array('fields' => array('id',
                        'kendra_name'), 'conditions' => array('Kendra.user_id' => $this->Auth->user('id'))));
            if ($kid == '')
                $loans_list = $this->Loan->find('all', array('conditions' => array(
                        'Loan.loan_status_id' => 6,
                        'Loan.is_security_fee_returned' => 0,
                        'Loan.user_id' => $this->Auth->user('id'))));
            else
                $loans_list = $this->Loan->find('all', array('conditions' => array(
                        'Loan.loan_status_id' => 6,
                        'Loan.is_security_fee_returned' => 0,
                        'Loan.kendra_id' => $kid)));
        }
        $this->set('kendra_list', $kendra_list);
        $cust_list = array();
        $fees_arr = array();
        foreach ($loans_list as $loan_row)
        {
            $cust_list[$loan_row['Customer']['id']] = $loan_row['Customer']['cust_fname'] .
                ' ' . $loan_row['Customer']['cust_lname'];
            $fees_arr[$loan_row['Customer']['id']] = $loan_row['Loan']['security_fee'];
        }
        $this->set('cust_list', $cust_list);
        $this->set('fees_arr', $fees_arr);
        if ($this->request->is('post'))
        {
            $this->Loan->id = $this->request->data['Loan']['loan_id'];
            $this->Loan->saveField('is_security_fee_returned', 1);
            $this->Session->setFlash('Security fees refunded');
            $this->redirect('/security_deposite_return/');
        }

    }
    // Security deposit return function end


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Branch loan details of a branch function start
    public function branch_loan_details($branch_id = '')
    {
        $this->layout = 'panel_layout';
        $this->set('title', 'Branch Loan Details');
        // Import Number helper
        App::import('Helper', 'Number');
        $Number = new NumberHelper(new View(null));
        $summary_data = array();
        $user_id = $this->Auth->user('id');
        $user_type_id = $this->Auth->user('user_type_id');
        $branch_list = $this->Branch->find('list', array('fields' => array('id',
                    'branch_name'), 'conditions' => array('Branch.organization_id' => $this->Auth->
                    user('organization_id'))));
        $this->set('branch_list', $branch_list);
        if ($branch_id == '')
        {
            $branch_id = key($branch_list);
            $this->request->data['User']['branch_id'] = $branch_id;
        }

        $branch_data = $this->Branch->find('first', array('conditions' => array('Branch.id' =>
                    $branch_id)));
        $loanArray = array();
        if (!empty($branch_data['Branch']))
        {
            $organizationArray = $branch_data['Organization'];
            $regionArray = $branch_data['Region'];
            $branchArray = $branch_data['Branch'];
            $branchManagerArray = $branch_data['User'];
        }
        $start_date = date("Y-m-d", strtotime("-7 days"));
        // Calculate last updated date in the database
        $max_date = $this->LoanTransaction->find('all', array(
            'fields' => array('MAX(LoanTransaction.insta_paid_on) as max_date'),
            'conditions' => array(

                'LoanTransaction.insta_paid_on !=' => '0000-00-00',
                'Loan.loan_status_id' => 3,
                'Loan.branch_id' => $branch_id),
            'joins' => array(array(
                    'table' => 'loans',
                    'alias' => 'Loan',
                    'type' => 'inner',
                    'foreignKey' => true,
                    'conditions' => array('Loan.id = LoanTransaction.loan_id'))),
            ));
        $final_date = $max_date[0][0]['max_date'];
        $this->set('update_on', $final_date);
        $start_date = date("Y-m-d", strtotime("$final_date -7 days"));
        $end_date = $final_date;
        $option_val = 1;
        $option_name = 'Current week';
        $this->set('option_val', $option_val);
        $select_date = date("m-d-Y", strtotime($final_date));
        $send_date['start_date'] = $select_date;
        $send_date['end_date'] = $select_date;
        // User Values after post
        if ($this->request->is('post'))
        {
            $branch_id = $this->request->data['User']['branch_id'];
            $option_val = $this->request->data['User']['selectdate'];
            $this->set('option_val', $option_val);
            if ($option_val == 1)
            {
                $start_date = date("Y-m-d", strtotime("$final_date -7 days"));
                $end_date = $final_date;
                $option_name = 'Current week';
            }
            if ($option_val == 2)
            {
                $start_date = date("Y-m-d", strtotime("$final_date -14 days"));
                $end_date = date("Y-m-d", strtotime("$final_date -7 days"));
                $option_name = 'Last week';
            }
            if ($option_val == 3)
            {
                $start_date = date("Y-m-d", strtotime("$final_date -30 days"));
                $end_date = $final_date;
                $option_name = 'Current Month';
            }
            if ($option_val == 4)
            {
                $postarray = $this->request->data;
                $daterange = explode('-', $this->request->data['datefilter']);
                $postarray['start_date'] = trim($daterange[0]);
                $postarray['end_date'] = trim($daterange[1]);
                $send_date['start_date'] = $postarray['start_date'];
                $send_date['end_date'] = $postarray['end_date'];
                $this->set('send_date', $send_date);
                $start_date = date("Y-m-d", strtotime($postarray['start_date']));
                $end_date = date("Y-m-d", strtotime($postarray['end_date']));
                $option_name = 'Choose Date';
            }
        }
        $send_date['date_diff'] = $this->date_differ($start_date, $end_date);
        $send_date['option_val'] = $option_val;
        $send_date['option_name'] = $option_name;
        $send_date['start_date'] = $start_date;
        $send_date['end_date'] = $end_date;
        $this->set('send_date', $send_date);

        $loan_collection = $this->branch_overdue_details($branch_id, $start_date, $end_date);

        $due_loan = $this->LoanTransaction->find('all', array(
            'fields' => array(
                'COUNT(distinct(LoanTransaction.loan_id)) as no_of_loan',
                'SUM(LoanTransaction.insta_principal_due) as due_balance',
                '(SUM(LoanTransaction.insta_principal_paid)+SUM(LoanTransaction.insta_interest_paid)+SUM(LoanTransaction.overdue_paid)+SUM(LoanTransaction.prepayment)) as paid_balance',
                ),
            'conditions' => array('Loan.loan_status_id' => 3, 'Loan.branch_id' => $branch_id),
            'joins' => array(array(
                    'table' => 'loans',
                    'alias' => 'Loan',
                    'type' => 'inner',
                    'foreignKey' => true,
                    'conditions' => array('Loan.id = LoanTransaction.loan_id'))),
            ));
        
                
        $new_loan_application = $this->Loan->find('all', array('fields' => array('COUNT(Loan.id) as no_of_loan',
                    'SUM(Loan.loan_principal) as total_loan_principal'), 'conditions' => array(
                'Loan.branch_id' => $branch_id,
                'Loan.loan_status_id' => 1,
                'Loan.loan_date BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'',
                )));
        $approved_loan = $this->Loan->find('all', array('fields' => array('COUNT(Loan.id) as no_of_loan',
                    'SUM(Loan.loan_principal) as total_loan_principal'), 'conditions' => array(
                'Loan.branch_id' => $branch_id,
                'Loan.loan_status_id' => 2,
                'Date(Loan.approved_date) BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'',
                )));
        $disbursed_loan = $this->Loan->find('all', array('fields' => array('COUNT(Loan.id) as no_of_loan',
                    'SUM(Loan.loan_principal) as total_loan_principal'), 'conditions' => array(
                'Loan.branch_id' => $branch_id,
                'Loan.loan_status_id' => 3,
                'Loan.loan_dateout BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'',
                )));
        $closed_loan = $this->Loan->find('all', array('fields' => array('COUNT(Loan.id) as no_of_loan',
                    'SUM(Loan.loan_principal) as total_loan_principal'), 'conditions' => array(
                'Loan.branch_id' => $branch_id,
                'Loan.loan_status_id' => 6,
                'Date(Loan.closing_date) BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'',
                )));
        $total_loan_ever = $this->Loan->find('all', array('fields' => array('COUNT(Loan.id) as no_of_loan',
                    'SUM(Loan.loan_principal) as total_loan_principal'), 'conditions' => array('Loan.branch_id' =>
                    $branch_id, array('OR' => array(array('Loan.loan_status_id' => 3), array('Loan.loan_status_id' =>
                                6))))));

        $total_saving = $this->Saving->find('all', array('fields' => array('COUNT(Saving.id) as no_of_saving',
                    'SUM(Saving.current_balance) as total_saving_balance'), 'conditions' => array(
                'Saving.branch_id' => $branch_id,
                'Saving.status' => 1,
                )));

        $summary_data['realize_amt'] = $loan_collection[0][0]['insta_realise'];
        $summary_data['realizable_amt'] = $loan_collection[0][0]['insta_realisable'];
        $summary_data['loan_amount_in_mkt'] = $due_loan[0][0]['due_balance'] - $due_loan[0][0]['paid_balance']- $loan_collection[0][0]['realizable_interest_amount'];
        $summary_data['total_loan_in_mkt'] = $due_loan[0][0]['no_of_loan'];
        $summary_data['overdue_amount'] = $loan_collection[0][0]['insta_realisable'] - $loan_collection[0][0]['insta_realise'];
        $summary_data['percentage_paid'] = ($summary_data['realizable_amt'] == 0) ? 100 :
            round(($summary_data['realize_amt'] / $summary_data['realizable_amt'] * 100), 2);
        $summary_data['new_loan_application'] = $new_loan_application[0][0];
        $summary_data['approved_loan'] = $approved_loan[0][0];
        $summary_data['disbursed_loan'] = $disbursed_loan[0][0];
        $summary_data['closed_loan'] = $closed_loan[0][0];
        $summary_data['total_loan_ever'] = $total_loan_ever[0][0];
        $summary_data['total_saving'] = $total_saving[0][0];
        $customer_no = $this->Customer->find('count', array('conditions' => array('Customer.branch_id' =>
                    $branch_id, 'Customer.status' => 1)));

        $branchLoanSummary['organization_details'] = $organizationArray;
        $branchLoanSummary['region_details'] = $regionArray;
        $branchLoanSummary['branch_manager_details'] = $branchManagerArray;
        $branchLoanSummary['branch_details'] = $branchArray;
        $branchLoanSummary['total_group'] = $this->Kendra->find('count', array('conditions' =>
                array('Kendra.branch_id' => $branch_id, 'Kendra.status' => 1)));
        $branchLoanSummary['total_co'] = $this->User->find('count', array('conditions' =>
                array(
                'User.branch_id' => $branch_id,
                'User.status' => 1,
                'User.user_type_id' => 5)));
        $branchLoanSummary['total_customer'] = $customer_no;
        $this->set('branchLoanSummary', $branchLoanSummary);


        $this->set('summary_data', $summary_data);
    }
    //Branch loan details function end

    // Saving details function start
    public function saving_details($saving_id)
    {
        $this->layout = 'panel_layout';
        $this->set('title', 'Saving Details');
        $saving_data = $this->Saving->find('first', array('conditions' => array('Saving.id' =>
                    $saving_id)));
        $this->set('saving_data', $saving_data);
    }
    // Saving details function end

    // Loan Officer Wise Loan Collection START
    public function loan_officer_details($user_id = '')
    {
        $this->layout = 'panel_layout';
        $this->set('title', 'Loan Officer Details');
        if ($this->request->is('post'))
        {
            $user_id = $this->request->data['Customer']['branch_id'];
        }
        $lo_list = $this->User->find('list', array('fields' => array('id', 'first_name'),
                'conditions' => array('User.organization_id' => $this->Auth->user('organization_id'),
                    'User.user_type_id' => 5)));
        $this->set('lo_list', $lo_list);
        $branchLoanSummary = array();
        $loanOfficerSummary = array();
        $loanArray = array();
        if ($user_id != '')
        {
            $user_data = $this->User->find('first', array('conditions' => array('User.id' =>
                        $user_id)));
            if (!empty($user_data['Kendra']))
            {
                $organizationArray = $user_data['Organization'];
                $branchArray = $user_data['Branch'];
                $userArray = $user_data['User'];
                $loanArray = $user_data['Loan'];
                $kendraArray = $user_data['Kendra'];
                $customerArray = $user_data['Customer'];
            }
            if (!empty($user_data['Loan']))
            {
                $loanDetailsArray = array();
                foreach ($loanArray as $kbranch => $branchloandetails)
                {
                    if (!empty($loanArray))
                    {
                        $loan_id = $branchloandetails['id'];
                        $loanDetailsArray[] = $this->loan_summary($loan_id);
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
                foreach ($loanDetailsArray as $kloande => $loandetails)
                {
                    if (!empty($loanDetailsArray))
                    {
                        if ($loandetails['last_paid_date'] != '')
                        {
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
                $loanOfficerSummary['percentage_paid'] = round(($percentage_paid / $number_of_loop),2);
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
            } else
            {
                $loanOfficerSummary['data_status'] = 0;
            }
        } else
        {
            $loanOfficerSummary['data_status'] = 0;
        }
        $this->set('loan_officer_summery', $loanOfficerSummary);
    }
    // Loan Officer Wise Loan Collection END

    // Delete a single customer data start
    public function delete_single_customer()
    {
        if ($this->request->is('post'))
        {
            $customer_id = $this->request->data['customer_id'];
            $this->delete_customer($customer_id, $cascade = true, $callbacks = false);
            $this->redirect(array('action' => 'customer_list'));
        }
    }
    // Delete a single customer data end
    
    

}
// Customer Controller END here


?>