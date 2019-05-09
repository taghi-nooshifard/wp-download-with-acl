jQuery(document).ready(
    function ($) {
        console.log('RUN');


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
        $(".wp_download_login_or_register").on("click",function () {
            $( "#mainDialog" ).data('d_caller',$(".wp_download_login_or_register")).dialog( "open" );
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
            let d_caller = $( "#mainDialog" ).data('d_caller');
                $.ajax({
                    url:d_caller.attr("data-url")+'/wp-admin/admin-ajax.php',
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
                        redirect:d_caller.attr("data-value")
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
            let d_caller = $( "#mainDialog" ).data('d_caller');
                $.ajax({
                    url:d_caller.attr("data-url")+'/wp-admin/admin-ajax.php',
                    type:'post',
                    data:{
                        action:'wp_download_login_user',
                        email: email,
                        mobile: mobile,
                        password:password,
                        redirect:d_caller.attr("data-value")
                    },
                    success:function (response) {
                        $("#message_board").show();
                        $("#message_board").html(response.message);
                        $("#message_board").removeClass("wp_download_error");
                        $("#message_board").addClass("wp_download_success");
                        $("#message_board").delay(3000).hide(400);
                        setTimeout(function(){ window.location.replace(d_caller.attr("data-value"));}, 1000);

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
        $(".wp_download_file").click(function() {
            // alert('Downloading...')
            let file_name = $(this).attr("data-value");
            $.ajax({
                url:$(this).attr("data-url")+'/wp-admin/admin-ajax.php',
                type:'post',
                data:{
                    action:'wp_download_file',
                    post_id:$(this).attr("data-in"),
                    file_name:$(this).attr("data-value")
                },
                success:function (response) {
                    // alert(response.message);
                     console.log(response);


                    // Create a new Blob object using the
                    //response data of the onload object
                    let blob = new Blob([response], {type: 'application/octet-stream'});
                    //Create a link element, hide it, direct
                    //it towards the blob, and then 'click' it programatically
                    let a = document.createElement("a");
                    a.style = "display: none";
                    document.body.appendChild(a);
                    //Create a DOMString representing the blob
                    //and point the link element towards it
                    let url = createObjectURL(blob);
                    a.href = url;
                    console.log(file_name);
                    a.download = file_name;
                    //programatically click the link to trigger the download
                    a.click();
                    //release the reference to the file by revoking the Object URL

                },
                error:function (error) {

                }
            });

        });

        function createObjectURL ( file ) {
            if ( window.webkitURL ) {
                return window.webkitURL.createObjectURL( file );
            } else if ( window.URL && window.URL.createObjectURL ) {
                return window.URL.createObjectURL( file );
            } else {
                return null;
            }
        }
    }
);