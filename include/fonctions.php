<?php
	//fonction filtre mois
	function generateMonthOptions($selectedMonth) {
		$months = [
			'01' => 'Janvier',
			'02' => 'Février',
			'03' => 'Mars',
			'04' => 'Avril',
			'05' => 'Mai',
			'06' => 'Juin',
			'07' => 'Juillet',
			'08' => 'Août',
			'09' => 'Septembre',
			'10' => 'Octobre',
			'11' => 'Novembre',
			'12' => 'Décembre'
		];

		$options = '';
		foreach ($months as $monthNum => $monthName) {
			$selected = ($selectedMonth == $monthNum) ? 'selected' : '';
			$options .= "<option value='$monthNum' $selected>$monthName</option>";
		}

		return $options;
	}
	
	//fonction filtre année
	function generateYearOptions($selectedYear) {
		$startYear = 2023;
		$endYear = date('Y');

		$options = '';
		for ($year = $startYear; $year <= $endYear; $year++) {
			$selected = ($selectedYear == $year) ? 'selected' : '';
			$options .= "<option value='$year' $selected>$year</option>";
		}

		return $options;
	}
	
	//fonction filtre champ bdd
	function generateOptions($selectedValue, $query, $con) {
		$options = '<option value="all">Tous</option>';
	
		$result = mysqli_query($con, $query);
		while ($row = mysqli_fetch_array($result)) {
			$value = $row["value"]; 
			$selected = ($selectedValue == $value) ? 'selected' : '';
			$options .= "<option value='$value' $selected>$value</option>";
		}
	
		return $options;
	}

	//execute requete générique mysql
	function singleQuery($sql) {
		$con = new mysqli ("localhost", "root", "", "rosemarketplace", 3306);
		if($con->connect_errno) {
			return false;
		}
		$result = mysqli_query($con, $sql);
		$con -> close();
		if($result){
            $data = mysqli_fetch_assoc($result);
		}
		return $data;
	}

	//fonctions messagerie
	function getMessages($con, $id_client, $id_gestionnaire) {
		$msg_query = "SELECT * FROM message ";
		$user_type = $_SESSION['user_type'];
		$msg_query .= " WHERE idclient_message = '$id_client' AND idgestionnaire_message = '$id_gestionnaire'";
		$msg_query .= " ORDER BY date_message ASC";
		$result = $con->query($msg_query);
		$messages = array();

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$messages[] = $row;
			}
		}
		return $messages;
	}

	function sendMessage($con, $contenu, $clientId, $gestionnaireId, $sens) {
		$contenu = mysqli_real_escape_string($con, $contenu); 

		$query = "INSERT INTO message (date_message, contenu_message, idclient_message, idgestionnaire_message, sens, type_message)
				VALUES (NOW(), '$contenu', '$clientId', '$gestionnaireId', '$sens','message')";
		$result = $con->query($query);

		if ($result) {
			return $result; 
		} else {
			return false; 
		}
	}

	function deleteMessage($con, $messageId){
		$messageId = mysqli_real_escape_string($con, $messageId); 
		$query = "DELETE FROM message WHERE id_message = '$messageId'";
		$result = $con->query($query);

		if ($result) {
			return $result; 
		} else {
			return false; 
		}
	}
	
	//protege des injections sql
	function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
	}

	function get_user_ip() {
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
	}

	//couleur vignette contrats
	function getBorderColorClass($statut) {
		switch ($statut) {
			case 'en attente':
				return 'en-attente-border';
			case 'validé':
				return 'valide-border';
			case 'refusé':
				return 'refuse-border';
			default:
				return '';
		}
	}
?>