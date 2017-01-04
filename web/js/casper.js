$(function(){
    $('.events .event').hover(function(){
        $(this).css('background-color', '#D3D3D3');
    },function(){
        $(this).css('background-color', '');
    });
    $('.attend.button').click(function(){
        var obj = $(this);
        $.ajax({
            url: location.href,
            method: "POST",
            success: function(data) {
                if (data.status === 1)
                {
                    obj.off("click").removeClass('button').html("Already attending");
                    $('.participants').append('<div class="participant">' + data.name + '</div>')
                }
            }
        });
    });
    $('.event').hover(function(){
        $('.edit', $(this)).show();
    },function(){
        $('.edit', $(this)).hide();
    });
});