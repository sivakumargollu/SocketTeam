<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ContractModel
 *
 * @author nagulmeera
 */
class ContractModel extends CFormModel{
    //put your code here
    private $agentSine = 74;
    private $agentDutyCode = '18941305ba1e3572';
    public $contractID =null;
    public $contractName = null;
    public $contractSigned = null;
    public $contractFrom = null;
    public $contractTo = null;
    public $contractPdfPath = null;
    public $contractKey = null;
    public $pdfFile = null;
    public $objectId = null;
    public $benutzer_id = null;
    public $contractorId = null;
    

    public function rules() {
        return array(
            array('contractID','required', 'on'=> 'Edit'),
            array('contractID','numerical','integerOnly'=>true),
            array('contractName','required',),
            array('contractName','length','min'=>3,'max'=>50,),
            array('contractSigned','required', 'on'=> 'Save'),
            array('contractSigned','type', 'dateFormat' => 'd M yy','on'=> 'Save'),
            array('contractFrom','required',),
            array('contractFrom','type', 'dateFormat' => 'd M yy',),
            array('contractTo','required', ),
            array('contractTo','type','dateFormat' => 'd M yy', ),
            array('contractKey','required', ),
            array('contractKey','length','min'=>2,'max'=>50,),
            array('pdfFile','file','types'=>'pdf','mimeTypes'=>'application/pdf','maxSize'=>1024*1024*5,'message'=>'Please upload PDF file only','on' => 'Save'),
            array('pdfFile',"uploadToCMS",'on'=>'Save'),
            array('contractorId','required'),
            array('contractorId','numerical','integerOnly'=>true),
            
        );
    }
    public function __construct($scenario = '') {
        parent::__construct($scenario);
        $this->objectId = isset($_REQUEST['object_id']) ? $_REQUEST['object_id'] : $this->addError("Object ID", "Object ID is missing in model");
        $this->benutzer_id = isset($_REQUEST['benutzer_id']) ? $_REQUEST['benutzer_id'] : $this->addError("User ID", "User ID is missing in model");
    }
    public function setData($data) {
        $this->contractID = $data->ContractID;
        $this->contractName = $data->CustomerType;
        $this->contractFrom = date('d M Y',  strtotime($data->EffectiveDate));
        $this->contractTo = date('d M Y',  strtotime($data->ExpireDate));
        $this->contractSigned = date('d M Y',  strtotime($data->SignupDate));
        $this->contractPdfPath = $data->RPH;
        $this->contractKey = $data->contractKey;
        
    }
    public function uploadToCMS($attribute,$params) {
        $file = CUploadedFile::getInstance($this, "pdfFile");
        
//        $CMSUpload = new CMSUpload();
//        $CMSUpload->uploadFileToCMS($file, $this->objectId."_".$this->benutzer_id."_");
        $fileContent = file_get_contents($file->tempName);
        //sfile_put_contents("/home/nagulmeera/corporateRates/".$file->name."-".date('Y-m-d ').".pdf", $fileContent);
        $this->contractPdfPath = "/home/nagulmeera/corporateRates/".$file->name."-".date('Y-m-d').".pdf";
        
    }
    public function  setContract(){
        $xmlinput = $this->prepareContractXml();
        $xmloutput= ContractorModel::sendXML($xmlinput);
        echo "XMLLLLLLLL".$xmlinput."\n".$xmloutput;
        $this->pareseResponse($xmloutput);
        print_r($this->getErrors());
        
    }
    public function getContract($contractId) {
        
    }
    public function prepareContractXml() {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
                <OTA_ProfileCreateRQ PrimaryLangID="en" Target="Production" Version="3.30"
                                     TimeStamp="'.date('Y-m-d\TH:i:s').'" xmlns="http://www.opentravel.org/OTA/2003/05">
                    <Profile ProfileType="1">
                        <Accesses>
                            <Access ID="'.$this->contractorId.'"/>
                        </Accesses>
                        <Customer>
                            <CustLoyalty EffectiveDate="'.date('Y-m-d H:i:s', strtotime($this->contractFrom)).'" ExpireDate="'.date('Y-m-d H:i;s',  strtotime($this->contractTo)).'"
                             SignupDate="'.date('Y-m-d H:i:s',  strtotime($this->contractSigned)).'"  
                             RPH="'.$this->contractPdfPath.'"
                             VendorCode="'.$this->contractKey.'" LoyalLevel="Active" CustomerType="'.$this->contractName.'"/>
                        </Customer>
                        <TPA_Extensions>
                            <POS>
                                <Source AgentDutyCode ="'.$this->agentDutyCode.'" AgentSine ="'.$this->agentSine.'">
                                    <RequestorID ID ="'.$this->objectId.'" ID_Context ="CLTZ" Type="10"/>
                                    <BookingChannel Type ="7"/>
                                </Source>
                            </POS>
                        </TPA_Extensions>
                    </Profile>
                </OTA_ProfileCreateRQ>';
        return $xml;
    }
    
    public function pareseResponse($xmloutput) {
        if (stripos($xmloutput, "Success")) {
            return true;
        } else {
            $dom = new DOMDocument();
            if ($dom->loadXML(trim($xmloutput))) {
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
}
