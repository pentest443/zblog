<?php 

/* Categoryu functions */

function get_categories($id = "") {
	include "connect.php";
	$sql = "";
	if(empty($id)){
		$sql = "SELECT * FROM categories";
	}else {
		$sql = "SELECT * FROM categories WHERE id = ? ";
	}

	try {
		if(empty($id)){
			$result = $con->query($sql);
			return $result;
		}else {
			$result = $con->prepare($sql);
			$result->bindValue(1,$id,PDO::PARAM_INT);
			$result->execute();
			return $result->fetch(PDO::FETCH_ASSOC);
		}
	}
	catch(Exception $e) {
		echo "Error: ".$e->getMessage();
		return array();
	}
}

function insert_category($datetime,$name,$creater_name) {
	$fields = array($datetime, $name, $creater_name);
	include "connect.php";
	$sql = "INSERT INTO categories (datetime, name, creater_name) VALUES (?,?,?)";

	try{
		$result = $con->prepare($sql);
		for($i = 1; $i <= 3; $i++){
			$result->bindValue($i, $fields[$i - 1], PDO::PARAM_STR);
		}
		return $result->execute();
	}catch(Exception $e) {
		echo "Error: ". $e->getMessage();
		return false;
	}
}

function update_category($name, $id) {
	include "connect.php";
	$sql = "UPDATE categories SET name = ? WHERE id = ?";

	try {
		$result = $con->prepare($sql);
		
		$result->bindValue(1,$name, PDO::PARAM_STR);
		$result->bindValue(2,$id, PDO::PARAM_INT);
		
		return $result->execute();
	}catch(Exception $e) {
		echo "Error: " .$e->getMessage();
		return false;
	}
}
/* Post Functions  */
function insert_post($datetime, $title, $content, $author, $excerpt, $image, $category, $tags) {
	$fields = array($datetime, $title, $content, $author, $excerpt, $image, $category, $tags);
	include "connect.php";
	$sql = "INSERT INTO posts (datetime, title, content, author, excerpt, image, category, tags) VALUES (?,?,?,?,?,?,?,?) ";

	try{
		$result = $con->prepare($sql);
		for($i = 1; $i <= 8; $i++){
			$result->bindValue($i, $fields[$i - 1], PDO::PARAM_STR);
		}
		return $result->execute();
	}catch(Exception $e) {
		echo "Error: ". $e->getMessage();
		return false;
	}
}
function get_posts($id = "") {
	include "connect.php";
	$sql = "";
	if(empty($id)){
		$sql = "SELECT * FROM posts ORDER BY datetime DESC";
	}else {
		$sql = "SELECT * FROM posts WHERE id = ? ";
	}

	try {

		if(! empty($id)) {
			$result = $con->prepare($sql);
			$result->bindValue(1, $id, PDO::PARAM_INT);
			$result->execute();

			return $result->fetch(PDO::FETCH_ASSOC);
		}else {
			$result = $con->query($sql);
			return $result;
		}
	}
	catch(Exception $e) {
		echo "Error: ".$e->getMessage();
		return array();	
	}
}


function delete($table, $id) {
	include "connect.php";
	$sql = "DELETE FROM $table WHERE id = ? ";
	try{
		$result = $con->prepare($sql);
		$result->bindValue(1, $id, PDO::PARAM_INT);

		return $result->execute();
	}
	catch(Exception $e) {
		echo "Error: " . $e->getMessage();
		return false;
	}
}

function update_post($title, $content, $excerpt, $image = "", $category, $tags, $id) {
	$fields = array($title, $content, $excerpt,$category, $tags);
	include "connect.php";
	$sql = "";
	if(empty($image)){
		$sql = "UPDATE posts SET title = ?, content = ?, excerpt = ?, category = ?, tags = ? WHERE id = ?";
	}else {
		$sql = "UPDATE posts SET title = ?, content = ?, excerpt = ?, category = ?, tags = ?, image = ? WHERE id = ?";
	}
	try {

		$result = $con->prepare($sql);
		for($i = 1; $i <= 5; $i++){
			$result->bindValue($i, $fields[$i - 1], PDO::PARAM_STR);
		}

		if(! empty($image)) {
			$result->bindValue(6, $image, PDO::PARAM_STR);
			$result->bindValue(7,$id,PDO::PARAM_INT);
		}else {
			$result->bindValue(6,$id,PDO::PARAM_INT);
		}
		return $result->execute();
	}catch(Exception $e) {
		echo "Error: " .$e->getMessage();
		return false;
	}
}

// insert dmin
function insert_admin($datetime, $username, $email, $password, $roletype, $created_by, $img_name) {
	$fields = array($datetime, $username, $email, $password, $roletype, $created_by, $img_name);
	include "connect.php";
	$sql = "INSERT INTO admins (datetime, username, email, password, role_type, created_by, image)	VALUES (?,?,?,?,?,?,?) ";

	try{
		$result = $con->prepare($sql);
		for($i = 1; $i <= 7; $i++){
			$result->bindValue($i, $fields[$i - 1], PDO::PARAM_STR);
		}
		return $result->execute();
	}catch(Exception $e) {
		echo "Error: ". $e->getMessage();
		return false;
	}
}
// Get admin 
function get_admins($id = "") {
	include "connect.php";
	$sql = "";
	if(empty($id)){
		$sql = "SELECT * FROM admins ORDER BY datetime DESC";
	}else {
		$sql = "SELECT * FROM admins WHERE id = ? ";
	}

	try {

		if(! empty($id)) {
			$result = $con->prepare($sql);
			$result->bindValue(1, $id, PDO::PARAM_INT);
			$result->execute();
			return $result->fetch(PDO::FETCH_ASSOC);
		}else {
			$result = $con->query($sql);
			return $result;
		}
	}
	catch(Exception $e) {
		echo "Error: ".$e->getMessage();
		return array();	
	}
}

function update_admin($username,$roletype,$img_name, $id) {

	$fields = array($username,$roletype,$img_name);
	include "connect.php";
	$sql = "";
	if(empty($img_name)){
		$sql = "UPDATE admins SET username = ?, role_type = ? WHERE id = ?";
	}else {
		$sql = "UPDATE admins SET username = ?, role_type = ?, image = ? WHERE id = ?";
	}
	try {

		$result = $con->prepare($sql);
		for($i = 1; $i <= 2; $i++){
			$result->bindValue($i, $fields[$i - 1], PDO::PARAM_STR);
		}

		if(! empty($img_name)) {
			$result->bindValue(3, $img_name, PDO::PARAM_STR);
			$result->bindValue(4,$id,PDO::PARAM_INT);
		}else {
			$result->bindValue(3,$id,PDO::PARAM_INT);
		}
		return $result->execute();
	}catch(Exception $e) {
		echo "Error: " .$e->getMessage();
		return false;
	}

}


function is_admin($email) {

	include "connect.php";
	$sql = "SELECT * FROM admins WHERE email = ? ";
	try {

		$result = $con->prepare($sql);
		$result->bindValue(1, $email, PDO::PARAM_STR);
		$result->execute();
		return $result->fetch(PDO::FETCH_ASSOC);
	}
	catch(Exception $e) {
		echo "Error: ". $e->getMessage();
		return false;
	}
}

function update_reset_password_code($email) {

	include "connect.php";
	$newcode = rand(10000, 99999);
	$sql = "UPDATE admins SET reset_password_code = $newcode WHERE email = ? ";
	try {
		$result = $con->prepare($sql);
		$result->bindValue(1, $email, PDO::PARAM_STR);
		return $result->execute();
	}catch(Exception $e) {
		echo "Error: " .$e->getMessage();
		return false;
	}

}

function update_user_password($hashed_password, $email) {

	include "connect.php";
	$sql = "UPDATE users SET password = ? WHERE email = ? ";
	try{

		$result = $con->prepare($sql);
		$result->bindValue(1,$hashed_password,PDO::PARAM_STR);
		$result->bindValue(2,$email,PDO::PARAM_STR);
		return $result->execute();
	}catch(Exception $e){
		echo "Error: ". $e->getMessage();
		return false;
	}

}

// User Function

function get_users($id = "") {
	include "connect.php";
	$sql = "";
	if(empty($id)){
		$sql = "SELECT * FROM users ORDER BY id DESC";
	}else {
		$sql = "SELECT * FROM users WHERE id = ? ";
	}
	try {

		if(! empty($id)) {
			$result = $con->prepare($sql);
			$result->bindValue(1, $id, PDO::PARAM_INT);
			$result->execute();
			return $result->fetch(PDO::FETCH_ASSOC);
		}else {
			$result = $con->query($sql);
			return $result;
		}
	}
	catch(Exception $e) {
		echo "Error: ".$e->getMessage();
		return array();
	}
}

function redirect($location) {
	header("Location: $location");
	exit;
}
?>