jQuery(document).ready(
    function ($) {

        let has_mobile =  false;
        let has_mobile_login =  false;
        let has_phone =  false;
        let has_address =  false;
        if($("#mobile_register").length) has_mobile=true;
        if($("#mobile_login").length) has_mobile_login=true;
        if($("#phone_register").length) has_phone=true;
        if($("#address_register").length) has_address=true;
        //Handle Tab Control
        $("#mainTab").tabs();

        //Handle Dialog
        $( "#mainDialog" ).hide();

        $( "#mainDialog" ).dialog({
            autoOpen: false,
            show: {
                effect: "blind",
                duration: 2000
            },
            hide: {
                effect: "explode",
                duration: 1000
            },
            modal: true,
            resizable:true,
        });

        $(".close").on("click",function () {
            $( "#mainDialog" ).dialog( "close" );
        });



        //Open Dialog
        $("#wp_download_login_or_register").on("click",function () {
            $( "#mainDialog" ).dialog( "open" );
        });


        //Ajax For Register Users
        $("#register_button").click(function() {

            let name = $("#name_register").val();
            let email = $("#email_register").val();
            let mobile = (has_mobile? $("#mobile_register").val():'');
            let phone = (has_phone? $("#phone_register").val():'');
            let address = (has_address? $("#address_register").val():'');
            let password = $("#password_register").val();
            let cpassword = $("#cpassword_register").val();
            // if (name == '' || email == '' || (has_mobile && mobile == '') || (has_phone && phone == '') || (has_address && address == '') || password == '' || cpassword == '') {
            //     alert("لطفا همه فیلدها را پرکنید");
            // }  else if ((password.length) < 5) {
            //     alert("طول کلمه عبور باید حداقل 5 باشد");
            // } else if (password!=cpassword) {
            //     alert("کلمات عبور وارد شده، یکسان نیستند. دوباره تلاش کتید.");
            // }
            //  else{

                $.ajax({
                    url:'wp-admin/admin-ajax.php',
                    type:'post',
                    data:{
                        action:'wp_download_register_user',
                        name : name,
                        email: email,
                        mobile:mobile,
                        phone:phone,
                        address:address,
                        password:password,
                        cpassword:cpassword,
                        redirect:$("#wp_download_login_or_register").attr("data-value")
                    },
                    success:function (response) {
                        $("#message_board").show();
                        $("#message_board").html(response.message);
                        $("#message_board").removeClass("wp_download_error");
                        $("#message_board").addClass("wp_download_success");
                        $("#message_board").delay(3000).hide(400);

                    },
                    error:function (error) {
                        let message = "";
                        if(error.responseText == "0" && error.statusText == "Bad Request" ){
                            message = "برای انجام ثبت نام یا ورود باید از کاربر جاری خارج شوید";
                        }
                        else {
                            message = error.responseJSON.message;
                        }
                        $("#message_board").show();
                        $("#message_board").html(message);
                        $("#message_board").removeClass("wp_download_success");
                        $("#message_board").addClass("wp_download_error");
                        $("#message_board").delay(5000).hide(400);

                    }
                });

            // }

        });

        //Ajax For Login
        $("#login_button").click(function() {
            let email = $("#email_login").val();
            let mobile = (has_mobile_login? $("#mobile_login").val():'');
            let password = $("#password_login").val();

            // if (email == '' ||password == '') {
            //     alert("لطفا همه فیلدها را پرکنید");
            // } else if (!validateEmail(email)) {
            //     alert("ایمیل وارد شده، معتبر نیست");
            // }  else if ((password.length) < 8) {
            //     alert("طول کلمه عبور باید حداقل 8 باشد");
            // }
            // else {

                // alert('validate and send to server!');
                $.ajax({
                    url:'wp-admin/admin-ajax.php',
                    type:'post',
                    data:{
                        action:'wp_download_login_user',
                        email: email,
                        mobile: mobile,
                        password:password,
                        redirect:$("#wp_download_login_or_register").attr("data-value")
                    },
                    success:function (response) {
                        $("#message_board").show();
                        $("#message_board").html(response.message);
                        $("#message_board").removeClass("wp_download_error");
                        $("#message_board").addClass("wp_download_success");
                        $("#message_board").delay(3000).hide(400);
                        setTimeout(function(){ window.location.replace($("#wp_download_login_or_register").attr("data-value"));}, 1000);

                    },
                    error:function (error) {
                        let message = "";
                        if(error.responseText == "0" && error.statusText == "Bad Request" ){
                            message = "برای انجام ثبت نام یا ورود باید از کاربر جاری خارج شوید";
                        }
                        else {
                            message = error.responseJSON.message;
                        }
                        $("#message_board").show();
                        $("#message_board").html(message);
                        $("#message_board").removeClass("wp_download_success");
                        $("#message_board").addClass("wp_download_error");
                        $("#message_board").delay(5000).hide(400);

                    }
                });

            // }

        });

        //Ajax Download File
        $("#wp_download_file").click(function() {
            // alert('Downloading...')
            $.ajax({
                url:'wp-admin/admin-ajax.php',
                type:'post',
                data:{
                    action:'wp_download_file',
                    post_id:$("#wp_download_file").attr("data-in"),
                    file_name:$("#wp_download_file").attr("data-value")
                },
                success:function (response) {
                    // alert(response.message);

                },
                error:function (error) {

                }
            });

        });

    }
);