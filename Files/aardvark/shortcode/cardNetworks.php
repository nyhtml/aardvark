<?php
/*
Plugin Name: Sipylus CardNetworks Shortcode
Description: Provides [cardNetworks] shortcode functionality with inline CSS and responsive mobile support.
Version: 2025.07.13.A
Author: <a href="http://www.stephanpringle.com">Stephan Pringle</a>
*/

if (!defined('ABSPATH')) exit; // Prevent direct access

// Helper function to format social URLs
function format_social_link($value, $base) {
    if (!$value) return null;
    // If already a full URL starting with http, return as is; else prepend base URL
    return (strpos($value, 'http') === 0) ? $value : $base . ltrim($value, '@');
}

// SHORTCODE: [cardNetworks]
function cardnetworks_shortcode() {
    $facebook  = format_social_link(get_option('sipylus_facebook'),  'https://www.facebook.com/');
    $twitter   = format_social_link(get_option('sipylus_twitter'),   'https://x.com/');
    $linkedin  = format_social_link(get_option('sipylus_linkedin'),  'https://linkedin.com/in/');
    $github    = format_social_link(get_option('sipylus_github'),    'https://github.com/');
    $instagram = format_social_link(get_option('sipylus_instagram'), 'https://instagram.com/');

    // Handle YouTube input (supports @handle, full URL, or legacy username)
    $youtube_input = get_option('sipylus_youtube');
    if (strpos($youtube_input, 'http') === 0) {
        $youtube = $youtube_input;
    } elseif (strpos($youtube_input, '@') === 0) {
        $youtube = 'https://www.youtube.com/' . $youtube_input;
    } elseif ($youtube_input) {
        $youtube = 'https://www.youtube.com/user/' . ltrim($youtube_input, '@');
    } else {
        $youtube = null;
    }

    if (!$facebook && !$twitter && !$linkedin && !$github && !$youtube && !$instagram) {
        return '<p>Social links not available.</p>';
    }

    ob_start();
    ?>
    <div class="card-networks-wrapper">
        <div class="card-networks-buttons">
            <?php if ($facebook): ?>
                <a href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noopener" class="cn-btn facebook">Facebook</a>
            <?php endif; ?>
            <?php if ($twitter): ?>
                <a href="<?php echo esc_url($twitter); ?>" target="_blank" rel="noopener" class="cn-btn twitter">Twitter (X)</a>
            <?php endif; ?>
            <?php if ($linkedin): ?>
                <a href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="noopener" class="cn-btn linkedin">LinkedIn</a>
            <?php endif; ?>
            <?php if ($github): ?>
                <a href="<?php echo esc_url($github); ?>" target="_blank" rel="noopener" class="cn-btn github">GitHub</a>
            <?php endif; ?>
            <?php if ($youtube): ?>
                <a href="<?php echo esc_url($youtube); ?>" target="_blank" rel="noopener" class="cn-btn youtube">YouTube</a>
            <?php endif; ?>
            <?php if ($instagram): ?>
                <a href="<?php echo esc_url($instagram); ?>" target="_blank" rel="noopener" class="cn-btn instagram">Instagram</a>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('cardNetworks', 'cardnetworks_shortcode');

// Enqueue styles
function cardnetworks_enqueue_styles() {
    $css = "
    .card-networks-wrapper {
        margin: 20px 0;
        text-align: center;
        font-family: Arial, sans-serif;
    }
    .card-networks-buttons {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 12px;
    }
    .cn-btn {
        display: inline-block;
        padding: 10px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: bold;
        color: white;
        transition: background 0.3s ease;
    }
    .cn-btn.facebook  { background: #3b5998; }
    .cn-btn.twitter   { background: #1da1f2; }
    .cn-btn.linkedin  { background: #0077b5; }
    .cn-btn.github    { background: #333; }
    .cn-btn.youtube   { background: #ff0000; }
    .cn-btn.instagram { ba
