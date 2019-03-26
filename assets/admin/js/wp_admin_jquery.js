jQuery(document).ready(
    function ($) {

        $("#plugin_config_tabs").tabs();

        $("#save_wp_directory_path").on("click",function () {

            $.ajax({
                url:'admin-ajax.php',
                type:'post',
                data:{
                    action:'wp_download_save_path',
                    wp_download_path:$("#wp_directory_path").val()
                },
                success:function (response) {

                    $("#wp_download_message").show();
                    $("#wp_directory_path").val(response.wp_download_path);
                    $("#wp_download_message").html(response.message);
                    $("#wp_download_message").removeClass("wp_download_error");
                    $("#wp_download_message").addClass("wp_download_success");
                    $("#wp_download_message").delay(3000).hide(400);
                },
                error:function (error) {
                    $("#wp_download_message").show();
                    $("#wp_download_message").html(error.responseJSON.message);
                    $("#wp_download_message").removeClass("wp_download_success");
                    $("#wp_download_message").addClass("wp_download_error");
                    $("#wp_download_message").delay(3000).hide(400);

                }
            });


        });

        $("#user_list_edit_dialog").hide();
        $( "#user_list_edit_dialog" ).dialog({
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
            resizable:false,
            title:"فرم ویرایش",
            modal: true,

        });
        $("#user_list_edit_dialog_button_exit").on("click",function (event) {
            event.preventDefault();
            $( "#user_list_edit_dialog" ).dialog( "close" );


        });

        $(".wp_download_meta_edit_form").on("click",function (event) {
            event.preventDefault();
            $("#user_list_edit_dialog_text").attr("value",$(this).attr("data-value"));
            $("#user_list_edit_dialog_button_edit").attr("data-in",$(this).attr("data-in"));
            $("#user_list_edit_dialog_button_edit").attr("data-value",$(this).attr("data-out"));
            let title = "";
            if($(this).attr("data-out")=="mobile")
                title = "فرم ویرایش موبایل";
            if($(this).attr("data-out")=="phone")
                title = "فرم ویرایش تلفن";
            if($(this).attr("data-out")=="address")
                title = "فرم ویرایش آدرس";

            $( "#user_list_edit_dialog" ).dialog( "option", "title", title );
            $( "#user_list_edit_dialog" ).dialog( "open" );
        });

        $("#user_list_edit_dialog_button_edit").on("click",function () {
            let btn = $(this);
            $.ajax({
                url:'admin-ajax.php',
                type:'post',
                data:{
                    action:'user_list_edit_dialog_button_edit',
                    user_id : btn.attr('data-in'),
                    user_meta : btn.attr('data-value'),
                    user_meta_value :$("#user_list_edit_dialog_text").attr("value")
                },
                success:function (response) {
                    $("#user_list_edit_dialog" ).dialog( "close" );
                    window.location.replace("admin.php?page=wp_download_with_acl");

                },
                error:function (error) {
                    $("#user_list_edit_dialog_message").show();
                    $("#user_list_edit_dialog_message").html(error.responseJSON.message);
                    $("#user_list_edit_dialog_message").removeClass("wp_download_success");
                    $("#user_list_edit_dialog_message").addClass("wp_download_error");
                    $("#user_list_edit_dialog_message").delay(3000).hide(400);

                }
            });


        });

        $(".wp_download_user_access").on("click",function () {
            let btn = $(this);
            $.ajax({
                url:'admin-ajax.php',
                type:'post',
                data:{
                    action:'wp_download_user_access',
                    user_id:$(this).attr('data-value'),
                    has_access : $(this).attr('data-in')
                },
                success:function (response) {
                    $("#wp_download_message").show();
                    $("#wp_download_message").html(response.message);
                    btn.attr('value',response.has_access);
                    $("#wp_download_message").removeClass("wp_download_error");
                    $("#wp_download_message").addClass("wp_download_success");
                    $("#wp_download_message").delay(3000).hide(400);
                },
                error:function (error) {
                    $("#wp_download_message").show();
                    $("#wp_download_message").html(error.responseJSON.message);
                    $("#wp_download_message").removeClass("wp_download_success");
                    $("#wp_download_message").addClass("wp_download_error");
                    $("#wp_download_message").delay(3000).hide(400);

                }
            });


        });

        $("#wp_download_mobile_login").change(function() {
            if(this.checked) {
                $("#wp_download_message").show();
                $("#wp_download_message").html("برای استفاده از این قابلیت باید از بخش ثبت نام،فیلد شماره موبایل را فعال کنید");
                $("#wp_download_message").removeClass("wp_download_error");
                $("#wp_download_message").addClass("wp_download_success");
                $("#wp_download_message").delay(4000).hide(400);

            }
        });

        $("#save_wp_download_login_setting").on("click",function () {

            $.ajax({
                url:'admin-ajax.php',
                type:'post',
                data:{
                    action:'wp_download_login_setting',
                    wp_download_login_title:$("#wp_download_login_title").val(),
                    wp_download_mobile_login:$("#wp_download_mobile_login").prop("checked"),

                     },
                success:function (response) {

                    $("#wp_download_message").show();
                    $("#wp_download_message").html(response.message);
                    $("#wp_download_message").removeClass("wp_download_error");
                    $("#wp_download_message").addClass("wp_download_success");
                    $("#wp_download_message").delay(3000).hide(400);
                },
                error:function (error) {
                    $("#wp_download_message").show();
                    $("#wp_download_message").html(error.responseJSON.message);
                    $("#wp_download_message").removeClass("wp_download_success");
                    $("#wp_download_message").addClass("wp_download_error");
                    $("#wp_download_message").delay(3000).hide(400);

                }
            });


        });

        $("#save_wp_download_register_setting").on("click",function () {

            $.ajax({
                url:'admin-ajax.php',
                type:'post',
                data:{
                    action:'wp_download_register_setting',
                    wp_download_register_title:$("#wp_download_register_title").val(),
                    wp_download_mobile_register:$("#wp_download_mobile_register").prop("checked"),
                    wp_download_phone_register:$("#wp_download_phone_register").prop("checked"),
                    wp_download_address_register:$("#wp_download_address_register").prop("checked"),
                },
                success:function (response) {

                    $("#wp_download_message").show();
                    $("#wp_download_message").html(response.message);
                    $("#wp_download_message").removeClass("wp_download_error");
                    $("#wp_download_message").addClass("wp_download_success");
                    $("#wp_download_message").delay(3000).hide(400);
                },
                error:function (error) {
                    $("#wp_download_message").show();
                    $("#wp_download_message").html(error.responseJSON.message);
                    $("#wp_download_message").removeClass("wp_download_success");
                    $("#wp_download_message").addClass("wp_download_error");
                    $("#wp_download_message").delay(3000).hide(400);

                }
            });


        });
    }
);