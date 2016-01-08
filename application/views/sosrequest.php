<html lang="en">
<?php 
/**
* @ignore
*
* Page-level DocBlock
*/
$this->load->view('head');
?>      


<script type="text/javascript">
	
	$(document).ready(function(){
		
		var sos_enabled = '<?php $sos_enabled=$this->security->xss_clean($sos_enabled);echo $sos_enabled;?>' ;
		var sos_accepted = "<?php  $sos_already_accepted=$this->security->xss_clean($sos_already_accepted);echo $sos_already_accepted;?>";
		var sos_declined = "<?php $sos_already_declined=$this->security->xss_clean($sos_already_declined);echo $sos_already_declined; ?>";
		var link_status = "<?php  $link_enabled=$this->security->xss_clean($link_enabled);echo $link_enabled; ?>";
		if(link_status == 0)
		{
			$('#divbutton').empty();
		}
		if(sos_enabled == '1')
		{
			$('#divbutton').empty();
			$('#reportatext1').hide();
			$('#reportatext2').hide();
			$('#result').html(sos_accepted);
		}
		else if (sos_enabled == '0')
		{
			$('#divbutton').empty();
			$('#reportatext1').hide();
			$('#reportatext2').hide();
			$('#result').html(sos_declined);
		}
		
		$("#sos_accept").click(function(){
			
			
			
			var url = "<?php echo site_url('sosrequest/sosaccept'); ?>";
			var u_id = "<?php $content['uid']=$this->security->xss_clean($content['uid']);echo $content['uid']; ?>";
			var c_id = "<?php $content['cid']=$this->security->xss_clean($content['cid']);echo $content['cid']; ?>";
			var csrf_value = '<?php echo $this->security->get_csrf_hash(); ?>';
			$.ajax({
				url: url,
				type: 'post',
				data: { user_id: u_id ,'csrf_test_name':csrf_value, contect_id: c_id },
				success: function(json) {
					$('#result').html(json);
					$('#divbutton').hide();
					$('#reportatext1').hide();
					$('#reportatext2').hide();
					
				}
				
					}); 
		});
		$("#sos_reject").click(function(){
			
			var url = "<?php echo site_url('sosrequest/sosreject'); ?>";
			var u_id = "<?php $content['uid']=$this->security->xss_clean($content['uid']);echo $content['uid']; ?>";
			var c_id = "<?php $content['cid']=$this->security->xss_clean($content['cid']);echo $content['cid']; ?>";
			var csrf_value = '<?php echo $this->security->get_csrf_hash(); ?>';
			$.ajax({
				url: url,
				type: 'post',
				data: { user_id: u_id ,'csrf_test_name':csrf_value, contect_id: c_id },
				success: function(json) {
					$('#result').html(json);
					$('#divbutton').hide();
					$('#reportatext1').hide();
					$('#reportatext2').hide();
					
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
		background-color:#80A24A;
		cursor:pointer;
	}
</style>


<body>
	<div>    </div>
	<div id="container">
		<div style="margin-left:20px; margin-top:20px;"><img   src="<?php echo site_url('assets/include/repota.png');?>" width ="300" /></div>
		<div><h1></h1></div> 
		<h1><?php $text4=$this->security->xss_clean($text4) ;echo $text4; ?> </h1>

		<div id="body">
			<div id="reportatext1">
				
				<!--TEXT 1 -->
				<?php  $text1=$this->security->xss_clean($text1) ;echo $text1; ?>
			</div>
			<div id="divbutton" >
				<br><br>
				<a href="javascript:void(0)"><button id=sos_accept type="submit" class="button">Accept </button></a>
				
				<a href="javascript:void(0)"><button id=sos_reject type="submit" class="button">Decline </button></a>
			</div>
			<div id='reportatext2'>
				<!--TEXT 2 -->
				<?php $text2=$this->security->xss_clean($text2) ;echo $text2; ?>
			</div>
			<div id="result" > 
			</div>
			<div id='reportatext3'>
				<?php $text3=$this->security->xss_clean($text3) ; echo $text3; ?>
			</div>
			<br><br>
			
			
		</div>
		
	</div>
</body>
</html>