<?php

class AltMedia_Config{

    public function __construct(){

        add_action( 'admin_bar_init', array($this, 'altmedia_admin_bar_init') );
    }

    public function altmedia_admin_bar_init(  ){
        if ( ! current_user_can( 'edit_posts' ) ) {
                    return;
                }
        wp_enqueue_script('altmedia-admin', plugins_url( 'js/altmedia-admin.js', __FILE__ ), array('jquery'), false, true);
        wp_enqueue_style('altmedia-admin', plugins_url( 'css/altmedia-admin.css', __FILE__ ));
        add_action( 'get_footer', array($this, 'altmedia_frontend_admin_add_edit_panel') );
    }

    public function altmedia_frontend_admin_add_edit_panel(){

        if ( current_user_can( 'edit_posts' ) && !is_admin() && is_single() ) :
            global $post;
            $url = str_replace(get_bloginfo('url'), get_blog_option(get_current_blog_id(), 'Feed App Url'), get_permalink(get_the_ID())); ?>

            <div class="altmedia-fa-side-panel">
                <div class="post-edit-wrap" id="<?php echo $post->ID; ?>">
                    <textarea style="position:absolute; left:-9000px;" id="feedUrl"><?php echo $url; ?></textarea>

                    <div class="url-buttons">
                        <button class="btn one">
                            <a style="color:#fff !important;" target="_blank" href="<?php echo $url; ?>">View Post</a>
                        </button>
                        <button id="cpclip" class="btn">Copy URL</button>
                        <p class="cc-message"></p>
                        <div class="clear"></div>
                    </div>

                    <div class="explicit">
                        <h3>Syndication</h3>
                        <?php
                            $explicit_field = get_field_object("explicit", get_the_ID());
                            $explicit_type_field = get_field_object("explicit_type", get_the_ID());
                            $oem_field = get_field_object("oem_safe", get_the_ID());

                            $checked = '';
                            if($explicit_field['value'] !== ""){
                                $checked = 'checked="checked" ';
                            }
                            echo '<p style="margin-top:1em; margin-bottom:.25em; font-size:1.1em;">Explicit:</p>';
                        ?>
                            <p><input <?php echo $checked; ?>class="explicit-cb" type="<?php echo $explicit_field['type']; ?>" name="<?php echo $explicit_field['key']; ?>" value="<?php echo $explicit_field['value'][0]; ?>" /><label> <?php echo $explicit_field['label']; ?></label></p>
                        <?php

                        if($explicit_type_field):
                            echo '<p style="margin-top:1em; margin-bottom:.25em; font-size:1.1em;">Explicit Type:</p>';
                            foreach($explicit_type_field['choices'] as $choice):
                            if(gettype($explicit_type_field['value']) == "array"){
                                $checked = in_array($choice, $explicit_type_field['value']) ? 'checked="checked"' : '';
                            }else{
                                $checked = '';
                            }
                        ?>
                            <p style="margin-bottom:0;"><input <?php echo $checked; ?>class="explicit-type-cb" type="<?php echo $explicit_type_field['type']; ?>" name="<?php echo $explicit_type_field['key']; ?>[]" value="<?php echo $choice; ?>" /><label> <?php echo $choice; ?></label></p>

                        <?php endforeach; endif; ?>

                        <?php
                            $checked = '';
                            if($oem_field['value'] !== ""){
                                $checked = 'checked="checked" ';
                            }
                            if($oem_field):
                            echo '<p style="margin-top:1em; margin-bottom:.25em; font-size:1.1em;">OEM Safe:</p>';
                        ?>
                            <p><input <?php echo $checked; ?>class="oem-safe-cb" type="<?php echo $oem_field['type']; ?>" name="<?php echo $oem_field['key']; ?>" value="<?php echo $oem_field['value'][0]; ?>" /><label> <?php echo $oem_field['label']; ?></label></p>
                            <?php endif; ?>
                    </div>

                    <div class="altmedia-tagcloud">
                        <h3>Tags</h3>
                        <?php the_tags('', ' '); ?>
                    </div>
                </div>
            </div>

        <?php endif;
    }

}

