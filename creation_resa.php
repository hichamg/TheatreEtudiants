<?php

$titre = "Création d'une nouvelle resérvation";
include('entete.php');

$requete = ("
			SELECT NOMS, NUMS
			FROM LESSPECTACLES
		");

// noserie pour le ticket
$requete2 = ("
			SELECT max(noserie)+1 as noSerie from LesTickets
		");

// requete pour noDossier
$requete3 = ( "
			select distinct noDossier
			from LesTickets
			order by  noDossier
		");

// analyse de la requete et association au curseur
$curseur = oci_parse($lien, $requete);
$curseur2 = oci_parse($lien, $requete2);
$curseur3 = oci_parse($lien, $requete3);

// execution de la requete
$ok = @oci_execute($curseur);
$ok2 = @oci_execute($curseur2);
$ok3 = @oci_execute($curseur3);
// on teste $ok pour voir si oci_execute s'est bien passé
if (!$ok ) {

	// oci_execute a échoué, on affiche l'erreur
	$error_message = oci_error($curseur);
	echo "<p class=\"erreur\">{$error_message['message']}</p>";
} else {

	// oci_execute a réussi, on fetch sur le premier résultat
	$res = oci_fetch($curseur);
	
	if ( !$res) {

		// il n'y a aucun résultat
		echo "<p class=\"erreur\"><b>Aucune representation n'a ete trouvée</b></p>";
	} else {
		// on affiche le formulaire de sélection
		echo '
					<form action="creation_res_action1.php" method="post">
					<label for="sel_NOMS">Sélectionnez un spectacle :</label>
					<select id="sel_NUMS" name="NUMS">
				';

		// création des options
		do {

			$NOMS = oci_result($curseur, 1);
			$NUMS = oci_result($curseur,2);
			echo ("<option value=\"$NUMS\">$NOMS</option>");
		} while ($res = oci_fetch($curseur));

		echo '  </select> <br /><br />' ;

		if ( !$ok2) {
			// oci_execute a échoué, on affiche l'erreur
			$error_message = oci_error($curseur2);
			echo "<p class=\"erreur\">{$error_message['message']}</p>";
		}else {
			// oci_execute a réussi, on fetch sur le premier résultat
			$res2 = oci_fetch($curseur2);
			if (!$res2) {
				// il n'y a aucun résultat
				echo "<p class=\"erreur\"><b>Impossible de trouver le nouveau numero de série</b></p>";
			}else {
				$NOSERIE = oci_result($curseur2, 1);
				echo "<input type=\"hidden\" id=\"sel_NOSERIE\" name=\"NOSERIE\" value=\"$NOSERIE\" />";
				//echo $NOSERIE; //debug
			}
		}

		if (!$ok3) {
			// oci_execute a échoué, on affiche l'erreur
			$error_message = oci_error($curseur3);
			echo "<p class=\"erreur\">{$error_message['message']}</p>";
		} else {
			// oci_execute a réussi, on fetch sur le premier résultat
			$res3 = oci_fetch($curseur3);
			if (!$res3) {
				// il n'y a aucun résultat
				echo "<p class=\"erreur\"><b>Aucun dossier n'a été trouvé</b></p>";
			} else {
				$NODOSSIER = oci_result($curseur3, 1);
				echo '<label for="sel_NODOSSIER">Sélectionnez un dossier :</label>
					<select id="sel_NODOSSIER" name="NODOSSIER">
					<option value="0">NEW</option>';
				// création des options
				do {

					$NODOSSIER = oci_result($curseur3, 1);
					echo ("<option value=\"$NODOSSIER\">$NODOSSIER</option>");
				} while ($res = oci_fetch($curseur3));

				echo '</select> <br /><br />';
			}
		}



		echo '
                    <input type="submit" value="Valider" />
                    <input type="reset" value="Annuler" />
                </form>
				';
	}
}

// on libère le curseur
oci_free_statement($curseur);
oci_free_statement($curseur2);
oci_free_statement($curseur3);
include('pied.php');
