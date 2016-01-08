<html>
<?php 
/**
* @ignore
*/
$this->load->view('head');
?>       

<body>
    <div>    </div>
    <div id="container">
       <div style="margin-top:2% !important;margin-left:2% !important;"><img   src="<?php echo site_url('assets/include/repota.png');?>" width ="300" /></div>
       <div><h1></h1></div>

       <div id="body">
          <div> </div>
          <?php 
          $error = '';
          $error = validation_errors();
          if($error!="" || $errormsg!="")
              { ?>
          <div class="alert alert-danger">
            <strong>Wrong Credentials!</strong>
            <?php echo validation_errors(); ?>
            <?php $errormsg=$this->security->xss_clean($errormsg);echo $errormsg; ?>
        </div>
        <?php  } ?>
        
        
    </div>
    
    <div class="loginform" id ="loginform" style="margin-top: 5%;">
        <?php $attributes = array('class' => 'basic-grey', 'id' => 'frmlogin');?>
        <?php echo form_open('login', $attributes); ?>
        
        <h1>Administrative Login
            <span></span>
        </h1>
        <label>
            <span>Username :</span>
            <input type="text" class="form-control required email" name="username" id="username" placeholder="Enter Username">
        </label>
        
        <label>
            <span>Password :</span>
            <input type="password" class="form-control required" name="password" id="password" placeholder="Enter password">
        </label>
        
        <label>
            <span>&nbsp;</span> 
            <button type="submit" >Login</button>
        </label>
        <br><br>
        <label><samp><a id="forgotpassword" href="<?php echo site_url('login/forgotpassword'); ?>">Forgot your password?</a></samp></label>
        <?php echo form_close();?>
    </div>
    
    <div class="center" style="width: 500px !important;"><div style="margin-left:10% !important;margin-top: 10% !important;"><img   src="<?php echo site_url('assets/include/iwmflogo.jpg');?>" width ="300" /></div></div>
    <div class="center" style="width: 500px !important;">Reporta<sup style='font-size:8px;'>TM</sup> is powered by The International Women's Media Foundation</div>

</div>

</body>
</html>
