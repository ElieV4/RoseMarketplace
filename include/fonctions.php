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