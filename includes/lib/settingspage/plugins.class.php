<?php

class plugins_class{

	private static $message = array();
	private static $gravityforms = 'gravityforms/gravityforms.php';

	static function get_form(){

		$functions = get_class_methods(__CLASS__);
		foreach($functions as $function){
			if(isset($_POST[$function]))
				self::$function();
		}

		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		$prechecked[] = 'admin-quick-jump/jck-quick-jump.php';
		$prechecked[] = 'advanced-custom-fields/acf.php';
		$prechecked[] = 'acf-repeater/acf-repeater.php';
		$prechecked[] = 'black-studio-tinymce-widget/black-studio-tinymce-widget.php';
		$prechecked[] = 'bulkpress/bulkpress.php';
		$prechecked[] = 'codepress-admin-columns/codepress-admin-columns.php';
		$prechecked[] = 'drag-drop-featured-image/index.php';
		$prechecked[] = 'dynamic-image-resizer/dynamic-image-resizer.php';
		$prechecked[] = 'force-regenerate-thumbnails/force-regenerate-thumbnails.php';
		$prechecked[] = 'google-analyticator/google-analyticator.php';
		$prechecked[] = 'gravityforms/gravityforms.php';
		$prechecked[] = 'manual-image-crop/manual-image-crop.php';
		$prechecked[] = 'mass-edit-pages/mass-edit-pages.php';
		$prechecked[] = 'mce-table-buttons/mce_table_buttons.php';
		$prechecked[] = 'menu-image/menu-image.php';
		$prechecked[] = 'post-duplicator/m4c-postduplicator.php';
		$prechecked[] = 'simple-image-sizes/simple_image_sizes.php';
		$prechecked[] = 'simple-page-ordering/simple-page-ordering.php';
		$prechecked[] = 'simple-template-columns/index.php';
		$prechecked[] = 'sprinter-slideshow/sprinter-slideshow.php';
		$prechecked[] = 'theme-blvd-responsive-google-maps/themeblvd-google-maps.php';
		$prechecked[] = 'the-taxonomy-sort/the-taxonomy-sort.php';
		$prechecked[] = 'wck-custom-fields-and-custom-post-types-creator/wck.php';
		$prechecked[] = 'wordpress-seo/wp-seo.php';
		$prechecked[] = 'wp-visual-icon-fonts/wp_visual_icon_fonts.php';
		$prechecked[] = 'sprinter-slideshow/sprinter-slideshow.php';

		$all = get_plugins();
		$all_merge = $all;
		$count = count($all);
		$count_prechecked = count($prechecked);
		if($count > 0){
			foreach($prechecked as $k => $path){
				$sorted[] = $path;
				unset($all_merge[$path]);
			}
			foreach($all_merge as $k => $v)
				$sorted[] = $k;
		?>
		<div class="white-box">
			<div class="padding-hor padding-top border-bottom">
				<h2>Plugins</h2>
			</div>

			<div class="all-plugins padding-hor padding-bottom padding-top">

				<input type="button" value="Markera alla" id="check-all-plugins" class="button button-secondary button-small" />
				<input type="button" value="Markera standard" id="check-default-plugins" class="button button-secondary button-small" />
				<div class="cf"></div>
				<div class="equals">
					<div class="equal equal-3">
						<?php
						$i = 1;
						$splitter = round($count / 3);
						foreach($sorted as $key => $path){
							if($i > $splitter){
								echo '</div><div class="equal equal-3">';
								$splitter *= 2;
							}
							$checked = '';
							if(is_plugin_active($path))
								$checked = ' checked="checked"';

							$class = '';
							if($i <= $count_prechecked)
								$class = ' class="prechecked"';

							$title = $all[$path]['Title'];
							$dots = (strlen($title) > 36) ? '...' : '';
							echo '<div class="row"><input type="checkbox" id="plugin-'. $key .'" name="plugin['. $key .']" value="'. $path .'"'. $checked . $class .' /><label for="plugin-'. $key .'">'. substr($title, 0, 36) . $dots .'</label></div>';
							$i++;
						}
						?>
					</div>
				</div>
				<input type="submit" name="activate_plugins" value="Aktivera & Inaktivera plugin" id="activate_plugins" class="button button-primary button-large" />
			</div>
		</div>

		<?php
		}

		if(is_plugin_active(self::$gravityforms)){
		?>
			<hr /><hr /><hr />
			<div class="white-box">
				<div class="padding-top border-bottom padding-hor">
					<h2>Skapa data</h2>
				</div>
				<div class="padding-top padding-bottom padding-hor">
					<input type="submit" name="fill_gravityform" value="Gravity Forms" id="fill_gravityform" class="button button-primary button-large" />
					<br />
					<small>Skapa ett standardformulär till en kontaktsida</small>
				</div>
			</div>


		<?php
		}
		?>
<?php
	}

	function activate_plugins(){
		global $message_class;
		if(isset($_POST['plugin']) && is_array($_POST['plugin']) && file_exists(ABSPATH .'wp-content/plugins')){
			$selected = $_POST['plugin'];

			$all = get_plugins();

			foreach($all as $value => $data){
				if(in_array($value, $selected)){
					if(!is_plugin_active($value)){
						activate_plugin($value);
						$activated[] = $data['Title'];
					}
				}
				else{
					if(is_plugin_active($value)){
						deactivate_plugins($value, true);
						$deactivated[] = $data['Title'];
					}
				}
			}
		}
		else
			$message_class->message['e'][] = 'Sökvägen: '. ABSPATH .'wp-content/plugins kunde inte hittas.';

		if(!empty($activated)){
			foreach($activated as $a)
				$message_class->message['info'][] .= $a .' aktiverades';
		}

		if(!empty($deactivated)){
			foreach($deactivated as $d)
				$message_class->message['info'][] .= $d .' inaktiverades';
		}

		if(empty($deactivated) && empty($activated))
			$message_class->message['i'][] .= 'Ingen åtgärd vidtogs.';


	}


	//FILL
	function fill_gravityform(){
		global $message_class;
		if(is_plugin_active(self::$gravityforms)){
			global $wpdb;
			$wpdb->query('INSERT INTO `'. $wpdb->base_prefix .'rg_form` (`id`, `title`, `date_created`, `is_active`) VALUES (1, "Kontakt", "2014-06-27 09:18:15", 1)');
			$wpdb->query('INSERT INTO `'. $wpdb->base_prefix .'rg_form_meta` (`form_id`, `display_meta`, `entries_grid_meta`, `confirmations`, `notifications`) VALUES (1, \'a:15:{s:5:"title";s:7:"Kontakt";s:11:"description";s:0:"";s:14:"labelPlacement";s:9:"top_label";s:20:"descriptionPlacement";s:5:"below";s:6:"button";a:3:{s:4:"type";s:4:"text";s:4:"text";s:6:"Skicka";s:8:"imageUrl";s:0:"";}s:6:"fields";a:3:{i:0;a:16:{s:2:"id";i:1;s:5:"label";s:4:"Namn";s:10:"adminLabel";s:0:"";s:4:"type";s:4:"text";s:10:"isRequired";b:1;s:4:"size";s:6:"medium";s:12:"errorMessage";s:0:"";s:6:"inputs";N;s:18:"calculationFormula";s:0:"";s:19:"calculationRounding";s:0:"";s:17:"enableCalculation";s:0:"";s:15:"disableQuantity";b:0;s:20:"displayAllCategories";b:0;s:9:"inputMask";b:0;s:14:"inputMaskValue";s:0:"";s:17:"allowsPrepopulate";b:0;}i:1;a:16:{s:2:"id";i:2;s:5:"label";s:6:"E-post";s:10:"adminLabel";s:0:"";s:4:"type";s:5:"email";s:10:"isRequired";b:1;s:4:"size";s:6:"medium";s:12:"errorMessage";s:0:"";s:6:"inputs";N;s:18:"calculationFormula";s:0:"";s:19:"calculationRounding";s:0:"";s:17:"enableCalculation";s:0:"";s:15:"disableQuantity";b:0;s:20:"displayAllCategories";b:0;s:9:"inputMask";b:0;s:14:"inputMaskValue";s:0:"";s:17:"allowsPrepopulate";b:0;}i:2;a:16:{s:2:"id";i:3;s:5:"label";s:10:"Meddelande";s:10:"adminLabel";s:0:"";s:4:"type";s:8:"textarea";s:10:"isRequired";b:1;s:4:"size";s:6:"medium";s:12:"errorMessage";s:0:"";s:6:"inputs";N;s:18:"calculationFormula";s:0:"";s:19:"calculationRounding";s:0:"";s:17:"enableCalculation";s:0:"";s:15:"disableQuantity";b:0;s:20:"displayAllCategories";b:0;s:9:"inputMask";b:0;s:14:"inputMaskValue";s:0:"";s:17:"allowsPrepopulate";b:0;}}s:2:"id";i:1;s:22:"useCurrentUserAsAuthor";b:1;s:26:"postContentTemplateEnabled";b:0;s:24:"postTitleTemplateEnabled";b:0;s:17:"postTitleTemplate";s:0:"";s:19:"postContentTemplate";s:0:"";s:14:"lastPageButton";N;s:10:"pagination";N;s:17:"firstPageCssClass";N;}\', NULL, \'a:1:{s:13:"53ad36d756ec9";a:8:{s:2:"id";s:13:"53ad36d756ec9";s:4:"name";s:20:"Default Confirmation";s:9:"isDefault";b:1;s:4:"type";s:7:"message";s:7:"message";s:80:"Tack för att du kontaktar oss! Vi kommer att komma i kontakt med dig inom kort.";s:3:"url";s:0:"";s:6:"pageId";s:0:"";s:11:"queryString";s:0:"";}}\', \'a:1:{s:13:"53ad36d755e3b";a:14:{s:2:"id";s:13:"53ad36d755e3b";s:2:"to";s:13:"{admin_email}";s:4:"name";s:27:"Administratörsnotifikation";s:5:"event";s:15:"form_submission";s:6:"toType";s:5:"email";s:7:"subject";s:23:"{form_title} - {Namn:1}";s:7:"message";s:12:"{all_fields}";s:3:"bcc";s:0:"";s:4:"from";s:10:"{E-post:2}";s:8:"fromName";s:8:"{Namn:1}";s:7:"replyTo";s:10:"{E-post:2}";s:7:"routing";N;s:16:"conditionalLogic";N;s:17:"disableAutoformat";s:0:"";}}\')');
			$wpdb->query('INSERT INTO `'. $wpdb->base_prefix .'rg_lead` (`id`, `form_id`, `post_id`, `date_created`, `is_starred`, `is_read`, `ip`, `source_url`, `user_agent`, `currency`, `payment_status`, `payment_date`, `payment_amount`, `transaction_id`, `is_fulfilled`, `created_by`, `transaction_type`, `status`) VALUES (1, 1, NULL, "2014-06-27 09:39:12", 0, 0, "127.0.0.1", "http://s/wp/kontakt/", "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36", "USD", NULL, NULL, NULL, NULL, NULL, 2, NULL, "active");');

			if( default_functions::get_page_id('kontakt')){
				wp_update_post(
						array(
								'ID' => default_functions::get_page_id('kontakt'),
								'post_content' => '[gravityform id="1" name="Kontakt" title="false" description="false"]'
						)
				);
			}

			//Remove standard CSS style
			update_option('rg_gforms_disable_css', 1);

			$message_class->message['s'][] = 'Data är nu tillagt för gravityforms.';
		}
		else
			$message_class->message['e'][] = 'Pluginet måste vara installerat och aktiverat innan du kan fylla det med information.';
	}

}
?>