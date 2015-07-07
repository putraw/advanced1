<?php


namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;


class Ambilpostgre  extends ActiveRecord{
   public function ambil($latlng,$modetematik,$idp,$nama_kolom,$nama_menurut ,$nama_di,$nama_kabupaten,$tipe_data,$idpulau){
      $connection=  Yii::$app->db;

//header("Location: http://localhost:8081/cgi-bin/mapserv.exe?map=C:/ms4w/apps/latihan/mapsederhana/temp/clone.map?&SERVICE=".$service."&REQUEST=".$request."&VERSION=".$version."&LAYERS=".$layers."$STYLES=".$styles."&FORMAT=".$format."&TRANSPARENT=".$transparent."&HEIGHT=".$height."&WIDTH=".$width."&SRS=".$srs);
//$latlng='109.48974609375 -7.536764322084078';
   //$nama_menurut="Provinsi";
   //$idp ="data_timese_prov";
//$nama_kolom="t2014";
//$nama_menurut="Provinsi";
//$nama_di="Indonesia";
//$modetematik='true';
//$tipe_data="3";
    // klo coba hati2 data kabupaten yang g sesuai g munculS 
//$modetematik='true';
$nama_tabel=  $idp;


$layer='id_prov';
$layerdatabase='s00prov';
$namaprovataukab="nama_prov";
$popupprovataukab="provinsi";
$where="";



if($nama_menurut=="Kabupaten"){
$layer='id_kab';
$layerdatabase='s00kab';
$namaprovataukab="nama_kab";
$popupprovataukab="kabupaten";
$where = "and $nama_di=id_prov" ;
} 
if($nama_menurut=="Kecamatan"){
$layer='id_kec';
$layerdatabase='s33kec';
$namaprovataukab="nama_kec";
$popupprovataukab="kecamatan";
if($nama_kabupaten=="seluruhKabupaten"){
$where="and $nama_di = id_prov";
}
else{
$where = "and $nama_kabupaten=id_kab";
} 
}

$wherepulau='';
if($idpulau!=0){
  $wherepulau="and $idp.$layer::varchar LIKE '".$idpulau."%'";

}

$query = "SELECT * FROM $layerdatabase
where 
ST_Intersects(
 
   'Point($latlng)'::geometry, geom::geometry    
);";




 $command=$connection->createCommand($query);
      $dataReader=$command->query();          
    $rows=$dataReader->readAll();
 $num_rows=$dataReader->rowCount;
 $json_provinsi =array("status"=>"OK","jumlah"=>0,"results"=>array());

if($modetematik=='false'& $num_rows!=0 ){

 foreach($rows as $myrow) { 


    $json_provinsi["results"][]= 
    array(
      "id_prov" => $myrow['id_prov'],
        "nama_provinsi" => $myrow['nama_prov']."",
          "data_jumlah" => " "
    
    );
} 

}
else if ($modetematik=='true'& $num_rows!=0){

  if($tipe_data=='1'){
$id_prov;
$nama_prov;
 foreach($rows as $myrow) { 
    $id_prov    = $myrow[$layer];  
    $nama_prov    = $myrow[$namaprovataukab];
} 


/*
while($myrow = pg_fetch_assoc($result)) { 


    $json_provinsi["results"][]= 
    array(
      "id_prov" => $myrow['id_prov'],
        "nama_provinsi" => $myrow['nama_prov']
    
    );
} 
*/

if($nama_di=='Indonesia'| $idpulau!=0){
$query2 = "SELECT $layer , SUM($nama_kolom) FROM $nama_tabel
where $id_prov=$layer $wherepulau GROUP BY $layer ;";}
else{
$query2 = "SELECT $layer , SUM($nama_kolom) FROM $nama_tabel
where $id_prov=$layer  ".$where." GROUP BY $layer ;";}

 $command=$connection->createCommand($query2);
      $dataReader=$command->query();          
    $rows=$dataReader->readAll();

 foreach($rows as $myrow) { 

    $json_provinsi["results"][]= 
    array(
      "id_prov" => $id_prov,
        "nama_provinsi" =>$popupprovataukab.": ". $nama_prov."<br/>",
        "data_jumlah" => "jumlah: ".$myrow['sum']
    
    );
} 
} if($tipe_data=='2')
{
$id_prov;
$nama_prov;
 foreach($rows as $myrow) { 
    $id_prov    = $myrow[$layer];  
    $nama_prov    = $myrow[$namaprovataukab];
} 





$query2 = "SELECT $layer , $nama_kolom FROM $nama_tabel where $id_prov=$layer  $wherepulau ;";
 $command=$connection->createCommand($query2);
      $dataReader=$command->query();          
    $rows=$dataReader->readAll();

 foreach($rows as $myrow) { 

    $json_provinsi["results"][]= 
    array(
      "id_prov" => $id_prov,
        "nama_provinsi" =>$popupprovataukab.": ". $nama_prov."<br/>",
        "data_jumlah" => "Keterangan: ".$myrow[$nama_kolom]
    
    );
} 

}
if($tipe_data=='3')
{
$id_prov;
$nama_prov;
 foreach($rows as $myrow) { 
    $id_prov    = $myrow[$layer];  
    $nama_prov    = $myrow[$namaprovataukab];
} 
$nama_kolom=array();
$query2 = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE column_name!='id_prov'and column_name!='id_kab' and column_name!='id_kec' and TABLE_NAME='".$nama_tabel."'";
 $command=$connection->createCommand($query2);
      $dataReader=$command->query();          
    $rows=$dataReader->readAll();
    $i=0;
    $query2 ="";
foreach($rows as $myrow) { 
$nama_kolom[$i]=$myrow['column_name'];
$query2=$query2.",SUM(".$nama_kolom[$i].") as sum_".$nama_kolom[$i];
$i++;
}
$num_rows=$dataReader->rowCount;
if($nama_di=='Indonesia'| $idpulau!=0){
$query2 = "SELECT $layer ".$query2." FROM $nama_tabel
where $id_prov=$layer  $wherepulau GROUP BY $layer ;";}
else{
$query2 = "SELECT $layer  ".$query2." FROM $nama_tabel
where $id_prov=$layer  ".$where." GROUP BY $layer ;";}


 $command=$connection->createCommand($query2);
      $dataReader=$command->query();          
    $rows=$dataReader->readAll();

 $json_provinsi["jumlah"]=$i;
 foreach($rows as $myrow) { 
for($x=0;$x<$i;$x++){
    $json_provinsi["results"][]= 
    array(
      "id_prov" => $id_prov,
        "nama_provinsi" =>$popupprovataukab.": ". $nama_prov."<br/>",
        "tahun" => $nama_kolom[$x],
        "data_jumlah" => $myrow['sum_'.$nama_kolom[$x]]
    
    );
  }
} 

}

}
 echo json_encode($json_provinsi, JSON_PRETTY_PRINT);
 
}
}
?>
