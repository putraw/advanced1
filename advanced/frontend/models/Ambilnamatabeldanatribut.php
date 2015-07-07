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


class Ambilnamatabeldanatribut  extends ActiveRecord{


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
 public function ambilNamaKolom($namaTabel){
//http://localhost:8081/advanced1/advanced/frontend/web/index.php?r=site/ambilnamatabeldanatribut&ambil=nama_kolom&namaTabel=hemat_energi

//$query = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE column_name!='id_prov'and column_name!='id_kab' and TABLE_NAME='".$namaTabel."'";
  $namaTabel2=$namaTabel;
  $query = "SELECT nama_kolom,nama_kolom_tampil,tipe_data FROM ddata WHERE '$namaTabel'=nama_tabel ";
      $connection=  Yii::$app->db;

    $command=$connection->createCommand($query);
      $dataReader=$command->query();          
    $rows=$dataReader->readAll();
        $num_rows=$dataReader->rowCount;

 $json_provinsi =array("status"=>"OK","jumlah"=>$num_rows,"jumlah2"=>0,"results"=>array(),"results2"=>array());
  foreach($rows as $myrow) { 
  
  
       $json_provinsi["results"][]= 
    array(
        "nama_kolom" => $myrow['nama_kolom'].$myrow['tipe_data'],
         "nama_kolom_tampil" => $myrow['nama_kolom_tampil'],
         
    ); 
    
} 
$namaTabel= trim($namaTabel,' ');
$query2 = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE  '$namaTabel'=TABLE_NAME";
 $command=$connection->createCommand($query2);
      $dataReader=$command->query();          
    $rows=$dataReader->readAll();



 $jumlahmenurut=0;
  foreach($rows as $myrow) { { 
  
  if($myrow['column_name']=="id_prov"){
       $json_provinsi["results2"][]= 
    array(
        "menurut" => Provinsi 
    ); 
     $jumlahmenurut++;
    }

      if($myrow['column_name']=="id_kab"){
       $json_provinsi["results2"][]= 
    array(
        "menurut" => Kabupaten 
    ); 
     $jumlahmenurut++;
    }
    if($myrow['column_name']=="id_kec"){
       $json_provinsi["results2"][]= 
    array(
        "menurut" => Kecamatan 
    ); 
     $jumlahmenurut++;
    }
}
 $json_provinsi["jumlah2"]=$jumlahmenurut;  


}
 echo json_encode($json_provinsi, JSON_PRETTY_PRINT);

 }
 public function ambilNamaTabel(){
 // untuk test ouput http://localhost:8081/advanced1/advanced/frontend/web/index.php?r=site/ambilnamatabeldanatribut&ambil=nama_tabel
//$query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES 
//WHERE TABLE_NAME != 'spatial_ref_sys' and TABLE_NAME != 's00prov' and TABLE_NAME != 's00kab' and TABLE_NAME != 'ddata' and TABLE_TYPE ='BASE TABLE' and TABLE_SCHEMA ='public'";
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
        "nama_tabel" => $myrow['nama_tabel'],
        "nama_tabel_tampil" => $myrow['nama_tabel_tampil']
    ); 
} 


 echo json_encode($json_provinsi, JSON_PRETTY_PRINT);
}



 public function ambilNamaDi($namaTabel,$namaMenurut){
  // untuk test ouput http://localhost:8081/advanced1/advanced/frontend/web/index.php?r=site/ambiltest&ambil=nama_di&namaTabel=jumlah_rumah_tangga_dengan_kepala_rumah_tangga_perempuan_per_ka&namaMenurut=Kabupaten
   $json_provinsi =array("status"=>"OK","jumlah"=>$num_rows,"results"=>array());

if($namaMenurut=="Provinsi"|$namaMenurut=="Kabupaten"|$namaMenurut=="Kecamatan"){
$json_provinsi["results"][]= 
    array(
        "di" => Indonesia ,
        "id_prov" => Indonesia
    ); 
$json_provinsi["jumlah"] =$json_provinsi["jumlah"] +1;
}


if( $namaMenurut=="Kabupaten"|$namaMenurut=="Kecamatan"){
$query = "SELECT s00kab.id_prov, s00kab.nama_prov FROM s00kab INNER JOIN   $namaTabel
ON $namaTabel.id_kab::varchar=s00kab.id_kab::varchar GROUP BY s00kab.id_prov,s00kab.nama_prov ORDER BY s00kab.nama_prov ASC;";
 $connection=  Yii::$app->db;

       
        $command=$connection->createCommand($query);
      $dataReader=$command->query();          
    $rows=$dataReader->readAll();
      $json_provinsi["jumlah"] +=$dataReader->rowCount;

  
  foreach($rows as $myrow) { 

  $json_provinsi["results"][]= 
    array(
        "di" => $myrow['nama_prov'],
        "id_prov" => $myrow['id_prov']
    ); 
}


}


 echo json_encode($json_provinsi, JSON_PRETTY_PRINT);

 }

 public function ambilNamaKab($namaTabel,$namaMenurut,$namaProvinsi){

  // untuk test ouput http://localhost:8081/advanced1/advanced/frontend/web/index.php?r=site/ambiltest&ambil=nama_di&namaTabel=jumlah_rumah_tangga_dengan_kepala_rumah_tangga_perempuan_per_ka&namaMenurut=Kabupaten
   $json_provinsi =array("status"=>"OK","jumlah"=>$num_rows,"results"=>array());

if( $namaMenurut=="Kecamatan"& $namaProvinsi!="Indonesia"){
$query = "SELECT s33kec.id_kab, s33kec.nama_kab FROM s33kec INNER JOIN   $namaTabel
ON $namaTabel.id_kec::varchar=s33kec.id_kec::varchar WHERE $namaProvinsi::varchar=s33kec.id_prov::varchar  GROUP BY s33kec.id_kab,s33kec.nama_kab ORDER BY s33kec.nama_kab DESC;";
 $connection=  Yii::$app->db;

       
        $command=$connection->createCommand($query);
      $dataReader=$command->query();          
    $rows=$dataReader->readAll();
      $json_provinsi["jumlah"] =$dataReader->rowCount;

  
  foreach($rows as $myrow) { 

  $json_provinsi["results"][]= 
    array(
        "kabupaten" => $myrow['nama_kab'],
        "id_kab" => $myrow['id_kab']
    ); 
}
  $json_provinsi["results"][]= 
    array(
        "kabupaten" =>'Seluruh Kabupaten',
        "id_kab" => 'seluruhKabupaten'
    );
$json_provinsi["jumlah"] =$json_provinsi["jumlah"] +1;

}

 echo json_encode($json_provinsi, JSON_PRETTY_PRINT);

 }
 }
?>