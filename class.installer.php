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
		// $table_name = $wpdb->prefix . "free_sms_setting";
		$table_name = "free_sms_setting";
		$my_db_version = '1.0.0';
		$charset_collate = $wpdb->get_charset_collate();

		if ( $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name ) {

			$sql = "CREATE TABLE `free_sms_setting` (
						`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
						`pageid` bigint(20) UNSIGNED NOT NULL,
						`foreground` char(7) NOT NULL,
						`background` char(7) NOT NULL,
						`freeSmsCount` smallint(5) UNSIGNED ZEROFILL NOT NULL,
						`freeSmsTime` char(8) DEFAULT NULL COMMENT '(factor | hours) of expire',
						`user_api` varchar(60) NOT NULL,
						`pass_api` varchar(255) NOT NULL,
						`phone_number` varchar(20) NOT NULL,
						`api_number` smallint(5) UNSIGNED ZEROFILL NOT NULL,
						`other_texts` text NOT NULL
					) ENGINE=INNODB $charset_collate;

					-- --------------------------------------------------------

					CREATE TABLE `free_sms_subject` (
						`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
						`subject` varchar(100) NOT NULL
					) ENGINE=INNODB $charset_collate;

					-- --------------------------------------------------------

					CREATE TABLE `free_sms_user` (
						`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
						`mobile` char(11) NOT NULL,
						`private_key` char(6) NOT NULL,
						`public_key` varchar(255) NOT NULL,
						`key_generate_date` datetime NOT NULL DEFAULT current_timestamp()
					) ENGINE=INNODB $charset_collate;

					-- --------------------------------------------------------
					CREATE TABLE `free_sms_profile` (
						`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
						`first_name` varchar(100) DEFAULT NULL,
						`last_name` varchar(100) DEFAULT NULL,
						`sex` tinyint(1) DEFAULT NULL COMMENT 'man=1, woman=0',
						`userid` bigint(20) UNSIGNED NOT NULL,
						FOREIGN KEY (`userid`) REFERENCES `free_sms_user` (`id`) ON DELETE CASCADE
					) ENGINE=INNODB $charset_collate;

					-- --------------------------------------------------------

					CREATE TABLE `free_sms_quote` (
						`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
						`subjectid` bigint(20) UNSIGNED NOT NULL,
						`message` varchar(255) NOT NULL,
						FOREIGN KEY (`subjectid`) REFERENCES `free_sms_subject` (`id`) ON DELETE CASCADE
					) ENGINE=INNODB $charset_collate;

					-- --------------------------------------------------------

					CREATE TABLE `free_sms_sms` (
						`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
						`userid` bigint(20) UNSIGNED NOT NULL,
						`quote` varchar(255) NOT NULL,
						`to` char(11) NOT NULL,
						`to_name` varchar(255) NOT NULL,
						`to_family` varchar(255) NOT NULL,
						`create_at` datetime NOT NULL DEFAULT current_timestamp(),
						`send_time` datetime NOT NULL DEFAULT current_timestamp(),
						FOREIGN KEY (`userid`) REFERENCES `free_sms_user` (`id`) ON DELETE CASCADE
					) ENGINE=INNODB $charset_collate;
			";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
			add_option('my_db_version', $my_db_version);
		}

	}

	private function pages () {
		$title = wp_strip_all_tags( 'SMS Page' );

		if(get_page_by_title( $title ) == NULL) {
			$my_post = array(
				'post_title'	=> $title,
				'post_content'	=> '[free_for_all text="ارسال پیامک رایگان"]',
				'post_status'	=> 'publish',
				'post_author'	=> 1,
				'post_type'		=> 'page',
			);

			$postId = wp_insert_post( $my_post );

			// set setting plugin
			global $wpdb;
			$wpdb->insert('free_sms_setting', 
				array(
					'pageid' 		=> $postId,
					'foreground' 	=> '#ffffff',
					'background' 	=> '#e27a4e',
					'freeSmsCount' 	=> '3',
					'freeSmsTime' 	=> '1|24',
					'user_api' 		=> 'mpi_blog',
					'pass_api' 		=> 'blog',
					'phone_number' 	=> '30004388511788',
					'api_number' 	=> '5',
					'other_texts'	=> serialize(
						array(
							"signature" => "این سرویس کادویی از طرف میزبان پیامک میباشد.",
							"profile" => array(),
							"gift_text" => "میزبان پیامک مفتخر است که به هر شماره 3 پیامک رایگان در هر 24 ساعت ارائه دهد. کد استفاده از سرویس : %s"
						)
					)
				),
				array(
					'%d',
					'%s',
					'%s',
					'%d',
					'%s',
					'%s',
					'%s',
					'%s',
					'%d',
					'%s'
				) 
			);

		}
	}
}