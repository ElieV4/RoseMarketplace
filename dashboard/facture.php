<?php
include('../include/connect.php');
include('../include/fonctions.php');

// Vérifier si l'identifiant de commande est défini dans l'URL
if (isset($_GET['idc'])) {
    $id_commande = $_GET['idc'];

    $sqlfr = "SELECT *
    FROM commande c 
    LEFT JOIN client fr ON c.id_fournisseur = cl.id_client
    LEFT JOIN adresse afr ON c.id_fournisseur = afr.id_client
    WHERE id_commande = $id_commande";
    $rowfr = signleQuery($sqlfr);
    $adressefr = $rowfr['numetrue_adresse'];
    $codepostalfr = $rowfr['codepostal_adresse'];
    $villefr = $rowfr['villeadresse_adresse'];

    // Exécuter la requête SQL pour récupérer les détails de la commande
    $sql = "SELECT *
    FROM commande c 
    LEFT JOIN produit pr USING (id_produit)
    LEFT JOIN client cl ON c.idclient_commande = cl.id_client
    LEFT JOIN adresse acl ON c.id_adresse = acl.id_adresse
    WHERE id_commande = $id_commande";
    $result = mysqli_query($con,$sql);

    // Vérifier s'il y a des résultats
    if ($result->num_rows > 0) {
        // Récupérer les détails de la commande
        while ($rowdata = $result->fetch_assoc()){
        $id_commande = $rowdata['id_commande'];
        $fournisseur = $rowdata['fr.raisonsociale_client'];
        $date_commande = $rowdata['date_commande'];
        $montant = $rowdata['montant_total'];
        $type_client = $rowdata['cl.type_client'];

        $id_produit = $rowdata['id_produit'];
        $nom_produit = $rowdata['nom_produit'];
        $marque_produit = $rowdata['marque_produit'];
        $quantité_produit = $rowdata['quantité_produit'];
        $produit = $rowdata['nom_produit'];

        $adressecl = $rowdata['cl.numetrue_adresse'];
        $codepostalcl = $rowdata['cl.codepostal_adresse'];
        $villecl = $rowdata['cl.villeadresse_adresse'];
    
        }

    } else {
        echo "Aucun résultat trouvé pour l'identifiant de commande $id_commande.";
    }
} else {
    echo "Identifiant de commande non spécifié dans l'URL.";
}

// Fermer la connexion à la base de données
$con->close();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rose. | Facture N°</title>
    <style>
        body {
        background: #ccc;
        padding: 30px;
        }

        .container {
        width: 21cm;
        min-height: 29.7cm;
        }

        .invoice {
        background: #fff;
        width: 100%;
        padding: 50px;
        }

        .logo {
        width: 200px;
        }

        .document-type {
        text-align: right;
        color: #444;
        }

        .conditions {
        font-size: 0.7em;
        color: #666;
        }

        .bottom-page {
        font-size: 0.7em;
        }
    </style>
</head>

<body>
<div class="container">
  <div class="invoice">
    <div class="row">
      <div class="col-7">
        <img src="../images/rose.png" class="logo">
      </div>
      <div class="col-5">
        <h1 class="document-type display-4">FACTURE</h1>
        <p class="text-right"><strong>N°90T-17-01-0123</strong></p>
      </div>
    </div>
    <div class="row">
      <div class="col-7">
        <p>
          <strong>90TECH SAS</strong><br>
          6B Rue Aux-Saussaies-Des-Dames<br>
          57950 MONTIGNY-LES-METZ
        </p>
      </div>
      <div class="col-5">
        <br><br><br>
        <p>
          <strong>Energies54</strong><br>
          Réf. Client <em>C00022</em><br>
          12 Rue de Verdun<br>
          54250 JARNY
        </p>
      </div>
    </div>
    <br>
    <br>
    <h6>Audits et rapports mensuels (1er Novembre 2016 - 30 Novembre 2016)</h6>
    <br>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Description</th>
          <th>Quantité</th>
          <th>Unité</th>
          <th>PU HT</th>
          <th>TVA</th>
          <th>Total HT</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Audits et rapports mensuels</td>
          <td>1</td>
          <td>Jour</td>
          <td class="text-right">500,00€</td>
          <td>20%</td>
          <td class="text-right">500,00€</td>
        </tr>
        <tr>
          <td>Génération des rapports d'activité</td>
          <td>4</td>
          <td>Rapport</td>
          <td class="text-right">800,00€</td>
          <td>20%</td>
          <td class="text-right">3 200,00€</td>
        </tr>
      </tbody>
    </table>
    <div class="row">
      <div class="col-8">
      </div>
      <div class="col-4">
        <table class="table table-sm text-right">
          <tr>
            <td><strong>Total HT</strong></td>
            <td class="text-right">3 700,00€</td>
          </tr>
          <tr>
            <td>TVA 20%</td>
            <td class="text-right">740,00€</td>
          </tr>
          <tr>
            <td><strong>Total TTC</strong></td>
            <td class="text-right">4 440,00€</td>
          </tr>
        </table>
      </div>
    </div>
    
    <p class="conditions">
      En votre aimable règlement
      <br>
      Et avec nos remerciements.
      <br><br>
      Conditions de paiement : paiement à réception de facture, à 15 jours.
      <br>
      Aucun escompte consenti pour règlement anticipé.
      <br>
      Règlement par virement bancaire.
      <br><br>
      En cas de retard de paiement, indemnité forfaitaire pour frais de recouvrement : 40 euros (art. L.4413 et L.4416 code du commerce).
    </p>
    
    <br>
    <br>
    <br>
    <br>
    
    <p class="bottom-page text-right">
      90TECH SAS - N° SIRET 80897753200015 RCS METZ<br>
      6B, Rue aux Saussaies des Dames - 57950 MONTIGNY-LES-METZ 03 55 80 42 62 - www.90tech.fr<br>
      Code APE 6201Z - N° TVA Intracom. FR 77 808977532<br>
      IBAN FR76 1470 7034 0031 4211 7882 825 - SWIFT CCBPFRPPMTZ
    </p>
  </div>
</div>
<body>
</html>
