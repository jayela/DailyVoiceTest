<?php

require("class-vote.php");

$db = mysql_connect ("localhost","root","");
mysql_select_db("dv",$db);

class voteTest extends PHPUnit_Framework_TestCase {
	
	public $vote;
	
	public function setUp() {
		$this->processIsolation = true;
		$a = mysql_query("SELECT id,img FROM images");
		if ($a === false) {
			die(mysql_error());
		}
		$b = mysql_fetch_array($a);
		$this->vote = new vote($b['img'], rand(1,255).".".rand(0,255).".".rand(0,255).".".rand(0,255));
		for($i=0;$i<10;$i++) {
			do {
				$rand = rand(1,2);
				if ($rand == 1) $this->vote->voteDown(); else $this->vote->voteUp();
				$this->vote->nextImage();
			} while ($this->vote->id != $b['id']);
			$this->vote->ip = rand(1,255).".".rand(0,255).".".rand(0,255).".".rand(0,255);
		}
	}
	
	public function testPercentages() {
		$a = mysql_query('SELECT * FROM images ORDER BY id ASC');
		while($b = mysql_fetch_array($a)) {
			$up = round($b['up'] / ($b['up'] + $b['down'])*100,0);
			$down = 100-$up;
			$per = $this->vote->calculatePercentages();
			$this->assertEquals($up,$per['up']);
			$this->assertEquals($down,$per['down']);
			$this->vote->nextImage();
		}
	}
	
	public function testDuplication() {
		$a = mysql_query("SELECT ip FROM voters ORDER BY id DESC LIMIT 5");
		while ($b = mysql_fetch_array($a)) {
			$this->vote->ip = $b['ip'];
			$this->vote->nextImage();
			$res = json_decode($this->vote->voteUp(),true);
			$this->assertEquals($res['voted'],'already');
		}
	}
	
}

?>