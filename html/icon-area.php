<?php require_once( compat_get_plugin_dir( 'wptouch' ) . '/include/icons.php' ); ?>
<?php global $wptouch_settings; ?>

<div class="metabox-holder" id="available_icons">
	<div class="postbox">
		<h3><?php _e( "Default &amp; Custom Icon Pool", "wptouch" ); ?></h3>

			<div class="wptouch-left-content">
				<p><?php _e( "You can select which icons will be displayed beside pages enabled below in the Site Menu section.", "wptouch" ); ?></p>
				<p><?php _e( "To add icons to the pool, simply upload a 60x60 .png, .jpeg or .gif image from your computer.", "wptouch" ); ?></p>
				<p><?php echo sprintf( __( "Need help? You can use %sthis easy online icon generator%s to make one.", "wptouch"), "<a href='http://www.flavorstudios.com/iphone-icon-generator' target='_blank'>", "</a>" ); ?></p>
				<p><?php echo sprintf( __( "These files will be stored in your wp-content %s/uploads/wptouch/custom-icons%s folder.", "wptouch"), "<strong>", "</strong>" ); ?></p>
				<p><?php echo sprintf( __( "If upload fails, you can create the folder yourself using FTP and try again.", "wptouch"), "<strong>", "</strong>" ); ?></p>
						
				<div id="upload_button"></div> 

			<div id="upload_response"></div>
				<div id="upload_progress" style="display:none">
					<p><img src="<?php echo compat_get_plugin_url( 'wptouch' ) . '/images/progress.gif'; ?>" alt="" /> <?php _e( "Uploading..."); ?></p>
				</div>
								
			</div><!-- wptouch-left-content -->
		
	<div class="wptouch-right-content">	
		<?php bnc_show_icons(); ?>
	</div>
	
	<div class="wptouch-clearer"></div>
	</div><!-- postbox -->
</div><!-- metabox -->