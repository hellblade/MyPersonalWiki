<?php

function displayPage($routes)
{
    $page = isset($routes[0]) ? $routes[0] : "home";

    global $display;
    $display["pageName"] = "History - " . GetPageTitle($page);
    $display["pageId"] = $page;
    $display["pageView"] = "history";

    $display["versions"] = getPageHistory(GetPageId($page));

    useView("main");
}