<?php
class sidebars_class{

	static function get_form(){

		$functions = get_class_methods(__CLASS__);
		foreach($functions as $function){
			if(isset($_POST[$function]))
				self::$function();
		}
?>

		<div class="white-box">
			<div class="padding-hor padding-top border-bottom">
				<h2>Sidebars / Widgets</h2>
			</div>
			<div class="padding-hor padding-top border-bottom">
				<input type="text" name="sidebar_name" value="" />
				<input type="submit" name="create_sidebar" value="Skapa sidebar" id="create_sidebar" class="button button-primary button-large" />

				<br /><br />

				<?php
				$all = get_option('registered_sidebars');
				if($all){
					echo '<ul>';
					foreach($all as $sidebar)
						echo '<li><input type="checkbox" name="remove_sidebar['. $sidebar .']" value="'. $sidebar .'">'. $sidebar .'</li>';

					echo '<li>&nbsp;</li><li><input type="submit" name="remove_sidebars" value="Ta bort markerade sidebars" class="button button-primary button-small" /></li>';
					echo '</ul';
				}
				?>
			</div>
		</div>

		</div>
<?php
	}
	function create_sidebar(){
		global $message_class;
		//If the sidebar_name field is not empty.
		if(isset($_POST['sidebar_name']) && !empty($_POST['sidebar_name'])){
			$name = strtolower($_POST['sidebar_name']);
			//Get all the current registered sidebars.
			$all_sidebars = get_option('registered_sidebars');

			if($all_sidebars){
				//Check if it already exists.
				if(!in_array($name, $all_sidebars)){
					//Add the new sidebar to the array with all sidebars.
					$all_sidebars[$name] = $name;
					//Update the extended array of all sidebars.
					update_option('registered_sidebars', $all_sidebars);
				}
				else
					$message_class->message['e'][] = 'Denna sidebar existerar redan.';
			}
			else
				update_option('registered_sidebars', array($name => $name));

			$message_class->message['s'][] = 'Sidebaren är nu registrerad.';
		}
		else
			$message_class->message['e'][] = 'Du måste fylla i ett namn på sidebaren.';
	}

	function remove_sidebars(){
		global $message_class;
		$all_sidebars = get_option('registered_sidebars');
		//If there is any checked sidebars, and all_settings is not FALSE.
		if(isset($_POST['remove_sidebar']) && is_array($_POST['remove_sidebar']) && $all_sidebars){
			//Loop through every checked sidebar.
			foreach($_POST['remove_sidebar'] as $sidebar){
				//if the checked sidebar exists in all_sidebars, remove it from that array.
				if(isset($all_sidebars[$sidebar]))
					unset($all_sidebars[$sidebar]);
			}
		}
		//Update the new array with less sidebars.
		update_option('registered_sidebars', $all_sidebars);

		$message_class->message['s'][] = 'Sidebaren togs bort utan problem.';
	}
}
?>