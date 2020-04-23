<?php

    // récupération de la catégorie
    $noDossier = $_POST['noDossier'];

    //
    $titre = "Liste des catégories associées au dossier $noDossier :";
    include('entete.php');

    // construction de la requete
    $requete = ( "
            select distinct NOMC from THEATRE.LESZONES
            natural join THEATRE.LESPLACES
            natural join THEATRE.LESTICKETS
            where NODOSSIER=:n 
        ");

    // analyse de la requete et association au curseur
    $curseur = oci_parse($lien, $requete);

    // affectation de la variable
    oci_bind_by_name($curseur, ':n', $noDossier);

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
            echo "<p class=\"erreur\"><b>Aucune catégorie associée au dossier $noDossier </b></p>";
        } else {

            // on affiche le formulaire de sélection
            echo ("
                        <form action=\"SpectaclesDossier_v3_action.php?noDossier=$noDossier\" method=\"post\">
                            <label for=\"sel_categorie\">Sélectionnez une catégorie :</label>
                            <select id=\"sel_categorie\" name=\"categorie\">
                    ");

            // création des options
            do {

                $categorie = oci_result($curseur, 1);
                echo ("<option value=\"$categorie\">$categorie</option>");
            } while ($res = oci_fetch($curseur));

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

include('pied.php');
