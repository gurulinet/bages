<?php
namespace Jigoshop\Extension\NewProductBadge;

use Jigoshop\Integration;

/**
 * Class Admin
 */
class Admin
{
	public function __construct()
	{
		Integration::addPsr4Autoload(__NAMESPACE__ . '\\', __DIR__);
		Integration\Helper\Render::addLocation('new_product_badge', JIGOSHOP_NEW_PRODUCT_BADGE_DIR);
		add_filter('plugin_action_links_' . plugin_basename(JIGOSHOP_NEW_PRODUCT_BADGE_DIR . '/bootstrap.php'), array($this, 'actionLinks'));
		add_action('init', array($this, 'init'));
	}
	public function init()
	{
		new Admin\Settings();
		new Admin\Product();
	}

	/**
	 * @param array $links
	 *
	 * @return array
	 */
	public function actionLinks($links)
	{
		// Missing links
		return $links;
	}
}

new Admin;