<?php
//--
$outerHead = "<head>";
$outerHeadLength = strlen($outerHead);
$outerHeadStart = 0;

$innerHead = "</head>";
$innerHeadLength = strlen($innerHead);
$innerHeadStart = 0;
//--

//--
$outerTitle = "<title>";
$outerTitleLength = strlen($outerTitle);
$outerTitleStart = 0;

$innerTitle = "</title>";
$innerTitleLength = strlen($innerTitle);
$innerTitleStart = 0;
//--

//--
$outerMeta = "<meta";
$innerMeta = ">";
$metaPointer = 0;
//--

//--
$metaNameBase = "name=";
$metaNamePointer = 0;
//--

//--
$metaPropertyBase = "property=";
$metaPropertyPointer = 0;
//--

//--
$metaContentBase = "content=";
$metaContentPointer = 0;
//--

//--
$hrefTag = array();
$hrefTag[0] = "<a";
$hrefTag[1] = "href";
$hrefTag[2] = "=";
$hrefTag[3] = ">";
$hrefTagCountStart = 0;
$hrefTagCountFinal = count($hrefTag);
$hrefTagLengthStart = 0;
$hrefTagLengthFinal = strlen($hrefTag[0]);
$hrefTagPointer =& $hrefTag[0];
//--

//--
$imgTag = array();
$imgTag[0] = "<img";
$imgTag[1] = "src";
$imgTag[2] = "=";
$imgTag[3] = ">";
$imgTagCountStart = 0;
$imgTagCountFinal = count($imgTag);
$imgTagLengthStart = 0;
$imgTagLengthFinal = strlen($imgTag[0]);
$imgTagPointer =& $imgTag[0];
//--

//--
$crawlOptions = array(
CURLOPT_RETURNTRANSFER => true,     		// return web page
CURLOPT_HEADER         => false,    		// don't return headers
CURLOPT_FOLLOWLOCATION => true,     		// follow redirects
CURLOPT_ENCODING       => "",       		// handle all encodings
CURLOPT_USERAGENT      => "algoberrybot", 	// who am i
CURLOPT_AUTOREFERER    => true,     		// set referer on redirect
CURLOPT_CONNECTTIMEOUT => 10,      			// timeout on connect
CURLOPT_TIMEOUT        => 10,      			// timeout on response
CURLOPT_MAXREDIRS      => 0       			// stop after 10 redirects
);
//--
?>