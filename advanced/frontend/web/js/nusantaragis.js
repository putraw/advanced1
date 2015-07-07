    var isiElm;
    var a;
    var layer1;
    var tauah = 0;
    var nama_provinsi;
    var layer1 = null;
    var items = [];
    var items2 = [];
    var items3 = [];
    var legenda_batas = [];
    var results, results2, results3;
    var indoLayer;
    var modetematik;
    var nama_kolom;
    var nama_menurut;
    var nama_di
    var modetematik = false;
    var legendCtrl;
    var map;
    var sidebar;
    var a_tematik = "";
    var nama_provinsi="";
    var nama_kolom_tematik = "";
    var nama_menurut_tematik = "";
    var nama_provinsi_tematik = "";
    var nama_kabupaten_tematik = "";
    var marker;
    var markers;
    var data_warna = [];
    var aTags = [];
    var radioKabupatenatauProvinsi;
    var data_tabel;
    var data_tabel_jumlah;
    var layerke = 0;
    var pilihwarna = '1';
    var kelasLegend = 5;
    var canvas;
    var  csrfToken;
    var idpulau=0;
    var modepulau="tidak";
    var modelegendcanggih="tidak";
    var modelegendcanggihtematik="tidak";
    var datalegendcanggih ="";

    //////// SESUAIKAN directory map2.map ////////////
    function pathmap2() {
        return   "c:/ms4w/apps/latihan/mapsederhana/map2.map";
    }
    ///////////////////////////////////////////////////



    function host_port() {
        str = window.location.href;
        var n = str.indexOf("/", 7);
        return   str.substr(0, n);
    }


   $(document).ready(function() {

        csrfToken=yii.getCsrfToken();
        $('#myTab a').click(function(e) {
            if ($(this).parent('li').hasClass('active')) {
                return false;
            }
            else {
                e.preventDefault();
                $(this).tab('show');
            }
        });
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
            if ($(this).text()=="Geografis") {

                modepulau="ya";
            }
            else{
                modepulau="tidak";
             
            }
        });

          
        modetematik = false;
        osm = new L.tileLayer("http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
        crs: L.CRS.EPSG4326});
        map = L.map('map').setView([-2.465337379, 118.01455336], 5);
        var osmUrl = host_port() + "/cgi-bin/mapserv.exe?map=" + pathmap2();
        indoLayer = new L.tileLayer.wms(osmUrl, {
            layers: 'propins,propins2',
            format: 'image/png',
            transparent: true,
            attribution: "NusantaraGIS",
            srs: "epsg:4326"
        }).addTo(map);
        none = new L.tileLayer.wms(osmUrl, {
            layers: '',
            format: 'image/png',
            transparent: true,
            attribution: "NusantaraGIS",
            srs: "epsg:4326"
        })
        var baseMaps = {
            "Indonesia": indoLayer,
            "OpenStreetMap": osm,
            "None"              : none
        };
        layerCtrl = L.control.layers(baseMaps, null).addTo(map);

        var osm2 = new L.TileLayer.WMS(osmUrl, {layers: 'propins', format: 'image/png',
            transparent: true, minZoom: 1, maxZoom: 5, srs: "epsg:4326",
            attribution: ""});

        var miniMap = new L.Control.MiniMap(osm2, {toggleDisplay: true}).addTo(map);
        var extentControl = L.Control.extend({
            options: {
                position: 'topleft'
            },
            onAdd: function(map) {
                var llbounds = map.getBounds();
                var container = L.DomUtil.create('div', 'extentControl leaflet-bar');
                $(container).css('font-size', '16px');
                $(container).html('<a href="#" title="Atur ulang zoom"><i class="glyphicon glyphicon-fullscreen"></i></a>');
                $(container).on('click', function() {
                    map.fitBounds(llbounds);
                });
                $('#zExt').on('click', function() {
                    map.fitBounds(llbounds);
                });
                return container;
            }
        }); 
        map.addControl(new extentControl());
   
        L.control.mousePosition().addTo(map);

        sidebar = L.control.sidebar('sidebar', {
            closeButton: true,
            position: 'left'
        });
        map.addControl(sidebar);



        var sidebarToggle = L.Control.extend({
            options: {
                position: 'topleft'
            },
            onAdd: function() {
                var container = L.DomUtil.create('div', 'extentControl leaflet-bar');
                $(container).css('font-size', '16px');
                $(container).html('<a href="#" title="Properti peta"><i class="glyphicon glyphicon-list"></i> </a>');
                $(container).on('click', function() {
                    sidebar.toggle();
                });
                $('#zProp').click(function() {
                    sidebar.toggle();
                });
                return container;
            }
        });
        map.addControl(new sidebarToggle());

        sidebarright = L.control.sidebar('sidebar-right', {
            closeButton: true,
            position: 'right'
        });
        map.addControl(sidebarright);



        var sidebarrightToggle = L.Control.extend({
            options: {
                position: 'topright'
            },
            onAdd: function() {
                var container = L.DomUtil.create('div', 'extentControl leaflet-bar');
                $(container).css('font-size', '16px');
                $(container).html('<a href="#" title="Properti peta"><i class="glyphicon glyphicon-search"></i></a>');
                $(container).on('click', function() {
                    sidebarright.toggle();
                });
                $('#zProp').click(function() {
                    sidebarright.toggle();
                });
                return container;
            }
        });
        map.addControl(new sidebarrightToggle());
        var snapshot = L.Control.extend({
            options: {
                position: 'topleft'
            },
            onAdd: function() {
                var container = L.DomUtil.create('div', 'extentControl leaflet-bar');
                $(container).css('font-size', '16px');
                $(container).html('<a href="#" title="Properti peta"><i class="glyphicon glyphicon-camera"></i> </a>');
                $(container).on('click', function() {
                    tampilModalSnapshot();
                });
                $('#zProp').click(function() {
                    tampilModalSnapshot();
                });
                return container;
            }
        });
        map.addControl(new snapshot());

        map.on('click', function(e) {
            var latlng = e.latlng.lng + " " + e.latlng.lat;
            var popLocation = e.latlng;
            a_tematik = a_tematik.replace(/\s+/, "");
            $.ajax({
                url: host_port() + '/advanced1/advanced/frontend/web/index.php?r=site/ambilpostgre',
                type: "post",
                dataType: "json",
                data:{_csrf : csrfToken,latlng:latlng,modetematik: modetematik,idp:a_tematik.replace(/\s+/, "") ,nama_kolom:getNamakolom(nama_kolom_tematik),nama_menurut: nama_menurut_tematik,nama_di:nama_provinsi_tematik,nama_kabupaten: nama_kabupaten_tematik ,tipe_data:getTipedata(nama_kolom_tematik),idpulau:idpulau},
                success: function(data) {
                    var resultklikp = data["results"];

                    if (getTipedata(nama_kolom_tematik) != "3") {
                        var popup = L.popup()
                        var popup = L.popup()
                                .setLatLng(popLocation)
                                .setContent('' + resultklikp[0].nama_provinsi + " " + resultklikp[0].data_jumlah)
                                .openOn(map);


                    }
                    if (getTipedata(nama_kolom_tematik) == "3") {
                        jumlah_tseries = [];
                        tahun_tseries = [];
                        // alert(data["jumlah"]+"");
                        jumlah_sesuai_thn = 0;
                        for (i = 0; i < data["jumlah"]; i++) {
                            jumlah_tseries[i] = resultklikp[i].data_jumlah;
                            tahun_tseries[i] = getTahun(resultklikp[i].tahun);
                            if (resultklikp[i].tahun == getNamakolom(nama_kolom_tematik))
                                jumlah_sesuai_thn = jumlah_tseries[i];
                        }
                        var popup = L.popup()
                                .setLatLng(popLocation)
                                .setContent("<b>" + resultklikp[0].nama_provinsi + "</b><br/><b>" + getTahun(getNamakolom(nama_kolom_tematik)) + ":" + jumlah_sesuai_thn + "</b><br/><div id='canvas-holder2'><canvas id='chart2' width='200' height='200' /></div>")
                                .openOn(map);
                        buatChart();

                    }




                },
                error: function(err) {
                    alert(err);
                }
            });

        });
        $select=$('#namaTabel').selectize();
        $select2=$('#namaKolom').selectize();
        $select3=$('#namaMenurut').selectize();
        $select4=$('#namaProvinsi').selectize();
        $select5=$('#namaKabupaten').selectize();
        $select6=$('#namaPulau').selectize();
        $.ajax({
            url: host_port() + '/advanced1/advanced/frontend/web/index.php?r=site/ambilnamatabeldanatribut',
            type: "post",
            dataType: "json",
            data:{_csrf : csrfToken,ambil:'nama_tabel'},
            success: function(data) {
                results = data["results"];
                for (i = 0; i < data["jumlah"]; i++) {
                    items.push(results[i].nama_tabel);
                    items2.push(results[i].nama_tabel_tampil);
                }

                var select = document.getElementById("namaTabel");
                for (var i = 0; i < items.length; i++) {
                    var option = document.createElement('option');
                    option.text = items2[i];
                    option.value = items[i];
                    select.add(option, 0);
                      var selectize = $select[0].selectize;
                    selectize.addOption({value:items[i],text:items2[i]});
                }
                
              namaTabelBerubah();

            },
            error: function(err) {
                alert(err);
            }
            

        });


        $(document.body).on('click', '.dropdown-menu li', function(event) {

            var $target = $(event.currentTarget);

            $target.closest('.btn-group')
                    .find('[data-bind="label"]').text($target.text())
                    .end()
                    .children('.dropdown-toggle').dropdown('toggle');
            kelasLegend = $target.text()
 canggihcekboxlist();
            return false;

        });
        $(".dropdown-menu li")[3].click();

        radioKabupatenatauProvinsi = "Provinsi";

        tampilkanCekBoxKoordinat();

        $("input:radio[name=cari]").click(function() {
            radioKabupatenatauProvinsi = $(this).val();
        });



        $("#tags").autocomplete({
            source: function(request, response) {
                $.ajax({
                    dataType: "json",
                    type: 'post',
                    url: host_port() + '/advanced1/advanced/frontend/web/index.php?r=site/cariprovinsikabupaten',
                    data:{_csrf : csrfToken,yangDiketik:$('#tags').val(),yangDicari:radioKabupatenatauProvinsi},
                    success: function(data) {
                        aTags = [];
                        results = data["results"];

                        $('input.suggest-user').removeClass('ui-autocomplete-loading');

                        for (i = 0; i < data["jumlah"]; i++) {
                            if (radioKabupatenatauProvinsi == "Provinsi")
                                aTags.push(results[i].nama_prov);
                            if (radioKabupatenatauProvinsi == "Kabupaten")
                                aTags.push(results[i].nama_kab + ", Prov. " + results[i].nama_prov);
                        }


                        response(aTags);

                    },
                    error: function(data) {
                        $('input.suggest-user').removeClass('ui-autocomplete-loading');
                        alert("error");
                    }
                });
            },
            minLength: 2
        });

     

        canggihcekbox();
    });

    function buatChart() {

        Chart.defaults.global.pointHitDetectionRadius = 1;

        var lineChartData = {
            labels: tahun_tseries,
            datasets: [{
                    label: "My Second dataset",
                    fillColor: "rgba(151,187,205,0.2)",
                    strokeColor: "rgba(151,187,205,1)",
                    pointColor: "rgba(151,187,205,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(151,187,205,1)",
                    data: jumlah_tseries
                }]
        };




        var ctx2 = document.getElementById("chart2").getContext("2d");
        myLine = new Chart(ctx2).Line(lineChartData, {
            responsive: true

        });


    }
    function tableCreate() {
        $("#judulTabel").text(nama_kolom_tematik_tampil);
        value_row = "";
        for (i = 0; i < data_tabel_jumlah; i++)
            value_row = value_row + "<tr><td>" + (i + 1) + "</td><td>" + data_tabel[i].nama + "</td> <td>" + data_tabel[i].jumlah + "</td></tr>";
        document.getElementById("modalTabelcontent").innerHTML = "<div ><table class='table table-striped' ><tr><th>No</th><th>" + nama_menurut_tematik + "</th> <th>" + nama_kolom_tematik_tampil + "</th></tr>" + value_row + "</table></div>";
        //document.getElementById("modalTabel").write('asdasdasdasdhallo');
    }

    function cariDimana() {
        yangDiketik = $("#tags").val();

        $.ajax({
            url: 'http://localhost:8081/advanced1/advanced/frontend/web/index.php?r=site/carititiktengah',
            type: "post",
            dataType: "json",
            data:{_csrf : csrfToken,yangDiketik:getNamaCari(yangDiketik),yangDicari:radioKabupatenatauProvinsi},
            success: function(data) {
                results2 = data["results2"];
                results = data["results"];
                envelope = results2[0].envelope;
                coordinates = envelope['coordinates'];
                var latlng = [];
                var lltemp;
                for (i = 0; i < coordinates[0].length; i++) {

                    lltemp = L.latLng(coordinates[0][i][1], coordinates[0][i][0]);

                    latlng.push(lltemp);
                }

                map.fitBounds(L.latLngBounds(latlng));
                var popLocation = L.latLng(results[0].latitude, results[0].longitude);

                var popup = L.popup()
                var popup = L.popup()
                        .setLatLng(popLocation)
                        .setContent('' + yangDiketik + " ").openOn(map);


            },
            error: function(err) {
                alert(err);
            }
        });

    }



    function tampilModalSnapshot() {
        if ($("#legendtocanvas")[0]) {
            $('#modalLoading').modal('show');
        }
        $('#modalSnapshot').modal('show');
        klik();
        $('#modalSnapshot').on('shown.bs.modal', function(e) {
            document.getElementById("modalSnapshot").style.padding = "0px";
        });

   $('#modalSnapshot').on('hidden.bs.modal', function() {
            document.body.style.padding = "50px 0px 0px 0px";
        });
    }
    function downloadCanvas(link, canvasId, filename) {
        link.href = document.getElementById(canvasId).toDataURL();
        link.download = filename;
    }

    function klik() {
        leafletlayer = document.getElementsByClassName('leaflet-layer');
        img1 = leafletlayer[layerke].getElementsByClassName('leaflet-tile-loaded');
        canvas = document.getElementById('canvas');
        var context = canvas.getContext('2d');

        canvas.width = img1[0].width * 5.73;
        canvas.height = img1[0].height * 2.3;

        for (i = 0; i < img1.length - 2; i++) {
            var left = img1[i].style.left;
            var top = img1[i].style.top;
            left = left.substring(0, left.length - 2);
            top = top.substring(0, top.length - 2);
            context.globalAlpha = 1.0;
            context.drawImage(img1[i], left, top);
        }

        var target = document.getElementById('legendtocanvas');
        if ($("#legendtocanvas")[0]) {


            context.font = "bold 24px Arial";
            context.textAlign = "center";
            context.fillText(nama_tabel_tematik_tampil.trim(), img1[0].width * 5.5 / 2, 30);
            context.font = "bold 20px Arial";
            context.fillText(nama_kolom_tematik_tampil.trim(), img1[0].width * 5.5 / 2, 50);

            html2canvas(target, {
                onrendered: function(canvas) {
                    context.globalAlpha = 1.0;
                    context.drawImage(canvas, 100, 450);
                    $('#modalLoading').modal('hide');

                }});
        } else {

        }
        document.getElementById('download').addEventListener('click', function() {
            downloadCanvas(this, 'canvas', 'peta.png');
        }, false);
    }
    function klikSaveCanvas() {
        var image = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");  // here is the most important part because if you dont replace you will get a DOM 18 exception.
        window.location.link = "peta.png";
        window.location.href = image;

    }
  
   
    function tampilkanData() {
      $('#modalTabel').modal('show');
        tableCreate();

      
 
    }
    function ambilNamaPulau(kodewilayahtersedia){

        var namaTabel = document.getElementById("namaTabel").value;
        $.ajax({
            url: host_port() + '/advanced1/advanced/frontend/web/index.php?r=site/tentukanpulau',
            type: "post",
            dataType: "json",
            data:{_csrf : csrfToken,namaTabel: namaTabel,kodewilayahtersedia: kodewilayahtersedia},
            success: function(data) {
                results = data["results"];
                for (i = 0; i < data["jumlah"]; i++) {
                    items.push(results[i].nama_pulau);
                }
            
                var selectize6 = $select6[0].selectize;
                selectize6.clearOptions();
                if(items.length==0){
                    selectize6.disable();}
                else
                    selectize6.enable();

                for (var i = 0; i < items.length; i++) {      
                    selectize6.addOption({value:getIdPulau(items[i]),text:getNamaPulau(items[i])});

                }
      
                
            },
            error: function(err) {
                alert(err);
            }
        });
    

    }
    function namaTabelBerubah() {
        var namaTabel = document.getElementById("namaTabel").value;
        $("#namaKolom").empty();
        $("#namaMenurut").empty();
        items = [];
        items2 = [];
        items3 = [];
        $.ajax({
            url: host_port() + '/advanced1/advanced/frontend/web/index.php?r=site/ambilnamatabeldanatribut',
            type: "post",
            dataType: "json",
            data:{_csrf : csrfToken,ambil:'nama_kolom',namaTabel: namaTabel},
            success: function(data) {
                results = data["results"];
                results2 = data["results2"];
                for (i = 0; i < data["jumlah"]; i++) {
                    items.push(results[i].nama_kolom);
                    items2.push(results[i].nama_kolom_tampil);
                }
                items.sort();
                for (i = 0; i < data["jumlah2"]; i++) {
                    items3.push(results2[i].menurut);
                }
                items2.sort();
                 var selectize = $select2[0].selectize;
                 selectize.clearOptions();
               if(items.length==0){
                    selectize.disable();}
                else
                    selectize.enable();
                var select = document.getElementById("namaKolom");
                for (var i = 0; i < items.length; i++) {
                 
                      
                    selectize.addOption({value:items[i],text:items2[i]});

                }
            
                 var selectize = $select3[0].selectize;
                 selectize.clearOptions();
                 if(items3.length==0){
                    selectize.disable();}
                 else
                    selectize.enable();
                select = document.getElementById("namaMenurut");
                for (var i = 0; i < items3.length; i++) {
                    option = document.createElement('option');
                    option.text = option.value = items3[i];
                    select.add(option, 0);
                      selectize.addOption({value: items3[i],text: items3[i]});
                }        

                ambilNamaPulau(items3[0]);
                namaMenurutBerubah();
            },
            error: function(err) {
                alert(err);
            }
        });
    }
    function namaMenurutBerubah() {
        $("#namaProvinsi").empty();
        var namaMenurut = document.getElementById("namaMenurut").value;
        var namaTabel = document.getElementById("namaTabel").value;
        items = [];
        items2 = [];
        $.ajax({
            url: host_port() + '/advanced1/advanced/frontend/web/index.php?r=site/ambilnamatabeldanatribut',
            type: "POST",
            dataType: "json",
            data:{_csrf : csrfToken,ambil:'nama_di',namaTabel:namaTabel,namaMenurut:namaMenurut},
            success: function(data) {
                results = data["results"];

                indonesia = "tidak";

                for (i = 0; i < data["jumlah"]; i++) {
                    if (results[i].di == "Indonesia") {
                        items.push("Seluruh Indonesia");
                    items2.push("Indonesia");
                        continue;
                    }
                    items.push(results[i].di);
                    items2.push(results[i].id_prov);
                }
                // items3.sort();
                if (indonesia == "ya") {
                    
                }

                  var selectize = $select4[0].selectize;
                 selectize.clearOptions();
                if(items.length==0){
                    selectize.disable();}
                 else
                    selectize.enable();

                select = document.getElementById("namaProvinsi");
                   
          
                for (var i = 0; i < items.length; i++) {
                    option = document.createElement('option');
                    option.text = items[i];
                    option.value = items2[i];
                    select.add(option, 0);
                         selectize.addOption({value: items2[i],text: items[i]});
                }
                namaProvinsiBerubah();
            },
            error: function(err) {
                alert(err);
            }
        });
    }
    function namaProvinsiBerubah() {
        $("#namaKabupaten").empty();
        var namaMenurut = document.getElementById("namaMenurut").value;
        var namaProvinsi = document.getElementById("namaProvinsi").value;
        var namaTabel = document.getElementById("namaTabel").value;
        items = [];
        items2 = [];
        $.ajax({
            url: host_port() + '/advanced1/advanced/frontend/web/index.php?r=site/ambilnamatabeldanatribut',
            type: "POST",
            dataType: "json",
             data:{_csrf : csrfToken,ambil:'nama_kabupaten',namaTabel:namaTabel,namaMenurut:namaMenurut,namaDi:namaProvinsi},
            success: function(data) {
                results = data["results"];

                for (i = 0; i < data["jumlah"]; i++) {
                    items.push(results[i].kabupaten);
                    items2.push(results[i].id_kab);
                }

                select = document.getElementById("namaKabupaten");
                       var selectize = $select5[0].selectize;
                 selectize.clearOptions();
                 if(items.length==0){
                    selectize.disable();}
                 else
                    selectize.enable();
                for (var i = 0; i < items.length; i++) {
                    option = document.createElement('option');
                    option.text = items[i];
                    option.value = items2[i];
                    select.add(option, 0);
                selectize.addOption({value: items2[i],text: items[i]});

                }

                //sidebar.show();
            },
            error: function(err) {
                alert(err);
            }
        });
    }

    function buatTematik() {

        /*Mengetes jika buat tematik sudah jalan secara terpisah*/
        //http://localhost:1236/advanced1/advanced/frontend/web/index.php?r=site/mapgenerate&idp=data_penangkaran_hewan&nama_kolom=penangkaran_hewan&nama_menurut=Provinsi&nama_di=Indonesia&tipe_data=2
        hapusTematik();
        layerke = 1;

        a = document.getElementById("namaTabel").value;
        nama_kolom = document.getElementById("namaKolom").value;
        nama_menurut = document.getElementById("namaMenurut").value;
        nama_provinsi = document.getElementById("namaProvinsi").value;
        nama_kabupaten = document.getElementById("namaKabupaten").value;

        /*Menampilkan inputan yang dipilih oleh pengguna*/
        alert(a);
        alert(nama_kolom);
        alert(nama_menurut);
        alert(nama_provinsi);
        alert(nama_kabupaten);
        
        
        nama_kolom_tematik_tampil = $('#namaKolom option:selected').text();
        nama_tabel_tematik_tampil = $('#namaTabel option:selected').text();

        a_tematik = a;
        nama_kolom_tematik = nama_kolom;
        nama_menurut_tematik = nama_menurut;
        nama_provinsi_tematik = nama_provinsi;
        nama_kabupaten_tematik = nama_kabupaten;

        if(modepulau=="ya"){
            idpulau=document.getElementById("namaPulau").value;
        }
        // alert(getNamakolom(nama_kolom));
        if (a != "") {
            modetematik = true;
            $.ajax({
                url: host_port() + '/advanced1/advanced/frontend/web/index.php?r=site/mapgenerate',
                type: "post",
                dataType: "json",
                data:{_csrf : csrfToken,idp:a,nama_kolom:getNamakolom(nama_kolom),nama_menurut:nama_menurut,nama_di:nama_provinsi,nama_kabupaten:nama_kabupaten,tipe_data:getTipedata(nama_kolom),pilihwarna:pilihwarna,kelasLegend:kelasLegend,idpulau:idpulau,modelegendcanggih:modelegendcanggihtematik,datalegendcanggih:datalegendcanggih},
                success: function(data) {
                    results = data["results"];
                    data_tabel = data["results2"];
                    data_tabel_jumlah = data["jumlah2"];
                    for (i = 0; i < data["jumlah"]; i++) {
                        legenda_batas.push([results[i].batas_bawah, results[i].R, results[i].G, results[i].B]);
                        //  alert(results[i].batas_bawah);
                    }

                    if (map.hasLayer(layer1)) {
                        tauah++;
                        layer1.setUrl(host_port() + "/advanced1/advanced/frontend/web/index.php?r=site/redirect&tauah=" + tauah);
                    } else {
                        tauah++;

                        layer1 = new L.tileLayer.wms(host_port() + "/advanced1/advanced/frontend/web/index.php?r=site/redirect&tauah=" + tauah, {
                            layers: 'provinsi',
                            format: 'image/png',
                            transparent: true,
                            attribution: "NusantaraGIS",
                        });
                        layer1.addTo(map);
                        layer1.bringToFront();
                        layerCtrl.removeLayer(layer1);
                        layerCtrl.addOverlay(layer1, 'provinsi');
                        init_legendCtrl();
                    }
                      tampildatabtn ="<br/><button title='Tampilkan Data' class='btn btn-sm btn-primary' onclick='tampilkanData()' style='float: right;'><span class='glyphicon glyphicon-list-alt' ></span></button>"
                 document.getElementById("divbuttontampildata").innerHTML =tampildatabtn;

                },
                error: function(err) {
                    alert(err);
                }
            });

            // Perform other work here ...
        }
        // Set another completion function for the request above
    }

    function tampilkanCekBoxKoordinat() {
        $.ajax({
            url: host_port() +'/advanced1/advanced/frontend/web/index.php?r=site/ambillistkoordinat'
                    ,
            type: "post",
            dataType: "json",
            data:{_csrf : csrfToken},
            success: function(data) {
                results = data["results"];
                for (i = 0; i < data["jumlah"]; i++) {
                    var checkbox = document.createElement('input');
                    checkbox.type = "checkbox";
                    checkbox.name = "name";
                    checkbox.value = results[i].nama_tabel;
                    checkbox.id = "id";
                    checkbox.class = "nameCheckbox";
                    var label = document.createElement('label');
                    label.htmlFor = "id";
                    label.appendChild(document.createTextNode(results[i].nama_tabel_tampil));

                    var mybr = document.createElement('br');

                    document.getElementById("petaKoordinatList").appendChild(checkbox);
                    document.getElementById("petaKoordinatList").appendChild(label);
                    document.getElementById("petaKoordinatList").appendChild(mybr);
                }


            },
            error: function(err) {
                alert(err);
            }
        });


    }
    
    function buatKoordinat() {
        if (map.hasLayer(markers)) {

            hapusKoordinat();
        }
        idpulau=0;
        nama_tabel_koordinat = "";
        nilaicheckbox = [];
        markers = new L.FeatureGroup();
        var i = 0;
        var checkedValue = $('#id:checked').each(function() {
            nama_tabel_koordinat = nama_tabel_koordinat + getHapusWhiteSpace($(this).val()) + ";";

            i++;
        });
        $.ajax({
            //url: host_port() + '/advanced1/advanced/frontend/web/index.php?r=site/mapgenerate&idp=' + a + '&nama_kolom=' + getNamakolom(nama_kolom) + '&nama_menurut=' + nama_menurut + '&nama_di=' + nama_di + '&tipe_data=' + getTipedata(nama_kolom)
            url: host_port() +'/advanced1/advanced/frontend/web/index.php?r=site/koordinat'
                    ,
            type: "post",
            dataType: "json",
            data:{_csrf : csrfToken,nama_tabel_koordinat:nama_tabel_koordinat},
            success: function(data) {
                results = data["results"];

                var jumlah = 0;
                for (i = 0; i < data["jumlah_tabel"]; i++)
                    jumlah = data["jumlah_per_tabel"][i] + jumlah;

                data_warna = ["red", "green", "lightblue", "lightgray", "orange", "white", "beige", "pink"];

                x = 0;
                var jumlah_sementara = data["jumlah_per_tabel"][0];


                for (i = 0; i < jumlah; i++) {

                    if (i == jumlah_sementara) {
                        x++;
                        jumlah_sementara = data["jumlah_per_tabel"][x] + jumlah_sementara;
                    }
                    marker = L.marker([getKoordinatLat(results[i].koordinat), getKoordinatLng(results[i].koordinat)], {icon: L.AwesomeMarkers.icon({icon: results[i].icon_marker, prefix: 'glyphicon', markerColor: data_warna[x], spin: false})});
                    marker.bindPopup("keterangan" + results[i].keterangan);
                    markers.addLayer(marker);

                }
                markers.addTo(map);

            },
            error: function(err) {
                alert(err);
            }
        });
    }
    function hapusKoordinat() {

        map.removeLayer(markers);
    }


    function hapusTematik() {
                         document.getElementById("divbuttontampildata").innerHTML ='';

        layerke = 0;
        idpulau=0;
        if (legendCtrl != undefined) {
            map.removeLayer(layer1);
            layerCtrl.removeLayer(layer1);
            modetematik = false
            legendCtrl.removeFrom(map)
            isiElm = "";
            legendCtrl = null;
        }
        legenda_batas = [];
    }
    function init_legendCtrl() {
        if (legendCtrl != undefined) {
            legendCtrl.removeFrom(map)
            isiElm = "";
            legendCtrl = null;
        }
        legendCtrl = L.control({position: 'bottomleft'});
        legendCtrl.onAdd = function(map) {
            this.div = L.DomUtil.create('div', 'info legend');
            this.update();
            return this.div;
        };
        legendCtrl.update = function(props) {
            isiElm = '<div id="legendtocanvas"><b>Legenda</b> <br/>';
            //      isiElm += '<img src="http://localhost:8081/mapsederhana/redirect2.php?tauah="'+tauah+ '" alt="legend">';
            for (i = 0; i < legenda_batas.length; i++) {
                isiElm += "<div style='  margin-bottom: 3px;'><span id='kotak' style='background-color:   " + rgbToHex(legenda_batas[i][1], legenda_batas[i][2], legenda_batas[i][3]) + "  ;   '> </span> " + legenda_batas[i][0] + "</div>";
            }
            if (getTipedata(nama_kolom_tematik) != 2)
                isiElm += "</div><bu type='button' class='btn btn-sm btn-primary'style='float: right;'' onclick='legendaPilihan()'><span class='glyphicon glyphicon-th' ></span></button>"

            this.div.innerHTML = isiElm;
        };
        legendCtrl.addTo(map);
    }
    function legendaPilihan() {
        $('#modalLegenda').modal('show');

        $("input:radio[name=legendpilihwarna]").click(function() {
            pilihwarna = $(this).val();
        });
      
    }

    function klikOkLegend() {
        $('#modalLegenda').modal('hide');
        if(modelegendcanggih=="ya"){
            canggihtextbox();
            modelegendcanggihtematik=modelegendcanggih;
        }
        buatTematik();
        modelegendcanggihtematik="tidak";
    }

    function klikBatalLegend() {

        $('#modalLegenda').modal('hide');
           
    }

    function canggihcekbox(){
          
         $('#canggih').change(function() {
        canggihcekboxlist();
    });

    }
    function canggihtextbox(){
        datalegendcanggih="";
       for(i=0;i<$("#legendacanggihbox input").length-1;i=i+3)
    {
datalegendcanggih=$("#legendacanggihbox input")[i].value+","+$("#legendacanggihbox input")[i+1].value+","+$("#legendacanggihbox input")[i+2].value+"|"+datalegendcanggih;

    }
    if($("#legendacanggihbox input")[i].value=="")
datalegendcanggih=datalegendcanggih+",,kosong";
else 
datalegendcanggih=datalegendcanggih+",,"+$("#legendacanggihbox input")[i].value;


    }
    function canggihcekboxlist(){

        document.getElementById("legendacanggihbox").innerHTML ="";
        if($('#canggih').is(":checked")) {
            canggihcekboxelem="<table  class='table'>";
            for(i=0;i<kelasLegend-1;i++){
            canggihcekboxelem=canggihcekboxelem+"<tr><td>"+(i+1)+"</td><td><input class='form-control batasbawah'  placeholder='Batas bawah' onkeypress='validate(event)' type='text'></td><td><input class='form-control batasatas' placeholder='Batas atas' onkeypress='validate(event)' type='text'></td><td><input class='form-control' placeholder='Label' type='text'></td></tr>";
                }
             canggihcekboxelem=canggihcekboxelem+"<tr><td>"+(i+1)+"</td><td>Lainnya: </td><td></td><td><input class='form-control' placeholder='Label' type='text'></td></tr>"+"</table>";          
              document.getElementById("legendacanggihbox").innerHTML =canggihcekboxelem;
        

                modelegendcanggih="ya";
        }else{
                            modelegendcanggih="tidak";

        }
}

        

    function validate(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var regex = /[0-9]|\.|\-/;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}
    function componentToHex(c) {
        var hex = c.toString(16);
        return hex.length == 1 ? "0" + hex : hex;
    }
    function rgbToHex(r, g, b) {
        return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
    }
    function getNamakolom(nama_kolom) {
        nm = nama_kolom.substr(0, nama_kolom.length - 1);
        return   nm.replace(/\s+/, "");
    }
    function getTipedata(nama_kolom) {
        return  nama_kolom.substr(nama_kolom.length - 1, nama_kolom.length - 1);
    }
    function getTahun(nama_kolom) {
        return  nama_kolom.substr(1, nama_kolom.length);
    }
    function getKoordinatLat(nama_kolom) {
        return  nama_kolom.substr(1, nama_kolom.indexOf(',') - 1);
    }
    function getNamaCari(nama_kolom) {
        if (nama_kolom.indexOf(',') != -1)
            nama_kolom = nama_kolom.substr(0, nama_kolom.indexOf(','));
        return  nama_kolom;
    }
    function getKoordinatLng(nama_kolom) {
        return  nama_kolom.substr(nama_kolom.indexOf(',') + 1, nama_kolom.indexOf(')') - 9);
    }
    function getHapusWhiteSpace(nama_kolom) {
        return  nama_kolom.replace(/\s+/, "");
    }
      
     function getNamaPulau(nama_kolom) {
        return  nama_kolom.substr(0, nama_kolom.length - 1);
    }
        function getIdPulau(nama_kolom) {
        return  nama_kolom.substr(nama_kolom.length-1, nama_kolom.length-1);
    }