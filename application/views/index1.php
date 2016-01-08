<?php
/**
* @ignore
*/
?>
<head>
  <title>IWMF | International Women's Media Foundation</title>
  
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  
  <link rel="stylesheet" href="<?php echo site_url('application/xcrud/themes/bootstrap/xcrud.css');?>">
  
  
  <!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
  
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="<?php echo site_url('assets/css/script.js');?>"></script>
  <link rel="stylesheet" href="<?php echo site_url('assets/css/manuestyles.css');?>">
</head>

<header>
 
  <div class="colcontainerhead">
    
    <div class="righthead">
      
      <?php
      $session = $this->session->userdata('admin_id');
      if(!empty($session))
      {
        ?>
        <div style="float:right;margin-right:10px;color:#444;">
          <label class='welcome'> Welcome back,<?php echo " ".$this->session->userdata('firstname')."  ".$this->session->userdata('lastname'); ?> </label>
          <a href="<?php echo site_url('login/logout'); ?>"><button type="submit" class="logoutbutton">Log Out</button></a>
        </div>
        <?php
      }
      ?>
    </div>
    
    <div class="lefthead"><a href="<?php echo site_url('home');?>"><img   src="<?php echo site_url('assets/include/iwmflogo.jpg');?>" width ="300" /></a>
    </div>
  </div>
  <div><h1></h1></div>
  
  <div id='cssmenu'>
    <ul class="nav nav-pills">

      <li role="presentation" class="dropdown"><a href="<?php echo site_url('home'); ?>" class="icon home"><span>Home</span></a></li>
      <li role="presentation" class="dropdown"><a href="<?php echo site_url('home/userinfo'); ?>" class="icon home"><span>User Information</span></a></li>
      <li role="presentation" class="dropdown"> <a href="<?php echo site_url('checkinSchedules'); ?>">Active Check-ins</a> </li>
      <li role="presentation" class="dropdown"><a href="<?php echo site_url('alert'); ?>">All Check-ins</a></li>
      <li role="presentation" class="dropdown"><a href="<?php echo site_url('report'); ?>">All Alerts</a></li>
      <li role="presentation" class="dropdown"> <a href="<?php echo site_url('login/changepassword'); ?>">Change Password</a> </li>
    </ul>
  </div>
  <h1></h1>
</header>

