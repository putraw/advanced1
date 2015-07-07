<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

class ambilnamatabel extends ActiveRecord {

    public function ambil() {
$query = "SELECT nama_tabel, nama_tabel_tampil FROM ddata GROUP BY nama_tabel, nama_tabel_tampil";
      $connection=  Yii::$app->db;

       
        $command=$connection->createCommand($query);
      $dataReader=$command->query();          
    $rows=$dataReader->readAll();
        $num_rows=$dataReader->rowCount;
 $json_provinsi =array("status"=>"OK","jumlah"=>$num_rows,"results"=>array());
   foreach($rows as $myrow) { 
  
  
     $json_provinsi["results"][]= 
    array(
        "nama_tabel" => preg_replace('/\s+/', '', $myrow['nama_tabel']),
        "nama_tabel_tampil" => $myrow['nama_tabel_tampil']
    ); 


    }
 echo json_encode($json_provinsi, JSON_PRETTY_PRINT);

}

}