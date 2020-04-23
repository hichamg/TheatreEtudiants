<?php

$titre = "Pour chaque spectacle: son numéro, nom, les dates de ses répresentations et pour chacune le nombre de places réservées";
include('entete.php');


// construction de la requete
    $requete = ( "
                    select distinct NOSPEC, NOMS, to_char(daterep,'DD-MM-YYYY HH24:MI') as DATEREP, count(NOSERIE) as nb_res
                    from theatre.LESREPRESENTATIONS
                    natural left outer join theatre.LESTICKETS
                    natural join THEATRE.LESSPECTACLES
                    group by NOSPEC, NOMS, DATEREP
                    order by NOSPEC
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
            echo "<p class=\"erreur\"><b>Aucun spectacle dans la base de donnée</b></p>";
        } else {

            // on affiche la table qui va servir a la mise en page du resultat
            echo "<table><tr>
                                <th>No° Spectacle</th>
                                <th>Nom du Spectacle</th>
                                <th>Date de la représentation</th>
                                <th>Nombre de places réservées</th>
                            </tr>";

            // on affiche un résultat et on passe au suivant s'il existe
            do {

                $NOSPEC = oci_result($curseur, 1);
                $NOMS = oci_result($curseur, 2);
                $dateRep = oci_result($curseur, 3);
                $nb_res = oci_result($curseur, 4);

                echo "<tr>  <td>$NOSPEC</td>
                                <td>$NOMS</td>
                                <td>$dateRep</td>
                                <td>$nb_res</td>
                        </tr>";
                    
                

                oci_free_statement($curseur2);
            } while (oci_fetch($curseur));

            echo "</table>";
        }
    }

    // on libère le curseur
    oci_free_statement($curseur);

include('pied.php');
