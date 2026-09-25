<?php

namespace hoggle\storynotice\acp;

class main_module
{
	public $u_action;
	public $tpl_name;
	public $page_title;

	public function main($id, $mode)
	{
		global $config, $db, $request, $template, $user;

		$user->add_lang_ext('hoggle/storynotice', 'acp_storynotice');

		$this->tpl_name = 'acp_storynotice';
		$this->page_title = $user->lang('STORYNOTICE_TITLE');

		add_form_key('hoggle_storynotice');

		if ($request->is_set_post('submit'))
		{
			if (!check_form_key('hoggle_storynotice'))
			{
				trigger_error($user->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			$picked = $request->variable('forums', array(0));
			$ids = array();
			foreach ($picked as $forum_id)
			{
				$forum_id = (int) $forum_id;
				if ($forum_id > 0)
				{
					$ids[$forum_id] = $forum_id;
				}
			}

			$config->set('hoggle_storynotice_forums', implode(',', $ids));
			$config->set('hoggle_storynotice_text', $request->variable('notice_text', '', true));

			trigger_error($user->lang('STORYNOTICE_SAVED') . adm_back_link($this->u_action));
		}

		$selected = array();
		$raw = isset($config['hoggle_storynotice_forums']) ? $config['hoggle_storynotice_forums'] : '';
		foreach (preg_split('/[^0-9]+/', (string) $raw) as $part)
		{
			id = (int) $part;
			if ($id > 0)
			{
				$selected[$id] = $id;
			}
		}

		$sql = 'SELECT forum_id, forum_name, forum_type, left_id
			FROM ' . FORUMS_TABLE . '
			WHERE forum_type = ' . FORUM_POST . '
			ORDER BY left_id';
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$forum_id = (int) $row['forum_id'];
			$template->assign_block_vars('forums', array(
				'ID'		=> $forum_id,
				'NAME'		=> $row['forum_name'],
				'S_CHECKED'	=> isset($selected[$forum_id]),
			));
		}
		$db->sql_freeresult($result);

		$text = isset($config['hoggle_storynotice_text']) ? $config['hoggle_storynotice_text'] : '';

		$template->assign_vars(array(
			'U_ACTION'		=> $this->u_action,
			'NOTICE_TEXT'	=> $text,
		));
	}
}
