<?php

require "Parsedown.php";

function LogMessage($logName, $text)
{
    file_put_contents("$logName.txt", "$text\r\n", FILE_APPEND | LOCK_EX);
}

/*
*  Gets the identifer of a page based on the name
*/
function getPageId($name)
{
	$name = strtolower ($name);

    # Replace non-word characters with underscores
	$name = preg_replace ("/[^\w()]+/", "_", $name);

    # Remove trailing underscores
    return trim($name, "_");
}

function getCurrentURL()
{
    $url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    
    $url_parts = parse_url($url);

    $constructed_url = "";
    
    if (isset($url_parts['scheme']))
    {
        $constructed_url .= $url_parts['scheme'] . ':';
    }
    
    $constructed_url .= "//" . @$url_parts['host'];
    
    if (isset($url_parts['port']) && $url_parts['port'] != 80)
    {
        $constructed_url .= ':' . $url_parts['port'];
    }

    $constructed_url .= @$url_parts['path'];
    return htmlspecialchars($constructed_url);
}

/*
The following function will strip the script name from URL
i.e.  http://www.something.com/search/book/fitzgerald will become /search/book/fitzgerald
*/
function getRelativeUri()
{
    global $installPath;

    $uri = substr($_SERVER['REQUEST_URI'], strlen($installPath));
    if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
    $uri = '/' . trim($uri, '/');
    return $uri;
}

function constructLocation($path)
{
    global $installPath;
    return ( (empty($_SERVER["HTTPS"])) ? 'http' : "https") . "://" . $_SERVER['HTTP_HOST'] . $installPath . $path;
}

function generateHTML($text)
{
    # Replace wikiwords
    global $wikiWordStart;
    global $wikiWordEnd;

    $wikiWordRegex = "/" . $wikiWordStart . "[^" . $wikiWordEnd . "]*" . $wikiWordEnd . "/";
    preg_match_all($wikiWordRegex, $text, $matches);

    foreach($matches[0] as $wikiword)
    {
        $title = preg_replace("/[" . $wikiWordStart . $wikiWordEnd . "]/", "", $wikiword);
        $pageId = getPageId($title);
        
        $text =  str_replace($wikiword,  "[$title](" .constructLocation("page/$pageId") . ")", $text);
    }


    # Convert markdown to html
    $Parsedown = new Parsedown();
    return $Parsedown->text($text);
}