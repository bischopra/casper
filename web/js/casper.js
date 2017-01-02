$(function(){
    $('.events .event').hover(function(){
        $(this).css('background-color', 'red');
    },function(){
        $(this).css('background-color', '');
    });
});