<?php
// File: $Id: global.php 20429 2006-11-07 19:53:57Z landseer $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001 by the PostNuke Development Team.
// http://www.postnuke.com/
// ----------------------------------------------------------------------
// Based on:
// PHP-NUKE Web Portal System - http://phpnuke.org/
// Thatware - http://thatware.org/
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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file: Everyone
// Purpose of file: Translation files
// Translation team: Read credits in /docs/CREDITS.txt
// ----------------------------------------------------------------------

define('_REGISTER_GLOBALS_ON', 'register_global is on - you should turn this off for enhanced security, for more information click <a href="http://php.net/manual/en/security.globals.php" title="more about register_globals">here</a>');
define('_REGISTER_GLOBALS_ON_HINT', 'Important note: PostNuke itself does not need register_globals=on, but some old modules might need this in order to work. You might consider to not use such modules.');

define('_ADMIN_EMAIL','Mail od Administratora');
define('_ADMIN_LOGIN','Administratov login');
define('_ADMIN_NAME','Ime Administratora');
define('_ADMIN_PASS','Lozinka Administratora');
define('_ADMIN_REPEATPASS','Lozinka Administratora (provjera)');
define('_ADMIN_URL','URL Administratora');
define('_BTN_CONTINUE','Nastavi');
define('_BTN_FINISH','Završeno');
define('_BTN_NEXT','Nastavi');
define('_BTN_RECHECK','Nova verifikacija');
define('_BTN_SET_LANGUAGE','Jezik');
define('_BTN_SET_LOGIN','Prijava');
define('_BTN_START','Poèni');
define('_BTN_SUBMIT','Potvrdi');
define('_CHANGE_INFO_1','Ispravite informacije za Vašu Bazu Podataka.');
define('_CHMOD_CHECK_1','Provjera CHMOD');
define('_CHMOD_CHECK_2','Mi èemo prvo provjeriti CHMOD to jest moguènost da se otvori datoteka i piše u njoj. Ako se to nemože, naredbena datoteka neèe moæi zakriti informacije kod Vaše datoteke konfiguracije (<i>config.php</i>). Zakriti informacije za ulazak na bazu podataka je jako važno za sigurnost Vašeg Sajta, zato je napravjena ova naredbena datoteka. Vi neèete moæi poslje promjeniti parametre administacije kada budu konfigurani.');
define('_CHMOD_CHECK_3','CHMOD kod config.php je na 666 -- OK, ova naredbena datoteka mož ažurirati datoteku');
define('_CHMOD_CHECK_4','Molimo Vas da promjenite CHMOD kod config.php na 666 da bi naredbena dateteka mogla ažurirati i zakriti Vaše informacije o bazi podataka');
define('_CHMOD_CHECK_5','CHMOD kod config-old.php je na 666 -- OK, ova naredbena datoteka mož ažurirati datoteku');
define('_CHMOD_CHECK_6','Molimo Vas da promjenite CHMOD kod config-old.php na 666 da bi naredbena dateteka mogla ažurirati i zakriti Vaše informacije o bazi podataka');
define('_CHM_CHECK_1','Unesite informacije o Vašoj Bazi Podataka. Ako Vi nemate ovlasti na serveru gdje se nalazi Vaša Baza Podataka (<i>hébergement mutualisé, itd...</>), Vi morate prvo napraviti Vašu Bazu Podataka prije nego što nastavite. Ako Vi nemožete napraviti sami Vašu Bazu Podataka zato što ste na besplatnom hosting ili nemate potrebne ovlasti, ova naredbena datoteka neèe moæi Vam napraviti BP. Ova naredbena datoteka može samo staviti informacije u Vašu BP koje mora biti aktivna to jest raditi. ');
define('_CONTINUE_1','Postavke Vaše Baze Podataka');
define('_CONTINUE_2','Vi sada možete definirati Vaš raèu admin. Ako vi skoèite ovu etapu, Vaš raèu admin æ biti Admin/ (pažnja sa velikom i malim slovima) to jest nema lozinke. Bilo bi bolje da definirate sada a ne poslje.');
define('_DBHOST','Host server za bazu podataka');
define('_DBINFO','Informacije za Bazu Podataka');
define('_DBNAME','Ime Baza Podataka ');
define('_DBPASS','Lozina za Bazu Podataka ');
define('_DBPREFIX','Prefix (za podjelu tablica) ');
define('_DBTYPE','Tip baze');
define('_DBUNAME','Korisnik Baze Podataka ');
define('_DEFAULT_1','Ova naredbna datoteka æe instalirati bazu podataka i pomoæi Vam oko stvaranja variabla potrebnih za poèetak. Vi æete biti praèeni preko razlièitih ekrana. Svaka stranica je ustvari jedna etapa instaliranja. Mi mislimo da za ukupno instaliranje potrebno je oko 10 minuta to jest sve ovisi o brzini Vašeg poslužitelja (server). Ako Vi imate problema oko instaliranja, molimo Vas da nam ih reèete u našim forumima kako bim Vam mogli dati više savjeta za instaliranje i pomoæi Vam.');
define('_DEFAULT_2','Licencija');
define('_DEFAULT_3','Molimo Vas da proèitate Glavnu Javnu Licenciju GNU (GPL). Iako PostNuke je jedan slobodan program, da se može napraviti distribucija i izdavanje Postnuka, mora se podvrgnuti nekim obavezama.');
define('_DONE','Završeno');
define('_FINISH_1','Rodni');
define('_FINISH_2','Evo osoba bez kojih Postnuke ne bih postajao, uzmite malo vrijemena i recite im koliko ste zadovoljni sa njihovim radom. Ako Vi želite biti isto tako u ovoj listi, pišite nam da bi htijeli uæi ekipu razvijanja PostNuke jer mi smo uvjek otvoreni za malo pomoæi.');
define('_FINISH_3','Postnuke instaliranje je sada završeno. Ako imate problema, samo nam recite koje to probleme imate. I ne zaboravite da izbrišete ovu naredbdnu datoteku jer je Vi više ne trebate.');
define('_FINISH_4','Otiðite na Vaš sajt PostNuke');
define('_FOOTER_1','Hvala Vam što koristite Postnuke i želimo Vam dobrodošlicu u našu zadrugu.');
define('_FORUM_INFO_1','Vaše tablice od foruma nisu promjene.<br><br>Za informaciju, Vaše tablice su  :');
define('_FORUM_INFO_2','Vi možete izbrisati te tablice ako vi neæete koristiti forume.<br> phpBB bi treba biti dostupan kao modul na http://mods.postnuke.com i XForum na http://trollix.com');
define('_INPUT_DATA_1','Informacije poslanes');
define('_INSTALLATION','PostNuke instaliranje');
define('_MAKE_DB_1','Nemoguènost da se napravi Baza Podataka');
define('_MAKE_DB_2','je napravljena.');
define('_MAKE_DB_3','Nijedna Baza nije napravljena');
define('_MODIFY_FILE_1','Greška : nemoguènost da se piše na :');
define('_MODIFY_FILE_2','Greška : nemoguènost da se piše na :');
define('_MODIFY_FILE_3','0 linje izmjenjene, nijedna promjena');
define('_MYPHPNUKE_1','Ažuriranje od MyPHPNuke 1.8.7 ?');
define('_MYPHPNUKE_2','Samo kliknite na <b>MyPHPNuke 1.8.7</b>');
define('_MYPHPNUKE_3','Ažuriranje od MyPHPNuke 1.8.8b2 ?');
define('_MYPHPNUKE_4','Samo kliknite na <b>MyPHPNuke 1.8.8</b>');
define('_NEWINSTALL','Novo Instaliranje');
define('_NEW_INSTALL_1','Vi ste izabrali da napravite novi instaliranje, evo informacije koje ste dali :');
define('_NEW_INSTALL_2','Ako imate ovlasti da napravite Bazu Podataka, izaberite <b>napravite Bazu Podataka</b>, inèe kliknite na Poènite.<br>Ako Vi nemate ovlasti da napravite Vašu Bazu Podataka, Vi je morate napraviti sami to jest ruèno i naredbna datoteka æe staviti tablice u Vašu Bazu Podataka');
define('_NEW_INSTALL_3','Napravi Bazu Podataka');
define('_NOTMADE','Nemoguèe da se napravi');
define('_NOTSELECT','Nemoguèe da se izabere Baza Podataka');
define('_NOTUPDATED','Nemoguèe da se izmjeni');
define('_PHPNUKE_1','Ažuriranje od PHP-Nuke 4.4 ?');
define('_PHPNUKE_10','Samo kliknite na <b>PHP-Nuke 5.3.1</b>');
define('_PHPNUKE_11','žuriranje od PHP-Nuke 5.4? ');
define('_PHPNUKE_12','Samo kliknite na <b>PHP-Nuke 5.4</b>');
define('_PHPNUKE_2','Molimo Vas da proèitate informaciju koja sljedi, i da pritisnite na <b>PHP-Nuke 4.4</b> za poèetak.<br><br> Ova nardbna datotaka neæe dirati vaše tablice od foruma ali ova verzija neæe uzimati inforamacije');
define('_PHPNUKE_3','žuriranje od PHP-Nuke 5 ?');
define('_PHPNUKE_4','Samo kliknite na <b>PHP-Nuke 5</b>');
define('_PHPNUKE_5','žuriranje od PHP-Nuke 5.2 ?');
define('_PHPNUKE_6','Samo kliknite na <b>PHP-Nuke 5.2</b>');
define('_PHPNUKE_7','žuriranje od PHP-Nuke 5.3 ?');
define('_PHPNUKE_8','Samo kliknite na <b>PHP-Nuke 5.3</b>');
define('_PHPNUKE_9','žuriranje od PHP-Nuke 5.3.1 ?');
define('_PHP_CHECK_1','Vaša verzija PHP je ');
define('_PHP_CHECK_2','Vi morate imati barem verziju 4.0.1 PHP - <a href=\'\'http://www.php.net\'\'>http://www.php.net</a>');
define('_PHP_CHECK_3','Nije dobro! magic_quotes_gpc je dezaktiviran.<br>To se može naknonaditi korišèenjem datoteke .htaccess sa ovim :<br>php_flag magic_quotes_gpc On');
define('_PHP_CHECK_4','Nije dobro! magic_quotes_runtime je dezaktivan.<br>To se može naknonaditi korišèenjem datoteke .htaccess sa ovim :<br>php_flag magic_quotes_runtime Off');
define('_PN6_1','Admin : Vi morate napraviti novo saèuvaljanje sistemskih postavki od Vašeg sajta od stranice administracije - ŠTO PTIJE TO BOLJE!');
define('_PN6_2','(molimo Vas da nas isprièate');
define('_PN6_3','ERREUR : Datoteka nije naðena :');
define('_PN6_4','Promjena starih blokova napravljena');
define('_POSTNUKE_1','Ažuriranje od PostNuke .5x ?');
define('_POSTNUKE_10','Kliknite samo na dugme <b>PostNuke 64</b>');
define('_POSTNUKE_11','Ažuriranje od PostNuke .7 ?');
define('_POSTNUKE_12','Kliknite samo na dugme <b>PostNuke 7</b>');
define('_POSTNUKE_13','Potvrda tablica');
define('_POSTNUKE_14','Ova narebdna datoteka æe napraviti dvostruku verifikaciju la structure des tables de votre base PostNuke. Exécutez chaque partie du script pour vous assurer que votre base de données est correctement installée. Cela est surtout utile p');
define('_POSTNUKE_15','Za potvrdu Vašeg jeziènog sistema?');
define('_POSTNUKE_16','Stisnite <b>Valider</b>');
define('_POSTNUKE_17','Za potvrdu tablica?');
define('_POSTNUKE_18','Stisnite <b>Valider</b>');
define('_POSTNUKE_19','Ažuriranje od de PostNuke .72 ?');
define('_POSTNUKE_20','Kliknite samo na dugme <b>PostNuke 72</b>');
define('_POSTNUKE_2','Kliknite samo na <b>PostNuke 5</b>');
define('_POSTNUKE_3','Ažuriranje od PostNuke .6 / .61 ?');
define('_POSTNUKE_4','Kliknite samo na <b>PostNuke 6</b>');
define('_POSTNUKE_5','Ažuriranje od PostNuke .62 ?');
define('_POSTNUKE_6','Kliknite samo na <b>PostNuke 62</b>');
define('_POSTNUKE_7','Ažuriranje od PostNuke .63 ?');
define('_POSTNUKE_8','Kliknite samo na  <b>PostNuke 63</b><br>');
define('_POSTNUKE_9','Ažuriranje od de PostNuke .64 ?');
define('_PWBADMATCH','rijeè lozinke nisu iste, vratite se natrag i stavite ponova Vašu lozinku');
define('_QUOTESCHECK_1','Provjera NS-Quotes');
define('_QUOTESCHECK_2','Prethodna verzija modula NS-Quotes module je otklonjena u korist nove verzija modula Quotes.<br> Molimo Vas da izbrišite direktorij modules/NS-Quotes.');
define('_SELECT_LANGUAGE_1','Izaberite Vaš jezik :');
define('_SELECT_LANGUAGE_2','Jezik :');
define('_SHOW_ERROR_INFO_1','Erreur d\'écriture </b> impossible de mettre à jour le fichier \'\'config.php\'\'<br/> Vous allez devoir le modifier manuellement à l\'aide d\'un éditeur de texte.<br />. Voici les changements à effectuer ');
define('_SKIPPED','Ispustiti');
define('_SUBMIT_1','Molimo Vas provjerite informacije i budite sigurni da su toène.');
define('_SUBMIT_2','Vi ste stavile ove informacije :');
define('_SUBMIT_3','Odaberite <b>Novo instaliranje</b> ou <b>Žuriranje</b> za poèetak.');
define('_SUCCESS_1','Gotovo');
define('_SUCCESS_2','Vaše ažuriranje prema najnovjoj verziji je gotovo.<br> Nemojte zaboraviti da promjenite config.php prije prvog korišèenjae config.php.');
define('_UPDATED',' ažurirano');
define('_UPDATING','Promjena tablice :');
define('_UPGRADETAKESALONGTIME','Ažuriranje prema Postnuke može biti dugo, možda nekoliko minuta. Kada izaberete jednu opciju ažuriranja, kliknite je samo jedanputa i èekajte da se pokaže sljedeæi ekran.');
define('_UPGRADE_1','Ažruriranje');
define('_UPGRADE_2','Odaberite sa kojeg CMS hoèete da napravite ažuriranje.<br><br><center> Izaberite <b>PHP-Nuke</b> da ažurirate postojeæi PHP-Nuke sistem.<br> Izaberite <b>PostNuke</b> da ažurirate postojeæi PostNuke sistem.<br> Izaberite <b>MyPHPNuke</b> da ažurirate postojeæi MyPHPNuke sistem.');
?>