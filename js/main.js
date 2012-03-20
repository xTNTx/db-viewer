$(document).ready(function(){
    // tables list scroll
    $('.container.tables ul').mCustomScrollbar({
        scrollButtons:{
            enable: true,
        }
    });
    // move active item to middle of list
    if ($('.container.tables ul span').size()) {
        var pos = $('.container.tables ul span').position().top;
        pos = pos <= 300 ? 0 : (pos - 300);
        $('.container.tables ul').mCustomScrollbar('scrollTo', pos);
    }
    // results scroll
    $('.container.results .data').mCustomScrollbar({
        horizontalScroll: true,
        scrollButtons:{
            enable: true,
        },
        mouseWheel: 'auto'
    });

    // plug to prevent menu toggling in firefox
    $('body').keydown(function(event) {
        if (event.keyCode == 18) {
            event.stopPropagation();
        }
    });

    $('input').keyup(function(e) {
        if (e.keyCode == 13) {
            $(this).parents('form').submit();
        }
    });
});
