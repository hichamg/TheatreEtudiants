<?php

	$titre = 'Liste des places associées au dossier 11 pour une catégorie donnée';
	include('entete.php');
if (empty($_POST[noDossier])) {
	// construction de la requete pour les numéros de dossiers
		$requete1 = ( "
				select distinct NODOSSIER
				from THEATRE.LESTICKETS
				order by NODOSSIER
			");
		$curseur1 = oci_parse($lien, $requete1);
		$ok = @oci_execute($curseur1);
		if (!$ok) {

			// oci_execute a échoué, on affiche l'erreur
			$error_message = oci_error($curseur1);
			echo "<p class=\"erreur\">{$error_message['message']}</p>";
		} else {

			// oci_execute a réussi, on fetch sur le premier résultat
			$res = oci_fetch($curseur1);

			if (!$res) {

				// il n'y a aucun résultat
				echo "<p class=\"erreur\"><b>Aucune dossier n'est trouvé dans la BD interogée</b></p>";
			} else {

			// on affiche le formulaire de sélection
			echo ( "
					<form action=\"SpectaclesDossier_v3.php\" method=\"POST\">
						<label for=\"sel_noDossier\">Veuillez sélectionnez un dossier :</label>
						<select id=\"sel_noDossier\" name=\"noDossier\">
				");

			// création des options
			do {

				$noDossier = oci_result($curseur1, 1);
				echo ("<option value=\"$noDossier\">$noDossier</option>");
			} while ($res = oci_fetch($curseur1));

			echo ( "
						</select>
						<br /><br />
						<input type=\"submit\" value=\"Valider\" />
						<input type=\"reset\" value=\"Annuler\" />
					</form>
				");
			}
		}

		// on libère le curseur
		oci_free_statement($curseur1);
	//$noDossier= $_GET[noDossier];
	//echo("<p>$noDossier</p> ");

}else{
		// Construction de la requete pour les categories
	//recuperation du resultat du premier formulaire
	$noDossier = $_POST[noDossier];

		$requete2 = ( "
					select distinct NOMC from THEATRE.LESZONES
					natural join THEATRE.LESPLACES
					natural join THEATRE.LESTICKETS
					where NODOSSIER=:n
				");
		$curseur2 = oci_parse($lien, $requete2);
		oci_bind_by_name($curseur2, ':n', $noDossier);
		$ok = @oci_execute($curseur2);
		if (!$ok) {

			// oci_execute a échoué, on affiche l'erreur
			$error_message = oci_error($curseur2);
			echo "<p class=\"erreur\">{$error_message['message']}</p>";
		} else {

			// oci_execute a réussi, on fetch sur le premier résultat
			$res = oci_fetch($curseur2);

			if (!$res) {

				// il n'y a aucun résultat
				echo "<p class=\"erreur\"><b>Aucune categorie n'a été trouveer pour le dossier $noDossier</b></p>";
			} else {

				// on affiche le formulaire de sélection
				echo ( "
				<form action=\"SpectaclesDossier_v3_action.php\" method=\"POST\">
					<label for=\"inp_categorie\">Veuillez saisir une catégorie :</label>
					<select id=\"inp_categorie\" name=\"CATEGORIE\">
					
				");

				// création des options
				do {

					$categorie = oci_result($curseur2, 1);
					echo ("<option value=\"$categorie\">$categorie</option>");
				} while ($res = oci_fetch($curseur2));

				echo ("
							</select>
							<br /><br />
							<input type=\"submit\" value=\"Afficher le resultat\" />
							<input type=\"reset\" value=\"Annuler\" />
						</form>
					");
			}
		}


		// on libère le curseur
		oci_free_statement($curseur2);

		$_POST['noDossier'] = $noDossier ;
	}


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

?>
