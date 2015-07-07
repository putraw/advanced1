<?php

$this->title = 'My Yii Application';
?>
<div id="modalLoading" data-target="#myModal" data-toggle="modal" data-keyboard="false" data-backdrop="static" class="modal  bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-sm modal-dialog-center">
        <div class="modal-content">
            <div id="modalLoadingcontent" class="modal-body">
                <br/>
                <center> <span class="glyphicon glyphicon-refresh spinning"></span> Loading... </center>
                <br/>
            </div>
        </div>
    </div>
</div>
 <div id="modalTabel" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" >
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="judulTabel"></h4>
            </div>
            <div id="modalTabelcontent" class="modal-body">
                Tidak ada data.
            </div>
        </div>
    </div>
</div>
<div id="modalLegenda" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" >
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="judulTabel">Pengaturan Legenda</h4>
            </div>
            <div id="modalLegendacontent" class="modal-body">
                Warna:<br/>
                <div id="radiopilihwarna">
                    <input type='radio' name='legendpilihwarna'  value='1' checked/>Pelangi<br/>
                    <input type='radio' name='legendpilihwarna'  value='2'/>Merah<br/>
                     <input type='radio' name='legendpilihwarna'  value='3'/>Biru<br/>
                    <input type='radio' name='legendpilihwarna'  value='4'/>Hijau<br/>
                 </div>
                 kelas:
               
                    <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <span data-bind="label">pilih</span>&nbsp;<span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#">5</a></li>
                            <li><a href="#">6</a></li>
                        </ul>
                    </div>
                    <div class="checkbox">
                         <label>
                            <input id="canggih" type="checkbox"> Canggih
                         </label>
                    </div>                                 
                       <div  id="legendacanggihbox" style="min-height:160px ;width:100% ;border:1px solid #ccc;  padding:5px;  margin-left: auto;
            margin-right: auto; padding-left:5px;">
                        </div>

                <div class="modal-footer" style="margin-top:15px;">
                     <button class="btn btn-primary" onclick='klikOkLegend()'>Ok</button> 
                     <button  onclick='klikBatalLegend()'  class="btn btn-default" >Batal</button>
                 </div>
            </div>
        </div>
    </div>
</div>

  <div id="modalSnapshot" data-keyboard="false" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="padding-left: 0px;">
      <div>
          <div id="modalSnapshotdialog" class="modal-dialog modal-lg" >

              <div  class="modal-content">
                  <div class="modal-header" >
                      <a class="btn btn-primary" id="download">Unduh</a>

                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="judul"></h4>
                  </div>            
                  <div id="modalSnapshotcontent" class="modal-body" style="overflow-y: auto">
                      <canvas id="canvas"></canvas>
                   </div>
               </div>
          </div>
      </div>
  </div>
  <div id="sidebar">

    <div class="bs-docs-example">
        <ul id="myTab" class="nav nav-tabs">
             <!--    <li class="active"><a href="#home" style="background-color:#f3f3f3;" data-toggle="tab">Peta Tematik</a></li>-->
             <!--  <li ><a href="#profile"  style="background-color:#f3f3f3;" data-toggle="tab">Peta Koordinat</a></li>-->
            <li class="active"><a href="#home"  data-toggle="tab">Peta Tematik</a></li>
             <li ><a href="#petaKoordinat"   data-toggle="tab">Peta Koordinat</a></li>
        </ul>
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade in active" id="home">
                <br/>
                Tabel:
                 <select id="namaTabel" name="namaTabel" class="combobox" onchange="namaTabelBerubah()">
                   <option value="">Pilih tabel...</option>     
                 </select>
                 
                 Atribut:
                <select id="namaKolom" name="namaKolom" class="combobox">
                  <option value="">Pilih atribut...</option>
                </select>
                                  <br/>

                 Level penyajian:
                <select id="namaMenurut" name="namaMenurut" onchange="namaMenurutBerubah()">
                    <option value="">Pilih level penyajian...</option>     

                </select>
                <div >
                 <div class="checkbox" style='float: right;'>
                         <label>
                            <input id="label" type="checkbox"  > Tampilkan label
                         </label>
                    </div>
                    </div> 
                 <br/>
                <div  id="List" style="margin-top:18px; height:200px ;width:95%;border:1px solid #ccc;  padding:5px;  margin-left: auto;
            margin-right: auto; padding-left:5px; box-shadow: 2px 2px 2px #888888;">
                <ul id="myTab2" class="nav nav-tabs">
                    <li class="active"><a href="#administratif"  data-toggle="tab">Adminitratif</a></li>
                    <li ><a href="#geografis"   data-toggle="tab">Geografis</a></li>
                </ul>

                <div  class="tab-content">
                    <div class="tab-pane fade in active" id="administratif">
                               Provinsi :
                 <select id="namaProvinsi"  name="namaProvinsi" onchange="namaProvinsiBerubah()">
                   <option value="">Pilih provinsi...</option>    
                 </select>
              Kabupaten :
                 <select id="namaKabupaten"  name="namaKabupaten" >
                    <option value="">Pilih kabupaten...</option>    
                 </select>

                    </div>
                    <div class="tab-pane fade" id="geografis">
                      Pulau :
                         <select id="namaPulau"  name="namaPulau" >
                    <option value="">Pilih kabupaten...</option>    
                 </select>
                    </div>
                </div>
                </div>
         
                <br/>
                <center>
                <button onclick="buatTematik()"  class="btn btn-primary" >buat tematik</button> &nbsp; &nbsp;
                <button onclick="hapusTematik()" class="btn btn-default" >hapus tematik</button>
                </center>
                <div id="divbuttontampildata">
                </div>
                <br/><br/>
            </div>
            <div class="tab-pane fade" id="petaKoordinat">
                <br/>
                <div  id="petaKoordinatList" style="height:300px ;width:80%;border:1px solid #ccc;  margin-left: auto;
                      margin-right: auto; padding-left:5px">
                </div><br/>
                <center><button class="btn btn-primary" onclick="buatKoordinat()">Buat Koordinat</button> <button class="btn btn-default" onclick="hapusKoordinat()">Hapus Koordinat</button></center>

            </div>
        </div>
    </div>
</div>
<div id="map" style="  width: 100%;  height: 100%;"></div>
<div id="sidebar-right">

    <input type='text' title='Tags' id="tags"/><br/>
    <input type="radio" name="cari"  onclick="klikRadioCari(this);" value="Provinsi" checked>Provinsi
    <input type="radio" name="cari" onclick="klikRadioCari(this);" value="Kabupaten">Kabupaten<br/>
    <button class="btn btn-primary btn-sm pull-right" onclick="cariDimana();">cari</button>
</div>