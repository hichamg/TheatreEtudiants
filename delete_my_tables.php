<?php

	$titre = 'Suppression de la Base de Données';
	include('entete.php');

	// définition des relations
	$lesRelations = array ("LESSPECTACLES","LESTICKETS","LESDOSSIERS","LESPLACES","LESREPRESENTATIONS","LESZONES","LesCategories");
	// pour chaque relation
	foreach ($lesRelations as $Relation) {

		// construction de la requête
		$requete = "drop table $Relation";

		// analyse de la requete et association au curseur
		$curseur = oci_parse ($lien, $requete) ;

		// execution de la requete
		$ok=@oci_execute ($curseur);
		if (!$ok) {
					 // oci_execute a échoué, on affiche l'erreur
					$error_message = oci_error($curseur1);
                    echo "<p class=\"erreur\">{$error_message['message']}<br></br><b>Relation inexistante dans la bdd</b></p>";


				} 
				else {	
                    echo "Suppression de la table ".$Relation." réussie" ;
                    echo '<br/>' ;
				}
	}

	oci_free_statement($curseur);

	include('pied.php');
