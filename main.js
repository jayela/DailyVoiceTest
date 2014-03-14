$(function (){
	$("#mainimg").load(function(e) {
        margin();
    });

	$('#arrowleft img').click(function(e) {
		changeImage("minus");
	});
	
	$('#arrowright img').click(function(e) {
		changeImage("plus");
	});

	$('#votedown').click(function(e) {
		vote("down");
	});
	
	$('#voteup').click(function(e) {
		vote("up");
	});
});

function margin() {
	var ih = Math.round(($("#mainimg").height() - 49) / 2);
	$("#arrowleft, #arrowright").css("padding-top", ih+"px");
	$("#arrowleft, #arrowright").css("padding-bottom", ih+"px");
}

function changeImage(dir) {
	var jqxhr = $.ajax({
		url : "imagechange.php?direction="+dir+"&current="+$('#mainimg').attr("src").replace("uploads/",""),
		dataType : "json",
		success: function(msg) {
			$('#mainimg').attr("src", "uploads/"+msg['img']);
			$('#voteup').html(msg['up']+"%");
			$('#votedown').html(msg['down']+"%");
		}
	});
    jqxhr.fail(function() { alert("ERROR!"); });
}

function vote(dir) {
	var jqxhr = $.ajax({
		url : "vote.php?direction="+dir+"&current="+$('#mainimg').attr("src").replace("uploads/",""),
		dataType : "json",
		success: function(msg) {
			if (msg['voted'] == "voted") {
				$('#voteup').html(msg['up']+"%");
				$('#votedown').html(msg['down']+"%");
				setTimeout(function(){changeImage("plus");},2000);
			}
		}
	});
    jqxhr.fail(function() { alert("ERROR!"); });
}