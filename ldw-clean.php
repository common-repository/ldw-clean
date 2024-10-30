<?php
/*
Plugin Name:	LDW Clean
Description:	Deletes redundant data from the WordPress database, speeds up your site, and reduces the database size!
Version:    	1.0.0
Author:     	Lake District Walks
Text Domain: 	ldw-clean
Domain Path:	/lang
*/

add_action( 'admin_menu', 'ldw_clean_add_admin_menu' );
add_action( 'admin_init', 'ldw_clean_settings_init' );

function ldw_clean_add_admin_menu(  ) { 
	$page = add_options_page( 'LDW Clean', 'LDW Clean', 'manage_options', 'ldw_clean', 'ldw_clean_options_page' );
    add_action( 'admin_print_styles-' . $page, 'ldw_clean_admin_styles' );
}

function ldw_clean_admin_styles() {
       wp_enqueue_style( 'ldw_clean_stylesheet' );
}

function ldw_clean_settings_init(  ) { 
    wp_register_style( 'ldw_clean_stylesheet', plugins_url('style.css', __FILE__) );
	register_setting( 'ldw_clean_plugin_page', 'ldw_clean_settings', 'ldw_clean_settings_check' );
	add_settings_section(
		'ldw_clean_plugin_page_section', 
		__( '', 'ldw-clean' ), 
		'ldw_clean_settings_section_callback', 
		'ldw_clean_plugin_page'
	);
	add_settings_field( 
		'ldw_clean_checkbox_field_0', 
		__( 'Comments That Failed Moderation: ' . ldw_clean_count('moderated'), 'ldw-clean' ), 
		'ldw_clean_checkbox_field_0_render', 
		'ldw_clean_plugin_page', 
		'ldw_clean_plugin_page_section'
	);
	add_settings_field( 
		'ldw_clean_checkbox_field_1', 
		__( 'Orphan Commentmeta: ' . ldw_clean_count('commentmeta'), 'ldw-clean' ), 
		'ldw_clean_checkbox_field_1_render', 
		'ldw_clean_plugin_page', 
		'ldw_clean_plugin_page_section' 
	);
	add_settings_field( 
		'ldw_clean_checkbox_field_2', 
		__( 'Orphan Postmeta: ' . ldw_clean_count('postmeta'), 'ldw-clean' ), 
		'ldw_clean_checkbox_field_2_render', 
		'ldw_clean_plugin_page', 
		'ldw_clean_plugin_page_section' 
	);
	add_settings_field( 
		'ldw_clean_checkbox_field_3', 
		__( 'Orphan Relationships: ' . ldw_clean_count('relationships'), 'ldw-clean' ), 
		'ldw_clean_checkbox_field_3_render', 
		'ldw_clean_plugin_page', 
		'ldw_clean_plugin_page_section' 
	);
	add_settings_field( 
		'ldw_clean_checkbox_field_4', 
		__( 'Post Auto-Drafts: ' . ldw_clean_count('autodraft'), 'ldw-clean' ), 
		'ldw_clean_checkbox_field_4_render', 
		'ldw_clean_plugin_page', 
		'ldw_clean_plugin_page_section' 
	);
	add_settings_field( 
		'ldw_clean_checkbox_field_5', 
		__( 'Post Drafts: ' . ldw_clean_count('draft'), 'ldw-clean' ), 
		'ldw_clean_checkbox_field_5_render', 
		'ldw_clean_plugin_page', 
		'ldw_clean_plugin_page_section' 
	);
	add_settings_field( 
		'ldw_clean_checkbox_field_6', 
		__( 'Post Old Slugs: ' . ldw_clean_count('oldslug'), 'ldw-clean' ), 
		'ldw_clean_checkbox_field_6_render', 
		'ldw_clean_plugin_page', 
		'ldw_clean_plugin_page_section' 
	);
	add_settings_field( 
		'ldw_clean_checkbox_field_7', 
		__( 'Post Revisions: ' . ldw_clean_count('revision'), 'ldw-clean' ), 
		'ldw_clean_checkbox_field_7_render', 
		'ldw_clean_plugin_page', 
		'ldw_clean_plugin_page_section' 
	);
	add_settings_field( 
		'ldw_clean_checkbox_field_8', 
		__( 'Spam Comments: ' . ldw_clean_count('spam'), 'ldw-clean' ), 
		'ldw_clean_checkbox_field_8_render', 
		'ldw_clean_plugin_page', 
		'ldw_clean_plugin_page_section' 
	);
	add_settings_field( 
		'ldw_clean_checkbox_field_9', 
		__( 'Transient Options: ' . ldw_clean_count('options'), 'ldw-clean' ), 
		'ldw_clean_checkbox_field_9_render', 
		'ldw_clean_plugin_page', 
		'ldw_clean_plugin_page_section' 
	);
	add_settings_field( 
		'ldw_clean_checkbox_field_10', 
		__( 'Trash Comments: ' . ldw_clean_count('trash'), 'ldw-clean' ), 
		'ldw_clean_checkbox_field_10_render', 
		'ldw_clean_plugin_page', 
		'ldw_clean_plugin_page_section' 
	);
	add_settings_field( 
		'ldw_clean_checkbox_field_11', 
		__( 'Optimise Database: ' . sprintf("%0.1f", total_size()) . ' KB', 'ldw-clean' ), 
		'ldw_clean_checkbox_field_11_render', 
		'ldw_clean_plugin_page', 
		'ldw_clean_plugin_page_section' 
	);
}

function ldw_clean_settings_check( $input ) { 
	// checks which fields were selected after form submit
	// does not check what was selected from saved settings
	// need to be in this order to catch orphans
	if ($input["ldw_clean_checkbox_field_0"] == true) {
		ldw_clean('moderated');
	}
	if ($input["ldw_clean_checkbox_field_4"] == true) {
		ldw_clean('autodraft');
	}
	if ($input["ldw_clean_checkbox_field_5"] == true) {
		ldw_clean('draft');
	}
	if ($input["ldw_clean_checkbox_field_6"] == true) {
		ldw_clean('oldslug');
	}
	if ($input["ldw_clean_checkbox_field_7"] == true) {
		ldw_clean('revision');
	}
	if ($input["ldw_clean_checkbox_field_8"] == true) {
		ldw_clean('spam');
	}
	if ($input["ldw_clean_checkbox_field_10"] == true) {
		ldw_clean('trash');
	}
	if ($input["ldw_clean_checkbox_field_1"] == true) {
		ldw_clean('commentmeta');
	}
	if ($input["ldw_clean_checkbox_field_2"] == true) {
		ldw_clean('postmeta');
	}
	if ($input["ldw_clean_checkbox_field_3"] == true) {
		ldw_clean('relationships');
	}
	if ($input["ldw_clean_checkbox_field_9"] == true) {
		ldw_clean('options');
	}
	if ($input["ldw_clean_checkbox_field_11"] == true) {
		ldw_clean_optimise();
	}
	return $input;
}

function ldw_clean_checkbox_field_0_render(  ) { 
	$options = get_option( 'ldw_clean_settings' );
	?>
	<input type='checkbox' name='ldw_clean_settings[ldw_clean_checkbox_field_0]' <?php checked( $options['ldw_clean_checkbox_field_0'], 1 ); ?> value='1' />
	<?php
}

function ldw_clean_checkbox_field_1_render(  ) { 
	$options = get_option( 'ldw_clean_settings' );
	?>
	<input type='checkbox' name='ldw_clean_settings[ldw_clean_checkbox_field_1]' <?php checked( $options['ldw_clean_checkbox_field_1'], 1 ); ?> value='1' />
	<?php
}

function ldw_clean_checkbox_field_2_render(  ) { 
	$options = get_option( 'ldw_clean_settings' );
	?>
	<input type='checkbox' name='ldw_clean_settings[ldw_clean_checkbox_field_2]' <?php checked( $options['ldw_clean_checkbox_field_2'], 1 ); ?> value='1' />
	<?php
}

function ldw_clean_checkbox_field_3_render(  ) { 
	$options = get_option( 'ldw_clean_settings' );
	?>
	<input type='checkbox' name='ldw_clean_settings[ldw_clean_checkbox_field_3]' <?php checked( $options['ldw_clean_checkbox_field_3'], 1 ); ?> value='1' />
	<?php
}

function ldw_clean_checkbox_field_4_render(  ) { 
	$options = get_option( 'ldw_clean_settings' );
	?>
	<input type='checkbox' name='ldw_clean_settings[ldw_clean_checkbox_field_4]' <?php checked( $options['ldw_clean_checkbox_field_4'], 1 ); ?> value='1' />
	<?php
}

function ldw_clean_checkbox_field_5_render(  ) { 
	$options = get_option( 'ldw_clean_settings' );
	?>
	<input type='checkbox' name='ldw_clean_settings[ldw_clean_checkbox_field_5]' <?php checked( $options['ldw_clean_checkbox_field_5'], 1 ); ?> value='1' />
	<?php
}

function ldw_clean_checkbox_field_6_render(  ) { 
	$options = get_option( 'ldw_clean_settings' );
	?>
	<input type='checkbox' name='ldw_clean_settings[ldw_clean_checkbox_field_6]' <?php checked( $options['ldw_clean_checkbox_field_6'], 1 ); ?> value='1' />
	<?php
}

function ldw_clean_checkbox_field_7_render(  ) { 
	$options = get_option( 'ldw_clean_settings' );
	?>
	<input type='checkbox' name='ldw_clean_settings[ldw_clean_checkbox_field_7]' <?php checked( $options['ldw_clean_checkbox_field_7'], 1 ); ?> value='1' />
	<?php
}

function ldw_clean_checkbox_field_8_render(  ) { 
	$options = get_option( 'ldw_clean_settings' );
	?>
	<input type='checkbox' name='ldw_clean_settings[ldw_clean_checkbox_field_8]' <?php checked( $options['ldw_clean_checkbox_field_8'], 1 ); ?> value='1' />
	<?php
}

function ldw_clean_checkbox_field_9_render(  ) { 
	$options = get_option( 'ldw_clean_settings' );
	?>
	<input type='checkbox' name='ldw_clean_settings[ldw_clean_checkbox_field_9]' <?php checked( $options['ldw_clean_checkbox_field_9'], 1 ); ?> value='1' />
	<?php
}

function ldw_clean_checkbox_field_10_render(  ) { 
	$options = get_option( 'ldw_clean_settings' );
	?>
	<input type='checkbox' name='ldw_clean_settings[ldw_clean_checkbox_field_10]' <?php checked( $options['ldw_clean_checkbox_field_10'], 1 ); ?> value='1' />
	<?php
}

function ldw_clean_checkbox_field_11_render(  ) { 
	$options = get_option( 'ldw_clean_settings' );
	?>
	<input type='checkbox' name='ldw_clean_settings[ldw_clean_checkbox_field_11]' <?php checked( $options['ldw_clean_checkbox_field_11'], 1 ); ?> value='1' />
	<?php
}

function ldw_clean_settings_section_callback(  ) { 
	echo __( '', 'ldw-clean' );
}

function total_size(  ) { 
	global $wpdb;
	$total_size = 0;
	$ldw_clean_sql = 'SHOW TABLE STATUS FROM `'.DB_NAME.'`';
	$result = $wpdb->get_results($ldw_clean_sql, ARRAY_A);
	foreach ($result as $row) {
		$table_size = $row['Data_length'] + $row['Index_length'];
		$table_size = $table_size / 1024;
		$table_size = sprintf("%0.1f",$table_size);
		$every_size = $row['Data_length'] + $row['Index_length'];
		$every_size = $every_size / 1024;
		$total_size += $every_size;
	}
	return $total_size;
}

function ldw_clean_count($type){
	global $wpdb;
	switch($type){
		case "moderated":
			$ldw_clean_sql = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = '0'";
			$count = $wpdb->get_var($ldw_clean_sql);
			break;
		case "commentmeta":
			$ldw_clean_sql = "SELECT COUNT(*) FROM $wpdb->commentmeta WHERE comment_id NOT IN (SELECT comment_id FROM $wpdb->comments)";
			$count = $wpdb->get_var($ldw_clean_sql);
			break;
		case "postmeta":
			$ldw_clean_sql = "SELECT COUNT(*) FROM $wpdb->postmeta pm LEFT JOIN $wpdb->posts wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL";
			$count = $wpdb->get_var($ldw_clean_sql);
			break;
		case "relationships":
			$ldw_clean_sql = "SELECT COUNT(*) FROM $wpdb->term_relationships WHERE term_taxonomy_id=1 AND object_id NOT IN (SELECT id FROM $wpdb->posts)";
			$count = $wpdb->get_var($ldw_clean_sql);
			break;
		case "autodraft":
			$ldw_clean_sql = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'auto-draft'";
			$count = $wpdb->get_var($ldw_clean_sql);
			break;
		case "draft":
			$ldw_clean_sql = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'draft'";
			$count = $wpdb->get_var($ldw_clean_sql);
			break;
		case "oldslug":
			$ldw_clean_sql = "SELECT COUNT(*) FROM $wpdb->postmeta WHERE meta_key = '_wp_old_slug'";
			$count = $wpdb->get_var($ldw_clean_sql);
			break;
		case "revision":
			$ldw_clean_sql = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'revision'";
			$count = $wpdb->get_var($ldw_clean_sql);
			break;
		case "spam":
			$ldw_clean_sql = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = 'spam'";
			$count = $wpdb->get_var($ldw_clean_sql);
			break;
		case "options":
			$ldw_clean_sql = "SELECT COUNT(*) FROM $wpdb->options WHERE option_name LIKE '%_transient_%'"; //use _transient_% to not count _site_transient_
			$count = $wpdb->get_var($ldw_clean_sql);
			// there are always 7 transient options after the plugin has run, so remove them from the count
			if ($count >= 7) {
				$count = $count - 7;
			}
			break;
		case "trash":
			$ldw_clean_sql = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = 'trash'";
			$count = $wpdb->get_var($ldw_clean_sql);
			break;
	}
	return $count;
}

function ldw_clean($type){
	global $wpdb;
	switch($type){
		case "moderated":
			$ldw_clean_sql = "DELETE FROM $wpdb->comments WHERE comment_approved = '0'";
			$wpdb->query($ldw_clean_sql);
			break;
		case "commentmeta":
			$ldw_clean_sql = "DELETE FROM $wpdb->commentmeta WHERE comment_id NOT IN (SELECT comment_id FROM $wpdb->comments)";
			$wpdb->query($ldw_clean_sql);
			break;
		case "postmeta":
			$ldw_clean_sql = "DELETE pm FROM $wpdb->postmeta pm LEFT JOIN $wpdb->posts wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL";
			$wpdb->query($ldw_clean_sql);
			break;
		case "relationships":
			$ldw_clean_sql = "DELETE FROM $wpdb->term_relationships WHERE term_taxonomy_id=1 AND object_id NOT IN (SELECT id FROM $wpdb->posts)";
			$wpdb->query($ldw_clean_sql);
			break;
		case "autodraft":
			$ldw_clean_sql = "DELETE FROM $wpdb->posts WHERE post_status = 'auto-draft'";
			$wpdb->query($ldw_clean_sql);
			break;
		case "draft":
			$ldw_clean_sql = "DELETE FROM $wpdb->posts WHERE post_status = 'draft'";
			$wpdb->query($ldw_clean_sql);
			break;
		case "oldslug":
			$ldw_clean_sql = "DELETE FROM $wpdb->postmeta WHERE meta_key = '_wp_old_slug'";
			$wpdb->query($ldw_clean_sql);
			break;
		case "revision":
			$ldw_clean_sql = "DELETE FROM $wpdb->posts WHERE post_type = 'revision'";
			$wpdb->query($ldw_clean_sql);
			break;
		case "spam":
			$ldw_clean_sql = "DELETE FROM $wpdb->comments WHERE comment_approved = 'spam'";
			$wpdb->query($ldw_clean_sql);
			break;
		case "options":
			$ldw_clean_sql = "DELETE FROM $wpdb->options WHERE option_name LIKE '%_transient_%'"; //use _transient_% to leave _site_transient_
			$wpdb->query($ldw_clean_sql);
			break;
		case "trash":
			$ldw_clean_sql = "DELETE FROM $wpdb->comments WHERE comment_approved = 'trash'";
			$wpdb->query($ldw_clean_sql);
			break;
	}
}

function ldw_clean_optimise(){
	global $wpdb;
	$ldw_clean_sql = 'SHOW TABLE STATUS FROM `'.DB_NAME.'`';
	$result = $wpdb->get_results($ldw_clean_sql, ARRAY_A);
	foreach ($result as $row) {
		$ldw_clean_sql = 'OPTIMIZE TABLE '.$row['Name'];
		$wpdb->query($ldw_clean_sql);
	}
}

function ldw_validation_notice(){
    global $pagenow;
    if ($pagenow == 'options-general.php' && $_GET['page'] == 'ldw_clean') {
		if ( (isset($_GET['updated']) && $_GET['updated'] == 'true') || (isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true') ) {
			unset($_GET['settings-updated']);
			$update_message = 'Database cleaned.';
			add_settings_error('general', 'settings_updated', $update_message, 'updated');
		}
	}
}
add_action( 'admin_notices', 'ldw_validation_notice');

function ldw_clean_options_page(  ) { 
	?><h1>LDW Clean</h1>
	<table style="width: 100%;">
		<tr>
			<td style="width: 50%;">
				<form action='options.php' method='post'>
					<?php
					settings_fields( 'ldw_clean_plugin_page' );
					do_settings_sections( 'ldw_clean_plugin_page' );
					submit_button( 'Clean Database' );
					?>
				</form>
			</td>
			<td>
				<div style="text-align: center;">
					<h4>Thanks for using LDW Clean :)</h4>
				</div>
			</td>
		</tr>
	</table>
	<?php
}
?>
