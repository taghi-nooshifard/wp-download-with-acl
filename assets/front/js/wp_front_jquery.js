jQuery(document).ready(
    function ($) {

        //Handle Tab Control
        $( "#mainDialog" ).hide();
        $('.tab_content').hide();
        $('.tab_content:first').show();
        $('.tabs li:first').addClass('active');
        $('.tabs li').click(function(event) {
            $('.tabs li').removeClass('active');
            $(this).addClass('active');
            $('.tab_content').hide();
            var selectTab = $(this).find('a').attr("href");
            $(selectTab).fadeIn();
        });

        //Handle Dialog
        $(".close").on("click",function () {
            $( "#mainDialog" ).dialog( "close" );
        });
        $("#wp_download_login_or_register").on("click",function () {
             //alert("wp_download_login_or_register");
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

            });
            $( "#mainDialog" ).dialog( "open" );
        });

        // Function that validates email address through a regular expression.
        function validateEmail($email) {
            var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            return emailReg.test( $email );
        }

        //Ajax For Register Users
        $("#register_button").click(function() {
            var name = $("#name_register").val();
            var email = $("#email_register").val();
            var mobile = $("#mobile_register").val();
            var password = $("#password_register").val();
            var cpassword = $("#cpassword_register").val();
            if (name == '' || email == '' || mobile == '' || password == '' || cpassword == '') {
                alert("لطفا همه فیلدها را پرکنید");
            } else if (!validateEmail(email)) {
                alert("ایمیل وارد شده، معتبر نیست");
            }  else if ((password.length) < 8) {
                alert("طول کلمه عبور باید حداقل 8 باشد");
            } else if (password!=cpassword) {
                alert("کلمات عبور وارد شده، یکسان نیستند. دوباره تلاش کتید.");
            }
                else {

                // alert('validate and send to server!');
                $.ajax({
                    url:'wp-admin/admin-ajax.php',
                    type:'post',
                    data:{
                        action:'wp_download_register_user',
                        name : name,
                        email: email,
                        mobile:mobile,
                        password:password,
                        redirect:$("#wp_download_login_or_register").attr("data-value")
                    },
                    success:function (response) {
                        alert(response.message);
                        if(response.success){
                            $('.tabs li').removeClass('active');
                            $('#login_tab').addClass('active');
                            $('.tab_content').hide();
                            var selectTab = $('#login_tab').find('a').attr("href");
                            $(selectTab).fadeIn();
                        }
                    },
                    error:function (error) {

                    }
                });

            }

        });

        //Ajax For Login
        $("#login_button").click(function() {
            var email = $("#email_login").val();
            var password = $("#password_login").val();

            if (email == '' ||password == '') {
                alert("لطفا همه فیلدها را پرکنید");
            } else if (!validateEmail(email)) {
                alert("ایمیل وارد شده، معتبر نیست");
            }  else if ((password.length) < 8) {
                alert("طول کلمه عبور باید حداقل 8 باشد");
            }
            else {

                // alert('validate and send to server!');
                $.ajax({
                    url:'wp-admin/admin-ajax.php',
                    type:'post',
                    data:{
                        action:'wp_download_login_user',
                        email: email,
                        password:password,
                        redirect:$("#wp_download_login_or_register").attr("data-value")
                    },
                    success:function (response) {
                        alert(response.message);
                        window.location.replace($("#wp_download_login_or_register").attr("data-value"));
                    },
                    error:function (error) {

                    }
                });

            }

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
                    alert(response.message);

                },
                error:function (error) {

                }
            });

        });

    }
);