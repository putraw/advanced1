
var contents;
var contentsperbaris=[];
var contentbarispertama=[];
var namakolomtanpaid;
var tipedata="Integer";
  var namatabelkirim;
var datatampil;
var datadata;
var nilaikolomunik;
  var $btntampil;
$(document).ready(function() {



        csrfToken=yii.getCsrfToken();

  document.getElementById('fileinput').addEventListener('change', readMultipleFiles, false);
///cariDimana()
        $select=$('#namatabel').selectize();
            ambilnamatabel();

         addeventtab();

});
function addeventtab(){
      $('#myTab2 a').click(function(e) {
            if ($(this).parent('li').hasClass('active')) {
                return false;
            }
            else {
                e.preventDefault();
                $(this).tab('show');
            }
        });


             $('ul#myTab2 li').click(function(e) 
        { 
            if ($(this).text()=="Unduh Data") {
            }
            else{
            }
        });


}
function ambilNamaKolomCSV(){

                  	listkolom="<table  class='table' style='max-width:500px'>";
                  	for(i=0; i<contentbarispertama.length;i++){
					if(contentbarispertama[i]!="id_prov"&contentbarispertama[i]!="id_kab"&contentbarispertama[i]!="id_kec"){
					if(i==0){
            
						listkolom=listkolom+"<tr><td class='col-sm-3'>"+contentbarispertama[i]+" : </td><td class='col-sm-9'> <input type='text' placeholder='Nama tabel yang ditampilkan' class='form-control batasbawah'></td></tr>";
					}else{
						listkolom=listkolom+"<tr><td class='col-sm-3'>"+contentbarispertama[i]+" : </td><td class='col-sm-9'> <input type='text' placeholder='Nama kolom yang ditampilkan' class='form-control batasbawah'></td></tr>";
}



}
					}
					document.getElementById("listkolom").innerHTML =listkolom+"</table>";
          document.getElementById("comboboxjenisdata").innerHTML = "Type Data:<div class='btn-group'><button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'><span data-bind='label'>pilih</span>&nbsp;<span class='caret'></span></button><ul class='dropdown-menu' role='menu'><li><a href='#'>Integer</a></li><li><a href='#'>Character</a></li><li><a href='#'>Time Series</a></li></ul></div>"; 
          document.getElementById("buttoninsert").innerHTML = " <center><button class='btn btn-primary'  onclick='simpanData()'>Simpan</button> <button class='btn btn-default'>Batal</button></center>"
  comboboxaddevent();

}



function simpanData(){
namakolomtampil="";
namakolom="";


for(i=0;i<$("#listkolom input").length;i++){
if((i+1)==$("#listkolom input").length)
{namakolomtampil+= $("#listkolom input")[i].value;
break;}
namakolomtampil+= $("#listkolom input")[i].value +"|";
}
for(i=0;i<contentbarispertama.length;i++){
if((i+1)==contentbarispertama.length){
namakolom+=contentbarispertama[i];
break;}
namakolom+=contentbarispertama[i]+"|";
}

namakolomtanpaid="";

    $.ajax({
            url: '/advanced1/advanced/backend/web/index.php?r=site/kirimcsv',
            type: "post",
            dataType: "html",
            data:{_csrf : csrfToken,namakolomtampil:namakolomtampil,namakolom:namakolom,isicsv:contents,tipedata:tipedata},
            success: function(data) {
             
//alert(data['jumlah']);

            },
            error: function(err) {
                alert(err);
            }
        });

}

 function readMultipleFiles(evt) {
    //Retrieve all the files from the FileList object
    var files = evt.target.files; 
        
    if (files) {

        for (var i=0, f; f=files[i]; i++) {
            var r = new FileReader();
            r.onload = (function(f) {
                return function(e) {
                     contents = e.target.result;

                   /* alert( "Got the file.n" 
                          +"name: " + f.name + "n"
                          +"type: " + f.type + "n"
                          +"size: " + f.size + " bytesn"
                          + "starts with: " + contents
                    );*/ 
                    //cariKirimcsv()
                    $("#pathcsv").text(f.name);
                    contentsperbaris=[];
                    contentbarispertama=[];
                 contentsperbaris=   contents.split("\n");
                 contentbarispertama=contentsperbaris[0].split(";");
                 contentbarispertama.unshift(f.name.substring(0,(f.name).indexOf(".csv")));
                // contentbarispertama.push(f.name.substring(0,(f.name).indexOf(".csv")));
               ambilNamaKolomCSV();
                };
            })(f);

            r.readAsText(f);
        }  
         //contents="asdsahaha";
    } else {
        alert("Failed to load files"); 
    }
//  cariDimana() ;
  }

function tampilakantabel(){
     $btntampil = $("#tampilakantabel");
     $btntampil.button('loading');
    namatabel =document.getElementById("namatabel").value;
ambildatatabel();



}

function ambilnamatabel(){

  items=[];
  items2=[];
      $.ajax({
            url: '/advanced1/advanced/backend/web/index.php?r=site/ambilnamatabel',
            type: "post",
            dataType: "json",
            data:{_csrf : csrfToken},
            success: function(data) {
                results = data["results"];
                for (i = 0; i < data["jumlah"]; i++) {
                    items.push(results[i].nama_tabel);
                    items2.push(results[i].nama_tabel_tampil);
                }

                for (var i = 0; i < items.length; i++) {
                    var option = document.createElement('option');
                  
                      var selectize = $select[0].selectize;
                    selectize.addOption({value:items[i],text:items2[i]});
                }
                
              //namaTabelBerubah();

            },
            error: function(err) {
                alert(err);
            }
            

        });


}

function ambildatatabel(){
        $btntampil = $("#tampilakantabel");

  items=[];
  items2=[];
      $.ajax({
            url: '/advanced1/advanced/backend/web/index.php?r=site/ambildatatabel',
            type: "post",
            dataType: "json",
            data:{_csrf : csrfToken,namatabel:namatabel},
            success: function(data) {
             results=data["results"];
             results2=data["results2"];
             datatampil=results;
             datadata=results2;
             th="<tr><th>No</th>"
             indexunik=0;
             kolomunik="";
             for(i=0;i<data["jumlah"];i++){
                th=th+"<th>"+results[i].nama_kolom_tampil+"</th>";
                if(results[i].nama_kolom_tampil=="id_kec"){
                  indexunik=i;
             kolomunik="id_kec";
                }else if (results[i].nama_kolom_tampil=="id_kab"&kolomunik!="id_kec")
              {
              indexunik=i;
             kolomunik="id_kab";

              }else if (results[i].nama_kolom_tampil=="id_prov"&kolomunik!="id_kab"&kolomunik!="id_kec")
              {
              indexunik=i;
             kolomunik="id_prov";

              }
             }
             th=th+"</tr>";
              document.getElementById("tabelsemua").innerHTML =th;
td="";
              for(i=0;i<data["jumlah2"];i++){
                td=td+"<tr><td>"+(i+1)+"</td>";
                for(x=0;x<data["jumlah"];x++)
                {
                    td=td+"<td>"+results2[i][x]+"</td>";
             }  td=td+"<td><button onclick='editbaris(this)' value='"+results2[i][indexunik]+"' class='btn btn-warning'>edit</button>&nbsp&nbsp<button onclick='deletebaris(this)' value='"+results2[i][indexunik]+"' class='btn btn-danger'>delete</button></td></tr>";
                }

                              document.getElementById("tabelsemua").innerHTML =th+td;

              //namaTabelBerubah();
     $btntampil.button('reset');
$("#pembaharuandata").button('reset');

            },
            error: function(err) {
                alert(err);
            }
            

        });


}
function editbaris(evt){
  nomoryangdiedit=0;
  for(i=0;i<datadata.length;i++){
   if(datadata[i][indexunik]==evt.value){
nomoryangdiedit=i;
   }}
   editform="<table id='tableedit' class='table'>";
   for(i=0;i<datatampil.length;i++){

    editform=editform+"<tr><td>"+datatampil[i].nama_kolom_tampil+"</td><td><input type='text' class='form-control' value='"+datadata[nomoryangdiedit][i]+"'></td></tr>";
   }
      document.getElementById("modalEditcontent").innerHTML=editform+"</table>";

          $('#modalEditbaris').modal('show');
  nilaikolomunik=evt.value;
}
function deletebaris(evt){
  nilaikolomunik=evt.value;
 $.ajax({
            url: '/advanced1/advanced/backend/web/index.php?r=site/deletebaris',
            type: "post",
            dataType: "html",
            data:{_csrf : csrfToken,namatabel:namatabel,kolomunik:kolomunik,nilaikolomunik:nilaikolomunik},
            success: function(data) {
               
              //namaTabelBerubah();
 tampilakantabel();
            },
            error: function(err) {
                alert(err);
            }
            

        });
}

function pembaharuandata(evt){
$("#pembaharuandata").button('loading');
  datanamakolom="";
  datapembaharuan="";
for(i=0;i<$("#tableedit input").length;i++)
{
if((i+1)==$("#tableedit input").length){
  datanamakolom=datanamakolom+datatampil[i].nama_kolom;
  datapembaharuan=datapembaharuan+$("#tableedit input")[i].value;}
else{
datanamakolom=datanamakolom+datatampil[i].nama_kolom+",;,";
datapembaharuan=datapembaharuan+$("#tableedit input")[i].value+",;,";
}}
 
 $.ajax({
            url: '/advanced1/advanced/backend/web/index.php?r=site/editbaris',
            type: "post",
            dataType: "html",
            data:{_csrf : csrfToken,namatabel:namatabel,datanamakolom:datanamakolom,datapembaharuan:datapembaharuan,kolomunik:kolomunik,nilaikolomunik:nilaikolomunik},
            success: function(data) {
               
              //namaTabelBerubah();
 $('#modalEditbaris').modal('hide');
 tampilakantabel();
            },
            error: function(err) {
                alert(err);
            }
            

        });

}


function bataledit()
{
   $('#modalEditbaris').modal('hide');

}
function comboboxaddevent(){
   $(document.body).on('click', '.dropdown-menu li', function(event) {

            var $target = $(event.currentTarget);

            $target.closest('.btn-group')
                    .find('[data-bind="label"]').text($target.text())
                    .end()
                    .children('.dropdown-toggle').dropdown('toggle');
            tipedata = $target.text()
 //canggihcekboxlist();
            return false;

        });
        $(".dropdown-menu li")[0].click();


}