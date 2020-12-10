<?php 
/**
 * 
 */
class Installer
{
	
	public function run() {
		$this->db();
		$this->pages();
	}

	private function db () {
		global $wpdb;
		$table_name = $wpdb->prefix . "my_products";
		$my_products_db_version = '1.0.0';
		$charset_collate = $wpdb->get_charset_collate();

		if ( $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name ) {

			$sql = "CREATE TABLE $table_name (
					ID mediumint(9) NOT NULL AUTO_INCREMENT,
					`product-model` text NOT NULL,
					`product-name` text NOT NULL,
					`product-description` int(9) NOT NULL,
					PRIMARY KEY (ID)
			) $charset_collate;";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
			add_option('my_db_version', $my_products_db_version);
		}

	}

	private function pages () {
		$title = wp_strip_all_tags( 'My Custom Page' );

		if(get_page_by_title( $title ) == NULL) {
			$my_post = array(
				'post_title'	=> $title,
				'post_content'	=> 'My custom page content',
				'post_status'	=> 'publish',
				'post_author'	=> 1,
				'post_type'		=> 'page',
			);

			wp_insert_post( $my_post );
		}

	}
}