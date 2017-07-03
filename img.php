<?php

// READ HISTORY - [dist] [x][y] [r][g][b] [colorName] [time]
$colorList = file("colorList.txt");
$dbBrut = file("history.txt") ;
for ($i=0; $i < count($dbBrut); $i++) { 
	$db[$i] = explode(" ", $dbBrut[$i] ) ;
}

// 	ADD TO HISTORY
if ( empty($_GET['r']) == FALSE ) {
	$name = explode("...",$colorList[$_GET['id']]) [0] ;
	$file = fopen ("history.txt", "a");
	fwrite($file, "\r\n".$_GET['dist']." ".$_GET['x']." ".$_GET['y']." ".$_GET['r']." ".$_GET['g']." ".$_GET['b']." ".$name." ".time() ); fclose ($file);


	// add to DATA BASE
	$colorList[ intval($_GET['id']) ] = str_replace("\n", "", $colorList[ intval($_GET['id']) ] ) ;
	$colorList[ intval($_GET['id']) ] .= "...".$_GET['r']." ".$_GET['g']." ".$_GET['b']."\r\n"	;
	
	$f = fopen ("colorList.txt", "r+"); 
	ftruncate($f, filesize("colorList.txt") );
	rewind($f);
	fwrite($f, implode("",$colorList) ); 
	fclose ($f);
}	

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//FR" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html><head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="stylesheet" href="scripts/global.css" />
</head>
<body>
	<a href="docu/"> Documentation / Recherche </a>
	<a href="database.php"> Consulter la base de données </a>
	<a href="index.php"> Entrer un nouveau pixel </a>
	<?php 
		$ppp = 10;
		for ($i = 0; $i < count($db); $i++) {
			$x = $db[$i][1]*$ppp-250*$ppp;
			$y = $db[$i][2]*$ppp-250*$ppp;
			echo "<div class='pixel' style='"
				."width:".$ppp."px;"
				."height:".$ppp."px;"
				."left:".$x."px;"
				."top:" .$y."px;"
				."background-color:rgb(".$db[$i][3].",".$db[$i][4].",".$db[$i][5].");"
			."'></div>"
			."<div class='name' style='"
				."left:".($x-30)."px;"
				."top:" .($y-144)."px;"
			."'>".str_replace("_", " ", $db[$i][6] )."</div>" ;
		}
		if ($_GET){
			echo "<div id='pixel' style='index:1;background-color:rgb(".$_GET['r'].",".$_GET['g'].",".$_GET['b'].")' ></div>";
		}
	?>
	<script src="scripts/jquery-2.1.0.min.js" type="text/javascript"  charset="utf-8"></script>
	<script src="scripts/easing.js" type="text/javascript"  charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
	$(document).ready(function(){
		$(".pixel, .name").css({
		    left: function( index, value ) {return parseFloat( value ) + window.innerWidth/2; },
		    top: function( index, value ) { return parseFloat( value ) + window.innerHeight/2; }
		});
		$(".pixel").hover( function() {
			$( this ).next().css("display","inline-block");
		}, function() {
			$( this ).next().css("display","none");
		});

		$("#pixel").animate({
		    <?php if($_GET){ echo "left: (".($_GET['x']*$ppp-250*$ppp)." + window.innerWidth/2)+'px',"
		    					 ."top:  (".($_GET['y']*$ppp-250*$ppp)." + window.innerHeight/2)+'px',"; } ?> 
		    width: <?php echo $ppp+"'px'" ?>,
		    height:<?php echo $ppp+"'px'" ?>
		}, 1000,"easeOutQuint", function() { });
	
	});
	</script>	

</body></html>