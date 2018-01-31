<?php

namespace Jigoshop\Extension\NewProductBadge\Admin;


use Jigoshop\Admin\Settings\TabInterface;
use Jigoshop\Integration;

class Settings implements TabInterface
{
	const SLUG = 'new_product_badge';
	
	/** @var array*/
	private $options;

	public function __construct()
	{
		// Default option for new badge
		add_filter('jigoshop\admin\settings', array($this, 'addSettingsTab'));
		
		$this->options = $this->getOptions();
		
	}
	
	/**
	 * @return string Title of the tab.
	 */
	public function getTitle()
	{
		return __('New Product Badge', 'jigoshop_new_product_badge');
	}
	
	/**
	 * @return string Tab slug.
	 */
	public function getSlug()
	{
		return self::SLUG;
	}
	
	/**
	 *
	 * @param string $name name of options
	 *
	 * @return mixed
	 */
	public function getOption($name)
	{
		if (!isset($this->options[$name]))
		{
			throw new \Jigoshop\Exception(sprintf('Options \'%s\' does not exists in that plugin', $name));
		}
		
		return $this->options[$name];
	}
	
	private function getDefaultOptions()
	{
		return array(
			'show_badge' => true,
			'badge_products_days' => '30',
			'badge_text' => __('New', 'jigoshop_new_product_badge'),
			'badge_color' => 'red',
		);
	}
	
	/**
	 * @return array List of items to display.
	 */
	public function getSections()
	{
		global $post;
		return array(
			array(
				'title' => __('General', 'jigoshop_new_product_badge'),
				'id' => 'general',
				'description' => __('Use below setting to set global new product badge options, for individual products settings go to product edit page.', 'jigoshop_new_product_badge'),
				'fields' => array(
					array(
						'name' => '[show_badge]',
						'title' => __('Enable New Badge', 'jigoshop_new_product_badge'),
						'tip' => __('', 'jigoshop_new_product_badge'),
						'description' => __('Enable this option to display "New" badge for products', 'jigoshop_new_product_badge'),
						'type' => 'checkbox',
						'classes' => array('switch-medium'),
						'checked' => $this->options['show_badge'],
					),
					array(
						'name' => '[badge_products_days]',
						'title' => __('Period of validity', 'jigoshop_new_product_badge'),
						'description' => __('How many days to display the "New" badge for?', 'jigoshop_new_product_badge'),
						'type' => 'text',
						'value' => $this->options['badge_products_days']
					),
					array(
						'name' => '[badge_text]',
						'title' => __('Badge text', 'jigoshop_new_product_badge'),
						'description' => __('Edit the badge text for new products, or use the default "New" value', 'jigoshop_new_product_badge'),
						'type' => 'text',
						'value' => $this->options['badge_text'],
					),
					array(
						'name' => '[badge_color]',
						'title' => __('Badge Color', 'jigoshop_new_product_badge'),
						'description' => __('Select the colour of the badge.', 'jigoshop_new_product_badge'),
						'type' => 'select',
						'options' => $this->badgeColor(),
						'value' => $this->options['badge_color'],
					),
				),
			)
		);
	}
	
	protected function badgeColor()
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
	 * Validate and sanitize input values.
	 *
	 * @param array $settings Input fields.
	 *
	 * @return array Sanitized and validated output.
	 * @throws ValidationException When some items are not valid.
	 */
	public function validate($settings)
	{
		$settings['show_badge'] = $settings['show_badge'] == 'on';
		
		return $settings;
	}
	
	/**
	 * Add Settings tab to Jigoshop settings page.
	 * @see https://github.com/jigoshop/Jigoshop2/blob/master/src/Jigoshop/Admin/Settings/GeneralTab.php Jigoshop\Admin\Settings\GeneralTab
	 *
	 * @param array $tabs
	 *
	 * @return array
	 */
	public function addSettingsTab($tabs)
	{
		
		$tabs[] = $this;
		
		return $tabs;
	}
	
	/**
	 * @return array
	 */
	private function getOptions()
	{
		return array_merge($this->getDefaultOptions(), Integration::getOptions()->get($this->getSlug(), array()));
	}
}