<html >
<?php 
/**
* @ignore
*/
$this->load->view('head');
?>	

<script>
    $(document).ready(function(){
        /* form validate */
        
        $('#result').hide();
        var status = '<?php $status= $this->security->xss_clean($status) ;echo $status; ?>';

        if(status == '0')
        {
            $('#newpass').html('<div id = "result"><label> <span>Invalid Request </span> </label></div>');
            $('#result').show();
        }
        else if (status == '2')
        {
            $('#newpass').html('');
        }
    });   
</script>
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
        <?php echo validation_errors(); ?>
    </div>
    <?php  } ?>
    <div class="alert alert-danger">
        <?php  $errormessage=$this->security->xss_clean($errormessage);echo $errormessage; ?>
    </div>
    <div class='alert alert-success'>
        <?php $message=$this->security->xss_clean($message);echo $message; ?>
    </div>
    <br>
    <br>
    
    
    <div class="forgotpassword" id="newpass" >
      <?php $attributes = array('class' => 'basic-grey', 'id' => 'frmlogin');?>
      <?php echo form_open('newpassword/updatepassword', $attributes); ?>
      
      <h1>Enter New Password
        <span>Please fill all the texts in the fields.</span>
    </h1>
    <input type="hidden"  name="user_id" id="user_id" value="<?php echo $user_id ; ?>" >
    <input type="hidden"  name="fc" id="fc" value="<?php echo $fc; ?>" >
    
    <label>
        <span>Password :</span>
        <input type="password" class="form-control required email" name="password" id="password"  >
    </label>
    <label>
        <span>Repeat New Password :</span>
        <input type="password" class="form-control required email" name="repassword" id="repassword">
    </label>
    
    <label>
        <span>&nbsp;</span> 
        <button type="submit" >Submit</button>
    </label>
    <?php echo form_close();?>
</div>
<br>
<br>
<div style="margin-left: 33%; margin-right: 30%;">
  Your password should not contain any personal information or common words, such as those found in a dictionary. Your password must include least 8 characters as well as:
  <ul>
    <li>  At least one uppercase character</li>
    <li>At least one numeral</li>
    <li>At least one special character</li>
</ul>
Be aware that Reporta will lock for 24 hours after six failed login attempts. You will need to wait 24 hours to attempt another login.
</div>

</div>

</body>
</html>


