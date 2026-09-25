<?php

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'HOGGLE_STORYNOTICE_TEXT'	=> 'Please don\'t forget to leave feedback on the stories you read!',
));
