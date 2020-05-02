<?php

    $titre = "Gestion des Représentations de l'application Théâtre";
    include('entete.php');

    echo'
        <body>

        <ul class=" menu ">
            <li><a href=" creation_rep.php "> Ajout d\'une nouvelle représentation </a> </li>
            <li><a href=" modification_rep.php"> Mofication d\'une représentation </a></li>
            <li><a href=" suppression_rep.php "> Annulation/Suppression de représentation </a></li>
        </ul>

        </body>
    ';

    include('pied.php');
