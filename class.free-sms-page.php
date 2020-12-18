<?php 
/**
 * 
 */
class Sms_page
{
	
	public function fixed_link($setting) {
		if ($setting->pageid == 0)
			$link = 'href="'.admin_url('admin-ajax.php').'?action=popupForm" onclick="return popupForm(this);"';
		else
			$link = 'href="'.get_permalink($setting->pageid).'"';

		return '
		<div class="free_for_all_color_default" id="free_for_all_click_me_text_default">
			<div id="free_for_all_click_me_close_default" onclick="free_for_all_click_me_close(\'free_for_all_click_me_text_default\');"></div>
			اگر میخوای همین الان پیامک رایگان ارسال کنی، کلیک کن
		</div>
		<div id="free_for_all_click_me_default">
			<a '.$link.' target="_blank" title="ارسال رایگان پیامک">
				<img width="50" height="50" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZlcnNpb249IjEuMSIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbG5zOnN2Z2pzPSJodHRwOi8vc3ZnanMuY29tL3N2Z2pzIiB3aWR0aD0iNTEyIiBoZWlnaHQ9IjUxMiIgeD0iMCIgeT0iMCIgdmlld0JveD0iMCAwIDUxMi4wNTMgNTEyLjA1MyIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNTEyIDUxMiIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgY2xhc3M9IiI+PGcgdHJhbnNmb3JtPSJtYXRyaXgoLTEsMCwwLDEsNTEyLjA1Mjg0MzA5Mzg3MjEsMCkiPjxnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0ibS4wMTMgMzY4LjY1M2MuMjcgNi4yNCA0LjM2IDExLjY2IDEwLjI5IDEzLjYxbDg5LjgyIDI5LjY0IDI5LjY0IDg5LjgyYzEuOTUgNS45MyA3LjM3IDEwLjAyIDEzLjYxIDEwLjI5IDYuMDczLjI4OSAxMS45MzctMy4yNzIgMTQuNDMtOS4xbDk2LTIyNWM1LjI4Ni0xMi40MjQtNy4yNy0yNC45NzUtMTkuNjktMTkuNjlsLTIyNSA5NmMtNS43NCAyLjQ1LTkuMzYgOC4yLTkuMSAxNC40M3oiIGZpbGw9IiM0ZDU3ODgiIGRhdGEtb3JpZ2luYWw9IiM0ZDU3ODgiIHN0eWxlPSIiIGNsYXNzPSIiPjwvcGF0aD48cGF0aCBkPSJtMTAwLjEyMyA0MTEuOTAzIDI5LjY0IDg5LjgyYzEuOTUgNS45MyA3LjM3IDEwLjAyIDEzLjYxIDEwLjI5IDYuMDczLjI4OSAxMS45MzctMy4yNzIgMTQuNDMtOS4xbDk2LTIyNWMyLjQtNS42NCAxLjE0LTEyLjE3LTMuMTktMTYuNXoiIGZpbGw9IiMzMTNhNmIiIGRhdGEtb3JpZ2luYWw9IiMzMTNhNmIiIHN0eWxlPSIiIGNsYXNzPSIiPjwvcGF0aD48cGF0aCBkPSJtOTcuMDAzIDI1Ni4wMjRjMCA4LjI4IDYuNzIgMTUgMTUgMTVzMTUtNi43MiAxNS0xNWMwLTcxLjEzIDU3Ljg3LTEyOSAxMjktMTI5IDcxLjEyNyAwIDEyOSA1Ny44NzggMTI5IDEyOSAwIDcxLjEzLTU3Ljg3IDEyOS0xMjkgMTI5LTguMjggMC0xNSA2LjcyLTE1IDE1czYuNzIgMTUgMTUgMTVjODcuNjcgMCAxNTktNzEuMzMgMTU5LTE1OSAwLTg3LjY2OS03MS4zMTctMTU5LTE1OS0xNTktODcuNjcgMC0xNTkgNzEuMzMtMTU5IDE1OXoiIGZpbGw9IiNmM2Q2NTIiIGRhdGEtb3JpZ2luYWw9IiNmM2Q2NTIiIHN0eWxlPSIiIGNsYXNzPSIiPjwvcGF0aD48cGF0aCBkPSJtMjQxLjAwMyA0MDAuMDI0YzAgOC4yOCA2LjcyIDE1IDE1IDE1IDg3LjY3IDAgMTU5LTcxLjMzIDE1OS0xNTkgMC00My44NC0xNy44My04My41OC00Ni42Mi0xMTIuMzhsLTIxLjIxIDIxLjIxYzIzLjM2IDIzLjM1IDM3LjgzIDU1LjYxIDM3LjgzIDkxLjE3IDAgNzEuMTMtNTcuODcgMTI5LTEyOSAxMjktOC4yOCAwLTE1IDYuNzItMTUgMTV6IiBmaWxsPSIjZTliYzNlIiBkYXRhLW9yaWdpbmFsPSIjZTliYzNlIiBzdHlsZT0iIiBjbGFzcz0iIj48L3BhdGg+PHBhdGggZD0ibS4wMDMgMjU2LjAyNGMwIDguMjggNi43MiAxNSAxNSAxNXMxNS02LjcyIDE1LTE1YzAtMTI0LjYyIDEwMS4zOC0yMjYgMjI2LTIyNiAxMjQuNjE3IDAgMjI2IDEwMS4zNzYgMjI2IDIyNiAwIDEyNC42Mi0xMDEuMzggMjI2LTIyNiAyMjYtOC4yOCAwLTE1IDYuNzItMTUgMTVzNi43MiAxNSAxNSAxNWMxNDAuOTY2IDAgMjU2LTExNS4wNTYgMjU2LTI1NiAwLTE0MC45NjYtMTE1LjA1Ni0yNTYtMjU2LTI1Ni0xNDAuOTY2IDAtMjU2IDExNS4wNTUtMjU2IDI1NnoiIGZpbGw9IiNlMjdhNGUiIGRhdGEtb3JpZ2luYWw9IiNlMjdhNGUiIHN0eWxlPSIiIGNsYXNzPSIiPjwvcGF0aD48cGF0aCBkPSJtMjQxLjAwMyA0OTcuMDI0YzAgOC4yOCA2LjcyIDE1IDE1IDE1IDY4LjExIDAgMTMyLjMzLTI2LjcgMTgwLjgxLTc1LjE5IDEwMC4yOTctMTAwLjI3NyAxMDAuMzQzLTI2MS4yOTcgMC0zNjEuNjJsLTIxLjA4IDIxLjA4YzQwLjkzIDQwLjkyIDY2LjI3IDk3LjQyIDY2LjI3IDE1OS43MyAwIDEyNC42Mi0xMDEuMzggMjI2LTIyNiAyMjYtOC4yOCAwLTE1IDYuNzItMTUgMTV6IiBmaWxsPSIjZGM0NTViIiBkYXRhLW9yaWdpbmFsPSIjZGM0NTViIiBzdHlsZT0iIiBjbGFzcz0iIj48L3BhdGg+PC9nPjwvZz48L3N2Zz4=" />
			</a>
		</div>';
	}

	public function get_popup($attr) {
		return '<p class="text-center">
					<a class="swipe-overlay-out" href="'.admin_url('admin-ajax.php').'?action=popupForm" title="'.$attr["text"].'" onclick="return popupForm(this);">
						<span>'.$attr["text"].'</span>
					</a>
				</p>';
	}

	public function admin_sms_setting($setting, $pages) {
		if (is_null($setting->freeSmsTime))
			$reset_time = array('','');
		else
			$reset_time = explode("|", $setting->freeSmsTime);

		$form = '
		<div class="wrap">
			<h1 class="wp-heading-inline">تنظیمات افزونه</h1>
			<form action="" method="POST" accept-charset="utf-8">
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row"><label for="pageid">صفحه پیامک</label></th>
							<td>
								<select name="pageid" id="pageid" class="regular-text">
									<option value="0">باز شدن در همه صفحه ها</option>';
									foreach ($pages as $page) {
										if ($setting->pageid == $page->ID)
											$form .= '<option value="'.$page->ID.'" selected="selected">'.$page->post_title.'</option>';
										else
											$form .= '<option value="'.$page->ID.'">'.$page->post_title.'</option>';
									}
								$form .= '</select>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="background">زمینه</label></th>
							<td><input name="background" type="color" id="background" value="'.$setting->background.'" class="regular-text"></td>
						</tr>
						<tr>
							<th scope="row"><label for="foreground">پیش نما</label></th>
							<td><input name="foreground" type="color" id="foreground" value="'.$setting->foreground.'" class="regular-text"></td>
						</tr>
						<tr>
							<th scope="row"><label for="freeSmsCount">تعداد پیامک رایگان</label></th>
							<td><input name="freeSmsCount" type="number" id="freeSmsCount" value="'.intval($setting->freeSmsCount).'" class="regular-text"></td>
						</tr>
						<tr>
							<th scope="row"><label for="freeSmsTime">بازگردانی پیامک هدیه </label></th>
							<td>
								<div>
									<select name="freeSmsTimeCycle" class="regular-text">';
										switch ($reset_time[0]) {
											case '1':
												$form .= '
												<option value="">هرگز</option>
												<option value="1" selected="selected">ساعتی</option>
												<option value="24">روزانه</option>
												<option value="168">هفتگی</option>
												<option value="720">ماهیانه</option>
												<option value="8760">سالیانه</option>';
												break;
											case '24':
												$form .= '
												<option value="">هرگز</option>
												<option value="1">ساعتی</option>
												<option value="24" selected="selected">روزانه</option>
												<option value="168">هفتگی</option>
												<option value="720">ماهیانه</option>
												<option value="8760">سالیانه</option>';
												break;
											case '168':
												$form .= '
												<option value="">هرگز</option>
												<option value="1">ساعتی</option>
												<option value="24">روزانه</option>
												<option value="168" selected="selected">هفتگی</option>
												<option value="720">ماهیانه</option>
												<option value="8760">سالیانه</option>';
												break;
											case '720':
												$form .= '
												<option value="">هرگز</option>
												<option value="1">ساعتی</option>
												<option value="24">روزانه</option>
												<option value="168">هفتگی</option>
												<option value="720" selected="selected">ماهیانه</option>
												<option value="8760">سالیانه</option>';
												break;
											case '8760':
												$form .= '
												<option value="">هرگز</option>
												<option value="1">ساعتی</option>
												<option value="24">روزانه</option>
												<option value="168">هفتگی</option>
												<option value="720">ماهیانه</option>
												<option value="8760" selected="selected">سالیانه</option>';
												break;
											default:
												$form .= '
												<option value="" selected="selected">هرگز</option>
												<option value="1">ساعتی</option>
												<option value="24">روزانه</option>
												<option value="168">هفتگی</option>
												<option value="720">ماهیانه</option>
												<option value="8760">سالیانه</option>';
												break;
										}
									$form .= '</select>
								</div>
								<div style="margin-top:0.5em">
									<input name="freeSmsTime" type="number" max="999" id="freeSmsTime" value="'.$reset_time[1].'" class="regular-text">
								</div>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="user_api">یوزرنیم پنل پیامک</label></th>
							<td><input name="user_api" type="text" id="user_api" value="'.$setting->user_api.'" class="regular-text"></td>
						</tr>
						<tr>
							<th scope="row"><label for="pass_api">رمز پنل پیامک</label></th>
							<td><input name="pass_api" type="password" id="pass_api" value="'.$setting->pass_api.'" class="regular-text"></td>
						</tr>
						<tr>
							<th scope="row"><label for="phone_number">شماره خط پیامکی</label></th>
							<td><input name="phone_number" type="number" id="phone_number" value="'.$setting->phone_number.'" class="regular-text"></td>
						</tr>
						<tr>
							<th scope="row"><label for="api_number">شماره وب سرویس</label></th>
							<td><input name="api_number" type="text" id="api_number" value="'.intval($setting->api_number).'" class="regular-text"></td>
						</tr>
						<tr>
							<th scope="row"><label>اطلاعات مشتری</label></th>
							<td>
								<span dir="rtl">
									<label for="fname">نام</label>';
									if (in_array('fname', unserialize($setting->other_texts)["profile"]))
										$form .= '<input checked="checked" name="profile[]" id="fname" type="checkbox" value="fname">';
									else
										$form .= '<input name="profile[]" id="fname" type="checkbox" value="fname">';
								$form .= '</span>
								<span dir="rtl">
									<label for="lname">نام خانوادگی</label>';
									if (in_array('lname', unserialize($setting->other_texts)["profile"]))
										$form .= '<input checked="checked" name="profile[]" id="lname" type="checkbox" value="lname">';
									else
										$form .= '<input name="profile[]" id="lname" type="checkbox" value="lname">';
								$form .= '</span>
								<span dir="rtl">
									<label for="sex">جنسیت</label>';
									if (in_array('sex', unserialize($setting->other_texts)["profile"]))
										$form .= '<input checked="checked" name="profile[]" id="sex" type="checkbox" value="sex">';
									else
										$form .= '<input name="profile[]" id="sex" type="checkbox" value="sex">';
								$form .= '</span>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="gift_text">متن هدیه</label></th>
							<td><input name="gift_text" type="text" id="gift_text" value="'.unserialize($setting->other_texts)["gift_text"].'" class="regular-text"></td>
						</tr>
						<tr>
							<th scope="row"><label for="signature">امضا پیامک</label></th>
							<td><input name="signature" type="text" id="signature" value="'.unserialize($setting->other_texts)["signature"].'" class="regular-text"></td>
						</tr>
					</tbody>
				</table>
				<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="ذخیره سازی"></p>
			</form>
		</div>
		';

		return $form;
	}

	public function admin_sms_text() {
		return '
		<div class="wrap">
			<h1 class="wp-heading-inline">متن پیامک ها</h1>
			<div class="card">
				<h2>ثبت از طریق فایل اکسل</h2>
				<form method="post" enctype="multipart/form-data">
					<p>ستون اول متن متعلق به پیامک ها و ستون دوم متعلق به دسته بندی است.</p>
					<p>در متن پیامک ها به جای متغیرها از عبارت %s استفاده کنید.</p>
					<table class="form-table" role="presentation">
						<tbody>
							<tr>
								<th scope="row"><label for="pageid">فایل xlsx</label></th>
								<td><input type="file" id="xlsx" name="xlsx"></input></td>
							</tr>
						</tbody>
					</table>
					<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="ذخیره سازی"></p>
				</form>
			</div>
			<div class="card">
				<h2>ثبت بصورت دستی</h2>
				<form method="post" accept-charset="utf-8">
					<p>در متن پیامک ها به جای متغیرها از عبارت %s استفاده کنید.</p>
					<table class="form-table" role="presentation">
						<tbody>
							<tr>
								<th scope="row"><label for="subject">دسته بندی</label></th>
								<td><input type="text" id="subject" name="subject"></input></td>
							</tr>
							<tr>
								<th scope="row"><label for="message">متن پیامک</label></th>
								<td><input type="text" id="message" name="message"></input></td>
							</tr>
						</tbody>
					</table>
					<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="ذخیره سازی"></p>
				</form>
			</div>
		</div>';
	}

	public function admin_sms_text2($texts) {
		$html = '<hr>
		<table class="wp-list-table widefat striped">
			<thead>
				<tr>
					<th><strong>موضوع</strong></th>
					<th><strong>متن</strong></th>
					<th><strong>حذف</strong></th>
				</tr>
			</thead>
			<tbody>';
				foreach ($texts as $text) {
					$html .= '
					<tr>
						<td>'.$text->subject.'</td>
						<td>'.$text->message.'</td>
						<td>
							<form method="POST" accept-charset="utf-8">
								<input type="hidden" name="messageid" value="'.$text->id.'">
								<input type="submit" value="حذف">
							</form>
						</td>
					</tr>';
				}
			$html .= '</tbody>
		</table">';

		return $html;
	}

	public function admin_sms_report($messages) {
		$html = '<table class="wp-list-table widefat fixed striped">
					<thead>
						<tr>
							<th><strong>from</strong></th>
							<th><strong>name</strong></th>
							<th><strong>to</strong></th>
							<th><strong>messages</strong></th>
							<th><strong>time</strong></th>
						</tr>
					</thead>
					<tbody>';
					foreach ($messages as $m) {
						$html .= '<tr>
							<td>'.$m->mobile.'</td>
							<td>'.$m->first_name.' '.$m->last_name.'</td>
							<td>'.$m->to.'</td>
							<td>'.$m->quote.'</td>
							<td>'.$m->send_time.'</td>
						</tr>';
					}
					$html .= '</tbody>
				</table>';
		return $html;
	}
}