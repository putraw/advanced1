<?php
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
 <div id="modalEditbaris" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" >
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="judulTabel"><b>Edit</b></h4>
            </div>
            <div id="modalEditcontent" class="modal-body">

            </div>
             <div class="modal-footer" style="margin-top:15px;">
                     <button class="btn btn-primary" id="pembaharuandata" data-loading-text="Tunggu..." onclick='pembaharuandata()'>Perbaharui</button> 
                     <button  onclick=' bataledit()'  class="btn btn-default" >Batal</button>
                 </div>
        </div>
    </div>
</div>
<div id="modalDeletebaris" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" >
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="judulTabel">Delete</h4>
            </div>
            <div id="modalDeletecontent" class="modal-body">
              
            </div>
        </div>
    </div>
</div>
       <ul id="myTab2" class="nav nav-tabs">
                    <li class="active"><a href="#unggah"  data-toggle="tab">Unggah Data</a></li>
                    <li ><a href="#edit"   data-toggle="tab">Edit Data</a></li>
                </ul>

                <div  class="tab-content">
                    <div class="tab-pane fade in active" id="unggah">
                            <br/>
                  <div class="panel panel-primary">
  <div class="panel-heading">

    <h3 class="panel-title">  Unggah Data</h3>
  </div>
  <div class="panel-body">
    <center>
   <span id="pathcsv" >
        
    </span>
    <br/>
    <span class="btn btn-default btn-file">

        Browse <input id="fileinput" type="file" name="file"  accept=".csv" multiple>

    </span>
   
<div id="" >
 
    <br/> <br/>
  <div id="listkolom">
  </div>
  <div id="comboboxjenisdata">
 
    </div>
  <br/>
  <div id="buttoninsert">
    
  </div>
</div>
    </center>
</div>
</div>


                    </div>
                    <div class="tab-pane fade" id="edit">
                       <br/>
                     <div class="panel panel-primary">
  <div class="panel-heading">

    <h3 class="panel-title">  Edit Data</h3>
  </div>
  <div class="panel-body">
    <div style="max-width:300px">
            <select id="namatabel"  name="namatabel">
                    <option value="">Pilih nama tabel...</option>    
                 </select>
                 <button id="tampilakantabel" onclick="tampilakantabel()" data-loading-text="Tunggu..." class="btn btn-primary">Tampilkan</button>
</div>
<br/>
<div class="table-responsive">
 <table id="tabelsemua" class="table">
    
  </table>
</div>
</div>
</div>
                    </div>
                </div>