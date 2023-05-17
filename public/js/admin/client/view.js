//using ajax for view profile for client
$(document).ready(function(){
    $('.client-view-btn').on('click', function(){
        let id = $(this).data('id');
        $.ajax({
            url: $(this).data('url'),
            method: 'GET',
            success: function(data){
                console.log(data)
                $('.clientName').html(data.name);
                $('.clientEmail').html(data.email);
                $('.clientPhone').html(data.phone);
                $('.clientAddress').html(data.address);
                $('.client_profile').html(`<img src="${data.avatar}" alt="profile" class="img-fluid">`);
            }
        });
    });
})