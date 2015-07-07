<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

class ambildatatabel extends ActiveRecord {

    public function ambil($namatabel) {

$nama_kolom=array();
$nama_kolom_tampil=array();

$query ="SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS WHERE  TABLE_NAME='$namatabel'";
 $connection=  Yii::$app->db;
        $command=$connection->createCommand($query);
      $dataReader=$command->query();          
    $rows=$dataReader->readAll();
    $semuakolom=array();
   foreach($rows as $myrow) { 

     array_push($semuakolom,$myrow["column_name"]);

    }

$query = "SELECT nama_kolom, nama_kolom_tampil  FROM ddata  where nama_tabel='$namatabel'";
      $connection=  Yii::$app->db;
        $command=$connection->createCommand($query);
      $dataReader=$command->query();          
    $rows=$dataReader->readAll();
        $num_rows1=$dataReader->rowCount;
         $json_provinsi =array("status"=>"OK","jumlah"=>0,"jumlah2"=>0,"results"=>array(),"results2"=>array());

   foreach($rows as $myrow) { 
  array_push($nama_kolom, str_replace(' ', '', $myrow["nama_kolom"]));
  array_push($nama_kolom_tampil, $myrow["nama_kolom_tampil"]);
       $json_provinsi["results"][]= 
    array(
        "nama_kolom" => $myrow['nama_kolom'],
        "nama_kolom_tampil" => $myrow['nama_kolom_tampil']
    ); 

    }
$sortby="";
    for($i=0;$i<sizeof($semuakolom);$i++){
      if($semuakolom[$i]=="id_prov"|$semuakolom[$i]=="id_kab"|$semuakolom[$i]=="id_kec"){

       $temparray=   array(
        "nama_kolom" =>$semuakolom[$i],
        "nama_kolom_tampil" => $semuakolom[$i]

    ); 
       if($semuakolom[$i]=="id_kec")
       $sortby="id_kec";
     else if($semuakolom[$i]=="id_kab"& $sortby!="id_kab")
      $sortby="id_kab";
       else if($semuakolom[$i]=="id_prov"& $sortby!="id_kab"& $sortby!="id_kec")
      $sortby="id_prov";
         array_unshift($nama_kolom,$semuakolom[$i]);
         $num_rows1++;
     array_unshift($json_provinsi["results"], $temparray);
      }
    }

$querykolom="";
for($i=0;$i<$num_rows1;$i++){
  if(($i+1)==$num_rows1)
     $querykolom=$querykolom.$nama_kolom[$i];
  else
  $querykolom=$querykolom.$nama_kolom[$i].",";

}

    $query = "SELECT $querykolom  FROM $namatabel ORDER BY $sortby";
      $connection=  Yii::$app->db;
        $command=$connection->createCommand($query);
      $dataReader=$command->query();          
    $rows=$dataReader->readAll();
       $num_rows2=$dataReader->rowCount;
$x=0;
        $json_provinsi["jumlah"]=$num_rows1;
$json_provinsi["jumlah2"]=$num_rows2;
  foreach($rows as $myrow) { 
    $nilaiperbaris=array();
    for($i=0;$i<$num_rows1;$i++){
$json_provinsi["results2"][$x][$i]=$myrow[$nama_kolom[$i]];}
$x++;
    }

 echo json_encode($json_provinsi, JSON_PRETTY_PRINT);

}

}