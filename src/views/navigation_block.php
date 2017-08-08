<nav>
    <h1>My Wiki</h1>

<form class="search" action="<?php echo ConstructLocation("search"); ?>" method="get">
    <input type="text" name="search" placeholder="Search" value="<?php if (isset($_GET["search"])) echo htmlspecialchars($_GET["search"]); ?>">
     <input type="submit" value="Search">
</form>

<a href="<?php echo constructLocation("page"); ?>">Home</a>

<a href="<?php echo constructLocation("search/recent"); ?>">Recent Changes</a>

<a href="<?php echo constructLocation("search/all"); ?>">All Pages</a>

  <a href="<?php echo constructLocation("logout"); ?>">Logout</a>
</nav>