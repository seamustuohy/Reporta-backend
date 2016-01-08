<?php
/**
* @ignore
*
* Page-level DocBlock
*/
?>
<html>
<head>
    <link rel="icon" type="image/jpeg" href="<?php echo site_url('assets/include/reporter.ico');?>" >
    <meta charset="utf-8">
    
    <link rel="stylesheet" href="<?php echo site_url('assets/css/reporta.css'); ?>">
</head>
<body>
    <div>    </div>
    <div id="container">
       <div> <?php $this->load->view('index1'); ?> </div>
       
       <div> <h1>Alert Information</h1></div>
       <div class="labeltab">
        <table>
            <tr>
                <td>
                    <label > Alert Id </label>
                </td>
                <td>:</td>
                <td> <label><?php $report['id']=$this->security->xss_clean($report['id']);echo $report['id']; ?></label>
                </td>
            </tr>
            <tr>
                <td>
                    <label > Alert Status</label>
                </td>
                <td>:</td>
                <td> <label><?php  $report['situation']= $this->security->xss_clean($report['situation']); echo $report['situation'] ;?></label>
                </td>
            </tr>
            
            <tr>
                <td>
                    <label > Alert Date:</label>
                </td>
                <td>:</td>
                <td> <label><?php $report['created_on']=$this->security->xss_clean($report['created_on']); echo date('m-d-Y H:i:s',strtotime($report['created_on']));?></label>
                </td>
            </tr>
            
            <tr>
                <td>
                    <label >User Name</label>
                </td>
                <td>:</td>
                <td> <a id="viewuser" href="<?php echo site_url('userinfo/userinfo/index');echo "/".$user['id']; ?>"> <label><?php echo $user['firstname'];echo " ".$user['lastname'];?> 
                   
                </label></a>
            </td>
        </tr>
        
    </table>
</div>
<div> <h1></h1></div>
<div class="labeltab">
    
    <label > <?php $report['situation']=$this->security->xss_clean($report['situation']);echo $report['situation']; ?> </label><br><br><br>
    <label >Additional Details:</label><br>
    <label > <?php $report['description']=$this->security->xss_clean($report['description']);echo $report['description'];?> </label><br><br>
    <label >Location:</label><br>
    <label > <?php $report['location']=$this->security->xss_clean($report['location']);echo $report['location'];?> </label>
    
</div>

<div id="body">
  <div>
      
      <div> <h1></h1><h4>Added Media Files:</h4></div>
      
  </div>
  <br><br>
  
  
  <?php
  if(count($media) == 0)
  {
     echo "<label class='labeltab'>No media found</label>";
 }
 ?>
 
 <fieldset>
    <legend>Audios:</legend>
    <div class="column">
        <?php
        for($i=0;$i< count($media);$i = $i+1)
        {
         if($media[$i]['mediatype'] == 1)
         {
            echo "<div class='column-left'>";
            echo "<audio  src=".site_url('assets/uploads/audio')."/".$media[$i]['medianame']."  controls></audio>";
            echo "<br>Uploaded On :".date('m-d-Y H:i:s',strtotime($media[$i]['created_on']))  ."<br>";
            echo "</div>";
        }
    }?>
</div>
</fieldset>

<fieldset>
    <legend>Videos:</legend>
    <div class="column">
        <?php
        
        for($i=0;$i< count($media);$i = $i+1)
        {
         if($media[$i]['mediatype'] == 2)
         {
            echo "<div class='column-left'>";
            echo "
            <video  height = '250' controls>
                <source src=".site_url('assets/uploads/video')."/".$media[$i]['medianame']." type='video/mp4'>
                    <source src=".site_url('assets/uploads/video')."/".$media[$i]['medianame']." type='video/ogg'>
                        Your browser does not support HTML5 video.
                    </video>";
                    echo "<br>Uploaded On :".date('m-d-Y H:i:s',strtotime($media[$i]['created_on'])) ."<br>";
                    echo "</div>";
                    
                }
            }?>
        </div>
    </fieldset>
    
    <fieldset>
        <legend>Images:</legend>
        <div class="column">
            <?php
            for($i=0;$i< count($media);$i = $i+1)
            {
             if($media[$i]['mediatype'] == 3)
             {
                echo "<div class='column-left'>";
                echo "<img   src=".site_url('assets/uploads/picture')."/".$media[$i]['medianame']." width ='150' height = '150' />";
                echo "<br>Uploaded On :".date('m-d-Y H:i:s',strtotime($media[$i]['created_on']))  ."<br>";
                echo "</div>";
            }
        }?>
    </div>
</fieldset>
</div>

</div>

</body>
</html>