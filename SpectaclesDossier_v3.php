<?php

	$titre = 'Liste des places associées au dossier 11 pour une catégorie donnée';
	include('entete.php');

	// construction de la requete
	$requete = ("
			SELECT distinct noDossier
			FROM theatre.LesTickets
			ORDER BY noDossier
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
			echo "<p class=\"erreur\"><b>Aucun dossier n'a été trouvé dans la base de donnée</b></p>";
		} else {

			// on affiche le formulaire de sélection
			echo ("
					<form action=\"SpectaclesDossier_v3_etape.php\" method=\"post\">
						<label for=\"sel_noDossier\">Sélectionnez un dossier :</label>
						<select id=\"sel_noDossier\" name=\"noDossier\">
				");

			// création des options
			do {

				$noDossier = oci_result($curseur, 1);
				echo ("<option value=\"$noDossier\">$noDossier</option>");
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

	// affichage du formulaire
	/*echo ("
		<form action=\"SpectaclesDossier_v3_action.php\" method=\"POST\">
			<label for=\"inp_categorie\">Veuillez saisir une catégorie :</label>
			<input type=\"text\" name=\"categorie\" />
			<br /><br />
			<input type=\"submit\" value=\"Valider\" />
			<input type=\"reset\" value=\"Annuler\" />
		</form>
	");
	*/
	// travail à réaliser
	echo ("
		<p class=\"work\">
			Ajoutez une étape à cet enchaînement de scripts de façon à obtenir le fonctionnement suivant :
			<ul>
				<li><b>Etape 1 :</b> un formulaire nous demande de choisir un numéro de dossier dans une liste extraite de la base de données</li>
				<li><b>Etape 2 :</b> pour le numéro de dossier choisi, un formulaire nous demande de sélectionner une catégorie dans une liste qui ne contiendra que les catégories concernées par le numéro de dossier demandé</li>
				<li><b>Etape 3 :</b> affichage de la liste des places correspondant à la catégorie et au numéro de dossier sélectionnés</li>
			</ul>
		</p>
	");

	include('pied.php');
