<!-- assets/templates/admin/metabox-meeting-info.php -->
<?php wp_nonce_field( 'wordpress_meetings_meeting_info_box', 'wordpress_meetings_meeting_info_nonce' ); ?>

<p><strong><label for="<?php echo $this->date_meta_key; ?>"><?php _e( 'Meeting Date', 'wordpress-meetings' ); ?></label></strong></p>

<p><input type="text" id="<?php echo $this->date_meta_key; ?>" name="<?php echo $this->date_meta_key; ?>" class="wp_datepicker" value="<?php echo $date; ?>" /></p>
