<?php
	
	//execute requete générique mysql
	function executeQuery($sql) {
		$c = new mysqli ("localhost", "root", "", $table, 3306);
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

?>