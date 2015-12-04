<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/css/ObjectOfRights.css');
$form = $this->beginWidget('CActiveForm',array(
                                    'id'=>'form',
                                    'enableAjaxValidation'=> true,
                                    'enableClientValidation'=>true,
                                    'focus'=>array($mappingModel,'contractId'),
                                    'action' => 'saveContract',
                                    'htmlOptions' => array('enctype'=>"multipart/form-data") ,
                                    ))

?>
<fieldset>
    <legend>
        Setup Connection
    </legend>
    <table>
        <tr>
            <td>
                Channel
            </td>
            <td>
                <?php echo $mappingModel->distributorId;?>
                <?php echo $form->hiddenField($mappingModel , "distributorId","")?>
            </td>
        </tr>
        <tr>
            <td>
                Contract
            </td>
            <td>
               
                <?php $data = CHtml::listData($mappingModel->contracts,"name","value","");
 print_r($data);
                echo $form->listBox($mappingModel , "contracts" ,$data ,array('multiple'=>false))?>
            </td>
        </tr>
        <tr>
            <td>
                Product
            </td>
            <td>
                <?php //echo $form->listBox($mappingModel,"products","",array('multiple'=>true))?>
            </td>
        </tr>
        <tr>
            <td>
                <input type="submit" name="save" value="Save">
            </td>
            <td>
                
            </td>
        </tr>
        
    </table>
</fieldset>
<?php $this->endWidget();?>