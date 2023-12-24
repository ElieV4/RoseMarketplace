<?php
	
	//execute requete générique mysql
	function executeQuery($sql) {
		$c = new mysqli ("localhost", "root", "", "rosemarketplace", 3306);
		if($c->connect_errno) {
			return false;
		}
		$res = $c->query($sql);
		$c-> close();
		return $res;
	}

	function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
	}

	function getproducts() {
		global $con; 
		$select_query="SELECT $ FROM produit ORDER BY rand() LIMIT 0,9";
	}
?>