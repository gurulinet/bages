<?php

namespace Jigoshop\Extension\NewProductBadge\Admin;

use Jigoshop\Helper\Styles;
use Jigoshop\Integration\Helper\Render;
use Jigoshop\Helper\Scripts;

class ProductBadge
{
	protected $data;
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
		Styles::add('jigoshop.vendors.bs_switch', JIGOSHOP_URL.'/assets/css/vendors/bs_switch.css', array('jigoshop.admin'));
		Scripts::add('jigoshop.vendors.bs_switch', JIGOSHOP_URL . '/assets/js/vendors/bs_switch.js', array('jigoshop.admin'));
	}
	
	/**
	 * @param array $menu
	 * @return array mixed
	 */
	public function addMenu($menu)
	{
		$menu['new_product_badge'] = array(
			'label' => __('New Badge', 'jigoshop_product_accessories'),
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
			'text' => get_post_meta($ID, 'new_product_badge_text_'.$ID, true),
			'days' => get_post_meta($ID, 'new_product_badge_days_'.$ID, true),
			'color' => get_post_meta($ID, 'new_product_badge_color_'.$ID, true),
			'checked' => get_post_meta($ID, 'enable_badge_'.$ID, true),
		);
		
		$badgeMeta = get_post_meta($ID, 'new_badge', $badgeData);
		
		$tabs['new_product_badge'] = Render::get('new_product_badge', 'admin/product/box', array(
			'productID' => $product->getId(),
			'productName' => $product->getName(),
			'badgeColors' => $this->badgeProductColor(),
			'productBadgeText' => $badgeMeta['text'],
			'productBadgeDays' => $badgeMeta['days'],
			'productBadgeColor' => $badgeMeta['color'],
			'checked' => $badgeMeta['checked'],
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
		
		if(isset($_POST['enable_badge_'.$ID])) {
			update_post_meta($ID, 'enable_badge_'.$ID, strip_tags($_POST['enable_badge_'.$ID]));
		}
		if(isset($_POST['new_product_badge_text_'.$ID])) {
			update_post_meta($ID, 'new_product_badge_text_'.$ID, strip_tags($_POST['new_product_badge_text_'.$ID]));
		}
		if(isset($_POST['new_product_badge_days_'.$ID])){
			update_post_meta($ID, 'new_product_badge_days_'.$ID , absint($_POST['new_product_badge_days_'.$ID]));
		}
		if(isset($_POST['new_product_badge_color_'.$ID])){
			update_post_meta($ID, 'new_product_badge_color_'.$ID, strip_tags($_POST['new_product_badge_color_'.$ID]));
		}
		$badgeData = array(
			'ID' => $ID,
			'text' => $_POST['new_product_badge_text_'.$ID],
			'days' => $_POST['new_product_badge_days_'.$ID],
			'color' => $_POST['new_product_badge_color_'.$ID],
			'checked' => $_POST['enable_badge_'.$ID],
		);
		update_post_meta($ID, 'new_badge', $badgeData);
	}
}