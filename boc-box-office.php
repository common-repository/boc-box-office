<?php
/*
Plugin Name: Box Office CinÃ©ma
Plugin URI: http://wordpress.org/plugins/boc-box-office/
Description: Ce widget permet d'afficher le top 10 du box office cinÃ©ma en france sur votre blog
Version: 1.0
Author: fandecine
Author URI: http://www.boxofficecine.fr/
Released under the GNU General Public License (GPL)
http://www.gnu.org/licenses/gpl.txt
*/

error_reporting(0);

class boc_widget_boxoffice extends WP_Widget {
    
    function boc_widget_boxoffice() {
        $widget_ops2 = array('classname' => 'boc_widget_boxoffice', 'description' => 'Le top 10 du box office cinÃ©ma de la semaine');
        $this->WP_Widget('boc_widget_boxoffice', 'BOC Box Office', $widget_ops2);
    }

    function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = strip_tags( $new_instance['title'] ); 
        return $instance;
    }
 
    function form( $instance ) {
        if ( isset( $instance['title'] ) ) {$title = $instance['title'];} else {$title = "Mon Titre";}
         ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Titre :</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <?php
    }
    function widget($args, $instance) {
        extract( $args );
        $title  = apply_filters('widget_title', $instance['title']);
        $urlparts = parse_url(home_url('/'));
        $ch = curl_init('http://www.boxofficecine.fr/datas/box-office.php'); 
        $post_prm = array('d'=> $urlparts[host]);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_FRESH_CONNECT, true );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $post_prm );
        $result = str_replace(chr(hexdec("EF")).chr(hexdec("BB")).chr(hexdec("BF")),"",curl_exec($ch));
        curl_close($ch);
        echo $before_widget;
        if (!empty($title)) echo $before_title.$title.$after_title;
        echo $result.$after_widget;
    }
}

add_action( 'widgets_init', create_function('', 'return register_widget("boc_widget_boxoffice");') );

?>