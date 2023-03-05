<?php
/*
Plugin Name: WooCommerce Promoted Product
Description: Display selected product as a promoted item.
Version: 1.0
Author: Saleem Summour
*/
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
use WooPromotedProduct\WooPromotedOptions;
use WooPromotedProduct\WooPromotedSettings;
use WooPromotedProduct\PromotedProductDisplay;

(new WooPromotedSettings);
(new WooPromotedOptions);
(new PromotedProductDisplay);