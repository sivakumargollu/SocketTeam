<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/css/ObjectOfRights.css');
$this->beginWidget('CActiveForm',array(
                                    'id'=>'form',
                                    'enableAjaxValidation'=> true,
                                    'enableClientValidation'=>true,
                                    ))
?>

<table>
    <tr>
        <td>
            Overview &nbsp;|&nbsp;<a href="default/createContractor" style="text-decoration: none">New Contractor</a>
        </td>
    </tr>
    <tr>
        <td>
            <table cellpadding="3px">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Zip Code</th>
                    <th>Place</th>
                    <th>Country</th>
                    <th>Current</th>
                    <th>Latest</th>
                    <th><img border="0" src="https://albatros.cultuzz.de/service/_img/plus.jpg"></th>
                    <th><img border="0" src="https://albatros.cultuzz.de/service/_img/edit.gif"></th>
                    <th><img border="0" src="https://albatros.cultuzz.de/service/_img/delete_blau.gif"></th>
                </tr>
                <tr>
                    <td colspan="10">
                        <hr color="#003366" size="2">
                    </td>
                </tr>
                <?php 
                    foreach ($contractors as $contractorID => $details){
                        echo "<tr>";
                        echo '  <td>'.$contractorID.'</td>
                                <td>'.$details['ContractorName'].'</td>
                                <td>'.$details['ZipCode'].'</td>
                                <td>'.$details['Place'].'</td>
                                <td>'.$details['Country'].'</td> 
                                <td>';
                        if(count($details['currentContracts'])>1){
                            echo '<img title="Download Current Contract" border="0" src="https://albatros.cultuzz.de/service/_img/pdfs_two.gif">';
                        }else if(count($details['currentContracts']) == 1){
                            echo '<img title="Download Current Contract" border="0" src="https://albatros.cultuzz.de/service/_img/pdf.gif">';
                        }

                        echo '  </td>
                                <td>';
                        if(count($details['latestContracts'])>1){
                            echo '<img title="Download Current Contract" border="0" src="https://albatros.cultuzz.de/service/_img/pdfs_two.gif">';
                        }else if(count($details['latestContracts']) == 1 ){
                            echo '<img title="Download Current Contract" border="0" src="https://albatros.cultuzz.de/service/_img/pdf.gif">';
                        }
                        echo '</td>';
                        echo '<td><a><img border="0" src="https://albatros.cultuzz.de/service/_img/plus.jpg" onclick="window.open(\'CorporateRates/default/createContract?contractorId='.$contractorID.'\',\'\',\'width=500,height=500\')"></a></td>
                                <td><a><img border="0" src="https://albatros.cultuzz.de/service/_img/edit.gif" onclick="window.open(\'CorporateRates/default/editContractor?contractorId='.$contractorID.'&action=edit\',\'_top\')"></a></td>
                                <td><a><img border="0" src="https://albatros.cultuzz.de/service/_img/delete_blau.gif" onclick="window.open(\'CorporateRates/default/editContractor?contractorId='.$contractorID.'&action=delete\',\'_top\')"></a></td>

                                ';
                    }
                ?>
            </table>
        </td>
    </tr>
</table>
<?php
$this->endWidget();
?>