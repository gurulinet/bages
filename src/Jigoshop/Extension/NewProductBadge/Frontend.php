<?php

namespace Jigoshop\Extension\NewProductBadge;

use Jigoshop\Integration;
use Jigoshop\Helper\Styles;

class Frontend
{
	protected $badge;
	protected $singleBadge;
	protected $options;
	protected $_productBadge;
	public function __construct()
	{
		// Enqueue the styles
		add_action('wp_enqueue_scripts', array($this, 'setupStyles'));
		
		add_action('jigoshop\shop\list\product\before', array($this, 'showBadge'),90);
	}
	
	public function setupStyles()
	{
		Styles::add('jigoshop.extensions.new_product_badge.frontend', JIGOSHOP_NEW_PRODUCT_BADGE_URL . '/assets/css/shop-front.css');
	}
	
	/**
	 * @param array $tabs
	 * @param \Jigoshop\Entity\Product $product
	 * @return array mixed
	 */
	public function showBadge($product)
	{
		$this->_productBadge = get_post_meta($product->getId(), 'new_badge', true);
		$this->options = Integration::getOptions()->get('new_product_badge');
		
		if(isset($this->_productBadge['checked']) && $this->_productBadge['checked'] == 'on'){
			$singlePostDate = get_the_time('Y-m-d', $product->getId());
			$singlePostDateStamp = strtotime($singlePostDate);
			$singleNewNess = absint($this->_productBadge['days']);
			$singleBadgeColor = strip_tags($this->_productBadge['color']);
			$this->singleBadge = sanitize_text_field($this->_productBadge['text']);
			$singleBadgeColor = !empty($singleBadgeColor) ? $singleBadgeColor : '';
			$class = ' '.$singleBadgeColor;
			if ((time() - (60 * 60 * 24 * $singleNewNess)) < $singlePostDateStamp) { // If the product was published within the newness time frame display the new badge
				echo '<span class="jigoshop-new-product-badge'.$class.'">'.__(sanitize_text_field($this->singleBadge), 'jigoshop_new_product_badge').'</span>';
			}
		}else{
			if($this->options['show_badge'] == true){
				$postDate = get_the_time('Y-m-d', $product->getId());
				$postDateStamp = strtotime($postDate);
				$newNess = absint($this->options['badge_products_days']);
				$badgeColor = strip_tags($this->options['badge_color']);
				$this->badge = sanitize_text_field($this->options['badge_text']);
				$badgeColor = !empty($badgeColor) ? $badgeColor : '';
				$class = ' '.$badgeColor;
				if ((time() - (60 * 60 * 24 * $newNess)) < $postDateStamp) { // If the product was published within the newness time frame display the new badge
					echo '<span class="jigoshop-new-product-badge'.$class.'">'.__(sanitize_text_field($this->badge), 'jigoshop_new_product_badge').'</span>';
				}
			}
		}
	}
}

new Frontend();