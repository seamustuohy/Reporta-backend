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
		
		$("#alert").click(function(){
			var url = "<?php echo site_url('userinfo/userinfo/alert'); ?>";
			var id = <?php echo $container['id']; ?>;
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
			var id = <?php echo $container['id']; ?>;
			var csrf_value = '<?php echo $this->security->get_csrf_hash(); ?>';
			$.ajax({
				url: url,
				type: 'post',
				data: {'id': id,'csrf_test_name':csrf_value, 'status':0},
				
				success: function(data) {
					$('#result').html(data);
				}
				
			});
				// end ajax call
				
			});
		
		$("#checkinschedules").click(function(){
			var url = "<?php echo site_url('userinfo/userinfo/checkinschedules'); ?>";
			var id = <?php echo $container['id']; ?>;
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
			var id = <?php echo $container['id']; ?>;
			var csrf_value = '<?php echo $this->security->get_csrf_hash(); ?>';
			$.ajax({
				url: url,
				type: 'post',
				data: {'id': id,'csrf_test_name':csrf_value},
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
		
		<h1> User Information</h1>
		
		<div id="body">
			<div>
				
			</div>
			
			<?php $attributes = array('class' => 'basic-grey', 'id' => 'formuser');?>
			<?php echo form_open('', $attributes); ?>
			
			<h1>User Information 
			</h1>
			<label>
				<span>Username :</span>
				<input type="text" class = "required" name="username" id="username" value = "<?php $container['username']=$this->security->xss_clean($container['username']);echo $container['username'];?>" readonly>
			</label>
			
			<label>
				<span>First Name :</span>
				<input type="text"  name="firstname" id="firstname" value = "<?php $container['firstname']=$this->security->xss_clean($container['firstname']);echo $container['firstname']; ?>" readonly>
			</label>
			
			<label>
				<span>Last Name :</span>
				<input type="text" name="lastname" id="lastname" value = "<?php $container['lastname']=$this->security->xss_clean($container['lastname']);echo $container['lastname']; ?>" readonly>
			</label>
			<label>
				<span>Email :</span>
				<input type="text"  name="email" id="email" value = "<?php  $container['email']= $this->security->xss_clean($container['email']);echo $container['email']; ?>" readonly>
			</label>
			<label>
				<span>Job Title :</span>
				<input type="text"  name="jobtitle" id="jobtitle" value = "<?php  $container['jobtitle']=$this->security->xss_clean($container['jobtitle']);echo $container['jobtitle']; ?>" readonly>
				
				
			</label>
			
			<label>
				<span>Phone No :</span>
				<input type="text" name="phone" id="phone" value = "<?php $container['phone']=$this->security->xss_clean($container['phone']);echo $container['phone'];?>"readonly>
			</label>
			
			<label>
				<span>Language :</span>
				<input type="text" name="language" id="language" value = "<?php $container['language']=$this->security->xss_clean($container['language']);echo $container['language']; ?>"readonly>
			</label>
			
			<label>
				<span>Affiliation :</span>
				<input type="text" name="affiliation" id="affiliation"  value = "<?php $container['affiliation_id']=$this->security->xss_clean($container['affiliation_id']);echo $container['affiliation_id']; ?>" readonly>
			</label>
			<label>
				<span>Freelancer :</span>
				<?php
				if($container['freelancer'] == 0)
				{
					$freelancer= "NO";
				}
				elseif($container['freelancer'] == 1)
				{
					$freelancer="YES";
				}
				?>
				
				
				<input type="text"  name="freelancer" id="freelancer" value = "<?php  $freelancer=$this->security->xss_clean($freelancer);echo $freelancer; ?>" readonly>			
			</label>
			<label>
				<span>Country of Origin :</span>
				<input type="text" name="origin_country" id="origin_country" value = "<?php  $container['origin_country']=$this->security->xss_clean($container['origin_country']);echo $container['origin_country']; ?>" readonly>
			</label>
			<label>
				<span>Country Where Working :</span>
				<input type="text" name="working_country" id="working_country" value = "<?php $container['working_country']=$this->security->xss_clean($container['working_country']);echo $container['working_country']; ?>" readonly>
			</label>
			<label>
				<span>status :</span>
				<?php
				if($container['status'] == -1)
				{
					$status = "Locked";
				}
				elseif($container['status'] == 0)
				{
					$status = "InActive";
				}
				elseif($container['status'] == 1)
				{
					$status = "Active";
				}
				?>
				<input type="text"  name="freelancer" id="freelancer" value = "<?php $status=$this->security->xss_clean($status);echo $status; ?>" readonly>
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