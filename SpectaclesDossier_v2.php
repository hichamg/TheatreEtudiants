<?php

	$titre = 'Liste des places associées au dossier 11 pour une catégorie donnée';
	include('entete.php');

	// construction de la requete
	$requete = ("
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
			echo "<p class=\"erreur\"><b>Aucune catégorie trouvé</b></p>";
		} else {

			// affichage du formulaire
			echo ("
				<form action=\"SpectaclesDossier_v2_action.php\" method=\"POST\">
				<label for=\"inp_categorie\">Veuillez saisir une catégorie :</label>
			");

			// on affiche un résultat et on passe au suivant s'il existe
			do {

				$nomS = oci_result($curseur, 1);
				echo ( "
					<input type=\"radio\" name=\"categorie\" value=\"$nomS\"/>
  				<label for=\"$nomS\">$nomS</label>
				");
			} while (oci_fetch($curseur));

			echo ("	<br /><br />
				<input type=\"submit\" value=\"Valider\" />
				<input type=\"reset\" value=\"Annuler\" />
			</form>
			");
		}
	}

	
	

	// travail à réaliser
	echo ("
		<p class=\"ok\">
			Améliorez l'interface utilisateur en proposant, à la place du champ de saisie libre, un choix de catégorie dans une liste contenant toutes les catégories (sous forme de boite de sélection ou de boutons radio).<br />Cette fois-ci, la liste sera extraite de la base de données.
		</p>
	");

	include('pied.php');

?>
