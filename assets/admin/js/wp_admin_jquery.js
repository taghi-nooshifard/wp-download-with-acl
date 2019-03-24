jQuery(document).ready(
    function ($) {

        $("#save_wp_directory_path").on("click",function () {
           //alert("save_wp_directory_path");
            if($("#wp_directory_path").val().length>0){
                $.ajax({
                    url:'admin-ajax.php',
                    type:'post',
                    data:{
                        action:'wp_download_save_path',
                        wp_download_path:$("#wp_directory_path").val()
                    },
                    success:function (response) {
                        if(response.data==null) {
                            $("#wp_directory_path").val(response.wp_download_path);
                            $("#wp_download_message").html("مسیر ذخیره شد");
                            $("#wp_download_message").removeClass("wp_download_error");
                            $("#wp_download_message").addClass("wp_download_success");
                        }
                        else{
                            $("#wp_download_message").html(response.data);
                            $("#wp_download_message").removeClass("wp_download_success");
                            $("#wp_download_message").addClass("wp_download_error");
                        }
                    },
                    error:function (error) {
                        $("#wp_download_message").html(error.data);
                        $("#wp_download_message").removeClass("wp_download_success");
                        $("#wp_download_message").addClass("wp_download_error");
                    }
                });
            }
            else {
                $("#wp_download_message").html("مسیر را وارد کنید");
                $("#wp_download_message").removeClass("wp_download_success");
                $("#wp_download_message").addClass("wp_download_error");

            }

        });
    }
);