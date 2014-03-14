<?php
include("db.php");
class vote {
	
	public $ip, $id, $info;
	
	public function __construct($img, $ip) {
		$this->ip = $ip;
		$this->getId($img);
		$this->getInfo();
	}
	
	public function getId($img) {
		$q = mysql_query("SELECT id FROM images WHERE img = '".$img."'");
		$r = mysql_fetch_array($q);
		$this->id = $r['id'];
	}
	
	public function getInfo() {
		$q = mysql_query("SELECT * FROM images WHERE id = ".$this->id);
		$this->info = mysql_fetch_array($q);
	}
	
	public function calculatePercentages() {
		$up = 0;
		$down = 0;
		if ($this->info['up'] + $this->info['down'] > 0) {
			$up = round($this->info['up'] / ($this->info['up'] + $this->info['down'])*100,0);
			$down = 100-$up;
		}
		return array("up" => $up, "down" => $down);
	}
	
	public function changeImage($dir) {
		$a = mysql_query("SELECT * FROM images WHERE id ".$dir['symbol']." ".$this->id." LIMIT 1");
		if (mysql_num_rows($a) < 1) {
			$a = mysql_query("SELECT * FROM images ORDER BY id ".$dir['order']." LIMIT 1");
		}
		$this->info = mysql_fetch_array($a);
		$this->id = $this->info['id'];
	}
	
	public function prevImage() {
		$this->changeImage(array("symbol" =>  "<", "order" => "DESC"));
		$ret = $this->calculatePercentages();
		$ret['img'] = $this->info['img'];
		return json_encode($ret);
	}
	
	public function nextImage() {
		$this->changeImage(array("symbol" =>  ">", "order" => "ASC"));
		$ret = $this->calculatePercentages();
		$ret['img'] = $this->info['img'];
		return json_encode($ret);
	}
	
	public function voteUp() {
		$voted = "already";
		if (!$this->checkIfVoted()) {
			$voted = "voted";
			$this->vote("up");
		}
		$ret = $this->calculatePercentages();
		$ret['voted'] = $voted;
		return json_encode($ret);
	}
	
	public function voteDown() {
		$voted = "already";
		if (!$this->checkIfVoted()) {
			$voted = "voted";
			$this->vote("down");
		}
		$ret = $this->calculatePercentages();
		$ret['voted'] = $voted;
		return json_encode($ret);
	}
	
	public function checkIfVoted() {
		$a = mysql_query("SELECT id FROM voters WHERE ip = '".$this->ip."' AND images LIKE '%,".$this->id.",%'");
		return mysql_num_rows($a) > 0;
	}
	
	public function vote($dir) {
		$a = mysql_query("UPDATE images SET ".$dir."=".$dir."+1 WHERE id = ".$this->id);
		if ($a) {
			$a = mysql_query("SELECT id FROM voters WHERE ip = '".$this->ip."'");
			if (mysql_num_rows($a) > 0) {
				$a = mysql_query("UPDATE voters SET images = CONCAT(images, '".$this->id."', ',') WHERE ip = '".$this->ip."'");
			} else {
				$a = mysql_query("INSERT INTO voters (ip, images) VALUES ('".$this->ip."', ',".$this->id.",')");
			}
		}
	}
	
}