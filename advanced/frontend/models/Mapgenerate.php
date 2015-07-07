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

class Mapgenerate extends ActiveRecord {

    public function generate($idp, $nama_kolom, $nama_menurut, $nama_di, $tipe_data, $nama_kabupaten, $pilihwarna, $kelasLegend, $idpulau, $modelegendcanggih, $datalegendcanggih) {
        $connection = Yii::$app->db;
        //$idp ="data_timese_prov";
        // $nama_kolom="2011";
        //$nama_menurut="Provinsi";
        //$nama_di="Indonesia";
        //$tipe_data="3";

        $namaprovkabkec = "nama_prov";
        $datam[][] = array();
        $i = 0;
        $jumlahdata = 0;
        $layer = 'id_prov';
        $layerdatabase = 's00prov';
        $query = '';
        $where = "";
        
        if ($nama_menurut == "Kabupaten") {
            $namaprovkabkec = "nama_kab";
            $layer = 'id_kab';
            $layerdatabase = 's00kab';
            $where = "WHERE $nama_di = $idp.id_prov";
        }

        if ($nama_menurut == "Kecamatan") {
            $namaprovkabkec = "nama_kec";

            $layer = 'id_kec';
            $layerdatabase = 's33kec';
            if ($nama_kabupaten == "seluruhKabupaten")
                $where = "WHERE $nama_di = $idp.id_prov";
            else {
                $where = "WHERE $nama_kabupaten = $idp.id_kab";
            }
        }
        $wherepulau = '';
        if ($idpulau != 0) {
            $wherepulau = "WHERE $idp.$layer::varchar LIKE '" . $idpulau . "%'";
        }
        $kelas = $kelasLegend;
        $json_provinsi = array("status" => "OK", "jumlah" => $kelas, "jumlah2" => 0, "results" => array(), "results2" => array());

        if ($tipe_data == "1" | $tipe_data == "3") {
            if ($nama_di == "Indonesia" | $idpulau != 0) {
                $query = "SELECT $idp.$layer, $namaprovkabkec, SUM(" . $nama_kolom . ") FROM " . $idp . " INNER JOIN   $layerdatabase
ON $idp.$layer::varchar=$layerdatabase.$layer::varchar  " . $wherepulau . "GROUP BY $idp.$layer ,$namaprovkabkec  ORDER BY $idp.$layer ";
//$query = "SELECT s00kab.id_prov, s00kab.nama_prov FROM s00kab INNER JOIN   $namaTabel
//ON $namaTabel.id_kab::varchar=s00kab.id_kab::varchar GROUP BY s00kab.id_prov,s00kab.nama_prov ORDER BY s00kab.nama_prov DESC;";
            } else {
                $query = "SELECT $idp.$layer, SUM(" . $nama_kolom . "), $namaprovkabkec FROM " . $idp . " INNER JOIN   $layerdatabase ON $idp.$layer::varchar=$layerdatabase.$layer::varchar " . $where . "  GROUP BY $idp.$layer, " . $namaprovkabkec;
            }

            $command = $connection->createCommand($query);
            $dataReader = $command->query();
            $rows = $dataReader->readAll();
            $json_provinsi["jumlah2"] = $dataReader->rowCount;
            foreach ($rows as $myrow) {
//echo"hjj";
                $json_provinsi["results2"][] = array(
                            "nama" => $myrow[$namaprovkabkec],
                            "jumlah" => $myrow["sum"],
                );
                $datam[$i][$layer] = $myrow[$layer];
                $datam[$i][$nama_kolom] = $myrow["sum"];
                $i++;
            }

            if ($modelegendcanggih == "tidak") {
                $nilaimaks = 0;
                for ($x = 0; $x < $i; $x++) {
                    if ($nilaimaks < $datam[$x][$nama_kolom])
                        $nilaimaks = $datam[$x][$nama_kolom];
                }
                $batasbawah = $nilaimaks / $kelas;


                $warnalgnd = array();
                $warnalgnd = self::_warnaLegenda($pilihwarna);

                for ($x = 0; $x < $i; $x++) {
                    for ($k = 0; $k < $kelas; $k++) {
                        if (($batasbawah * (1 + $k)) >= $datam[$x][$nama_kolom]) {
                            $datam[$x]['R'] = $warnalgnd[$k]["R"];
                            $datam[$x]['G'] = $warnalgnd[$k]["G"];
                            $datam[$x]['B'] = $warnalgnd[$k]["B"];
                            break;
//echo"atas Sendiri".$datam[$x][$layer]."".$datam[$x][$nama_kolom]. "knp <br/>";
                        }
                    }
                }
                for ($k = $kelas; $k > 0; $k--) {

                    $json_provinsi["results"][] = array(
                                "batas_bawah" => $batasbawah * (x + ($k - 1) ) . " - " . $batasbawah * (x + $k ),
                                "R" => $warnalgnd[($k - 1)]["R"],
                                "G" => $warnalgnd[($k - 1)]["G"],
                                "B" => $warnalgnd[($k - 1)]["B"]
                    );
                }
            } else if ($modelegendcanggih == "ya") {
                $datalegendcanggiharray = [];

                $firstDimension = explode('|', $datalegendcanggih); // Divide by | symbol
                foreach ($firstDimension as $temp) {
                    // Take each result of division and explode it by , symbol and save to result
                    $datalegendcanggiharray[] = explode(',', $temp);
                }
                $warnalgnd = array();
                $warnalgnd = self::_warnaLegenda($pilihwarna);

                for ($x = 0; $x < $i; $x++) {
                    for ($k = 0; $k < $kelas; $k++) {
                        if ($datalegendcanggiharray[$k][0] <= $datam[$x][$nama_kolom] & $datalegendcanggiharray[$k][1] >= $datam[$x][$nama_kolom]) {
                            $datam[$x]['R'] = $warnalgnd[$k]["R"];
                            $datam[$x]['G'] = $warnalgnd[$k]["G"];
                            $datam[$x]['B'] = $warnalgnd[$k]["B"];
                            break;
//echo"atas Sendiri".$datam[$x][$layer]."".$datam[$x][$nama_kolom]. "knp <br/>";
                        } else if ($datalegendcanggiharray[$kelas - 1][2] != "kosong") {
                            $datam[$x]['R'] = $warnalgnd[$kelas - 1]["R"];
                            $datam[$x]['G'] = $warnalgnd[$kelas - 1]["G"];
                            $datam[$x]['B'] = $warnalgnd[$kelas - 1]["B"];
                        }
                    }
                }
                for ($k = 0; $k < $kelas - 1; $k++) {
                    $json_provinsi["results"][] = array(
                                "batas_bawah" => $datalegendcanggiharray[$k][2],
                                "R" => $warnalgnd[($k)]["R"],
                                "G" => $warnalgnd[($k)]["G"],
                                "B" => $warnalgnd[($k)]["B"]
                    );
                }
                if ($datalegendcanggiharray[$kelas - 1][2] != "kosong") {


                    $json_provinsi["results"][] = array(
                                "batas_bawah" => $datalegendcanggiharray[$kelas - 1][2],
                                "R" => $warnalgnd[($kelas - 1)]["R"],
                                "G" => $warnalgnd[($kelas - 1)]["G"],
                                "B" => $warnalgnd[($kelas - 1)]["B"]
                    );
                } else
                    $json_provinsi["jumlah"] = $kelas - 1;
            }

            echo json_encode($json_provinsi, JSON_PRETTY_PRINT);
        } if ($tipe_data == "2") {


            $query = "SELECT $nama_kolom FROM " . $idp . " $wherepulau GROUP BY " . $nama_kolom;
            $command = $connection->createCommand($query);
            $dataReader = $command->query();
            $rows = $dataReader->readAll();

            $num_rows = $dataReader->rowCount;

            $json_provinsi = array("status" => "OK", "jumlah" => $num_rows, "jumlah2" => 0, "results" => array(), "results2" => array());


            //  $query = "SELECT $layer, $nama_kolom FROM ".$idp;
            // $command=$connection->createCommand($query);
            //    $dataReader=$command->query();          
            // $rows=$dataReader->readAll();
            $i = 0;
            foreach ($rows as $myrow) {

//$datam[$i][$layer] =$myrow[$layer];

                $json_provinsi["results"][] = array(
                            "batas_bawah" => $myrow[$nama_kolom],
                            "R" => 255 - (($i + 1) * 70),
                            "G" => 141 - (($i + 1) * 20),
                            "B" => 100 - (($i + 1) * 25)
                );

                $i++;
            }


            $sama = 0;






            $query = "SELECT $namaprovkabkec,$idp.$layer, $nama_kolom FROM " . $idp . " INNER JOIN   $layerdatabase ON $idp.$layer::varchar=$layerdatabase.$layer::varchar $wherepulau GROUP BY $nama_kolom ,$namaprovkabkec,$idp.$layer ORDER BY $idp.$layer";
            $command = $connection->createCommand($query);
            $dataReader = $command->query();
            $rows = $dataReader->readAll();

            $json_provinsi["jumlah2"] = $dataReader->rowCount;
            $i = 0;
            $w = array();
            foreach ($rows as $myrow) {

//$datam[$i][$layer] =$myrow[$layer];

                $w[$i] = $myrow[$nama_kolom];

                $json_provinsi["results2"][] = array(
                            "nama" => $myrow[$namaprovkabkec],
                            "jumlah" => $myrow[$nama_kolom],
                );

                for ($x = 0; $x < $num_rows; $x++) {

                    if ($w[$i] == $json_provinsi["results"][$x]["batas_bawah"]) {
                        $datam[$i][$layer] = $myrow[$layer];
                        $datam[$i]['R'] = $json_provinsi["results"][$x]["R"];
                        $datam[$i]['G'] = $json_provinsi["results"][$x]["G"];
                        $datam[$i]['B'] = $json_provinsi["results"][$x]["B"];
                    }
                }$i++;
            }
            echo json_encode($json_provinsi, JSON_PRETTY_PRINT);


            //$datam[$i]['R']=$json_provinsi["results"][$x]["R"];
            // $datam[$i]['G']=$json_provinsi["results"][$x]["G"];
            //$datam[$i]['B']=$json_provinsi["results"][$x]["B"]; 
        }

        $objMap = ms_NewMapObj("");
        $objMap->Set("name", "Kab");
        $objMap->setSize(384, 204);
        $objMap->setExtent(92.59, -19.443566666, 142.88, 14.1298);
        $objMap->Set("units", MS_DD); // derajat
        $objMap->imagecolor->SetRGB(210, 233, 255);
        //$objMap->SetSymbolSet ("C:\ms4w\apps\latihan\simbol\simbol.sym");
        $objMap->SetFontSet("C:/ms4w/apps/latihan/mapsederhana/font/font.dat");
        //$objMap->outputformat->set("transparent",1);
        //  $objMap->outputformat->set("imagemode", MS_GD_ALPHA);

        $objMap->setProjection("init=epsg:4326");

        $projInObj = ms_newprojectionobj("init=epsg:4326");

        // objek web di mapfile 
        $objMap->web->set("imagepath", "C:/ms4w/Apache/htdocs/temp/");
        $objMap->web->set("imageurl", "/temp/");
        $objMap->web->set("template", "C:/ms4w/apps/latihan/html/tmplb.html");
        $objMap->setMetaData("ows_title", "Peta wilayah Indonesia");
        $objMap->setMetaData("ows_onlineresource", "http://localhost/wms?");
        $objMap->setMetaData("wms_srs", "EPSG:4326 EPSG:3857");
        $objMap->setMetaData("wms_abstract", "WMS");
        $objMap->setMetaData("wms_enable_request", "*");
        $objMap->setMetaData("wms_encoding", "utf-8");
        $objMap->setMetaData("wfs_getfeature_formatlist", "json");

        //
        // objek layer 

        $objLayerJbr = ms_newLayerObj($objMap);
        $objLayerJbr->set("name", "provinsi");
        $objLayerJbr->setConnectionType(MS_POSTGIS);
        $dbpgsql = Yii::$app->message->dbpgsql();
        $objLayerJbr->set('connection', $dbpgsql);
        $objLayerJbr->set('data', "geom from (select geom ,gid," . $layer . "," . $namaprovkabkec . " from " . $layerdatabase . " ) as subquery using unique gid using srid=43266");
        $objLayerJbr->set("classitem", $layer);
        $objLayerJbr->set("type", MS_LAYER_POLYGON);
        $objLayerJbr->set("status", MS_ON);
        $objLayerJbr->set("opacity", 85);
        // $objLayerJbr->set ("labelitem",$namaprovkabkec); // label layer
        //$objLayerJbr->set("labelcache",MS_ON);// field kelas
        //$objLayerJbr->setprocessing("LABEL_NO_CLIP=on");
        //
         // objek class & style jabar
        for ($x = 0; $x < $i; $x++) {
            $objClassJbr[] = ms_newClassObj($objLayerJbr);
            // echo "<br/>".$datam[$x][$layer]."    >>".$datam[$x]['R']." ".$datam[$x]['G']." ".$datam[$x]['B'];
            $objClassJbr[$x]->SetExpression($datam[$x][$layer]);

            $objClassJbr[$x]->Set("name", "batas_prov");
            // kodeprop=1
            $objStyleJbr[] = ms_newStyleObj($objClassJbr[$x]);


            //echo $datam[$x]['R']." ".$datam[$x]['G']." ".$datam[$x]['B']."<br/>";
            $objStyleJbr[$x]->color->setRGB($datam[$x]['R'], $datam[$x]['G'], $datam[$x]['B']);
            //echo "warna : ".$datam[$x][$layer]." ".$datam[$x]['R']." ".$datam[$x]['G']." ".$datam[$x]['B']."<br/>";
            $objStyleJbr[$x]->outlinecolor->SetRGB(0, 0, 0);

            $objClassJbr[$x]->label->Set("font", "arialbold");
            $objClassJbr[$x]->label->Set("type", MS_TRUETYPE);
            $objClassJbr[$x]->label->Set("encoding", "utf-8");
            $objClassJbr[$x]->label->Set("size", 8);
            $objClassJbr[$x]->label->Set("buffer", 7);
            $objClassJbr[$x]->label->Set("partials", TRUE);
            $objClassJbr[$x]->label->Set("align", center);
            $objClassJbr[$x]->label->Set("position", MS_CC);
            $objClassJbr[$x]->label->color->SetRGB(3, 3, 3);
            $objClassJbr[$x]->label->outlinecolor->SetRGB(242, 236, 230);

            //
        }
        $objLayerJbr->setProjection("init=epsg:4326");
        //




        $clonefullpath = Yii::$app->message->clonefullpath();
        $objMap->save($clonefullpath);
        //   
    }

    private static function _warnaLegenda($pilih) {
        $warna = array();
        if ($pilih == 1) {
            $warna[0]['R'] = 255;
            $warna[0]['G'] = 0;
            $warna[0]['B'] = 0;

            $warna[1]['R'] = 0;
            $warna[1]['G'] = 0;
            $warna[1]['B'] = 205;

            $warna[2]['R'] = 0;
            $warna[2]['G'] = 139;
            $warna[2]['B'] = 0;

            $warna[3]['R'] = 205;
            $warna[3]['G'] = 205;
            $warna[3]['B'] = 0;

            $warna[4]['R'] = 255;
            $warna[4]['G'] = 127;
            $warna[4]['B'] = 0;

            $warna[5]['R'] = 197;
            $warna[5]['G'] = 97;
            $warna[5]['B'] = 20;
        }
        if ($pilih == 2) {

            $warna[5]['R'] = 205;
            $warna[5]['G'] = 0;
            $warna[5]['B'] = 0;

            $warna[4]['R'] = 255;
            $warna[4]['G'] = 0;
            $warna[4]['B'] = 0;

            $warna[3]['R'] = 255;
            $warna[3]['G'] = 48;
            $warna[3]['B'] = 48;

            $warna[2]['R'] = 255;
            $warna[2]['G'] = 99;
            $warna[2]['B'] = 71;

            $warna[1]['R'] = 240;
            $warna[1]['G'] = 128;
            $warna[1]['B'] = 128;

            $warna[0]['R'] = 255;
            $warna[0]['G'] = 193;
            $warna[0]['B'] = 193;
        }
        if ($pilih == 3) {

            $warna[5]['R'] = 0;
            $warna[5]['G'] = 0;
            $warna[5]['B'] = 139;


            $warna[4]['R'] = 0;
            $warna[4]['G'] = 0;
            $warna[4]['B'] = 255;

            $warna[3]['R'] = 61;
            $warna[3]['G'] = 89;
            $warna[3]['B'] = 171;

            $warna[2]['R'] = 72;
            $warna[2]['G'] = 118;
            $warna[2]['B'] = 255;

            $warna[1]['R'] = 56;
            $warna[1]['G'] = 176;
            $warna[1]['B'] = 222;

            $warna[0]['R'] = 202;
            $warna[0]['G'] = 225;
            $warna[0]['B'] = 255;
        }
        if ($pilih == 4) {
            $warna[5]['R'] = 0;
            $warna[5]['G'] = 128;
            $warna[5]['B'] = 0;

            $warna[4]['R'] = 69;
            $warna[4]['G'] = 139;
            $warna[4]['B'] = 0;

            $warna[3]['R'] = 0;
            $warna[3]['G'] = 205;
            $warna[3]['B'] = 0;

            $warna[2]['R'] = 0;
            $warna[2]['G'] = 255;
            $warna[2]['B'] = 0;

            $warna[1]['R'] = 144;
            $warna[1]['G'] = 238;
            $warna[1]['B'] = 144;

            $warna[0]['R'] = 193;
            $warna[0]['G'] = 255;
            $warna[0]['B'] = 193;
        }
        return $warna;
    }

}
?>   


