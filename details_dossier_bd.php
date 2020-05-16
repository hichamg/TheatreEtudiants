<?php

$titre = "Détails d'un dossier";
include('entete.php');

//choix
//affichage choix de spectacles 

$requete = ("
			SELECT noDossier
            FROM LesDossiers
            order by noDossier
		");

// analyse de la requete et association au curseur
$curseur = oci_parse($lien, $requete);

// execution de la requete
$ok = @oci_execute($curseur);

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
        echo "<p class=\"erreur\"><b>Aucun dossier dans la BDD</b></p>";
    } else {
        // on affiche le formulaire de sélection
        echo ("
					<form action=\"details_dossier_bd_action.php\" method=\"post\">
						<label for=\"sel_NODOSSIER\">Sélectionnez un dossier :</label>
						<select id=\"sel_NODOSSIER\" name=\"NODOSSIER\">
				");

        // création des options
        do {

            $NODOSSIER = oci_result($curseur, 1);
            echo ("<option value=\"$NODOSSIER\">$NODOSSIER</option>");
        } while ($res = oci_fetch($curseur));

        echo ("
						</select>
						<br /><br />
						<input type=\"submit\" value=\"Valider\" />
						<input type=\"reset\" value=\"Annuler\" />
					</form>
				");
    }
}

// on libère le curseur
oci_free_statement($curseur);

include('pied.php');
