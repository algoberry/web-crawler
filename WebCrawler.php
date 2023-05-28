<?php
Class WebCrawler {

    public $webPageContent;

    function parser($directoryURL) {
        $result = array();
        global $outerHead, $outerHeadLength, $outerHeadStart;
        global $innerHead, $innerHeadLength, $innerHeadStart;
        global $outerTitle, $outerTitleLength, $outerTitleStart;
        global $innerTitle, $innerTitleLength, $innerTitleStart;

        global $outerMeta, $innerMeta, $metaPointer;
        global $metaNameBase, $metaNamePointer;
        global $metaPropertyBase, $metaPropertyPointer;
        global $metaContentBase, $metaContentPointer;

        global $hrefTag, $hrefTagCountStart, $hrefTagCountFinal, $hrefTagLengthStart, $hrefTagLengthFinal, $hrefTagPointer;
        global $imgTag, $imgTagCountStart, $imgTagCountFinal, $imgTagLengthStart, $imgTagLengthFinal, $imgTagPointer;
        global $crawlOptions;

        if($directoryURL != "") {
            if(filter_var($directoryURL,FILTER_VALIDATE_URL) == true) {
                $hrefURL = "";
                $imgURL = "";
                $previousDirectoryCount = 0;
                $currentDirectoryCount = 0;
                $singleSlashCount = 0;
                $doubleSlashCount = 0;

                $urlParser = preg_split("/\//",$directoryURL);
                $dump = parse_url($directoryURL);
                $ownHost = $dump["host"]; 

                $curlObject = curl_init($directoryURL);
                curl_setopt_array($curlObject,$crawlOptions);
                $this->webPageContent = curl_exec($curlObject);
                $errorNumber = curl_errno($curlObject);
                curl_close($curlObject);

                if($errorNumber == 0) {
                    //--
                    $counter = 0;
                    $contentLength = strlen($this->webPageContent);
                    while($counter < $contentLength) {
                        $character = $this->webPageContent[$counter];
                        if($character == " ") {	
                            $counter++;	
                            continue;
                        }
                        if($outerHead[$outerHeadStart] == $character) {
                            $outerHeadStart++;
                            if($outerHeadStart == $outerHeadLength) {
                                $outerHeadStart = 0;
                                $counter++;
                                while($counter < $contentLength) {
                                    $character = $this->webPageContent[$counter];
                                    if($character == " ") {	
                                        $counter++;	
                                        continue;
                                    }
                                    //--
                                    if($outerTitle[$outerTitleStart] == $character) {
                                        $outerTitleStart++;
                                        if($outerTitleStart == $outerTitleLength) {
                                            $outerTitleStart = 0;
                                            $counter++;
                                            $startPosition = $counter;
                                            while($counter < $contentLength) {
                                                $character = $this->webPageContent[$counter];
                                                if($character == " ") {	
                                                    $counter++;	
                                                    continue;
                                                }
                                                if($innerTitle[$innerTitleStart] == $character) {
                                                    if($innerTitleStart == 0) {
                                                        $endPosition = $counter - 1;
                                                    }
                                                    $innerTitleStart++;
                                                    if($innerTitleStart == $innerTitleLength) {
                                                        $innerTitleStart = 0;
                                                        $result["title"] = $this->collectData($startPosition,$endPosition);
                                                        break;
                                                    }
                                                }
                                                else
                                                {
                                                    $innerTitleStart = 0;
                                                }
                                                $counter++;
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $outerTitleStart = 0;
                                    }
                                    //--
                                    //--
                                    if($outerMeta[$metaPointer] == $character) {
                                        $metaPointer++;
                                        if($metaPointer == strlen($outerMeta)) {
                                            $metaPointer = 0;
                                            $startPosition = 0;
                                            $endPosition = 0;
                                            $metaType = "";
                                            $metaValue = "";
                                            $counter++;
                                            while($counter < $contentLength) {
                                                $character = $this->webPageContent[$counter];
                                                if($character == " ") {	
                                                    $counter++;	
                                                    continue;
                                                }
                                                if($metaNameBase[$metaNamePointer] == $character) {
                                                    $metaNamePointer++;
                                                    if($metaNamePointer == strlen($metaNameBase)) {
                                                        $metaNamePointer = 0;
                                                        $counter++;
                                                        while($counter < $contentLength) {
                                                            $character = $this->webPageContent[$counter];
                                                            if($character == "\"" || $character == "'") {
                                                                if($startPosition == 0) {
                                                                    $startPosition = $counter + 1;
                                                                }
                                                                else if($endPosition == 0) {
                                                                    $endPosition = $counter - 1;
                                                                    break;
                                                                }
                                                            }
                                                            $counter++;
                                                        }
                                                        $metaType = $this->collectData($startPosition,$endPosition);
                                                        $startPosition = 0;
                                                        $endPosition = 0;
                                                    }
                                                }
                                                else
                                                {
                                                    $metaNamePointer = 0;
                                                }
                                                if($metaPropertyBase[$metaPropertyPointer] == $character) {
                                                    $metaPropertyPointer++;
                                                    if($metaPropertyPointer == strlen($metaPropertyBase)) {
                                                        $metaPropertyPointer = 0;
                                                        $counter++;
                                                        while($counter < $contentLength) {
                                                            $character = $this->webPageContent[$counter];
                                                            if($character == "\"" || $character == "'") {
                                                                if($startPosition == 0) {
                                                                    $startPosition = $counter + 1;
                                                                }
                                                                else if($endPosition == 0) {
                                                                    $endPosition = $counter - 1;
                                                                    break;
                                                                }
                                                            }
                                                            $counter++;
                                                        }
                                                        $metaType = $this->collectData($startPosition,$endPosition);
                                                        $startPosition = 0;
                                                        $endPosition = 0;
                                                    }
                                                }
                                                else
                                                {
                                                    $metaPropertyPointer = 0;
                                                }
                                                if($metaContentBase[$metaContentPointer] == $character) {
                                                    $metaContentPointer++;
                                                    if($metaContentPointer == strlen($metaContentBase)) {
                                                        $metaContentPointer = 0;
                                                        $counter++;
                                                        while($counter < $contentLength) {
                                                            $character = $this->webPageContent[$counter];
                                                            if($character == "\"" || $character == "'") {
                                                                if($startPosition == 0) {
                                                                    $startPosition = $counter + 1;
                                                                }
                                                                else if($endPosition == 0) {
                                                                    $endPosition = $counter - 1;
                                                                    break;
                                                                }
                                                            }
                                                            $counter++;
                                                        }
                                                        $metaValue = $this->collectData($startPosition,$endPosition);
                                                        $startPosition = 0;
                                                        $endPosition = 0;
                                                    }
                                                }
                                                else
                                                {
                                                    $metaContentPointer = 0;
                                                }
                                                if($innerMeta == $character) {
                                                    if($metaType != "" && $metaValue != "") {
                                                        $result["meta"][$metaType] = $metaValue;
                                                    }
                                                    break;
                                                }
                                                $counter++;
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $metaPointer = 0;
                                    }
                                    //--
                                    //--
                                    if($innerHead[$innerHeadStart] == $character) {
                                        $innerHeadStart++;
                                        if($innerHeadStart == $innerHeadLength) {
                                            $innerHeadStart = 0;
                                            break;
                                        }
                                    }
                                    else
                                    {
                                        $innerHeadStart = 0;
                                    }
                                    //--
                                    $counter++;
                                }
                                break;
                            }
                        }
                        else
                        {
                            $outerHeadStart = 0;
                        }
                        $counter++;
                    }
                    //--
                    
                    //--
                    $counter++;
                    while($counter < $contentLength) {
                        $character = $this->webPageContent[$counter];
                        if($character == "") {
                            $counter++;	
                            continue;
                        }
                        if($hrefTagPointer[$hrefTagLengthStart] == $character) {
                            $hrefTagLengthStart++;
                            if($hrefTagLengthStart == $hrefTagLengthFinal) {
                                $hrefTagCountStart++;
                                if($hrefTagCountStart == $hrefTagCountFinal) {
                                    $hrefTagCountStart = 0;
                                    if($previousDirectoryCount > 0 || $currentDirectoryCount > 0 || $singleSlashCount > 0 || $doubleSlashCount > 0) {
                                        if($previousDirectoryCount > 0) {
                                            $tempString = "";
                                            $tempCount = 0;
                                            $tempTotal = count($urlParser) - $previousDirectoryCount;
                                            while($tempCount < $tempTotal) {
                                                $tempString .= $urlParser[$tempCount]."/";
                                                $tempCount++;
                                            }
                                            $hrefURL = $tempString.$hrefURL;
                                        }
                                        else if($currentDirectoryCount > 0) {
                                            $hrefURL = $directoryURL."/".$hrefURL;
                                        }
                                        else if($singleSlashCount > 0) {
                                            $hrefURL = $directoryURL."/".$hrefURL;
                                        }
                                        else if($doubleSlashCount > 0) {
                                            $hrefURL = $urlParser[0]."//".$hrefURL;
                                        }
                                    }
                                    if(filter_var($hrefURL,FILTER_VALIDATE_URL) == true) {
                                        $dump = parse_url($hrefURL);
                                        if($ownHost == $dump["host"]) {
                                            $result["href"]["own"][] = $hrefURL;
                                        }
                                        else
                                        {
                                            $result["href"]["other"][] = $hrefURL;
                                        }
                                    }
                                }
                                else if($hrefTagCountStart == 3) {
                                    //--
                                    $hrefURL = "";
                                    $previousDirectoryCount = 0;
                                    $currentDirectoryCount = 0;
                                    $singleSlashCount = 0;
                                    $doubleSlashCount = 0;
                                    //--
                    
                                    $dotCount = 0;
                                    $slashCount = 0;
                    
                                    $firstCharacter = "";
                                    $leftPosition = 0;
                                    $rightPosition = 0;
                                    
                                    $counter++;
                                    while($counter < $contentLength) {
                                        $character = $this->webPageContent[$counter];
                                        if($character == "\"" || $character == "'") {
                                            if($firstCharacter == "") {
                                                $firstCharacter = $character;
                                                $leftPosition = $counter + 1;
                                            }
                                            else if($firstCharacter == $character) {
                                                $rightPosition = $counter - 1;
                                                break;
                                            }
                                        }
                                        else if($character == "#") {
                                            $hrefTagCountStart = 0;
                                            break;
                                        }
                                        $counter++;
                                    }
                                    while($leftPosition <= $rightPosition) {
                                        $character = $this->webPageContent[$leftPosition];
                                        if($hrefURL != "") {
                                            $hrefURL .= $character;
                                        }
                                        else if($character == "." || $character == "/") {
                                            if($character == ".") {
                                                $dotCount++;
                                                $slashCount = 0;
                                            }
                                            else if($character == "/") {
                                                $slashCount++;
                                                if($dotCount == 2 && $slashCount == 1) {
                                                    $previousDirectoryCount++;
                                                }
                                                else if($dotCount == 1 && $slashCount == 1) {
                                                    $currentDirectoryCount++;
                                                }
                                                else if($dotCount == 0 && $slashCount == 1) {
                                                    $singleSlashCount++;
                                                }
                                                else if($dotCount == 0 && $slashCount == 2) {
                                                    $singleSlashCount = 0;
                                                    $doubleSlashCount++;
                                                }
                                            }
                                        }
                                        else
                                        {
                                            $hrefURL .= $character;
                                        }
                                        $leftPosition++;
                                    }
                                }
                                $hrefTagLengthStart = 0;
                                $hrefTagLengthFinal = strlen($hrefTag[$hrefTagCountStart]);
                                $hrefTagPointer =& $hrefTag[$hrefTagCountStart];
                            }
                        }
                        else
                        {
                            $hrefTagLengthStart = 0;
                        }
                        if($imgTagPointer[$imgTagLengthStart] == $character) {
                            $imgTagLengthStart++;
                            if($imgTagLengthStart == $imgTagLengthFinal) {
                                $imgTagCountStart++;
                                if($imgTagCountStart == $imgTagCountFinal) {
                                    $imgTagCountStart = 0;
                                    if($previousDirectoryCount > 0 || $currentDirectoryCount > 0 || $singleSlashCount > 0 || $doubleSlashCount > 0) {
                                        if($previousDirectoryCount > 0) {
                                            $tempString = "";
                                            $tempCount = 0;
                                            $tempTotal = count($urlParser) - $previousDirectoryCount;
                                            while($tempCount < $tempTotal) {
                                                $tempString .= $urlParser[$tempCount]."/";
                                                $tempCount++;
                                            }
                                            $hrefURL = $tempString.$hrefURL;
                                        }
                                        else if($currentDirectoryCount > 0) {
                                            $hrefURL = $directoryURL."/".$hrefURL;
                                        }
                                        else if($singleSlashCount > 0) {
                                            $hrefURL = $directoryURL."/".$hrefURL;
                                        }
                                        else if($doubleSlashCount > 0) {
                                            $hrefURL = $urlParser[0]."//".$hrefURL;
                                        }
                                    }
                                    if(filter_var($hrefURL,FILTER_VALIDATE_URL) == true) {
                                        $result["img"][] = $hrefURL;
                                    }
                                }
                                else if($imgTagCountStart == 3) {
                                    //--
                                    $hrefURL = "";
                                    $previousDirectoryCount = 0;
                                    $currentDirectoryCount = 0;
                                    $singleSlashCount = 0;
                                    $doubleSlashCount = 0;
                                    //--
                    
                                    $dotCount = 0;
                                    $slashCount = 0;
                    
                                    $firstCharacter = "";
                                    $leftPosition = 0;
                                    $rightPosition = 0;
                                    
                                    $counter++;
                                    while($counter < $contentLength) {
                                        $character = $this->webPageContent[$counter];
                                        if($character == "\"" || $character == "'") {
                                            if($firstCharacter == "") {
                                                $firstCharacter = $character;
                                                $leftPosition = $counter + 1;
                                            }
                                            else if($firstCharacter == $character) {
                                                $rightPosition = $counter - 1;
                                                break;
                                            }
                                        }
                                        else if($character == "#") {
                                            $imgTagCountStart = 0;
                                            break;
                                        }
                                        $counter++;
                                    }
                                    while($leftPosition <= $rightPosition) {
                                        $character = $this->webPageContent[$leftPosition];
                                        if($hrefURL != "") {
                                            $hrefURL .= $character;
                                        }
                                        else if($character == "." || $character == "/") {
                                            if($character == ".") {
                                                $dotCount++;
                                                $slashCount = 0;
                                            }
                                            else if($character == "/") {
                                                $slashCount++;
                                                if($dotCount == 2 && $slashCount == 1) {
                                                    $previousDirectoryCount++;
                                                }
                                                else if($dotCount == 1 && $slashCount == 1) {
                                                    $currentDirectoryCount++;
                                                }
                                                else if($dotCount == 0 && $slashCount == 1) {
                                                    $singleSlashCount++;
                                                }
                                                else if($dotCount == 0 && $slashCount == 2) {
                                                    $singleSlashCount = 0;
                                                    $doubleSlashCount++;
                                                }
                                            }
                                        }
                                        else
                                        {
                                            $hrefURL .= $character;
                                        }
                                        $leftPosition++;
                                    }
                                }
                                $imgTagLengthStart = 0;
                                $imgTagLengthFinal = strlen($imgTag[$imgTagCountStart]);
                                $imgTagPointer =& $imgTag[$imgTagCountStart];
                            }
                        }
                        else
                        {
                            $imgTagLengthStart = 0;
                        }
                        $counter++;
                    }
                   //--
                }
            }
        }
        return $result;
    }
    
    function collectData($start, $end) {
        $temp = "";
        while($start <= $end) {
            $temp .= $this->webPageContent[$start];
            $start++;
        }
        return $temp;
    }
}
?>