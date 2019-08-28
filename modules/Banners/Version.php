<?php
// $Id: Version.php 15630 2005-02-04 06:35:42Z jorg $
$modversion['name'] = 'Banners Admin';
$modversion['version'] = '1.0';
$modversion['description'] = 'Administer Banners on your site';
$modversion['credits'] = '';
$modversion['help'] = '';
$modversion['changelog'] = '';
$modversion['license'] = '';
$modversion['official'] = 1;
$modversion['author'] = 'Francisco Burzi';
$modversion['contact'] = 'http://www.phpnuke.org';
$modversion['admin'] = 1;
$modversion['securityschema'] = array('Banners::Client' => 'Client name::Client ID',
                                      'Banners::Banner' => 'Client name::Banner ID');

?>