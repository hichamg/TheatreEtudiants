<?php

// récupération des variables
$dateRep = $_POST['dateRep'];
//$dateNv = $_POST['dateNv'];
//$date = date("Y-m-d H:i", strtotime($dateNv));

$titre = "Suppression de la représentation du $dateRep";
include('entete.php');

$requete = "    DELETE FROM LesRepresentations
                Where dateRep=TO_DATE('$dateRep', 'DD-MM-YYYY HH24:MI') ";

$curseur = oci_parse($lien, $requete);


$ok = @oci_execute($curseur, OCI_NO_AUTO_COMMIT);
// on teste $ok pour voir si oci_execute s'est bien passé
if (!$ok) {

    echo LeMessage("majRejetee") . "<br /><br />";
    $e = oci_error($curseur);
    if ($e['code'] == 1) {
        echo LeMessage("affectationconnue");
    } else {
        echo LeMessageOracle($e['code'], $e['message']);
    }

    // terminaison de la transaction : annulation
    oci_rollback($lien);
} else {

    echo LeMessage("majOK");
    // terminaison de la transaction : validation
    oci_commit($lien);
}
// on libère le curseur
oci_free_statement($curseur);
include('pied.php');
