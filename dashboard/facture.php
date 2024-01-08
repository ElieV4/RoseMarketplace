<?php
include('../include/connect.php');
include('../include/fonctions.php');

if (isset($_GET['idc'])) {
    $id_commande = $_GET['idc'];

    //détails fournisseur
    $sqlfr = "SELECT *
    FROM commande c 
    LEFT JOIN client fr ON c.id_fournisseur = fr.id_client
    LEFT JOIN adresse afr ON c.id_fournisseur = afr.id_client
    WHERE id_commande = $id_commande AND type_adresse = 'facturation'";
    $rowfr = singleQuery($sqlfr);
    $fournisseur = $rowfr['raisonsociale_client'];
    $adressefr = $rowfr['numetrue_adresse'];
    $codepostalfr = $rowfr['codepostal_adresse'];
    $villefr = $rowfr['villeadresse_adresse'];

    // détails commande + client
    $sql = "SELECT *
    FROM commande c 
    LEFT JOIN produit pr USING (id_produit)
    LEFT JOIN client cl ON c.idclient_commande = cl.id_client
    LEFT JOIN adresse acl ON c.id_adresse = acl.id_adresse
    WHERE id_commande = $id_commande";
    $result = mysqli_query($con,$sql);

    if ($result->num_rows > 0) {
        $montanthttotal = 0;
        $montantttctotal = 0;
        $tvatotal = 0;
        $commissiontotal = 0;

        while ($rowdata = $result->fetch_assoc()){
          $id_commande = $rowdata['id_commande'];
          $date_commande = $rowdata['date_commande'];
          $id_client = $rowdata['idclient_commande'];
          $type_client = $rowdata['type_client'];
          if($type_client == 0) {
              $client = $rowdata['prenom_client'] . ' '.$rowdata['nom_client'] ;
          } else {
              $client = $rowdata['raisonsociale_client'];
          }

          $adressecl = $rowdata['numetrue_adresse'];
          $codepostalcl = $rowdata['codepostal_adresse'];
          $villecl = $rowdata['villeadresse_adresse'];
          
          $montantttc = $rowdata['montant_total'];
          $commission = round($montantttc * 0.05 ,2);
          $tva = round($montantttc * 0.2, 2);
          $montantht = round($montantttc /1.25 ,2);

          $tvatotal = round($tvatotal + $tva, 2);
          $commissiontotal = round($commissiontotal + $commission, 2);
          $montanthttotal = round($montanthttotal + $montantht, 2);
          $montantttctotal = round($montantttctotal + $montantttc, 2);

        }

    } else {
        echo "Aucun résultat trouvé pour l'identifiant de commande $id_commande.";
    }
} else {
    echo "Identifiant de commande non spécifié dans l'URL.";
}

$con->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rose. | Facture N°<?php echo $id_commande; ?></title>
    <link rel="stylesheet" type="text/css" href="../css/facture.css">
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
        <p class="text-right"><strong>N°<?php echo $id_commande; ?></strong></p>
      </div>
    </div>
    <div class="row">
      <div class="col-7">
        <p>
          <strong><?php echo $fournisseur; ?></strong><br>
          <?php echo $adressefr ; ?><br>
          <?php echo $codepostalfr; ?> <?php echo $villefr; ?>
        </p>
      </div>
      <div class="col-5">
        <br><br><br>
        <p>
          <strong><?php echo $client; ?></strong><br>
          Réf. Client <em><?php echo $id_client; ?></em><br>
          <?php echo $adressecl; ?><br>
          <?php echo $codepostalcl; ?> <?php echo $villecl; ?>
        </p>
      </div>
    </div>
    <br>
    <br>
    <h6>Détails de la commande</h6>
    <br>
    <table class="table table-striped">
        <thead>
            <tr>
            <th>Description</th>
            <th>Quantité</th>
            <th>PU HT</th>
            <th>TVA</th>
            <th>Commission ROSE.</th>
            <th>Total HT</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $result->data_seek(0);
            while ($rowdata = $result->fetch_assoc()) {
                $id_produit = $rowdata['id_produit'];
                $nom_produit = $rowdata['nom_produit'];
                $marque_produit = $rowdata['marque_produit'];
                $quantité_produit = $rowdata['quantité_produit'];
                $produit = $rowdata['nom_produit'];
                $prixht = $rowdata['prixht_produit'];
                $totalht = round($quantité_produit * $prixht,2);
            ?>
            <tr>
                <td><?php echo $produit. ' '. $marque_produit; ?></td>
                <td><?php echo $quantité_produit; ?></td>
                <td class="text-right"><?php echo $prixht; ?>€</td>
                <td>20%</td>
                <td>5%</td>
                <td class="text-right"><?php echo $totalht; ?>€</td>
            </tr>
        <?php };?>    
  </tbody>
      </tbody>
    </table>
    <br>
    <div class="row">
      <div class="col-8">
      </div>
      <div class="col-4">
        <table class="table table-sm text-right">
          <tr>
            <td><strong>Total HT</strong></td>
            <td class="text-right"><?php echo $montanthttotal; ?>€</td>
          </tr>
          <tr>
            <td>TVA 20%</td>
            <td class="text-right"><?php echo $tvatotal; ?>€</td>
          </tr>          
          <tr>
            <td>Commission ROSE. 5%</td>
            <td class="text-right"><?php echo $commissiontotal; ?>€</td>
          </tr>
          <tr>
            <td><strong>Total TTC</strong></td>
            <td class="text-right"><?php echo $montantttctotal; ?>€</td>
          </tr>
        </table>
      </div>
    </div>
    
    <p class="conditions">
      En votre aimable règlement
      <br>
      Et avec nos remerciements.
      <br><br>
      Conditions de paiement : paiement à la commande.
      <br>
      Aucun escompte consenti pour règlement anticipé.
      <br>
      Règlement par virement bancaire.
      <br><br>
    </p>
    
    <br>
    <br>
    <br>
    <br>
    
    <p class="bottom-page text-right">
      ROSE. SAS - N° SIRET 80908753399015 RCS PARIS<br>
      Pl. du Maréchal de Lattre de Tassigny - 75016 Paris 01 22 83 98 45 - www.rosemarketplace.com<br>
      Code APE 6201Z - N° TVA Intracom. FR 77 808977532<br>
      IBAN FR76 1470 7034 0031 4211 7882 825 - SWIFT CCBPFRPPMTZ
    </p>
  </div>
</div>

<body>
</html>
