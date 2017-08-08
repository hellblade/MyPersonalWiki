<?php

function displayPage($routes)
{
    if (isset($_POST["wikiText"]) && (isset($routes[0]) || isset($_POST["pageName"])))
    {
        $page = isset($_POST["pageName"])  ? $_POST["pageName"] : $routes[0];

        SavePage($page, $_POST["wikiText"]);

        header('Location: '. constructLocation("page/" . getPageId($page)));
        die();
    }

    echo "Error";
}