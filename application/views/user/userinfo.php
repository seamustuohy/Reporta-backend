<?php
/**
* @ignore
*/
?>
<html>
<?php $this->load->view('head');?>
<?php  echo Xcrud::load_css();?>
<?php  echo Xcrud::load_js();?>
<script type="text/javascript">
	
	$(document).ready(function(){
		
		$('#jobtitle option[value="<?php $container['jobtitle']=$this->security->xss_clean($container['jobtitle']);echo $container['jobtitle']; ?>"]').attr("selected",true);
		$('#freelancer option[value="<?php $container['freelancer']=$this->security->xss_clean($container['freelancer']);echo $container['freelancer'];?>"]').attr("selected",true);
		$('#status option[value="<?php $container['status']=$this->security->xss_clean($container['status']);echo $container['status'];?>"]').attr("selected",true);
		$('#language_code option[value="<?php $container['language_code']=$this->security->xss_clean($container['language_code']);echo $container['language_code']; ?>"]').attr("selected",true);
		$('#gender_type option[value="<?php $container['gender_type']=$this->security->xss_clean($container['gender_type']);echo $container['gender_type']; ?>"]').attr("selected",true);
		
		if($('#gender_type option:selected').val() == 3)
		{
			$('#othergender').val('<?php $container['gender']=$this->security->xss_clean($container['gender']);echo $container['gender'];?>');
		}
		else
		{
			$('#othergender_label').hide()
		}
		
		$('#gender_type').change(function() {
			if($('#gender_type option:selected').val() == '3')
			{
				$('#othergender_label').show();	
			}
			else{
				$('#othergender_label').hide();
			}
			
		});
		
		$("#alert").click(function(){
			var url = "<?php echo site_url('userinfo/userinfo/alert'); ?>";
			var id = <?php  $container['id']=$this->security->xss_clean($container['id']);echo $container['id']; ?>;
			var csrf_value = '<?php echo $this->security->get_csrf_hash(); ?>';
			
			
			$.ajax({
				url: url,
				type: 'post',
				data: {'id': id,'csrf_test_name':csrf_value,'status':0},
				
				success: function(data) {
					
					$('#result').html(data);
				}
				
			});
				// end ajax call
			});
		
		$("#contact").click(function(){
			var url = "<?php echo site_url('userinfo/userinfo/contactlist'); ?>";
			var id = <?php $container['id']=$this->security->xss_clean($container['id']);echo $container['id']; ?>;
			var csrf_value = '<?php echo $this->security->get_csrf_hash(); ?>';
			$.ajax({
				url: url,
				type: 'post',
				data: {'id': id, 'csrf_test_name':csrf_value,'status':0},
				
				success: function(data) {
					$('#result').html(data);
				}
				
			});
				// end ajax call
				
			});
		
		$("#checkinschedules").click(function(){
			var url = "<?php echo site_url('userinfo/userinfo/checkinschedules'); ?>";
			var id = <?php  $container['id']=$this->security->xss_clean($container['id']); echo $container['id']; ?>;
			var csrf_value = '<?php echo $this->security->get_csrf_hash(); ?>';
			$.ajax({
				url: url,
				type: 'post',
				data: {'id': id,'csrf_test_name':csrf_value,'status':0},
				success: function(data) {
					$('#result').html(data);
				}
				
			});
				// end ajax call
				
			});
		
		$("#report").click(function(){
			var url = "<?php echo site_url('userinfo/userinfo/report'); ?>";
			var id = <?php $container['id']=$this->security->xss_clean($container['id']); echo $container['id']; ?>;
			var csrf_value = '<?php echo $this->security->get_csrf_hash(); ?>';
			$.ajax({
				url: url,
				type: 'post',
				data: {'id': id,'csrf_test_name':csrf_value,},
				success: function(json) {   
					$('#result').html(json);
				}
			});
				// end ajax call
			});
	});

function nospaces(t){
	
	if(t.value.match(/\s/g)){

		alert('Sorry, you are not allowed to enter any spaces');

		t.value=t.value.replace(/\s/g,'');

	}

}

</script>
<body>
	<div>    </div>
	<div id="container">
		<div> <?php $this->load->view('index1'); ?> </div>
		
		<h1>User Information</h1>
		
		<div id="body">
			<div>
				
				
				<?php 
				$error = '';
				$errormsg = $this->security->xss_clean($errormsg);
				$Updatemsg=$this->security->xss_clean($Updatemsg);
				if($errormsg != "")
				{
					echo " <div class='alert alert-danger'>".$errormsg."</div>";
				}
				if($Updatemsg != "")
				{
					echo " <div class='alert alert-success'>".$Updatemsg."</div>";
				}
				$error = validation_errors();
				if($error!="")
				{
					?>
					<div class="alert alert-danger">
						<?php
						echo validation_errors();
					}
					?>
				</div>
				
			</div>
			<?php $attributes = array('class' => 'basic-grey', 'id' => 'formuser');
			$form_url = 'userinfo/userinfo/updateuser/'.$container['id'];
			echo form_open($form_url, $attributes); ?>
			
			<h1>User Information 
				<span>Please fill all the texts in the fields.</span>
			</h1>
			<input type="hidden" class = "required" name="user_id" id="user_id" value = "<?php $container['id']=$this->security->xss_clean($container['id']);echo $container['id'];?>">
			<label>
				<span>Username :</span>
				<input type="text" class = "required" name="username" id="username" onchange="nospaces(this);" value = "<?php $container['username']=$this->security->xss_clean($container['username']);echo $container['username']; ?>">
			</label>
			
			<label>
				<span>First Name :</span>
				<input type="text" class="form-control required" name="firstname" id="firstname" value = "<?php $container['firstname']=$this->security->xss_clean($container['firstname']);echo $container['firstname']; ?>">
			</label>
			
			<label>
				<span>Last Name :</span>
				<input type="text" name="lastname" id="lastname" value = "<?php $container['lastname']=$this->security->xss_clean($container['lastname']);echo $container['lastname']; ?>">
			</label>
			<label>
				<span>Account Created :</span>
				<input type="text" name="accountcreated" id="accountcreated" value = "<?php $container['created_on']=$this->security->xss_clean($container['created_on']);echo $container['created_on']; ?>" readonly>
			</label>
			<label>
				<span>Email :</span>
				<input type="text" class="form-control required email" name="email" id="email" value = "<?php $container['email']=$this->security->xss_clean($container['email']);echo $container['email']; ?>">
			</label>
			
			<label>
				<span>Gender :</span>
				<select id="gender_type"  name="gender_type">
					<option value="1">Male</option>
					<option value="2">Female</option>
					<option value="3">Other</option>
				</select>
			</label>
			<label id='othergender_label'>
				<span></span>
				<input type="text"  name="othergender" id="othergender" value = "">
			</label>
			
			<label>
				<span>Job Title :</span>
				<select id="jobtitle" name="jobtitle">
					<option value="Journalist/Reporter" selected="">Journalist/Reporter</option>
					<option value="Editor">Editor</option>
					<option value="Photographer">Photographer</option>
					<option value="Producer">Producer</option>
					<option value="Presenter">Presenter</option>
					<option value="Camera/Sound Person">Camera/Sound Person</option>
					<option value="Media Support Worker">Media Support Worker</option>
				</select>
			</label>

			<label>
				<span>Language :</span>
				<select id="language_code" name="language_code">
					<option value="EN" >English</option>
					<option value="AR" >Arabic</option>
					<option value="FR" >French</option>
					<option value="IW" >Hebrew</option>
					<option value="ES" >Spanish</option>
					<option value="TR" >Turkish</option>
				</select>
			</label>
			
			<label>
				<span>Phone No :</span>
				<input type="text" name="phone" id="phone" value = "<?php $container['phone']=$this->security->xss_clean($container['phone']);echo $container['phone'];?>">
			</label>
			
			<label>
				<span>Affiliation :</span>
				<input type="text" name="affiliation" id="affiliation"  value = "<?php $container['affiliation_id']=$this->security->xss_clean($container['affiliation_id']);echo $container['affiliation_id'];?>">
			</label>
			<label>
				<span>Freelancer :</span>
				<select id="freelancer" name = "freelancer">
					<option value="1">Yes</option>
					<option value="0">No</option>
				</select>
			</label>
			<label>
				<span>Country of Origin :</span>
				
				<select id="origin_country" name="origin_country">
					<?php
					foreach ($countrylist as $key => $value)
					{
						echo "<option value='".$key."'";
						if($container['origin_country'] == $key)
						{
							echo " selected='selected'";
						}
						echo">".$value."</option>";
					}
					?>
					
				</select>
				
			</label>
			<label>
				<span>Country Where Working :</span>
				<select id="working_country" name="working_country">
					
					<?php
					foreach ($countrylist as $key => $value)
					{
						echo "<option value='".$key."'";
						if($container['working_country'] == $key)
						{
							echo " selected='selected'";
						}
						echo">".mb_convert_encoding($value,"HTML-ENTITIES","UTF-8")."</option>";
					}
					?>
				</select>
			</label>
			<label>
				<span>Status :</span>
				<select id="status"  name="status">
					<option value="-1" selected="">Locked</option>
					<option value="1">Active</option>
					<option value="0">Inactive</option>
				</select>
			</label>
			<label>
				<span>&nbsp;</span> 
				<button type="submit" >Update</button>
			</label>     
			<?php echo form_close();?>
			
			
			<h1></h1>
			<div class="menu_simple">
				<ul >
					
					<li role="presentation" class="dropdown"><a id="alert" href="javascript:void(0)">All Check-ins</a></li>
					<li role="presentation" class="dropdown"><a id="report" href="javascript:void(0)">All Alerts</a> </li>
					<li role="presentation" class="dropdown"> <a id="checkinschedules" href="javascript:void(0)">Active Check-ins</a> </li>
					<li role="presentation" class="dropdown"> <a id="contact" href="javascript:void(0)">Contact Lists</a> </li>
					
				</ul>
			</div>
			<div id="result">
			</div>
		</div>

	</div>

</body>
</html>