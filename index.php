<?php  
	session_start();
?>
<html>
<head>
	<title>Login</title>
</head>
<body>
<?php 
		//check to see if there are any errors. 
		if(isset($_SESSION['errors']))
		{
			//if there are errors... display them
			foreach($_SESSION['errors'] as $error)
			{
				echo "<p>". $error ."</p>";
			}
		}
		//unset the session errors so they only display once
		unset($_SESSION['errors']);

		//check to see if there are any success messages
		if(isset($_SESSION['success']))
		{
			//display the success messages
			echo $_SESSION['success'];
		}
		//unset the session success message so it only displays once
		unset($_SESSION['success']);

?>
	<div class='register'>
		<h1>Register</h1>
		<form action='process.php' method='post'>
			<p>First Name: <input type='text' name='first_name'></p>
			<p>Last Name: <input type='text' name='last_name'></p>
			<p>Email: <input type='email' name='email'></p>
			<p>Password: <input type='password' name='password'></p>
			<p>Confirm Passworld: <input type='password' name='confirm_password'></p>
			<p><input type='submit' value='Register'></p>
			<input type='hidden' name='action' value='register'>
		</form>
	</div>

	<div class='login'>
		<h1>Login</h1>
		<form action='process.php' method='post'>
			<p>Email: <input type='email' name='email'></p>
			<p>Password: <input type='password' name='password'></p>
			<p><input type='submit' value='Login'></p>
			<input type='hidden' name='action' value='login'>
		</form>
	</div>
</body>
</html>