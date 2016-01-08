<?php
/**
* @ignore
*/
?>
<html lang="en">

<?php $this->load->view('head');?>

<script type="text/javascript">
	
	$(document).ready(function(){
		
		
		
		
		$('#status_select').change(function() {
			
			var u = "<?php echo site_url('userinfo/userinfo/contactlist'); ?>";
			var id = "<?php  $user_id=$this->security->xss_clean($user_id);echo $user_id; ?>";
			var csrf_value = '<?php echo $this->security->get_csrf_hash(); ?>';
			$.ajax({
				url: u,
				
				type: 'POST',

				data: {'csrf_test_name':csrf_value,circle : $('#status_select option:selected').val(),id:id,status:1},
				success: function(data) {
									// clear the current elements in select box
									$('#alertinfo').empty();
									$('#alertinfobystatus').html(data);
								}
							});
		});

	});

</script>
<body>
	<div>    </div>
	<div id="container">

		<h1>Contact Lists  </h1>
		
		
		<div class="status" id="status" >
			<label> Contact type : </label>
			<select name="users" id="status_select" class="status_select">
				<option value="0">All</option>
				<option value="1">private</option>
				<option value="2">public</option>
			</select>
			
		</div>

		<div id="body">
			<div> </div>
			
			<div class="alertinfo" id="alertinfo"><?php echo $content; ?></div>
			<div class="alertinfobystatus" id="alertinfobystatus"></div>
		</div>

	</div>

</body>
</html>