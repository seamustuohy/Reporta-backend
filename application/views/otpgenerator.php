<html lang="en">
<?php 
/**
* @ignore
*/
$this->load->view('head');
?>      


<script type="text/javascript">
	
	$(document).ready(function(){
		
		
		var statusotp = '<?php $statusotp=$this->security->xss_clean($statusotp);echo $statusotp; ?>' ;
		if(statusotp == '0')
		{
			$('#divbutton').empty();
		}
		
		
		$("#otp").click(function(){
			
			var url = "<?php echo site_url('otpgenerator/generate'); ?>";
			var u_id = "<?php $content['uid']=$this->security->xss_clean($content['uid']);echo $content['uid']; ?>";
			var c_id = "<?php echo $content['cid']; ?>";
			var csrf_value = '<?php echo $this->security->get_csrf_hash(); ?>';
			$.ajax({
				url: url,
				type: 'post',
				data: {'csrf_test_name':csrf_value, user_id: u_id , contect_id: c_id},
				success: function(json) {
					$('#otpvalue').html(json);
					$('#otp').hide();
				}
				
			});

			});
		
	});

</script>

<style type="text/css">
	.button {
		background-color: #9DC45F;
		border-radius: 5px;
		-webkit-border-radius: 5px;
		-moz-border-border-radius: 5px;
		border: none;
		padding: 10px 25px 10px 25px;
		color: #FFF;
		text-shadow: 1px 1px 1px #949494;
	}
	.button:hover {
		cursor:pointer !important;
		background-color:#80A24A;
	}
</style>


<body>
	<div>    </div>
	<div id="container">
		<div style="margin-left:20px; margin-top:20px;"><img   src="<?php echo site_url('assets/include/repota.png');?>" width ="300" /></div>
		<div><h1></h1></div>     
		<h1><?php $text=$this->security->xss_clean($text);echo $text; ?></h1>

		<div id="body">
			
			
			<div><?php  echo $text1; ?></div>
			
			
			<div id="result" > 
			</div>
			
			<?php $text2=$this->security->xss_clean($text2);echo $text2; ?>
			<a href='http://www.iwmf.org' ><img   src="<?php echo site_url('assets/include/iwmflogo.jpg') ; ?>" width ='300' /></a>
			<br/><br/>
		</div>
		
	</div>

</body>
</html>