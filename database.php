<?php

// READ HISTORY - [dist] [x][y] [r][g][b] [colorName] [time]
$dbBrut = file("history.txt",FILE_SKIP_EMPTY_LINES) ;
for ($i=0; $i < count($dbBrut); $i++) { 
	$db[$i] = explode(" ", $dbBrut[$i] ) ;
	$grid [$db[$i][1]] [$db[$i][2]] = 5; //array( "dist"=>$db[$i][0] , "r"=>$db[$i][3], "g"=>$db[$i][4], "b"=>$db[$i][5], "name"=>$db[$i][6], "time"=>$db[$i][7] );
}

// READ COLOR LIST - [line] [colorName]   [ [r][g][b] ]   [ [r][g][b] ]   [ [r][g][b] ]
$colorList = file("colorList.txt");

$lastDif = 2000;
for ($i=0; $i < count($colorList); $i++) {
	$listBrut[$i] = explode("...", $colorList[$i] ) ;
	$color[$i]['name'] = $listBrut[$i][0];

	$color[$i]['r'] = $color[$i]['g'] = $color[$i]['b'] = 0;	
	for ($j=1; $j < count($listBrut[$i]) ; $j++) {    // color average
		$color[$i][$j] = explode(" ", $listBrut[$i][$j]) ;

		$color[$i]['r'] += $color[$i][$j][0] ;
		$color[$i]['g'] += $color[$i][$j][1] ;
		$color[$i]['b'] += $color[$i][$j][2] ;
	}
	// average
	$color[$i]['r'] = intval( $color[$i]['r'] / ( count($listBrut[$i])-1 ) );
	$color[$i]['g'] = intval( $color[$i]['g'] / ( count($listBrut[$i])-1 ) );
	$color[$i]['b'] = intval( $color[$i]['b'] / ( count($listBrut[$i])-1 ) );
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//FR" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html><head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="stylesheet" href="scripts/global.css" />
	<link rel="stylesheet" href="scripts/minicolors/jquery.minicolors.css" />
    <title>Mécanique colorée</title>
</head>
<body>
    <a href="img.php" style="margin-left:0">retour</a><br><br>
	<?php

for ($i=0; $i < count($colorList); $i++) {
	echo "<div class='colorBlock' style='background:rgb(".$color[$i]['r'].",".$color[$i]['g'].",".$color[$i]['b'].")'></div>" ;
	echo "<div class='nameBlock'>".$color[$i]['name']."</div>" ;

	for ($j=1; $j < count($listBrut[$i]) ; $j++) { 
		echo "<div class='colorBlock' style='background:rgb(".$color[$i][$j]['0'].",".$color[$i][$j]['1'].",".$color[$i][$j]['2'].")'></div>" ;
	}
	echo "<br>" ;
}

	?>
	<script src="scripts/jquery-2.1.0.min.js" type="text/javascript"></script>
	<script src="scripts/minicolors/jquery.minicolors.min.js" type="text/javascript"></script>
	<script type="text/javascript">
$( document ).ready(function() {          
   $('INPUT[type=minicolors]').minicolors();

    //rgb = $('INPUT[type=minicolors]').minicolors('rgbObject');
    $('#inlinecolors').minicolors({
        inline: true,
        theme: 'bootstrap',
        change: function(hex) {
            if(!hex) return;
            $('BODY').css('backgroundColor', hex);
            $('a').attr('href',"img1.php?r=" + hexToRgb(hex).r +
                                    "&g=" + hexToRgb(hex).g +
                                    "&b=" + hexToRgb(hex).b +
            <?php echo '"&x='.$current['x'].'&y='.$current['y'].'&dist='.$current['dist'].'&id='.$id.'"' ; ?>
            );
        }
    });

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
            inline: true,
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
    <br><br>
    <a href="img.php">retour</a>
</body></html>