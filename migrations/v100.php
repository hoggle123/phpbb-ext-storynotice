<?php

namespace hoggle\storynotice\migrations;

class v100 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['hoggle_storynotice_forums']);
	}

	public static function depends_on()
	{
		return array('\phpbb\db\migration\data\v330\v330');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('hoggle_storynotice_forums', '')),
			array('config.add', array('hoggle_storynotice_text', '')),
			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_STORYNOTICE',
			)),
			array('module.add', array(
				'acp',
				'ACP_STORYNOTICE',
				array(
					'module_basename'	=> '\hoggle\storynotice\acp\main_module',
					'modes'				=> array('settings'),
				),
			)),
		);
	}
}
