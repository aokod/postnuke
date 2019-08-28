<?php
// File: $Id: newtables.php 15874 2005-02-27 14:27:45Z landseer $
function dosql($table,$sql) {
   GLOBAL $dbconn;
   $result = $dbconn->Execute($sql);
   if ($result === false) {
      echo "<font class=\"pn-failed\">"._NOTMADE." ".$table."</font>";
      exit;
   }
   echo "<br><font class=\"pn-sub\">".$table." "._MADE."</font>";
}

$dbconn = dbconnect($dbhost, $dbuname, $dbpass, $dbname, $dbtype);

$table = $prefix.'_admin_category';
$sql = "
   CREATE TABLE ".$prefix."_admin_category (
	pn_cid int(10) NOT NULL auto_increment,
	pn_name varchar(32) NOT NULL default '',
	pn_description varchar(254) NOT NULL default '',
	PRIMARY KEY (pn_cid)
) TYPE = " . $dbtabletype . "
";
dosql($table,$sql);

$table= $prefix.'_admin_module';
$sql = "
   CREATE TABLE ".$prefix."_admin_module (
    pn_mid int(10) NOT NULL default 0,
    pn_cid int(10) NOT NULL default 0
) TYPE = " . $dbtabletype . "
";
dosql($table,$sql);

$table= $prefix.'_autonews';
$sql = "
   CREATE TABLE ".$prefix."_autonews (
     pn_anid int(11) NOT NULL auto_increment,
     pn_catid int(11) NOT NULL default '0',
     pn_aid varchar(30) NOT NULL default '',
     pn_title varchar(80) NOT NULL default '',
     pn_time varchar(19) NOT NULL default '',
     pn_hometext text NOT NULL,
     pn_bodytext text NOT NULL,
     pn_topic tinyint(4) NOT NULL default '1',
     pn_informant varchar(20) NOT NULL default '',
     pn_notes text NOT NULL,
     pn_ihome tinyint(1) NOT NULL default '0',
     pn_language varchar(30) NOT NULL default '',
     pn_withcomm int(1) NOT NULL default '0',
     PRIMARY KEY  (pn_anid)
) TYPE = " . $dbtabletype . "
";
dosql($table,$sql);

$table = $prefix.'_blocks';
$sql = "
CREATE TABLE ".$prefix."_blocks (
  pn_bid int(11) unsigned NOT NULL auto_increment,
  pn_bkey varchar(255) NOT NULL default '',
  pn_title varchar(255) NOT NULL default '',
  pn_content text NOT NULL,
  pn_url varchar(254) NOT NULL default '',
  pn_mid int(11) unsigned NOT NULL default '0',
  pn_position char(1) NOT NULL default 'l',
  pn_weight decimal(10,1) NOT NULL default '0.0',
  pn_active tinyint(3) unsigned NOT NULL default '1',
  pn_refresh int(11) unsigned NOT NULL default '0',
  pn_last_update timestamp(14) NOT NULL,
  pn_language varchar(30) NOT NULL default '',
  pn_collapsable int(11) NOT NULL default '1',
  pn_defaultstate int(11) NOT NULL default '1',
  PRIMARY KEY  (pn_bid)
) TYPE = " . $dbtabletype . "
";
dosql($table,$sql);

$table = $prefix.'_blocks_buttons';
$sql = "
CREATE TABLE ".$prefix."_blocks_buttons (
  pn_id int(11) unsigned NOT NULL auto_increment,
  pn_bid int(11) unsigned NOT NULL default '0',
  pn_title varchar(255) NOT NULL default '',
  pn_url varchar(254) NOT NULL default '',
  pn_images longtext NOT NULL,
  PRIMARY KEY  (pn_id)
) TYPE = " . $dbtabletype . "
";
dosql($table,$sql);

$table = $prefix.'_group_membership';
$sql = "
CREATE TABLE ".$prefix."_group_membership (
  pn_gid int(11) NOT NULL default '0',
  pn_uid int(11) NOT NULL default '0'
) TYPE = " . $dbtabletype . "
";
dosql($table,$sql);

$table = $prefix.'_group_perms';
$sql = "
CREATE TABLE ".$prefix."_group_perms (
  pn_pid int(11) NOT NULL auto_increment,
  pn_gid int(11) NOT NULL default '0',
  pn_sequence int(11) NOT NULL default '0',
  pn_realm smallint(4) NOT NULL default '0',
  pn_component varchar(255) NOT NULL default '',
  pn_instance varchar(255) NOT NULL default '',
  pn_level smallint(4) NOT NULL default '0',
  pn_bond int(2) NOT NULL default '0',
  PRIMARY KEY  (pn_pid)
) TYPE = " . $dbtabletype . "
";
dosql($table,$sql);

$table = $prefix.'_groups';
$sql = "
CREATE TABLE ".$prefix."_groups (
  pn_gid int(11) NOT NULL auto_increment,
  pn_name varchar(255) NOT NULL default '',
  PRIMARY KEY  (pn_gid)
) TYPE = " . $dbtabletype . "
";
dosql($table,$sql);

$table = $prefix.'_headlines';
$sql = "
CREATE TABLE ".$prefix."_headlines (
  pn_id int(11) unsigned NOT NULL auto_increment,
  pn_sitename varchar(255) NOT NULL default '',
  pn_rssuser varchar(10) default NULL,
  pn_rsspasswd varchar(10) default NULL,
  pn_use_proxy tinyint(3) NOT NULL default '0',
  pn_rssurl varchar(255) NOT NULL default '',
  pn_maxrows tinyint(3) NOT NULL default '10',
  pn_siteurl varchar(255) NOT NULL default '',
  pn_options varchar(20) default '',
  PRIMARY KEY  (pn_id)
) TYPE = " . $dbtabletype . "
";
dosql($table,$sql);

$table = $prefix.'_hooks';
$sql = "
CREATE TABLE ".$prefix."_hooks (
  pn_id int(11) unsigned NOT NULL auto_increment,
  pn_object varchar(64) NOT NULL,
  pn_action varchar(64) NOT NULL,
  pn_smodule varchar(64),
  pn_stype varchar(64),
  pn_tarea varchar(64) NOT NULL,
  pn_tmodule varchar(64) NOT NULL,
  pn_ttype varchar(64) NOT NULL,
  pn_tfunc varchar(64) NOT NULL,
  PRIMARY KEY  (pn_id)
) TYPE = " . $dbtabletype . "
";
dosql($table,$sql);

$table = $prefix.'_module_vars';
$sql = "
CREATE TABLE ".$prefix."_module_vars (
  pn_id int(11) unsigned NOT NULL auto_increment,
  pn_modname varchar(64) NOT NULL default '',
  pn_name varchar(64) NOT NULL default '',
  pn_value longtext,
  PRIMARY KEY  (pn_id),
  KEY pn_modname (pn_modname),
  KEY pn_name (pn_name)
) TYPE = " . $dbtabletype . "
";
dosql($table,$sql);

$table = $prefix.'_modules';
$sql = "
CREATE TABLE ".$prefix."_modules (
  pn_id int(11) unsigned NOT NULL auto_increment,
  pn_name varchar(64) NOT NULL default '',
  pn_type int(6) NOT NULL,
  pn_displayname varchar(64) NOT NULL default '',
  pn_description varchar(255) NOT NULL default '',
  pn_regid int(11) unsigned NOT NULL default '0',
  pn_directory varchar(64) NOT NULL default '',
  pn_version varchar(10) NOT NULL default '0',
  pn_admin_capable tinyint(1) NOT NULL default '0',
  pn_user_capable tinyint(1) NOT NULL default '0',
  pn_state tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (pn_id)
) TYPE = " . $dbtabletype . "
";
dosql($table,$sql);

$table = $prefix.'_queue';
$sql = "
CREATE TABLE ".$prefix."_queue (
  pn_qid smallint(5) unsigned NOT NULL auto_increment,
  pn_uid mediumint(9) NOT NULL default '0',
  pn_arcd tinyint(1) NOT NULL default '0',
  pn_uname varchar(40) NOT NULL default '',
  pn_subject varchar(255) NOT NULL default '',
  pn_story text,
  pn_timestamp datetime NOT NULL default '0000-00-00 00:00:00',
  pn_topic varchar(20) NOT NULL default '',
  pn_language varchar(30) NOT NULL default '',
  pn_bodytext text,
  PRIMARY KEY  (pn_qid)
) TYPE = " . $dbtabletype . "
";
dosql($table,$sql);

$table = $prefix.'_realms';
$sql = "
CREATE TABLE ".$prefix."_realms (
  pn_rid int(11) NOT NULL auto_increment,
  pn_name varchar(255) NOT NULL default '',
  PRIMARY KEY  (pn_rid)
) TYPE = " . $dbtabletype . "
";
dosql($table,$sql);

$table = $prefix.'_related';
$sql = "
CREATE TABLE ".$prefix."_related (
  pn_rid int(11) NOT NULL auto_increment,
  pn_tid int(11) NOT NULL default '0',
  pn_name varchar(30) NOT NULL default '',
  pn_url varchar(254) NOT NULL default '',
  PRIMARY KEY  (pn_rid)
) TYPE = " . $dbtabletype . "
";
dosql($table,$sql);

$table = $prefix.'_session_info';
$sql = "
CREATE TABLE ".$prefix."_session_info (
  pn_sessid varchar(32) NOT NULL default '',
  pn_ipaddr varchar(20) NOT NULL default '',
  pn_firstused int(11) NOT NULL default '0',
  pn_lastused int(11) NOT NULL default '0',
  pn_uid int(11) NOT NULL default '0',
  pn_vars blob,
  PRIMARY KEY  (pn_sessid)
) TYPE = " . $dbtabletype . "
";
dosql($table,$sql);

$table = $prefix.'_stories';
$sql = "
CREATE TABLE ".$prefix."_stories (
  pn_sid int(11) NOT NULL auto_increment,
  pn_catid int(11) NOT NULL default '0',
  pn_aid varchar(30) NOT NULL default '',
  pn_title varchar(255) default NULL,
  pn_time datetime default NULL,
  pn_hometext text,
  pn_bodytext text NOT NULL,
  pn_comments int(11) default '0',
  pn_counter mediumint(8) unsigned default NULL,
  pn_topic tinyint(4) NOT NULL default '1',
  pn_informant varchar(20) NOT NULL default '',
  pn_notes text NOT NULL,
  pn_ihome tinyint(1) NOT NULL default '0',
  pn_themeoverride varchar(30) NOT NULL default '',
  pn_language varchar(30) NOT NULL default '',
  pn_withcomm tinyint(1) NOT NULL default '0',
  pn_format_type tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (pn_sid)
) TYPE = " . $dbtabletype . "
";
dosql($table,$sql);

$table = $prefix.'_stories_cat';
$sql = "
CREATE TABLE ".$prefix."_stories_cat (
  pn_catid int(11) NOT NULL auto_increment,
  pn_title varchar(40) NOT NULL default '',
  pn_counter int(11) NOT NULL default '0',
  pn_themeoverride varchar(30) NOT NULL default '',
  PRIMARY KEY  (pn_catid)
) TYPE = " . $dbtabletype . "
";
dosql($table,$sql);

$table = $prefix.'_user_data';
$sql = "
CREATE TABLE ".$prefix."_user_data (
  pn_uda_id int(11) NOT NULL auto_increment,
  pn_uda_propid int(11) NOT NULL default '0',
  pn_uda_uid int(11) NOT NULL default '0',
  pn_uda_value mediumblob NOT NULL,
  PRIMARY KEY  (pn_uda_id)
) TYPE = " . $dbtabletype . "
";
dosql($table,$sql);

$sql = "ALTER TABLE ".$prefix."_user_data ADD UNIQUE `index_id_propid` ( `pn_uda_propid` , `pn_uda_id` )";
dosql($table,$sql);

$table = $prefix.'_user_perms';
$sql = "
CREATE TABLE ".$prefix."_user_perms (
  pn_pid int(11) NOT NULL auto_increment,
  pn_uid int(11) NOT NULL default '0',
  pn_sequence int(6) NOT NULL default '0',
  pn_realm int(4) NOT NULL default '0',
  pn_component varchar(255) NOT NULL default '',
  pn_instance varchar(255) NOT NULL default '',
  pn_level int(4) NOT NULL default '0',
  pn_bond int(2) NOT NULL default '0',
  PRIMARY KEY  (pn_pid)
) TYPE = " . $dbtabletype . "
";
dosql($table,$sql);

$table = $prefix.'_user_property';
$sql = "
CREATE TABLE ".$prefix."_user_property (
  pn_prop_id int(11) NOT NULL auto_increment,
  pn_prop_label varchar(255) NOT NULL default '',
  pn_prop_dtype int(11) NOT NULL default '0',
  pn_prop_length int(11) NOT NULL default '255',
  pn_prop_weight int(11) NOT NULL default '0',
  pn_prop_validation varchar(255) default NULL,
  PRIMARY KEY  (pn_prop_id),
  UNIQUE KEY pn_prop_label (pn_prop_label)
) TYPE = " . $dbtabletype . "
";
dosql($table,$sql);

$table = $prefix.'_userblocks';
$sql = "
CREATE TABLE ".$prefix."_userblocks (
  pn_uid int(11) NOT NULL default '0',
  pn_bid int(11) NOT NULL default '0',
  pn_active tinyint(3) NOT NULL default '1',
  pn_last_update timestamp(14) NOT NULL
) TYPE = " . $dbtabletype . "
";
dosql($table,$sql);

$table = $prefix.'_users';
$sql = "
CREATE TABLE ".$prefix."_users (
  pn_uid int(11) NOT NULL auto_increment,
  pn_name varchar(60) NOT NULL default '',
  pn_uname varchar(25) NOT NULL default '',
  pn_email varchar(60) NOT NULL default '',
  pn_femail varchar(60) NOT NULL default '',
  pn_url varchar(254) NOT NULL default '',
  pn_user_avatar varchar(30) default NULL,
  pn_user_regdate varchar(20) NOT NULL default '',
  pn_user_icq varchar(15) default NULL,
  pn_user_occ varchar(100) default NULL,
  pn_user_from varchar(100) default NULL,
  pn_user_intrest varchar(150) default NULL,
  pn_user_sig varchar(255) default NULL,
  pn_user_viewemail tinyint(2) default NULL,
  pn_user_theme tinyint(3) default NULL,
  pn_user_aim varchar(18) default NULL,
  pn_user_yim varchar(25) default NULL,
  pn_user_msnm varchar(25) default NULL,
  pn_pass varchar(40) NOT NULL default '',
  pn_storynum tinyint(4) NOT NULL default '10',
  pn_umode varchar(10) NOT NULL default '',
  pn_uorder tinyint(1) NOT NULL default '0',
  pn_thold tinyint(1) NOT NULL default '0',
  pn_noscore tinyint(1) NOT NULL default '0',
  pn_bio tinytext NOT NULL,
  pn_ublockon tinyint(1) NOT NULL default '0',
  pn_ublock text NOT NULL,
  pn_theme varchar(255) NOT NULL default '',
  pn_commentmax int(11) NOT NULL default '4096',
  pn_counter int(11) NOT NULL default '0',
  pn_timezone_offset float(3,1) NOT NULL default '0.0',
  PRIMARY KEY  (pn_uid)
) TYPE = " . $dbtabletype . "
";
dosql($table,$sql);

?>