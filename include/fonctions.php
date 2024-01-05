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
		// Adapter la plage d'années selon vos besoins
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
			$value = $row["value"]; // Remplacez "value" par le nom de la colonne contenant les valeurs
			$selected = ($selectedValue == $value) ? 'selected' : '';
			$options .= "<option value='$value' $selected>$value</option>";
		}
	
		return $options;
	}

	//execute requete générique mysql
	function singleQuery($sql) {
		$c = new mysqli ("localhost", "root", "", "rosemarketplace", 3306);
		if($c->connect_errno) {
			return false;
		}
		$res = $c->query($sql);
		$c-> close();
		if($res){
            $data = mysqli_fetch_assoc($res);
		}
		return $data;
	}

	//fonctions messagerie
	////////////////////////////////////////////////////////////////////////////////////////////////
	// Fonction pour récupérer les messages entre un gestionnaire et un client
	function getMessages($con, $id_client, $id_gestionnaire) {
		$msg_query = "SELECT * FROM message ";
		$user_type = $_SESSION['user_type'];
		$msg_query .= " WHERE idclient_message = '$id_client' AND idgestionnaire_message = '$id_gestionnaire' AND type_message = 'message'";
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

	// Fonction pour insérer un nouveau message dans la table "message"
	function sendMessage($con, $contenu, $clientId, $gestionnaireId, $sens) {
		$contenu = mysqli_real_escape_string($con, $contenu); // Éviter les injections SQL

		$query = "INSERT INTO message (date_message, contenu_message, idclient_message, idgestionnaire_message, sens, type_message)
				VALUES (NOW(), '$contenu', '$clientId', '$gestionnaireId', '$sens','message')";
			// Exécution de la requête
		$result = $con->query($query);

		// Vérifier si l'insertion a réussi
		if ($result) {
			return $result; // Retourner true si l'insertion a réussi
		} else {
			return false; // Retourner false si l'insertion a échoué
		}
	}

	// Fonction pour supprimer un message dans la table "message"
	function deleteMessage($con, $messageId){
		$messageId = mysqli_real_escape_string($con, $messageId); // Éviter les injections SQL

		$query = "DELETE FROM message WHERE id_message = '$messageId'";
		// Exécution de la requête
		$result = $con->query($query);

		// Vérifier si la suppression a réussi
		if ($result) {
			return $result; // Retourner true si la suppression a réussi
		} else {
			return false; // Retourner false si la suppression a échoué
		}
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	//protege des injections sql
	function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
	}

	function get_user_ip() {
    // Si l'adresse IP est disponible dans l'en-tête HTTP_X_FORWARDED_FOR
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    // Sinon, utilise l'adresse IP normale
    else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
	}

	//display all products
	function getproducts() {
		global $con; 

		$select_query = "SELECT produit.id_produit,MIN(id_photo_produit) AS min_photo_id, image_type, image, nom_produit, categorie_produit, marque_produit, prixht_produit, raisonsociale_client 
		FROM produit 
		LEFT JOIN photo USING (id_produit) 
		LEFT JOIN client ON produit.id_fournisseur = client.id_client 
		WHERE quantitestock_produit > 0 
		GROUP BY produit.id_produit
		ORDER BY date_ajout_produit DESC;";

		$result = mysqli_query($con, $select_query);
		$rows = mysqli_num_rows($result);
		if($rows ==0){
			echo "Pas de produits actuellement sur le site";
		} else {
			return $result;
		}
	}
	
	//display specific products
	function getspecificproducts() {
		global $con; 

		$categorie = $_GET['categorie'];
		$marque = $_GET['marque'];

		$select_query = "SELECT produit.id_produit,MIN(id_photo_produit) AS min_photo_id, image_type, image, nom_produit, categorie_produit, marque_produit, prixht_produit, raisonsociale_client 
		FROM produit 
		LEFT JOIN photo USING (id_produit) 
		LEFT JOIN client ON produit.id_fournisseur = client.id_client 
		WHERE quantitestock_produit > 0 AND categorie_produit = '$categorie' AND marque_produit = '$marque'
		GROUP BY produit.id_produit
		ORDER BY date_ajout_produit DESC;";

		$result = mysqli_query($con, $select_query);
		$rows = mysqli_num_rows($result);
		if($rows ==0){
			echo "Pas de produits de ce type actuellement sur le site";
		} else {
 			return $result;
		}
	}

	//display brand
	function getbrand() {
		global $con; 

		$marque = $_GET['marque'];

		$select_query = "SELECT produit.id_produit,MIN(id_photo_produit) AS min_photo_id, image_type, image, nom_produit, categorie_produit, marque_produit, prixht_produit, raisonsociale_client 
		FROM produit 
		LEFT JOIN photo USING (id_produit) 
		LEFT JOIN client ON produit.id_fournisseur = client.id_client 
		WHERE quantitestock_produit > 0 AND marque_produit = '$marque'
		GROUP BY produit.id_produit
		ORDER BY date_ajout_produit DESC;";

		$result = mysqli_query($con, $select_query);
		$rows = mysqli_num_rows($result);

		if($rows ==0){
			echo "Pas de produits de cette marque actuellement sur le site";
		} else {
			return $result;
		}

	}

	//display category
	function getcategory() {
		global $con; 

		$categorie = $_GET['categorie'];

		$select_query = "SELECT produit.id_produit,MIN(id_photo_produit) AS min_photo_id, image_type, image, nom_produit, categorie_produit, marque_produit, prixht_produit, raisonsociale_client 
		FROM produit 
		LEFT JOIN photo USING (id_produit) 
		LEFT JOIN client ON produit.id_fournisseur = client.id_client 
		WHERE quantitestock_produit > 0 AND categorie_produit = '$categorie'
		GROUP BY produit.id_produit
		ORDER BY date_ajout_produit DESC;";

		$result = mysqli_query($con, $select_query);
		$rows = mysqli_num_rows($result);
		if($rows ==0){
			echo "Pas de produits de cette catégorie actuellement sur le site";
		} else {
			return $result;
		}
	}

	//searchbar
	function searchbar(){
		global $con;
		if(isset($_GET['mysearch'])){
			$search = $_GET['mysearch'];
			
			$select_query = "SELECT produit.id_produit,MIN(id_photo_produit) AS min_photo_id, image_type, image, nom_produit, categorie_produit, marque_produit, prixht_produit, raisonsociale_client,
				CONCAT(nom_produit, categorie_produit, marque_produit, raisonsociale_client, description_produit) AS searchfield 
			FROM produit 
			LEFT JOIN photo USING (id_produit) 
			LEFT JOIN client ON produit.id_fournisseur = client.id_client 
			WHERE CONCAT(nom_produit, categorie_produit, marque_produit, raisonsociale_client, description_produit) like '%$search%'
			GROUP BY produit.id_produit
			ORDER BY date_ajout_produit DESC;";
	
			$result = mysqli_query($con, $select_query);
			$rows = mysqli_num_rows($result);
			if($rows == 0){
				echo "Aucun résultat actuellement sur le site, veuillez reformulez votre requête.";
			} else {
				return $searchresult;
			}
		}
	}
?>