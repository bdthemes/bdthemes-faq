<?php

if (!class_exists('BdThemesFaqImage')) {
	class BdThemesFaqImage {
		public function __construct() {
			add_action('admin_init', [$this, 'bdthemes_faq_add_metabox'], 9);
			add_action('save_post', [$this, 'faq_save_metabox']);
			add_action('admin_enqueue_scripts', [$this, 'load_media_files']);
			add_action('admin_footer', [$this, 'load_faq_media_scripts'], 9);
		}
		public function bdthemes_faq_add_metabox() {
			add_meta_box('bdthemes_faq_metabox_fields', esc_html__('FAQ Icon', 'bdthemes-faq'), [$this, 'faq_metabox_fields_callback'], 'faq', 'side');
		}
		public function faq_save_metabox($post_id) {
			if (!$this->is_secured('bdt_faq_image_nonce', 'faq_image', $post_id)) {
				return $post_id;
			}
			$image_id    = isset($_POST['bdt_faq_image_id']) ? $_POST['bdt_faq_image_id'] : '';
			$image_url    = isset($_POST['bdt_faq_image_url']) ? $_POST['bdt_faq_image_url'] : '';
			update_post_meta($post_id, 'bdt_faq_image_id', $image_id);
			update_post_meta($post_id, 'bdt_faq_image_url', $image_url);
		}

		public function faq_metabox_fields_callback($post) {
			wp_nonce_field('faq_image', 'bdt_faq_image_nonce');
			$faq_label = esc_html__('Add Icon', 'ultimate-post-kit-pro');
			$image_id = esc_attr(get_post_meta($post->ID, 'bdt_faq_image_id', true));
			$image_url = esc_attr(get_post_meta($post->ID, 'bdt_faq_image_url', true));
			if (!empty($image_url)) {
				$image = '<img width="100%" height="auto" src="' . $image_url . '">';
			} else {
				$image = ' ';
			}
			$metabox_html = '<div class="form-field term-group-wrap">';
			$metabox_html .= '<input type="hidden" name="bdt_faq_image_id" id="bdt_faq_image_id" value="' . $image_id . '"/>';
			$metabox_html .= '<input type="hidden" name="bdt_faq_image_url" id="bdt_faq_image_url" value="' . $image_url . '"/>';
			$metabox_html .= '<div id="bdt-faq-image-wrapper">' . $image . '</div>';
			$metabox_html .= '<p>';
			$metabox_html .= '<input type="button" class="button button-secondary bdthemes_faq_image" id="bdthemes_faq_image" name="bdthemes_faq_image" value="' . $faq_label . '" />';
			$metabox_html .= '</p>';
			$metabox_html .= '</div>';
			echo $metabox_html;
		}
		private function is_secured($nonce_field, $action, $post_id) {
			$nonce = isset($_POST[$nonce_field]) ? $_POST[$nonce_field] : '';

			if ($nonce == '') {
				return false;
			}
			if (!wp_verify_nonce($nonce, $action)) {
				return false;
			}

			if (!current_user_can('edit_post', $post_id)) {
				return false;
			}

			if (wp_is_post_autosave($post_id)) {
				return false;
			}

			if (wp_is_post_revision($post_id)) {
				return false;
			}

			return true;
		}

		public function load_media_files() {
			wp_enqueue_media();
		}

		public function load_faq_media_scripts() { ?>
			<script>
				(function($) {
					$(document).ready(function() {
						$(".bdthemes_faq_image.button").on("click", function() {
							let UPK = wp.media({
								multiple: false
							});
							UPK.on('select', function() {
								let attachment = UPK.state().get('selection').first().toJSON();
								$("#bdt_faq_image_id").val(attachment.id);
								$("#bdt_faq_image_url").val(attachment.url);
								$("#bdt-faq-image-wrapper").html(`<img width="100%" height="auto" src="${attachment.url}"/>`);
							});
							UPK.open();
							return false;
						});
					});
				})(jQuery);
			</script>
<?php
		}
	}

	new BdThemesFaqImage();
}
