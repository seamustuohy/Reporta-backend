<?php
/**
* @ignore
*/
?>
<html lang="en">


<body>
    <div>    </div>
    <div id="container">
       
       <h1>User </h1>
       
       
       <table border="3">
        <tr>
            <td>id</td>
            <td>username</td>
            <td>email</td>
            <td>firstname</td>
            <td>lastname</td>
            <td>last login time</td>
            
        </tr>
        <?php
        for($i = 0 ;$i < count($user);$i++)
            {  ?>
        <tr>
            <td><?php echo $user[$i]['id']; ?></td>
            <td><?php echo$user[$i]['username']; ?></td>
            <td><?php echo$user[$i]['email']; ?></td>
            <td><?php echo$user[$i]['firstname']; ?></td>
            <td><?php echo$user[$i]['lastname']; ?></td>
            <td><?php echo$user[$i]['last_login_time']; ?></td>
        </tr>
        <?php
    }?>
    
</table>


<h1>ALERT </h1>


<table border="3">
    <tr>
        <td>id</td>
        <td>username</td>
        <td>user_id</td>
        <td>situation</td>
        <td>description</td>
        
    </tr>
    <?php
    for($i = 0 ;$i < count($alert);$i++)
        {  ?>
    <tr>
        <td><?php echo $alert[$i]['id']; ?></td>
        <td><?php echo $alert[$i]['username']; ?></td>
        <td><?php echo $alert[$i]['user_id']; ?></td>
        <td><?php echo $alert[$i]['situation']; ?></td>
        <td><?php echo $alert[$i]['description']; ?></td>
        
    </tr>
    <?php
}?>

</table>


<h1>CHECKIN </h1>


<table border="3">
    <tr>
        <td>id</td>
        <td>username</td>
        <td>user_id</td>
        <td>location</td>
        <td>description</td>
        
    </tr>
    <?php
    for($i = 0 ;$i < count($checkin);$i++)
        {  ?>
    <tr>
        <td><?php echo $checkin[$i]['id']; ?></td>
        <td><?php echo $checkin[$i]['username']; ?></td>
        <td><?php echo $checkin[$i]['user_id']; ?></td>
        <td><?php echo $checkin[$i]['location']; ?></td>
        <td><?php echo $checkin[$i]['description']; ?></td>
        
    </tr>
    <?php
}?>

</table>


<h1>contactlist </h1>


<table border="3">
    <tr>
        <td>id</td>
        <td>listname</td>
        <td>user_id</td> 
    </tr>
    <?php
    for($i = 0 ;$i < count($contacts);$i++)
        {  ?>
    <tr>
        <td><?php echo $contacts[$i]['contactlist_id']; ?></td>
        <td><?php echo $contacts[$i]['listname']; ?></td>
        <td><?php echo $contacts[$i]['user_id']; ?></td>
        
    </tr>
    <?php
}?>

</table>


</div>

</body>
</html>
