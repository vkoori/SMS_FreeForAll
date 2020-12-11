<?php 

/**
 * 
 */
class smsQueries
{
	public function setting() {
		global $wpdb;
		$setting = $wpdb->get_results("SELECT * FROM `free_sms_setting` WHERE `id`=1");
		return $setting[0];
	}

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
		} else{
			$user = $user[0];
		}


		return $user;
		

	}
}