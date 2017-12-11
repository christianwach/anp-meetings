<!-- assets/templates/admin/metabox-meeting-info.php -->
<?php wp_nonce_field( 'wordpress_meetings_meeting_info_box', 'wordpress_meetings_meeting_info_nonce' ); ?>

<p><strong><label for="<?php echo $this->date_meta_key; ?>"><?php _e( 'Meeting Date', 'wordpress-meetings' ); ?></label></strong><br />

<input type="text" id="<?php echo $this->date_meta_key; ?>" name="<?php echo $this->date_meta_key; ?>" class="wp_datepicker" value="<?php echo $date; ?>" /></p>

<p><strong><label for="<?php echo $this->start_time_meta_key; ?>"><?php _e( 'Start Time', 'wordpress-meetings' ); ?></label></strong><br />

<input type="text" id="<?php echo $this->start_time_meta_key; ?>" name="<?php echo $this->start_time_meta_key; ?>" class="wp_timepicker" value="<?php echo $start_time; ?>" /></p>

<p><strong><label for="<?php echo $this->end_time_meta_key; ?>"><?php _e( 'End Time', 'wordpress-meetings' ); ?></label></strong><br />

<input type="text" id="<?php echo $this->end_time_meta_key; ?>" name="<?php echo $this->end_time_meta_key; ?>" class="wp_timepicker" value="<?php echo $end_time; ?>" /></p>
