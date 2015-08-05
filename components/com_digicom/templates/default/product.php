<?php
/**
 * @package		DigiCom
 * @author 		ThemeXpert http://www.themexpert.com
 * @copyright	Copyright (c) 2010-2015 ThemeXpert. All rights reserved.
 * @license 	GNU General Public License version 3 or later; see LICENSE.txt
 * @since 		1.0.0
 */

defined('_JEXEC') or die;
$app=JFactory::getApplication();
$input = $app->input;

$conf = $this->configs;
$date_today = time();

if($this->configs->get('afteradditem',0) == "2"){
	JHTML::_('behavior.modal');
	JFactory::getDocument()->addScript(JURI::base()."media/digicom/assets/js/createpopup.js");
}
?>
<div id="digicom">

	<div class="digi-products">

		<?php if(!$this->item->id): ?>
		<div class="alert alert-warning">
			<p><?php echo JText::_('COM_DIGICOM_PRODUCT_NOT_AVAILABLE_NOTICE'); ?></p>
			<p><a href="<?php echo JRoute::_(DigiComSiteHelperRoute::getCategoryRoute($this->item->catid, $this->item->language)); ?>"><?php echo JText::_("COM_DIGICOM_CONTINUE_SHOPPING"); ?></a></p>
		</div>
		<?php return true; ?>
		<?php endif; ?>

		<?php if(($this->item->publish_up > $date_today) || ($this->item->publish_down != 0 && $this->item->publish_down < $date_today)): ?>
		<div class="alert alert-warning">
			<?php echo JText::_('COM_DIGICOM_PRODUCT_PUBLISH_DOWN_NOTICE'); ?>
		</div>
		<?php return true; ?>
		<?php endif; ?>

		<?php if($this->item->state == 0): ?>
		<div class="alert alert-warning">
			<p><?php echo JText::_('COM_DIGICOM_PRODUCT_UNPUBLISHED_NOTICE'); ?></p>
			<p><a href="<?php echo JRoute::_("index.php?option=com_digicom&view=categories&id=0"); ?>"><?php echo JText::_("COM_DIGICOM_HOMEPAGE"); ?></a></p>
		</div>
		<?php return true; ?>
		<?php endif; ?>

		<?php

		$addtocart = '<input type="submit" value="'.(JText::_("COM_DIGICOM_ADD_TO_CART")).'" class="btn"/> ';
		?>

		<form name="prod" id="product-form" action="<?php echo JRoute::_('index.php?option=com_digicom&view=cart');?>" method="post" style="width:100%;">

			<div class="row-fluid">

				<!-- Details & Cart -->
				<div class="span12">
					<?php
						if($this->item->price > 0){
							 $price = '<span>'.JText::_('COM_DIGICOM_PRODUCT_PRICE').": ".DigiComSiteHelperPrice::format_price($this->item->price, $conf->get('currency','USD'), true, $conf).'</span>';
						  }else{
						  	$price = '<span>'.JText::_('COM_DIGICOM_PRODUCT_PRICE_FREE').'</span>';
						  }
					?>
					<?php if(!empty($this->item->images)): ?>
					<img src="<?php echo $this->item->images; ?>" class="img-responsive"/>
					<?php endif; ?>

					<h1 class="digi-page-title">
					<?php echo $this->item->name; ?>
						
						<?php if($this->item->featured):?>
						<span class="label label-important"><?php echo JText::_('JFEATURED');?></span>
						<?php endif; ?>

						<?php if(!empty($this->item->bundle_source)):?>
						<span class="label"><?php echo JText::sprintf('COM_DIGICOM_PRODUCT_TYPE_BUNDLE');?></span>
						<?php endif; ?>
					</h1>

					<?php if (!empty($this->item->tags->itemTags)) : ?>
						<?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
						<?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
					<?php endif; ?>

					<p class="short-desc"><?php echo $this->item->introtext; ?></p>
					<div class="description"><?php echo $this->item->fulltext; ?></div>

					<?php if(!empty($this->item->bundle_source)):?>
					<div class="bundled-products">
						<h3><?php echo JText::_('COM_DIGICOM_PRODUCT_BUNDLE_ITEMS_TITLE');?></h3>
						<ul>
							<?php foreach($this->item->bundleitems as $key=>$bitem):
								  $link = JRoute::_(DigiComSiteHelperRoute::getProductRoute($bitem->id,$bitem->catid, $bitem->language));

							?>
								<li>
									<a href="<?php echo $link; ?>"><?php echo $bitem->name; ?></a>
									<span class="label"><?php echo DigiComSiteHelperPrice::format_price($bitem->price, $conf->get('currency','USD'), true, $conf); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php endif; ?>

					<?php if ($this->configs->get('catalogue',0) == '0' and !$this->item->hide_public) : ?>
					<div class="well clearfix">
						<div class="product-price">
							<?php echo $price; ?>
							<br>
							<?php if ($this->configs->get('show_validity',1) == 1) : ?>
							<span>
								<small><?php echo JText::_('COM_DIGICOM_PRODUCT_VALIDITY'); ?> : <?php echo DigiComSiteHelperPrice::getProductValidityPeriod($this->item); ?></small>
							</span>
							<?php endif; ?>
						</div>

						<div class="addtocart-bar<?php echo ($conf->get('show_quantity',0) == 1 ? " input-append" : ''); ?>">
							<?php if($conf->get('show_quantity',0) == "1") {	?>
								<input id="quantity_<?php echo $this->item->id; ?>" type="number" name="qty" min="1" class="input-small" value="1" size="2" placeholder="<?php echo JText::_('COM_DIGICOM_QUANTITY'); ?>">
							<?php } ?>

							<?php if($conf->get('afteradditem',0) == "2") {	?>
								<button type="button" class="btn btn-warning" onclick="javascript:createPopUp(<?php echo $this->item->id; ?>,'<?php echo JRoute::_("index.php?option=com_digicom&view=cart"); ?>');"><i class="icon-cart"></i> <?php echo JText::_("COM_DIGICOM_ADD_TO_CART");?></button>
							<?php }else { ?>
								<button type="submit" class="btn btn-warning"><i class="icon-cart"></i> <?php echo JText::_('COM_DIGICOM_ADD_TO_CART'); ?></button>
							<?php } ?>

						</div>
					</div>
					<?php endif; ?>

				</div>
			</div>
			<input type="hidden" name="option" value="com_digicom"/>
			<input type="hidden" name="view" value="cart"/>
			<input type="hidden" name="task" value="cart.add"/>
			<input type="hidden" name="pid" value="<?php echo $this->item->id; ?>"/>
			<input type="hidden" name="cid" value="<?php echo $this->item->catid; ?>"/>
		</form>


		<?php echo DigiComSiteHelperDigicom::powered_by(); ?>
	</div>
</div>
