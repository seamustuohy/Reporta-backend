<?php
/**
* @ignore
*
* Page-level DocBlock
*/
?>
<html lang="en">
<head>
	<?php $this->load->view('head');?>

	<script type="text/javascript">
		
		$(document).ready(function(){
			
			
			
			$('#status_select').change(function() {
				
				var u = "<?php echo site_url('alert'); ?>";
				var csrf_value = '<?php echo $this->security->get_csrf_hash(); ?>';
				
				$.ajax({

					url: u,
					
					type: 'POST',

					data: {'csrf_test_name':csrf_value ,status : $('#status_select option:selected').val()},
					success: function(data) {
									$('#alertinfo').empty(); 
									$('#alertinfobystatus').html(data);
								}
							});
			});

		});

	</script>	

</head>
<body>
	<div>    </div>
	<div id="container">
		<div> <?php $this->load->view('index1'); ?> </div>
		
		<h1>Check Ins  </h1>
		<div id="body">
			<div>
				<div class="status" id="status" >
					<label> Check In Type : </label>
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
			</div> 
			<div class="alertinfo" id="alertinfo"><?php  echo $content; ?></div>
			<div class="alertinfobystatus" id="alertinfobystatus"></div>
			
		</div>

	</div>

</body>
</html>