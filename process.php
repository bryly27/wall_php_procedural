<?php  
	session_start();
	//need the connection to connect with the database information
	require('connection.php');

	//if the action button submitted has the value register
	if(isset($_POST['action']) && $_POST['action'] == 'register')
	{
		//use the register function
		register_user($_POST);
	}
	//if the action button submitted has the value login
	elseif(isset($_POST['action']) && $_POST['action'] == 'login')
	{
		//use the login function
		login_user($_POST);
	}
	//if the action button submitted has the value create_post
	elseif(isset($_POST['action']) && $_POST['action'] == 'create_post')
	{
		//use the create_post function
		create_post($_POST);
	}
	//if the action button submitted has the value create_comment
	elseif(isset($_POST['action']) && $_POST['action'] == 'create_comment')
	{
		//use the create_commment function
		create_comment($_POST);
	}
	//if any one tries to enter the process.php page through the url, redirect them to the main page and destroy the whole session. This is what hackers would try to do
	else
	{
		header('location: index.php');
		session_destroy();
		die();
	}


	function register_user($post)
	{
		//check to see if the first name input is empty
		if(empty($post['first_name']))
		{
			//if it is.... push the message into an array so it can be displayed on the index page.
			$_SESSION['errors'][] = 'First name cannot be empty';
		}
		//check to see if the last name input is empty
		if(empty($post['last_name']))
		{
			//if it is.... push the message into an array so it can be displayed on the index page.
			$_SESSION['errors'][] = 'Last name cannot be empty';
		}
		//check to see if the email input is empty and also if the email info entered is valid
		if(empty($post['email']) || (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)))
		{
			//if it is.... push the message into an array so it can be displayed on the index page.
			$_SESSION['errors'][] = 'Email must be valid';
		}
		//check to see if the password input is empty
		if(empty($post['password']))
		{
			//if it is.... push the message into an array so it can be displayed on the index page.
			$_SESSION['errors'][] = 'Password cannot be empty';
		}
		//check to see if the password input and the confirm_password input match
		if($post['password'] !== $post['confirm_password'])
		{
			//if it does not match push the message into an array so it can be displayed on the index page.
			$_SESSION['errors'][] = 'Passwords must match';
		}

		//checks if there are any errors... if the number of errors are greater than zero
		if(isset($_SESSION['errors']) && count($_SESSION['errors']) > 0)
		{
			//redirect them to index
			header('location: index.php');
			die();
		}
		else
		{
			//save all post data to a variable
			$first_name = escape_this_string($post['first_name']);
			$last_name = escape_this_string($post['last_name']);
			$email = escape_this_string($post['email']);
			$password = md5(escape_this_string($post['password']));

			//insert the $post info to the database and redirect them to the index page with the success message
			$query = ("INSERT INTO users (first_name, last_name, email, password, created_at, updated_at) VALUES ('{$first_name}', '{$last_name}', '{$email}', '{$password}', NOW(), NOW())");
			run_mysql_query($query);
			$_SESSION['success'] = 'You are now registered';
			header('location: index.php');
			die();
		}

	}

	function login_user($post)
	{
		//save $post info to a variable
		$email = escape_this_string($post['email']);
		$password = md5(escape_this_string($post['password']));

		//run the query to select the email and password that matches the $post info
		$query = ("SELECT * FROM users WHERE email = '{$email}' AND password = '{$password}'");
		$user = fetch_record($query);

		//if you get a result
		if(count($user) > 0)
		{
			//save the info to a session variable
			$_SESSION['user'] = $user;
			//run a function that gets all the posts so it can be displayed on the success page
			get_posts();
			//redirect them to the success page
			header('location: success.php');
			die();
		}
		else
		{
			//if there are errors, send them a message and redirect them back to the index page
			$_SESSION['errors'][] = 'You entered bad info';
			header('location: index.php');
			die();
		}
	}

	function create_post($post)
	{
		//running a query that inserts the #post data into the database
		$query = ("INSERT INTO posts (post, created_at, updated_at, user_id) VALUES ('{$post['post']}', NOW(), NOW(), '{$_SESSION['user']['id']}') ");
		run_mysql_query($query);
		//run the function get_posts so it will update the success page with new info
		get_posts();
		//redirect to success page
		header('location: success.php');
		die();
	}

	function create_comment($post)
	{	
		//running a query that inserts the $post data into the database
		$query = ("INSERT INTO comments (comment, created_at, updated_at, user_id, post_id) VALUES ('{$post['comment']}', NOW(), NOW(), '{$_SESSION['user']['id']}', '{$post['post_id']}')");
		run_mysql_query($query);
		//run the function get_posts so it will update the success page with new info
		get_posts();
		//redirect to success page
		header('location: success.php');
		die();
	}

	function get_posts()
	{
		//this is a query that gets all the posts
		$query = ("SELECT posts.*, users.first_name, users.last_name FROM posts LEFT JOIN users ON users.id = posts.user_id ORDER BY id DESC");
		$_SESSION['posts'] = fetch_all($query);


		//this is a query to get all the comments 
		$query = ("SELECT comments.*, users.first_name, users.last_name FROM comments LEFT JOIN users on users.id = comments.user_id");
		$_SESSION['comments'] = fetch_all($query);
	}

?>