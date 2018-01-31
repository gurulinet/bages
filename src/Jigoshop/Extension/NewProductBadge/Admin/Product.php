<?php

namespace Jigoshop\Extension\NewProductBadge\Admin;

use Jigoshop\Helper\Styles;
use Jigoshop\Integration;
use Jigoshop\Integration\Helper\Render;
use Jigoshop\Helper\Scripts;

class Product
{
	protected $data;
	public $_productBadge;
	
	/**
	 * Product constructor.
	 */
	public function __construct()
	{
		add_filter('jigoshop\admin\product\menu', array($this, 'addMenu'));
		add_filter('jigoshop\admin\product\tabs', array($this, 'addTabs'), 10, 2);
		add_action('jigoshop\service\product\save', array($this, 'saveProduct'), 20);
		add_action('admin_enqueue_scripts', array($this, 'adminAssets'));
	}
	
	public function adminAssets()
	{
		Styles::add('jigoshop.vendors.bs_switch', JIGOSHOP_URL . '/assets/css/vendors/bs_switch.css', array('jigoshop.admin'));
		Scripts::add('jigoshop.vendors.bs_switch', JIGOSHOP_URL . '/assets/js/vendors/bs_switch.js', array('jigoshop.admin'));
	}
	
	/**
	 * @param array $menu
	 * @return array mixed
	 */
	public function addMenu($menu)
	{
		$menu['new_product_badge'] = array(
			'label' => __('New Product Badge', 'jigoshop_product_accessories'),
			'visible' => true
		);
		
		return $menu;
	}
	
	/**
	 * @param array $tabs
	 * @param \Jigoshop\Entity\Product $product
	 * @return array mixed
	 */
	public function addTabs($tabs, $product)
	{
		$ID = $product->getId();
		
		$badgeData = array(
			'ID' => $ID,
			'text' => get_post_meta($ID, 'new_product_badge_text_' . $ID, true),
			'days' => get_post_meta($ID, 'new_product_badge_days_' . $ID, true),
			'color' => get_post_meta($ID, 'new_product_badge_color_' . $ID, true),
			'checked' => get_post_meta($ID, 'enable_badge_' . $ID, true),
		);
		
		
		$badgeMeta = get_post_meta($ID, 'new_badge', $badgeData);
		// Global options
		$globalOptions = Integration::getOptions()->get('new_product_badge');
		
		$badgeText = '';
		if (!empty($badgeMeta['text']) && $badgeMeta['checked'] == 'on') {
			$badgeText = $badgeMeta['text'];
		}else{
			$badgeText = $globalOptions['badge_text'];
		}

		$badgeDays = '';
		if (!empty($badgeMeta['days']) && $badgeMeta['checked'] == 'on') {
			$badgeDays = $badgeMeta['days'];
		}else{
			$badgeDays = $globalOptions['badge_products_days'];
		}

		$badgeColor = '';
		if (!empty($badgeMeta['color']) && $badgeMeta['checked'] == 'on') {
			$badgeColor = $badgeMeta['color'];
		}else{
			$badgeColor = $globalOptions['badge_color'];
		}

		$badgeChecked = '';
		if(!empty($badgeMeta['checked'])){
			$badgeChecked = $badgeMeta['checked'];
		}
		
		$tabs['new_product_badge'] = Render::get('new_product_badge', 'admin/product/box', array(
			'productID' => $product->getId(),
			'productName' => $product->getName(),
			'badgeColors' => $this->badgeProductColor(),
			'productBadgeText' => $badgeText,
			'productBadgeDays' => $badgeDays,
			'productBadgeColor' => $badgeColor,
			'checked' => $badgeChecked,
		));
		
		return $tabs;
	}
	
	protected function badgeProductColor()
	{
		return array(
			'red' => __('Red', 'jigoshop_new_product_badge'),
			'blue' => __('Blue', 'jigoshop_new_product_badge'),
			'green' => __('Green', 'jigoshop_new_product_badge'),
			'yellow' => __('Yellow', 'jigoshop_new_product_badge'),
			'orange' => __('Orange', 'jigoshop_new_product_badge'),
			'pink' => __('Pink', 'jigoshop_new_product_badge'),
			'purple' => __('Purple', 'jigoshop_new_product_badge'),
			'turquoise' => __('Turquoise', 'jigoshop_new_product_badge'),
			'grey' => __('Grey', 'jigoshop_new_product_badge'),
			'dark' => __('Dark', 'jigoshop_new_product_badge'),
			'light' => __('Light', 'jigoshop_new_product_badge'),
		);
	}
	
	/**
	 * @param \Jigoshop\Entity\Product $product
	 */
	public function saveProduct($product)
	{
		$ID = $product->getId();

		$enableBadge = '';
		if (isset($_POST['enable_badge_' . $ID])) {
			$enableBadge = $_POST['enable_badge_' . $ID];
			update_post_meta($ID, 'enable_badge_' . $ID, strip_tags($enableBadge));
		}
		$badgeText = '';
		if (isset($_POST['new_product_badge_text_' . $ID])) {
			$badgeText = $_POST['new_product_badge_text_' . $ID];
			update_post_meta($ID, 'new_product_badge_text_' . $ID, strip_tags($badgeText));
		}
		$badgeDays = '';
		if (isset($_POST['new_product_badge_days_' . $ID])) {
			$badgeDays = $_POST['new_product_badge_days_' . $ID];
			update_post_meta($ID, 'new_product_badge_days_' . $ID, absint($badgeDays));
		}
		$badgeColor = '';
		if (isset($_POST['new_product_badge_color_' . $ID])) {
			$badgeColor = $_POST['new_product_badge_color_' . $ID];
			update_post_meta($ID, 'new_product_badge_color_' . $ID, strip_tags($badgeColor));
		}

		// Global options
		$globalOptions = Integration::getOptions()->get('new_product_badge');

		$globalShowBadge = false;
		if($globalOptions['show_badge'] == true){
			$globalShowBadge = $globalOptions['show_badge'];
			$globalShowBadge = true;
		}

		$globalBadgeProductDays = '';
		if(isset($globalOptions['badge_products_days'])){
			$globalBadgeProductDays = $globalOptions['badge_products_days'];
		}

		$globalBadgeText = '';
		if(isset($globalOptions['badge_text'])){
			$globalBadgeText = $globalOptions['badge_text'];
		}
		
		$globalBadgeColor = '';
		if(isset($globalOptions['badge_color'])){
			$globalBadgeColor = $globalOptions['badge_color'];
		}

		
		if($globalShowBadge == true && !isset($_POST['enable_badge_' . $ID])){
			$globalBadgeOptionForEveryProduct = array(
				'ID' => $product->getId(),
				'global_days' => $globalBadgeProductDays,
				'global_text' => $globalBadgeText,
				'global_color' => $globalBadgeColor
			);
			self::updateGlobalProductMeta($product->getId(), 'new_badge', $globalBadgeOptionForEveryProduct);
		}else{
			$badgeData = array(
				'productName' => $product->getName(),
				'ID' => $ID,
				'publishDate' => get_the_time('Y-m-d', $product->getId()),
				'text' => $badgeText,
				'days' => $badgeDays,
				'color' => $badgeColor,
				'checked' => $enableBadge,
			);
			update_post_meta($ID, 'new_badge', $badgeData);
		}
		
	}
	
	private function updateGlobalProductMeta($productId, $metaKey, $metaValue)
	{
		return update_post_meta($productId, $metaKey, $metaValue);
	}
}