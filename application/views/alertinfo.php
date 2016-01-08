<html>
<?php 
/**
* @ignore
*
* Page-level DocBlock
*/
$this->load->view('head');
?>

<style type="text/css">	
	table, th, td
	{
		border: none !important;
	}
</style>
<body>
	<script type="text/javascript">
		
		$(document).ready(function(){
			$('#contactlist').click(function() {
				var u = "<?php echo site_url('userinfo/userinfo/contactlist'); ?>";
				var id = "<?php $user['id'] = $this->security->xss_clean($user['id']);echo $user['id']; ?>";
				var csrf_value = '<?php echo $this->security->get_csrf_hash(); ?>';
				$.ajax({
					url: u,
					type: 'POST',
					data: {'csrf_test_name':csrf_value,id:id,status:0},
					success: function(data) {
						$('#alertinfo1').html(data);
					}
				});
			});
		});
	</script>
	
	<div>    </div>
	<div id="container">
		<div> <?php $this->load->view('index1'); ?> </div>
		<div class="checkininfo" id="checkininfo">
			<h1>Check In Information  </h1>
			
			<div class= "labeltab">
				<table border=0>
					<tr>
						<td>
							<label> Check In No</label>
						</td>
						<td>:</td>
						<td> <label><?php $checkin['id']=$this->security->xss_clean($checkin['id']); echo $checkin['id'];?></label>
						</td>
					</tr>
					<tr>
						<td>
							<label> Check In Status</label>
						</td>
						<td>:</td>
						<td> <label><?php
							
							if($checkin['status'] == 0)
							{
								echo "Pending";
							}
							elseif($checkin['status'] == 1)
							{
								echo"Started";
							}
							elseif($checkin['status'] == 2)
							{
								echo"Confirmed";
							}
							elseif($checkin['status'] == 3)
							{
								echo"Deleted";
							}
							elseif($checkin['status'] == 4)
							{
								echo"closed";
							}
							elseif($checkin['status'] == 5)
							{
								echo"<span style='color: #f00;'>Missed</span>";
							}
							?>
						</label>
					</td>
				</tr>
				
				<tr>
					<td>
						<label> Check In Schedule </label>
					</td>
					<td>:</td>
					<td> <label><?php echo date('m-d-Y H:i:s',strtotime($checkin['starttime']));
						if( strtotime($checkin['endtime']) >0)
						{
							echo "	To  ".date('m-d-Y H:i:s',strtotime($checkin['endtime']));
						}?>
					</label>
				</td>
			</tr>
			
			<tr>
				<td>
					<label> Check In Frequency </label>
				</td>
				<td>:</td>
				<td> <label><?php $checkin['frequency'] = $this->security->xss_clean($checkin['frequency']);echo $checkin['frequency']." Minute";?></label>
				</td>
			</tr>
			
			<tr>
				<td>
					<label> User Name</label>
				</td>
				<td>:</td>
				<td> <a id="viewuser" href="<?php echo site_url('userinfo/userinfo/index');echo "/".$user['id']; ?>"> <label><?php echo $user['firstname'];echo " ".$user['lastname'];?> 
					
				</label></a>
				
			</td>
		</tr>
		
	</table>
</div>
<div> <h1></h1></div>
<label class="labeltab">Location Details</label>
<div class="labeltab">
	<div class="colcontainer">
		<div class="right">
			<label class="labeltab">Geolocated address:</label><br>
			<label class="labeltab"><?php $checkin['location']= $this->security->xss_clean($checkin['location']); echo $checkin['location']; ?></label>
			<br><br><br>
			
			<label class="labeltab">User added details:</label><br>
			<label class="labeltab"><?php $checkin['description']=$this->security->xss_clean($checkin['description']);echo $checkin['description']; ?></label>
		</div>
		<div class="left">
			
			
			<?php echo $map['js']; ?>
			<?php echo $map['html']; ?>
		</div>
		
	</div>
</div>
<h1></h1>
<div>
	<h4>Contact Lists: <a id="contactlist" href="javascript:void(0)">View Contacts</a></h4>
	
	<div class="alertinfo1" id="alertinfo1"></div>
</div>
<h1>  </h1>

<div id="body">
	<h2>Check In Details:</h2>
	
	<?php echo $content; ?>
	
</div>

</div>

</body>
</html>