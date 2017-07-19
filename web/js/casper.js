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
                if (data.status === 1){
                    obj.off("click").removeClass('button').html("Already attending");
                    $('.participants').append('<div class="participant">' + data.name + '</div>');
                    $('.participants .parlen').text($('.participants .participant').length);
                }
            },
            error: function(){
                alert('Your request could not be completed. Contact administrator.');
            }
        });
    });
    $('.invitaions.button').click(function(){
        var obj = $(this);
        $.ajax({
            url: '/invitations/send/' + obj.attr('data-id'),
            method: "GET",
            success: function(data) {
                if (data.status === 1) {
                    window.location.reload();
                }
            },
            error: function(){
                alert('Your request could not be completed. Contact administrator.');
            }
        });
    });
    $('.event').hover(function(){
        $('.edit', $(this)).show();
    },function(){
        $('.edit', $(this)).hide();
    });
    $('.participant').hover(function(){
        $('.remove', $(this)).show();
    },function(){
        $('.remove', $(this)).hide();
    });
    $('.participant .remove').click(function(){
        if(confirm("Are you sure you want to remove this user from event?")){
            var obj = $(this).closest('.participant');
            $.ajax({
                url: location.href,
                method: "POST",
                data: 'action=remove&uid=' + $(obj).attr('data-pid'),
                success: function(data) {
                    if (data.status === 1){
                        $(obj).animate({
                            height: "toggle"
                        }, 'normal', function(){
                            $(this).remove();
                            $('.participants .parlen').text($('.participants .participant').length);
                        });
                    }
                },
                error: function(){
                    alert('Your request could not be completed. Contact administrator.');
                }
            });
        }
    });
    $('.notify.button').click(function(){
        var obj = $(this);
        var lat = $(this).attr('data-lat');
        var lng = $(this).attr('data-lng');
        $.ajax({
            url: location.href,
            method: "POST",
            data: 'lat=' + lat + '&lng=' + lng,
            success: function(data) {
                if (data.status === 1) {
                    obj.addClass('green');
                }
            },
            error: function(){
                alert('Your request could not be completed. Contact administrator.');
            }
        });
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