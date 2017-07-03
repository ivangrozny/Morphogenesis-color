$( document ).ready(function() {          
   $('INPUT[type=minicolors]').minicolors();

    //rgb = $('INPUT[type=minicolors]').minicolors('rgbObject');

    $('#inlinecolors').minicolors({
        inline: true,
        theme: 'bootstrap',
        change: function(hex) {
            if(!hex) return;
            $('BODY').css('backgroundColor', hex);
            $('a').attr('href',"index.php?r=" + hexToRgb(hex).r +
                                    "&g=" + hexToRgb(hex).g +
                                    "&b=" + hexToRgb(hex).b );
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
