<?php

    // récupération des variables
    $NOMS = $_POST['NOMS'];
    $DATEREP = $_POST['DATEREP'];
    $HEUREREP = $_POST['HEUREREP'];

   
	$titre = "Création d'une représentation du spectacle $NOMS pour la date suivante : $DATEREP $HEUREREP";
    include('entete.php');
    //print_r($_POST);
    
	// construction des requêtes
	$requete1 = "select numS from LesSpectacles where nomS='$NOMS'";
	$requete2 = "INSERT INTO LesRepresentations values (TO_DATE('$DATEREP $HEUREREP', 'YYYY-MM-DD HH24:MI'), :n )";

	// analyse de la requete 1 et association au curseur
	$curseur1 = oci_parse ($lien, $requete1) ;

	// execution de la requete
	$ok = @oci_execute ($curseur1);

	// on teste $ok pour voir si oci_execute s'est bien passé
	if (!$ok) {

		// oci_execute a échoué, on affiche l'erreur
		$error_message = oci_error($curseur);
		echo "<p class=\"erreur\">{$error_message['message']}</p>";

	}
	else {

		// oci_execute a réussi, on fetch sur le premier résultat
		$res = oci_fetch ($curseur1);

		if (!$res) {

			// il n'y a aucun résultat
			echo "<p class=\"erreur\"><b>Représentation inconnue</b></p>" ;

		}
		else {
            $curseur2 = oci_parse ($lien, $requete2) ;
            // affectation de la variable
            oci_bind_by_name($curseur2, ':n', $NOMS);

            $ok = @oci_execute ($curseur2, OCI_NO_AUTO_COMMIT) ;
            // on teste $ok pour voir si oci_execute s'est bien passé
            if (!$ok) {

                echo LeMessage ("majRejetee")."<br /><br />";
                $e = oci_error($curseur2);
                if ($e['code'] == 1) {
                    echo LeMessage ("représentationconnue") ;
                }
                else {
                    echo LeMessageOracle ($e['code'], $e['message']) ;
                    
                }

                // terminaison de la transaction : annulation
                oci_rollback ($lien) ;

            }
            else {

                echo LeMessage ("majOK") ;
                // terminaison de la transaction : validation
                oci_commit ($lien) ;

            }
        }
    }


	// on libère le curseur
    oci_free_statement($curseur1);
    oci_free_statement($curseur2);

	include('pied.php');
