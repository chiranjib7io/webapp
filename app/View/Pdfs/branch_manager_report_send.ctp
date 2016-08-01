<?php


            $html = '';
            
            
            
            $html .= '<section class="content" style="
    min-height: 250px;
    padding: 15px;
    margin-right: auto;
    margin-left: auto;
    padding-left: 15px;
    padding-right: 15px;
    display: block;
    font-family: \'Source Sans Pro\',\'Helvetica Neue\',Helvetica,Arial,sans-serif;
">
              <div class="row" style="margin-right: -15px; margin-left: -15px;">
             
                <div class="col-xs-12" style="float: left;position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                	<div class="box box-primary" style="float:left;border-top-color: #3c8dbc;position: relative;
    border-radius: 3px;
    background: #ffffff;
    border-top: 3px solid #d2d6de;
    margin-bottom: 20px;
    width: 100%;
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);">';
						
						
            if($branch_data['Branch']['id']!= 0) {
						
						$html .= '<div class="box-header with-border" style="border-top: none;
    padding-top: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #f4f4f4;
    color: #444;
    display: block;
    padding: 10px;
    position: relative;">
                          <h3 class="box-title" style="display: inline-block;
    font-size: 18px;
    margin: 0;
    line-height: 1;">'.$branch_data['Branch']['branch_name'].' ['.date("d-M-Y").']</h3>
                        </div>
                        <div class="box-body" style="border-top-left-radius: 0;
    border-top-right-radius: 0;
    border-bottom-right-radius: 3px;
    border-bottom-left-radius: 3px;
    padding: 10px;">
                            <div class="profile_info" style="width:100%; padding-bottom:20px;float: left;
    position: relative;
    padding: 0 15px 20px;">
                                <ul style="padding: 0;margin: 0;">
                                    <li style="display: block;
    float: left;
    width: 34%;
    margin: 0;
    padding: 0;
    list-style: none;
    line-height: 30px;"><strong>Branch Manager Name:</strong> '.$branch_data['Branch']['manager_name'].'</li>
                                    
                                    <li style="display: block;
    float: left;
    width: 34%;
    margin: 0;
    padding: 0;
    list-style: none;
    line-height: 30px;"><strong>Email ID:</strong> '.$branch_data['Branch']['contact_email'].'</li>
                                    <li style="display: block;
    float: left;
    width: 34%;
    margin: 0;
    padding: 0;
    list-style: none;
    line-height: 30px;"><strong>Organisation name:</strong> '.$branch_data['Branch']['organization_name'].'</li>
                                    <li style="display: block;
    float: left;
    width: 34%;
    margin: 0;
    padding: 0;
    list-style: none;
    line-height: 30px;"><strong>No. of Kendra:</strong> '.$branch_data['Branch']['total_kendra'].'</li>
                                    <li style="display: block;
    float: left;
    width: 34%;
    margin: 0;
    padding: 0;
    list-style: none;
    line-height: 30px;"><strong>No. of Customer:</strong> '.$branch_data['Branch']['total_customer'].'</li>
                                    <li style="display: block;
    float: left;
    width: 34%;
    margin: 0;
    padding: 0;
    list-style: none;
    line-height: 30px;"><strong>Total Loan amount:</strong> '.$branch_data['Branch']['total_loan'].'</li>
                                    <li style="display: block;
    float: left;
    width: 34%;
    margin: 0;
    padding: 0;
    list-style: none;
    line-height: 30px;"><strong>Loan in Market:</strong> '.$branch_data['Branch']['total_loan_in_market'].'</li>
                                    <li style="display: block;
    float: left;
    width: 34%;
    margin: 0;
    padding: 0;
    list-style: none;
    line-height: 30px;"><strong>Total Overdue:</strong> '.$branch_data['Branch']['total_overdue'].'</li>
                                    <li style="display: block;
    float: left;
    width: 34%;
    margin: 0;
    padding: 0;
    list-style: none;
    line-height: 30px;"><strong>Percent paid:</strong> '.$branch_data['Branch']['percent_paid'].'%</li>
                                    <li style="display: block;
    float: left;
    width: 34%;
    margin: 0;
    padding: 0;
    list-style: none;
    line-height: 30px;"><strong>Address:</strong> '.$branch_data['Branch']['address'].', '.$branch_data['Branch']['city'].', '.$branch_data['Branch']['state'].', '.$branch_data['Branch']['zip'].'</li>
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>';
              	//customer info ends
                  
                  $html .= '<div class="col-xs-12" style="float: left;position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                      <div class="box box-primary" style="float:left;border-top-color: #3c8dbc;position: relative;
    border-radius: 3px;
    background: #ffffff;
    border-top: 3px solid #d2d6de;
    margin-bottom: 20px;
    width: 100%;
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);">
                        <div class="box-header with-border" style="border-top: none;
    padding-top: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #f4f4f4;
    color: #444;
    display: block;
    padding: 10px;
    position: relative;">
                          <h3 class="box-title" style="display: inline-block;
    font-size: 18px;
    margin: 0;
    line-height: 1;">Kendra Summary</h3>
                        </div>
                        <div class="box-body" style="padding-bottom:20px; border-top-left-radius: 0;
    border-top-right-radius: 0;
    border-bottom-right-radius: 3px;
    border-bottom-left-radius: 3px;">
                        	<div class="table-responsive">
                                <table id="example2" class="table table-bordered table-hover kendra_list" style="border: 1px solid #f4f4f4;    width: 100%;
    max-width: 100%;
    margin-bottom: 20px;background-color: transparent;">
                                    <thead style="background: #3c8dbc;
    color: #fff;">
                                        <tr>
                                            <th>#</th>
                                            <th>Kendra Name</th>
                                            <th>Total Loan</th>
                                            <th>Loan in Market</th>
                                            <th>Realizable</th>
                                            <th>Realized</th>
                                            <th>Percent Paid</th>
                                            <th>Total Customer</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>';

                                    $i=1;
                                    foreach($branch_data['Kendra'] as $pay_row)
                                    {
                                        
                                        
                                        $html .= '<tr>
                                            <td>'.$i.'</td>
                                            <td>'.$pay_row['Kendra']['kendra_name'].'</td>
                                            <td>'.$pay_row['Kendra']['total_loan'].'</td>
                                            <td>'.$pay_row['Kendra']['total_loan_in_market'].'</td>
                                            <td>'.$pay_row['Kendra']['total_realisable'].'</td>
                                            <td>'.$pay_row['Kendra']['total_realise'].'</td>
                                            <td>'.$pay_row['Kendra']['percent_paid'].' %</td>
                                            <td>'.$pay_row['Kendra']['total_customer'].'</td>
                                        </tr>';

                                    $i++;
                                    }

                                        
                                    $html .= '</tbody>
                                </table>
                            </div>
                        </div>';

							} else { 
							 $html .= '<div class="box-body">
								No Data Found
							 </div>';
							
							}  
                    $html .= '</div>
                  </div>
			  </div>';
	  

echo $html;	

?>						