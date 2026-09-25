<?php

namespace hoggle\storynotice\acp;

class main_info
{
	public function module()
	{
		return array(
			'filename'	=> '\hoggle\storynotice\acp\main_module',
			'title'		=> 'ACP_STORYNOTICE',
			'version'	=> '1.0.0',
			'modes'		=> array(
				'settings'	=> array(
					'title'	=> 'ACP_STORYNOTICE_SETTINGS',
					'auth'	=> 'acl_a_board',
					'cat'	=> array('ACP_STORYNOTICE'),
				),
			),
		);
	}
}
