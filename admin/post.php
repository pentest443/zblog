<?php 
	include "inc/header.php";
	include "inc/navbar.php";
	include "inc/functions.php";
?>

<?php 

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(isset($_POST['addpost'])) {
	
			$title = filter_input(INPUT_POST, 'title' , FILTER_SANITIZE_STRING);
			$content = filter_input(INPUT_POST, 'content' , FILTER_SANITIZE_STRING);
			$category = filter_input(INPUT_POST, 'category' , FILTER_SANITIZE_STRING);
			$excerpt = filter_input(INPUT_POST, 'excerpt' , FILTER_SANITIZE_STRING);
			$tags = filter_input(INPUT_POST, 'tags' , FILTER_SANITIZE_STRING);

			$author = "Ebrahem"; // Temporary Author until creating admins

			date_default_timezone_set("Asia/Riyadh");
			$datetime = date('M-D-Y h:m', time());

			$image = $_FILES['image'];

			$img_name = $image['name'];
			$img_tmp_name = $image['tmp_name'];
			$img_size = $image['size'];


			$error_msg = "";
			if(strlen($title) < 100 || strlen($title) > 200) {
				$error_msg = "Title must be between 100 and 200";			
			}else if (strlen($content) < 500 || strlen($content) > 10000) {
				$error_msg = "Content must be between 500 and 10000";
			}else if(! empty($excerpt)){
				if (strlen($excerpt) < 100 || strlen($excerpt) > 500) {
				$error_msg = "Content must be between 100 and 500";
				}
			}else {

				if(! empty($img_name)) {
					$img_extension = strtolower(explode('.', $img_name)[1]); 

					$allowed_extension = array('jpg' , 'png' , 'jpeg');

					if(! in_array($img_extension, $allowed_extension)) {
						$error_msg = "Allowed extension are jpg, png and jepg";
					}else if( $img_size > 2000000) {
						$error_msg = "Image size must be less than 1M";
					}

				}

			}

			if(empty($error_msg)) {

				// Insert Data in Database
				if( insert_post($datetime, $title, $content, $author, $excerpt, $img_name, $category, $tags) ) {

					if(! empty($img_name)) {
						$new_path = "uploads/posts/".$img_name;
						move_uploaded_file( $img_tmp_name, $new_path);
					}
					echo "Success";
				}

			} else {
				echo $error_msg;
			}



	}
}	


?>


<div class="container-fluid">
	<div class="row">
		<div class="col-sm-2">
			<?php include "inc/sidebar.php"; ?>
		</div>
		<div class="col-sm">
			<div class="post">
				<h3>Add New Post</h3>
				<form action="post.php" method="POST" enctype="multipart/form-data">
					<div class="form-group">
						<input class="form-control" type="text" name="title" placeholder="Title" required autocomplete="off" >
						<p class="error title-error">Title must be between 100 and 200 characters</p>
					</div>
					<div class="form-group">
						<textarea required placeholder="Content" autocomplete="off" rows="6" name="content" class="form-control" ></textarea>
						<p class="error content-error">Content must be between 500 and 10000 characters</p>
					</div>
					<div class="form-group">
						<select class="form-control" name="category">
							<?php 
								foreach (get_categories() as $category) {
									echo "<option>";
									echo $category['name'];
									echo "</option>";
								}
							?>
						</select>
					</div>
					<div class="form-group">
						<input class="form-control" type="text" name="excerpt" autocomplete="off" placeholder="Excepert ( optional )">
						<p class="error excerpt-error">Excerpt must be between 100 and 500 characters</p>
					</div>
					<div class="form-group">
						<input class="form-control" type="text" name="tags" autocomplete="off" placeholder="Tags">
					</div>
					<div class="form-group">
						<input type="file" name="image" class="form-control">
					</div>
					<input value="Add Post" type="submit" name="addpost" class="btn btn-primary" style="float: right;">
				</form>
			</div>
		</div>
	</div>
</div>











<?php include "inc/footer.php"; ?>