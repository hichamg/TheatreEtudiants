<?php

    $titre = "Gestion des Réservations de l'application Théâtre";
    include('entete.php');

    echo '
        <body>

        <ul class="menu">
            <li><a href=" details_tickets_bd.php"> Détails d\'un ticket </a> </li>
            <li><a href=" details_dossier_bd.php"> Détails d\'un dossier </a> </li>
            <li><a href=" creation_resa.php"> Ajout d\'une nouvelle réservation </a> </li>
            <li><a href=" modification_resa.php"> Mofication d\'une réservation </a></li>
            <li><a href=" suppression_resa.php "> Annulation/Suppression de réservation </a></li>
        </ul>

        </body>
    ';

    include('pied.php');

?>