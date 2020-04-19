<?php

// récupération des variables
$NOMC = $_POST['NOMC'];

$titre = "Détails des tickets pour la catégorie $NOMC";
include('entete.php');

// construction de la requete
$requete = ( "
        SELECT DISTINCT NOPLACE, NORANG, NOSERIE, NOSPEC, DATEREP, NODOSSIER, DATEEMISSION 
        FROM THEATRE.LESTICKETS
        NATURAL JOIN THEATRE.LESPLACES
        NATURAL JOIN THEATRE.LESZONES
        WHERE NOMC= :n
        ORDER BY NOSERIE
	");

// analyse de la requete et association au curseur
$curseur = oci_parse($lien, $requete);

// affectation de la variable
oci_bind_by_name($curseur, ':n', $NOMC);

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
        echo "<p class=\"erreur\"><b>Aucun ticket pour cette catégorie</b></p>";
    } else {

        // on affiche la table qui va servir a la mise en page du resultat
        echo "<table><tr>   <th>No° du Siège</th>
                            <th>No° du Rang</th>
                            <th>No° de Série</th>
                            <th>No° du Spectacle</th>
                            <th>Date de la représentation</th>
                            <th>No° du dossier</th>
                            <th>Date d'émission</th>
                      </tr>";

        // on affiche un résultat et on passe au suivant s'il existe
        do {
            //NORANG, NOSERIE, NOSPEC, DATEREP, NODOSSIER, DATEEMISSION
            $NOPLACE = oci_result($curseur, 1);
            $NORANG = oci_result($curseur, 2);
            $NOSERIE = oci_result($curseur, 3);
            $NOSPEC = oci_result($curseur, 4);
            $DATEREP = oci_result($curseur, 5);
            $NODOSSIER = oci_result($curseur, 6);
            $DATEEMISSION = oci_result($curseur, 7);

            echo "<tr>  <td>$NOPLACE</td>
                        <td>$NORANG</td>
                        <td>$NOSERIE</td>
                        <td>$NOSPEC</td>
                        <td>$DATEREP</td>
                        <td>$NODOSSIER</td>
                        <td>$DATEEMISSION</td>
                </tr>";
        } while (oci_fetch($curseur));

        echo "</table>";
    }
}

// on libère le curseur
oci_free_statement($curseur);

include('pied.php');
