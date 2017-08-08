<?php
  function displayPage($routes)
  {

      if (isset($_POST["name"]) && isset($_POST["password"]))
      {
          LogMessage('login', "Log on attempt (" . @$_SERVER['REMOTE_ADDR'] . ") - " . @$_POST["name"] . ", " . $_POST["password"]);

          if (VerifyUser($_POST["name"], $_POST["password"]))
          {
              $_SESSION["loggedIn"] = true;
              $_SESSION["user"] = $_POST["name"];
          }
      }

    if (isset($_SESSION["loggedIn"]))
    {
      header('Location: '. constructLocation(""));
      die();
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <link href="resources/style.css" type="text/css" rel="Stylesheet">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>My Wiki - Log In</title>
  </head>
  <body>

      <form action="<?php echo getCurrentURL() ?>" method="post">
        Name: <input type="text" name="name"><br>
        Password: <input type="password" name="password"><br>
      <input type="submit">
    </form>   

  </body>
</html>