<?php

$titre = "Liste des représentations sans place réservée";
include('entete.php');


    // construction de la requete
    $requete = ( "
            with res1 as (
            ( select distinct daterep, nospec from theatre.LESREPRESENTATIONS)
            minus
            ( select distinct daterep , nospec from theatre.LESTICKETS )
            )
            select noms, DateRep
            from res1
            natural join theatre.lesspectacles
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
            echo "<p class=\"erreur\"><b>Aucune représentation dans la base de donnée</b></p>";
        } else {
            
            // on affiche la table qui va servir a la mise en page du resultat
            echo "<table><tr><th>Nom du Spectacle</th><th>Date de la représentation</th></tr>";

            // on affiche un résultat et on passe au suivant s'il existe
            do {

                $NOMS = oci_result($curseur, 1);
                $dateRep = oci_result($curseur, 2);


                echo "<tr><td>$NOMS</td><td>$dateRep</td></tr>";
            } while (oci_fetch($curseur));

            echo "</table>";
        }
    }

    // on libère le curseur
    oci_free_statement($curseur);

include('pied.php');
