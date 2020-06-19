<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>

    <style>


        body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        }

        .header {
        text-align: center;
        padding: 32px;
        }

        .row {
        display: -ms-flexbox; /* IE 10 */
        display: flex;
        -ms-flex-wrap: wrap; /* IE 10 */
        flex-wrap: wrap;
        padding: 0 4px;
        }

        .column {
        -ms-flex: 50%; /* IE 10 */
        flex: 50%;
        padding: 0 4px;
        }

        .column img {
        margin-top: 8px;
        vertical-align: middle;
        }

        /* Style the buttons */
        .btn {
        border: none;
        outline: none;
        padding: 10px 16px;
        background-color: #f1f1f1;
        cursor: pointer;
        font-size: 18px;
        }

        .btn:hover {
        background-color: #ddd;
        }

        .btn.active {
        background-color: #666;
        color: white;
        }

/* Container needed to position the button. Adjust the width as needed */
.container {
  position: relative;
  width: 50%;
}

/* Style the button and place it in the middle of the container/image */
.container .btn {
  position: relative;
  top: 50%;
  left: 35%;
  transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  background-color: #555;
  color: white;
  font-size: 16px;
  padding: 12px 24px;
  border: none;
  cursor: pointer;
  border-radius: 5px;
}

.container .btn:hover {
  background-color: black;
}
</style>

</head>
<body>

<br><br><br>
    <!-- Header -->
<div class="header" id="myHeader">
  <h1><?php echo $friend?> Wishlist</h1>
  <hr>
</div>

<div class="row"> 
  <div class="column">
   
    <?php
    foreach ($names as $index=>$name){
    ?>
    <div class="container col-sm-12">
        <img src="http://labs.iam.cat/~a17adrposmon/wishes/public/Assets/img/<?php echo $image_id[$index]?>.jpg" alt="Imagen producto" style="width:50%; border-style:solid; border-color:grey;">
        <p><?php echo $name?></p>
    </div>
    <?php
    }
    ?>
    
  </div>  

</div>

</body>
</html>