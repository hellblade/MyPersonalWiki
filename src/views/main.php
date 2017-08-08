<!DOCTYPE html>
<html lang="en">
  <head>
    <link href="<?php echo constructLocation("resources/style.css"); ?>" type="text/css" rel="Stylesheet">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title> <?php echo $display["pageName"]; ?> - My Wiki</title>
  </head>
  <body>
      <div id='main'>
      <?php
        require "navigation_block.php";
      ?>

        <article>
          <div id="actions">
            Actions:
            <a href="<?php echo constructLocation("add"); ?>">Create New Page</a>
            |
            <a href="<?php echo constructLocation("edit/" . $display["pageId"]); ?>">Edit Page</a>
          </div>
           <h1><?php echo $display["pageName"]; ?></h1>
          <?php
              if (isset($display["pageView"]))
              {
                  useView($display["pageView"]);
              }
              elseif (isset($display["pageText"]))
              {
                  echo $display["pageText"];
              }
          ?>
        </article>
        <?php 
              #if (isset($display["sideBlock"]))
              {
                  require "side_block.php";
              }
        ?>
        
      </div>
  </body>
</html>