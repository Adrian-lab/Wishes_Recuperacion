<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>td{padding-bottom: 15px; padding-top: 20px;}</style>
</head>
<body>
    <br><br><br>
<table style="width:50%; margin-left:auto; margin-right:auto;">
  <tr>
    <th>Name</th>
    <th>Email</th>
    <th>View</th>

  </tr>
  <?php foreach( $names as $index => $name ) {
      ?>
        <tr>
            <td><?php echo $name?></td>
            <td><?php echo $mails[$index]?></td>
            <td><a style="margin-top: 10px; border: none; background-color: black; color: white; padding: 8px 20px 8px 20px;" href="http://labs.iam.cat/~a17adrposmon/wishes/public/home/viewUser?arg=<?php echo $mails[$index]?>">View</a></td>
        </tr>
      <?php

  }?>
</table>
</body>
</html>