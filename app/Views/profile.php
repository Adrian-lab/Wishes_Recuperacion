<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body>

    <br><br><br><br><br>
    <div class="col-sm-12">
        <h1>Your profile</h1>
        <a style="margin-left: 80%; margin-top: 10px; border: none; background-color: black; color: white; padding: 8px 20px 8px 20px;" href="http://labs.iam.cat/~a17adrposmon/wishes/public/home/friendlist">Friend List</a>
        <hr>
        <div class="col-sm-8">
            <div class="col-sm-6">
                <a>Name: <?php echo $_SESSION["name"]; ?></a><br>
                <a>Surname: <?php echo $surname; ?></a><br>
                <a>Age: <?php echo $age; ?></a><br>
                <a>Email: <?php echo $_SESSION["email"]; ?></a>
            </div>  
        </div>
        <br><br>
        <div class="col-sm-6">
            <h2>Wishlist Products</h2>
            <hr>
            <?php foreach ($products_names as $row) {
            ?>
                <a>Wish: <?php echo $row; ?></a><br>
            <?php
            }
            ?>
        </div>
        <br><br>
       

    </div>

    <script>
        function addProduct() {
            if(document.getElementById("createWishlist").style.display = "none"){
                document.getElementById("createWishlist").style.display = "block";
            }else{
                document.getElementById("createWishlist").style.display = "none";   
            }
        }
    </script>
</body>
</html>