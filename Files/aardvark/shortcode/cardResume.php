<?php
/*
Plugin Name: Sipylus CardResume Shortcode Handler
Description: Provides [cardResume] shortcode functionality with inline CSS and responsive mobile support.
Version: 2025.07.13.A
Author: <a href="http://www.stephanpringle.com">Stephan Pringle</>
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

function my_cardresume_shortcode($atts, $content = null) {
    $a = shortcode_atts( array(
        'jobtitle' => '',
        'company'  => '',
        'date'     => '',
    ), $atts );

    return sprintf(
        '<div class="resume-entry">
            <div class="resume-top">
                <div class="resume-jobtitle">%s</div>
                <div class="resume-date">%s</div>
            </div>
            <div class="resume-company">%s</div>
            <div class="resume-recommend">%s</div>
        </div>',
        esc_html($a['jobtitle']),
        esc_html($a['date']),
        esc_html($a['company']),
        wpautop(do_shortcode($content))
    );
}
add_shortcode('cardResume', 'my_cardresume_shortcode');

function my_cardresume_enqueue_styles() {
    $custom_css = "
    .resume-entry {
        margin: 15px 0;
        padding-bottom: 5px;
        border-top: 1px solid #ccc;
        font-family: Arial, sans-serif;
    }
    .resume-top {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
    }
    .resume-jobtitle {
        font-size: 1.2em;
        font-weight: bold;
        color: #444;
    }
    .resume-company {
        color: #777;
        text-transform: uppercase;
        font-size: 0.95em;
        margin-bottom: 3px;
    }
    .resume-date {
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
    .resume-recommend {
        font-size: 0.95em;
    }
    ";
    wp_register_style('my_cardresume_styles', false);
    wp_enqueue_style('my_cardresume_styles');
    wp_add_inline_style('my_cardresume_styles', $custom_css);
}
add_action('wp_enqueue_scripts', 'my_cardresume_enqueue_styles');
