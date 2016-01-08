<html >
<?php 
/**
* @ignore
*/
$this->load->view('head');
?>	
<body>
    <div>    </div>
    <div id="container">
       <div class="center"><img   src="<?php echo site_url('assets/include/iwmflogo.jpg');?>" width ="300" /></div>
       <div><h1></h1></div>
       <div id="body">
          <div> </div>
          
          <div id = "result" class="alert alert-danger">
            
          </div>
          
          
      </div>
      <?php 
      $error = '';
      $error = validation_errors();
      if($error!="" ){ ?>
      <div class="alert alert-danger">
        <strong>Wrong Credentials!</strong>
        <?php echo validation_errors(); ?>
    </div>
    <?php  } ?>
    <div class="alert alert-danger">
        <?php  $errormsg=$this->security->xss_clean($errormsg); echo $errormsg; ?>
    </div>
    <div class='alert alert-success'>
        <?php  $message= $this->security->xss_clean($message);echo $message; ?>
    </div>
    <br>
    <br>
    
    <div class="forgotpassword" id="forgotpassword" >
       
       <?php $attributes = array('class' => 'basic-grey', 'id' => 'frmlogin');?>
       <?php echo form_open('login/phoneverification', $attributes); ?>
       <h1>PHONE Verification
        <span>Please fill all the texts in the fields.</span>
    </h1>
    <label>
        <span>ENTER CODE :</span>
        <input type="text" class="form-control required email" name="code" id="code" placeholder="Enter code">
    </label>
    
    <label>
        <span>&nbsp;</span> 
        <button type="submit" >Submit</button>
    </label>
    <?php echo form_close();?>
</div>
</div>

</body>
</html>
