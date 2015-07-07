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


class Cmantest  extends ActiveRecord{
   public function generate($datalgnd){
//echo $datalgnd;
$result = [];


$firstDimension = explode('|', $datalgnd); // Divide by | symbol
foreach($firstDimension as $temp) {
    // Take each result of division and explode it by , symbol and save to result
    $result[] = explode(',', $temp);
}

print_r($result);

   }

}