<?php
$data = file_get_contents("https://dblp.org/search/publ/api?q=a&h=100&format=json");
    
file_put_contents("data.json",$data);
?>
<pre>
    <?php echo $data ?>
</pre>