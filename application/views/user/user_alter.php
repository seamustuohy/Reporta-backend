<?php
/**
* @ignore
*/
?>
<html>

<script type="text/javascript">
	
	$(document).ready(function(){

		$('#status_select').change(function() {
			var u = "<?php echo site_url('userinfo/userinfo/alert'); ?>";
			var id = "<?php $user_id=$this->security->xss_clean($user_id);echo $user_id; ?>";
			var csrf_value = '<?php echo $this->security->get_csrf_hash(); ?>';
			$.ajax({
				url: u,
				
				type: 'POST',
				
				data: {'csrf_test_name':csrf_value,selectstatus : $('#status_select option:selected').val(),id:id,status:1},
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
		
		<h1>Check Ins  </h1>
		
		<div id="body">
			<div>
				<div class="status_select" id="status_select" >
					<label style="margin-left:15px;"> Check In Type : </label>
					<select name="users" id="status_select" class="status_select">
						<option value="-1">All</option>
						<option value="0">Pending</option>
						<option value="1">Started</option>
						<option value="2">Confirmed</option>
						<option value="3">Deleted</option>
						<option value="4">Closed</option>
						<option value="5">Missed</option>
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