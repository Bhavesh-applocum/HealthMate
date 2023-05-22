// var NullField = $('#ClientDrop').find('option[value=""]');
// if (NullField) {
//     $('.ClientInfo').hide()
// }
// else if(NullField != ''){
//     $('.ClientInfo').show()
// }

// function showDiv() {
//     document.getElementsByClassName('.#ClientDrop').value == '' ? document.getElementsByClassName('.ClientInfo').style.display = "none" : document.getElementsByClassName('.ClientInfo').style.display = "block"; 
// }
$(document).ready(function (){
    let id = $(this).children(":selected").data('id');
    console.log(id);
    var value = $(this).children(":selected").attr('val');
    if (value == '') {
        document.getElementsByClassName('.ClientInfo').style.display == 'none';
    }else{
        document.getElementsByClassName('.ClientInfo').style.display == 'block';
    }
})
// $('#ClientDrop').change(function () {
//     var url = $(this).children(":selected").data('url');
    
//     $.ajax({
//         url: url,
//         type: 'GET',
//         success: function (data) {
//             let ClientData = data.data
//             //avatar
//             $('.client_profile').html(`<img src="${ClientData.avatar}" alt="profile" class="img-fluid">`);
//             $('.clientEmail').val(ClientData.email);
//             $('.clientPhone').val(ClientData.phone);
//             $('.clientAddress').val(ClientData.address);
//             $('.clientArea').val(ClientData.area);
//             $('.postCode').val(ClientData.post_code);
//         }
//     })
// })