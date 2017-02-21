<?php
/**
 * @package WordPress
 * @subpackage Kleo
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since Kleo 1.0
 */

/**
 * Kleo Child Theme Functions
 * Add custom code below
*/

add_action( 'after_setup_theme', 'wcamp_sensei', 10 );

function wcamp_sensei() {
	remove_action( 'sensei_single_course_modules_before', array( Sensei()->modules, 'course_modules_title' ), 20 );
}

function kleo_comment_form( $args = array(), $post_id = null ) {
    global $id;

    $user = wp_get_current_user();
    $user_identity = $user->exists() ? $user->display_name : '';

    if ( null === $post_id ) {
        $post_id = $id;
    }
    else {
        $id = $post_id;
    }

    if ( comments_open( $post_id ) ) :
        ?>
        <div id="respond-wrap">
            <?php
            $commenter = wp_get_current_commenter();
            $req = get_option( 'require_name_email' );
            $aria_req = ( $req ? " aria-required='true'" : '' );
            $fields =  array(
                'author' => '<div class="row"><p class="comment-form-author col-sm-4"><label for="author">' . __( 'Name', 'kleo_framework' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) . '<input id="author" name="author" type="text" class="form-control" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>',
                'email' => '<p class="comment-form-email col-sm-4"><label for="email">' . __( 'Email', 'kleo_framework' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) . '<input id="email" name="email" type="text" class="form-control" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>',
                'url' => '<p class="comment-form-url col-sm-4"><label for="url">' . __( 'Website', 'kleo_framework' ) . '</label><input id="url" name="url" type="text" class="form-control" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p></div>'
            );

            if (function_exists('bp_is_active')) {
                $profile_link = bp_get_loggedin_user_link();
            }
            else {
                $profile_link = admin_url( 'profile.php' );
            }

            $comments_args = array(
                'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
                'logged_in_as'		   => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'kleo_framework' ), $profile_link, $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>',
                'title_reply'          => __( 'Share Your Thoughts', 'kleo_framework' ),
                'title_reply_to'       => __( 'Leave a reply to %s', 'kleo_framework' ),
                'cancel_reply_link'    => __( 'Click here to cancel the reply', 'kleo_framework' ),
                'label_submit'         => __( 'Post comment', 'kleo_framework' ),
                'comment_field'		   => '<p class="comment-form-comment"><label for="comment">' . __( 'Comment', 'kleo_framework' ) . '</label><textarea class="form-control" id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
                'must_log_in'		   => '<p class="must-log-in">' .  sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.', 'kleo_framework' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>',
            );

            comment_form($comments_args);
            ?>
        </div>

    <?php
    endif;
}

// Add submenus to Orders tab
add_action( 'bp_setup_nav', 'mtollwc_kleo_woo_order_submenus' , 302 );
function mtollwc_kleo_woo_order_submenus() {
	$bp = buddypress();
	if(version_compare( BP_VERSION, '2.6', '>=' )) {
		$url = $bp->members->nav->orders->slug;
	} else {
		$url = $bp->bp_nav['orders']['slug'];
	}


	bp_core_new_subnav_item(
		array(
			'name' => __('My Subscriptions', 'kleo_framework'),
			'slug' => 'my-subscriptions',
			'parent_url' => $bp->loggedin_user->domain  . $url . '/',
			'parent_slug' => $url,
			'position' => 15,
			'show_for_displayed_user' => false,
			'screen_function' => 'mtollwc_kleo_woo_subscriptions_screen',
		));

	bp_core_new_subnav_item(
		array(
			'name' => __('Payment Methods', 'kleo_framework'),
			'slug' => 'payment-methods',
			'parent_url' => $bp->loggedin_user->domain  . $url . '/',
			'parent_slug' => $url,
			'position' => 40,
			'show_for_displayed_user' => false,
			'screen_function' => 'mtollwc_kleo_woo_pay_methods_screen'
		));
}

// Load My Subscriptions template
function mtollwc_kleo_woo_subscriptions_screen() {
	add_action( 'bp_template_content', 'mtollwc_kleo_woo_subscriptions_screen_content' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

// Display My Subscriptions screen
function mtollwc_kleo_woo_subscriptions_screen_content() {
	echo '<div class="woocommerce">';
//	wc_get_template( 'myaccount/my-orders.php' );
	echo '</div>';
}

// Load Payment Methods template
function mtollwc_kleo_woo_pay_methods_screen() {
	add_action( 'bp_template_content', 'mtollwc_kleo_woo_pay_methods_screen_content' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

// Display Payment Methods screen
function mtollwc_kleo_woo_pay_methods_screen_content() {
	echo '<div class="woocommerce">';
	wc_get_template( 'myaccount/payment-methods.php' );
	echo '</div>';
}
