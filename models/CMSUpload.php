<?php
	class CMSUpload{

        function uploadFileToCMS($Files, $fileName) {
                   //echo "Here the condition true";
                include_once "/www/htdocs/service/CMLXML/CMLFileMetaDataRQ.class.php";
                include_once "/www/htdocs/service/CMLXML/CMLConnection.class.php";
		
		$dirName = "CSI/CorporateContracts/";
		$file = $fileName.date('Hms');
		$uploadedFile = $Files->name;
		$uploadedFile = explode('.',$uploadedFile);
		$file .= ".".$uploadedFile[1];
                $source = new CMLSource();
                $source->setAgentSine('74');
                $source->setAgentDutyCode('18941305ba1e3572');
                $source->setUserId('369');

		$bookingChannel = new CMLBookingChannel();
		$bookingChannel->setType(4);

                $pos = new CMLPOS();
                $pos->addSource($source);
		$pos->addBookingChannel($bookingChannel);

                $retainFileName = new CMLRetainFileName();
                $retainFileName->setStatus('0');

                $accessType = new CMLAccessTypes();
                $accessType->setLevel("0");
                $accessType->setType("0");

                $overWrite = new CMLOverWrite();
                $overWrite->setStatus('0');

                $fixedPath = new CMLFixedPathReservation();
                $fixedPath->setPath($dirName);
                $fixedPath->setKey("abc");

                $metaData = new CMLMetaData();

                $fileName = new CMLFileName();
                $fileName->setValue($file);
                $details = new CMLDetails();
                $details->setSize($Files->size);
                $details->setType($uploadedFile[1]);

                $category = new CMLCategory();
                $category->setName("Test category");
                $category->setId('123');

                $description = new CMLDescription();
                $description->setValue("CorporateContract pdfs");


                $metaData->addFileName($fileName);
                $metaData->addDetails($details);
                $metaData->addCategory($category);
                $metaData->addDescription($description);

                $RQ = new CMLFileMetaDataRQ();
                $RQ->addPos($pos);
                $RQ->addOverWrite($overWrite);
                $RQ->addAccessType($accessType);
                $RQ->addFixedPathReservation($fixedPath);
                $RQ->addRetainFileName($retainFileName);
                $RQ->addMetaData($metaData);

                $cmlRQ = $RQ->getCMLFileMetaDataRQ();


                $paramArray = array();
                $paramArray['textData'] =  urlencode($cmlRQ);
                $paramArray['xmlFile'] =  "@".$Files->tempName;

                $conn = new CMLConnection();
                $res = $conn->sendXmlRequestToCML($paramArray);
                //echo "coming";
                file_put_contents("/tmp/nagu.txt", $cmlRQ."\n".$res);
		#mail("nagulmeera.shaik@cultuzz.com","Image uploading..",$cmlRQ."\n\n\n".$res);
		$pos = strpos($res, "Success");

		if($pos === false){
			mail("nagulmeera.shaik@cultuzz.com","Image uploading failed",$cmlRQ."\n\n\n".$res);	
		}
		return $this->parseCMLFileMetaDataRQ($res);
        }

	function parseCMLFileMetaDataRQ($xmlRQ) {
		$dom = new DOMDocument();
		$returnArray = array();
		if($dom->loadXML(trim($xmlRQ))) {
			$root = $dom->documentElement;
			$base = null;
			$base = $root->nodeName;
			$childs = null;
			$subBase = null;
			if($root->hasChildNodes()) {
				$childs = $root->childNodes;
				foreach ($childs as $child) {
					if($child->nodeType  == 1 && $child->nodeName == 'Error') {
						$subBase = $child->nodeName;
						if($child->hasAttribute("message"))
							$returnArray['message'] = $child->getAttribute('message');
					} else if($child->nodeType  == 1 && $child->nodeName == 'Success') {
						$subBase = $child->nodeName;
					}
					$returnArray['Status'] = $subBase;
					$subChilds = null;
					if($child->nodeType == 1 && $child->hasChildNodes()) {
						$subChilds = $child->childNodes;
						foreach ($subChilds as $contents ) {
							if($contents->nodeType == 1) 
								$returnArray[$contents->nodeName] = $contents->nodeValue;
						}
					}
				}
			}
		} else {
			$returnArray['Status'] = "Error";
		}
		return $returnArray;
		
	}
}

?>
