<html lang="en">
<?php 
/**
* @ignore
*/
$this->load->view('head');
?>
<body>
    <div id="container">
       <div> <?php $this->load->view('index1'); ?> </div>
       <h1> View Media  </h1>
       <div>
          
          <h4>Added Media Files:</h4>
          
      </div>
      
      <?php
      if(count($media) == 0)
      {
         echo "<label class='label'>No media found</label>";
     }?>
     
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
            echo "<video  height = '250' controls>
            <source src=".site_url('assets/uploads/video')."/".$media[$i]['medianame']." type='video/mp4'>
                <source src=".site_url('assets/uploads/video')."/".$media[$i]['medianame']." type='video/ogg'>
                    Your browser does not support HTML5 video.
                </video>";
                echo "<br>Uploaded On :".date('m-d-Y H:i:s',strtotime($media[$i]['created_on']))  ."<br>";
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

                                </body>
                                </html>