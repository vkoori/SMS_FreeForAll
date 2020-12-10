<?php 
/**
 * 
 */
class Sms_page
{
	
	public function free_html() {
		if (sizeof($_POST) == 0) {
			return $this->step_one();
		}
	}

	private function step_one() {
		return 'step one';
	}

	private function get_subjects() {
		
	}

	private function get_quotes() {
		
	}
}