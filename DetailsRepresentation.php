<?php

	$titre = "Détails des représentations";
	include('entete.php');

//choix
	//affichage choix de spectacles 

	$requete = ("
			SELECT NOMS
			FROM THEATRE.LESSPECTACLES
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
			echo "<p class=\"erreur\"><b>Aucune représentation dans la base de donnée</b></p>";
		} else {
			// on affiche le formulaire de sélection
			echo ("
					<form action=\"DetailsRepresentation_action.php\" method=\"post\">
						<label for=\"sel_NOMS\">Sélectionnez un spectacle :</label>
						<select id=\"sel_NOMS\" name=\"NOMS\">
				");

			// création des options
			do {

				$NOMS = oci_result($curseur, 1);
				echo ("<option value=\"$NOMS\">$NOMS</option>");
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


//Details
	// construction de la requete  pour les dates de spectacle
	$requete = ( "
        SELECT to_char(daterep,'Day, DD-Month-YYYY HH:MI') as DATEREP, NOMS
        FROM THEATRE.LESREPRESENTATIONS
        NATURAL JOIN THEATRE.LESSPECTACLES
	");

	// analyse de la requete et association au curseur
	$curseur = oci_parse ($lien, $requete) ;

	// execution de la requete
	$ok = @oci_execute ($curseur) ;

	// on teste $ok pour voir si oci_execute s'est bien passé
	if (!$ok) {

		// oci_execute a échoué, on affiche l'erreur
		$error_message = oci_error($curseur);
		echo "<p class=\"erreur\">{$error_message['message']}</p>";

	}
	else {

		// oci_execute a réussi, on fetch sur le premier résultat
		$res = oci_fetch ($curseur);

		if (!$res) {

			// il n'y a aucun résultat
			echo "<p class=\"erreur\"><b>Aucune représentation dans la base de donnée</b></p>" ;

		}
		else {

			// on affiche la table qui va servir a la mise en page du resultat
			echo "<table><tr><th>dateRep</th><th>Nom du Spectacle</th></tr>";

			// on affiche un résultat et on passe au suivant s'il existe
			do {

				$dateRep = oci_result($curseur, 1);
				$NOMS = oci_result($curseur, 2);


				echo "<tr><td>$dateRep</td><td>$NOMS</td></tr>";
			} while (oci_fetch($curseur));

			echo "</table>";
			
		}

	}

	// on libère le curseur
    oci_free_statement($curseur);


	// travail à réaliser
	echo ( "
		<p class=\"ok\">
			1. Afficher la date de la repréesentation (dans la granularité de l’heure) et le nom du spectacle.
			<br></br>
			2. Afficher les détails des représentations d’un spectacle choisi dans la liste.
		</p>
	");

	include('pied.php');
