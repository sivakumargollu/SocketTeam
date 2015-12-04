<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ContractorModel
 *
 * @author nagulmeera
 */
include_once '/www/htdocs/service/cultchannel/distribution/channel_msk/HttpConnector.php';

class ContractorModel extends CFormModel {

    private $agentSine = null;
    private $agentDutyCode = null;
    public $object_id = null;
    public $benutzer_id = null;
    public $contractorName = null;
    public $contractorZipCode = null;
    public $contractorPlace = null;
    public $contractorCountry = null;
    public $contractorChannel = null;

    public function rules() {
        return (
                array(
                    array('contractorName', 'required',),
                    array('contractorZipCode', 'required'),
                    array('contractorPlace', 'required'),
                    array('contractorCountry', 'required'),
                    array('contractorChannel', 'required'),
                    array('contractorName', 'length', 'min' => 3, 'max' => 50),
                    array('contractorPlace', 'length', 'min' => 3, 'max' => 50),
                    array('contractorZipCode', 'numerical', 'integerOnly' => true, 'min' => 100000, 'max' => 999999),
                )
                );
    }

    public function __construct() {
        $this->object_id = isset($_REQUEST['object_id']) ? $_REQUEST['object_id'] : parent::addError("ObjectId", "Object ID should not be null");
        $this->benutzer_id = isset($_REQUEST['benutzer_id']) ? $_REQUEST['benutzer_id'] : parent::addError("benutzer_id", "User ID should not be null");
        $this->agentSine = Yii::app()->params['agentSine'];
        $this->agentDutyCode = Yii::app()->params['agentDutyCode'];
    }

    public function getContractors() {
        $xmlinput = $this->prepareContractorsListXML();
        $xmloutput = $this->sendXML($xmlinput);
        $contractors = $this->parseContractorsList($xmloutput);
        return $contractors;
    }
    public function setContractorDetails() {
        $xmlinput = $this->prepareSetContractorXml();
        $xmloutput = $this->sendXML($xmlinput);
        $response = $this->pareseResponse($xmloutput);
        return $response;
    }
    public function getContaractorDetails($contractID) {
        $xmlinput = $this->prepareContractorsListXML($contractID);
        $xmloutput = $this->sendXML($xmlinput);
        $contractorDetails = $this->parseContractorsList($xmloutput);
        $this->contractorChannel = $contractorDetails[$contractID]['Distributor'];
        $this->contractorCountry = $contractorDetails[$contractID]['Country'];
        $this->contractorName = $contractorDetails[$contractID]['ContractorName'];
        $this->contractorPlace = $contractorDetails[$contractID]['Place'];
        $this->contractorZipCode = $contractorDetails[$contractID]['ZipCode'];
        
        //print_r($contractorDetails[$contractID]['contracts']);
        return $contractorDetails[$contractID]['contracts'];
    }

    public function prepareContractorsListXML($id = "All") {
        $xml = '';
        $xml .= '<?xml version="1.0" encoding="UTF-8"?>
                    <OTA_ReadRQ xmlns="http://www.opentravel.org/OTA/2003/05"
                    TimeStamp="' . date('Y-m-d\TH:i:s') . '" Target="Production" Version="3.30"
                    PrimaryLangID="de" ReservationType="3">
                        <POS>
                            <Source AgentDutyCode="' . $this->agentDutyCode . '" AgentSine="' . $this->agentSine . '">
                                <RequestorID ID="' . $this->object_id . '" Type="10"/>
                                <BookingChannel Type="7"/>
                            </Source>
                            <Source>
                                <RequestorID ID="' . $this->benutzer_id . '" Type="1" URL="urn:cultuzz:cultswitch:auth:username"/>
                                <BookingChannel Type="7"/>
                            </Source>
                        </POS>  
                        <UniqueID ID="'.$id.'" ID_Context="ContractorID" Type = "1"/>
                    </OTA_ReadRQ>';
        return $xml;
    }

    public function parseContractorsList($xmloutput) {
        if(stripos($xmloutput, "Error")){
            throw new Exception("Error while getting Contractors xml.");
        }
        $contractors = array();
        $contract = array();
        $dom = new DOMDocument();
        $dom->loadXML(trim($xmloutput));
        $profileInfos = $dom->getElementsByTagName("ProfileInfo");
        foreach ($profileInfos as $profileInfo) {
            $latestContracts = array();
            $currentContracts = array();
            if ($profileInfo->nodeType == 1 && $profileInfo->nodeName == 'ProfileInfo' && $profileInfo->hasChildNodes()) {
                $profiles_Unique = $profileInfo->childNodes;
                foreach ($profiles_Unique as $profile) {
                    if ($profile->nodeType == 1 && $profile->nodeName == "Profile" && $profile->hasChildNodes()) {
                        $customers = $profile->childNodes;
                        foreach ($customers as $customer) {
                            if ($customer->nodeType == 1 && $customer->nodeName == "Customer" && $customer->hasChildNodes()) {
                                $addresses = $customer->childNodes;
                                foreach ($addresses as $address) {
                                    if ($address->nodeType == 1 && $address->nodeName == "Address" && $address->hasChildNodes()) {
                                        $childNodes = $address->childNodes;
                                        foreach ($childNodes as $childNode) {
                                            if ($childNode->nodeType == 1) {
                                                switch ($childNode->nodeName) {
                                                    case 'CityName':
                                                        $contractorPlace = $childNode->nodeValue;
                                                        break;
                                                    case 'PostalCode':
                                                        $contractorZipCode = $childNode->nodeValue;
                                                        break;
                                                    case 'County':
                                                        $contractorCountry = $childNode->nodeValue;
                                                        break;
                                                    case 'CompanyName' :
                                                        $contractorDistributor = $childNode->getAttribute("Code");
                                                        $contractorCompanyName = $childNode->getAttribute('CompanyShortName');
                                                }
                                            }
                                        }
                                    } else if ($address->nodeName == "CustLoyalty" && $address->nodeType == 1) {
                                        $contractId = $address->getAttribute("MembershipID");
                                        $contract[$contractId]['ContractID'] = $contractId;
                                        $contract[$contractId]['CustomerType'] = $address->getAttribute("CustomerType");
                                        $contract[$contractId]['EffectiveDate'] = $address->getAttribute("EffectiveDate");
                                        $contract[$contractId]['ExpireDate'] = $address->getAttribute("ExpireDate");
                                        $contract[$contractId]['SignupDate'] = $address->getAttribute("SignupDate");
                                        $contract[$contractId]['RPH'] = $address->getAttribute("RPH");
                                        $contract[$contractId]['LoyalLevel'] = $address->getAttribute("LoyalLevel");
                                        $contract[$contractId]['contractKey'] = $address->getAttribute("VendorCode");
                                        if ($address->getAttribute("EffectiveDate") >= date('Y-m-d')) {
                                            $latestContracts[] = $contractId;
                                            echo "coming" . $contractId;
                                        }
                                        if ($address->getAttribute("EffectiveDate") <= date('Y-m-d') && $address->getAttribute("ExpireDate") > date('Y-m-d')) {
                                            $currentContracts[] = $contractId;
                                        }
                                    }
                                }
                            }
                        }
                    } else if ($profile->nodeType == 1 && $profile->nodeName == "UniqueID") {
                        $contractor_id = $profile->getAttribute("ID");
                    }
                }
                $contractors[$contractor_id]['Place'] = $contractorPlace;
                $contractors[$contractor_id]['ZipCode'] = $contractorZipCode;
                $contractors[$contractor_id]['Country'] = $contractorCountry;
                $contractors[$contractor_id]['Distributor'] = $contractorDistributor;
                $contractors[$contractor_id]['ContractorName'] = $contractorCompanyName;
                $contractors[$contractor_id]['contracts'] = $contract;
                $contractors[$contractor_id]['latestContracts'] = $latestContracts;
                $contractors[$contractor_id]['currentContracts'] = $currentContracts;
            }
        }
//           echo "<pre>";
//           print_r($contractors);
//           echo "</pre>";
        return $contractors;
    }
    public function prepareSetContractorXml() {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
                <OTA_ProfileCreateRQ PrimaryLangID="en" Target="Production" TimeStamp="2001-12-17T09:30:47" Version="3.30" 
                                     xmlns="http://www.opentravel.org/OTA/2003/05">
                    <Profile ProfileType="1">
                        <Accesses>
                            <Access ActionType="Create" ID="new"/>
                        </Accesses>
                        <Customer>
                            <Address>
                                <CityName>' . $this->contractorPlace . '</CityName>
                                <PostalCode>' . $this->contractorZipCode . '</PostalCode>
                                <CountryName Code="' . $this->contractorCountry . '"/>
                                <CompanyName CompanyShortName="' . $this->contractorName . '"
                                             Code="' . $this->contractorChannel . '" CodeContext="Distributor"/>
                            </Address>
                        </Customer>
                        <TPA_Extensions>
                            <POS>
                                <Source AgentDutyCode="' . $this->agentDutyCode . '" AgentSine="' . $this->agentSine . '">
                                    <RequestorID ID="' . $this->object_id . '" ID_Context="CLTZ" Type="10"/>
                                    <BookingChannel Type="7"/>
                                </Source>
                            </POS>
                        </TPA_Extensions>
                    </Profile>
                </OTA_ProfileCreateRQ>';
        return $xml;
    }
    public static  function pareseResponse($xmloutput) {
        if (stripos($xmloutput, "Success")) {
            return true;
        } else {
            $dom = new DOMDocument();
            if ($dom->loadXML($xmloutput)) {
                $errors = $dom->getElementsByTagName("Error");
                foreach ($errors as $error){
                    if($error->nodeType == 1 && $error->nodeName == "Error"){
                        $this->addError("error", $error->nodeValue);
                    }
                }
            } else {
                throw new Exception("Failed to load XML");
            }
        }
    }

    public static function sendXML($xmlRequest) {
        $httpConnector = new HttpConnector();
        $xmlResponse = $httpConnector->sendXMLdata($xmlRequest);
        return $xmlResponse;
    }

}
