<?php get_header(); ?>

<div class="container">
    <h1><?php echo __('Fluid Connector to Holdfast Part # Conversion', 'wcd'); ?></h1>
	<div id="repeater">

		<!-- Repeater Heading -->
		<div class="repeater-heading">
			<button class="btn btn-primary repeater-add-btn" id="addMore"><?php echo __('Start Conversion', 'wcd'); ?></button>
		</div>
        
        <!-- Repeater Items -->
		<div class="items" data-group="test">
            
			<!-- Repeater Content -->
			<div class="item-content">

				<div class="form-group">
					<label class="control-label" for="inputEmail"><?php echo __('Fluid Connector Part', 'wcd'); ?></label>
					<div class="input-wrapper ">
						<input class="form-control" data-name="fluconPart" id="fluconPart" placeholder="<?php echo __('Part #', 'wcd'); ?>" type="text">
					</div>
				</div><!-- .form-group -->

				<div class="form-group">
					<label class="control-label" for="inputEmail"><?php echo __('Holdfast Part', 'wcd'); ?></label>
					<div class="input-wrapper ">
						<input class="form-control" data-name="hfpPart" data-skip-name="true" id="hfpPart" placeholder="<?php echo __('Part #', 'wcd'); ?>" type="text">
					</div>
				</div><!-- .form-group -->

			</div><!-- .item-content -->
            
            <!-- Repeater Remove Btn -->
			<div class="pull-right repeater-remove-btn">
			<button class="btn btn-primary add-another-conversion" onclick="addNew();"><i class="im im-plus"></i></button>
				<button class="btn btn-danger" id="remove-btn" onclick="jQuery(this).parents('.items').remove()"><i class="im im-minus"></i></button>
			</div><!-- .remove-btn -->

		</div><!-- .items -->

	</div><!-- #repeater -->
</div><!-- .container -->

<?php get_footer(); ?>
