<?php

$titre = "Détails d'un ticket'";
include('entete.php');

//choix
//affichage choix de spectacles 

$requete = ("
			SELECT noSerie
            FROM LesTickets
            order by noSerie
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
        echo "<p class=\"erreur\"><b>Aucun ticket dans la base de donnée</b></p>";
    } else {
        // on affiche le formulaire de sélection
        echo ( "
					<form action=\"details_tickets_bd_action.php\" method=\"post\">
						<label for=\"sel_NOSERIE\">Sélectionnez un ticket :</label>
						<select id=\"sel_NOSERIE\" name=\"NOSERIE\">
				");

        // création des options
        do {

            $NOSERIE = oci_result($curseur, 1);
            echo ("<option value=\"$NOSERIE\">$NOSERIE</option>");
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
