<?php
/*************************************************************************************
 * smarty.php
 * ----------
 * Author: Alan Juden (alan@judenware.org)
 * Copyright: (c) 2004 Alan Juden, Nigel McNie (http://qbnz.com/highlighter/)
 * Release Version: 1.0.7.15
 * CVS Revision Version: $Revision: 119 $
 * Date Started: 2004/07/10
 * Last Modified: $Date: 2006-11-12 10:42:38 +0000 (Sun, 12 Nov 2006) $
 *
 * Smarty template language file for GeSHi.
 *
 * CHANGES
 * -------
 * 2004/11/27 (1.0.0)
 *  -  Initial Release
 *
 * TODO
 * ----
 *
 *************************************************************************************
 *
 *     This file is part of GeSHi.
 *
 *   GeSHi is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   GeSHi is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with GeSHi; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 ************************************************************************************/

$language_data = array (
	'LANG_NAME' => 'Smarty',
	'COMMENT_SINGLE' => array(),
	'COMMENT_MULTI' => array('{*' => '*}'),
	'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
	'QUOTEMARKS' => array("'", '"'),
	'ESCAPE_CHAR' => '\\',
	'KEYWORDS' => array(
		1 => array(
			'$smarty', 'now', 'const', 'capture', 'config', 'section', 'foreach', 'template', 'version', 'ldelim', 'rdelim',
			'config_load', 'foreachelse', 'include', 'include_php', 'insert', 'if', 'elseif', 'else', 'php',
			'sectionelse', 'clear_all_cache', 'clear_cache', 'is_cached',
			),
		2 => array(
			'capitalize', 'count_characters', 'cat', 'count_paragraphs', 'count_sentences', 'count_words', 'date_format',
			'default', 'escape', 'indent', 'lower', 'nl2br', 'regex_replace', 'replace', 'spacify', 'string_format',
			'strip', 'strip_tags', 'truncate', 'upper', 'wordwrap'
			),
		3 => array(
			'assign', 'counter', 'cycle', 'debug', 'eval', 'fetch', 'html_checkboxes', 'html_image', 'html_options',
			'html_radios', 'html_select_date', 'html_select_time', 'html_table', 'math', 'mailto', 'popup_init',
			'popup', 'textformat'
			),
		4 => array(
			'$template_dir', '$compile_dir', '$config_dir', '$plugins_dir', '$debugging', '$debug_tpl',
			'$debugging_ctrl', '$autoload_filters', '$compile_check', '$force_compile', '$caching', '$cache_dir',
			'$cache_lifetime', '$cache_handler_func', '$cache_modified_check', '$config_overwrite',
			'$config_booleanize', '$config_read_hidden', '$config_fix_newlines', '$default_template_handler_func',
			'$php_handling', '$security', '$secure_dir', '$security_settings', '$trusted_dir', '$left_delimiter',
			'$right_delimiter', '$compiler_class', '$request_vars_order', '$request_use_auto_globals',
			'$error_reporting', '$compile_id', '$use_sub_dirs', '$default_modifiers', '$default_resource_type'
			),
		5 => array(
			'append', 'append_by_ref', 'assign', 'assign_by_ref', 'clear_all_assign', 'clear_all_cache',
			'clear_assign', 'clear_cache', 'clear_compiled_tpl', 'clear_config', 'config_load', 'display',
			'fetch', 'get_config_vars', 'get_registered_object', 'get_template_vars', 'is_cached',
			'load_filter', 'register_block', 'register_compiler_function', 'register_function',
			'register_modifier', 'register_object', 'register_outputfilter', 'register_postfilter',
			'register_prefilter', 'register_resource', 'trigger_error', 'template_exists', 'unregister_block',
			'unregister_compiler_function', 'unregister_function', 'unregister_modifier', 'unregister_object',
			'unregister_outputfilter', 'unregister_postfilter', 'unregister_prefilter', 'unregister_resource'
			),
		6 => array(
			'name', 'assign', 'file', 'scope', 'global', 'key', 'once', 'script',
			'loop', 'start', 'step', 'max', 'show', 'values', 'value', 'from', 'item'
			),
		7 => array(
			'eq', 'neq', 'ne', 'lte', 'gte', 'ge', 'le', 'not', 'mod'
			),
		),
	'SYMBOLS' => array(
		'/', '=', '==', '!=', '>', '<', '>=', '<=', '!', '%'
		),
	'CASE_SENSITIVE' => array(
		GESHI_COMMENTS => false,
		1 => false,
		2 => false,
		3 => false,
		4 => false,
		5 => false,
		6 => false,
		7 => false,
		),
	'STYLES' => array(
		'KEYWORDS' => array(
			1 => 'color: #0600FF;',		//Functions
			2 => 'color: #008000;',		//Modifiers
			3 => 'color: #0600FF;',		//Custom Functions
			4 => 'color: #804040;',		//Variables
			5 => 'color: #008000;',		//Methods
			6 => 'color: #6A0A0A;',		//Attributes
			7 => 'color: #D36900;'		//Text-based symbols
			),
		'COMMENTS' => array(
			'MULTI' => 'color: #008080; font-style: italic;'
			),
		'ESCAPE_CHAR' => array(
			0 => 'color: #000099; font-weight: bold;'
			),
		'BRACKETS' => array(
			0 => 'color: #D36900;'
			),
		'STRINGS' => array(
			0 => 'color: #ff0000;'
			),
		'NUMBERS' => array(
			0 => 'color: #cc66cc;'
			),
		'METHODS' => array(
			1 => 'color: #006600;'
			),
		'SYMBOLS' => array(
			0 => 'color: #D36900;'
			),
		'SCRIPT' => array(
            0 => ''
			),
		'REGEXPS' => array(
			)
		),
	'URLS' => array(
		1 => 'http://smarty.php.net/{FNAME}',
		2 => 'http://smarty.php.net/{FNAME}',
		3 => 'http://smarty.php.net/{FNAME}',
		4 => 'http://smarty.php.net/{FNAME}',
		5 => 'http://smarty.php.net/{FNAME}',
		6 => '',
		7 => 'http://smarty.php.net/{FNAME}'
		),
	'OOLANG' => true,
	'OBJECT_SPLITTERS' => array(
		1 => '.'
		),
	'REGEXPS' => array(
		),
	'STRICT_MODE_APPLIES' => GESHI_ALWAYS,
	'SCRIPT_DELIMITERS' => array(
		0 => array(
			'{' => '}'
			)
	),
	'HIGHLIGHT_STRICT_BLOCK' => array(
		0 => true
		)
);

?>
