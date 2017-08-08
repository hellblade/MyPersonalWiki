<form action="<?php echo constructLocation("save"); ?>" method="post">
    Page Name:<input type="text" name="pageName" value="<?php echo $display["pageId"]; ?>" />
    <textarea name="wikiText"># New Page</textarea>
    <input type="submit" value="Save">
    <input class="button" type="button" onclick="window.history.back();" value="Cancel" />
</form>