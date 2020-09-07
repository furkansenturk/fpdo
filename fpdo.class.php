<?php 
class fpdo { 

	private $db;

	private $tip;
	private $sutun;
	private $tablo;
	private $where;
	private $is_where;
	private $is_set;
	private $set;
	private $limit;
	private $is_limit;
	private $is_siralama;
	private $sirala;
	private $inc;
	private $lastInsertId;
	private $hata;
	
	/*fpdo içerisindeki değerleri sıfırlar*/
	private function sifirla(){
		$this->tip = null;
		$this->sutun = "*";
		$this->tablo = null;
		$this->where = null;
		$this->is_where = 0;
		$this->is_set = 0;
		$this->set = null;
		$this->limit = null;
		$this->is_limit = 0;
		$this->is_siralama = 0;
		$this->sirala = null;
		$this->set = null;
		$this->hata = 0;
		$this->inc = null;
	}
	
	/*pdo dbsini fpdo içerisine dahil eder*/
	public function __construct($db){
		$this->db = $db;
		$this->sifirla();
	}
	/* Tablo ve hangi işlemler olacağı belirtilir */
	public function select($tablo){ 
		$this->sifirla();
		$this->tip = "SELECT";
		$this->tablo = $tablo;
	}
	public function update($tablo){ 
		$this->sifirla();
		$this->tip = "UPDATE";
		$this->tablo = $tablo;
	}
	public function insert($tablo){ 
		$this->sifirla();
		$this->tip = "INSERT INTO";
		$this->tablo = $tablo;
	}
	public function delete($tablo){ 
		$this->sifirla();
		$this->tip = "DELETE";
		$this->tablo = $tablo;
	}
	
	/* Hangi sutünların çekileceğini belirtir */
	public function sutun($x="*"){
		$this->sutun = $x;
	}
	
	/*WHERE komutu*/
	public function where($x){
		/*verileri bölmek için AND veya OR kullanilir*/
		$this->is_where = 1;
		$this->where = $x;
	}
	
	/*SET Komutu*/
	public function set($x){
		$this->is_set = 1;
		$this->set = $x;
	}
	
	/*Çekilecek verileri limitler*/
	public function limit($x){
		
			$this->limit = $x;
			$this->is_limit = 1;
		
	}
	/*Çekilen verileri sıraya sokar */
	public function sirala($x= "id",$y="desc"){
		$this->is_siralama = 1;
		$this->sirala = " ORDER BY ".$x." ".$y;
	}
	/*Insert işlemi yapıldıktan sonra son eklenen id'yi çeker */
	public function lastInsertId(){
		return $this->lastInsertId;
	}

	/*ÇALIŞTIRIR*/
	public function run($x = null){
		$this->inc = $x;
		/*
			verileri tipine göre ayrı ayrı çalıştırıyoruz
		*/
		if($this->tip == "SELECT"){
			$text = "SELECT ".$this->sutun." FROM ".$this->tablo;

			/*WHERE var ise ekliyoruz*/
			if($this->is_where){
				$text.=" WHERE ".$this->where;
			} 	

		}else if($this->tip == "UPDATE"){
			$text = "UPDATE ".$this->tablo;

			/*SET var ise ekliyoruz*/
			if($this->is_set){
				$text.=" SET ".$this->set;
			}
			/*WHERE var ise ekliyoruz*/
			if($this->is_where){
				$text.=" WHERE ".$this->where;
			} 	
			/*
				update yaparken set ve where komutları olmak zorunda bunların kontrolünü yapıyoruz.
			*/
			if($this->where == null){
				$this->hata = 1;
				$this->hata_text = "UPDATE işleminin nerede yapilacağı belirtilmedi ! (WHERE komutu)";

			}else if($this->set == null){
				$this->hata = 1;
				$this->hata_text = "UPDATE işleminde ne değiştirileceği belirtilmedi ! (SET komutu)";
			}
			
		}else if($this->tip == "INSERT INTO"){
			$text = "INSERT INTO ".$this->tablo;


			if($this->is_set){
				$text.=" SET ".$this->set;
			}
			/*
				insert yaparken set komutu olmak zorunda
			*/
			if($this->set == null){
				$this->hata = 1;
				$this->hata_text = "INSERT işleminde ne değiştirileceği belirtilmedi ! (SET komutu)";
			}

		}else if($this->tip == "DELETE"){
			$text = "DELETE FROM ".$this->tablo;

			/*WHERE var ise ekliyoruz*/
			if($this->is_where){
				$text.=" WHERE ".$this->where;
			} 	
		}else{
			$this->hata = 1;
			$this->hata_text = "Veri tipi belirtilmedi (select,update,insert,delete)";
		}

		if($this->is_set == 1 || $this->is_where == 1){
			if($this->inc==null){
				$this->hata = 1;
				$this->hata_text = "Değişken belirtilmedi";
			}
		}
		/*SIRALAMAyı dahil ediyoruz*/
		if($this->is_siralama){
			$text.= $this->sirala;
		}
		/*LİMİTi dahil ettik*/
		if($this->is_limit){
			$text.= " LIMIT ".$this->limit;
		}
		/*
			yapida hata var ise sorgu yapmaya gerek yok zaten :)
		*/
			echo $text;
			print_r($this->inc);
			echo"<hr>";
		if($this->hata == 1){
			$this->sifirla();
			exit($this->hata_text);
		}else{
			$db = $this->db;
			$query = $db->prepare($text);
			$sonuc = $query->execute($this->inc);
			if($this->tip == "INSERT INTO"){
				$this->lastInsertId = $db->lastInsertId();
			}
			if($this->tip == "SELECT"){
				return $query;
			}else{
				return $sonuc;
			}

		}

	}

}
?>
