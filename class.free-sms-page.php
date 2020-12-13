<?php 
/**
 * 
 */
class Sms_page
{
	
	public function fixed_link($setting) {
		return '
		<div class="free_for_all_color_'.$setting->theme.'" id="free_for_all_click_me_text_'.$setting->theme.'">
			<div id="free_for_all_click_me_close_'.$setting->theme.'" onclick="free_for_all_click_me_close(\'free_for_all_click_me_text_'.$setting->theme.'\');"></div>
			اگر میخوای همین الان پیامک رایگان ارسال کنی، کلیک کن
		</div>
		<div id="free_for_all_click_me_'.$setting->theme.'">
			<a href="'.get_permalink($setting->pageid).'" target="_blank" title="ارسال رایگان پیامک">
				<img width="50" height="50" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZlcnNpb249IjEuMSIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbG5zOnN2Z2pzPSJodHRwOi8vc3ZnanMuY29tL3N2Z2pzIiB3aWR0aD0iNTEyIiBoZWlnaHQ9IjUxMiIgeD0iMCIgeT0iMCIgdmlld0JveD0iMCAwIDUxMi4wNTMgNTEyLjA1MyIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNTEyIDUxMiIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgY2xhc3M9IiI+PGcgdHJhbnNmb3JtPSJtYXRyaXgoLTEsMCwwLDEsNTEyLjA1Mjg0MzA5Mzg3MjEsMCkiPjxnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0ibS4wMTMgMzY4LjY1M2MuMjcgNi4yNCA0LjM2IDExLjY2IDEwLjI5IDEzLjYxbDg5LjgyIDI5LjY0IDI5LjY0IDg5LjgyYzEuOTUgNS45MyA3LjM3IDEwLjAyIDEzLjYxIDEwLjI5IDYuMDczLjI4OSAxMS45MzctMy4yNzIgMTQuNDMtOS4xbDk2LTIyNWM1LjI4Ni0xMi40MjQtNy4yNy0yNC45NzUtMTkuNjktMTkuNjlsLTIyNSA5NmMtNS43NCAyLjQ1LTkuMzYgOC4yLTkuMSAxNC40M3oiIGZpbGw9IiM0ZDU3ODgiIGRhdGEtb3JpZ2luYWw9IiM0ZDU3ODgiIHN0eWxlPSIiIGNsYXNzPSIiPjwvcGF0aD48cGF0aCBkPSJtMTAwLjEyMyA0MTEuOTAzIDI5LjY0IDg5LjgyYzEuOTUgNS45MyA3LjM3IDEwLjAyIDEzLjYxIDEwLjI5IDYuMDczLjI4OSAxMS45MzctMy4yNzIgMTQuNDMtOS4xbDk2LTIyNWMyLjQtNS42NCAxLjE0LTEyLjE3LTMuMTktMTYuNXoiIGZpbGw9IiMzMTNhNmIiIGRhdGEtb3JpZ2luYWw9IiMzMTNhNmIiIHN0eWxlPSIiIGNsYXNzPSIiPjwvcGF0aD48cGF0aCBkPSJtOTcuMDAzIDI1Ni4wMjRjMCA4LjI4IDYuNzIgMTUgMTUgMTVzMTUtNi43MiAxNS0xNWMwLTcxLjEzIDU3Ljg3LTEyOSAxMjktMTI5IDcxLjEyNyAwIDEyOSA1Ny44NzggMTI5IDEyOSAwIDcxLjEzLTU3Ljg3IDEyOS0xMjkgMTI5LTguMjggMC0xNSA2LjcyLTE1IDE1czYuNzIgMTUgMTUgMTVjODcuNjcgMCAxNTktNzEuMzMgMTU5LTE1OSAwLTg3LjY2OS03MS4zMTctMTU5LTE1OS0xNTktODcuNjcgMC0xNTkgNzEuMzMtMTU5IDE1OXoiIGZpbGw9IiNmM2Q2NTIiIGRhdGEtb3JpZ2luYWw9IiNmM2Q2NTIiIHN0eWxlPSIiIGNsYXNzPSIiPjwvcGF0aD48cGF0aCBkPSJtMjQxLjAwMyA0MDAuMDI0YzAgOC4yOCA2LjcyIDE1IDE1IDE1IDg3LjY3IDAgMTU5LTcxLjMzIDE1OS0xNTkgMC00My44NC0xNy44My04My41OC00Ni42Mi0xMTIuMzhsLTIxLjIxIDIxLjIxYzIzLjM2IDIzLjM1IDM3LjgzIDU1LjYxIDM3LjgzIDkxLjE3IDAgNzEuMTMtNTcuODcgMTI5LTEyOSAxMjktOC4yOCAwLTE1IDYuNzItMTUgMTV6IiBmaWxsPSIjZTliYzNlIiBkYXRhLW9yaWdpbmFsPSIjZTliYzNlIiBzdHlsZT0iIiBjbGFzcz0iIj48L3BhdGg+PHBhdGggZD0ibS4wMDMgMjU2LjAyNGMwIDguMjggNi43MiAxNSAxNSAxNXMxNS02LjcyIDE1LTE1YzAtMTI0LjYyIDEwMS4zOC0yMjYgMjI2LTIyNiAxMjQuNjE3IDAgMjI2IDEwMS4zNzYgMjI2IDIyNiAwIDEyNC42Mi0xMDEuMzggMjI2LTIyNiAyMjYtOC4yOCAwLTE1IDYuNzItMTUgMTVzNi43MiAxNSAxNSAxNWMxNDAuOTY2IDAgMjU2LTExNS4wNTYgMjU2LTI1NiAwLTE0MC45NjYtMTE1LjA1Ni0yNTYtMjU2LTI1Ni0xNDAuOTY2IDAtMjU2IDExNS4wNTUtMjU2IDI1NnoiIGZpbGw9IiNlMjdhNGUiIGRhdGEtb3JpZ2luYWw9IiNlMjdhNGUiIHN0eWxlPSIiIGNsYXNzPSIiPjwvcGF0aD48cGF0aCBkPSJtMjQxLjAwMyA0OTcuMDI0YzAgOC4yOCA2LjcyIDE1IDE1IDE1IDY4LjExIDAgMTMyLjMzLTI2LjcgMTgwLjgxLTc1LjE5IDEwMC4yOTctMTAwLjI3NyAxMDAuMzQzLTI2MS4yOTcgMC0zNjEuNjJsLTIxLjA4IDIxLjA4YzQwLjkzIDQwLjkyIDY2LjI3IDk3LjQyIDY2LjI3IDE1OS43MyAwIDEyNC42Mi0xMDEuMzggMjI2LTIyNiAyMjYtOC4yOCAwLTE1IDYuNzItMTUgMTV6IiBmaWxsPSIjZGM0NTViIiBkYXRhLW9yaWdpbmFsPSIjZGM0NTViIiBzdHlsZT0iIiBjbGFzcz0iIj48L3BhdGg+PC9nPjwvZz48L3N2Zz4=" />
			</a>
		</div>';
	}

	public function get_phone($setting) {
		return '
		<div id="progress-bar" class="free_for_all_color_gray relative text-center">0%</div>
		<div id="free_for_all_step_box" class="bg-white free_for_all_border_gray free_for_all_radius">
			<div id="free_for_all_step_title" class="free_for_all_color_gray free_for_all_border_gray px-1">شماره موبایل خود را وارد نمایید:</div>
			<form id="free_for_all_step_form" class="text-center" action="'.admin_url('admin-ajax.php').'" method="POST" accept-charset="utf-8" onsubmit="return nextStep(this);">
				<div class="free_for_all_row" dir="ltr">
					<span class="d-ib">09</span><input class="d-ib free_for_all_input free_for_all_input_'.$setting->theme.'" type="number" name="mobile">
				</div>
				<div class="free_for_all_row">
					<button type="submit" class="free_for_all_btn free_for_all_color_'.$setting->theme.'">بعدی</button>
				</div>
			</form>
		</div>
		';
	}

	public function admin_sms_setting($setting) {
		return '
		<div class="wrap">
			<h1 class="wp-heading-inline">تنظیمات افزونه</h1>
			<form action="" method="POST" accept-charset="utf-8">
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row"><label for="pageid">آی دی صفحه پیامک</label></th>
							<td><input name="pageid" type="number" id="pageid" value="'.$setting->pageid.'" class="regular-text"></td>
						</tr>
						<tr>
							<th scope="row"><label for="theme">تم</label></th>
							<td>
								<select name="theme" id="theme">
									<option value="default">پیشفرض</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="freeSmsCount">تعداد پیامک رایگان</label></th>
							<td><input name="freeSmsCount" type="number" id="freeSmsCount" value="'.intval($setting->freeSmsCount).'" class="regular-text"></td>
						</tr>
						<tr>
							<th scope="row"><label for="freeSmsTime">هر چند ساعت ریست شدن</label></th>
							<td><input name="freeSmsTime" type="number" id="freeSmsTime" value="'.intval($setting->freeSmsTime).'" class="regular-text"></td>
						</tr>
						<tr>
							<th scope="row"><label for="user_api">یوزرنیم پنل پیامک</label></th>
							<td><input name="user_api" type="text" id="user_api" value="'.$setting->user_api.'" class="regular-text"></td>
						</tr>
						<tr>
							<th scope="row"><label for="pass_api">رمز پنل پیامک</label></th>
							<td><input name="pass_api" type="text" id="pass_api" value="'.$setting->pass_api.'" class="regular-text"></td>
						</tr>
					</tbody>
				</table>
				<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="ذخیره سازی"></p>
			</form>
		</div>
		';
	}

	public function admin_sms_text() {
		return '
		<div class="wrap">
			<h1 class="wp-heading-inline">متن پیامک ها</h1>
			<form  method="post" enctype="multipart/form-data">
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
		</div>';
	}

	public function admin_sms_text2($texts) {
		$html = '<hr>
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th><strong>موضوع</strong></th>
					<th><strong>متن</strong></th>
				</tr>
			</thead>
			<tbody>';
				foreach ($texts as $text) {
					$html .= '
					<tr>
						<td>'.$text->subject.'</td>
						<td>'.$text->message.'</td>
					</tr>';
				}
			$html .= '</tbody>
		</table">';

		return $html;
	}
}