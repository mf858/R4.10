<?php
    $file = file_get_contents("https://dblp.org/search/author/api?q=orazio");
    echo "<pre>";
    print_r($file);
    echo "</pre>";
?>