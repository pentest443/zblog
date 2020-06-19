<?php $page_title = "Comments";
	include "inc/header.php";
	include "inc/functions.php";
	include "inc/navbar.php";
?>

<?php 	$id = "";
		if(! session_id()) {
			session_start();
		}
		$username = $_SESSION['admin_username'];
		$email = $_SESSION['admin_email'];
		$commenter_comment = "";
	
	if($_SERVER['REQUEST_METHOD'] === 'POST') {
		if(isset($_POST['addcomment'])) {

			$username = filter_input(INPUT_POST,'username' , FILTER_SANITIZE_STRING);
			$email = filter_input(INPUT_POST,'email' , FILTER_SANITIZE_STRING);
			$comment_comment = filter_input(INPUT_POST,'comment' , FILTER_SANITIZE_STRING);
			$post_id = filter_input(INPUT_POST,'post_id' , FILTER_SANITIZE_STRING);

			$author = "Ebrahem"; // Temporary Author until creating admins

			date_default_timezone_set("Africa/Cairo");
			$datetime = date('M-d-Y h:m', time());

			//$image = $_FILES['image'];

			$img_name = $image['name'];
			$img_tmp_name = $image['tmp_name'];
			$img_size = $image['size'];


			$error_msg = "";
			if(strlen($comment_comment) < 20 || strlen($commen_comment) > 1000) {
				$error_msg = "Comment must be between 50 and 500";
			}

			if(empty($error_msg)) {
				if (! session_id()){
					session_start();
				}
				// Insert Data in Database
				if( insert_comment($datetime, $username, $email, $comment_comment, $post_id)) {
					$_SESSION['success'] = "Comment has been Added Successfully";
					redirect("comments.php");
				}else {
					$_SESSION['error'] = "Unable to Add Comment";
					redirect("comment.php");
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
		<div class="col-sm-2">
			<?php include "inc/sidebar.php"; ?>
		</div>
		<div class="col-sm">
			<div class="comment">
				<?php if(isset($_GET['id'])) { ?>
				<h4>Edit Comment</h4>
			<?php }else {
				echo "<h4>Add New Comment</h4>";
			} ?>
				<form action="comment.php" method="POST">
					<div class="form-group">
						<input type="hidden" name="id" value="<?php echo $id; ?>">
						<input readonly value="<?php echo $username; ?>" class="form-control" type="text" name="username">
					</div>
					<div class="form-group">
						<input readonly value="<?php echo $email; ?>" class="form-control" type="email" name="email">
					</div>
					<div class="form-group">
						<textarea required placeholder="Comment" autocomplete="off" rows="6" name="comment" class="form-control" ><?php echo $commenter_comment; ?></textarea>
						<p class="error comment-error">Comment must be between 20 and 1000 characters</p>
					</div>
					<div class="form-group">
						<select class="form-control" name="post_id">
							<?php 
								foreach (get_posts() as $post) {
									echo "<option value='{$post['id']}' ";
								/*	if(isset($_GET['id'])) {
										if($post_category_name === $category['name']) {
											echo "selected >";
										}else {
											echo ">";
										}
									} else { */
										echo ">";
									//}
									echo $post['title'];
									echo "</option>";
								}
							?>
						</select>
					</div>
					<?php if(isset($_GET['id'])) { ?>
						<input value="Update Comment" type="submit" name="updatecomment" class="btn btn-primary" style="float: right;">
					<?php }else { ?>
					<input value="Add Comment" type="submit" name="addcomment" class="btn btn-primary" style="float: right;">
					<?php } ?>
				</form>
			</div>
		</div>
	</div>
</div>











<?php include "inc/footer.php"; ?>