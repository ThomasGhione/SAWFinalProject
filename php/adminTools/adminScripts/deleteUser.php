<?php
    require("../shared/initializePage.php");
    
    if (!$sessionManager->isSessionSet() || !$sessionManager->isAdmin()) {
        header("Location: ../../index.php");
        exit;
    }

    if (isset($_GET["email"])) {
        $email = $_GET["email"];

        if (filter_var($email, FILTER_VALIDATE_EMAIL)){
            if ($dbManager->deleteUser($email)) {
                // TODO GESTIRE IL CASO IN CUI TUTTO SIA ANDATO SECONDO I PIANI, E POSTARE UN MESSAGGIO DI AZIONE COMPIUTA SU MANAGEUSERS
            } 
        }
    }
    else {
        // TODO GESTIRE IL CASO IN CUI NON SIA STATO SETTATO CORRETTAMENTE IL VALORE IN GET (TORNARE SU MANAGEUSERS E POSTARE ERRORE)
    }



?>