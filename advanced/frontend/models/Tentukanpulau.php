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


class Tentukanpulau  extends ActiveRecord{


   public function pulau($namaTabel,$kodewilayahtersedia){

   	$hasil = array();
if($kodewilayahtersedia=="Provinsi")
   	{
$kodewilayahtersedia="id_prov";

    }else if($kodewilayahtersedia=="Kabupaten")
    {
$kodewilayahtersedia="id_kab";

    }else if($kodewilayahtersedia=="Kecamatan")
    {
$kodewilayahtersedia="id_kec";

    }
   $json_provinsi =array("status"=>"OK","jumlah"=>0,"results"=>array());

if($kodewilayahtersedia=="id_prov"|$kodewilayahtersedia=="id_kab"|$kodewilayahtersedia=="id_kec"){
   	$query = "SELECT  $kodewilayahtersedia FROM $namaTabel";
      $connection=  Yii::$app->db;
      $command=$connection->createCommand($query);
      $dataReader=$command->query();          
    $rows=$dataReader->readAll();
        $num_rows=$dataReader->rowCount;

 $i=0;
  foreach($rows as $myrow) { 
   
  $hasil[$i]= $pulauapa=self::_tentukan($myrow[$kodewilayahtersedia]);
       $i++;  
    

}
 $hasil = array_unique($hasil);
//print_r($hasil);
  $i=0;
 foreach ($hasil as $nama ) {
 	# code...
        $json_provinsi["results"][]= 
    array(
        "nama_pulau" => $nama,
      
         
    ); 
     $i++;  
 }
 $json_provinsi["jumlah"]=$i;  
}
  echo json_encode($json_provinsi, JSON_PRETTY_PRINT);

 }
  private static function _tentukan($id_prov)
{
$hasil=substr($id_prov,0,1);
if($hasil==1|$hasil==2){
	$hasil="Sumatera1";
}else if($hasil==3){
	$hasil="Jawa3";
}else if($hasil==6){
	$hasil="Kalimantan6";
}else if($hasil==7){
	$hasil="Sulawesi7";
}else if($hasil==8){
	$hasil="Maluku8";
}else if($hasil==9){
	$hasil="Papua9";
}else if($hasil==5){
	$hasil="Bali dan Nusa Tenggara5";
}
return $hasil;
}

}