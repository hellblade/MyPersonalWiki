<?php

function displayPage($routes)
{
    $page = isset($routes[0]) ? $routes[0] : "home";
    $page = getPageId($page);

    global $display;
    $display["pageTitle"] = getPageTitle($page);
    $display["pageName"] = "Edit Page (" . $display["pageTitle"] . ")";
    $display["pageId"] = $page;
    $display["pageText"] = GetPageMarkdown(GetPageId($page), false);
    $display["pageView"] = "edit";
    $display["sideBlock"] = true;
    $display["sidePageMarkdownRef"] = true;


    useView("main");
}