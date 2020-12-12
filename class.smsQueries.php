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

		curl_close($curl);
		return $response;
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

	
}