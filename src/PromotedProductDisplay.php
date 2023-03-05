<?php
/*
 * The PromotedProductDisplay class is responsible for displaying the promoted product in a designated div located after the header. It retrieves the necessary data from the WooPromotedSettings and WooPromotedOptions classes to ensure the correct product is displayed.
 */
namespace WooPromotedProduct;
class PromotedProductDisplay {

    // Constructor method
    public function __construct() {

        add_action( 'wp_body_open', array( $this, 'display_banner' ) );
    }

    // Method to display the banner
    public function display_banner() {
        // Create an instance of the WooPromotedOptions class
        $p_options = new WooPromotedOptions();
        $woo_p_settings = new WooPromotedSettings();

        // Get the value of the $PromotedProduct property from the options object
        $promoted_product = $p_options->get_promoted_product();
        $CustomPTitle = $p_options->get_custom_title();
        $promoted_product_title = $woo_p_settings->get_promoted_product_title();
        $promoted_product_background_color = $woo_p_settings->get_promoted_product_background_color();
        $promoted_product_text_color = $woo_p_settings->get_promoted_product_text_color();
        $promoted_product_link=get_the_permalink($promoted_product);
        // Check if the $PromotedProduct property has a value
        if ( ! empty( $promoted_product ) ) {
            // Output the HTML for the banner
            echo '<div style="background-color:'.$promoted_product_background_color.';color:'.$promoted_product_text_color.';text-align:center;height: 50px; width: 100%;">';
            echo '<h2>'.$promoted_product_title.': <a style="color:'.$promoted_product_text_color.';" href="'.$promoted_product_link.'">' . $CustomPTitle . '</a></h2>';
            echo '</div>';
        }
    }

}
