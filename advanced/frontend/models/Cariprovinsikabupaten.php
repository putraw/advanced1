<?php

//header("Location: http://localhost:8081/cgi-bin/mapserv.exe?map=C:/ms4w/apps/latihan/mapsederhana/temp/clone.map?&SERVICE=".$service."&REQUEST=".$request."&VERSION=".$version."&LAYERS=".$layers."$STYLES=".$styles."&FORMAT=".$format."&TRANSPARENT=".$transparent."&HEIGHT=".$height."&WIDTH=".$width."&SRS=".$srs);
//$latlng='101.38 -0.26';
   
//$ambil = 'nama_kolom';
//$ambil = 'nama_di';
//$namaMenurut='Kabupaten';
//$namaTabel = 'jumlah_rumah_tangga_dengan_kepala_rumah_tangga_perempuan_per_ka';
namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;


class Cariprovinsikabupaten  extends ActiveRecord{
function cari($yangDiketik,$yangDicari){


if($yangDicari=="Provinsi"){
$query="SELECT nama_prov FROM s00prov WHERE UPPER( nama_prov) LIKE UPPER( '%".$yangDiketik."%');";
$connection=  Yii::$app->db;

       
        $command=$connection->createCommand($query);
      $dataReader=$command->query();          
    $rows=$dataReader->readAll();
        $num_rows=$dataReader->rowCount;
 $json_provinsi =array("status"=>"OK","jumlah"=>$num_rows,"results"=>array());
   foreach($rows as $myrow) { 
  
  
     $json_provinsi["results"][]= 
    array(
        "nama_prov" => $myrow['nama_prov'],
    ); 
} 


}
else if($yangDicari=="Kabupaten"){
  
$query="SELECT nama_kab, nama_prov FROM s00kab WHERE UPPER( nama_kab) LIKE UPPER( '".$yangDiketik."%') GROUP BY nama_prov, nama_kab ;";

$connection=  Yii::$app->db;

       
        $command=$connection->createCommand($query);
      $dataReader=$command->query();          
    $rows=$dataReader->readAll();
        $num_rows=$dataReader->rowCount;
 $json_provinsi =array("status"=>"OK","jumlah"=>$num_rows,"results"=>array());
   foreach($rows as $myrow) { 
  
  
     $json_provinsi["results"][]= 
    array(
        "nama_kab" => $myrow['nama_kab'],
        "nama_prov" => $myrow['nama_prov'],
    ); 
} 

}
 echo json_encode($json_provinsi, JSON_PRETTY_PRINT);

}
function cariTitikTengah($yangDiketik,$yangDicari){
$nama_tabel="";
$nama_kolom="";
if ($yangDicari=="Provinsi"){
  $nama_tabel="s00prov";
  $nama_kolom="nama_prov";
}else if ($yangDicari=="Kabupaten"){
  $nama_tabel="s00kab";
  $nama_kolom="nama_kab";
}



$query="SELECT  $nama_kolom,ST_Y(st_centroid(st_union(geom))) as lat,ST_X(st_centroid(st_union(geom))) as lng FROM $nama_tabel  WHERE  $nama_kolom='$yangDiketik' GROUP BY $nama_kolom;";

$connection=  Yii::$app->db;
      
        $command=$connection->createCommand($query);
      $dataReader=$command->query();          
    $rows=$dataReader->readAll();
        $num_rows=$dataReader->rowCount;
 $json_provinsi =array("status"=>"OK","jumlah"=>$num_rows,"results"=>array(),"results2"=>array());
   foreach($rows as $myrow) { 
     $json_provinsi["results"][]= 
    array(
        "latitude" => $myrow['lat'],
        "longitude" => $myrow['lng'],
        "yangDiketik" => $myrow[$nama_kolom],
    ); 
}
$query="SELECT  $nama_kolom, st_asgeojson(st_envelope(geom)) AS envelope FROM $nama_tabel  WHERE  $nama_kolom='$yangDiketik'";

$connection=  Yii::$app->db;
      
        $command=$connection->createCommand($query);
      $dataReader=$command->query();          
 $rows=$dataReader->readAll();

   foreach($rows as $myrow) { 

    $envelope=str_replace("\\","",$myrow['envelope']);
    //echo "string".$envelope;
         $data = json_decode($myrow['envelope'], true);

     $json_provinsi["results2"][]= 
    array(
        
          "envelope" => $data,
    ); 

} 


 echo json_encode($json_provinsi, JSON_PRETTY_PRINT);

}
}
?>