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
// $(document).ready(function (){
//     let id = $(this).children(":selected").data('id');
//     console.log(id);
//     var value = $(this).children(":selected").attr('val');
//     if (value == '') {
//         document.getElementsByClassName('ClientInfo').style.display == 'none';
//     }else{
//         document.getElementsByClassName('ClientInfo').style.display == 'block';
//     }
// })

$('#ClientDrop').change(function () {
    // let id = $(this).children(":selected").data('id');

    var value = $(this).children(":selected").attr('val');
    if (value == '') {
        $('.ClientInfo').hide()
    }else{
        $('.ClientInfo').show()
    }
})

$("#addressForCreate").change(function (){
    // get url 
    var url = $(this).children(":selected").data('url');
    // ajax call
    $.ajax({
        // id
        url: url,
        method: 'GET',
        success: function (data){
            let addressData = data.data
            $(".clientArea").val( addressData.area);
            $(".postCode").val(addressData.post_code);
        }
    })
});


$('#ClientDrop').change(function () {
    var url = $(this).children(":selected").data('url');
    
    $.ajax({
        url: url,
        type: 'GET',
        success: function (data) {
            let ClientData = data.data
            $(".client_profile").attr('src',ClientData.client_avatar?"/"+ClientData.client_avatar:'/images/user.svg');
            $('.clientEmail').val(ClientData.client_email);
            $('.clientPhone').val(ClientData.client_phone);
            $('.clientAddress').empty()
            ClientData.AllAddress.forEach((ad)=>{
                let opt =  $(`<option value="${ad.id}" data-url="${ad.url}">${ad.address}</option>`)
                $('.clientAddress').append(opt)
            })
            $('.clientAddress').val(ClientData.client_address_id).change();
            $('.clientArea').val(ClientData.client_area);
            $('.postCode').val(ClientData.client_post_code);
        }
    })
})