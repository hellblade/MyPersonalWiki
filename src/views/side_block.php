<aside>
        <?php
        if (isset($display["sidePageDetails"])) {
        ?>
        <h1><?php echo $display["pageName"]; ?></h1>
        <p>
                Last Updated: <?php echo date("d-m-Y H:i:s", strtotime($display["sidePageDetails"]["lastUpdate"])); ?><br/>
        </p>
        <p>
                <a href="<?php echo constructLocation("history/" . $display["pageId"]); ?>">History</a>
        </p>
        <?php } ?>

        <?php

        if (isset($display["sidePageMarkdownRef"]))
        {
                require "markdown_ref.php";
        }
        ?>
</aside>
