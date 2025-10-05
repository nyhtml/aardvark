<?php
/**
 * cardNetworks.php — helper shortcode for Aardvark plugin
 * Version: 1.0.1
 * Author: Stephan Pringle
 */

if (!defined('ABSPATH')) exit; // Prevent direct access
if (!defined('AARDVARK_PLUGIN_VERSION')) {
    define('AARDVARK_PLUGIN_VERSION', '1.0.1');
}

// Helper function to format social URLs
function format_social_link($value, $base) {
    if (!$value) return null;
    // If already a full URL starting with http, return as is; else prepend base URL
    return (strpos($value, 'http') === 0) ? $value : $base . ltrim($value, '@');
}

// SHORTCODE: [cardNetworks]
function cardnetworks_shortcode() {
    $facebook  = format_social_link(get_option('aardvark_facebook'),  'https://www.facebook.com/');
    $twitter   = format_social_link(get_option('aardvark_twitter'),   'https://x.com/');
    $linkedin  = format_social_link(get_option('aardvark_linkedin'),  'https://linkedin.com/in/');
    $github    = format_social_link(get_option('aardvark_github'),    'https://github.com/');
    $instagram = format_social_link(get_option('aardvark_instagram'), 'https://www.instagram.com/');
    $pinterest = format_social_link(get_option('aardvark_pinterest'), 'https://www.pinterest.com/');

    // Handle YouTube input (supports @handle, full URL, or legacy username)
    $youtube_input = get_option('aardvark_youtube');
    if (strpos($youtube_input, 'http') === 0) {
        $youtube = $youtube_input;
    } elseif (strpos($youtube_input, '@') === 0) {
        $youtube = 'https://www.youtube.com/' . $youtube_input;
    } elseif ($youtube_input) {
        $youtube = 'https://www.youtube.com/user/' . ltrim($youtube_input, '@');
    } else {
        $youtube = null;
    }

    if (!$facebook && !$twitter && !$linkedin && !$github && !$youtube && !$instagram && !pinterest) {
        return '<p>Social links not available.</p>';
    }

    ob_start();
    ?>
    <div class="card-networks-wrapper">
        <div class="card-networks-buttons">
            <?php if ($facebook): ?>
                <a href="<?php echo esc_url($facebook); ?>" target="_blank" rel="nofollow noopener noreferrer" class="cn-btn facebook">Facebook</a>
            <?php endif; ?>
            <?php if ($twitter): ?>
                <a href="<?php echo esc_url($twitter); ?>" target="_blank" rel="nofollow noopener noreferrer" class="cn-btn twitter">Twitter</a>
            <?php endif; ?>
            <?php if ($linkedin): ?>
                <a href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="nofollow noopener noreferrer" class="cn-btn linkedin">LinkedIn</a>
            <?php endif; ?>
            <?php if ($github): ?>
                <a href="<?php echo esc_url($github); ?>" target="_blank" rel="nofollow noopener noreferrer" class="cn-btn github">GitHub</a>
            <?php endif; ?>
            <?php if ($youtube): ?>
                <a href="<?php echo esc_url($youtube); ?>" target="_blank" rel="nofollow noopener noreferrer" class="cn-btn youtube">YouTube</a>
            <?php endif; ?>
            <?php if ($instagram): ?>
                <a href="<?php echo esc_url($instagram); ?>" target="_blank" rel="nofollow noopener noreferrer" class="cn-btn instagram">Instagram</a>
            <?php endif; ?>
            <?php if ($pinterest): ?>
                <a href="<?php echo esc_url($pinterest); ?>" target="_blank" rel="nofollow noopener noreferrer" class="cn-btn pinterest">Pinterest</a>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('cardNetworks', 'cardnetworks_shortcode');

// Enqueue inline styles
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
    .cn-btn:hover {
        opacity: 0.85;
    }
    .cn-btn.facebook    { background: #1877f2; }
    .cn-btn.twitter     { background: #1da1f2; }
    .cn-btn.linkedin    { background: #0077b5; }
    .cn-btn.github      { background: #000000; }
    .cn-btn.youtube     { background: #ff0000; }
    .cn-btn.instagram   { background: #e1306c; }
    .cn-btn.pinterest   { background: #e60023; }
    @media (max-width: 480px) {
        .card-networks-buttons {
            flex-direction: column;
        }
    }
    ";
    wp_register_style('cardnetworks-inline-style', false);
    wp_enqueue_style('cardnetworks-inline-style');
    wp_add_inline_style('cardnetworks-inline-style', $css);
}
add_action('wp_enqueue_scripts', 'cardnetworks_enqueue_styles');
