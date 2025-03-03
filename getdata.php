<?php
$data = file_get_contents("https://dblp.org/search/publ/api?q=test&format=json");
?>
<pre>
    <?php echo $data ?>
</pre>