<?php if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) : ?>
	<div id="sms-lightbox" class="flex flex-center" onclick="free_sms_closelightbox(event);">
<?php else: ?>
	<?php wp_head(); ?>
<?php endif; ?>
	<div id="sms-form" class="free_for_all_radius relative">
		<?php if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) : ?>
			<div id="free_for_all_click_me_close_lightbox" onclick="free_for_all_click_me_close('sms-lightbox');"></div>
		<?php endif; ?>
		<div id="progress-bar" class="free_for_all_color_gray relative text-center free_for_all_border_gray">
			<span id="precent-label">0%</span>
			<div id="progress-load" class="progress-bar-striped free_for_all_color_default"></div>
		</div>
		<div id="free_for_all_step_box" class="bg-white free_for_all_border_gray free_for_all_radius">
			<div id="free_for_all_step_title" class="free_for_all_color_gray">شماره موبایل خود را وارد نمایید:</div>
			<div id="sms-error" class="free_for_all_row"></div>
			<form id="free_for_all_step_form" action="<?php echo admin_url('admin-ajax.php') ?>" method="POST" accept-charset="utf-8" onsubmit="return nextStep(this);">
				<div class="free_for_all_row relative">
					<label for="mobile">شماره موبایل:</label>
					<input dir="ltr" class="free_for_all_input free_for_all_input_default" placeholder="09 ××× ×× ××" type="number" name="mobile" id="mobile">
				</div>
				<div class="free_for_all_row">
					<button type="submit" class="swipe-overlay-out"><span>بعدی</span></button>
				</div>
			</form>
		</div>
	</div>
<?php if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) : ?>
	</div>
<?php else: ?>
	<?php wp_footer(); ?>
<?php endif; ?>