<?php

// READ HISTORY - [dist] [x][y] [r][g][b] [colorName] [time]
$dbBrut = file("history.txt",FILE_SKIP_EMPTY_LINES) ;
for ($i=0; $i < count($dbBrut); $i++) { 
	$db[$i] = explode(" ", $dbBrut[$i] ) ;
	$grid [$db[$i][1]] [$db[$i][2]] = 5; //array( "dist"=>$db[$i][0] , "r"=>$db[$i][3], "g"=>$db[$i][4], "b"=>$db[$i][5], "name"=>$db[$i][6], "time"=>$db[$i][7] );
}

// SELECT POSITION
$dist = 500;
$newArr;
for ($i=0; $i < count($dbBrut); $i++) { $newArr[$i] = $i ; }
$newArr = shuffleArray($newArr);
for ($i=0; $i < count($dbBrut); $i++) {
	$r = $newArr[$i] ;
	$empty = 0;

	// DETECT IF EMPTY NEIGHBOUG PXL
	$Xoff = array(1,-1,0,0); $Yoff = array(0,0,1,-1);
    $randOff = shuffleArray( array(0,1,2,3) );
    //echo $randOff[0]." ".$randOff[1]." ".$randOff[2]." ".$randOff[3]." - " ;
	for ($k=0; $k < 4; $k++) { 
		$off = $randOff[$k] ;
		if( !isset($grid[ $db[$r][1] + $Xoff[$off] ]
						[ $db[$r][2] + $Yoff[$off] ] ) ) { 
			$empty = $off + 1 ; 
		} 
	}
	
	if($empty != 0 && $db[$r][0] < $dist){ // le plus proche du centre avec un voisin libre
		$dist = $db[$r][0]; 
		$currentPrev = $db[$r];
		$current = array("x"   => $db[$r][1] + $Xoff[$empty-1] , 
						 "y"   => $db[$r][2] + $Yoff[$empty-1] , 
						 "dist"=> $db[$r][0]+1 );
	}
}

// READ COLOR LIST - [line] [colorName]   [ [r][g][b] ]   [ [r][g][b] ]   [ [r][g][b] ]
$colorList = file("colorList.txt");

$colorSelect;
$countC=3; $colorSelect[0]= "Rouge feu"; $colorSelect[1]= "Bitume"; $colorSelect[2]= "Fraise"; $colorSelect[3]= "Vert empire";
$lastDif = 2000;
for ($i=0; $i < count($colorList); $i++) {
	$listBrut[$i] = explode("...", $colorList[$i] ) ;

	$c['r'] = 0;	
	$c['g'] = 0;	
	$c['b'] = 0;	
	for ($j=1; $j < count($listBrut[$i]) ; $j++) {    // color average
		$c[$j] = explode(" ", $listBrut[$i][$j]) ;

		$c['r'] += $c[$j][0] ;
		$c['g'] += $c[$j][1] ;
		$c['b'] += $c[$j][2] ;
	}

	// ATTRIBUTE COLOR NAME
	$dif= abs( $currentPrev[3] - $c['r'] / ( count($listBrut[$i])-1 )  )
		+ abs( $currentPrev[4] - $c['g'] / ( count($listBrut[$i])-1 )  )
		+ abs( $currentPrev[5] - $c['b'] / ( count($listBrut[$i])-1 )  ) ;
	
	if( $dif < $lastDif ) { 
		$lastDif = $dif ;
		$colorPrevName = $listBrut[$i][0] ;
		$id = $i ;

		$countC++;
		$colorSelect[$countC] = $listBrut[$i][0] ;
	}
}

function shuffleArray($array) {
    for ($i = count($array) - 1; $i > 0; $i--) {
        $j = mt_rand( 0, $i );
        $temp = $array[$i];
        $array[$i] = $array[$j];
        $array[$j] = $temp;
    }
    return $array;
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//FR" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html><head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="stylesheet" href="scripts/global.css" />
	<link rel="stylesheet" href="scripts/minicolors/jquery.minicolors.css" />
	<title>Mécanique colorée</title>
</head>
<body>
	<h1>Mécanique colorée</h1>
	<form>
		<div id="instruction">
Participez à l’élaboration d’une image collective <br>
par une action minimale. Donnez votre vision <br>
d'un nom de couleur correspondant au pixel voisin :
		</div>	
		<h2 id="colorName"> </h2>
	 	<input id="inlinecolors" value="#0088cc" input="inline" /> <br>
	 	<a id="post"> Valider </a>
	</form>

	<script src="scripts/jquery-2.1.0.min.js" type="text/javascript"></script>
	<script src="scripts/minicolors/jquery.minicolors.min.js" type="text/javascript"></script>
	<script type="text/javascript">
$( document ).ready(function() {       
	$("#inlinecolors").attr({"value":"#"+Math.floor(Math.random()*99)+Math.floor(Math.random()*99)+Math.floor(Math.random()*99) });

   $('INPUT[type=minicolors]').minicolors();

    //rgb = $('INPUT[type=minicolors]').minicolors('rgbObject');
    $('#inlinecolors').minicolors({
        inline: true,
        theme: 'bootstrap',
        change: function(hex) {
            if(!hex) return;
            $('BODY').css('backgroundColor', hex);
            $('a').attr('href',"img.php?r=" + hexToRgb(hex).r +
                                    "&g=" + hexToRgb(hex).g +
                                    "&b=" + hexToRgb(hex).b +
            <?php echo '"&x='.$current['x'].'&y='.$current['y'].'&dist='.$current['dist'].'&id='.$id.'"' ; ?>
            );
        }
    });

    //roulette computing color
    function displayName(name) {
    	$("#colorName").html(name+"");
    }
    <?php 
    foreach ($colorSelect as $key => $value) {
    	$value = '"'.$value.'"';
    	echo 'setTimeout( function(){ $("#colorName").html(' ;
    	echo str_replace("_", " ", $value) ;
    	echo '); }, ' ;
		echo $key*150 ;
		echo ");\n" ;  
    }
    ?>
    setTimeout( function(){ $("#colorName").css({'color':'white'}); }, <?php echo( (count($colorSelect)-1) * 150 );?> );

    // TO DO onclique(hue) : a=visible
    $.minicolors.defaults = $.extend($.minicolors.defaults, {
         animationSpeed: 0,
            animationEasing: 'swing',
            change: null,
            changeDelay: 0,
            control: 'hue',
            defaultValue: '',
            hide: null,
            hideSpeed: 0,
            inline: false,
            letterCase: 'uppercase',
            opacity: false,
            position: 'bottom right',
            show: true,
            showSpeed: 100,
            theme: 'default'
   });

    function hexToRgb(hex) {
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    }
});

	</script>
</body></html>