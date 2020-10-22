<?php

/**
Plugin Name: ACF Group Loop
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Adding Group Loops for AFC plugin.
Version: 1.0
Author: jayjay
Author URI: http://jakubforman.eu
License: A "Slug" license name e.g. GPL2
*/

function my_acf_repeater($atts, $content = '')
{
    extract(shortcode_atts([
        "field" => null,
        "post_id" => null
    ], $atts));

    $_content = '';

    if (have_rows($field, $post_id)) {


        // vyberu z pole pouze ty hodnoty, které jsou zadány v shortcode
        $availableSubFields = array_filter(get_fields(), function ($key) use ($field) {
            return $key == $field;
        }, ARRAY_FILTER_USE_KEY)[$field];

        // projdu všechny sub fieldy z hlavního fieldu
        foreach ($availableSubFields as $subFieldIndex => $subFieldValue) {
            // získám objekt/pole z custom fieldu
            $fieldData = get_sub_field_object($subFieldIndex);

            // vypíšu hodnoty jak potřebuji
            $label = $fieldData['label'];

            // výpis pomocí TMP proměnné
            $_tmp = str_replace('%key%', $label, $content);
            $_tmp = str_replace('%value%', $subFieldValue, $_tmp);
            $_content .= do_shortcode($_tmp);
        }
    }

    return $_content;
}
// přidám shortcode
add_shortcode("acf_repeater", "my_acf_repeater");


/**
 * Zobrazí jako HTML list
 * <ul class="afc-lv">
 *      <li><span class="afc-lv-label">Label</span><span class="afc-lv-value">Value</span></li>
 * </ul>
 *
 * @param $atts
 * @return string
 */
function acfListView($atts)
{
    // převod na proměnné z atributů shortcode
    extract(shortcode_atts([
        "field" => null,
        "post_id" => null
    ], $atts));

    $_content = '';

    if (have_rows($field, $post_id)) {


        // vyberu z pole pouze ty hodnoty, které jsou zadány v shortcode
        $availableSubFields = array_filter(get_fields(), function ($key) use ($field) {
            return $key == $field;
        }, ARRAY_FILTER_USE_KEY)[$field];

        $_content .= '<ul class="afc-lv">';
        // projdu všechny sub fieldy z hlavního fieldu
        foreach ($availableSubFields as $subFieldIndex => $subFieldValue) {
            // získám objekt/pole z custom fieldu
            $fieldData = get_sub_field_object($subFieldIndex);

            // vypíšu hodnoty jak potřebuji
            $label = $fieldData['label'];

            // výpis pomocí TMP proměnné
            $_content .= "<li><span class='afc-lv-label'>$label</span><span class='afc-lv-value'>$subFieldValue</span></li>";
        }
        $_content .= '</ul>';
    }

    return $_content;
}

// přidám shortcode
add_shortcode("acf_repeater_list", "acfListView");