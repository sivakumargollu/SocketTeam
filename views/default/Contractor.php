<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/css/ObjectOfRights.css');


$form = $this->beginWidget('CActiveForm',array(
                                    'id'=>'form',
                                    'enableAjaxValidation'=> true,
                                    'enableClientValidation'=>true,
                                    'focus'=>array($contractModel,'contractorChannel'),
                                    'action' => 'saveContractor'
                                    ))
        
?>

<table>
    <tr>
        <td>
            
        </td>
    </tr>
    <tr>
        <td>
            <fieldset><legend>
                <?php if($contractModel->contractorName != NULL){
                    echo "Edit Contractor Data";
                }else{
                    echo "New Contractor";
                }
                ?>
               </legend>
                
                <span style="color: red ">
                    <?php 
                    if($contractModel->hasErrors(null)){
                        foreach ($contractModel->getErrors() as $key => $value){
                            echo $value[0]."<br>";
                        }
                    }
                    ?>
                </span>
            <table class="text" cellpadding="5px">
                
                <tr>
                    <td>
                        Channel :
                    </td>
                    <td>
                        
                        <?php  echo $form->dropDownList($contractModel ,'contractorChannel',$channels)?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Name :
                    </td>
                    <td>
                        
                        <?php echo $form->textField($contractModel , 'contractorName' , "")?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Zip Code :
                    </td>
                    <td>
                        
                        <?php echo $form->textField($contractModel , 'contractorZipCode' ,"")?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Place :
                    </td>
                    <td>
                        
                        <?php echo $form->textField($contractModel , 'contractorPlace' , "")?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Country :
                    </td>
                    <td>
                        <?php echo  $form->dropDownList($contractModel ,'contractorCountry',$countries)?>
                    </td>
                </tr>
                <tr>
                    <td/>
                    <td >
                        <input type="submit" name="save" value="Save" class="mybt_m">
                    </td>
                    
                </tr>
                
            </table>
            </fieldset>
        </td>
    </tr>
    <?php if(!empty($contractDetails) && $contractDetails != null){ ?>
    <tr>
        <td class="text">
            <strong>Contracts of Contractor :</strong><br/>   
            <table cellpadding="5px" class="text" align="left">
                <tr>
                    <th >ID</th>
                    <th >Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>PDF</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                <tr>
                    <td colspan="7"><hr color="#003366" size="2"></td>
                </tr>
                <?php foreach ($contractDetails as $contractID => $details){
                    echo '<tr>';
                    echo '<td>'.$details['ContractID'].'</td>';
                    echo '<td>'.$details['CustomerType'].'</td>';
                    echo '<td>'.$details['EffectiveDate'].'</td>';
                    echo '<td>'.$details['ExpireDate'].'</td>';
                    echo '<td><img border="0" src="https://albatros.cultuzz.de/service/_img/pdf.gif" onclick=download('.$details['RPH'].')</td>';
                    echo '<td><img border="0" src="https://albatros.cultuzz.de/service/_img/edit.gif" onclick= window.open("editContract?contractId='.$details['ContractID'].'&details='. urlencode(json_encode($details)).'","","width=500,height=500")></td>';
                    echo '<td><img border="0" src="https://albatros.cultuzz.de/service/_img/delete_blau.gif" onclick=deleteContract('.$details['ContractID'].')></td>';
                    echo '</tr>';
                    
                }
                echo '<input type="hidden" name="contractData" value="'.json_encode($contractDetails).'">';
?>
            </table>
        </td>
    </tr>
    <?php }?>
</table>
<?php
$this->endWidget();
?>