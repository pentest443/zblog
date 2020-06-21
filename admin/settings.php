<?php $page_title = "Settings | ZBlog";?>
<?php include "inc/header.php";  ?>
<?php include "inc/functions.php";  ?>
<?php include "inc/navbar.php";  ?>

<?php 
	foreach (get_settings() as $setting) {
		$name = $setting['name'];
		$tagline = $setting['tagline'];

		$logo = $setting['logo'];

		$hpn = $setting['home_posts_number'];
		$posts_order = $setting['posts_order'];
		$rpn = $setting['recent_posts_number'];
		$relatedpn = $setting['related_posts_number'];
	}
?>

<div class="container-fluid">
 	<div class="row">
    	<div class="col-sm-2">
      		<?php include "inc/sidebar.php"; ?>
    </div>
    <div class="col-sm">
		<div class="settings">

			<div class="general-settings">

					<h4>General Settings</h4>
					<form action="settings.php" method="POST" enctype="multipart/form-data">
						<div class="row">
							<div class="col-sm-2">
								<label>Site Name : </label>
							</div>
							<div class="col-sm-6">
								<div class="from-group">
									<input value="<?php echo $name; ?>" type="text" name="name" class="form-control" placeholder="Site Name" required autocomplete="off">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-2">
								<label>Site Tagline : </label>
							</div>
							<div class="col-sm-6">
								<div class="from-group">
									<input value="<?php echo $tagline; ?>" type="text" name="tagline" class="form-control" placeholder="Site Tagline" required autocomplete="off">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-2">
								<label>Site Logo : </label>
							</div>
							<div class="col-sm-6">
								<div class="from-group">
									<img class="logo" width="200" height="200" src="uploads/<?php echo $logo; ?>">
									<input type="file" name="logo" class="form-control">
									<input style="float: right;" class="btn btn-info" type="submit" name="save-posts" value="Save Changes">
								</div>
							</div>
						</div>
					</form>
				</div>
				<hr>
				<h4>Posts Settings</h4>
					<form action="settings.php" method="POST">
						<div class="row">
							<div class="col-sm-2">
								<label>Home Posts Number : </label>
							</div>
							<div class="col-sm-6">
								<div class="from-group">
									<input value="<?php echo $hpn; ?>" min="2" max="20" type="number" name="hpn" class="form-control">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-2">
								<label>Posts Order : </label>
							</div>
							<div class="col-sm-6">
								<div class="from-group">
									<select class="form-control" name="posts_order">
										<?php 
											if($posts_order === "newest") {
												echo "<option value='newest' selected >Newest";
												echo "</option>";
												echo "<option value='oldest'>Oldest";
												echo "</option>";
											}else {
												echo "<option value='newest' >Newest";
												echo "</option>";
												echo "<option value='oldest' selected>Oldest";
												echo "</option>";
											}
										?>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-2">
								<label>Recent Posts Number : </label>
							</div>
							<div class="col-sm-6">
								<div class="from-group">
									<input  value="<?php echo $rpn; ?>" min="2" max="10" type="number" name="rpn" class="form-control">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-2">
								<label>Related Posts Number : </label>
							</div>
							<div class="col-sm-6">
								<div class="from-group">
									<input value="<?php echo $relatedpn; ?>" min="1" max="3" type="number" name="relatedpn" class="form-control">	
									<input style="float: right;" class="btn btn-info" type="submit" name="save-posts" value="Save Changes">
								</div>
								</div>
							</div>
						</div>
					</form>
				</div>

			</div>
		</div>
 	 </div>
</div>

<?php include "inc/footer.php"; ?>