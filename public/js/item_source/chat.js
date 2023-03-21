$("#message").keypress(function(event) {
    if (event.which == '13') {
        $('#btnChat').click();
    }
});

//Chat
$('#btnChat').click(function(event) {
    event.preventDefault();
    var header_id = $('#headerID').val();
    var message = $('#message').val();
    $.ajax({
        url: "{{ route('save-message') }}",
        type: "POST",
        dataType: 'json',

        data: {
            "_token": token,
            "header_id" : header_id,
            "message": message,
        },
        success: function (data) {
            if (data.status == "success") {
                $('.body-comment').append('<span class="session-comment"> ' +
                                    '<p><span class="comment">'+data.message.comments +'</span> </p>'+
                                    '<p style="text-align:right; font-size:12px; font-style: italic; padding-right:5px;"> '+ new Date(data.message.created_at) +'</p></span>');
                $('#message').val('');
            }
            var interval = setInterval(function() {
            $('.chat').scrollTop($('.chat')[0].scrollHeight);
            },200);
        }
    }); 
});