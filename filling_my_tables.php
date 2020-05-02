<?php

	$titre = 'Contenu des relations fournies';
	include('entete.php');

	// définition des relations
	$lesRelations = array ( "LesCategories", "LesZones", "LesPlaces", "LesSpectacles", "LesRepresentations", "LesDossiers","LesTickets");
 	$requete[1]= "	insert into LesCategories
					select * from theatre.LESCATEGORIES
				";

    $requete[2]= "	insert into LesZones
					select * from theatre.LesZones
				";
    
    $requete[3]= "	insert into LesPlaces
					select * from theatre.LESSIEGES
				";

    $requete[4]= "	insert into LesSpectacles
					select NOSPEC, NOMS from THEATRE.LESSPECTACLES
				";

    $requete[5]= "	insert into LesRepresentations
					select dateRep,nospec from THEATRE.LESREPRESENTATIONS
				";

    $requete[6]= "	insert into LESDOSSIERS
					select noDossier, sum(prix) as montant
					from theatre.LesTickets
					natural join theatre.LESSIEGES
					natural join theatre.LesZones
					natural join theatre.LesCategories
					group by noDossier
					order by noDossier
				";

    $requete[7]= "	insert into LesTickets
					select * from THEATRE.LESTICKETS
				";
				
    for ($i=1; $i<=7 ; $i++){
        $curseur = oci_parse ($lien, $requete[$i]) ;
        $ok=@oci_execute ($curseur);
        if (!$ok) {
					 // oci_execute a échoué, on affiche l'erreur
					$error_message = oci_error($curseur1);
                    echo "<p class=\"erreur\">{$error_message['message']}<b>Insertion impossible</b></p>";

				} 
				else {	
                    echo "Remplissage de la table ".$lesRelations[$i]." réussie" ;
                    echo '<br/>' ;
				}
    }

	include('pied.php');
