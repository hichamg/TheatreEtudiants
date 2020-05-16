<?php

// récupération des variables
$noSerie = $_POST['noSerie'];

$titre = "Modification du ticket no° $noSerie ";
include('entete.php');


// requete pour retrouver place/rang 
$requete = "
            select nomS, numS,  to_char(dateRep, 'DD-MM-YYYY HH24:MI') as dateRep
            from LesRepresentations
            natural join LesSpectacles
            order by numS, dateRep
        ";
// analyse de la requete et association au curseur
$curseur = oci_parse($lien, $requete);


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
        echo "<p class=\"erreur\"><b>Aucune représentation dans la bdd</b></p>";
        $e = oci_error($curseur);
        echo LeMessageOracle($e['code'], $e['message']);
    } else {
        // on affiche le formulaire de sélection
        echo '
            <form action="modification_resa_action2.php" method="post">
            <label for="sel_nDate">Sélectionnez une représentation (nomS/dateRep) :</label>
            <select id="sel_nDate" name="nDate">
        ';

        do {
            $nomS = oci_result($curseur, 1);
            $numS = oci_result($curseur, 2);
            $dateRep = oci_result($curseur, 3);

            echo ("<option value='$numS*$dateRep'>" . $nomS . "/" . $dateRep . "</option>");
        } while ($res = oci_fetch($curseur));

        echo "
            </select>
            <br /><br />
            <input type=\"hidden\" id=\"sel_NOSERIE\" name=\"NOSERIE\" value=\"$noSerie\" />
            <input type=\"submit\" value=\"Valider\" />
            <input type=\"reset\" value=\"Annuler\" />
            </form>
            ";
    }
}

// on libère le curseur
oci_free_statement($curseur);


include('pied.php');
