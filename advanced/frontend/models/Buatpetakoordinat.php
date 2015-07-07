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


class Buatpetakoordinat  extends ActiveRecord{


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

 public function ambilKoordinat($nama_tabel){
 // untuk test ouput http://localhost:8081/advanced1/advanced/frontend/web/index.php?r=site/ambilnamatabeldanatribut&ambil=nama_tabel
//$query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES 
//WHERE TABLE_NAME != 'spatial_ref_sys' and TABLE_NAME != 's00prov' and TABLE_NAME != 's00kab' and TABLE_NAME != 'ddata' and TABLE_TYPE ='BASE TABLE' and TABLE_SCHEMA ='public'";

$nama_tabel_baru=array();
$jumlah_tabel_checked=0;
for( $i=0;$i<strlen($nama_tabel);$i++){

$cetak=substr($nama_tabel,$i, strpos($nama_tabel, ";", $i)-$i );
array_push($nama_tabel_baru,$cetak);
$jumlah_tabel_checked++;
$i=strpos($nama_tabel, ";", $i);

}
 $json_provinsi =array("status"=>"OK","jumlah_tabel"=>count($nama_tabel_baru),"jumlah_per_tabel"=>array(),"results"=>array());

for($i=0;$i<$jumlah_tabel_checked;$i++){

$query = "SELECT koordinat, keterangan, ddata_koordinat.icon_marker FROM ".$nama_tabel_baru[$i]." INNER JOIN ddata_koordinat
ON ddata_koordinat.nama_tabel= '".$nama_tabel_baru[$i]."'";
     $connection=  Yii::$app->db;

       
       $command=$connection->createCommand($query);
      $dataReader=$command->query();          
    $rows=$dataReader->readAll();
       $num_rows=$dataReader->rowCount;
 $json_provinsi["jumlah_per_tabel"][$i]= $num_rows;

   foreach($rows as $myrow) { 
  
  
    $json_provinsi["results"][]= 
    array(
      "nama_tabel" => $nama_tabel_baru[$i],
        "icon_marker" => $myrow['icon_marker'],
        "koordinat" => $myrow['koordinat'],
       "keterangan" => $myrow['keterangan']
   ); 
   
} 
}

 echo json_encode($json_provinsi, JSON_PRETTY_PRINT);
}

public function pisahnamatabel($nama_tabel_koordinat){



}

 }


?>