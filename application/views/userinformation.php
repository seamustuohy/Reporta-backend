<html lang="en">
<?php 
/**
* @ignore
*
* Page-level DocBlock
*/
$this->load->view('head');
?>
<style type="text/css">
	.button {
		background-color: #9DC45F;
		margin-left:15px;
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
		color: #FFF;
	}
	
</style>

<body>
	<div>    </div>
	<div id="container">
		<div> <?php $this->load->view('index1'); ?> </div>
		
		<h1>User Information  </h1>
		
		<a href="<?php echo site_url('userinfo/userinfo/useradd')  ?>"><button type="submit" class="button">Add</button></a>
		
		<div id="body">
			<div><br><br > </div>
			
			<?php echo $content ?>
			
		</div>

	</div>

</body>
</html>