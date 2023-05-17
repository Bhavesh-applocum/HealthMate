//using ajax for view profile for client
$(document).ready(function(){
    $('.candidate-view-btn').on('click', function(){
        let id = $(this).data('id');
        $.ajax({
            url: $(this).data('url'),
            method: 'GET',
            success: function(data){
                console.log(data)
                $('.candidateName').html(data.name);
                $('.candidateGender').html(data.gender);
                $('.candidateRole').html(data.role);
                $('.candidateEmail').html(data.email);
                $('.candidatePhone').html(data.phone);
                $('.candidate_profile').html(`<img src="${data.avatar}" alt="profile" class="img-fluid">`);
            }
        });
    });
})