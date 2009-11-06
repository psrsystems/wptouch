<?php global $wptouch_settings; ?>

<div class="metabox-holder">
	<div class="postbox">
		<h3><span class="style-options"></span><?php _e( "Style &amp; Color Options", "wptouch" ); ?></h3>

			<div class="left-content skins-left-content">
				<p><?php _e( "Here you can customize some of the more prominent style features of WPtouch.", "wptouch" ); ?></p>
			</div>
		
			<div class="right-content skins-fixed">


 <!-- Default skin -->
 
		<div class="skins-desc" id="default-skin">
			<p><?php _e( "The default WPtouch skin emulates a native iPhone application.", "wptouch" ); ?></p>
			<ul class="wptouch-make-li-italic">
					<li><select name="style-background">
							<option <?php if ($wptouch_settings['style-background'] == "classic-wptouch-bg") echo " selected"; ?> value="classic-wptouch-bg">
								<?php _e( "Classic", "wptouch" ); ?>
							</option>
							<option <?php if ($wptouch_settings['style-background'] == "horizontal-wptouch-bg") echo " selected"; ?> value="horizontal-wptouch-bg">
								<?php _e( "Horizontal Grey", "wptouch" ); ?>
							</option>
							<option <?php if ($wptouch_settings['style-background'] == "diagonal-wptouch-bg") echo " selected"; ?> value="diagonal-wptouch-bg">
								<?php _e( "Diagonal Grey", "wptouch" ); ?>
							</option>
							<option <?php if ($wptouch_settings['style-background'] == "skated-wptouch-bg") echo " selected"; ?> value="skated-wptouch-bg">
								<?php _e( "Skated Concrete", "wptouch" ); ?>
							</option>
							<option <?php if ($wptouch_settings['style-background'] == "argyle-wptouch-bg") echo " selected"; ?> value="argyle-wptouch-bg">
								<?php _e( "Argyle Tie", "wptouch" ); ?>
							</option>
							<option <?php if ($wptouch_settings['style-background'] == "grid-wptouch-bg") echo " selected"; ?> value="grid-wptouch-bg">
								<?php _e( "Thatches", "wptouch" ); ?>
							</option>
						</select>
						<?php _e( "Background", "wptouch" ); ?>
					</li> 
				<li>#<input type="text" id="header-text-color" name="header-text-color" value="<?php echo $wptouch_settings['header-text-color']; ?>" /><?php _e( "Title text color", "wptouch" ); ?></li>
				<li>#<input type="text" id="header-background-color" name="header-background-color" value="<?php echo $wptouch_settings['header-background-color']; ?>" /><?php _e( "Header background color", "wptouch" ); ?></li>
				<li>#<input type="text" id="header-border-color" name="header-border-color" value="<?php echo $wptouch_settings['header-border-color']; ?>" /><?php _e( "Sub-header background color", "wptouch" ); ?></li>
				<li>#<input type="text" id="link-color" name="link-color" value="<?php echo $wptouch_settings['link-color']; ?>" /><?php _e( "Site-wide links color", "wptouch" ); ?></li>
			</ul> 
			<img src="<?php echo compat_get_plugin_url( 'wptouch' ); ?>/images/default.jpg" alt="" />
		</div>
		
		</div><!-- right content -->
	<div class="bnc-clearer"></div>
	</div><!-- postbox -->
</div><!-- metabox -->