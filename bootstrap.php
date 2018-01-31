<?php
/**
 * Here goes plugin name and bla bla bla
 */
// Define plugin name
define('JIGOSHOP_NEW_PRODUCT_BADGE_NAME', 'Jigoshop New Product Badge');
add_action('plugins_loaded', function () {
	load_plugin_textdomain('jigoshop_new_product_badge', false, dirname(plugin_basename(__FILE__)) . '/languages/');
	if (class_exists('\Jigoshop\Core')) {
		// Define plugin directory for inclusions
		define('JIGOSHOP_NEW_PRODUCT_BADGE_DIR', dirname(__FILE__));
		// Define plugin URL for assets
		define('JIGOSHOP_NEW_PRODUCT_BADGE_URL', plugins_url('', __FILE__));
		//Check version.
		if (\Jigoshop\addRequiredVersionNotice(JIGOSHOP_NEW_PRODUCT_BADGE_NAME, '2.0')) {
			return;
		}
		//Check license.
		/**
		 * Code is missing as it is not necessary here at this point
		 */
		//Init components.
		if (is_admin()) {
			require_once(JIGOSHOP_NEW_PRODUCT_BADGE_DIR . '/src/Jigoshop/Extension/NewProductBadge/Admin.php');
		}else{
			require_once(JIGOSHOP_NEW_PRODUCT_BADGE_DIR . '/src/Jigoshop/Extension/NewProductBadge/Frontend.php');
		}
	}else {
		add_action('admin_notices', function () {
			echo '<div class="error"><p>';
			printf(__('%s requires Jigoshop plugin to be active. Code for plugin %s was not loaded.', 'jigoshop_new_product_badge'),
				JIGOSHOP_NEW_PRODUCT_BADGE_NAME, JIGOSHOP_NEW_PRODUCT_BADGE_NAME);
			echo '</p></div>';
		});
	}
});
