<?php

function displayPage($routes)
{
    $page = isset($routes[0]) ? $routes[0] : "home";

    global $display;
    $display["pageId"] = $page;
    $display["pageView"] = "add";
    $display["sideBlock"] = true;
    $display["pageName"] = "Add Page";    
    $display["sidePageMarkdownRef"] = true;

    useView("main");
}