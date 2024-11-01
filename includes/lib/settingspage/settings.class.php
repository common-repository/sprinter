<?php

class settings_class{

	static function get_form(){
		$functions = get_class_methods(__CLASS__);
		foreach($functions as $function){
			if(isset($_POST[$function]))
				self::$function();
		}

	?>
		<div class="white-box">
			<div class="padding-hor padding-top border-bottom">
				<h2>Ställ in din WordPressinstallation</h2>
			</div>

			<div class="default-settings padding-hor padding-bottom padding-top">
				<input type="submit" name="set_default" value="Aktivera standardinställningar" id="standard" class="button button-primary button-small" />
				<hr />
				<input type="submit" name="reset_rewrite" value="Generera ny .htaccess" id="reset_rewrite" class="button button-primary button-small" />
				<hr />
<textarea name="page_names" rows="7" cols="30">Hem
Om oss
Kontakt</textarea>
				<br />
				<input type="submit" name="create_pages" value="Skapa standard sidor" id="pages" class="button button-primary button-small" title="Skapa standard sidor (Hem, Om oss, Kontakt)" />
			</div>
		</div>

	<?php
	}

	function set_default(){
		global $message_class;
		global $wp_rewrite;
		global $def;

		//Set permalink structure
		$wp_rewrite->set_permalink_structure( '/%postname%/' );

		//Set default page
		$hem =  $def->get_page_id('hem');
		if($hem){
			update_option( 'page_on_front', $hem );
			update_option( 'show_on_front', 'page' );
		}

		//Remove example page/post
		if(get_permalink(1))
			wp_delete_post(1); //Remove first post (Hello World)

		if(get_permalink(2))
			wp_delete_post(2); //Remove first page (Example page)

		$message_class->message['s'][] = '<strong>Standardinställningarna är nu registrerade.</strong>';
	}

	function create_pages(){
		global $message_class;
		$content = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris dignissim leo vel adipiscing lobortis. Sed consequat, diam eu lacinia volutpat, diam metus dapibus odio, eget laoreet diam ipsum vel ligula. Fusce dignissim, ligula id elementum aliquet, erat justo interdum massa, ut adipiscing nisl nibh ut nibh. Maecenas sollicitudin luctus mi, eu luctus magna placerat quis. In hac habitasse platea dictumst. Aenean nec condimentum ipsum, vel condimentum urna. Donec quis pellentesque turpis. Vestibulum hendrerit nulla eget lorem rhoncus, nec feugiat eros iaculis. In rutrum nibh in arcu pellentesque, sit amet venenatis elit lacinia. Vestibulum volutpat libero vitae est dictum, eget congue felis pharetra. Aliquam quis libero in nunc iaculis semper eu vestibulum ante. Integer gravida tellus nec tristique imperdiet. Duis ac sodales odio. Mauris accumsan odio ut enim ultrices, sit amet rutrum tellus sagittis. Duis luctus sodales viverra. Maecenas nisi libero, consectetur ut felis at, commodo egestas mi.</p>
					<p>Quisque mollis nec elit quis pharetra. Suspendisse sed lobortis augue. Mauris sed lectus orci. Sed in mi semper, vulputate sem nec, dignissim est. Phasellus blandit est nibh, non interdum tortor dignissim ut. Donec sagittis nibh a dolor feugiat feugiat. Aenean nec sollicitudin felis.</p>
					<p>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Sed sollicitudin arcu lacus, sed eleifend velit interdum eget. Suspendisse placerat, arcu in vehicula vestibulum, ligula nibh ornare turpis, ac vestibulum lacus tortor in neque. Donec quis sem non mauris pretium tincidunt. Integer egestas lacus nec dolor volutpat ullamcorper. Donec a ornare enim, venenatis ornare neque. Donec volutpat, sem eu blandit porta, mi quam dictum arcu, eget semper erat tortor adipiscing magna. Aliquam elit arcu, laoreet ut porta ut, pellentesque volutpat dolor. Mauris condimentum non est id feugiat. Proin tristique augue in elit scelerisque feugiat. Morbi mattis nunc ac tortor interdum condimentum consectetur quis arcu.</p>';
		$rows = trim($_POST['page_names']);
		$rows = explode ("\n", $rows);

		$i = 0;
		foreach ($rows as $name) {
			if(!empty($name)){
				if(!get_page_by_title($name)){
					$data = array(
						'post_content'   => __($content),
						'post_name'      => __(strtolower($name)),
						'post_title'     => __($name),
						'post_status'    => 'publish',
						'post_type'      => 'page',
						'menu_order'     => $i,
						'comment_status' => 'closed'
					);
					wp_insert_post($data);

					$message_class->message['s'][] = '<strong>Sidan '. $name .' skapades.</strong>';
					$i++;
				}
				else
					$message_class->message['e'][] = '<strong>Sidan '. $name .' finns redan.</strong>';
			}
		}
	}

	function reset_rewrite(){
		global $message_class;
		flush_rewrite_rules();
		$message_class->message['s'][] = '<strong>En ny .htaccess har blivit genererad!</strong>';
	}

}

?>