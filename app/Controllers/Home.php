<?php namespace App\Controllers;

class Home extends BaseController
{
	//pag home
	public function index()
	{

		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		echo view('/inc/header');
		echo view('home');

	}

	//pag login
	public function login()
	{
		echo view('/inc/header');
		echo view('login');
	}

	//datos usuario
	public function user(){
		$okmail = 0;
		$okpass = 0;
		$okmailAdmin = 0;
		$okpassAdmin = 0;
		$products_names = array();

		$username = $_POST['uname'];
		$pass = $_POST['psw'];
		$encrypted_pass =  md5($pass);
		echo($encrypted_pass);

		//echo view('login');
		session_start();
		//validation
		// BD connection
		try
		{
			$db = \Config\Database::connect();

		}
			catch (\Exception $e)
		{
    		die($e->getMessage());
		}

		//Take information form table
		try
		{
			$query = $db->query('SELECT Email FROM user');
		}
			catch (\Exception $e)
		{
    		die($e->getMessage());
		}

		//Validation mail
		foreach ($query->getResult('array') as $row)
		{
			if (strcmp($row['Email'],$username)==0){
				$okmail =1;
			}
		}

		$query = $db->query('SELECT Password FROM user');

		//validation password
		foreach ($query->getResult('array') as $row)
		{
			if (strcmp($row['Password'], $encrypted_pass)==0){
				$okpass =1;
			}

		}
		
		if (($okmail==1) && ($okpass == 1)){

			if(strcmp($username, "admin@gmail.com") == 0){

				$sql = "SELECT Name FROM user WHERE Email = ?";
				$query = $db->query($sql, $username);

				foreach ($query->getResult('array') as $row)
				{
					
					$nom = $row['Name'];

				}

				$_SESSION['name'] = $nom;
				$_SESSION['email'] = $username;

				echo view("/inc/header");
				echo view("admin");
			}else{

				//$session = session();

				$sql = "SELECT Name FROM user WHERE Email = ?";
				$query = $db->query($sql, $username);

				foreach ($query->getResult('array') as $row)
				{
					
					$nom = $row['Name'];

				}
				
				$_SESSION['name'] = $nom;
				$_SESSION['email'] = $username;

				//GET USER DATA 
				$sql = "SELECT * FROM user WHERE Email = ?";
				$query = $db->query($sql, $username);

				foreach ($query->getResult('array') as $row)
				{
					$age = $row['Age'];
					$surname = $row['Surname'];
				}
				
				$data['age'] = $age;
				$data['surname'] = $surname;	
				
				//Wishlist
			$sql = "SELECT Items FROM wishlist WHERE UserMail = ?";
			$query = $db->query($sql, $_SESSION['email']);

			foreach ($query->getResult('array') as $row)
			{	
				$items = $row['Items'];
			}

			$array_items = str_split($items);
//HERE
			foreach ($array_items as $char) {
				if($char != ','){
					$sql = "SELECT Name FROM product WHERE Img = ?";
					$query = $db->query($sql, $char);
					foreach ($query->getResult('array') as $row)
					{	
						array_push($products_names, $row['Name']);
					}
				}
			}
			
			$data['products_names'] = $products_names;

				echo view('/inc/header');
				echo view('profile', $data);
			}
		}else{
			echo view('/inc/header');
			echo view('login');	
		}

	}

	//nuevo usuario
	public function signin(){
		echo view('/inc/header');
		echo view('singin');

	}

	//pasar datos
	public function newuser(){
		$uname = $_POST['uname'];
		$sname = $_POST['sname'];
		$age = $_POST['age'];
		$mail = $_POST['mail'];
		$psw = $_POST['psw'];
		$exists = 0;
		$encrypted_pass;


		//validation
		try
		{
			$db = \Config\Database::connect();
	
		}
			catch (\Exception $e)
		{
			die($e->getMessage());
		}


		$query = $db->query("SELECT Email FROM user");

		foreach ($query->getResult('array') as $row)
		{
				
			if (strcmp($row['Email'], $mail) == 0){
				$exists = 1;
			}

		}
		
		if ($exists != 1){

			//SESSION
			
			$_SESSION['name'] = $uname;
			$_SESSION['email'] = $mail;
			
			$encrypted_pass = md5($psw);

			//Registro
			$query = $db->query("INSERT INTO user (Name, Surname, Age, Email, Password) VALUES ('$uname', '$sname', '$age', '$mail', '$encrypted_pass')");

			//Create de wishlist
			$query = $db->query("INSERT INTO wishlist (UserMail) VALUES ('$mail')");

			//Create de friendlist
			$query = $db->query("INSERT INTO friendlist (Owner) VALUES ('$mail')");
			
			echo view('/inc/header');
			echo view('home');
		}else{
			echo view('/inc/header');
			echo view('singin');
		}

	}

	//llamar pag products
	public function products(){

		$products = array();

		try
		{
			$db = \Config\Database::connect();
	
		}
			catch (\Exception $e)
		{
			die($e->getMessage());
		}

		$query = $db->query("SELECT Img FROM product");

		foreach ($query->getResult('array') as $row)
		{
				
			array_push($products, $row['Img']);

		}

		$data['products'] = $products;
		
		echo view('/inc/header');
		echo view('products', $data);

	}

	//llamar pag product
	public function product(){

		$product_info = array();
	
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		$img_id = substr($actual_link, -1);

		try
		{
			$db = \Config\Database::connect();
	
		}
			catch (\Exception $e)
		{
			die($e->getMessage());
		}

		$sql = "SELECT * FROM product WHERE Img = ?";
		$query = $db->query($sql, $img_id);

		foreach ($query->getResult('array') as $row)
		{
			
			array_push($product_info, $row['Name'] );
			array_push($product_info, $row['Price'] );
			array_push($product_info, $row['Description'] );
			array_push($product_info, $row['Link'] );
	
		}
		$img_url = "../../public/Assets/img/".$img_id.".jpg";
		array_push($product_info,$img_url );
		$data['product_info'] = $product_info;

		echo view('/inc/header');
		echo view('product', $data);

	}

	//logout
	public function logout(){
		session_destroy();
		unset($_SESSION['name']);
		unset($_SESSION['email']);
		echo view('/inc/header');
		echo view('home');
	}

	//profile
	public function profile(){
		$products_names = array();
		$items;
		$img_id;

		try
		{
			$db = \Config\Database::connect();
	
		}
			catch (\Exception $e)
		{
			die($e->getMessage());
		}

		//Data Username
		$sql = "SELECT * FROM user WHERE Email = ?";
		$query = $db->query($sql, $_SESSION['email']);

		if($_SESSION['email'] == "admin@gmail.com"){
			echo view('/inc/header');
			echo view('admin');
		}else{
			foreach ($query->getResult('array') as $row)
			{
				$age = $row['Age'];
				$surname = $row['Surname'];
			}

			//Wishlist
			$sql = "SELECT Items FROM wishlist WHERE UserMail = ?";
			$query = $db->query($sql, $_SESSION['email']);

			foreach ($query->getResult('array') as $row)
			{	
				$items = $row['Items'];
			}

			$array_items = str_split($items);

			foreach ($array_items as $char) {
				if($char != ','){
					$sql = "SELECT Name FROM product WHERE Img = ?";
					$query = $db->query($sql, $char);
					foreach ($query->getResult('array') as $row)
					{	
						array_push($products_names, $row['Name']);
					}
				}
			}
			
			$data['products_names'] = $products_names;
			$data['age'] = $age;
			$data['surname'] = $surname;
			echo view('/inc/header');
			echo view('profile', $data);
		}

	}

	public function admin(){
		if (strcmp($_SESSION['email'], "admin@gmail.com") == 0){
			$data['ok'] = "ok";
			echo view('/inc/header');
			echo view('admin');
		}else{
			$data['ok'] = "ko";
			echo view('/inc/header');
			echo view('login',$data);
		}

		
	}

	//Function add product
	public function addProduct (){
		$pname = $_POST['pname'];
		$price = $_POST['price'];
		$description = $_POST['description'];
		$link = $_POST['link'];
		$imgnumber = $_POST['imgnumber'];
		$exists = 0;

		//validation
		try
		{
			$db = \Config\Database::connect();
			
		}
			catch (\Exception $e)
		{
			die($e->getMessage());
		}

		$query = $db->query("SELECT Img FROM product");
		
		foreach ($query->getResult('array') as $row)
		{			
			if (strcmp($row['Img'], $imgnumber) == 0){
				$exists = 1;
			}

		}

		if ($exists != 1){

			//Registro
			$query = $db->query("INSERT INTO product (Name, Price, Description, Link, Img) VALUES ('$pname', '$price', '$description', '$link', '$imgnumber')");

			
			
			echo view('/inc/header');
			echo view('admin');
		}else{
			echo view('/inc/header');
			echo view('home');
		}

	}

	//Function remove Product
	public function removeProduct(){
		$imgnumber = $_POST['imgnumber'];
		$exists = 0;

		//validation
		try
		{
			$db = \Config\Database::connect();
			
		}
			catch (\Exception $e)
		{
			die($e->getMessage());
		}

		$query = $db->query("SELECT Img FROM product");

		foreach ($query->getResult('array') as $row)
		{			
			if (strcmp($row['Img'], $imgnumber) == 0){
				$exists = 1;
			}

		}

		if ($exists != 1){

			$data['info'] = "noexist";
			echo view('/inc/header');
			echo view('admin', $data);

		}else{
			//Registro
			$query = $db->query("DELETE FROM product WHERE Img = $imgnumber");
			

			$data['info'] = "ok";
			echo view('/inc/header');
			echo view('admin', $data);
		}
		
	}

	//Delete user
	public function removeUser(){
		$mail = $_POST['mail'];
		$exists = 0;

		//validation
		try
		{
			$db = \Config\Database::connect();
			
		}
			catch (\Exception $e)
		{
			die($e->getMessage());
		}

		$query = $db->query("SELECT Email FROM user");

		foreach ($query->getResult('array') as $row)
		{			
			if (strcmp($row['Email'], $mail) == 0){
				$exists = 1;
			}

		}

		if ($exists != 1){

			if (empty($mail)){
				$data['info'] = "noexist";	
			}
			$data['info'] = "nodata";
			echo view('/inc/header');
			echo view('admin', $data);
			

		}else{
			//Registro
			
			$sql = "DELETE FROM user WHERE Email = ?";
			$query = $db->query($sql, $mail);
			
			$data['info'] = "ok";

			echo view('/inc/header');
			echo view('admin', $data);
		}
	}

	public function wish(){

		$items;

		$products = array();
	
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		$img_id = substr($actual_link, -1);

		$exists = 0;

		try
		{
			$db = \Config\Database::connect();
	
		}
			catch (\Exception $e)
		{
			die($e->getMessage());
		}

		$sql = "SELECT Items FROM wishlist WHERE UserMail = ?"; 

		$query = $db->query($sql, $_SESSION['email']);

		foreach ($query->getResult('array') as $row)
		{	
			
			$items = $row['Items'];
		}

		$array_items = str_split($items);

		foreach ($array_items as $char) {
			if ($char == $img_id){
				$exists = 1;
			}
		}

		if ($exists == 1){

			$products = array();

			try
			{
				$db = \Config\Database::connect();
		
			}
				catch (\Exception $e)
			{
				die($e->getMessage());
			}

			$query = $db->query("SELECT Img FROM product");

			foreach ($query->getResult('array') as $row)
			{
					
				array_push($products, $row['Img']);

			}

			$data['products'] = $products;
			$data['info'] = "exists";
			echo view('/inc/header');
			echo view('products', $data);

		}else{
			$items = $items.",".$img_id;

			$products = array();

			try
			{
				$db = \Config\Database::connect();
		
			}
				catch (\Exception $e)
			{
				die($e->getMessage());
			}

			$query = $db->query("SELECT Img FROM product");

			foreach ($query->getResult('array') as $row)
			{
					
				array_push($products, $row['Img']);

			}

			echo "Items:". $items;
			$sql = ("UPDATE wishlist SET Items='$items' WHERE UserMail=?"); 
			$query = $db->query($sql, $_SESSION['email']);

			$data['products'] = $products;
			$data['info'] = "ok";
			echo view('/inc/header');
			echo view('products', $data);
		}
		
	}

	public function profiles(){

		$user_to_search = $_POST['user_to_search'];
		$users = array();
		$mails = array();

		try
		{
			$db = \Config\Database::connect();
	
		}
			catch (\Exception $e)
		{
			die($e->getMessage());
		}

		$sql = "SELECT Name FROM user WHERE Name LIKE ?"; 

		$query = $db->query($sql, $user_to_search."%");

		foreach ($query->getResult('array') as $row)
		{
				
			array_push($users, $row['Name']);

		}

		foreach ($users as $user){
			$sql = "SELECT Email FROM user WHERE Name = ?"; 
			$query = $db->query($sql, $user);
			foreach ($query->getResult('array') as $row)
			{
				
			array_push($mails, $row['Email']);

			}

		}


		//$sql = "SELECT Name FROM user WHERE Name = ?"; 

		//$query = $db->query($sql, $user_to_search);
		$data['users'] = $users;
		$data['mails'] = $mails;
		
		echo view('inc/header');
		echo view('profiles', $data);



	}

	public function addUser(){
		$array_friends=array();
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$exists = 0;

		//$user_to_follow = substr($actual_link, -1);

		$pos = strpos($actual_link, "=");
		$len = strlen($actual_link);

		$pos++;
		$subr = $len - $pos;

		$user_to_follow = substr($actual_link, -$subr);

		try
		{
			$db = \Config\Database::connect();
	
		}
			catch (\Exception $e)
		{
			die($e->getMessage());
		}


		$sql = "SELECT FriendNames FROM friendlist WHERE Owner = ?";
		$query = $db->query($sql, $_SESSION['email']);

		foreach ($query->getResult('array') as $row)
		{
				
			$friends = $row['FriendNames'];

		}

		$array_friends = str_split($friends);

		foreach ($array_friends as $char) {
			if($char != ','){
				$friend = $friend.$char;
			}else{
				if (strcmp($friend, $user_to_follow) ==0){
					$exists = 1;
				}
				$friend="";
			}
		}

		if ($exists ==1){
			$user_to_search = $_POST['user_to_search'];
			$users = array();
			$mails = array();

			try
			{
				$db = \Config\Database::connect();
		
			}
				catch (\Exception $e)
			{
				die($e->getMessage());
			}

			$sql = "SELECT Name FROM user WHERE Name LIKE ?"; 

			$query = $db->query($sql, $user_to_search."%");

			foreach ($query->getResult('array') as $row)
			{
					
				array_push($users, $row['Name']);

			}

			foreach ($users as $user){
				$sql = "SELECT Email FROM user WHERE Name = ?"; 
				$query = $db->query($sql, $user);
				foreach ($query->getResult('array') as $row)
				{
					
				array_push($mails, $row['Email']);

				}

			}


			//$sql = "SELECT Name FROM user WHERE Name = ?"; 

			//$query = $db->query($sql, $user_to_search);
			$data['users'] = $users;
			$data['mails'] = $mails;
			$data['info'] = "exists";
			
			echo view('inc/header');
			echo view('profiles', $data); 
		}else{
			$friends = $friends.$user_to_follow.",";

			$sql = ("UPDATE friendlist SET FriendNames='$friends' WHERE Owner=?"); 
			$query = $db->query($sql, $_SESSION['email']);
			$user_to_search = $_POST['user_to_search'];
			$users = array();
			$mails = array();

			try
			{
				$db = \Config\Database::connect();
		
			}
				catch (\Exception $e)
			{
				die($e->getMessage());
			}

			$sql = "SELECT Name FROM user WHERE Name LIKE ?"; 

			$query = $db->query($sql, $user_to_search."%");

			foreach ($query->getResult('array') as $row)
			{
					
				array_push($users, $row['Name']);

			}

			foreach ($users as $user){
				$sql = "SELECT Email FROM user WHERE Name = ?"; 
				$query = $db->query($sql, $user);
				foreach ($query->getResult('array') as $row)
				{
					
				array_push($mails, $row['Email']);

				}

			}


			//$sql = "SELECT Name FROM user WHERE Name = ?"; 

			//$query = $db->query($sql, $user_to_search);
			$data['users'] = $users;
			$data['mails'] = $mails;
			$data['info'] = "exists";

			$data['info'] = "newuser";

			echo view('inc/header');
			echo view('profiles',$data);

		}

	}

	public function friendlist(){

		$friends_list = array();
		$names = array();
		try
		{
			$db = \Config\Database::connect();
	
		}
			catch (\Exception $e)
		{
			die($e->getMessage());
		}

		$sql = "SELECT FriendNames FROM friendlist WHERE Owner = ?"; 
		$query = $db->query($sql, $_SESSION['email']);
		
		foreach ($query->getResult('array') as $row)
		{
				
			$friends = $row['FriendNames'];

		}
		
		$array_friends = str_split($friends);
		
		foreach ($array_friends as $char) {
			if($char != ','){
				$friend = $friend.$char;
			}else{
				array_push($friends_list, $friend);
				$friend = "";
			}
		}
		
		foreach ($friends_list as $friend){
			$sql = "SELECT Name FROM user WHERE Email = ?"; 
			$query = $db->query($sql, $friend);
			foreach ($query->getResult('array') as $row)
			{
				
				array_push($names, $row['Name']);

			}

		}

		
		$data['mails'] = $friends_list;
		$data['names'] = $names;


		echo view('inc/header');
		echo view('friendlist',$data);
	}

	public function viewUser(){
		$product_ids = array();
		$product_name = array();

		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		$pos = strpos($actual_link, "=");
		$len = strlen($actual_link);

		$pos++;
		$subr = $len - $pos;

		$user_to_view = substr($actual_link, -$subr);


		try
		{
			$db = \Config\Database::connect();
	
		}
			catch (\Exception $e)
		{
			die($e->getMessage());
		}

		$sql = "SELECT Items FROM wishlist WHERE UserMail = ?"; 
		$query = $db->query($sql, $user_to_view);
		
		foreach ($query->getResult('array') as $row)
		{
				
			$items = $row['Items'];

		}
		$array_items = str_split($items);

		foreach ($array_items as $char) {
			if($char != ','){
				array_push($product_ids, $char);
			}
		}
		foreach ($product_ids as $id) {
			$sql = "SELECT Name FROM product WHERE Img = ?"; 
			$query = $db->query($sql, $id);
		
			foreach ($query->getResult('array') as $row)
			{	
				
				array_push($product_name, $row['Name']);
				

			}
		}
		$data['image_id']  = $product_ids;
		$data['names'] = $product_name;
		$data['friend'] = $user_to_view;

		var_dump($product_ids);
		var_dump($product_name);



		echo view('inc/header');
		echo view('friendProfile', $data);





	}

	
	//--------------------------------------------------------------------

}
