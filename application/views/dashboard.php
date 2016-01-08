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
       
       <div class="table-responsive" style="width:70%; margin-left:auto;margin-right:auto;margin-top:5%">
          <table class="table" style="width:100%; align:center;text-align:center;">
            <tr style="height:10px; background-color:#888;color: #fff;">
                <td>Current registered app users</td>
                <td>New users last 30 days</td>
                <td>Current number locked users</td>
            </tr>
            <tr style="height:100px; background-color:#fff;color: #000;">
                <td># <?php echo $this->security->xss_clean($totaluser); ?></td>
                <td># <?php echo $this->security->xss_clean($newuser); ?></td>
                <td># <?php echo $this->security->xss_clean($lockuser); ?></td>
            </tr>
            <tr style="height:10px; background-color:#888;color: #fff;">
                <td>Total countries of origin</td>
                <td>Total countries where working</td>
                <td>Percent non-English users</td>
            </tr>
            <tr style="height:100px; background-color:#fff;color: #000;">
                <td># <?php echo $this->security->xss_clean($totalcountriesoforigin); ?></td>
                <td># <?php echo $this->security->xss_clean($totalcountrieswhereworking); ?></td>
                <td># <?php echo $this->security->xss_clean($nonenglishusers); ?></td>
            </tr>
            <tr style="height:10px; background-color:#888;color: #fff;">
                <td>Current active Check-ins</td>
                <td>Missed Check-ins past 24 hours</td>
                <td>Alerts past 24 hour</td>
            </tr>
            <tr style="height:100px; background-color:#fff;color: #000;">
                <td># <?php echo $this->security->xss_clean($activecheckins); ?></td>
                <td># <?php echo $this->security->xss_clean($missedcheckins); ?></td>
                <td># <?php echo $this->security->xss_clean($pastalerts); ?></td>
            </tr>
        </table>
    </div>
    <br/><br/><br/>

</div>

</body>
</html>
