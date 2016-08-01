<div class="box-body">
                        <div class="form-group">
                          <label for="Name">First Name</label>
                          <input type="text" class="form-control" id="first_name" name="data[User][first_name]" placeholder="Enter First Name" required="required">
                        </div>
						
						<div class="form-group">
                          <label for="Name">Last Name</label>
                          <input type="text" class="form-control" id="last_name" name="data[User][last_name]" placeholder="Enter Last Name" required="required">
                        </div>
                        
                        <div class="form-group">
                          <label for="Email">Email Id</label>
                          <input type="email" class="form-control" id="email" name="data[User][email]" placeholder="Enter Email" required="required">
                        </div>
                        
                        <div class="form-group">
                          <label for="Password">Password</label>
                          <input type="password" class="form-control" id="password" name="data[User][password]" placeholder="Enter Password" required="required">
                        </div>
                        <!--
                        <div class="form-group">
                          <label for="re-password">Re-enter Password</label>
                          <input type="text" class="form-control" id="email" name="data[User][email]" placeholder="Re-enter Password" required="required">
                        </div>
                        -->
                        <div class="form-group">
                          <label for="OrganizationName">Organization Name</label>
                          <input type="text" class="form-control" id="organization_name" name="data[Organization][organization_name]" placeholder="Enter Organization Name" required="required">
                        </div>
                        
                        <div class="form-group">
                          <label for="Address ">Address </label>
                          <input type="text" class="form-control" id="address" name="data[Organization][address]" placeholder="Enter Address" required="required">
                        </div>
                        
                        <div class="form-group">
                          <label for="City ">City </label>
                          <input type="text" class="form-control" id="city" name="data[Organization][city]" placeholder="Enter City" required="required">
                        </div>
                        
                        <div class="form-group">
                          <label for="State">State</label>
                          <input type="text" class="form-control" id="state" name="data[Organization][state]" placeholder="Enter State" required="required">
                        </div>
						
						<!--
						 <div class="form-group">
                          <label for="State">Country</label>
                          <input type="text" class="form-control" id="country" name="data[Organization][country]" placeholder="Enter State" required="required">
                        </div>
						-->
                       
                        <div class="form-group">
                          <label>Country</label>
								<?php
									echo $this->Form->input('Organization.country_id', array('type' => 'select', 'options' => $countryList, 'class'=>'form-control', 'label'=>false, 'value'=>99, 'empty' => 'Select Country'));
								?>
                        </div>
                       
						
                        <div class="form-group">
                          <label for="Zip">Zip</label>
                          <input type="text" class="form-control" id="zip" name="data[Organization][zip]" placeholder="Enter Zip" required="required">
                        </div>
    
                    </div><!-- /.box-body -->
                    <div class="box-footer" align="center">
                    	<button type="submit" class="btn btn-primary btn-lg">Submit</button>
                    </div>