$(document).ready(function() {
    document.getElementById("news").style.backgroundColor = "#FFF";
    $('.new').hide();
    $('.news').show();
    $('.bar').click(function() {
        $('.new').hide();
        previ = this.getAttribute('name');
        $('.' + previ).show();
        
        document.getElementById("news").style.background = "none";
        document.getElementById("aande").style.background = "none";
        document.getElementById("sports").style.background = "none";
        document.getElementById("opinions").style.background = "none";

        document.getElementById(previ).style.backgroundColor = "#FFF";

    });
});     

/*
 *
 *$(document).ready(function() {
    $('#all').attr('checked',true);
    $('[type=checkbox]').click(function() {
        var $val = $(this).attr('value');
        if ($val == 'all') {
            $('.new').show();
            $('.box').attr('checked',false);
        }
        if (!($val == 'all')) {
            $('#all').attr('checked', false);
            $('.new').hide();
            var values = $('input:checkbox:checked').map(function () {
                return this.value;
            }).get();
            for(var i = 0; i < values.length; i+=1) {
                var val = values[i];
                $('#' + val).show();
            }
        }
    });
}); 
*/