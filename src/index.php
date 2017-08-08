<?php
session_start();

 # Include dir
$includeDir = "";

$isLoggedIn = isset($_SESSION["loggedIn"]);
$installPath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/'; # Gets the path where the wiki is installed to on the server (base path of it)

require $includeDir . "include/database.php";
require $includeDir . "include/helpers.php";
require $includeDir . "include/settings.php";

CreateConnection();


# Work out the routing
$base_url = getRelativeUri($installPath);
$routes = array();    
foreach(explode('/', $base_url)  as $route)
{
    if(trim($route) != '')
        array_push($routes, $route);
}

# TODO: Security?...   Acessing ../../../hidden file...
# Controller not existing, etc
$controller = (isset($routes[0])) ? $routes[0] : "page";     

# Remove controller so we can pass on the rest
array_splice($routes, 0, 1);

# Handle log out
if ($controller == "logout")
{
    $isLoggedIn = false;
    session_destroy();
}

# Force log in if not currently logged in
if (!$isLoggedIn && $controller != "login")
{
    header('Location: '. constructLocation("login"));
    die();
}

$display = array();

function useView($viewName)
{
    global $display;
    global $includeDir;
    require $includeDir . "views/" . $viewName . ".php";
}

if (!(include $includeDir . "controllers/" . $controller . ".php"))
{
    header('Location: '. constructLocation("page"));
    die();
}

displayPage($routes);
?>