<?php
class image_sizes_class{

	private static $message = array();
	private function message($function){
		if(isset(self::$message[$function]))
			echo self::$message[$function];
	}
	static function get_form(){

		$functions = get_class_methods(__CLASS__);
		foreach($functions as $function){
			if(isset($_POST[$function]))
				self::$function();
		}
?>
		<h2>Hantera bildstorlekar</h2>
		<p>Ställ in vilka olika bildstorlekar som wordpress ska använda vid bilduppladdningar.</p>
		<table class="form-table">
			<tr>
				<th><label for="img_name">Namn:</label></th>
				<td><input type="text" name="img_name" value="" id="img_name" /></td>
			</tr>
			<tr>
				<th><label for="img_width">Max bredd:</label></th>
				<td><input type="text" name="img_width" value="" id="img_width" /></td>
			</tr>
			<tr>
				<th><label for="img_height">Max höjd:</label></th>
				<td><input type="text" name="img_height" value="" id="img_height" /> (Lämna tom för att inte justera höjden)</td>
			</tr>
			<tr>
				<th><label for="img_crop">Beskär:</label></th>
				<td><input type="radio" name="img_crop" value="true" id="img_crop" /> Ja<br />
					<input type="radio" name="img_crop" value="false" id="img_crop" checked /> Nej
				</td>
			</tr>
			<tr>
				<th></th><td><input type="submit" name="add_image_size" value="Skapa bildproportioner" /></td> <td><?php self::message('add_image_size'); ?></td>
			</tr>
			<?php
			$all_sizes = get_option('registered_img_sizes');
			if($all_sizes){
				foreach($all_sizes as $name => $data){
					?>
					<tr>
						<th><?php echo $name; ?></th>
						<td><input type="checkbox" name="remove_imagesize[<?php echo $name; ?>]" value="<?php echo $name; ?>"> Bredd: <strong><?php echo $data['img_width']; ?></strong> | Höjd: <strong><?php echo $data['img_height']; ?></strong> | Beskär: <strong><?php echo $data['img_crop']; ?></strong></td>
					</tr>
				<?php
				}
				?>
				<tr>
					<td></td> <td><input type="submit" name="remove_imagesizes" value="Ta bort markerade bildstorlekar" /></td><td><?php self::message('remove_imagesizes'); ?></td>
				</tr>
			<?php
			}
			?>
		</table>
<?php
	}

	function add_image_size(){
		$fields = array('img_name', 'img_width', 'img_height', 'img_crop');
		$empty = FALSE;
		$data = array();

		if(!empty($_POST['img_name'])){
			foreach($fields as $field){
				if(!empty($_POST[$field]))
					$data[$field] = strtolower($_POST[$field]);
				else
					$data[$field] = 0;
			}

			$setting_name = 'registered_img_sizes';
			$img_name = $_POST['img_name'];
			$all_sizes = get_option($setting_name);

			if($all_sizes){
				if(!in_array($img_name, array_keys($all_sizes))){
					$all_sizes[$img_name] = $data;
					update_option($setting_name, $all_sizes);
					self::$message[__FUNCTION__] = '<strong>Bildproportionen är nu skapad</strong>';
				}
				else
					self::$message[__FUNCTION__] = '<strong>Denna bildproportion existerar redan.</strong>';

			}
			else
				update_option($setting_name, array($img_name => $data));
		}
		else
			self::$message[__FUNCTION__] = '<strong>Du måste fylla i ett namn för bildproportionen</strong>';
	}

	function remove_imagesizes(){
		$all_imagesizes = get_option('registered_img_sizes');
		//If there is any checked sidebars, and all_settings is not FALSE.
		if(isset($_POST['remove_imagesizes']) && is_array($_POST['remove_imagesize']) && $all_imagesizes){
			//Loop through every checked sidebar.
			foreach($_POST['remove_imagesize'] as $imagesize){
				//if the checked sidebar exists in all_sidebars, remove it from that array.
				if(isset($all_imagesizes[$imagesize]))
					unset($all_imagesizes[$imagesize]);
			}
			//Update the new array with less sidebars.
			update_option('registered_img_sizes', $all_imagesizes);
			self::$message[__FUNCTION__] = '<strong>Bildproportionen togs bort utan problem.</strong>';
		}
		else
			self::$message[__FUNCTION__] = '<strong>Du måste välja minst en bildproportion som du inte ska använda längre.</strong>';
	}
}

?>