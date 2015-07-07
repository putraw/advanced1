<?php
namespace app\components;
use yii\base\Component;





class MessageComponent extends Component{

/////////////////// port Mapserver ///////////////////
	public $portMapserver=1236;
/////////////////////////////////////////////////////


///////////////////////////////// Sesuaikan  dengan lokasi clone.map akan diletakan //////////////////////////////////////// 

	public function clonepath(){
		//setting dimana clone.map dibuka dimapserver
		return "C:/ms4w/apps/latihan/mapsederhana/temp/";
	}
    public function clonefullpath(){
		//setting dimana clone.map dibuka dimapserver
		return "C:/ms4w/apps/latihan/mapsederhana/temp/clone.map";
	}
		public function clonemapserver(){
		//setting dimana clone.map ditempatkan 
		return "http://localhost:1236/cgi-bin/mapserv.exe?map=C:/ms4w/apps/latihan/mapsederhana/temp/clone.map";
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//////////////////////////////////// Sesuaikan dngan koneksi PGSQL /////////////////////////////////////////////////////////// 
	public function dbpgsql(){
		//setting dimana clone.map ditempatkan 
		return "host=localhost dbname=postgres user=postgres password=admin port=5432";
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	
}

?>