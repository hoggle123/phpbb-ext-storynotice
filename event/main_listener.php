<?php

namespace hoggle\storynotice\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class main_listener implements EventSubscriberInterface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\request\request_interface */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	public function __construct(
		\phpbb\config\config $config,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\request\request_interface $request,
		\phpbb\template\template $template,
		\phpbb\user $user
	)
	{
		$this->config = $config;
		$this->db = $db;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
	}

	public static function getSubscribedEvents()
	{
		return array(
			'core.user_setup'                  => 'load_language',
			'core.viewtopic_modify_page_title' => 'viewtopic_modify_page_title',
		);
	}

	public function load_language($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'hoggle/storynotice',
			'lang_set' => 'common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function viewtopic_modify_page_title($event)
	{
		$topic_data = $event['topic_data'];
		$forum_id = isset($topic_data['forum_id']) ? (int) $topic_data['forum_id'] : 0;
		if ($forum_id < 1 || !$this->forum_enabled($forum_id))
		{
			return;
		}

		if (!$this->is_last_page($topic_data))
		{
			return;
		}

		$text = isset($this->config['hoggle_storynotice_text']) ? trim($this->config['hoggle_storynotice_text']) : '';
		if ($text === '')
		{
			$text = $this->user->lang('HOGGLE_STORYNOTICE_TEXT');
		}

		$this->template->assign_vars(array(
			'S_HOGGLE_STORYNOTICE'	=> true,
			'HOGGLE_STORYNOTICE_TEXT'	=> $text,
		));
	}

	protected function forum_enabled($forum_id)
	{
		$raw = isset($this->config['hoggle_storynotice_forums']) ? $this->config['hoggle_storynotice_forums'] : '';
		foreach (preg_split('/[^0-9]+/', (string) $raw) as $part)
		{
			if ((int) $part === $forum_id)
			{
				return true;
			}
		}
		return false;
	}

	protected function is_last_page(array $topic_data)
	{
		$per_page = isset($this->config['posts_per_page']) ? (int) $this->config['posts_per_page'] : 10;
		if ($per_page < 1)
		{
			$per_page = 10;
		}

		$start = $this->request->variable('start', 0);
		if ($start < 0)
		{
			$start = 0;
		}

		$total = isset($topic_data['topic_posts_approved']) ? (int) $topic_data['topic_posts_approved'] : 0;
		if ($total < 1)
		{
			return true;
		}

		return ($start + $per_page) >= $total;
	}
}
