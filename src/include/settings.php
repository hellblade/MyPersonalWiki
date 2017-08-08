<?php
    date_default_timezone_set("Pacific/Auckland");

    # Wiki words
    $wikiWordStart = "{{";
    $wikiWordEnd = "}}";


    # Database connection details
    $databaseConnectionString = "sqlite:"; # Append path to the end
    $databaseUsername = "";
    $databasePassword = "";
    $databasePersistant = false;