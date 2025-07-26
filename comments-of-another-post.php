<?php
/**
 * Plugin Name: Comments of Another Post
 * Description: Provides a shortcode [comments_of post_id=123] to embed comments from another post or page.
 * Version: 1.0
 * Author: ChatGPT and me
 */

function coa_render_comments($atts) {
    $atts = shortcode_atts([
        'post_id' => 0,
        'show_form' => false,
    ], $atts);

    $post_id = intval($atts['post_id']);
    if (!$post_id || get_post_status($post_id) !== 'publish') {
        return '<p>Invalid or unpublished post ID.</p>';
    }

    // Setup global $post
    global $post;
    $old_post = $post;
    $post = get_post($post_id);
    setup_postdata($post);

    // Start output buffering
    ob_start();

    // Output comments
    if (comments_open($post_id) || get_comments_number($post_id)) {
        comments_template('/comments.php', true);
    } else {
        echo '<p>No comments yet.</p>';
    }

    // Optional form
    if ($atts['show_form'] && comments_open($post_id)) {
        comment_form(['comment_notes_after' => ''], $post_id);
    }

    // Reset global $post
    wp_reset_postdata();
    $post = $old_post;

    return ob_get_clean();
}
add_shortcode('comments_of', 'coa_render_comments');
