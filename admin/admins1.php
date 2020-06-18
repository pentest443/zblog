<?php 
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

?>