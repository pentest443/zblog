<?php
	include "inc/header.php";
	include "inc/functions.php";
	include "inc/navbar.php";
?>

<?php 	$id = "";
		$username = "";
		$email = "";
		$excerpt = "";
		$tags = "";

	if($_SERVER['REQUEST_METHOD'] === 'POST') {
		if(isset($_POST['addpost'])) {

			$title = filter_input(INPUT_POST,'title' , FILTER_SANITIZE_STRING);
			$content = filter_input(INPUT_POST,'content' , FILTER_SANITIZE_STRING);
			$category = filter_input(INPUT_POST,'category' , FILTER_SANITIZE_STRING);
			$excerpt = filter_input(INPUT_POST,'excerpt' , FILTER_SANITIZE_STRING);
			$tags = filter_input(INPUT_POST,'tags' , FILTER_SANITIZE_STRING);

			$author = "Ebrahem"; // Temporary Author until creating admins

			date_default_timezone_set("Africa/Cairo");
			$datetime = date('M-d-Y h:m', time());

			$image = $_FILES['image'];

			$img_name = $image['name'];
			$img_tmp_name = $image['tmp_name'];
			$img_size = $image['size'];


			$error_msg = "";
			if(strlen($title) < 30 || strlen($title) > 200) {
				$error_msg = "Title must be between 30 and 200";
			}else if(strlen($content) < 500 || strlen($content) > 10000) {
				$error_msg = "Content must be between 500 and 10000";
			}else if(! empty($excerpt)){
				if(strlen($excerpt) < 50 || strlen($excerpt) > 500) {
					$error_msg = "Excerpt must be between 50 and 500";
				}
			}else {

				if(! empty($img_name)) { 
					$img_extension = strtolower(explode('.', $img_name)[1]); // gfdgdfg.jpg

					$allowed_extensions = array('jpg' , 'png' , 'jpeg');

					if(! in_array($img_extension, $allowed_extensions)) {
						$error_msg = "Allowed Extensions are jpg, png and jpeg ";
					}else if( $img_size > 1000000) {
						$error_msg = "Image size must be less than 1M";
					}
				}
			}

			if(empty($error_msg)) {
				if (! session_id()){
					session_start();
				}
				// Insert Data in Database
				if( insert_post($datetime, $title, $content, $author, $excerpt, $img_name, $category, $tags) ) {
					if(! empty($img_name)) {
						$new_path = "uploads/posts/".$img_name;
						move_uploaded_file( $img_tmp_name, $new_path);
					}
					$_SESSION['success'] = "Post has been Added Successfully";
					redirect("posts.php");
				}else {
					$_SESSION['error'] = "Unable to Add Post";
					redirect("posts.php");
				}
			}
		}else {

			if(isset($_POST['updatepost'])){

			$id = filter_input(INPUT_POST,'id' , FILTER_SANITIZE_NUMBER_INT);

			$title = filter_input(INPUT_POST,'title' , FILTER_SANITIZE_STRING);
			$content = filter_input(INPUT_POST,'content' , FILTER_SANITIZE_STRING);
			$category = filter_input(INPUT_POST,'category' , FILTER_SANITIZE_STRING);
			$excerpt = filter_input(INPUT_POST,'excerpt' , FILTER_SANITIZE_STRING);
			$tags = filter_input(INPUT_POST,'tags' , FILTER_SANITIZE_STRING);
			$image = $_FILES['image'];

			$img_name = $image['name'];
			$img_tmp_name = $image['tmp_name'];
			$img_size = $image['size'];


			$error_msg = "";
			if(strlen($title) < 30 || strlen($title) > 200) {
				$error_msg = "Title must be between 30 and 200";
			}else if(strlen($content) < 500 || strlen($content) > 10000) {
				$error_msg = "Content must be between 500 and 10000";
			}else if(! empty($excerpt)){
				if(strlen($excerpt) < 50 || strlen($excerpt) > 500) {
					$error_msg = "Excerpt must be between 50 and 500";
				}
			}else {

				if(! empty($img_name)) { 
					$img_extension = strtolower(explode('.', $img_name)[1]); // gfdgdfg.jpg

					$allowed_extensions = array('jpg' , 'png' , 'jpeg');

					if(! in_array($img_extension, $allowed_extensions)) {
						$error_msg = "Allowed Extensions are jpg, png and jpeg ";
					}else if( $img_size > 1000000) {
						$error_msg = "Image size must be less than 1M";
					}
				}
			}

			if(empty($error_msg)) {
				$updated = "";

				if(empty($image)) {
					$updated = update_post($title, $content, $excerpt,$category, $tags, $id);
				}else {
					$updated = update_post($title, $content, $excerpt,$img_name, $category, $tags, $id);
				}
				if($updated) {

					if(! session_id()){
						session_start();
					}
					if(! empty($img_name)) {
						$new_path = "uploads/posts/".$img_name;
						move_uploaded_file( $img_tmp_name, $new_path);
					}
					$_SESSION['success'] = "Post has been Updated Successfully";
					redirect("posts.php");
				}else {
					$_SESSION['error'] = "Unable to Update Post";
					redirect("posts.php");
				}
			}
			}
		}


	}else if(isset($_GET['id'])){

		$id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);

		$post = get_posts($id);

		$title = $post['title'];
		$content = $post['content'];
		$post_category_name = $post['category'];
		$excerpt = $post['excerpt'];
		$tags = $post['tags'];
	}

?>


<div class="container-fluid">
	<div class="row">
		<?php include "inc/media_sidebar.php"; ?>
		<div class="col-sm-2">
			<?php include "inc/sidebar.php"; ?>
		</div>
		<div class="col-sm">
			<div class="post">
				<?php if(isset($_GET['id'])) { ?>
				<h4>Edit Admin</h4>
			<?php }else {
				echo "<h4>Add New Admin</h4>";
			} ?>
				<form action="post.php" method="POST" enctype="multipart/form-data">
					<div class="form-group">
						<input type="hidden" name="id" value="<?php echo $id; ?>">
						<input value="<?php echo $username; ?>" class="form-control" type="text" name="username" placeholder="Username" required autocomplete="off" >
						<p class="error username-error">Username must be between 5 and 30 characters</p>
					</div>
					<div class="form-group">
						<input value="<?php echo $email; ?>" class="form-control" type="email" name="email" placeholder="Email" required autocomplete="off" >
						<p class="error email-error">Email must be between 10 and 100 characters</p>
					</div>
					<div class="form-group">
						<select class="form-control" name="roletype">
						<option value="Admin">Admin</option>
						<option value="Subscriber">Subscriber</option>
						</select>
					</div>

					<?php if(! empty($post['image'])){ ?>
						<label>Post Image: </label>
						<img width="100" src="uploads/posts/<?php echo $post['image'];?>">
					<?php } ?>

					<div class="form-group">
						<input type="file" name="image" class="form-control">
					</div>
					<?php if(isset($_GET['id'])) { ?>
						<input value="Update Admin" type="submit" name="updateadmin" class="btn btn-primary" style="float: right;">
					<?php }else { ?>
					<input value="Add Admin" type="submit" name="addadmin" class="btn btn-primary" style="float: right;">
					<?php } ?>
				</form>
			</div>
		</div>
	</div>
</div>


<?php include "inc/footer.php"; ?>