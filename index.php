<?php
include('db.php');

$a = mysql_query('SELECT * FROM images LIMIT 1');
$img = mysql_fetch_array($a);

$up = round($img['up'] / ($img['up'] + $img['down'])*100,0);
$down = 100-$up;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="main.js"></script>
</head>

<body>
<div id="shadowcontainer">
	<div id="shadow"></div>
</div>
<div id="maincontainer">
	<div id="main">
    	<div class="question">Don't You Love This Photo?</div>
        <div class="imagecontainer"><div id="arrowleft"><img src="img/left.png" /></div><img src="uploads/<?=$img['img']?>" id="mainimg" /><div id="arrowright"><img src="img/right.png" /></div></div>
        <div class="votes">
        	<div id="voteup"><?=$up?>%</div><div id="votedown"><?=$down?>%</div>
        </div>
    </div>
</div>
</body>
</html>