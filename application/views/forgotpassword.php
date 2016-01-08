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
        <?php $errormessage=$this->security->xss_clean($errormessage);echo $errormessage; ?>
    </div>
    <div class='alert alert-success'>
        <?php  $message=$this->security->xss_clean($message); echo $message; ?>
    </div>
    <br>
    <br>
    <a href="<?php echo site_url('login')  ?>"><button type="submit" class="button">Login</button></a>
    <div class="forgotpassword" id="forgotpassword" >
       
      <?php $attributes = array('class' => 'basic-grey', 'id' => 'frmlogin');?>
      <?php echo form_open('login/forgotpassword', $attributes); ?>
      
      <h1>Forgot Password
        <span>Please fill all the texts in the fields.</span>
    </h1>
    <label>
        <span>Email :</span>
        <input type="text" class="form-control required email" name="email" id="email" placeholder="Enter EMAIL">
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

