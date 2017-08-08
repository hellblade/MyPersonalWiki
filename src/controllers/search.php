<?php

function displayPage($routes)
{
    $action = isset($routes[0]) ? $routes[0] : "search";

    global $display;
    $display["pageName"] = "Search";
    $display["pageId"] = "";    
    $display["pageView"] = "page_list";

    switch($action)
    {
        case "recent":
            recent();
            break;

        case "all":
            all();
            break;

        default:
            search();
    }

    useView("main");
}

function search()
{
    global $display;

    $searchTerms = explode(" ", $_GET["search"]);
    $any = false;

    // Search page titles...
    $pages = getAllPages();
    if ($pages !== false)
    {
         foreach($pages as $page)
         {
             $pageId = getPageId($page[0]);
             $lowerCaseTitle = strtolower($page[0]);
             $text = strtolower(GetPageMarkdown($pageId, false));

            foreach($searchTerms as $term)
            {
                $found = strpos($lowerCaseTitle, $term) !== FALSE;
                 $found = $found || strpos($text, $term) !== FALSE;

                if ($found)
                    break;
            }

            if (!$found)
                break;

            $display["pageList"][] = array("id" => $pageId, "title" => $page[0]);
            $any = true;
         }
    }

    if (!$any)
    {
          $display["pageText"] = "Not found!";
          unset($display["pageView"]);
    }
}

function recent()
{
    global $display;
    $display["pageName"] = "Recent Changes";

    $pages = getRecentPages();
    if ($pages === false)
    {
        $display["pageText"] = "No recent pages!";
        unset($display["pageView"]);
        return;
    }

    foreach($pages as $page)
    {
        $display["pageList"][] = array("id" => getPageId($page[0]), "title" => $page[0]);
    }
}

function all()
{
    global $display;
    $display["pageName"] = "All pages";

    $pages = getAllPages();
    if ($pages === false)
    {
        $display["pageText"] = "No pages!";
        unset($display["pageView"]);
        return;
    }

    foreach($pages as $page)
    {
        $display["pageList"][] = array("id" => getPageId($page[0]), "title" => $page[0]);
    }
}