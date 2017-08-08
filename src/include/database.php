<?php

$databaseConnection = false;

function CreateConnection()
{
    global $databaseConnectionString;
    global $databaseConnection;
    global $databaseUsername;
    global $databasePassword;
    global $databasePersistant;

    try
    {
        $conn = new PDO($databaseConnectionString, $databaseUsername, $databasePassword, array(PDO::ATTR_PERSISTENT => $databasePersistant));

        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $databaseConnection = $conn;
        return $conn;
    }
    catch(PDOException $e)
    {
        LogMessage("database", "Connection failed: " . $e->getMessage());
        return false;
    }
}

function GetPageVersion($pageId, $db = false)
{
    global $databaseConnection;
    if ($db === false)
    {
        $pageId = GetPageDbId($pageId);
    }

    $stmt = $databaseConnection->prepare("SELECT max(Version) FROM WikiPageMarkdown where WikiPageId = ?");
    if ($stmt->execute(array($pageId)))
    {
        $val = $stmt->fetch();
       return (int)$val[0];
    }

    return false;
}

function GetPageDbId($pageIdentifier)
{
    global $databaseConnection;
    if ($databaseConnection === FALSE)
    {
        LogMessage("database", "No connection");
        return false;
    }

    $stmt = $databaseConnection->prepare("SELECT Id FROM WikiPages where Identifier = ?");
    if ($stmt->execute(array($pageIdentifier)))
    {
         $val = $stmt->fetch();
         if ($val === FALSE)
            return $val;
       return (int)$val[0];
    }

    return FALSE;
}

function getPageTitle($pageIdentifier)
{
    global $databaseConnection;

    $stmt = $databaseConnection->prepare("SELECT Title FROM WikiPages where Identifier = ?");
    if ($stmt->execute(array($pageIdentifier)))
    {
         $val = $stmt->fetch();
         if ($val === FALSE)
            return $val;
       return $val[0];
    }

    return FALSE;
}

function SavePage($pageTitle, $pageText)
{
    global $databaseConnection;
    $pageIdentifier = GetPageId($pageTitle);
    $html = generateHTML($pageText);

    $version = 1;
    $pageId = GetPageDbId($pageIdentifier);

    $databaseConnection->beginTransaction();

    try
    {
        if ($pageId)
        {
            // Existing page so get the new version number
            $version = GetPageVersion($pageId, true) + 1;

            # Update title...
            $stmt = $databaseConnection->prepare("Update WikiPages Set Title = ? where Id = ?");
            $stmt->execute(array($pageTitle, $pageId));
        }
        else
        {
            // Create new page
            $sql = $databaseConnection->prepare("INSERT INTO WikiPages (Title, Identifier, CreateDate) VALUES (?, ?, ?)");
            $sql->execute(array($pageTitle, $pageIdentifier, date(DATE_RFC3339)));
            $pageId = $databaseConnection->lastInsertId();
        }

        $sql = $databaseConnection->prepare("INSERT INTO WikiPageMarkdown (Version, WikiPageId, Markdown, Username, CreateDate) VALUES (?, ?, ?, ?, ?)");
        $sql->execute(array($version, $pageId, $pageText, $_SESSION["user"], date(DATE_RFC3339)));

        $sql = $databaseConnection->prepare("INSERT INTO WikiPageHTML (Version, WikiPageId, Html, CreateDate) VALUES (?, ?, ?, ?)");
        $sql->execute(array($version, $pageId, $html, date(DATE_RFC3339)));

        $databaseConnection->commit();

    }
    catch(PDOException $e)
    {
        $databaseConnection->rollBack();
        LogMessage("database", "Error saving page: " . $e->getMessage());
        echo 'DATABASE ERROR';
        die();
    }
}

/**
* Gets the markdown for the given page.
*
*   $pageId: The identifier of the page
*   $version: The version to get. Use false or nothing for the latest version
*/
function GetPageMarkdown($pageId, $version)
{
    $pageId = GetPageDbId($pageId);
    global $databaseConnection;

    if (!isset($version) || $version == false)
    {
        $version = GetPageVersion($pageId, true);
    }

    $stmt = $databaseConnection->prepare("SELECT Markdown FROM WikiPageMarkdown where WikiPageId = ? and Version = ?");

    if ($stmt->execute(array($pageId, $version)))
    {
        $val = $stmt->fetch();
        if ($val)
        {
            return $val[0];
        }
    }

    return false;
}

/**
* Gets the html for the given page.
*
*   $pageId: The identifier of the page
*   $version: The version to get. Use false or nothing for the latest version
*/
function GetPageHTML($pageId, $version)
{
    $pageId = GetPageDbId($pageId);
    global $databaseConnection;

    if (!isset($version) || $version == false)
    {
        $version = GetPageVersion($pageId, true);
    }

    $stmt = $databaseConnection->prepare("SELECT Html FROM WikiPageHTML where WikiPageId = ? and Version = ?");    
    
    if ($stmt->execute(array($pageId, $version)))
    {
        $val = $stmt->fetch();
        if ($val)
        {
            return $val[0];
        }
    }

    return false;
}

function getPageLastModified($pageId)
{
    $pageId = GetPageDbId($pageId);
    global $databaseConnection;

    $version = GetPageVersion($pageId, true);
    $stmt = $databaseConnection->prepare("SELECT CreateDate FROM WikiPageHTML where WikiPageId = ? and Version = ?");    
    
    if ($stmt->execute(array($pageId, $version)))
    {
        $val = $stmt->fetch();
        if ($val)
        {
            return $val[0];
        }
    }

    return false;
}

function getPageHistory($pageId)
{
    $pageId = GetPageDbId($pageId);
    global $databaseConnection;

    $stmt = $databaseConnection->prepare("SELECT Version, CreateDate FROM WikiPageHTML where WikiPageId = ? order by Version");    
    
    if ($stmt->execute(array($pageId)))
    {
        $val = $stmt->fetchAll();
        if ($val)
        {
            return $val;
        }
    }

    return false;
}

function VerifyUser($username, $password)
{
    global $databaseConnection;

    $stmt = $databaseConnection->prepare("SELECT Password FROM Users where Username = ?");
    if ($stmt->execute(array($username)))
    {
        $val = $stmt->fetch();
        if ($val === FALSE)
            return FALSE;

        $hash = $val[0];
        return password_verify($password, $hash);
    }

    return FALSE;
}

function getRecentPages()
{
    global $databaseConnection;

    $stmt = $databaseConnection->prepare("SELECT Title FROM WikiPages where Id in (SELECT WikiPageId FROM WikiPageMarkdown order by CreateDate desc) limit 10");
    if ($stmt->execute())
    {
        $val = $stmt->fetchAll();
        if ($val)
        {
            return $val;
        }
    }
    return FALSE;
}

function getAllPages()
{
    global $databaseConnection;

    $stmt = $databaseConnection->prepare("SELECT Title FROM WikiPages");
    if ($stmt->execute())
    {
        $val = $stmt->fetchAll();
        if ($val)
        {
            return $val;
        }
    }
    return FALSE;
}