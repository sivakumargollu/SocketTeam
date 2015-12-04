<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/css/ObjectOfRights.css');
$form = $this->beginWidget('CActiveForm',array(
                                    'id'=>'form',
                                    'enableAjaxValidation'=> true,
                                    'enableClientValidation'=>true,
                                    'focus'=>array($contractModel,'contractName'),
                                    'action' => 'saveContract',
                                    'htmlOptions' => array('enctype'=>"multipart/form-data") ,
                                    ))

?>
<?php echo $form->hiddenField($contractModel , 'contractorId',"")?>
<fieldset>
    <legend>Add New Contract</legend>
    <table class="text" cellpadding="5px">
    <tr>
        <td>
           Contract Name  
        </td>
        <td>
            <?php echo $form->textField($contractModel , 'contractName',"")?>
        </td>
    </tr>
    <tr>
        <td>
            Contract Signed
        </td>
        <td>
            <?php 
                     $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                        'model' => $contractModel,
                                        'id' => 'contractSigned',
                                        'attribute' => 'contractSigned',
                                        'options' => array(
                                            'showButtonPanel' => true,
                                            'dateFormat' => 'd M yy',
                                            'minDate' => 1,
                                            'maxDate' => "",
                                        ),
                                        'htmlOptions' => array(
                                            'style' => '',
                                            'size' => '10px',
                                            'readonly' => true,
                                            'class' => 'textbox',
                                            'disabled' => "",
                                        ),
                                    ));
                    
                    ?>
            <img src="https://albatros.cultuzz.de/service/_img/calendar.gif" alt="cal" onlcick="showCalender(contractSigned)">
        </td>
    </tr>
    
    <tr>
        <td>
            Contract Validity
        </td>
        <td>
            <table class="text">
                <tr>
                    <td>
                        From
                    </td>
                    <td>
                                    <?php 
                                 $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                                    'model' => $contractModel,
                                                    'attribute' => 'contractFrom',
                                                    'id'=> 'contractFrom',
                                                    'options' => array(
                                                        'showButtonPanel' => true,
                                                        'dateFormat' => 'd M yy',
                                                        'minDate' => 1,
                                                        'maxDate' => "",
                                                    ),
                                                    'htmlOptions' => array(
                                                        'style' => '',
                                                        'size' => '10px',
                                                        'readonly' => true,
                                                        'class' => 'textbox',
                                                        'disabled' => "",
                                                    ),
                                                ));
                                ?>
                               <img src="https://albatros.cultuzz.de/service/_img/calendar.gif" alt="cal" onlcick="showCalender(contractFrom)">
                    </td>
                </tr>
                <tr>
                    <td>
                        To
                    </td>
                    <td>
                                    <?php 
                                 $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                                    'model' => $contractModel,
                                                    'attribute' => 'contractTo',
                                                    'id'=> 'contractTo',
                                                    'options' => array(
                                                        'showButtonPanel' => true,
                                                        'dateFormat' => 'd M yy',
                                                        'minDate' => 1,
                                                        'maxDate' => "",
                                                    ),
                                                    'htmlOptions' => array(
                                                        'style' => '',
                                                        'size' => '10px',
                                                        'readonly' => true,
                                                        'class' => 'textbox',
                                                        'disabled' => "",
                                                    ),
                                                ));
                                ?>
                               <img src="https://albatros.cultuzz.de/service/_img/calendar.gif" alt="cal" onlcick="showCalender(contractTo)">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            Key 
        </td>
        <td>
            <?php echo $form->textField($contractModel , 'contractKey');?>
        </td>
    </tr>
    <tr>
        <td>
            Contract file
        </td>
        <td>
               <?php echo $form->fileField($contractModel,'pdfFile',array('accept'=>'application/pdf'))?>
        </td>
    </tr>
    <tr>
        <td>
            
        </td>
        <td>
            <input type="submit" name="save" value="Save">
        </td>
    </tr>
</table>
</fieldset>
<?php $this->endWidget()?>