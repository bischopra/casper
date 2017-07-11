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
                    $('.participants').append('<div class="participant">' + data.name + '</div>');
                    $('.participants .parlen').text($('.participants .participant').length);
                }
            }
        });
    });
    $('.event').hover(function(){
        $('.edit', $(this)).show();
    },function(){
        $('.edit', $(this)).hide();
    });

    $('input[name="appbundle_event[city]"],input[name="appbundle_event[address]"]').change(function(){
        var address = $('input[name="appbundle_event[city]"]').val() + ', "' + $('input[name="appbundle_event[address]"]').val() + '"';
        map.geoLocation(address);
    });

    $('.map-events .form input').change(function(){
        var address = $('.map-events .form #city').val() + ', "' + $('.map-events .form #location').val() + '"';
        map.geoLocation(address, 1);
    });
});