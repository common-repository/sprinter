<?php
add_action( 'customize_register', 'customize_appearence');
add_action( 'customize_controls_print_styles', 'customize_styles', 50);

$p = plugin_dir_url(__FILE__). '/../..';
$styles['reset-css']         = $p .'/styles/reset.css';
$styles['animate-css']       = $p .'/styles/animate.css';
$styles['slicknav']          = $p .'/plugins/slicknav/slicknav.css';
$styles['font_awesome-css']  = $p .'/plugins/font-awesome/font-awesome.css';
$styles['fancybox']          = $p .'/plugins/fancybox/jquery.fancybox-1.3.4.css';
$styles['custom_scrollbar']  = $p .'/plugins/custom-scrollbar/jquery.mCustomScrollbar.css';

$scripts['slicknav']         = $p .'/plugins/slicknav/jquery.slicknav.min.js';
$scripts['fancybox']         = $p .'/plugins/fancybox/jquery.fancybox-1.3.4.pack.js';
//$scripts['fancybox'][]       = $p .'/plugins/fancybox/helpers/jquery.fancybox-media.js';
$scripts['placeholders']     = $p .'/scripts/placeholders.js';
$scripts['masonry']          = $p .'/scripts/masonry.min.js';
$scripts['skrollr']          = $p .'/scripts/skrollr.js';
$scripts['wowJs']            = $p .'/scripts/wow.min.js';
$scripts['jqueryrotate']     = $p .'/scripts/jqueryrotate.js';
$scripts['custom_scrollbar'] = $p .'/plugins/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js';
ksort($styles);
ksort($scripts);
$names = array_merge(array_keys($styles), array_keys($scripts));

function customize_styles() { ?>
	<style type="text/css">
		.wp-full-overlay {
			z-index: 150000 !important;
		}
	</style>
<?php }


function customize_appearence($wp_customize){
	global $image_controls;

	$wp_customize->add_section( 'view_section' , array(
			'title'      => __('Utseende'),
			'priority'   => 200
	) );

	$image_controls = array();
	$image_controls['theme_logo'] =  add_image_control('theme_logo', 'Logo', 'theme_logo');

	global $image_controls;
	foreach ($image_controls as $id => $control)
		$control->add_tab( 'library',   __( 'Media Library' ), 'library_tab' );

	//Run plugin section
	plugin_section($wp_customize);
}

function plugin_section($wp_customize){
	global $def;
	global $names;
	if($def->access()){
		//Activate plugins
		$wp_customize->add_section( 'generate_resources' , array(
			'title'      => __('Aktivera plugins'),
			'priority'   => 230
		) );

		foreach($names as $v){
			$wp_customize->add_setting('generate_plugin['. $v .']', array(
				'type'       => 'option',
				'default'    => false
			));

			$wp_customize->add_control('generate_plugin_'. $v, array(
				'settings' => 'generate_plugin['. $v .']',
				'label'    => __( ucfirst( str_replace('-css', ' | CSS', str_replace('_', ' ', $v)) ) ),
				'section'  => 'generate_resources',
				'type'     => 'checkbox',
			));
		}
	}
}

function add_image_control( $slug, $label , $setting_name = 'theme_settings') {
	global $wp_customize;
	static $count = 0;
	$id = $setting_name;
	$wp_customize->add_setting( $id, array(
			'type'              => 'option',
			'transport'     => 'postMessage'
	) );


	$control = new WP_Customize_Image_Control( $wp_customize, $slug,
					array(
							'label'         => __( $label),
							'section'       => 'view_section',
							'priority'      => $count,
							'settings'      => $id
					)
			);
	$wp_customize->add_control($control);
	$count++;
	return $control;
}


function library_tab() {
	global $image_controls;
	static $tab_num = 0; // Sync tab to each image control

	$control = array_slice($image_controls, $tab_num, 1);
	?>
	<a class="choose-from-library-link button"
	   data-controller = "<?php esc_attr_e( key($control) ); ?>">
		<?php _e( 'Öppna mediabibliotek' ); ?>
	</a>

	<?php
	$tab_num++;
}

add_action( 'wp_enqueue_scripts', 'sprinter_generate_theme_resources');
function sprinter_generate_theme_resources(){
	global $styles;
	global $scripts;

	//Generate plugins
	$generate = get_option('generate_plugin');
	if($generate){
		foreach($generate as $k => $v){
			if($v){
				$loop = array();
				if(isset($styles[$k])){
					if(!is_array($styles[$k]))
						$loop = array($k => $styles[$k]);
					else
						$loop = $styles[$k];

					foreach($loop as $name => $path){
						wp_register_style('generate_'. $name, $path);
						wp_enqueue_style('generate_'. $name);
					}
				}

				if(isset($scripts[$k])){
					if(!is_array($scripts[$k]))
						$loop = array($k => $scripts[$k]);
					else
						$loop = $scripts[$k];

					foreach($loop as $name => $path){
						wp_register_script('generate_'. $name, $path, array('jquery'));
						wp_enqueue_script('generate_'. $name);
					}
				}
			}
		}
	}
}


?>