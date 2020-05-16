<?php

// récupération des variables
$noSerie = $_POST['NOSERIE'];
$nDate = explode("*", $_POST['nDate']);
$numS = $nDate[0];
$dateRep = $nDate[1];

$titre = "Modification du ticket no° $noSerie ";
include('entete.php');

// requete pour retrouver place/rang 
$requete = "
    select noPlace, noRang from LesPlaces
    minus
    select noPlace, noRang from LesTickets
    where dateRep = TO_DATE(:n, 'DD-MM-YYYY HH24:MI')    
";
// analyse de la requete et association au curseur
$curseur = oci_parse($lien, $requete);

oci_bind_by_name($curseur, ':n', $dateRep);

// execution de la requete
$ok = oci_execute($curseur);

// on teste $ok pour voir si oci_execute s'est bien passé
if (!$ok) {
    // oci_execute a échoué, on affiche l'erreur
    $error_message = oci_error($curseur);
    echo "<p class=\"erreur\">{$error_message['message']}</p>";
} else {

    // oci_execute a réussi, on fetch sur le premier résultat
    $res = oci_fetch($curseur);

    if (!$res) {
        // il n'y a aucun résultat
        echo "<p class=\"erreur\"><b>Aucune place n'est disponoble</b></p>";
        $e = oci_error($curseur);
        echo LeMessageOracle($e['code'], $e['message']);
    } else {
        // on affiche le formulaire de sélection
        echo '
            <form action="modification_resa_action3.php" method="post">
            <label for="sel_pRang">Sélectionnez un siège (noPlace/noRang) :</label>
            <select id="sel_pRang" name="pRang">
        ';

        do {
            $noPlace = oci_result($curseur, 1);
            $noRang = oci_result($curseur, 2);
            echo ("<option value='$noPlace:$noRang'>" . $noPlace . "/" . $noRang . "</option>");
        } while ($res = oci_fetch($curseur));

        echo "
            </select>
            <br /><br />
            <input type=\"hidden\" id=\"sel_NOSERIE\" name=\"NOSERIE\" value=\"$noSerie\" />
            <input type=\"hidden\" id=\"sel_NUMS\" name=\"NUMS\" value=\"$numS\" />
            <input type=\"hidden\" id=\"sel_DATEREP\" name=\"DATEREP\" value=\"$dateRep\" />
            <input type=\"submit\" value=\"Valider\" />
            <input type=\"reset\" value=\"Annuler\" />
            </form>
            ";
    }
}

// on libère le curseur
oci_free_statement($curseur);


include('pied.php');
