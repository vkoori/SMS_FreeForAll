<?php 

/**
 * 
 */
class smsQueries
{
	/**
	 * 
	 */
	public function setting() {
		global $wpdb;
		$setting = $wpdb->get_results("SELECT * FROM `free_sms_setting` WHERE `id`=1");
		return $setting[0];
	}

	/**
	 * 
	 */
	public function update_setting($data) {
		global $wpdb;
		$setting = $wpdb->update('free_sms_setting', 
			$data, array(
				'id' => 1
			), array(
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

		return $setting;
	}

	/**
	 * 
	 */
	public function setUser($data) {

		global $wpdb;
		$user = $wpdb->get_results("SELECT * FROM `free_sms_user` WHERE `mobile`={$data['mobile']}");

		if (sizeof($user) == 0) {
			$wpdb->insert('free_sms_user', 
				$data, array(
					'%s',
					'%s',
					'%s',
					'%s'
				) 
			);
			$user = $data;
		} elseif (time()-strtotime($user[0]->key_generate_date) > 60*2) {
			$wpdb->update('free_sms_user', 
				$data, array(
					'mobile' => $data['mobile']
				), array(
					'%s',
					'%s',
					'%s',
					'%s'
				)
			);
			$user = $data;
		} else {
			$user = $user[0];
		}

		return $user;
	}

	/**
	 * 
	 */
	public function sendsms($data) {
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://my.mizbansms.ir/wssms.asmx/sendsms",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => http_build_query($data),
			CURLOPT_HTTPHEADER => array(
				"Content-Type: application/x-www-form-urlencoded"
			),
		));

		$response = curl_exec($curl);
		if (curl_errno($curl))
			$response = curl_error($curl);

		curl_close($curl);
		return $response;

// 		require dirname(__FILE__).'/vendor/autoload.php';
// 		$client = new \GuzzleHttp\Client();
// 		$client->post(
// 		    'http://my.mizbansms.ir/wssms.asmx/sendsms',
// 		    array(
// 		        'form_params' => $data
// 		    )
// 		);
// 		return 1;
	}

	/**
	 * 
	 */
	public function getUser($colname, $colvalue) {
		global $wpdb;
		$user = $wpdb->get_results("SELECT * FROM `free_sms_user` WHERE `{$colname}`='{$colvalue}'");

		return $user[0];
	}

	/**
	 * 
	 */
	public function expireUser($mobile) {
		global $wpdb;
		$user = $wpdb->update('free_sms_user'
					, array(
						'key_generate_date' => "2001-01-01 12:00:00"
					) , array(
						'mobile' => $mobile
					), array(
						'%s'
					)
				);

		return $user;
	}

	/**
	 * 
	 */
	public function getProfile($userid) {
		global $wpdb;
		$user = $wpdb->get_results("SELECT * FROM `free_sms_profile` WHERE `userid`='{$userid}'");

		return $user;
	}


	/**
	 * 
	 */
	public function setProfile($data) {
		global $wpdb;
		$profile = $wpdb->insert('free_sms_profile', 
						$data, array(
							'%s',
							'%s',
							'%d',
							'%d'
						) 
					);

		return $profile;
	}

	/**
	 * 
	 */
	public function updateProfile($data) {
		global $wpdb;
		$profile = $wpdb->update('free_sms_profile', 
					$data , 
					array(
						'userid' => $_POST['userid']
					)
				);

		return $profile;
	}

	/**
	 * 
	 */
	public function get_subjects() {
		global $wpdb;
		$subjects = $wpdb->get_results("SELECT * FROM `free_sms_subject`");

		return $subjects;
	}

	/**
	 * 
	 */
	public function texts_with_subjectid($sid) {
		global $wpdb;
		$texts = $wpdb->get_results("SELECT * FROM `free_sms_quote` WHERE `subjectid`=$sid");

		return $texts;
	}

	/**
	 * 
	 */
	public function texts_with_id($id) {
		global $wpdb;
		$texts = $wpdb->get_results("SELECT * FROM `free_sms_quote` WHERE `id`=$id");

		return $texts;
	}

	/**
	 * 
	 */
	public function all_texts() {
		global $wpdb;
		$texts = $wpdb->get_results("SELECT `free_sms_quote`.`id`, `message`, `subject` FROM `free_sms_quote`
									INNER JOIN `free_sms_subject`
									ON `subjectid`=`free_sms_subject`.`id`");

		return $texts;
	}

	/**
	 * 
	 */
	public function get_subject($subject) {
		global $wpdb;
		$subject = $wpdb->get_results("SELECT * FROM `free_sms_subject` WHERE `subject`='{$subject}'");

		return $subject;
	}

	/**
	 * 
	 */
	public function overwite_subjects($subjects) {
		global $wpdb;
		$wpdb->query('DELETE FROM `free_sms_subject`');
		$wpdb->query('ALTER TABLE `free_sms_subject` AUTO_INCREMENT = 1');

		$subjects = $wpdb->get_results("INSERT INTO `free_sms_subject` (`subject`) VALUES {$subjects}");

		return $subjects;
	}

	/**
	 * 
	 */
	public function insert_subjects($subjects) {
		global $wpdb;
		$subjects = $wpdb->get_results("INSERT INTO `free_sms_subject` (`subject`) VALUES {$subjects}");

		return $subjects;
	}

	/**
	 * 
	 */
	public function overwite_quotes($quotes) {
		global $wpdb;
		$wpdb->query('DELETE FROM `free_sms_quote`');
		$wpdb->query('ALTER TABLE `free_sms_quote` AUTO_INCREMENT = 1');

		$quotes = $wpdb->get_results("INSERT INTO `free_sms_quote` (`subjectid`,`message`) VALUES {$quotes}");

		return $quotes;
	}

	/**
	 * 
	 */
	public function insert_quotes($quotes) {
		global $wpdb;
		$quotes = $wpdb->get_results("INSERT INTO `free_sms_quote` (`subjectid`,`message`) VALUES {$quotes}");

		return $quotes;
	}

	/**
	 * 
	 */
	public function remove_quote($id) {
		global $wpdb;
		$quote = $wpdb->query("DELETE FROM `free_sms_quote` WHERE `id`={$id}");

		return $quote;
	}

	/**
	 * 
	 */
	public function update_quote($id, $message) {
		global $wpdb;
		$quote = $wpdb->get_results("UPDATE `free_sms_quote` SET `message`='{$message}' WHERE `id`={$id}");

		return $quote;
	}

	/**
	 * 
	 */
	public function get_quote($id) {
		global $wpdb;
		$quote = $wpdb->get_results("SELECT * FROM `free_sms_quote` WHERE `id`={$id}");

		return $quote;
	}

	/**
	 * 
	 */
	public function count_of_use_sms($userid) {
		global $wpdb;
		$smsCount = $wpdb->get_results("SELECT COUNT(`id`) AS `count` FROM `free_sms_sms` WHERE `userid`={$userid}");

		return $smsCount[0];
	}

	/**
	 * 
	 */
	public function count_of_use_sms_mobile($mobile) {
		global $wpdb;
		$smsCount = $wpdb->get_results("SELECT COUNT(`id`) AS `count` FROM `free_sms_sms` WHERE `userid`=(SELECT `id` FROM `free_sms_user` WHERE `mobile`={$mobile})");

		return $smsCount[0];
	}

	/**
	 * 
	 */
	public function count_of_use_sms2($userid, $date) {
		global $wpdb;
		$smsCount = $wpdb->get_results("SELECT COUNT(`id`) AS `count` FROM `free_sms_sms` WHERE `userid`={$userid} AND `create_at`>'{$date}'");

		return $smsCount[0];
	}

	/**
	 * 
	 */
	public function count_of_use_sms_mobile2($mobile, $date) {
		global $wpdb;
		$smsCount = $wpdb->get_results("SELECT COUNT(`id`) AS `count` FROM `free_sms_sms` WHERE `userid`=(SELECT `id` FROM `free_sms_user` WHERE `mobile`={$mobile}) AND `create_at`>'{$date}'");

		return $smsCount[0];
	}


	/**
	 * 
	 */
	public function insert_sms($data) {
		global $wpdb;
		$sms = $wpdb->insert('free_sms_sms', 
					$data, array(
						'%d',
						'%s',
						'%s',
						'%s',
						'%s'
					) 
				);

		return $sms;
	}

	/**
	 * 
	 */
	public function report_sms() {
		global $wpdb;
		$messages = $wpdb->get_results("SELECT `quote`, `to`, `to_name`, `to_family`, `send_time`, `mobile`, `first_name`, `last_name`, `sex`
									FROM `free_sms_sms` 
									INNER JOIN `free_sms_user`
									ON `free_sms_user`.`id`=`free_sms_sms`.`userid`
									LEFT JOIN `free_sms_profile`
									ON `free_sms_profile`.`userid`=`free_sms_sms`.`userid`");

		return $messages;
	}

	/**
	 * 
	 */
	public function report_sms_range($from, $to) {
		global $wpdb;
		$messages = $wpdb->get_results("SELECT `quote`, `to`, `to_name`, `to_family`, `send_time`, `mobile`, `first_name`, `last_name`, `sex`
									FROM `free_sms_sms` 
									INNER JOIN `free_sms_user`
									ON `free_sms_user`.`id`=`free_sms_sms`.`userid`
									LEFT JOIN `free_sms_profile`
									ON `free_sms_profile`.`userid`=`free_sms_sms`.`userid`
									WHERE `create_at` BETWEEN '{$from}' AND '{$to}'");

		return $messages;
	}
}