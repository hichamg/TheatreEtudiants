<?php

    $titre = "Modification d'une représentation";
    include('entete.php');

    if(!isset($_POST['numS'])){
        $requete = ("
                    SELECT NOMS, NUMS
                    FROM LESSPECTACLES
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
                echo "<p class=\"erreur\"><b>Aucun ticket dans la base de donnée</b></p>" ;
        
            }else {
        
                // on affiche le formulaire de sélection
                echo '
                    <form action="modification_rep.php" method="post">
                        <label for="sel_numS">Sélectionnez un spectacle :</label>
                        <select id="sel_numS" name="numS">
                ';
        
                // création des options
                do {
        
                    $nomS = oci_result($curseur, 1);
                    $numS = oci_result($curseur, 2);
                    
                    echo ("<option value=\"$numS\">$nomS</option>");
        
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
    }else {
        $numS = $_POST['numS'];
        $requete2 = ( "
                    SELECT to_char(daterep,'DD-MM-YYYY HH24:MI') as DATEREP
                    FROM LesRepresentations
                    where numS='$numS'
                ");
        
        // analyse de la requete et association au curseur
        $curseur2 = oci_parse($lien, $requete2);
        
        // execution de la requete
        $ok = @oci_execute($curseur2);
        
        // on teste $ok pour voir si oci_execute s'est bien passé
        if (!$ok) {
        
            // oci_execute a échoué, on affiche l'erreur
            $error_message = oci_error($curseur2);
            echo "<p class=\"erreur\">{$error_message['message']}</p>";
        } else {
        
            // oci_execute a réussi, on fetch sur le premier résultat
            $res = oci_fetch($curseur2);
        
            if (!$res) {
        
                // il n'y a aucun résultat
                echo "<p class=\"erreur\"><b>Aucune representation pour le spectacle donnée</b></p>" ;
        
            }else {
                
                // on affiche le formulaire de sélection
                echo '
                    <form action="modification_rep_action.php" method="post">
                        <label for="sel_dateRep">Sélectionnez la date de la représenation a modifier :</label>
                        <select id="sel_dateRep" name="dateRep">
                ';
        
                // création des options
                do {
        
                    $dateRep = oci_result($curseur2, 1);
                    
                    echo ("<option value=\"$dateRep\">$dateRep</option>");
        
                } while ($res = oci_fetch ($curseur2));

                echo( "
                        </select>
                        <br /><br />
                        <label for=\"sel_dateNv\">La nouvelle date de la représentation :</label>
                        <input type=\"datetime-local\" id=\"sel_dateNv\" name=\"dateNv\" />
                        <br /><br />
                        <input type=\"submit\" value=\"Valider\" />
                        <input type=\"reset\" value=\"Annuler\" />
                    </form>
                ");
        
            }
        }
    }


    // on libère le curseur
    oci_free_statement($curseur);
    oci_free_statement($curseur2);

include('pied.php');
