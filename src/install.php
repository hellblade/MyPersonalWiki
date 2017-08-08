<?php

 # Include dir
$includeDir = "";

require $includeDir . "include/database.php";
require $includeDir . "include/helpers.php";
require $includeDir . "include/settings.php";

if (isset($_POST["name"]) && isset($_POST["password"]))
{
    $conn = CreateConnection();

    if ($conn == FALSE)
    {
        echo "No Connection";
        die();
    }

    try
    {
        // WikiPages
        $sql = "CREATE TABLE WikiPages (
            Id INTEGER PRIMARY KEY AUTOINCREMENT, 
            Title TEXT NOT NULL,
            Identifier TEXT NOT NULL UNIQUE,
            CreateDate TIMESTAMP
            )";
        
        $conn->exec($sql);

        $sql = "CREATE INDEX WikiPagesIdentifer on WikiPages (Identifier)";
        $conn->exec($sql);

        $sql = "CREATE INDEX WikiPagesCreateDate on WikiPages (CreateDate)";
        $conn->exec($sql);


        // WikiPageMarkdown
        $sql = "CREATE TABLE WikiPageMarkdown (
            Id INTEGER PRIMARY KEY AUTOINCREMENT, 
            Version INTEGER,
            WikiPageId INTEGER,
            Markdown TEXT,
            Username TEXT,
            CreateDate TIMESTAMP
            )";
        $conn->exec($sql);

        $sql = "CREATE INDEX WikiPageMarkdownVersion on WikiPageMarkdown (Version)";
        $conn->exec($sql);

        $sql = "CREATE INDEX WikiPageMarkdownPageId on WikiPageMarkdown (WikiPageId)";
        $conn->exec($sql);

        $sql = "CREATE INDEX WikiPageMarkdownPageCreateDate on WikiPageMarkdown (CreateDate)";
        $conn->exec($sql);
            
        // WikiPageHTML
        $sql = "CREATE TABLE WikiPageHTML (
            Id INTEGER PRIMARY KEY AUTOINCREMENT,  
            Version INTEGER,
            WikiPageId INTEGER,
            Html TEXT,
            CreateDate TIMESTAMP
            )";
        $conn->exec($sql);

        $sql = "CREATE INDEX WikiPageHTMLVersion on WikiPageHTML (Version)";
        $conn->exec($sql);

        $sql = "CREATE INDEX WikiPageHTMLPageId on WikiPageHTML (WikiPageId)";
        $conn->exec($sql);

        $sql = "CREATE INDEX WikiPageHTMLPageCreateDate on WikiPageHTML (CreateDate)";
        $conn->exec($sql);


        // User Accounts
         $sql = "CREATE TABLE Users (
            Id INTEGER PRIMARY KEY AUTOINCREMENT, 
            Username TEXT NOT NULL UNIQUE,
            Password TEXT NOT NULL
            )";
        $conn->exec($sql);
        $sql = "INSERT INTO Users (Username, Password)
            VALUES ('" . $_POST["name"] . "', '" . password_hash($_POST["password"], PASSWORD_DEFAULT) . "')";
        $databaseConnection->exec($sql);



        echo "Complete";
    }
    catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
    die();
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Install</title>
  </head>
  <body>
    Enter user account details to be used:

      <form action="<?php echo getCurrentURL() ?>" method="post">
        Name: <input type="text" name="name"><br>
        Password: <input type="password" name="password"><br>
      <input type="submit" value="Install">
    </form>   

  </body>
</html>