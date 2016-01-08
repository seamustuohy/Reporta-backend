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
       
       <h1>Change Paswword  </h1>
       <div id="body">
          <div><br><br > </div>

          
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
        <?php $errormessage=$this->security->xss_clean($errormessage);echo $errormessage; ?>
    </div>
    <div class='alert alert-success'>
        <?php $message=$this->security->xss_clean($message);echo $message; ?>
    </div>
    <br>
    <br>
    <div class="forgotpassword" id="forgotpassword">
      
       <?php $attributes = array('class' => 'basic-grey', 'id' => 'frmlogin');?>
       <?php echo form_open('login/changepassword', $attributes); ?>
       
       <h1>Change Password
        <span>Please fill all the texts in the fields.</span>
    </h1>
    <label>
        <span>Current password:</span>
        <input type="password" class="form-control required email" name="currentpassword" id="currentpassword" placeholder="Enter Current Password">
    </label>
    <label>
        <span>New Password :</span>
        <input type="password" class="form-control required email" name="newpassword" id="newpassword" placeholder="Enter New Password">
    </label>
    <label>
        <span>Confirm Password :</span>
        <input type="password" class="form-control required email" name="confirmpassword" id="confirmpassword" placeholder="Confirm P   assword">
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