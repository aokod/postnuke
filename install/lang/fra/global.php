<?php
// File: $Id: global.php 20451 2006-11-08 17:26:58Z larsneo $
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
// Original Author of file: FC - PostNuke-France
// Purpose of file: Translation files
// Translation team: Read credits in /docs/CREDITS.txt
// ----------------------------------------------------------------------

define('_REGISTER_GLOBALS_ON', 'register_global is on - you should turn this off for enhanced security, for more information click <a href="http://php.net/manual/en/security.globals.php" title="more about register_globals">here</a>');
define('_REGISTER_GLOBALS_ON_HINT', 'Important note: PostNuke itself does not need register_globals=on, but some old modules might need this in order to work. You might consider to not use such modules.');

define('_ADMIN_EMAIL','E-mail de l\'administrateur');
define('_ADMIN_LOGIN','Identifiant de l\'administrateur');
define('_ADMIN_NAME','Nom de l\'administrateur');
define('_ADMIN_PASS','Mot de passe administrateur');
define('_ADMIN_REPEATPASS','Mot de passe administrateur (v�rification)');
define('_ADMIN_URL','URL de l\'administrateur');
define('_BTN_CHANGEINFO','Modifier les informations');
define('_BTN_CONTINUE','Continuer');
define('_BTN_FINISH','Terminer');
define('_BTN_NEXT','Suite');
define('_BTN_NEWINSTALL','Nouvelle installation');
define('_BTN_RECHECK','Nouvelle v�rification');
define('_BTN_SET_LANGUAGE','Langue');
define('_BTN_SET_LOGIN','Identification');
define('_BTN_START','Commencer');
define('_BTN_SUBMIT','Valider');
define('_BTN_UPGRADE','Mise � jour');
define('_CHANGE_INFO_0','Information erron�e');
define('_CHANGE_INFO_1','Corrigez les informations de votre base de donn�es.');
define('_CHMOD_CHECK_1','V�rification des droits (CHMOD)');
define('_CHMOD_CHECK_2','Nous allons d\'abord v�rifier que les droits d\'acc�s (CHMOD) permettent au script d\'�crire dans le fichier de configuration. S\'ils ne sont pas corrects, le script ne pourra pas encrypter les donn�es de votre fichier de configuration. Encrypter les donn�es d\'acc�s � la base est important pour la s�curit�, c\'est un des objectifs de ce script. Vous ne pourrez plus changer vos param�tres d\'administration une fois ceux-ci configur�s.');
define('_CHMOD_CHECK_3','Les droits CHMOD de config.php sont � 666 -- OK, ce script peut mettre le fichier � jour');
define('_CHMOD_CHECK_4','Veuillez changer les droits CHMOD de config.php � 666 pour que le script puisse mettre � jour et encrypter vos infos de BD');
define('_CHMOD_CHECK_5','Les droits CHMOD de config-old.php sont � 666 -- OK, ce script peut mettre le fichier � jour');
define('_CHMOD_CHECK_6','Veuillez changer les droits CHMOD de config-old.php � 666 pour que le script pusse mettre � jour et encrypter vos infos de BD');
define('_CHM_CHECK_1','Svp., entrez vos informations de Base de donn�es. Si vous n\'avez pas l\'acc�s \'\'root\'\' � votre Base de donn�es (serveur virtuel ou mutualis�, etc), vous devrez pr�alablement cr�er votre base de donn�es avant de proc�der. En r�gle g�n�rale, si vous ne pouvez pas cr�er la base de donn�es par phpMyAdmin � cause de votre h�bergement ou de la s�curit� sur mySQL, ce script ne pourra pas la cr�er pour vous. Ce script devra quand m�me �tre ex�cut� et pourra remplir la base de donn�es existante.<br><br>Si vous ne connaissez pas les valeurs pour l\'h�te de base de donn�es, l\'utilisateur ou le mot de passe, laissez les valeurs par d�faut.  <br><br><b>NOTE : Certains serveurs utilisent 127.0.0.1 comme h�te de base de donn�es. Si vous obtenez une erreur "Impossible de se connecter � la base MySQL", essayez de changer l\'h�te par d�faut en inscrivant 127.0.0.1 </b><br><br>Si des probl�mes persistent, contactez votre h�bergeur afin d\'obtenir vos informations de connexion. ');
define('_CONTINUE_1','Pr�f�rences de votre BD');
define('_CONTINUE_2','Vous pouvez maintenant d�finir votre compte admin. Si vous passez cette �tape, votre compte admin sera Admin/Password (attention aux majuscules/minuscules). Il vaut mieux le d�finir maintenant et non plus tard.');
define('_DBHOST','Serveur h�te de la base');
define('_DBINFO','Informations sur la base de donn�es');
define('_DBNAME','Nom de la base');
define('_DBPASS','Mot de passe de la base');
define('_DBPREFIX','Pr�fixe (pour le partage de tables)');
define('_DBTABLETYPE','Type de table de base de donn�es');
define('_DBTYPE','Type de base');
define('_DBUNAME','Utilisateur de la base');
define('_DEFAULT_1','Ce script va installer la base de donn�es PostNuke et vous assistera dans la cr�ation des variables n�cessaires au d�marrage. Vous serez guid� � travers une succession d\'�crans. Chaque page correspond � une �tape de l\'installation. Nous estimons que la proc�dure d\'installation prendra environ 10 minutes. Si vous avez le moindre soucis, n\'h�sitez pas � lire nos forums pour obtenir de l\'aide.');
define('_DEFAULT_2','Licence');
define('_DEFAULT_3','Veuillez prendre connaissance de la Licence Publique G�n�rale GNU (GPL). Bien que PostNuke soit un logiciel libre, sa distribution et sa publication sont soumises � certaines obligations.');
define('_DONE','Termin�.');
define('_FINISH_1','G�n�rique');
define('_FINISH_2','Voici les gens qui font exister PostNuke. Prenez un peu de temps pour leur faire savoir que vous appr�ciez leur travail. Si vous souhaitez appara�tre dans cette liste, contactez-nous pour rejoindre l\'�quipe de d�veloppement. Nous sommes toujours ouvert � un peu d\'aide.');
define('_FINISH_3','L\'installation de PostNuke est maintenant termin�e. Si vous rencontrez des probl�mes, faites-le nous savoir. Assurez-vous d\'effacer ce script, vous n\'en aurez plus besoin.');
define('_FINISH_4','Acc�s � votre site PostNuke');
define('_FOOTER_1','Merci d\'utiliser PostNuke, et bienvenue dans notre communaut�.');
define('_FORUM_INFO_1','Vos tables de forum sont inchang�es.<br><br>Pour info, ces tables sont :');
define('_FORUM_INFO_2','Vous pouvez effacer ces tables si vous n\'avez pas l\'intention d\'utiliser les forums.<br> phpBB devrait �;tre disponible sous forme de module sur http://mods.postnuke.com');
define('_INPUT_DATA_1','Donn�es envoy�es');
define('_INSTALLATION','Installation de PostNuke');
define('_INSTALLED','Install�');
define('_MADE',' cr��e.');
define('_MAKE_DB_1','Impossible de cr�er la base de donn�es.');
define('_MAKE_DB_2','a �t� cr��e.');
define('_MAKE_DB_3','Aucune base � cr�er.');
define('_MODIFY_FILE_1','Erreur : acc�s en lecture impossible sur :');
define('_MODIFY_FILE_2','Erreur : acc�s en �criture impossible sur :');
define('_MODIFY_FILE_3','0 ligne modifi�e, aucun changement');
define('_MYPHPNUKE_1','Mise � jour � partir de MyPHPNuke 1.8.7 ?');
define('_MYPHPNUKE_2','Cliquez simplement sur le bouton <b>MyPHPNuke 1.8.7</b>');
define('_MYPHPNUKE_3','Mise � jour � partir de MyPHPNuke 1.8.8b2 ?');
define('_MYPHPNUKE_4','Cliquez simplement sur le bouton <b>MyPHPNuke 1.8.8</b>');
define('_NEWINSTALL','Nouvelle Installation');
define('_NEW_INSTALL_1','Vous avez choisi de faire une nouvelle installation. Voici les informations que vous avez fournies :');
define('_NEW_INSTALL_2','Si vous avez un acc�s root, cochez la case pour <b>cr�er la base de donn�es</b>. Sinon, cliquez sur Commencer.<br>Si vous n\'avez pas d\'acc�s root, vous devez cr�er la base manuellement et le script ajoutera les tables pour vous');
define('_NEW_INSTALL_3','Cr�er la base de donn�es');
define('_NOTMADE','Impossible de cr�er');
define('_NOTSELECT','Impossible de s�lectionner la base.');
define('_NOTUPDATED','Impossible de mettre � jour ');
define('_PHPNUKE_1','Mise � jour � partir de PHP-Nuke 4.4 ?');
define('_PHPNUKE_10','Cliquez simplement sur le bouton <b>PHP-Nuke 5.3.1</b>');
define('_PHPNUKE_11','Mise � jour � partir de PHP-Nuke 5.4? ');
define('_PHPNUKE_12','Cliquez simplement sur le bouton <b>PHP-Nuke 5.4</b>');
define('_PHPNUKE_2','Veuillez lire la note qui suit, et appuyez sur le bouton <b>PHP-Nuke 4.4</b> pour d�marrer.<br><br> Ce script laissera intactes vos tables de forum mais cetter version ne tiendra pas compte des donn�es.<i> Un script de conversion pour ces do');
define('_PHPNUKE_3','Mise � jour � partir de PHP-Nuke 5 ?');
define('_PHPNUKE_4','Cliquez simplement sur le bouton <b>PHP-Nuke 5</b>');
define('_PHPNUKE_5','Mise � jour � partir de PHP-Nuke 5.2 ?');
define('_PHPNUKE_6','Cliquez simplement sur le bouton <b>PHP-Nuke 5.2</b>');
define('_PHPNUKE_7','Mise � jour � partir de PHP-Nuke 5.3 ?');
define('_PHPNUKE_8','Cliquez simplement sur <b>PHP-Nuke 5.3</b>');
define('_PHPNUKE_9','Mise � jour � partir de PHP-Nuke 5.3.1 ?');
define('_PHP_CHECK_1','Votre version de PHP est');
define('_PHP_CHECK_2','Vous devez au moins passer � la version 4.1.0 de PHP - <a href=\'http://www.php.net\' title=\'PHP\'>http://www.php.net</a>');
define('_PHP_CHECK_3','Pas bon ! magic_quotes_gpc est d�sactiv�.<br>Cela peut en g�n�ral �;tre contourn� en utilisant un fichier .htaccess contenant la ligne :<br>php_flag magic_quotes_gpc On');
define('_PHP_CHECK_4','Pas bon ! magic_quotes_runtime est d�sactiv�.<br>Cela peut en g�n�ral �;tre contourn� en utilisant un fichier .htaccess contenant la ligne :<br>php_flag magic_quotes_runtime Off');
define('_PN6_1','Admin : Vous devrez faire une nouvelle sauvegarde des pr�f�rences de votre site � partir de la page d\'administration - AUSSITOT QUE POSSIBLE !');
define('_PN6_2','(veuillez nous excuser pour la g�;ne occasionn�e)');
define('_PN6_3','ERREUR : Fichier non trouv� :');
define('_PN6_4','Conversion de l\'ancien format de blocs de boutons effectu�e.');
define('_PNTEMP_DIRNOTWRITABLE', 'Svp. Modifiez les permissions de ce r�pertoire en 777 afin de permettre au script la cr�ation de fichier dans celui-ci (Indice : utilisez "chmod")');
define('_PNTEMP_DIRWRITABLE', 'Ok, le script peut �crire dans ce r�pertoire');
define('_POSTNUKE_1','Mise � jour � partir de PostNuke .5x ?');
define('_POSTNUKE_10','Cliquez simplement sur le bouton <b>PostNuke .64</b>');
define('_POSTNUKE_11','Mise � jour � partir de PostNuke .7 ?');
define('_POSTNUKE_12','Cliquez simplement sur le bouton <b>PostNuke 7</b>');
define('_POSTNUKE_13','Mise � jour � partir de PostNuke .71 ?');
define('_POSTNUKE_14','Cliquez simplement sur le bouton <b>PostNuke 71</b>');
define('_POSTNUKE_15','Pour valider votre syst�me de langue ?');
define('_POSTNUKE_16','Pressez sur le bouton <b>Valider</b>');
define('_POSTNUKE_17','Pour valider la structure des tables ?');
define('_POSTNUKE_18','Pressez sur le bouton <b>Valider</b>');

define('_POSTNUKE_19','Mise � jour � partir de PostNuke .72 ?');
define('_POSTNUKE_2','Cliquez simplement sur le bouton <b>PostNuke .5</b>');
define('_POSTNUKE_20','Cliquez simplement sur le bouton <b>PostNuke .72</b>');
define('_POSTNUKE_3','Mise � jour � partir de PostNuke .6 / .61 ?');
define('_POSTNUKE_4','Cliquez simplement sur le bouton <b>PostNuke .6</b>');
define('_POSTNUKE_5','Mise � jour � partir de PostNuke .62 ?');
define('_POSTNUKE_6','Cliquez simplement sur le bouton <b>PostNuke .62</b>');
define('_POSTNUKE_7','Mise � jour � partir de PostNuke .63 ?');
define('_POSTNUKE_8','Cliquez simplement sur le bouton <b>PostNuke .63</b><br>');
define('_POSTNUKE_9','Mise � jour � partir de PostNuke .64 ?');
define('_PWBADMATCH','Les mots de passe entr�s ne sont pas identiques. Revenez en arri�re et tapez les mots de passe � nouveau.');
define('_QUOTESCHECK_1','NS-Quotes Check');
define('_QUOTESCHECK_2','The Former NS-Quotes module has been deprecated in favor of the new Quotes module.<br> Please remove the modules/NS-Quotes directory.');
define('_SELECT_LANGUAGE_1','S�lectionnez votre langue.');
define('_SELECT_LANGUAGE_2','Langue :');
define('_SHOW_ERROR_INFO_1','Erreur d\'�criture </b> impossible de mettre � jour le fichier \'\'config.php\'\'<br/> Vous allez devoir le modifier manuellement � l\'aide d\'un �diteur de texte.<br />. Voici les changements � effectuer ');
define('_SKIPPED','Pass�.');
define('_SUBMIT_1','Veuillez v�rifier les informations et assurez-vous qu\'elles soient correctes.');
define('_SUBMIT_2','Vous avez entr� les informations suivantes :');
define('_SUBMIT_3','<br>S�lectionnez <b>Nouvelle installation</b> ou <b>Mise � jour</b> pour continuer.');
define('_SUCCESS_1','Termin�');
define('_SUCCESS_2','Votre mise � niveau vers la derni�re version de PostNuke est termin�e.<br> N\'oubliez pas de changer votre config.php avant la premi�re utilisation.');
define('_UPDATED',' modifi�e.');
define('_UPDATING','Modification de la table :');
define('_UPGRADETAKESALONGTIME','Effectuer une mise � jour de PostNuke peut prendre un certain temps, peut-�tre plusieurs minutes.<br>Lorsque vous choisissez une option de mise � jour, ne cliquez qu\'une seule fois et attendez que l\'�cran suivant apparaisse.<br>Cliquer plusieurs fois peut causer une d�faillance lors de la mise � jour.');
define('_UPGRADE_1','<br><br><center>Mises � jour</center>');
define('_UPGRADE_2','<center>S�lectionner � partir de quel CMS vous effectuez la mise � jour.<br><br>Choisissez <b>PHP-Nuke</b> pour mettre � jour un syst�me PHP-Nuke existant.<br> Choisissez <b>PostNuke</b> pour mettre � jour un syst�me PostNuke existant.<br> Choisissez <b>MyPhpNuke</b> pour mettre � jour un syst�me MyPhpNuke existant.');
define('_VERSION_WARNING','NOTE : Les distributions Officielles de Postnuke ne sont disponibles qu\'� partir du site <a href="http://download.postnuke.com/" target="_blank">PostNuke.com</a>.<br>Pour l\'assurance de qualit�, assurez-vous d\'installer une distribution officielle.');

define('_YES', 'Oui');
define('_NO',  'Non');

/* Installation defines */
define('_MESSAGE_00_a',"Bienvenue sur PostNuke, version (0.764) =-Platinum-=");
define('_MESSAGE_00_b', "<p><a href=\"http://www.postnuke.com\" title=\"PostNuke\">PostNuke</a> est un syst�me de gestion de contenu (CMS)/weblog. Il est plus s�curitaire et stable que bien des produits concurrents et s'int�gre facilement dans un environnement � grand volume.</p><p>Quelques fonctions cl�s de PostNuke sont :</p><ul><li> Personnalisation de tous les aspects de l'apparence du site � travers des th�mes, incluant le support de feuilles de style</li><li> Possibilit� de d�finir si une information est destin�e � une seule langue ou pour toutes les langues</li><li> La meilleure garantie d'un affichage appropri� sur tous les navigateurs gr�ce � la compatibilit� HTML 4.01 transitionnel standard</li><li> une API (application programming interface) et une documentation compl�te permettant l'expansion des fonctionalit�s de votre site � travers des modules et des blocs</li></ul><p>PostNuke est une communaut� tr�s active de d�veloppeurs et de support sur <a href=\"http://www.postnuke.com\" title=\"PostNuke\">PostNuke.com</a>.</p><p>Nous esp�rons que vous appr�cierez utiliser PostNuke.</p><p><strong>L'�quipe de d�veloppement de PostNuke</strong></p><p><em>Note : vous pouvez modifier ou supprimer ce message en vous rendant dans l'administration de votre site et en cliquant sur la section 'Messages Administratifs'</em></p>");
define('_MESSAGE_00_d','0');
define('_MESSAGE_00_e','1');
define('_MESSAGE_00_f','1');
define('_MESSAGE_00_g','');

define('_FOOTMSGTEXT','<a href="http://www.postnuke.com" title="PostNuke"><img src="images/powered/postnuke.butn.gif" alt="Site Internet g�n�r� par PostNuke" /></a> <a href="http://adodb.sourceforge.net" title="Librairies ADODB"><img src="images/powered/adodb2.gif" alt="Librairies ADODB" /></a> <a href="http://www.php.net" title="PHP.net"><img src="images/powered/php4_powered.gif" alt="Langage PHP" /></a><p>Tous les logos et marques de commerce sur ce site sont la propri�t� de leur auteur respectif. Les commentaires sont la propri�t� des intervenants, tout le reste (c) '.date("Y").' par moi<br />Ce site Internet a �t� cr�� avec <a href="http://www.postnuke.com" title="PostNuke">PostNuke</a>, un syst�me de portail Web �crit en PHP. PostNuke est distribu� sous la licence <a href="http://www.gnu.org" title="GNU/GPL">GNU/GPL</a>.</p>Vous avez acc�s au Fil de syndication de ce site en utilisant le fichier <a href="backend.php" title="RSS">backend.php</a>');
define('_BLOCKTITLE_INCOMING','Contenu en Attente');
define('_BLOCKTITLE_WHOISONLINE','En Ligne');
define('_BLOCKTITLE_OTHERSTORIES','Autres Actualit�s');
define('_BLOCKTITLE_USERSBLOCK','Bloc Utilisateurs');
define('_BLOCKTITLE_SEARCHBOX','Bo�te de recherche');
define('_BLOCKTITLE_LANGUAGES','Langues');
define('_BLOCKTITLE_CATMENU','Menu Cat�gories');
define('_BLOCKTITLE_RANHEAD','Actualit�s en vrac');
define('_BLOCKTITLE_POLL','Sondage');
define('_BLOCKTITLE_BIGSTORY','La Une du jour');
define('_BLOCKTITLE_USERSLOGIN','Indentification');
define('_BLOCKTITLE_PASTART','Actualit� r�centes');
define('_BLOCKTITLE_ADMINMESS','Messages Administratifs');
define('_BLOCKTITLE_USERSBLOCK_TEXTE','Inscrivez ce que vous voulez ici');
define('_BLOCKTITLE_MAINMENU','Menu Principal');
define('_BLOCKTITLE_MAINMENU_HOME','Accueil');
define('_BLOCKTITLE_MAINMENU_HOMEALT',"Retour � la page d\'accueil.");
define('_BLOCKTITLE_MAINMENU_USER','Votre compte');
define('_BLOCKTITLE_MAINMENU_USERALT','Administrer votre compte personnel.');
define('_BLOCKTITLE_MAINMENU_ADMIN','Administration');
define('_BLOCKTITLE_MAINMENU_ADMINALT','Administrer votre site PostNuke.');
define('_BLOCKTITLE_MAINMENU_USEREXIT','D�connexion');
define('_BLOCKTITLE_MAINMENU_USEREXITALT','D�connexion de votre compte.');
define('_BLOCKTITLE_MAINMENU_DL','T�l�chargements');
define('_BLOCKTITLE_MAINMENU_DLALT','Parcourez les t�l�chargements de ce site.');
define('_BLOCKTITLE_MAINMENU_FAQ','FAQ');
define('_BLOCKTITLE_MAINMENU_FAQALT','Questions fr�quemment pos�es');
define('_BLOCKTITLE_MAINMENU_NEWS','Actualit�s');
define('_BLOCKTITLE_MAINMENU_NEWSALT','Derni�res Actualit�s du site.');
define('_BLOCKTITLE_MAINMENU_RWS','Comptes-rendus');
define('_BLOCKTITLE_MAINMENU_RWSALT','Section des Comptes-rendus du site.');
define('_BLOCKTITLE_MAINMENU_SEARCH','Recherche');
define('_BLOCKTITLE_MAINMENU_SEARCHALT','Rechercher sur le site.');
define('_BLOCKTITLE_MAINMENU_SECTIONS','Sections');
define('_BLOCKTITLE_MAINMENU_SECTIONSALT','Autre contenu du site.');
define('_BLOCKTITLE_MAINMENU_SNEWS','Proposer une Actualit�');
define('_BLOCKTITLE_MAINMENU_SNEWSALT','Proposer une Actualit�');
define('_BLOCKTITLE_MAINMENU_TOPICS','Sujets');
define('_BLOCKTITLE_MAINMENU_TOPICSALT',"Liste des sujets d\'actualit� du site.");
define('_BLOCKTITLE_MAINMENU_WLINKS','Liens Web');
define('_BLOCKTITLE_MAINMENU_WLINKSALT',"Liens vers d\'autres sites.");
define('_POLLDATATEXT1',"C\'est quoi PostNuke ?");
define('_POLLDATATEXT2','Ce dont on avait besoin.');
define('_POLLDATATEXT3',"R�fl�chis ? Je l\'utilise !");
define('_POLLDESCTEXT','Que pensez-vous de PostNuke ?');
define('_REVIEWSMAINTITLE','Titre de la Section des Comptes-rendus');
define('_REVIEWSMAINDESC','Description longue de la Section des Comptes-rendus');
// Groups
define('_GROUPS_1_a','Membres');
define('_GROUPS_2_a','Admins');

define('_PNDOCSLINKURL', 'http://www.postnuke-france.org/index.php?module=pnWiki&func=view');
define('_PNDOCSLINKTEXT', 'Documentation PostNuke (Fran�ais)');
define('_PNDOCSLINKTITLE', 'Documentation PostNuke (Fran�ais)');
define('_PNSUPPORTLINKURL', 'http://www.postnuke-france.org/');
define('_PNSUPPORTLINKTEXT', 'Support francophone (Fran�ais)');
define('_PNSUPPORTLINKTITLE', 'Support francophone (Fran�ais)');

define('_INSTALLGUIDEREF1', 'Svp., r�f�rez-vous au');
define('_INSTALLGUIDEREF2', 'Guide d\'installation');
define('_INSTALLGUIDEREF3', 'durant le processus');

/* pn0.76 */
define('_INSTALL_ANONYMOUS','Anonyme');
define('_INSTALL_BACKENDLANG','fr-fr');
define('_INSTALL_CENSORLIST','fuck,cunt,fucker,fucking,pussy,cock,c0ck,cum,twat,clit,bitch,fuk,fuking,motherfucker');
define('_INSTALL_ILLEGALNAMES','root adm linux webmaster admin god dieu administrateur personne anonymous anonimo anonyme anonymes');
define('_INSTALL_METAKEYWORDS','nuke, postnuke, free, communaut�, php, portail, opensource, open source, gpl, mysql, sql, database, web site, website, weblog, gestion, contenu, content management, contentmanagement, web content, contenu web, gestion, gestioncontenu');
define('_INSTALL_NOTIFYFRM','webmaster');
define('_INSTALL_NOTIFYMAIL','vous@votresite.com');
define('_INSTALL_NOTIFYMSG','Bonjour ! Vous avez une nouvelle proposition sur votre site.');
define('_INSTALL_NOTIFYSBJ','Actualit�s de mon site');
define('_INSTALL_PNPOWERED','Site G�n�r� par PostNuke');
define('_INSTALL_REASONS','Tel Quel,Hors Sujet,Provocateur,Troll,Redondant,Inspir�,Int�ressant,Informatif,Dr�le,Sur�valu�,Sous-�valu�');
define('_INSTALL_REGDISABLED','D�sol�, les inscriptions sont pr�sentement d�sactiv�es.');
define('_INSTALL_YOURSITENAME','Nom de Votre Site');
define('_INSTALL_YOURSLOGAN','Slogan de Votre Site');
/* Modules Descriptions */
define('_MODDESC_ADDSTORY','Ajouter une Actualit�');
define('_MODDESC_ADMIN','Administration');
define('_MODDESC_ADMMESSAGES','Affiche les messages automatiques/programm�s.');
define('_MODDESC_AUTOLINKS','Liens g�n�r�s � partir de Mots-Cl�s');
define('_MODDESC_AVANTGO','Actualit�s pour votre PDA');
define('_MODDESC_BANNERS','Banni�res');
define('_MODDESC_BLOCKS','Administration de vos blocs centres et de c�t�s');
define('_MODDESC_CENSOR','Contr�le de Censure du Site');
define('_MODDESC_COMMENTS','Commentaires sur les articles');
define('_MODDESC_CREDITS','Affiche les cr�dits des Modules, les licences, aides et contacts');
define('_MODDESC_DOWNLOADS','Fichiers en t�l�chargement');
define('_MODDESC_EPHEM','Ev�nements journaliers');
define('_MODDESC_FAQ','Questions Fr�quemment Pos�es');
define('_MODDESC_GROUPS','Gestion des Groupes');
define('_MODDESC_HEADFOOT','En-t�te et Pied de page Postnuke');
define('_MODDESC_LANGUAGES','Liste des Langues par leur nom et leur Code ISO');
define('_MODDESC_LEGAL','D�claration g�n�rique du site - Conditions Utilisation');
define('_MODDESC_LOSTPASS','R�cup�ration de mot passe utilisateur.');
define('_MODDESC_MAILER','Mailer Postnuke');
define('_MODDESC_MAILUSERS','Envoi de mail aux membres de votre site.');
define('_MODDESC_MBLIST','Information sur les membres du site');
define('_MODDESC_MESSAGES','Messages Priv�s des membres du site');
define('_MODDESC_MODULES','Activer/d�sactiver des modules, voir les cr�dits/docs/install.');
define('_MODDESC_MULTISITES','Cr�er plusieurs sites en utilisant la m�me installation de PN.');
define('_MODDESC_NEWS','Actualit�s');
define('_MODDESC_NEWUSER','Nouveau membre.');
define('_MODDESC_PERMISSIONS','Configuration des permissions');
define('_MODDESC_PNRENDER','Int�gration Smarty pour PostNuke');
define('_MODDESC_POLLS','Sondages');
define('_MODDESC_QUOTES','Citations');
define('_MODDESC_RATINGS','Utilitaire des �valuations');
define('_MODDESC_RECOMMENDUS','Nous Recommander');
define('_MODDESC_REFERERS','R�f�rants');
define('_MODDESC_REVIEWS','Comptes-Rendus');
define('_MODDESC_SEARCH','Recherche sur le site');
define('_MODDESC_SECTIONS','Sections');
define('_MODDESC_SETTINGS','Pr�f�rences');
define('_MODDESC_STATS','Statistiques du Site');
define('_MODDESC_SUBMITNEWS','Proposer une Actualit�');
define('_MODDESC_TOPICS','Sujets des Articles');
define('_MODDESC_TOPLIST','Palmar�s Top 10');
define('_MODDESC_TYPETOOL','Editeur Visuel TypeTool');
define('_MODDESC_USER','Administration des Membres');
define('_MODDESC_WEBLINKS','Liens vers des sites ext�rieurs');
define('_MODDESC_WIKI','Extension Wiki');
define('_MODDESC_XMLRPC','Utilitaire XML-RPC');
define('_MODDESC_YOURACCOUNT','Options des membres');
define('_MODDESC_XANTHIA','Moteur de th�me Xanthia');
/* Modules Aliases */
define('_MODNAME_ADDSTORY','Actualit�-Publier');
define('_MODNAME_ADMIN','Administration');
define('_MODNAME_ADMMESSAGES','Messages Admin');
define('_MODNAME_AUTOLINKS','Liens_Automatiques');
define('_MODNAME_AVANTGO','AvantGo');
define('_MODNAME_BANNERS','Banni�res');
define('_MODNAME_BLOCKS','Blocs');
define('_MODNAME_CENSOR','Censure');
define('_MODNAME_COMMENTS','Commentaires');
define('_MODNAME_CREDITS','Cr�dits');
define('_MODNAME_DOWNLOADS','T�l�chargements');
define('_MODNAME_EPHEM','Eph�m�rides');
define('_MODNAME_FAQ','FAQ');
define('_MODNAME_GROUPS','Groupes');
define('_MODNAME_HEADFOOT','Header_Footer');
define('_MODNAME_LANGUAGES','Langues');
define('_MODNAME_LEGAL','D�claration');
define('_MODNAME_LOSTPASS','R�cup�ration MDP');
define('_MODNAME_MAILER','Mailer');
define('_MODNAME_MAILUSERS','Mailing');
define('_MODNAME_MBLIST','Liste des Membres');
define('_MODNAME_MESSAGES','Messages');
define('_MODNAME_MODULES','Modules');
define('_MODNAME_MULTISITES','Multisites');
define('_MODNAME_NEWS','Actualit�s');
define('_MODNAME_NEWUSER','Nouveau Membre');
define('_MODNAME_PERMISSIONS','Permissions');
define('_MODNAME_PNRENDER','pnRender');
define('_MODNAME_POLLS','Sondages');
define('_MODNAME_QUOTES','Citations');
define('_MODNAME_RATINGS','Evaluation');
define('_MODNAME_RECOMMENDUS','Recommandation');
define('_MODNAME_REFERERS','R�f�rants');
define('_MODNAME_REVIEWS','Comptes-Rendus');
define('_MODNAME_SEARCH','Recherches');
define('_MODNAME_SECTIONS','Sections');
define('_MODNAME_SETTINGS','Pr�f�rences');
define('_MODNAME_STATS','Statistiques');
define('_MODNAME_SUBMITNEWS','Actualit�-Proposition');
define('_MODNAME_TOPICS','Sujets');
define('_MODNAME_TOPLIST','Palmar�s');
define('_MODNAME_TYPETOOL','TypeTool');
define('_MODNAME_USER','Membres');
define('_MODNAME_WEBLINKS','Liens Web');
define('_MODNAME_WIKI','Wiki');
define('_MODNAME_XMLRPC','xmlrpc');
define('_MODNAME_YOURACCOUNT','Votre Compte');
define('_MODNAME_XANTHIA','Xanthia');

/* admin module default categories */
define('_ADMIN_CATEGORY_00_a',  'Syst�me');
define('_ADMIN_CATEGORY_00_b',  'Modules Syst�mes');
define('_ADMIN_CATEGORY_01_a',  'Contenu');
define('_ADMIN_CATEGORY_01_b',  'Modules de Contenu');
define('_ADMIN_CATEGORY_02_a',  'Pack Ressources');
define('_ADMIN_CATEGORY_02_b',  'Modules du Pack Ressources');
define('_ADMIN_CATEGORY_03_a',  'Utilitaires');
define('_ADMIN_CATEGORY_03_b',  'Modules Utilitaires');
define('_ADMIN_CATEGORY_04_a',  'Compl�mentaires');
define('_ADMIN_CATEGORY_04_b',  'Modules Compl�mentaires');

/* language defines as taken from language/xxx/language.php */
define('_LANGUAGE_ARA','Arabe');
define('_LANGUAGE_BUL','Bulgare');
define('_LANGUAGE_CAT','Catalan');
define('_LANGUAGE_CES','Tch�que');
define('_LANGUAGE_CRO','Croate CRO');
define('_LANGUAGE_HRV','Croate HRV ');
define('_LANGUAGE_DAN','Danois');
define('_LANGUAGE_DEU','Allemand');
define('_LANGUAGE_ELL','Grec');
define('_LANGUAGE_ENG','Anglais');
define('_LANGUAGE_EPO','Esperanto');
define('_LANGUAGE_EST','Estonien');
define('_LANGUAGE_FIN','Finnois');
define('_LANGUAGE_FRA','Fran�ais');
define('_LANGUAGE_HEB','H�breu');
define('_LANGUAGE_HUN','Hongrois');
define('_LANGUAGE_IND','Indon�sien');
define('_LANGUAGE_ISL','Islandais');
define('_LANGUAGE_ITA','Italien');
define('_LANGUAGE_JPN','Japonais');
define('_LANGUAGE_KOR','Cor�en');
define('_LANGUAGE_LAV','Letton');
define('_LANGUAGE_LIT','Lituanien');
define('_LANGUAGE_MAS','Massa');
define('_LANGUAGE_MKD','Mac�donien');
define('_LANGUAGE_NLD','N�erlandais');
define('_LANGUAGE_NOR','Norv�gien');
define('_LANGUAGE_POL','Polonais');
define('_LANGUAGE_POR','Portugais');
define('_LANGUAGE_RON','Roumain');
define('_LANGUAGE_RUS','Russe');
define('_LANGUAGE_SLV','Slov�ne');
define('_LANGUAGE_SPA','Espagnol');
define('_LANGUAGE_SWE','Su�dois');
define('_LANGUAGE_THA','Tha�');
define('_LANGUAGE_TUR','Turque');
define('_LANGUAGE_UKR','Ukrainien');
define('_LANGUAGE_X_BRAZILIAN_PORTUGUESE','Portugais du Br�sil');
define('_LANGUAGE_X_KLINGON','Klingon');
define('_LANGUAGE_X_RUS_KOI8R','Russe KOI8-R');
define('_LANGUAGE_YID','Yiddish');
define('_LANGUAGE_ZHO','Chinois (Simp.)');

?>