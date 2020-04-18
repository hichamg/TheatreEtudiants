<?php

	$titre = "Détails des représentations";
	include('entete.php');

	// construction de la requete 1 pour la selection 
	$requete = ( "
        select NOMS
        from theatre.LESSPECTACLES
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

			// on affiche le formulaire de sélection
			echo ("
				<form action=\"DetailsRepresentation_action.php\" method=\"post\">
					<label for=\"sel_NOMS\">Sélectionnez une représentation :</label>
					<select id=\"sel_NOMS\" name=\"NOMS\">
			");

			// création des options
			do {
                
				$NOMS = oci_result($curseur, 1);
				echo ("<option value=\"$NOMS\">$NOMS</option>");

			} while ($res = oci_fetch ($curseur));

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


    // construction de la requete 2 pour les details des spectacles 
    $requete2 = ( "
        select to_char(daterep,'Day, DD-Month-YYYY HH:MI') as DATEREP, NOMS
        from THEATRE.LESREPRESENTATIONS
        natural join THEATRE.LESSPECTACLES
        ");

    // analyse de la requete et association au curseur
    $curseur2 = oci_parse($lien, $requete);

    // execution de la requete
    $ok = @oci_execute($curseur2);

    // on teste $ok pour voir si oci_execute s'est bien passé
    if (!$ok) {

        // oci_execute a échoué, on affiche l'erreur
        $error_message = oci_error($curseur2);
        echo "<p class=\"erreur\">{$error_message['message']}</p>";
    } else {

        // oci_execute a réussi, on fetch sur le premier résultat
        $res2 = oci_fetch($curseur2);

        if (!$res2) {

            // il n'y a aucun résultat
            echo "<p class=\"erreur\"><b>Aucune représentation dans la base de donnée</b></p>";
        } else {

            // on affiche la table qui va servir a la mise en page du resultat
            echo "<table><tr><th>dateRep</th><th>NomS</th></tr>";

        // on affiche un résultat et on passe au suivant s'il existe
        do {

            $dateRep = oci_result($curseur2, 1);
            $NOMS2 = oci_result($curseur2,2);

            echo "<tr><td>$dateRep</td><td>$NOMS2</td></tr>";
        } while (oci_fetch($curseur2));

        echo "</table>";
        }
    }

    oci_free_statement($curseur2);


	// travail à réaliser
	echo ("
		<p class=\"work\">
            A remplacer plus tard.
            Verifier si l'affichage est correcte.
		</p>
	");

	include('pied.php');
