<?php

$titre = "Détails des représentations";
include('entete.php');

//choix
//affichage choix de spectacles 

$requete = ( "
			SELECT NOMC FROM THEATRE.LESCATEGORIES
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
        echo "<p class=\"erreur\"><b>Aucune catégorie dans la base de donnée</b></p>";
    } else {
        // on affiche le formulaire de sélection
        echo ("
					<form action=\"Categorie_action.php\" method=\"post\">
						<label for=\"sel_NOMC\">Choisir une catégorie :</label>
						<select id=\"sel_NOMC\" name=\"NOMC\">
				");

        // création des options
        do {

            $NOMC = oci_result($curseur, 1);
            echo ("<option value=\"$NOMC\">$NOMC</option>");
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


// travail à réaliser
echo ( "
		<p class=\"ok\">
            Créer les programmes CategorieTicket.php et Categorie-action.php pour améliorer l’interface utilisateur.
		</p>
	");

include('pied.php');
