<form action="<?php echo constructLocation("save/" . $display["pageId"]); ?>" method="post">
    Page Name:<input type="text" name="pageName" value="<?php echo $display["pageTitle"]; ?>" />
    <textarea name="wikiText"><?php echo $display["pageText"];  ?></textarea>
    <input type="submit" value="Save">
    <input class="button" type="button" onclick="window.location.assign('<?php echo constructLocation("page/" . $display["pageId"]); ?>')" value="Cancel" />
</form>