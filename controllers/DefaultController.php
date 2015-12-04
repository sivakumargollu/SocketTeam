<?php

$_REQUEST['object_id'] = '2208';
$_REQUEST['benutzer_id'] = 41875;

class DefaultController extends Controller {

    public function actionIndex() {


        $contractorModelObj = new ContractorModel();
        $contractors = $contractorModelObj->getContractors();
        $this->render("index", array("contractors" => $contractors));
    }

    public function actionCreateContractor() {
        try {

            $contractorModelObj = new ContractorModel();
            $extraInfoModel = new ExtraInfoModel();
            $channels = $extraInfoModel->getChannelsList();
            //Yii::app()->cache->set("channels",$channels);
            //$session['channels'] = $channels;
            $countries = $extraInfoModel->getCountriesList();
            //$session['countries'] = $countries;

            $this->render('Contractor', array('contractModel' => $contractorModelObj, 'channels' => $channels, 'countries' => $countries));
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function actionEditContractor() {
        try {
            $contractorModel = new ContractorModel();
            if (isset($_REQUEST['contractorId']) && isset($_REQUEST['action'])) {
                if ($_REQUEST['action'] == 'edit') {
                    $contractDetails = $contractorModel->getContaractorDetails(filter_input(INPUT_GET, "contractorId"));
                    $extraInfoModel = new ExtraInfoModel();
                    $channels = $extraInfoModel->getChannelsList();
                    $countries = $extraInfoModel->getCountriesList();
                    $this->render('Contractor', array('contractModel' => $contractorModel, 'contractDetails' => $contractDetails, 'channels' => $channels, 'countries' => $countries));
                } else if ($_REQUEST['action'] == 'delete') {

                    $contractors = $contractorModelObj->getContractors();
                    $this->render("index", array("contractors" => $contractors));
                } else {
                    throw new Exception("Undefined action specified");
                }
            } else {
                throw new Exception("Contract ID should not be null");
            }
        } catch (Exception $ex) {
            $contractors = $contractorModelObj->getContractors();
            $this->render("index", array("contractors" => $contractors, 'errorMessage' => $ex->getMessage()));
        }
    }

    public function actionSaveContractor() {
        
        if (isset($_POST['ContractorModel'])) {

            $contractorModelObj = new ContractorModel();
            $extraInfoModel = new ExtraInfoModel();
            $contractorModelObj->attributes = filter_input($_POST, "ContractorModel");
            $contractorModelObj->setScenario('Save');
            $contractorModelObj->validate();
            $channels = $extraInfoModel->getChannelsList();
            $countries = $extraInfoModel->getCountriesList();
            $contractorModelObj->setContractorDetails();

            $this->render("CreateContract", array('contractModel' => $contractorModelObj, 'channels' => $channels, 'countries' => $countries));
        } else {
            
        }
    }

    public function actionCreateContract() {
        if(isset($_REQUEST['contractorId'])){
            $contractModelObj = new ContractModel();
            $contractModelObj->contractorId = $_REQUEST['contractorId'];
            $this->render("Contract", array('contractModel' => $contractModelObj));
        }else{
            
        }
           
    }

    public function actionSaveContract() {
        
        if (isset($_POST['ContractModel'])) {
            $contractModelObj = new ContractModel();
            $contractModelObj->setScenario("Save");
            $contractModelObj->attributes = $_POST['ContractModel'];
            $contractModelObj->validate();
            $response = $contractModelObj->setContract();
            
        }
    }

    public function actionEditContract() {
        if(isset($_REQUEST['contractId']) && isset($_REQUEST['details'])){
            $contractModelObj = new ContractModel();
            $contractModelObj->setData(json_decode($_REQUEST['details']));
            $this->render("Contract", array('contractModel' => $contractModelObj,'contractDetails'));
        }else{
            
        }
    }
    public function actionProductMapping() {
        if(isset($_REQUEST['contractorId'])){
            $mappingModel = new MappingModel();
            $products = $mappingModel->getProductsList();
            $contractorModel = new ContractorModel();
            $contracts = $contractorModel->getContaractorDetails($mappingModel->contractorId);
            $mappingModel->setContracts($contracts);
            $this->render("ProductMapping",array('mappingModel'=> $mappingModel));
        }else{
            
        }
        
    }

}
