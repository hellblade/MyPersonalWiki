<?php

function displayPage($routes)
{
    $page = isset($routes[0]) ? $routes[0] : "home";
    $version = isset($routes[1]) ? $routes[1] : false;

    global $display;
    $display["pageName"] = GetPageTitle($page);
    $display["pageId"] = $page;
    $display["pageText"] = GetPageHTML(GetPageId($page), $version);

    $display["sidePageDetails"]["lastUpdate"] = getPageLastModified(GetPageId($page));

    // If an invalid version is given, default to the latest
    if ($display["pageText"] === false && $version !== false)
    {
        $display["pageText"] = GetPageHTML(GetPageId($page), false);
    }

    // Go to add page if doesn't exist
    if ($display["pageText"] === false)
    {
      header('Location: '. constructLocation("add/" . $page));
      die();
    }

    useView("main");
}