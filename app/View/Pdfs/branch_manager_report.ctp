<?php
App::import('Vendor','xtcpdf');
 
$pdf = new XTCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
 
$pdf->AddPage();

$html = '';
            
            
if($branch_data['Branch']['id']!= 0) {
    
$html = '<table align="left" style="width:80%;">
    	<tr>
        	<td>
            	<table style="width:100%">
                	<tr>
                    	<td colspan="2" align="left">
                        	<h2>'.$branch_data['Branch']['branch_name'].' ['.date("d-M-Y").']</h2><hr>
                        </td>
                    </tr>
                    <tr>
                    	<td style="width:50%;">
                        	<table>
                            	<tr>
                                	<td><strong>Branch Manager Name:</strong></td>
                                    <td>'.$branch_data['Branch']['manager_name'].'</td>
                                </tr>
                            </table>
                        </td>
                    	<td style="width:50%;">
                        	<table>
                            	<tr>
                                	<td><strong>Email ID :</strong></td>
                                    <td>'.$branch_data['Branch']['contact_email'].'</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                    	<td style="width:50%;">
                        	<table>
                            	<tr>
                                	<td><strong>Organisation Name :</strong></td>
                                    <td>'.$branch_data['Branch']['organization_name'].'</td>
                                </tr>
                            </table>
                        </td>
                    	<td style="width:50%;">
                        	<table>
                            	<tr>
                                	<td><strong>No. of Kendra :</strong></td>
                                    <td>'.$branch_data['Branch']['total_kendra'].'</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                    	<td style="width:50%;">
                        	<table>
                            	<tr>
                                	<td><strong>No. of Customer :</strong></td>
                                    <td>'.$branch_data['Branch']['total_customer'].'</td>
                                </tr>
                            </table>
                        </td>
                    	<td style="width:50%;">
                        	<table>
                            	<tr>
                                	<td><strong>Total Loan Amount :</strong></td>
                                    <td>'.$branch_data['Branch']['total_loan'].'</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                    	<td style="width:50%;">
                        	<table>
                            	<tr>
                                	<td><strong>Loan in Market:</strong></td>
                                    <td>'.$branch_data['Branch']['total_loan_in_market'].'</td>
                                </tr>
                            </table>
                        </td>
                    	<td style="width:50%;">
                        	<table>
                            	<tr>
                                	<td><strong>Total Overdue :</strong></td>
                                    <td>'.$branch_data['Branch']['total_overdue'].'</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                    	<td style="width:50%;">
                        	<table>
                            	<tr>
                                	<td><strong>Percent Paid :</strong></td>
                                    <td>'.$branch_data['Branch']['percent_paid'].'%</td>
                                </tr>
                            </table>
                        </td>
                    	<td style="width:50%;">
                        	<table>
                            	<tr>
                                	<td><strong>Address :</strong></td>
                                    <td>'.$branch_data['Branch']['address'].', '.$branch_data['Branch']['city'].', '.$branch_data['Branch']['state'].', '.$branch_data['Branch']['zip'].'</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    	<tr>
        	<td> 
            	<table style="width:100%;" cellspacing="0" cellpadding="0">
                	<tr>
                    	<td colspan="8" align="left"><h3>::Kendra Summary::</h3></td>
                    </tr>
                	<tr style="background-color:#036; color:#fff;">	
                    	<td style="padding:5px;" align="center">#</td>
                    	<td style="padding:5px;">Kendra Name</td>
                    	<td style="padding:5px;">Total Loan</td>
                    	<td style="padding:5px;">Loan in Market</td>
                    	<td style="padding:5px;">Realizable</td>
                    	<td style="padding:5px;">Realized</td>
                    	<td style="padding:5px;">Percent Paid</td>
                    	<td style="padding:5px;">Total Customer</td>
                    </tr>';
                    
             $i=1;
            foreach($branch_data['Kendra'] as $pay_row)
            {       
                    
                    
                	$html .= '<tr>
                    	<td align="center">'.$i.'</td>
                        <td style="padding:5px;">'.$pay_row['Kendra']['kendra_name'].'</td>
                        <td style="padding:5px;">'.$pay_row['Kendra']['total_loan'].'</td>
                        <td style="padding:5px;">'.$pay_row['Kendra']['total_loan_in_market'].'</td>
                        <td style="padding:5px;">'.$pay_row['Kendra']['total_realisable'].'</td>
                        <td style="padding:5px;">'.$pay_row['Kendra']['total_realise'].'</td>
                        <td style="padding:5px;">'.$pay_row['Kendra']['percent_paid'].' %</td>
                        <td style="padding:5px;">'.$pay_row['Kendra']['total_customer'].'</td>
                    </tr>';
            $i++;
            }        
                    
}else{                    
                    
     $html = '<table align="center" style="width:80%;">
    	<tr>
        	<td>
            <table style="width:100%">
            <tr><td>No Data Found</td></tr>
            ';               
}                	
            $html .= '</table>
            </td>
        </tr>
    
    </table>';

$temp_html = $html;    
//echo $html;die;	
echo $temp_html;
/*	  
$pdf->writeHTML($html, true, false, true, false, '');
 
$pdf->lastPage();
 
$pdf->Output(APP . 'files/pdf' . DS . $branch_data['Branch']['branch_name'].'.pdf', 'FD');
*/

