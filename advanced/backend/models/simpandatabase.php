<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

class simpandatabase extends ActiveRecord {

    public function simpan($data, $namakolom, $namakolomtampil, $tipedata) {
        $json_provinsi = array("status" => "OK", "jumlah" => "");
        $datapergaris = array();

        $datapergaris = explode(PHP_EOL, $data);
        $datapergaristerbagi = array();
        $nilaiinsert = "";
        $queryinsert = "";
        $namakolomtanpaidarray = array();
          //echo "sebellumsplit".$namakolom." ";
        $tipedataquery = " integer";
        $namakolomarray = explode("|", $namakolom);
        $nilai = "";
        $kolominsert = "";
        if ($tipedata == "Integer")
            $tipedataddata = 1;
        else if ($tipedata == "Time Series")
            $tipedataddata = 3;
        else if ($tipedata != "Integer" & $tipedata != "Time Series") {
            $tipedataquery = " character(200)";
            $tipedataddata = 2;
        }
        for ($i = 1; $i < sizeof($namakolomarray); $i++) {


            if (($i + 1) == sizeof($namakolomarray)) {
                $namakolomarray[$i] = trim(preg_replace('/\s\s+/', ' ', $namakolomarray[$i]));
                if ($namakolomarray[$i] != "id_prov" & $namakolomarray[$i] != "id_kec" & $namakolomarray[$i] != "id_kab")
                    $nilai = $nilai . " " . $namakolomarray[$i] . $tipedataquery;
                else
                    $nilai = $nilai . " " . $namakolomarray[$i] . " integer";

                $kolominsert = $kolominsert . " " . $namakolomarray[$i];
            }else {
                if ($namakolomarray[$i] != "id_prov" & $namakolomarray[$i] != "id_kec" & $namakolomarray[$i] != "id_kab")
                    $nilai = $nilai . " " . $namakolomarray[$i] . $tipedataquery . ",";
                else
                    $nilai = $nilai . " " . $namakolomarray[$i] . " integer" . ",";
                $kolominsert = $kolominsert . " " . $namakolomarray[$i] . ",";
            }
        }
        //echo "tabel:".$namakolomarray[0];
        //echo  "nilai:".$nilai;
        $sql = "CREATE TABLE " . $namakolomarray[0] . " (" . $nilai . ")";
        $connection = Yii::$app->db;

        $command = $connection->createCommand($sql);
        $dataReader = $command->query();


        for ($i = 1; $i < sizeof($datapergaris) - 1; $i++) {
            $nilaiinsert = "";
            $datapergaristerbagi = explode(";", $datapergaris[$i]);
            for ($x = 0; $x < sizeof($datapergaristerbagi); $x++) {
                $datapergaristerbagi[$x] = trim(preg_replace('/\s\s+/', ' ', $datapergaristerbagi[$x]));
                if (($x + 1) == sizeof($datapergaristerbagi)) {
                    $nilaiinsert = $nilaiinsert . " '" . $datapergaristerbagi[$x] . "'";
                    break;
                }
                $nilaiinsert = $nilaiinsert . "'" . $datapergaristerbagi[$x] . "',";
            }
            $queryinsert = "INSERT INTO $namakolomarray[0] ($kolominsert) VALUES ($nilaiinsert);";
            echo $queryinsert;
            $command = $connection->createCommand($queryinsert);
            $dataReader = $command->query();
        }

        for ($i = 0; $i < sizeof($namakolomarray); $i++) {
            if ($namakolomarray[$i] != "id_prov" & $namakolomarray[$i] != "id_kec" & $namakolomarray[$i] != "id_kab") {
                $namakolomarray[$i] = trim(preg_replace('/\s\s+/', ' ', $namakolomarray[$i]));
                array_push($namakolomtanpaidarray, $namakolomarray[$i]);
            }
        }
        $namakolomtampilarray = explode("|", $namakolomtampil);


        for ($i = 1; $i < sizeof($namakolomtanpaidarray); $i++) {

            $queryddata = "INSERT INTO ddata (nama_tabel,nama_tabel_tampil,nama_kolom,nama_kolom_tampil,tipe_data) VALUES ('$namakolomtanpaidarray[0]','$namakolomtampilarray[0]','$namakolomtanpaidarray[$i]','$namakolomtampilarray[$i]','$tipedataddata');";
            //echo "////////////".$queryddata;
            $command = $connection->createCommand($queryddata);
            $dataReader = $command->query();
        }
    }

}