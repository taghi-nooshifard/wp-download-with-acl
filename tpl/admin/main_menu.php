<div class="wrap">
    <h1 style="font-family: Tahoma"> تنظیمات پلاگین دانلود با مجوز</h1>
    <h3 style="font-family: Tahoma" id="wp_download_message"></h3>

    <div id="plugin_config_tabs" class="wp_download_form">
        <ul  class="">
            <li><a href="#plugin_config-1">عمومی</a></li>
            <li><a href="#plugin_config-2">فرم ورود</a></li>
            <li><a href="#plugin_config-3">فرم ثبت نام</a></li>
        </ul>
        <div id="plugin_config-1" class="">
            <label for="wp_directory_path">مسیر ذخیره سازی فایل های داتلود شده</label>
            <input style="direction: ltr" type="text" name="wp_directory_path" id="wp_directory_path" value="<?php echo $wp_download_directory;?>">
            <button class="button-primary" id="save_wp_directory_path" name="save_wp_directory_path">دخیره مسیر</button>
            <hr>
            <h2>لیست کاربران </h2>
            <div id="user_list_edit_dialog" style="font-family: Tahoma">
<!--                Edit Dialog -->
                <h2 id="user_list_edit_dialog_message"></h2>
                <textarea class="wp-ui-text-primary" style="margin-right: 0px;margin-left: 0px;width: 100%" maxlength="1000" id="user_list_edit_dialog_text" placeholder="مقدار مورد نظر را واردکنید"></textarea>
                <hr>
                <input id="user_list_edit_dialog_button_edit"  type="button" class="button-primary" value="ویرایش">
                <input id="user_list_edit_dialog_button_exit"  type="button" class="button-primary" value="خروج">
            </div>
            <table class="widefat">
                <thead>
                <tr>
                    <th>شناسه</th>
                    <th>نام کامل</th>
                    <th>نام کاربری</th>
                    <th>ایمیل</th>
                    <th>موبایل</th>
                    <th>تلفن ثابت</th>
                    <th>آدرس</th>
                    <th>مجوز</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($userList as $user): ?>
                    <tr>
                        <th><?php echo $user->ID; ?></th>
                        <th><?php echo $user->display_name; ?></th>
                        <th><?php echo $user->user_login; ?></th>
                        <th><?php echo $user->user_email; ?></th>
                        <th>
                            <?php echo get_user_meta($user->ID,"mobile",true); ?>
                            <a href="#" class="wp_download_meta_edit_form" data-out="mobile" data-in="<?php echo $user->ID; ?>" data-value="<?php echo get_user_meta($user->ID,"mobile",true); ?>"><span class="dashicons dashicons-edit"></span></a>
                        </th>
                        <th><?php echo get_user_meta($user->ID,"phone",true); ?>
                            <a href="#" class="wp_download_meta_edit_form" data-out="phone"  data-in="<?php echo $user->ID; ?>" data-value="<?php echo get_user_meta($user->ID,"phone",true); ?>"><span class="dashicons dashicons-edit"></span></a>
                        </th>
                        <th><?php echo get_user_meta($user->ID,"address",true); ?>
                            <a href="#" class="wp_download_meta_edit_form" data-out="address" data-in="<?php echo $user->ID; ?>" data-value="<?php echo get_user_meta($user->ID,"address",true); ?>"><span class="dashicons dashicons-edit"></span></a>
                        </th>
                        <th><?php
                            $btn_title="ندارد";
                            $btn_state = false;
                            $wp_user = get_user_by('ID',$user->ID);
                            if ( $wp_user->has_cap( 'wp_download') ) {
                                $btn_title="دارد";
                                $btn_state = true;
                            }
                                ?>
                            <input type="button" class="button-primary wp_download_user_access" data-in="<?php echo $btn_state;?>" data-value="<?php echo $user->ID;?>" value="<?php echo $btn_title;?>"/>

                            </th>

                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

        </div>
        <div id="plugin_config-2" class="">
            <label for="wp_download_login_title">عنوان فرم ورود</label>
            <input value="<?php if(!empty($wp_download_login_title)){
                echo $wp_download_login_title;
            }
            else{
                echo "فرم ورود";
            }?>" type="text" id="wp_download_login_title" title="عنوان صفحه ورود">
            <hr>
            <input  type="checkbox" id="wp_download_mobile_login"  <?php
            if($wp_download_mobile_login == 'true'){
                echo "checked";
            }
            ?>>
            <label for="wp_download_mobile_login"> ورود از طریق شماره موبایل</label>

            <hr>

            <button class="button-primary" id="save_wp_download_login_setting" name="save_wp_download_login_setting">ذخیره تنظیمات فرم ورود</button>

        </div>
        <div id="plugin_config-3" class="">
            <label for="wp_download_register_title">عنوان فرم ثبت نام</label>
            <input value="<?php if(!empty($wp_download_register_title)){
                echo $wp_download_register_title;
            }
            else{
                echo "فرم ثبت نام";
            }?>"  type="text" id="wp_download_register_title" title="عنوان صفحه ثبت نام">
            <hr>
                <h2>تعیین فیلدهای فرم ثبت نام</h2>
            <br>
            <ul>
                <li>
            <input type="checkbox" id="wp_download_mobile_register" <?php
            if($wp_download_mobile_register == 'true'){
                echo "checked";
            }
            ?>>
                    <label for="wp_download_mobile_register"> شماره موبایل</label>

                </li>
                <li>
            <input type="checkbox" id="wp_download_phone_register"  <?php
            if($wp_download_phone_register == 'true'){
                echo "checked";
            }
            ?>>
                    <label for="wp_download_phone_register"> شماره ثابت</label>

                </li>
                <li>
            <input type="checkbox" id="wp_download_address_register"  <?php
            if($wp_download_address_register == 'true'){
                echo "checked";
            }
            ?>>
                    <label for="wp_download_address_register">آدرس</label>

                </li>
            </ul>
            <hr>
            <button class="button-primary" id="save_wp_download_register_setting" name="save_wp_download_register_setting">ذخیره تنظیمات فرم ثبت نام</button>

        </div>
    </div>




</div>