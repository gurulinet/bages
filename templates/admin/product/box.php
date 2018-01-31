<?php 
/**@var $productID string
 * @var $productName string
 * @var $badgeColor string
 * @var $badgeColors array
 * @var $productBadgeText string
 * @var $productBadgeColor string
 * @var $productBadgeDays string
 * @var $newBadgeData array
 */
?>
<div class="tab-content">

	<div id="general" class="tab-panel active">
		<fieldset>
            <div class="form-group padding-bottom-5">
                <div class="row">
                    <div class="col-sm-12">
                        <label for="enable_badge_<?php echo $productID; ?>" class="col-xs-12 col-sm-2 margin-top-bottom-9">
                            <?php _e('Enable', 'jigoshop_new_product_badge'); ?></label>
                        <div class="col-xs-2 col-sm-1 text-right">
                        </div>
                        <div class="col-xs-8 col-sm-9">
                            <?php if($checked !== ''): ?>
                                <?php $checked = 'checked' ?>
                            <?php endif; ?>
                            <input class="switch-medium" type="checkbox" <?php echo $checked; ?> id="enable_badge_<?php echo $productID; ?>"  name="enable_badge_<?php echo $productID; ?>" />
                            <span class="help enable_description" style="margin-left:5px; vertical-align: bottom;"><?php _e('Enable to apply individual product settings, otherwise global settings will be used', 'jigoshop_new_product_badge') ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php \Jigoshop\Admin\Helper\Forms::text(array(
                    'id' => 'new_product_badge_days_'.$productID,
                    'name' => 'new_product_badge_days_'.$productID,
                    'label' => __('How many days', 'jigoshop_new_product_badge'),
                    'value' => $productBadgeDays,
                    'description' => __('', 'jigoshop_new_product_badge'),
            )) ?>
            <?php \Jigoshop\Admin\Helper\Forms::text(array(
				'id' => 'new_product_badge_text_'.$productID,
				'name' => 'new_product_badge_text_'.$productID,
				'label' => __('Badge text', 'jigoshop_new_product_badge'),
				'value' => $productBadgeText,
				'description' => __('Add a custom text for new badge', 'jigoshop_new_product_badge'),
			)) ?>
			<?php \Jigoshop\Admin\Helper\Forms::select(array(
                'id' => 'new_product_badge_color_'.$productID,
				'name' => 'new_product_badge_color_'.$productID,
				'label' => __('Badge Color', 'jigoshop_new_product_badge'),
				'value' => $productBadgeColor,
				'description' => __('Select the color of badge', 'jigoshop_new_product_badge'),
				'options' => $badgeColors,
			)) ?>
		</fieldset>
	</div>
</div>


