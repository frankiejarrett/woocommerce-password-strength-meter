<?php
/**
 * Plugin Name: WooCommerce Password Strength Meter
 * Plugin URI: http://www.woothemes.com/products/woocommerce-password-strength-meter/
 * Description: Display a password strength meter when customers register during checkout.
 * Version: 1.0.0
 * Author: WooThemes
 * Author URI: http://woothemes.com/
 * Developer: Frankie Jarrett
 * Developer URI: http://frankiejarrett.com/
 * Depends: WooCommerce
 * Text Domain: woocommerce-password-strength-meter
 * Domain Path: /languages
 *
 * Copyright: © 2009-2015 WooThemes.
 * License: GNU General Public License v3.0
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Plugin Class
 *
 * Display a password strength meter when customers register during checkout.
 *
 * @version 1.0.0
 * @package WooCommerce
 * @author  Frankie Jarrett
 */
class WC_Password_Strength_Meter {

	/**
	 * Hold class instance
	 *
	 * @access public
	 * @static
	 *
	 * @var WC_Password_Strength_Meter
	 */
	public static $instance;

	/**
	 * Plugin version number
	 *
	 * @const string
	 */
	const VERSION = '1.0.0';

	/**
	 * Class constructor
	 *
	 * @access private
	 */
	private function __construct() {
		if ( ! $this->woocommerce_exists() ) {
			return;
		}

		define( 'WC_PASSWORD_STRENGTH_METER_URL', plugins_url( '/', __FILE__ ) );

		// Enqueue scripts and styles during checkout
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Add password strength meter element to the registration form during checkout
		add_action( 'woocommerce_after_checkout_registration_form', array( $this, 'password_strength_html' ) );
	}

	/**
	 * Return an active instance of this class
	 *
	 * @access public
	 * @since 1.0.0
	 * @static
	 *
	 * @return WC_Password_Strength_Meter
	 */
	public static function get_instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Returns true if WooCommerce exists
	 *
	 * Looks at the active list of plugins on the site to
	 * determine if WooCommerce is installed and activated.
	 *
	 * @access private
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function woocommerce_exists() {
		return in_array( 'woocommerce/woocommerce.php', (array) apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
	}

	/**
	 * Enqueue front-end scripts
	 *
	 * We will enqueue the `password-strength-meter` script that
	 * comes bundled with core that does a great job checking
	 * not only the length of the password but its entropy score.
	 * In addition, custom JS and CSS will be enqueued to target
	 * the strength meter element.
	 *
	 * @action wp_enqueue_scripts
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		if ( ! is_checkout() ) {
			return;
		}

		wp_enqueue_script( 'password-strength-meter' );
		wp_enqueue_script( 'wc-password-strength', WC_PASSWORD_STRENGTH_METER_URL . 'ui/wc-password-strength.min.js', array( 'jquery', 'password-strength-meter' ), self::VERSION );
		wp_enqueue_style( 'wc-password-strength', WC_PASSWORD_STRENGTH_METER_URL . 'ui/wc-password-strength.min.css', array(), self::VERSION );
	}

	/**
	 * HTML markup for password strength meter
	 *
	 * Outputs the password strength element on the checkout
	 * page below the registration form. This element is targeted
	 * with the enqueued scripts and styles.
	 *
	 * @action woocommerce_after_checkout_registration_form
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function password_strength_html() {
		?>
		<p id="wc-pass-strength-result"></p>
		<?php
	}

}

/**
 * Instantiate the plugin instance
 *
 * @global WC_Password_Strength_Meter
 */
$GLOBALS['wc_password_strength_meter'] = WC_Password_Strength_Meter::get_instance();
