<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

class editdeletebaris extends ActiveRecord {

    public function edit($namatabel,$datanamakolom,$datapembaharuan,$kolomunik,$nilaikolomunik) {
    	$datanamakolomarr=explode(",;,", $datanamakolom);
    	 $datapembaharuanarr=explode(",;,", $datapembaharuan);
    	 $nilai=array();

    	 for($i=0;$i<sizeof($datapembaharuanarr);$i++){
    	   	$nilai[trim(preg_replace('/\s\s+/', ' ', $datanamakolomarr[$i] ))] =$datapembaharuanarr[$i];
    	   			
    	   	
    	 }
echo $namatabel;
echo $kolomunik."='".$nilaikolomunik."'";
print_r($nilai);
      $connection=  Yii::$app->db;
      $connection	->createCommand()
		->update($namatabel, 	$nilai,  $kolomunik."='".$nilaikolomunik."'")
			->execute();
	
    }

  public function deletebaris($namatabel,$kolomunik,$nilaikolomunik) {
          $connection=  Yii::$app->db;

$connection ->createCommand()
            ->delete($namatabel, $kolomunik."='".$nilaikolomunik."'")
            ->execute();
    
    }
}