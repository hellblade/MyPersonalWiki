<?php


echo '<ul>';

foreach($display["pageList"] as $page)
{
    echo '<li><a href="' . constructLocation("home/" . $page['id']) . '">' . $page["title"] . '</a></li>';
}

echo '</ul>';