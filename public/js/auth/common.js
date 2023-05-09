$(document).ready(function() {
    $(document).on('change','#showPassword',function(){
        let password = $(this).closest('.input-group').find('[name="password"]');
        if($(this).is(':checked')){
            password.attr('type','text');
            $(this).next().attr('src','/images/eye-slash.svg')
        }else{
            password.attr('type','password');
            $(this).next().attr('src','/images/eye.svg')
        }
    })
   
})