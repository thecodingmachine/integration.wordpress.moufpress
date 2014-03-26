<?php
/*
 * Copyright (c) 2014 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */

namespace Mouf\Integration\Wordpress\Moufpress;

use Mouf\Html\HtmlElement\HtmlElementInterface;
use Mouf\MoufManager;

/**
 * A Wordpress widget that opens the possibility to display any instance declared in Mouf and implementing
 * the HtmlElementInterface interface.
 * 
 */
class MoufpressWidget extends \WP_Widget {

	public function __construct() {
		parent::WP_Widget('moufpress_widget', 'Moufpress Widget', 
				array(
						'description' => 'Use this widget to display any instance declared in Mouf2 and implementing the HtmlElementInterface interface.'
				));
	}

	/** 
	 * Display widget 
	 */	
	public function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		echo $before_widget;
		$title = empty($instance['title']) ? null : apply_filters('widget_title', $instance['title']);
		if ( !empty( $title ) ) {
			echo $before_title . $title . $after_title; 
		};
		
		MoufManager::getMoufManager()->get($instance['instance'])->toHtml();
		
		echo $after_widget; 
	}
	
	/**
	 * update/save function
	 */
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance ['title'] = strip_tags ( $new_instance ['title'] );
		$instance ['instance'] = strip_tags ( $new_instance ['instance'] );
		return $instance;
	}
	
	/**
	 * admin control form
	 */
	function form($instance) {
		$default = array (
				'title' => '',
				'instance' => null 
		);
		$instance = wp_parse_args ( ( array ) $instance, $default );
		$title_id = $this->get_field_id ( 'title' );
		$title_name = $this->get_field_name ( 'title' );
		echo '<p>
				<label for="' . $title_id . '">' . __ ( 'Title' ) . 
			': <input type="text" class="widefat" id="' . $title_id . '" name="' . $title_name . '" value="' . esc_attr ( $instance ['title'] ) . '" />
			</label></p>';
		
		$instance_id = $this->get_field_id ( 'instance' );
		$instance_name = $this->get_field_name ( 'instance' );
		
		$moufManager = MoufManager::getMoufManager();
		$instances = $moufManager->findInstances('Mouf\\Html\\HtmlElement\\HtmlElementInterface');
		sort($instances);
		
		echo '<p>
				<label for="' . $instance_id . '">' . __ ( 'Instance' ) .':
				<select class="widefat" id="' . $instance_id . '" name="' . $instance_name . '" >
					';
		foreach ($instances as $name) {
			if ($moufManager->isInstanceAnonymous($name)) {
				continue;
			}
			?>
			<option value="<?php echo esc_attr($name); ?>" <?php if ($name == $instance ['instance']) echo "selected='selected'"; ?>><?php echo esc_html($name); ?></option>
			<?php 
		}
		echo '
				</select>
			</label></p>';
	}
}
