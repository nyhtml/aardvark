<?php
/**
 * cardSkill.php — helper shortcode for Aardvark plugin
 * Version: 1.0.1
 * Author: Stephan Pringle
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly
if (!defined('AARDVARK_PLUGIN_VERSION')) {
    define('AARDVARK_PLUGIN_VERSION', '1.0.1');
}

function my_cardskill_shortcode($atts, $content = null) {
    $a = shortcode_atts( array(
        'jobtitle' => '',
        'company'  => '',
        'date'     => '',
    ), $atts );

    return sprintf(
        '<div class="skill-entry">
            <div class="skill-top">
                <div class="skill-jobtitle">%s</div>
                <div class="skill-date">%s</div>
            </div>
            <div class="skill-company">%s</div>
            <div class="skill-recommend">%s</div>
        </div>',
        esc_html($a['jobtitle']),
        esc_html($a['date']),
        esc_html($a['company']),
        wpautop(do_shortcode($content))
    );
}
add_shortcode('cardSkill', 'my_cardskill_shortcode');

function my_cardskill_enqueue_styles() {
    $custom_css = "
    .skill-entry {
        margin: 15px 0;
        padding-bottom: 5px;
        border-top: 1px solid #ccc;
        font-family: Arial, sans-serif;
    }
    .skill-top {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
    }
    .skill-jobtitle {
        font-size: 1.2em;
        font-weight: bold;
        color: #444;
    }
    .skill-company {
        color: #777;
        text-transform: uppercase;
        font-size: 0.95em;
        margin-bottom: 3px;
    }
    .skill-date {
        color: #333;
        padding: 3px 8px;
        border: 1px solid #ddd;
        background: linear-gradient(to bottom, #eee, #ccc);
        border-radius: 3px;
        font-weight: bold;
        font-size: 0.9em;
        white-space: nowrap;
        margin: 5px 0;
    }
    .skill-recommend {
        font-size: 0.95em;
    }
    ";
    wp_register_style('my_cardskill_styles', false);
    wp_enqueue_style('my_cardskill_styles');
    wp_add_inline_style('my_cardskill_styles', $custom_css);
}
add_action('wp_enqueue_scripts', 'my_cardskill_enqueue_styles');
