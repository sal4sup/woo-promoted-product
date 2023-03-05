<?php
/*
 *The "WooPromotedOptions" class is designed to enable the addition of custom fields to WordPress products and save them to the general WordPress options.
 */
namespace WooPromotedProduct;
class WooPromotedOptions {
    private $PromotedProduct;
    private $CustomPTitle;
    private $ProductExpiration;
    private $ProductExpirationDate;

    // Constructor method
    public function __construct() {
        $this->PromotedProduct = get_option( 'promoted_product' );
        $this->CustomPTitle = get_option( 'custom_product_title' );
        $this->ProductExpiration = get_option( 'product_expiration' );
        $this->ProductExpirationDate = get_option( 'product_expiration_date' );
        add_action('activated_plugin', array(__CLASS__, 'add_promoted_product_option'));
        add_filter( 'woocommerce_product_options_general_product_data', array( $this, 'woop_add_custom_product_fields' ) );
        add_filter( 'woocommerce_process_product_meta', array( $this, 'woop_save_custom_product_fields' ) );
        if (!wp_next_scheduled('wp_cron_check_expired_products')) {
            wp_schedule_event(time(), 'hourly', 'wp_cron_check_expired_products');
        }
        // Schedule the cron job to run every hour
        add_filter( 'wp_cron_check_expired_products', array( $this, 'check_expired_products' ) );

    }

    //Adds the 'promoted_product' option to the database.
    public static function add_promoted_product_option() {
        add_option( 'promoted_product', '', '', 'no' );
        add_option( 'custom_product_title', '', '', 'no' );
        add_option( 'product_expiration', '', '', 'no' );
        add_option( 'product_expiration_date', '', '', 'no' );
    }

    // Public method to retrieve the value of the $PromotedProduct property
    public function get_promoted_product() {
        return $this->PromotedProduct;
    }

    // Public method to retrieve the value of the $CustomPTitle property
    public function get_custom_title() {
        $original_title=get_the_title($this->PromotedProduct);
        return !empty($this->CustomPTitle) ? $this->CustomPTitle : $original_title;
    }

    // Public method to retrieve the value of the $ProductExpiration property
    public function is_product_expired() {
        return $this->ProductExpiration;
    }

    // Public method to retrieve the value of the $ProductExpirationDate property
    public function get_product_expiration_date() {
        return $this->ProductExpirationDate;
    }


    // Add custom fields to the product editor
    public function woop_add_custom_product_fields() {
        if($this->PromotedProduct!=get_the_ID()){
            $this->PromotedProduct = '';
            $this->CustomPTitle = '';
            $this->ProductExpiration = '';
            $this->ProductExpirationDate = '';
        }
        // Promote this product checkbox
        woocommerce_wp_checkbox( array(
            'id' => 'promoted_product',
            'label' => __('Promote this product', 'woocommerce'),
            'description' => __('Check this box to mark this product as promoted', 'woocommerce'),
            'value' => !empty($this->PromotedProduct) ? 'yes' : 'no',
        ));

        // Custom product title text field
        woocommerce_wp_text_input( array(
            'id' => 'custom_product_title',
            'class' => 'short',
            'label' => __('Custom Product Title', 'woocommerce'),
            'placeholder' => __('Enter a custom title for this product', 'woocommerce'),
            'value' => $this->CustomPTitle,
        ));

        // Set expiration date checkbox and date/time field
        woocommerce_wp_checkbox( array(
            'id' => 'product_expiration',
            'label' => __('Set an expiration date and time', 'woocommerce'),
            'description' => __('Check this box to set an expiration date and time for this product', 'woocommerce'),
            'value' => $this->ProductExpiration,
        ));

        woocommerce_wp_text_input( array(
            'id' => 'product_expiration_date',
            'class' => 'short',
            'label' => __('Expiration Date', 'woocommerce'),
            'description' => __('Select the expiration date and time for this product', 'woocommerce'),
            'type' => 'datetime-local',
            'value' => $this->ProductExpirationDate,
            'wrapper_class' => 'product_expiration_field',
        ));
    }

    // Save custom product fields
    public function woop_save_custom_product_fields($product_id) {

        if ( isset( $_POST['promoted_product'] ) ) {
            update_option( 'promoted_product', $product_id);
            if ( isset( $_POST['custom_product_title'] ) ) {
                update_option( 'custom_product_title', sanitize_text_field($_POST['custom_product_title']));
            }
            if ( isset( $_POST['product_expiration'] ) ) {
                update_option( 'product_expiration','yes');
                if ( isset( $_POST['product_expiration_date'] ) ) {
                    update_option( 'product_expiration_date', sanitize_text_field($_POST['product_expiration_date']));
                    wp_clear_scheduled_hook( 'wp_cron_check_expired_products' );
                }
            }
            else{
                update_option( 'product_expiration','no');
                update_option( 'product_expiration_date', '');
            }
        }
        else{
            update_option( 'promoted_product', '');
        }

    }

    // Check if the expiration date has passed
    public function check_expired_products() {
        if (strtotime($this->ProductExpirationDate) < time()) {
            update_option( 'promoted_product', '');
            update_option( 'custom_product_title', '');
            update_option( 'product_expiration','');
            update_option( 'product_expiration_date', '');

        }
    }


}
