<html lang="en">
<?php 
/**
* @ignore
*/
$this->load->view('head');
?>

<body>
	<div>    </div>
	<div id="container">
		<div> <?php $this->load->view('index1'); ?> </div>
		
		<h1>User Information  </h1>
		
		
		<a href="<?php echo site_url('userinfo/userinfo/useradd')  ?>"><button type="submit" class="button">Add</button></a>
		
		<div id="body">
			<div><br><br > </div>
			
			<?php echo $content; ?>
			
		</div>

	</div>

</body>
</html>


<?php 
$login_type = $this->session->userdata('type');
if($login_type == 3)
	{?>
<script type="text/javascript">
	$(".xcrud").on("click","a.ref",function(){
		
		var a_href = $(this).attr('href');
		var csrf_value = '<?php echo $this->security->get_csrf_hash(); ?>';
		var u = "<?php echo site_url('home/deleteuser'); ?>";
		var cancel = confirm("Do You want to Delete User?");
		if(cancel)
		{
			$.ajax({
				
				url: u,
				
				type: 'POST',
				
				data: {'csrf_test_name':csrf_value ,user_id : a_href},
				success: function() {
					alert('User DELETED');
					location.reload();
				}
			});
		}
		return false;
	});
</script>
<?php } ?>