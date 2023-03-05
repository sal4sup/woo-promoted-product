<?php
/*
 * The WooPromotedSettings class is designed to enhance the functionality of the plugin by adding a section called "promoted_product" to the Woocommerce > Settings > Products page
 *
 */
namespace WooPromotedProduct;
class WooPromotedSettings{
    private $promoted_product_title;
    private $promoted_product_background_color;
    private $promoted_product_text_color;
    // Constructor method
    public function __construct() {
        add_filter( 'woocommerce_get_sections_products', array( $this, 'woop_add_settings_section' ) );
        add_action( 'woocommerce_get_settings_products', array( $this, 'woop_get_settings' ) , 10, 2);
        add_action( 'woocommerce_admin_field_active_promoted_product', array( $this, 'active_promoted_product' ) , 10, 2);
        $this->promoted_product_title = get_option( 'promoted_product_title' );
        $this->promoted_product_background_color = get_option( 'promoted_product_background_color' );
        $this->promoted_product_text_color = get_option( 'promoted_product_text_color' );
    }


    public function woop_add_settings_section( $sections  ) {
        $sections['promoted_product'] = __( 'Promoted Product', 'woop-product' );
        return $sections;
    }
    public function woop_get_settings($settings,$current_section) {

        /**
         * Check the current section is what we want
         **/
        if ( $current_section == 'promoted_product' ) {
            $promoted_settings = array(
                'section_title' => array(
                    'name'     => __( 'Promoted Product Settings', 'woop-product' ),
                    'type'     => 'title',
                    'desc'     => '',
                    'id'       => 'promoted_product_section_title'
                ),
                'promoted_product_title' => array(
                    'name'     => __( 'Promoted Product Title', 'woop-product' ),
                    'type'     => 'text',
                    'desc'     => __( 'Enter the title of the promoted product', 'woop-product' ),
                    'placeholder'   =>__( 'FLASH SALE','woop_product'),
                    'id'       => 'promoted_product_title'
                ),
                'promoted_product_background_color' => array(
                    'name'     => __( 'Background Color', 'woop-product' ),
                    'type'     => 'color',
                    'desc'     => __( 'Choose the background color for the promoted product', 'woop-product' ),
                    'id'       => 'promoted_product_background_color'
                ),
                'promoted_product_text_color' => array(
                    'name'     => __( 'Text Color', 'woop-product' ),
                    'type'     => 'color',
                    'desc'     => __( 'Choose the text color for the promoted product', 'woop-product' ),
                    'id'       => 'promoted_product_text_color'
                ),
                'promoted_product_display' => array(
                    'name'     => __( 'Active Promoted Product', 'woop-product' ),
                    'type'     => 'active_promoted_product',
                    'desc'     => __( 'Displays the currently active promoted product and allows you to edit it', 'woop-product' ),
                    'id'       => 'promoted_product_display'
                ),

            );
            return $promoted_settings;

            /**
             * If not, return the standard settings
             **/
        }
        else {
            return $settings;
        }
    }
    // Define the custom HTML field
    public function active_promoted_product() {
        $p_options = new WooPromotedOptions();
        $promoted_product = $p_options->get_promoted_product();
        $html='<tr valign="top"><th scope="row" class="titledesc"><label>Active Promoted Product</label></th>';
        $html.='<td class="forminp"><a href="'.get_edit_post_link($promoted_product).'">'.get_the_title($promoted_product).'</a></td>';
        $html .= '</tr>';

        echo $html;
    }
    public function get_promoted_product_title() {
        return $this->promoted_product_title;
    }

    public function get_promoted_product_background_color() {
        return $this->promoted_product_background_color;
    }

    public function get_promoted_product_text_color() {
        return $this->promoted_product_text_color;
    }

}
