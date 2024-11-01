<?php
class message{
	public $message;
}
$message_class = new message();
require_once('settingspage/plugins.class.php');
require_once('settingspage/settings.class.php');
require_once('settingspage/sidebars.class.php');
require_once('settingspage/menus.class.php');

if($def->access())
	$settings_page = new settings_page();

class settings_page{
	function settings_page(){
		add_action('admin_menu', array(&$this, 'setup_theme_admin_menus'));
		add_action('admin_init', array(&$this, 'initialize'));
		add_action('admin_enqueue_scripts', array(&$this, 'enqueue_styles_and_scripts'));
	}

	function initialize(){
		register_setting( 'theme_settings', 'theme_logo');
	}

	function setup_theme_admin_menus() {
		//Menyn ska vara under tools.php / verktyg; Menytext; Sidtitel; Administratörsbehörighet (admin), Meny-slug, Function att köra vid klick.
		add_menu_page(__('Hantera Wordpress'), __('Hantera Wordpress'), 'activate_plugins', 'manage-wordpress', array(&$this, 'option_page'), 'dashicons-smiley', 76);
		add_submenu_page( 'manage-wordpress', 'Standardinställningar', 'Standardinställningar', 'activate_plugins', 'default-settings', array(&$this, 'default_settings_page') );
		add_submenu_page( 'manage-wordpress', 'Plugins & Funktioner', 'Plugins & Funktioner', 'activate_plugins', 'plugins-functions', array(&$this, 'plugins_page') );
	}

	function option_page(){
	?>
		<div class="wrap">
			<form method="post" action="">
				<?php settings_class::get_form(); ?>
				<br /><br />
				<?php plugins_class::get_form(); ?>
				<hr /><hr /><hr />
				<?php sidebars_class::get_form(); ?>
				<hr /><hr /><hr />
				<?php menus_class::get_form(); ?>
			</form>
		</div>

		<?php
		global $message_class;
		if(count($message_class->message) > 0){
			if(isset($message_class->message['s'])){
		?>
			<div id="popup-message" class="success">
				<ul>
					<?php
					foreach($message_class->message['s'] as $key => $data)
						echo '<li>'. $data .'</li>';
					?>
				</ul>
			</div>
			<?php
			}

			if(isset($message_class->message['e'])){
				?>
				<div id="popup-message" class="error">
					<ul>
						<?php
						foreach($message_class->message['e'] as $key => $data)
							echo '<li>'. $data .'</li>';
						?>
					</ul>
				</div>
			<?php
			}

			if(isset($message_class->message['i'])){
			?>
				<div id="popup-message" class="info">
					<ul>
						<?php
						foreach($message_class->message['i'] as $key => $data)
							echo '<li>'. $data .'</li>';
						?>
					</ul>
				</div>
			<?php
			}

			if(isset($message_class->message['info'])){
			?>
				<div id="popup-info">
					<ul>
						<?php
						foreach($message_class->message['info'] as $key => $data)
							echo '<li>'. $data .'</li>';
						?>
					</ul>
				</div>
		<?php
			}
		}
	}

	function default_settings_page(){
	?>
		<h2>Standardinställningar</h2>
		<p>Underlätta ditt arbete genom att ställa in din wordpressinstallation med få enkla knapptryckningar.</p>

		<div class="wrap">
			<form method="post" action="">
				<?php settings_class::get_form(); ?>
			</form>
		</div>
	<?php
	}

	function plugins_page(){
	?>
		<h2>Plugin & Funktioner</h2>
		<p>Här finns funktioner för att aktivera & inaktivera plugins. Funktioner för att fylla plugins och wordpress med standarddata kan du även hitta här.</p>

		<div class="wrap">
			<form method="post" action="">
				<?php plugins_class::get_form(); ?>
			</form>
		</div>
	<?php
	}


	function enqueue_styles_and_scripts() {
		global $def;
		$p = plugin_dir_url( __FILE__ ) .'../../assets';

		wp_enqueue_script('wp-media-uploader', $p .'/js/wp-media-uploader.js', array( ), '1.0', true);

		wp_register_script( 'sprinter-plugin-script', $p .'/js/script.js');
		wp_enqueue_script( 'sprinter-plugin-script' );

		wp_register_style( 'sprinter-plugin-style', $p .'/css/style.css');
		wp_enqueue_style( 'sprinter-plugin-style' );

		wp_enqueue_media();
	}
}
?>
