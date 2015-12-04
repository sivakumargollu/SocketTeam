<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExtraInfoModel
 *
 * @author nagulmeera
 */
class ExtraInfoModel {
    //put your code here
    private $csiDB = null;
    
    public function __construct() {
        $this->csiDB = Yii::app()->menudb;
    }
    public function getChannelsList() {
        $query = '  SELECT  cc.distributor_id , cc.channel_name
                    FROM    csi.channel_x_contracts cc';
        $channels = array();
        try{
        $command = $this->csiDB->createCommand($query);
        $result = $command->queryAll();
       
        foreach ($result as $key => $value) {
            $channels[$value['distributor_id']] = $value['channel_name'];
        }
        }  catch (Exception $e){
            throw new Exception("Failed to get Channels");
        }
        return $channels;
    }
    public function getCountriesList(){
        $query = 'SELECT  distinct(laender.id),text.text  FROM cusebeda.laender, cumulida.finder, cumulida.text 
                        WHERE   laender.eu=1 
                        AND             laender.finder_id = finder.id
                        AND             finder.text_id = text.id
                        AND             text.cusebeda_sprache_id= 2
                        ORDER BY text.text';
        $result =array();
        try{
        //$command = $this->csiDB->createCommand($query);
        //$result = $command->queryAll();
            $result = array(2=>"Austria",12=>"Belgium",32=>"Bulgaria ", 34=>"Cyprus ",10=>"Czech Republic", 13=>"Denmark",62=>"Estonia",16=>"Finnland", 5=>"France ", 1=>"Germany", 255=>"Germany",36=>"Greece ",18=>"Hungary",29=>"Ireland", 3=>"Italy",248=>"Jersey Islands",71=>"Latvia ",40=>"Lithuania ", 24=>"Luxembourg ", 61=>"Malta  ", 9=>"Netherlands ", 11=>"Poland ",46=>"Portugal ", 55=>"Romania",48=>"Slovakia ", 41=>"Slovenia ", 4=>"Spain  ",15=>"Sweden ",14=>"United Kingdom ");
            
        }  catch (Exception $e){
            throw new Exception("Failed to get contries List");
        }
        return $result;
    }
}
