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
