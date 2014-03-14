<?php

require("class-vote.php");

$_GET['current'] = htmlentities($_GET['current'], ENT_QUOTES);
$_GET['direction'] = htmlentities($_GET['direction'], ENT_QUOTES);

$vote = new vote($_GET['current'], $_SERVER["REMOTE_ADDR"]);

if ($_GET['direction'] == 'up') {
	echo $vote->voteUp();
} elseif ($_GET['direction'] == 'down') {
	echo $vote->voteDown();
}

?>