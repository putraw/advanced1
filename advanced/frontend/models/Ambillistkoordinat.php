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


class Ambillistkoordinat  extends ActiveRecord{


   public function ditest($test){
   $sql='SELECT * FROM data_makan';
      $connection=  Yii::$app->db;

        $command=$connection->createCommand($sql);
      $dataReader=$command->query();          
    $rows=$dataReader->readAll();
    $namevalue=array();

    $num_rows=$dataReader->rowCount;
    foreach($rows as $max)
    {
    $namevalue = $max['jumlah_miskin'];

   echo $namevalue."<br/>";
    }
echo "<p>".$dataReader->rowCount."</p>";
  
return $namevalue;
}

 public function ambilNamaTabel(){
 // untuk test ouput http://localhost:8081/advanced1/advanced/frontend/web/index.php?r=site/ambilnamatabeldanatribut&ambil=nama_tabel
//$query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES 
//WHERE TABLE_NAME != 'spatial_ref_sys' and TABLE_NAME != 's00prov' and TABLE_NAME != 's00kab' and TABLE_NAME != 'ddata' and TABLE_TYPE ='BASE TABLE' and TABLE_SCHEMA ='public'";
$query = "SELECT nama_tabel, nama_tabel_tampil FROM ddata_koordinat GROUP BY nama_tabel, nama_tabel_tampil";
      $connection=  Yii::$app->db;

       
        $command=$connection->createCommand($query);
      $dataReader=$command->query();          
    $rows=$dataReader->readAll();
        $num_rows=$dataReader->rowCount;
 $json_provinsi =array("status"=>"OK","jumlah"=>$num_rows,"results"=>array());
   foreach($rows as $myrow) { 
  
  
     $json_provinsi["results"][]= 
    array(
        "nama_tabel" => $myrow['nama_tabel'],
        "nama_tabel_tampil" => $myrow['nama_tabel_tampil']
    ); 
} 


 echo json_encode($json_provinsi, JSON_PRETTY_PRINT);
}




 }


?>