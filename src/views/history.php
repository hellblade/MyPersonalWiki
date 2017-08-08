<ul>
<?php foreach ($display["versions"] as $version) { ?>
    <li>
        <a href="<?php echo constructLocation("page/" . $display["pageId"] . "/" . $version["Version"]); ?>">
            <?php echo str_pad($version["Version"] , 4, " ", STR_PAD_LEFT). " on " . date("d-m-Y H:i:s", strtotime($version["CreateDate"])); ?>
        </a>
    </li>
<?php } ?>
</uk>

