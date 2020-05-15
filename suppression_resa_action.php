<?php

// récupération des variables
//$NOSERIE = $_POST['NOSERIE'];
$s_d = explode(":", $_POST['NOSERIE']);
$NOSERIE = $s_d[0];
$NODOSSIER = $s_d[1];


$titre = "Suppression du ticket no° $NOSERIE";
include('entete.php');


    $requete = "    DELETE FROM LesTickets
                    Where NOSERIE=$NOSERIE ";

    $curseur = oci_parse($lien, $requete);


    $ok = @oci_execute($curseur, OCI_NO_AUTO_COMMIT);
    // on teste $ok pour voir si oci_execute s'est bien passé
    if (!$ok) {

        echo LeMessage("majRejetee") . "<br /><br />";
        $e = oci_error($curseur);
        if ($e['code'] == 1) {
            echo LeMessage("affectationconnue");
        }else {
            echo LeMessageOracle($e['code'], $e['message']);
        }

        // terminaison de la transaction : annulation
        oci_rollback($lien);
    } else {

        echo LeMessage("majOK");
        // terminaison de la transaction : validation
        oci_commit($lien);
    }
    
    MajLesDossiers($NODOSSIER,$lien);
    // on libère le curseur
    oci_free_statement($curseur);
     echo '
		<p class="info">
            <a href="details_tickets_bd.php"> Détails d\'un ticket </a>
            <br></br>
            <a href="details_dossier_bd.php"> Détails d\'un dossier </a>
		</p>
	';

include('pied.php');
