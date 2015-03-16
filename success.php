<?php  
	session_start();

?>

<html>
<head>
	<title>Wall Demo</title>
	<style type="text/css">
		h1 {
			border-bottom: 1px solid black;
		}
		.create_post textarea{
			width: 500px;
			height: 100px;
		}


	</style>
</head>
<body>
	<!-- display the users firstname -->
	<h1>Welcome <?= $_SESSION['user']['first_name'] ?></h1>
	<a href="process.php">Log out</a>
	<div class='create_post'>

		<!-- creating a new post -->
		<form action='process.php' method='post'>
			<h3>Submit Post</h3>
			<textarea name='post'></textarea>
			<input type='submit' value='Submit Post'>
			<input type='hidden' name='action' value='create_post'>
		</form>
	</div>

		<!-- displays all the posts we got from the get_posts function -->
<?php  
				foreach($_SESSION['posts'] as $post)
				{ ?>
					<div class='post'>

						<p><?= $post['post'] ?></p>

		<!-- displays all the comments we got from the get posts function -->
<?php  
				foreach($_SESSION['comments'] as $comment)
				{

		// if the post id is equal to the comment id.... display it here.... this is how we display the comments to its corresponding post
					if($post['id'] === $comment['post_id'])
					{ ?>	
						<p><?= $comment['first_name'] . ' ' . $comment['last_name'] . ' '. $comment['created_at'] ?> </p>
						<p><?= $comment['comment'] ?></p>
<?php			}

				}

?>
				<!-- creating a new comment -->
						<form action='process.php' method='post'>
							<h3> Create a comment</h3>
							<textarea name='comment'></textarea>
							<input type='submit' value='Submit Comment'>
							<input type='hidden' name='action' value='create_comment'>
							<input type='hidden' name='post_id' value='<?= $post['id'] ?>'>
						</form>
					</div>
<?php		}
?>

</body>
</html>