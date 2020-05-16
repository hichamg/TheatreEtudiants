<?php

// récupération des variables
$NOSERIE = $_POST['NOSERIE'];

$titre = "Détails du ticket no° $NOSERIE";
include('entete.php');


$requete = ("
            SELECT  numS,
                    to_char(daterep,'DD-MM-YYYY HH24:MI') as dateRep,  
                    noPlace, 
                    noRang, 
                    to_char(dateEmission,'DD-MM-YYYY HH24:MI') as dateEmission, 
                    noDossier
            FROM LesTickets
            WHERE noSerie=$NOSERIE
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
        echo "<p class=\"erreur\"><b>Le Ticket n'a pas été trouvé dand la BDD</b></p>";
    } else {

        // on affiche la table qui va servir a la mise en page du resultat
        echo "<table><tr>	<th>numS</th>
                            <th>dateRep</th>
                            <th>noPlace</th>
                            <th>noRang</th>
                            <th>dateEmission</th>
                            <th>noDossier</th>
						</tr>";

        // on affiche un résultat et on passe au suivant s'il existe
        

        $numS = oci_result($curseur, 1);
        $dateRep = oci_result($curseur, 2);
        $noPlace = oci_result($curseur, 3);
        $noRang = oci_result($curseur, 4);
        $dateEmission = oci_result($curseur, 5);
        $noDossier = oci_result($curseur, 6);
        echo "<tr>	<td>$numS</td>
                        <td>$dateRep</td>
                        <td>$noPlace</td>
                        <td>$noRang</td>
                        <td>$dateEmission</td>
                        <td>$noDossier</td></tr>";
        

        echo "</table>";

    }
}

// on libère le curseur
oci_free_statement($curseur);

echo '
		<p class="info">
            <a href="details_tickets_bd.php"> Détails d\'un ticket </a>
            <br></br>
            <a href="details_dossier_bd.php"> Détails d\'un dossier </a>
		</p>
	';

include('pied.php');
