<?php
// on inclut le fichier contenant la fonction pou MàJ les dossiers

// récupération des variables
$PRANG = explode(":", $_POST['pRang']);
$NOPLACE = $PRANG[0];
$NORANG = $PRANG[1];
$NOSERIE = $_POST['NOSERIE'];
$NUMS = $_POST['NUMS'];
$DATEREP = $_POST['DATEREP'];
$NODOSSIER = $_POST['NODOSSIER'];
/* Array ( [pRang] => 1:4 [NOSERIE] => 1952 [NUMS] => 1 [DATEREP] => 2020-05-20 20:30 [NODOSSIER] => 106 )
PLACE : [ 1 ] RANG: [ 4 ] */

$titre = "Création du ticket no° $NOSERIE du spectacle no° $NUMS [$DATEREP] ";
include('entete.php');
//include('update_dossier.php');
//echo " debug noplace = $NORANG";
$requete = "INSERT INTO LesTickets
                values (:a, :b, TO_DATE(:c, 'YYYY-MM-DD HH24:MI'), :d, :e, CURRENT_TIMESTAMP, :f) ";

$curseur = oci_parse($lien, $requete);
oci_bind_by_name($curseur, ':a', $NOSERIE);
oci_bind_by_name($curseur, ':b', $NUMS);
oci_bind_by_name($curseur, ':c', $DATEREP);
oci_bind_by_name($curseur, ':d', $NOPLACE);
oci_bind_by_name($curseur, ':e', $NORANG);
oci_bind_by_name($curseur, ':f', $NODOSSIER);
$ok = oci_execute($curseur);
if (!$ok) {
    
    echo LeMessage("majRejetee") . "<br /><br />";
    $e = oci_error($curseur);
    if ($e['code'] == 1) {
        echo LeMessage( "majRejetee");
        } else {
            $a = $e['code'];
            echo LeMessageOracle($e['code'], $e['message']);
        }

        // terminaison de la transaction : annulation
        oci_rollback($lien);
    } else {
        //echo LeMessage("majOK");
        echo "<p class=\"ok\">
            La nouvelle réservation $NOSERIE a été effectuée avec succès.
            </p> ";
        // terminaison de la transaction : validation
        oci_commit($lien);
    }

    oci_free_statement($curseur);

    MajLesDossiers($NODOSSIER,$lien);
    
    echo '
		<p class="info">
            <a href="details_tickets_bd.php"> Détails d\'un ticket </a>
            <br></br>
            <a href="details_dossier_bd.php"> Détails d\'un dossier </a>
		</p>
	';

include('pied.php');
