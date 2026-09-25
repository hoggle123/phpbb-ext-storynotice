<?php

namespace hoggle\storynotice\migrations;

class v110 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['hoggle_storynotice_show_bottom']);
	}

	public static function depends_on()
	{
		return array('\hoggle\storynotice\migrations\v100');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('hoggle_storynotice_show_top', '0')),
			array('config.add', array('hoggle_storynotice_show_bottom', '1')),
		);
	}
}
