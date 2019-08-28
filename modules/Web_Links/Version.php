<?php // $Id: Version.php 15630 2005-02-04 06:35:42Z jorg $

$modversion['name'] = 'Web_Links';
$modversion['version'] = '1.0';
$modversion['description'] = 'Web Links Module';
$modversion['credits'] = 'docs/credits.txt';
$modversion['help'] = 'docs/install.txt';
$modversion['changelog'] = 'docs/changelog.txt';
$modversion['license'] = 'docs/license.txt';
$modversion['official'] = 1;
$modversion['author'] = 'Francisco Burzi';
$modversion['contact'] = 'http://www.phpnuke.org';
$modversion['admin'] = 0;
$modversion['securityschema'] = array('Web Links::Category' => 'Category name::Category ID',
                                      'Web Links::Link' => 'Category name:Link name:Link ID');

?>