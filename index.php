<?php
include_once("config.php");
include_once("WebCrawler.php");

$obj = new WebCrawler();
$data = $obj->parser("https://www.algoberry.com");
echo "<pre>";
print_r($data);
echo "</pre>";
?>