<!-- assets/templates/admin/metabox-meeting-info.php -->
<?php wp_nonce_field( 'wordpress_meetings_proposal_info_box', 'wordpress_meetings_proposal_info_nonce' ); ?>

<p><strong><label for="<?php echo $this->status_meta_key; ?>"><?php _e( 'Status', 'wordpress-meetings' ); ?></label></strong><br />

<?php wp_dropdown_categories( array(
	'taxonomy' => 'proposal_status',
	'show_option_none' => __( 'No status', 'wordpress-meetings' ),
	'id' => $this->status_meta_key,
	'name' => $this->status_meta_key,
	'hide_empty' => 0,
	'hierarchical' => 1,
	'selected' => $status,
) ); ?></p>

<p><strong><label for="<?php echo $this->date_accepted_meta_key; ?>"><?php _e( 'Date Accepted', 'wordpress-meetings' ); ?></label></strong><br />

<input type="text" id="<?php echo $this->date_accepted_meta_key; ?>" name="<?php echo $this->date_accepted_meta_key; ?>" class="wp_datepicker" value="<?php echo $date_accepted; ?>" /></p>

<p><strong><label for="<?php echo $this->date_effective_meta_key; ?>"><?php _e( 'Date Effective', 'wordpress-meetings' ); ?></label></strong><br />

<input type="text" id="<?php echo $this->date_effective_meta_key; ?>" name="<?php echo $this->date_effective_meta_key; ?>" class="wp_datepicker" value="<?php echo $date_effective; ?>" /></p>

