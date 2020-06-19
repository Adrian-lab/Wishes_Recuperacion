<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <br><br><br>
<?php 
    if(strcmp($info, "exists") ==0){
        ?><div class="warning" style="width:100%; height:20px; background-color:orange; position:absolute; top:61px;">
        <p style="padding-left:10px;">This user is already your firend, check te users list to add friends</p>
      </div><?php 
    }else{
        if (strcmp($info, "newuser") ==0){
            ?><div class="warning" style="width:100%; height:20px; background-color:green; position:absolute; top:61px;">
            <p style="padding-left:10px;">New Friend added! Check te user list to add more friends!</p>
          </div><?php    
        }
    }?>
<table style="width:50%; margin-left:auto; margin-right:auto;">
  <tr>
    <th>Name</th>
    <th>Email</th>
    <th>Add</th>
  </tr>
  <?php foreach( $users as $index => $user ) {
      ?>
        <tr>
            <td><?php echo $user?></td>
            <td><?php echo $mails[$index]?></td>
            <td><a style="margin-top: 10px; border: none; background-color: black; color: white; padding: 8px 20px 8px 20px;" href="http://labs.iam.cat/~a17adrposmon/wishes/public/home/addUser?arg=<?php echo $mails[$index]?>">Add User</a></td>
        </tr>
      <?php

  }?>
</table>
    
</body>
</html>