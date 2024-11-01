<?php
class menus_class{
	static function get_form(){

		$functions = get_class_methods(__CLASS__);
		foreach($functions as $function){
			if(isset($_POST[$function]))
				self::$function();
		}
?>

		<div class="white-box">
			<div class="padding-hor padding-top border-bottom">
				<h2>Menyer</h2>
			</div>

			<div class="padding-hor padding-top border-bottom">
				<label for="menu_name">Namn:</label>
				<input type="text" name="menu_name" value="" id="menu_name" />
				<input type="submit" name="register_menus" value="Skapa meny" class="button button-primary button-large" />
				<br /><br />

				<?php
				$all_menus = get_option('registered_menus');
				if($all_menus){
					foreach($all_menus as $name)
						echo ' <input type="checkbox" name="remove_menu['. $name .']" value="'. $name .'"> '. $name .'<br />';
					?>
					<br />
					<input type="submit" name="remove_menus" value="Ta bort markerade menyer" class="button button-primary button-small" />
				<?php
				}
				?>
			</div>
		</div>



	<?php
	}

	function register_menus(){
		global $message_class;
		if(isset($_POST['menu_name'])){
			$setting_name = 'registered_menus';
			$name = $_POST['menu_name'];
			$all_menus = get_option($setting_name);

			if($all_menus){
				if(!in_array($name, $all_menus)){
					$all_menus[$name] = $name;
					if(update_option($setting_name, $all_menus))
						$message_class->message['s'][] = 'Menyn är nu registrerad';
				}
				else
					$message_class->message['e'][] = 'Denna meny existerar redan.';

			}
			else{
				if(update_option($setting_name, array($name => $name)))
					$message_class->message['s'][] = 'Menyn är nu registrerad';
			}
		}
	}

	function remove_menus(){
		global $message_class;
		$all_menus = get_option('registered_menus');
		//If there is any checked sidebars, and all_settings is not FALSE.
		if(isset($_POST['remove_menu']) && is_array($_POST['remove_menu']) && $all_menus){
			//Loop through every checked sidebar.
			foreach($_POST['remove_menu'] as $menu){
				//if the checked sidebar exists in all_sidebars, remove it from that array.
				if(isset($all_menus[$menu]))
					unset($all_menus[$menu]);
			}
			//Update the new array with less sidebars.
			if(update_option('registered_menus', $all_menus))
				$message_class->message['s'][] = 'Menyn togs bort utan problem.';
		}
	}

}
?>