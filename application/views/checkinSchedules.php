<html lang="en">
<?php 
/**
* @ignore
*/
$this->load->view('head');
?>

<script type="text/javascript">
	
	$(document).ready(function(){

		$('#status_select').change(function() {
			var u = "<?php echo site_url('checkinSchedules'); ?>";
			var csrf_value = '<?php echo $this->security->get_csrf_hash(); ?>';
			$.ajax({

				url: u,
				
				type: 'POST',

				data: {status : $('#status_select option:selected').val(),'csrf_test_name':csrf_value},
				success: function(data) {
									$('#alertinfo').empty(); 
									$('#alertinfobystatus').html(data);
								},
							});
		});

	});

</script>
<body>
	<div>    </div>
	<div id="container">
		<div> <?php $this->load->view('index1'); ?> </div>
		<h1>Check Ins Schedules  </h1>

		<div id="body">
			<div>
				<div class="status" id="status" >
					<label> Check In Type : </label>
					<select name="users" id="status_select" class="status_select">
						<option value="-1">All</option>
						<option value="0">Pending</option>
						<option value="1">Started</option>
						<option value="2">Confirmed</option>
					</select>
					
				</div>
			</div>
			
			<div class="alertinfo" id="alertinfo"><?php echo $content; ?></div>
			<div class="alertinfobystatus" id="alertinfobystatus"></div>
			
		</div>

	</div>

</body>
</html>