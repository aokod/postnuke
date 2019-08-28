<?php
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001 by the PostNuke Development Team.
// http://www.postnuke.com/
// ----------------------------------------------------------------------
// Based on:
// Thatware - http://thatware.org/
// PHP-NUKE Web Portal System - http://phpnuke.org/
// ----------------------------------------------------------------------
// LICENSE
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file:
// Purpose of file:
// ----------------------------------------------------------------------


//$prefix = $pnconfig['prefix'];
GLOBAL $prefix;

$pntable = array();

$autolinks = $prefix . '_autolinks';
$pntable['autolinks'] = $autolinks;
$pntable['autolinks_column'] = array ('lid' => $autolinks . '.lid',
                                      'keyword' => $autolinks . '.keyword',
                                      'title' => $autolinks . '.title',
                                      'url' => $autolinks . '.url',
                                      'comment' => $autolinks . '.comment');

$autonews = $prefix . '_autonews';
$pntable['autonews'] = $autonews;
$pntable['autonews_column'] = array ('anid' => $autonews . '.anid',
                                     'catid' => $autonews . '.catid',
                                     'aid' => $autonews . '.aid',
                                     'title' => $autonews . '.title',
                                     'time' => $autonews . '.time',
                                     'hometext' => $autonews . '.hometext',
                                     'bodytext' => $autonews . '.bodytext',
                                     'topic' => $autonews . '.topic',
                                     'informant' => $autonews . '.informant',
                                     'notes' => $autonews . '.notes',
                                     'ihome' => $autonews . '.ihome',
                                     'alanguage' => $autonews . '.alanguage',
                                     'withcomm' => $autonews . '.withcomm');

$banner = $prefix . '_banner';
$pntable['banner'] = $banner;
$pntable['banner_column'] = array ('bid' => $banner . '.bid',
                                   'cid' => $banner . '.cid',
                                   'imptotal' => $banner . '.imptotal',
                                   'impmade' => $banner . '.impmade',
                                   'clicks' => $banner . '.clicks',
                                   'imageurl' => $banner . '.imageurl',
                                   'clickurl' => $banner . '.clickurl',
                                   'date' => $banner . '.date');

$bannerclient = $prefix . '_bannerclient';
$pntable['bannerclient'] = $bannerclient;
$pntable['bannerclient_column'] = array ('cid' => $bannerclient . '.cid',
                                         'name' => $bannerclient . '.name',
                                         'contact' => $bannerclient . '.contact',
                                         'email' => $bannerclient . '.email',
                                         'login' => $bannerclient . '.login',
                                         'passwd' => $bannerclient . '.passwd',
                                         'extrainfo' => $bannerclient . '.extrainfo');

$bannerfinish = $prefix . '_bannerfinish';
$pntable['bannerfinish'] = $bannerfinish;
$pntable['bannerfinish_column'] = array ('bid' => $bannerfinish . '.bid',
                                         'cid' => $bannerfinish . '.cid',
                                         'impressions' => $bannerfinish . '.impressions',
                                         'clicks' => $bannerfinish . '.clicks',
                                         'datestart' => $bannerfinish . '.datestart',
                                         'dateend' => $bannerfinish . '.dateend');

$blocks = $prefix . '_blocks';
$pntable['blocks'] = $blocks;
$pntable['blocks_column'] = array ('bid' => $blocks . '.bid',
                                   'bkey' => $blocks . '.bkey',
                                   'title' => $blocks . '.title',
                                   'content' => $blocks . '.content',
                                   'url' => $blocks . '.url',
                                   'mid' => $blocks . '.mid',
                                   'position' => $blocks . '.position',
                                   'weight' => $blocks . '.weight',
                                   'active' => $blocks . '.active',
                                   'refresh' => $blocks . '.refresh',
                                   'last_update' => $blocks . '.last_update',
                                   'blanguage' => $blocks . '.blanguage');

$blocks_buttons = $prefix . '_blocks_buttons';
$pntable['blocks_buttons'] = $blocks_buttons;
$pntable['blocks_buttons_column'] = array ('id' => $blocks_buttons . '.id',
                                           'bid' => $blocks_buttons . '.bid',
                                           'title' => $blocks_buttons . '.title',
                                           'url' => $blocks_buttons . '.url',
                                           'images' => $blocks_buttons . '.images');

$comments = $prefix . '_comments';
$pntable['comments'] = $comments;
$pntable['comments_column'] = array ('tid' => $comments . '.tid',
                                     'pid' => $comments . '.pid',
                                     'sid' => $comments . '.sid',
                                     'date' => $comments . '.date',
                                     'name' => $comments . '.name',
                                     'email' => $comments . '.email',
                                     'url' => $comments . '.url',
                                     'host_name' => $comments . '.host_name',
                                     'subject' => $comments . '.subject',
                                     'comment' => $comments . '.comment',
                                     'score' => $comments . '.score',
                                     'reason' => $comments . '.reason');

$counter = $prefix . '_counter';
$pntable['counter'] = $counter;
$pntable['counter_column'] = array ('type' => $counter . '.type',
                                    'var' => $counter . '.var',
                                    'count' => $counter . '.count');

$downloads_categories = $prefix . '_downloads_categories';
$pntable['downloads_categories'] = $downloads_categories;
$pntable['downloads_categories_column'] = array ('cid' => $downloads_categories . '.cid',
                                                 'title' => $downloads_categories . '.title',
                                                 'cdescription' => $downloads_categories . '.cdescription');

$downloads_downloads = $prefix . '_downloads_downloads';
$pntable['downloads_downloads'] = $downloads_downloads;
$pntable['downloads_downloads_column'] = array ('lid' => $downloads_downloads . '.lid',
                                                'cid' => $downloads_downloads . '.cid',
                                                'sid' => $downloads_downloads . '.sid',
                                                'title' => $downloads_downloads . '.title',
                                                'url' => $downloads_downloads . '.url',
                                                'description' => $downloads_downloads . '.description',
                                                'date' => $downloads_downloads . '.date',
                                                'name' => $downloads_downloads . '.name',
                                                'email' => $downloads_downloads . '.email',
                                                'hits' => $downloads_downloads . '.hits',
                                                'submitter' => $downloads_downloads . '.submitter',
                                                'downloadratingsummary' => $downloads_downloads . '.downloadratingsummary',
                                                'totalvotes' => $downloads_downloads . '.totalvotes',
                                                'totalcomments' => $downloads_downloads . '.totalcomments',
                                                'filesize' => $downloads_downloads . '.filesize',
                                                'version' => $downloads_downloads . '.version',
                                                'homepage' => $downloads_downloads . '.homepage');

$downloads_editorials = $prefix . '_downloads_editorials';
$pntable['downloads_editorials'] = $downloads_editorials;
$pntable['downloads_editorials_column'] = array ('downloadid' => $downloads_editorials . '.downloadid',
                                                 'adminid' => $downloads_editorials . '.adminid',
                                                 'editorialtimestamp' => $downloads_editorials . '.editorialtimestamp',
                                                 'editorialtext' => $downloads_editorials . '.editorialtext',
                                                 'editorialtitle' => $downloads_editorials . '.editorialtitle');

$downloads_modrequest = $prefix . '_downloads_modrequest';
$pntable['downloads_modrequest'] = $downloads_modrequest;
$pntable['downloads_modrequest_column'] = array ('requestid' => $downloads_modrequest . '.requestid',
                                                 'lid' => $downloads_modrequest . '.lid',
                                                 'cid' => $downloads_modrequest . '.cid',
                                                 'sid' => $downloads_modrequest . '.sid',
                                                 'title' => $downloads_modrequest . '.title',
                                                 'url' => $downloads_modrequest . '.url',
                                                 'description' => $downloads_modrequest . '.description',
                                                 'modifysubmitter' => $downloads_modrequest . '.modifysubmitter',
                                                 'brokendownload' => $downloads_modrequest . '.brokendownload',
                                                 'name' => $downloads_modrequest . '.name',
                                                 'email' => $downloads_modrequest . '.email',
                                                 'filesize' => $downloads_modrequest . '.filesize',
                                                 'version' => $downloads_modrequest . '.version',
                                                 'homepage' => $downloads_modrequest . '.homepage');

$downloads_newdownload = $prefix . '_downloads_newdownload';
$pntable['downloads_newdownload'] = $downloads_newdownload;
$pntable['downloads_newdownload_column'] = array ('lid' => $downloads_newdownload . '.lid',
                                                  'cid' => $downloads_newdownload . '.cid',
                                                  'sid' => $downloads_newdownload . '.sid',
                                                  'title' => $downloads_newdownload . '.title',
                                                  'url' => $downloads_newdownload . '.url',
                                                  'description' => $downloads_newdownload . '.description',
                                                  'name' => $downloads_newdownload . '.name',
                                                  'email' => $downloads_newdownload . '.email',
                                                  'submitter' => $downloads_newdownload . '.submitter',
                                                  'filesize' => $downloads_newdownload . '.filesize',
                                                  'version' => $downloads_newdownload . '.version',
                                                  'homepage' => $downloads_newdownload . '.homepage');

$downloads_subcategories = $prefix . '_downloads_subcategories';
$pntable['downloads_subcategories'] = $downloads_subcategories;
$pntable['downloads_subcategories_column'] = array ('sid' => $downloads_subcategories . '.sid',
                                                    'cid' => $downloads_subcategories . '.cid',
                                                    'title' => $downloads_subcategories . '.title');

$downloads_votedata = $prefix . '_downloads_votedata';
$pntable['downloads_votedata'] = $downloads_votedata;
$pntable['downloads_votedata_column'] = array ('ratingdbid' => $downloads_votedata . '.ratingdbid',
                                               'ratinglid' => $downloads_votedata . '.ratinglid',
                                               'ratinguser' => $downloads_votedata . '.ratinguser',
                                               'rating' => $downloads_votedata . '.rating',
                                               'ratinghostname' => $downloads_votedata . '.ratinghostname',
                                               'ratingcomments' => $downloads_votedata . '.ratingcomments',
                                               'ratingtimestamp' => $downloads_votedata . '.ratingtimestamp');

$ephem = $prefix . '_ephem';
$pntable['ephem'] = $ephem;
$pntable['ephem_column'] = array ('eid' => $ephem . '.eid',
                                  'did' => $ephem . '.did',
                                  'mid' => $ephem . '.mid',
                                  'yid' => $ephem . '.yid',
                                  'content' => $ephem . '.content',
                                  'elanguage' => $ephem . '.elanguage');

$faqanswer = $prefix . '_faqanswer';
$pntable['faqanswer'] = $faqanswer;
$pntable['faqanswer_column'] = array ('id' => $faqanswer . '.id',
                                      'id_cat' => $faqanswer . '.id_cat',
                                      'question' => $faqanswer . '.question',
                                      'answer' => $faqanswer . '.answer',
                                      'submittedby' => $faqanswer . '.submittedby');

$faqcategories = $prefix . '_faqcategories';
$pntable['faqcategories'] = $faqcategories;
$pntable['faqcategories_column'] = array ('id_cat' => $faqcategories . '.id_cat',
                                          'categories' => $faqcategories . '.categories',
                                          'flanguage' => $faqcategories . '.flanguage',
                                          'parent_id' => $faqcategories . '.parent_id');

$group_membership = $prefix . '_group_membership';
$pntable['group_membership'] = $group_membership;
$pntable['group_membership_column'] = array ('gid' => $group_membership . '.gid',
                                             'uid' => $group_membership . '.uid');

$group_perms = $prefix . '_group_perms';
$pntable['group_perms'] = $group_perms;
$pntable['group_perms_column'] = array ('pid' => $group_perms . '.pid',
                                        'gid' => $group_perms . '.gid',
                                        'sequence' => $group_perms . '.sequence',
                                        'realm' => $group_perms . '.realm',
                                        'component' => $group_perms . '.component',
                                        'instance' => $group_perms . '.instance',
                                        'level' => $group_perms . '.level',
                                        'bond' => $group_perms . '.bond');

$groups = $prefix . '_groups';
$pntable['groups'] = $groups;
$pntable['groups_column'] = array ('gid' => $groups . '.gid',
                                   'name' => $groups . '.name');

$headlines = $prefix . '_headlines';
$pntable['headlines'] = $headlines;
$pntable['headlines_column'] = array ('id' => $headlines . '.id',
                                      'sitename' => $headlines . '.sitename',
                                      'rssuser' => $headlines . '.rssuser',
                                      'rsspasswd' => $headlines . '.rsspasswd',
                                      'use_proxy' => $headlines . '.use_proxy',
                                      'rssurl' => $headlines . '.rssurl',
                                      'maxrows' => $headlines . '.maxrows',
                                      'siteurl' => $headlines . '.siteurl',
                                      'options' => $headlines . '.options');

$links_categories = $prefix . '_links_categories';
$pntable['links_categories'] = $links_categories;
$pntable['links_categories_column'] = array ('cat_id' => $links_categories . '.cat_id',
                                             'parent_id' => $links_categories . '.parent_id',
                                             'title' => $links_categories . '.title',
                                             'cdescription' => $links_categories . '.cdescription');

$links_editorials = $prefix . '_links_editorials';
$pntable['links_editorials'] = $links_editorials;
$pntable['links_editorials_column'] = array ('linkid' => $links_editorials . '.linkid',
                                             'adminid' => $links_editorials . '.adminid',
                                             'editorialtimestamp' => $links_editorials . '.editorialtimestamp',
                                             'editorialtext' => $links_editorials . '.editorialtext',
                                             'editorialtitle' => $links_editorials . '.editorialtitle');

$links_links = $prefix . '_links_links';
$pntable['links_links'] = $links_links;
$pntable['links_links_column'] = array ('lid' => $links_links . '.lid',
                                        'cat_id' => $links_links . '.cat_id',
                                        'title' => $links_links . '.title',
                                        'url' => $links_links . '.url',
                                        'description' => $links_links . '.description',
                                        'date' => $links_links . '.date',
                                        'name' => $links_links . '.name',
                                        'email' => $links_links . '.email',
                                        'hits' => $links_links . '.hits',
                                        'submitter' => $links_links . '.submitter',
                                        'linkratingsummary' => $links_links . '.linkratingsummary',
                                        'totalvotes' => $links_links . '.totalvotes',
                                        'totalcomments' => $links_links . '.totalcomments');

$links_modrequest = $prefix . '_links_modrequest';
$pntable['links_modrequest'] = $links_modrequest;
$pntable['links_modrequest_column'] = array ('requestid' => $links_modrequest . '.requestid',
                                             'lid' => $links_modrequest . '.lid',
                                             'cat_id' => $links_modrequest . '.cat_id',
                                             'title' => $links_modrequest . '.title',
                                             'url' => $links_modrequest . '.url',
                                             'description' => $links_modrequest . '.description',
                                             'modifysubmitter' => $links_modrequest . '.modifysubmitter',
                                             'brokenlink' => $links_modrequest . '.brokenlink');

$links_newlink = $prefix . '_links_newlink';
$pntable['links_newlink'] = $links_newlink;
$pntable['links_newlink_column'] = array ('lid' => $links_newlink . '.lid',
                                          'cat_id' => $links_newlink . '.cat_id',
                                          'title' => $links_newlink . '.title',
                                          'url' => $links_newlink . '.url',
                                          'description' => $links_newlink . '.description',
                                          'name' => $links_newlink . '.name',
                                          'email' => $links_newlink . '.email',
                                          'submitter' => $links_newlink . '.submitter');

$languages_constant = $prefix . '_languages_constant';
$pntable['languages_constant'] = $languages_constant;

$languages_file = $prefix . '_languages_file';
$pntable['languages_file'] = $languages_file;

$languages_translation = $prefix . '_languages_translation';
$pntable['languages_translation'] = $languages_translation;

$links_subcategories = $prefix . '_links_subcategories';
$pntable['links_subcategories'] = $links_subcategories;

$links_votedata = $prefix . '_links_votedata';
$pntable['links_votedata'] = $links_votedata;
$pntable['links_votedata_column'] = array ('ratingdbid' => $links_votedata . '.ratingdbid',
                                           'ratinglid' => $links_votedata . '.ratinglid',
                                           'ratinguser' => $links_votedata . '.ratinguser',
                                           'rating' => $links_votedata . '.rating',
                                           'ratinghostname' => $links_votedata . '.ratinghostname',
                                           'ratingcomments' => $links_votedata . '.ratingcomments',
                                           'ratingtimestamp' => $links_votedata . '.ratingtimestamp');

$message = $prefix . '_message';
$pntable['message'] = $message;
$pntable['message_column'] = array ('mid' => $message . '.mid',
                                    'title' => $message . '.title',
                                    'content' => $message . '.content',
                                    'date' => $message . '.date',
                                    'expire' => $message . '.expire',
                                    'active' => $message . '.active',
                                    'view' => $message . '.view',
                                    'mlanguage' => $message . '.mlanguage');

$module_vars = $prefix . '_module_vars';
$pntable['module_vars'] = $module_vars;
$pntable['module_vars_column'] = array ('id' => $module_vars . '.id',
                                        'modname' => $module_vars . '.modname',
                                        'name' => $module_vars . '.name',
                                        'value' => $module_vars . '.value',
                                        'vtype' => $module_vars . '.vtype',
                                        'server' => $module_vars . '.server');

$modules = $prefix . '_modules';
$pntable['modules'] = $modules;
$pntable['modules_column'] = array ('id' => $modules . '.id',
                                    'name' => $modules . '.name',
                                    'displayname' => $modules . '.displayname',
                                    'description' => $modules . '.description',
                                    'directory' => $modules . '.directory',
                                    'version' => $modules . '.version',
                                    'regid' => $modules . '.regid',
                                    'admin_capable' => $modules . '.admin_capable',
                                    'user_capable' => $modules . '.user_capable',
                                    'state' => $modules . '.state',
                                    'hidden' => $modules . '.hidden');

$poll_check = $prefix . '_poll_check';
$pntable['poll_check'] = $poll_check;
$pntable['poll_check_column'] = array ('ip' => $poll_check . '.ip',
                                       'time' => $poll_check . '.time');

$poll_data = $prefix . '_poll_data';
$pntable['poll_data'] = $poll_data;
$pntable['poll_data_column'] = array ('pollid' => $poll_data . '.pollid',
                                      'optiontext' => $poll_data . '.optiontext',
                                      'optioncount' => $poll_data . '.optioncount',
                                      'voteid' => $poll_data . '.voteid');

$poll_desc = $prefix . '_poll_desc';
$pntable['poll_desc'] = $poll_desc;
$pntable['poll_desc_column'] = array ('pollid' => $poll_desc . '.pollid',
                                      'polltitle' => $poll_desc . '.polltitle',
                                      'timestamp' => $poll_desc . '.timestamp',
                                      'voters' => $poll_desc . '.voters',
                                      'planguage' => $poll_desc . '.planguage');

$pollcomments = $prefix . '_pollcomments';
$pntable['pollcomments'] = $pollcomments;
$pntable['pollcomments_column'] = array ('tid' => $pollcomments . '.tid',
                                         'pid' => $pollcomments . '.pid',
                                         'pollid' => $pollcomments . '.pollid',
                                         'date' => $pollcomments . '.date',
                                         'name' => $pollcomments . '.name',
                                         'email' => $pollcomments . '.email',
                                         'url' => $pollcomments . '.url',
                                         'host_name' => $pollcomments . '.host_name',
                                         'subject' => $pollcomments . '.subject',
                                         'comment' => $pollcomments . '.comment',
                                         'score' => $pollcomments . '.score',
                                         'reason' => $pollcomments . '.reason');

$priv_msgs = $prefix . '_priv_msgs';
$pntable['priv_msgs'] = $priv_msgs;
$pntable['priv_msgs_column'] = array ('msg_id' => $priv_msgs . '.msg_id',
                                      'msg_image' => $priv_msgs . '.msg_image',
                                      'subject' => $priv_msgs . '.subject',
                                      'from_userid' => $priv_msgs . '.from_userid',
                                      'to_userid' => $priv_msgs . '.to_userid',
                                      'msg_time' => $priv_msgs . '.msg_time',
                                      'msg_text' => $priv_msgs . '.msg_text',
                                      'read_msg' => $priv_msgs . '.read_msg');

$queue = $prefix . '_queue';
$pntable['queue'] = $queue;
$pntable['queue_column'] = array ('qid' => $queue . '.qid',
                                  'uid' => $queue . '.uid',
                                  'arcd' => $queue . '.arcd',
                                  'uname' => $queue . '.uname',
                                  'subject' => $queue . '.subject',
                                  'story' => $queue . '.story',
                                  'timestamp' => $queue . '.timestamp',
                                  'topic' => $queue . '.topic',
                                  'alanguage' => $queue . '.alanguage',
                                  'bodytext' => $queue . '.bodytext');

$realms = $prefix . '_realms';
$pntable['realms'] = $realms;
$pntable['realms_column'] = array ('rid' => $realms . '.rid',
                                   'name' => $realms . '.name');

$referer = $prefix . '_referer';
$pntable['referer'] = $referer;
$pntable['referer_column'] = array ('rid' => $referer . '.rid',
                                    'url' => $referer . '.url',
                                    'frequency' => $referer . '.frequency');

$related = $prefix . '_related';
$pntable['related'] = $related;
$pntable['related_column'] = array ('rid' => $related . '.rid',
                                    'tid' => $related . '.tid',
                                    'name' => $related . '.name',
                                    'url' => $related . '.url');

$reviews = $prefix . '_reviews';
$pntable['reviews'] = $reviews;
$pntable['reviews_column'] = array ('id' => $reviews . '.id',
                                    'date' => $reviews . '.date',
                                    'title' => $reviews . '.title',
                                    'text' => $reviews . '.text',
                                    'reviewer' => $reviews . '.reviewer',
                                    'email' => $reviews . '.email',
                                    'score' => $reviews . '.score',
                                    'cover' => $reviews . '.cover',
                                    'url' => $reviews . '.url',
                                    'url_title' => $reviews . '.url_title',
                                    'hits' => $reviews . '.hits',
                                    'rlanguage' => $reviews . '.rlanguage');

$reviews_add = $prefix . '_reviews_add';
$pntable['reviews_add'] = $reviews_add;
$pntable['reviews_add_column'] = array ('id' => $reviews_add . '.id',
                                        'date' => $reviews_add . '.date',
                                        'title' => $reviews_add . '.title',
                                        'text' => $reviews_add . '.text',
                                        'reviewer' => $reviews_add . '.reviewer',
                                        'email' => $reviews_add . '.email',
                                        'score' => $reviews_add . '.score',
                                        'url' => $reviews_add . '.url',
                                        'url_title' => $reviews_add . '.url_title',
                                        'rlanguage' => $reviews_add . '.rlanguage');

$reviews_comments = $prefix . '_reviews_comments';
$pntable['reviews_comments'] = $reviews_comments;
$pntable['reviews_comments_column'] = array ('cid' => $reviews_comments . '.cid',
                                             'rid' => $reviews_comments . '.rid',
                                             'userid' => $reviews_comments . '.userid',
                                             'date' => $reviews_comments . '.date',
                                             'comments' => $reviews_comments . '.comments',
                                             'score' => $reviews_comments . '.score');

$reviews_main = $prefix . '_reviews_main';
$pntable['reviews_main'] = $reviews_main;
$pntable['reviews_main_column'] = array ('title' => $reviews_main . '.title',
                                         'description' => $reviews_main . '.description');

$seccont = $prefix . '_seccont';
$pntable['seccont'] = $seccont;
$pntable['seccont_column'] = array ('artid' => $seccont . '.artid',
                                    'secid' => $seccont . '.secid',
                                    'title' => $seccont . '.title',
                                    'content' => $seccont . '.content',
                                    'counter' => $seccont . '.counter',
                                    'slanguage' => $seccont . '.slanguage');

$sections = $prefix . '_sections';
$pntable['sections'] = $sections;
$pntable['sections_column'] = array ('secid' => $sections . '.secid',
                                     'secname' => $sections . '.secname',
                                     'image' => $sections . '.image');

$session_info = $prefix . '_session_info';
$pntable['session_info'] = $session_info;
$pntable['session_info_column'] = array ('sessid' => $session_info . '.sessid',
                                         'ipaddr' => $session_info . '.ipaddr',
                                         'firstused' => $session_info . '.firstused',
                                         'lastused' => $session_info . '.lastused',
                                         'guest' => $session_info . '.guest',
                                         'vars' => $session_info . '.vars');

$stats_date = $prefix . '_stats_date';
$pntable['stats_date'] = $stats_date;
$pntable['stats_date_column'] = array ('date' => $stats_date . '.date',
                                       'hits' => $stats_date . '.hits');

$stats_hour = $prefix . '_stats_hour';
$pntable['stats_hour'] = $stats_hour;
$pntable['stats_hour_column'] = array ('hour' => $stats_hour . '.hour',
                                       'hits' => $stats_hour . '.hits');

$stats_month = $prefix . '_stats_month';
$pntable['stats_month'] = $stats_month;
$pntable['stats_month_column'] = array ('month' => $stats_month . '.month',
                                        'hits' => $stats_month . '.hits');

$stats_week = $prefix . '_stats_week';
$pntable['stats_week'] = $stats_week;
$pntable['stats_week_column'] = array ('weekday' => $stats_week . '.weekday',
                                       'hits' => $stats_week . '.hits');

$stories = $prefix . '_stories';
$pntable['stories'] = $stories;
$pntable['stories_column'] = array ('sid' => $stories . '.sid',
                                    'cid' => $stories . '.catid',
                                    'catid' => $stories . '.catid', // for back compat
                                    'aid' => $stories . '.aid',
                                    'title' => $stories . '.title',
                                    'time' => $stories . '.time',
                                    'hometext' => $stories . '.hometext',
                                    'bodytext' => $stories . '.bodytext',
                                    'comments' => $stories . '.comments',
                                    'counter' => $stories . '.counter',
                                    'topic' => $stories . '.topic',
                                    'informant' => $stories . '.informant',
                                    'notes' => $stories . '.notes',
                                    'ihome' => $stories . '.ihome',
                                    'themeoverride' => $stories . '.themeoverride',
                                    'alanguage' => $stories . '.alanguage',
                                    'withcomm' => $stories . '.withcomm',
                                    'format_type' => $stories . '.format_type' );

$stories_cat = $prefix . '_stories_cat';
$pntable['stories_cat'] = $stories_cat;

$pntable['stories_cat_column'] = array ('catid' => $stories_cat . '.catid',
                                        'title' => $stories_cat . '.title',
                                        'counter' => $stories_cat . '.counter',
                                        'themeoverride' => $stories_cat . '.themeoverride');

$topics = $prefix . '_topics';
$pntable['topics'] = $topics;
$pntable['topics_column'] = array ('tid' => $topics . '.topicid',
                                   'topicid' => $topics . '.topicid', // for back compat
                                   'topicname' => $topics . '.topicname',
                                   'topicimage' => $topics . '.topicimage',
                                   'topictext' => $topics . '.topictext',
                                   'counter' => $topics . '.counter');

$user_perms = $prefix . '_user_perms';
$pntable['user_perms'] = $user_perms;
$pntable['user_perms_column'] = array ('pid' => $user_perms . '.pid',
                                       'uid' => $user_perms . '.uid',
                                       'sequence' => $user_perms . '.sequence',
                                       'realm' => $user_perms . '.realm',
                                       'component' => $user_perms . '.component',
                                       'instance' => $user_perms . '.instance',
                                       'level' => $user_perms . '.level',
                                       'bond' => $user_perms . '.bond');

$userblocks = $prefix . '_userblocks';
$pntable['userblocks'] = $userblocks;
$pntable['userblocks_column'] = array ('uid' => $userblocks . '.uid',
                                       'bid' => $userblocks . '.bid',
                                       'active'=> $userblocks . '.active',
                                       'last_update' => $userblocks . '.last_update');

$users = $prefix . '_users';
$pntable['users'] = $users;

$pntable['users_column'] = array ('uid' => $users . '.uid',
                                  'name' => $users . '.name',
                                  'uname' => $users . '.uname',
                                  'email' => $users . '.email',
                                  'femail' => $users . '.femail',
                                  'url' => $users . '.url',
                                  'user_avatar' => $users . '.user_avatar',
                                  'user_regdate' => $users . '.user_regdate',
                                  'user_icq' => $users . '.user_icq',
                                  'user_occ' => $users . '.user_occ',
                                  'user_from' => $users . '.user_from',
                                  'user_intrest' => $users . '.user_intrest',
                                  'user_sig' => $users . '.user_sig',
                                  'user_viewemail' => $users . '.user_viewemail',
                                  'user_theme' => $users . '.user_theme',
                                  'user_aim' => $users . '.user_aim',
                                  'user_yim' => $users . '.user_yim',
                                  'user_msnm' => $users . '.user_msnm',
                                  'pass' => $users . '.pass',
                                  'storynum' => $users . '.storynum',
                                  'umode' => $users . '.umode',
                                  'uorder' => $users . '.uorder',
                                  'thold' => $users . '.thold',
                                  'noscore' => $users . '.noscore',
                                  'bio' => $users . '.bio',
                                  'ublockon' => $users . '.ublockon',
                                  'ublock' => $users . '.ublock',
                                  'theme' => $users . '.theme',
                                  'commentmax' => $users . '.commentmax',
                                  'counter' => $users . '.counter',
                                  'timezone_offset' => $users . '.timezone_offset');

$user_property = $prefix . '_user_property';
$pntable['user_property'] = $user_property;

$pntable['user_property_column'] = array ('prop_id' => $user_property . '.prop_id',
                                  'prop_label' => $user_property . '.prop_label',
                                  'prop_dtype' => $user_property . '.prop_dtype',
                                  'prop_length' => $user_property . '.prop_length',
                                  'prop_weight' => $user_property . '.prop_weight',
                                  'prop_validation' => $user_property . '.prop_validation'
                                  );


?>