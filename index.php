<?php
include_once("config.php");
include_once("WebCrawler.php");

$obj = new WebCrawler();
$data = $obj->parser("https://www.forbes.com/advisor/business/best-designed-websites/");
echo "<pre>";
print_r($data);
echo "</pre>";
?>