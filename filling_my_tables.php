<?php

	$titre = 'Remplissage de la base de données';
	include('entete.php');

	// définition des relations
	$lesRelations = array ("LesCategories","LesZones", "LesPlaces","LesSpectacles","LesRepresentations","LesDossiers","LesTickets");

	// définition des attributs
	$lesSchemas = array (
        "LesCategories" => array("NOMC","PRIX"),
        "LesZones" => array("NUMZ","NOMC"),
        "LesPlaces" => array("NOPLACE","NORANG","NUMZ"),
        "LesSpectacles" => array("NUMS","NOMS"),
        "LesRepresentations" => array("DATEREP","NUMS"),
        "LesDossiers" => array("NODOSSIER","MONTANT"),
        "LesTickets" => array("NOSERIE","NUMS","DATEREP","NOPLACE","NORANG","DATEEMISSION","NODOSSIER")
    );


	// pour chaque relation
	foreach ($lesRelations as $uneRelation) {

		// construction de la requête
		$requete = "select * from theatre.$uneRelation ORDER BY {$lesSchemas[$uneRelation][0]}";

		// analyse de la requete et association au curseur
		$curseur = oci_parse ($lien, $requete) ;

		// execution de la requete
		oci_execute ($curseur);

		if (!($row = oci_fetch_array ($curseur, OCI_ASSOC))) {

			// le resultat est vide
			echo "<p><b>La relation ".$uneRelation." est vide </b></p>" ;

		}
		else {

			// création de la table qui va servir a la mise en page du resultat
			echo "<p><table> <tr><th> ".$uneRelation." </th></tr><tr>" ;

			foreach ($lesSchemas[$uneRelation] as $unAttr)
				echo "<td> ".$unAttr." </td>";

			echo "</tr>";

			// Affichage du resultat (non vide)
			do {
				echo "<tr>";
				foreach ($lesSchemas[$uneRelation] as $unAttr)
					echo "<td> ".$row[$unAttr]." </td>";
				echo "</tr>";
			} while ($row = oci_fetch_array ($curseur, OCI_ASSOC));

			echo "</table></p>";

		}

		oci_free_statement($curseur);

	}

	include('pied.php');
