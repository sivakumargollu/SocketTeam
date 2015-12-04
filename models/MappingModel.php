<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProductMapping
 *
 * @author nagulmeera
 */
class MappingModel extends CFormModel{
    public $contractId = null;
    public $products = array();
    public $distributorId = null;
    public $objectId = null;
    public $benutzer_id = null;
    public $contractorId = null;
    public $agentSine = null;
    public $agentDutyCode = null;
        public $contracts = array();

    public function __construct() {
        $this->objectId = isset($_REQUEST['object_id']) ? $_REQUEST['object_id'] : $this->addError('Object ID','Object ID should not be empty.');
        $this->benutzer_id = isset($_REQUEST['benutzer_id']) ?  $_REQUEST['benutzer_id'] : $this->addError("User ID ", "User Id should not be empty.");
        $this->contractorId = isset($_REQUEST['contractorId']) ? $_REQUEST['contractorId'] : $this->addError("ContractorID", "Contractor ID should not empty.");
        $this->agentSine = Yii::app()->params['agentSine'];
        $this->agentDutyCode = Yii::app()->params['agentDutyCode'];
    }
    public function getProductsList() {
        $xmlinput  = $this->prepareProductList();
        $xmloutput = ContractorModel::sendXML($xmlinput);
        //echo "XML".$xmlinput."\n".$xmloutput;
        $products = $this->parseProductList($xmloutput);
        return $products;
    }
    public function setContracts($contracts) {
        $displayContracts = array();
        foreach ($contracts as $contractId => $details){
            $displayContracts[$details['contractKey']] = $details['CustomerType'];
        }
        $this->contracts = $displayContracts;
    }
    public function prepareProductList() {
        $xmlinput = '<?xml version="1.0" encoding="UTF-8"?>
                    <OTA_HotelDescriptiveInfoRQ xmlns="http://www.opentravel.org/OTA/2003/05"
                                                TimeStamp="'.date('Y-m-d\TH:i:s').'" Target="Production" Version="3.30"
                                                PrimaryLangID="en">
                        <POS>
                            <Source AgentDutyCode ="'.$this->agentDutyCode.'" AgentSine ="'.$this->agentSine.'">
                                    <RequestorID ID ="'.$this->objectId.'" ID_Context ="CLTZ" Type="10"/>
                                    <BookingChannel Type ="4"/>
                                </Source>
                        </POS>
                        <HotelDescriptiveInfos>
                            <HotelDescriptiveInfo HotelCode="'.$this->objectId.'">
                                <ContentInfos>
                                    <ContentInfo Name="ProductList"/>
                                </ContentInfos>
                            </HotelDescriptiveInfo>
                        </HotelDescriptiveInfos>
                    </OTA_HotelDescriptiveInfoRQ>';
        return $xmlinput;
    }
    public function parseProductList($xmloutput) {
        $dom = new DOMDocument();
        $products = array();
        $displayProducts = array();
        if(stripos($xmloutput, "Errors")){
            throw new Excetption("Error occured while getting Products list");
        }
        if($dom->loadXML(trim($xmloutput))){
            $ratePlansArray = $dom->getElementsByTagName("RatePlans");
            foreach ($ratePlansArray as $ratePlans) {
                if($ratePlans->nodeName =='RatePlans' && $ratePlans->nodeType == 1 && $ratePlans->hasChildNodes()){
                    $ratePlanArray = $ratePlans->childNodes;
                    foreach ($ratePlanArray as $ratePlan) {
                        if($ratePlan->nodeName =='RatePlan' && $ratePlan->nodeType==1 && $ratePlan->hasChildNodes()){
                            $inventoryAllocatedInd = $ratePlan->getAttribute('InventoryAllocatedInd');
                            $marketCode = $ratePlan->getAttribute('MarketCode');
                            $ratePlaneCategory = $ratePlan->getAttribute('RatePlanCategory');
                            $ratePlanID = $ratePlan->getAttribute('RatePlanID');
                            $ratePlanType = $ratePlan->getAttribute("RatePlanType");
                            $ratePlanStatusType = $ratePlan->getAttribute("RatePlanStatusType");
                            $ratePlanCode = $ratePlan->getAttribute('RatePlanCode');
                            $ratePlanChildsArray = $ratePlan->childNodes;
                            foreach ($ratePlanChildsArray as $descriptions) {
                                if( $descriptions->nodeName=='Description' && $descriptions->nodeType==1 && $descriptions->hasChildNodes() && $descriptions->hasAttribute('Name') && $descriptions->getAttribute('Name') == "txt:name"){
                                    $textNodes = $descriptions->childNodes;
                                    foreach ($textNodes as $textNode) {
                                        if($textNode->nodeName =="Text" && $textNode->nodeType ==1 && $textNode->hasAttribute('Language') && $textNode->getAttribute('Language') == "en"){
                                            $name = $textNode->nodeValue;
                                        }
                                    }
                                } 
                            }
                            $products[$ratePlanID]['name'] = $name;
                            $displayProducts[$ratePlanID] = $name;
                            $products[$ratePlanID]['InventoryAllocatedInd'] = $inventoryAllocatedInd;
                            $products[$ratePlanID]['MarketCode'] = $marketCode;
                            $products[$ratePlanID]['RatePlanCategory'] = $ratePlaneCategory;
                            $products[$ratePlanID]['RatePlanType'] = $ratePlanType;
                            $products[$ratePlanID]['RatePlanStatusType'] = $ratePlanStatusType;
                            $products[$ratePlanID]['RatePlanCode'] = $ratePlanCode;
                        }
                    }
                }
            }
        }else{
            throw  new Exception("Getting products XML is failed to load.");
        }
        $this->products = $displayProducts;
        return $products;
    }
}
