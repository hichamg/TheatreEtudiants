<?php
// on inclut le fichier contenant la fonction pou MàJ les dossiers

// récupération des variables
$PRANG = explode(":", $_POST['pRang']);
$NOPLACE = $PRANG[0];
$NORANG = $PRANG[1];
$NOSERIE = $_POST['NOSERIE'];
$NUMS = $_POST['NUMS'];
$DATEREP = $_POST['DATEREP'];
 
$titre = "Modification du ticket no° $NOSERIE";
include('entete.php');

$requete = "UPDATE LesTickets
set numS        = :a,
    dateRep     = to_date(:b,'DD-MM-YYYY HH24:MI'),
    noPlace     = :c,
    noRang      = :d,
    dateEmission=CURRENT_TIMESTAMP
where noSerie = :e
 ";

$curseur = oci_parse($lien, $requete);
oci_bind_by_name($curseur, ':a', $NUMS);
oci_bind_by_name($curseur, ':b', $DATEREP);
oci_bind_by_name($curseur, ':c', $NOPLACE);
oci_bind_by_name($curseur, ':d', $NORANG);
oci_bind_by_name($curseur, ':e', $NOSERIE);
$ok = oci_execute($curseur);
if (!$ok) {

    echo LeMessage("majRejetee") . "<br /><br />";
    $e = oci_error($curseur);
    if ($e['code'] == 1) {
        echo LeMessage("majRejetee");
    }else if ($e['code']== 2290) {
        echo LeMessage( "violationContarinte2");
    } else {
        $a = $e['code'];
        echo LeMessageOracle($e['code'], $e['message']);
    }

    // terminaison de la transaction : annulation
    oci_rollback($lien);
} else {
    //echo LeMessage("majOK");
    echo "<p class=\"ok\">
            Le ticket $NOSERIE a été modifié avec succès.
            </p> ";
    // terminaison de la transaction : validation
    oci_commit($lien);
}

oci_free_statement($curseur);

MajLesDossiers($NODOSSIER, $lien);

echo '
		<p class="info">
            <a href="details_tickets_bd.php"> Détails d\'un ticket </a>
            <br></br>
            <a href="details_dossier_bd.php"> Détails d\'un dossier </a>
		</p>
	';

include('pied.php');
