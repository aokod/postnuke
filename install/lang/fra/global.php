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
define('_ADMIN_REPEATPASS','Mot de passe administrateur (vérification)');
define('_ADMIN_URL','URL de l\'administrateur');
define('_BTN_CHANGEINFO','Modifier les informations');
define('_BTN_CONTINUE','Continuer');
define('_BTN_FINISH','Terminer');
define('_BTN_NEXT','Suite');
define('_BTN_NEWINSTALL','Nouvelle installation');
define('_BTN_RECHECK','Nouvelle vérification');
define('_BTN_SET_LANGUAGE','Langue');
define('_BTN_SET_LOGIN','Identification');
define('_BTN_START','Commencer');
define('_BTN_SUBMIT','Valider');
define('_BTN_UPGRADE','Mise à jour');
define('_CHANGE_INFO_0','Information erronée');
define('_CHANGE_INFO_1','Corrigez les informations de votre base de données.');
define('_CHMOD_CHECK_1','Vérification des droits (CHMOD)');
define('_CHMOD_CHECK_2','Nous allons d\'abord vérifier que les droits d\'accès (CHMOD) permettent au script d\'écrire dans le fichier de configuration. S\'ils ne sont pas corrects, le script ne pourra pas encrypter les données de votre fichier de configuration. Encrypter les données d\'accès à la base est important pour la sécurité, c\'est un des objectifs de ce script. Vous ne pourrez plus changer vos paramètres d\'administration une fois ceux-ci configurés.');
define('_CHMOD_CHECK_3','Les droits CHMOD de config.php sont à 666 -- OK, ce script peut mettre le fichier à jour');
define('_CHMOD_CHECK_4','Veuillez changer les droits CHMOD de config.php à 666 pour que le script puisse mettre à jour et encrypter vos infos de BD');
define('_CHMOD_CHECK_5','Les droits CHMOD de config-old.php sont à 666 -- OK, ce script peut mettre le fichier à jour');
define('_CHMOD_CHECK_6','Veuillez changer les droits CHMOD de config-old.php à 666 pour que le script pusse mettre à jour et encrypter vos infos de BD');
define('_CHM_CHECK_1','Svp., entrez vos informations de Base de données. Si vous n\'avez pas l\'accès \'\'root\'\' à votre Base de données (serveur virtuel ou mutualisé, etc), vous devrez préalablement créer votre base de données avant de procéder. En règle générale, si vous ne pouvez pas créer la base de données par phpMyAdmin à cause de votre hébergement ou de la sécurité sur mySQL, ce script ne pourra pas la créer pour vous. Ce script devra quand même être exécuté et pourra remplir la base de données existante.<br><br>Si vous ne connaissez pas les valeurs pour l\'hôte de base de données, l\'utilisateur ou le mot de passe, laissez les valeurs par défaut.  <br><br><b>NOTE : Certains serveurs utilisent 127.0.0.1 comme hôte de base de données. Si vous obtenez une erreur "Impossible de se connecter à la base MySQL", essayez de changer l\'hôte par défaut en inscrivant 127.0.0.1 </b><br><br>Si des problèmes persistent, contactez votre hébergeur afin d\'obtenir vos informations de connexion. ');
define('_CONTINUE_1','Préférences de votre BD');
define('_CONTINUE_2','Vous pouvez maintenant définir votre compte admin. Si vous passez cette étape, votre compte admin sera Admin/Password (attention aux majuscules/minuscules). Il vaut mieux le définir maintenant et non plus tard.');
define('_DBHOST','Serveur hôte de la base');
define('_DBINFO','Informations sur la base de données');
define('_DBNAME','Nom de la base');
define('_DBPASS','Mot de passe de la base');
define('_DBPREFIX','Préfixe (pour le partage de tables)');
define('_DBTABLETYPE','Type de table de base de données');
define('_DBTYPE','Type de base');
define('_DBUNAME','Utilisateur de la base');
define('_DEFAULT_1','Ce script va installer la base de données PostNuke et vous assistera dans la création des variables nécessaires au démarrage. Vous serez guidé à travers une succession d\'écrans. Chaque page correspond à une étape de l\'installation. Nous estimons que la procédure d\'installation prendra environ 10 minutes. Si vous avez le moindre soucis, n\'hésitez pas à lire nos forums pour obtenir de l\'aide.');
define('_DEFAULT_2','Licence');
define('_DEFAULT_3','Veuillez prendre connaissance de la Licence Publique Générale GNU (GPL). Bien que PostNuke soit un logiciel libre, sa distribution et sa publication sont soumises à certaines obligations.');
define('_DONE','Terminé.');
define('_FINISH_1','Générique');
define('_FINISH_2','Voici les gens qui font exister PostNuke. Prenez un peu de temps pour leur faire savoir que vous appréciez leur travail. Si vous souhaitez apparaître dans cette liste, contactez-nous pour rejoindre l\'équipe de développement. Nous sommes toujours ouvert à un peu d\'aide.');
define('_FINISH_3','L\'installation de PostNuke est maintenant terminée. Si vous rencontrez des problèmes, faites-le nous savoir. Assurez-vous d\'effacer ce script, vous n\'en aurez plus besoin.');
define('_FINISH_4','Accès à votre site PostNuke');
define('_FOOTER_1','Merci d\'utiliser PostNuke, et bienvenue dans notre communauté.');
define('_FORUM_INFO_1','Vos tables de forum sont inchangées.<br><br>Pour info, ces tables sont :');
define('_FORUM_INFO_2','Vous pouvez effacer ces tables si vous n\'avez pas l\'intention d\'utiliser les forums.<br> phpBB devrait ê;tre disponible sous forme de module sur http://mods.postnuke.com');
define('_INPUT_DATA_1','Données envoyées');
define('_INSTALLATION','Installation de PostNuke');
define('_INSTALLED','Installé');
define('_MADE',' créée.');
define('_MAKE_DB_1','Impossible de créer la base de données.');
define('_MAKE_DB_2','a été créée.');
define('_MAKE_DB_3','Aucune base à créer.');
define('_MODIFY_FILE_1','Erreur : accès en lecture impossible sur :');
define('_MODIFY_FILE_2','Erreur : accès en écriture impossible sur :');
define('_MODIFY_FILE_3','0 ligne modifiée, aucun changement');
define('_MYPHPNUKE_1','Mise à jour à partir de MyPHPNuke 1.8.7 ?');
define('_MYPHPNUKE_2','Cliquez simplement sur le bouton <b>MyPHPNuke 1.8.7</b>');
define('_MYPHPNUKE_3','Mise à jour à partir de MyPHPNuke 1.8.8b2 ?');
define('_MYPHPNUKE_4','Cliquez simplement sur le bouton <b>MyPHPNuke 1.8.8</b>');
define('_NEWINSTALL','Nouvelle Installation');
define('_NEW_INSTALL_1','Vous avez choisi de faire une nouvelle installation. Voici les informations que vous avez fournies :');
define('_NEW_INSTALL_2','Si vous avez un accès root, cochez la case pour <b>créer la base de données</b>. Sinon, cliquez sur Commencer.<br>Si vous n\'avez pas d\'accès root, vous devez créer la base manuellement et le script ajoutera les tables pour vous');
define('_NEW_INSTALL_3','Créer la base de données');
define('_NOTMADE','Impossible de créer');
define('_NOTSELECT','Impossible de sélectionner la base.');
define('_NOTUPDATED','Impossible de mettre à jour ');
define('_PHPNUKE_1','Mise à jour à partir de PHP-Nuke 4.4 ?');
define('_PHPNUKE_10','Cliquez simplement sur le bouton <b>PHP-Nuke 5.3.1</b>');
define('_PHPNUKE_11','Mise à jour à partir de PHP-Nuke 5.4? ');
define('_PHPNUKE_12','Cliquez simplement sur le bouton <b>PHP-Nuke 5.4</b>');
define('_PHPNUKE_2','Veuillez lire la note qui suit, et appuyez sur le bouton <b>PHP-Nuke 4.4</b> pour démarrer.<br><br> Ce script laissera intactes vos tables de forum mais cetter version ne tiendra pas compte des données.<i> Un script de conversion pour ces do');
define('_PHPNUKE_3','Mise à jour à partir de PHP-Nuke 5 ?');
define('_PHPNUKE_4','Cliquez simplement sur le bouton <b>PHP-Nuke 5</b>');
define('_PHPNUKE_5','Mise à jour à partir de PHP-Nuke 5.2 ?');
define('_PHPNUKE_6','Cliquez simplement sur le bouton <b>PHP-Nuke 5.2</b>');
define('_PHPNUKE_7','Mise à jour à partir de PHP-Nuke 5.3 ?');
define('_PHPNUKE_8','Cliquez simplement sur <b>PHP-Nuke 5.3</b>');
define('_PHPNUKE_9','Mise à jour à partir de PHP-Nuke 5.3.1 ?');
define('_PHP_CHECK_1','Votre version de PHP est');
define('_PHP_CHECK_2','Vous devez au moins passer à la version 4.1.0 de PHP - <a href=\'http://www.php.net\' title=\'PHP\'>http://www.php.net</a>');
define('_PHP_CHECK_3','Pas bon ! magic_quotes_gpc est désactivé.<br>Cela peut en général ê;tre contourné en utilisant un fichier .htaccess contenant la ligne :<br>php_flag magic_quotes_gpc On');
define('_PHP_CHECK_4','Pas bon ! magic_quotes_runtime est désactivé.<br>Cela peut en général ê;tre contourné en utilisant un fichier .htaccess contenant la ligne :<br>php_flag magic_quotes_runtime Off');
define('_PN6_1','Admin : Vous devrez faire une nouvelle sauvegarde des préférences de votre site à partir de la page d\'administration - AUSSITOT QUE POSSIBLE !');
define('_PN6_2','(veuillez nous excuser pour la gê;ne occasionnée)');
define('_PN6_3','ERREUR : Fichier non trouvé :');
define('_PN6_4','Conversion de l\'ancien format de blocs de boutons effectuée.');
define('_PNTEMP_DIRNOTWRITABLE', 'Svp. Modifiez les permissions de ce répertoire en 777 afin de permettre au script la création de fichier dans celui-ci (Indice : utilisez "chmod")');
define('_PNTEMP_DIRWRITABLE', 'Ok, le script peut écrire dans ce répertoire');
define('_POSTNUKE_1','Mise à jour à partir de PostNuke .5x ?');
define('_POSTNUKE_10','Cliquez simplement sur le bouton <b>PostNuke .64</b>');
define('_POSTNUKE_11','Mise à jour à partir de PostNuke .7 ?');
define('_POSTNUKE_12','Cliquez simplement sur le bouton <b>PostNuke 7</b>');
define('_POSTNUKE_13','Mise à jour à partir de PostNuke .71 ?');
define('_POSTNUKE_14','Cliquez simplement sur le bouton <b>PostNuke 71</b>');
define('_POSTNUKE_15','Pour valider votre système de langue ?');
define('_POSTNUKE_16','Pressez sur le bouton <b>Valider</b>');
define('_POSTNUKE_17','Pour valider la structure des tables ?');
define('_POSTNUKE_18','Pressez sur le bouton <b>Valider</b>');

define('_POSTNUKE_19','Mise à jour à partir de PostNuke .72 ?');
define('_POSTNUKE_2','Cliquez simplement sur le bouton <b>PostNuke .5</b>');
define('_POSTNUKE_20','Cliquez simplement sur le bouton <b>PostNuke .72</b>');
define('_POSTNUKE_3','Mise à jour à partir de PostNuke .6 / .61 ?');
define('_POSTNUKE_4','Cliquez simplement sur le bouton <b>PostNuke .6</b>');
define('_POSTNUKE_5','Mise à jour à partir de PostNuke .62 ?');
define('_POSTNUKE_6','Cliquez simplement sur le bouton <b>PostNuke .62</b>');
define('_POSTNUKE_7','Mise à jour à partir de PostNuke .63 ?');
define('_POSTNUKE_8','Cliquez simplement sur le bouton <b>PostNuke .63</b><br>');
define('_POSTNUKE_9','Mise à jour à partir de PostNuke .64 ?');
define('_PWBADMATCH','Les mots de passe entrés ne sont pas identiques. Revenez en arrière et tapez les mots de passe à nouveau.');
define('_QUOTESCHECK_1','NS-Quotes Check');
define('_QUOTESCHECK_2','The Former NS-Quotes module has been deprecated in favor of the new Quotes module.<br> Please remove the modules/NS-Quotes directory.');
define('_SELECT_LANGUAGE_1','Sélectionnez votre langue.');
define('_SELECT_LANGUAGE_2','Langue :');
define('_SHOW_ERROR_INFO_1','Erreur d\'écriture </b> impossible de mettre à jour le fichier \'\'config.php\'\'<br/> Vous allez devoir le modifier manuellement à l\'aide d\'un éditeur de texte.<br />. Voici les changements à effectuer ');
define('_SKIPPED','Passé.');
define('_SUBMIT_1','Veuillez vérifier les informations et assurez-vous qu\'elles soient correctes.');
define('_SUBMIT_2','Vous avez entré les informations suivantes :');
define('_SUBMIT_3','<br>Sélectionnez <b>Nouvelle installation</b> ou <b>Mise à jour</b> pour continuer.');
define('_SUCCESS_1','Terminé');
define('_SUCCESS_2','Votre mise à niveau vers la dernière version de PostNuke est terminée.<br> N\'oubliez pas de changer votre config.php avant la première utilisation.');
define('_UPDATED',' modifiée.');
define('_UPDATING','Modification de la table :');
define('_UPGRADETAKESALONGTIME','Effectuer une mise à jour de PostNuke peut prendre un certain temps, peut-être plusieurs minutes.<br>Lorsque vous choisissez une option de mise à jour, ne cliquez qu\'une seule fois et attendez que l\'écran suivant apparaisse.<br>Cliquer plusieurs fois peut causer une défaillance lors de la mise à jour.');
define('_UPGRADE_1','<br><br><center>Mises à jour</center>');
define('_UPGRADE_2','<center>Sélectionner à partir de quel CMS vous effectuez la mise à jour.<br><br>Choisissez <b>PHP-Nuke</b> pour mettre à jour un système PHP-Nuke existant.<br> Choisissez <b>PostNuke</b> pour mettre à jour un système PostNuke existant.<br> Choisissez <b>MyPhpNuke</b> pour mettre à jour un système MyPhpNuke existant.');
define('_VERSION_WARNING','NOTE : Les distributions Officielles de Postnuke ne sont disponibles qu\'à partir du site <a href="http://download.postnuke.com/" target="_blank">PostNuke.com</a>.<br>Pour l\'assurance de qualité, assurez-vous d\'installer une distribution officielle.');

define('_YES', 'Oui');
define('_NO',  'Non');

/* Installation defines */
define('_MESSAGE_00_a',"Bienvenue sur PostNuke, version (0.764) =-Platinum-=");
define('_MESSAGE_00_b', "<p><a href=\"http://www.postnuke.com\" title=\"PostNuke\">PostNuke</a> est un système de gestion de contenu (CMS)/weblog. Il est plus sécuritaire et stable que bien des produits concurrents et s'intègre facilement dans un environnement à grand volume.</p><p>Quelques fonctions clés de PostNuke sont :</p><ul><li> Personnalisation de tous les aspects de l'apparence du site à travers des thèmes, incluant le support de feuilles de style</li><li> Possibilité de définir si une information est destinée à une seule langue ou pour toutes les langues</li><li> La meilleure garantie d'un affichage approprié sur tous les navigateurs grâce à la compatibilité HTML 4.01 transitionnel standard</li><li> une API (application programming interface) et une documentation complète permettant l'expansion des fonctionalités de votre site à travers des modules et des blocs</li></ul><p>PostNuke est une communauté très active de développeurs et de support sur <a href=\"http://www.postnuke.com\" title=\"PostNuke\">PostNuke.com</a>.</p><p>Nous espérons que vous apprécierez utiliser PostNuke.</p><p><strong>L'équipe de développement de PostNuke</strong></p><p><em>Note : vous pouvez modifier ou supprimer ce message en vous rendant dans l'administration de votre site et en cliquant sur la section 'Messages Administratifs'</em></p>");
define('_MESSAGE_00_d','0');
define('_MESSAGE_00_e','1');
define('_MESSAGE_00_f','1');
define('_MESSAGE_00_g','');

define('_FOOTMSGTEXT','<a href="http://www.postnuke.com" title="PostNuke"><img src="images/powered/postnuke.butn.gif" alt="Site Internet généré par PostNuke" /></a> <a href="http://adodb.sourceforge.net" title="Librairies ADODB"><img src="images/powered/adodb2.gif" alt="Librairies ADODB" /></a> <a href="http://www.php.net" title="PHP.net"><img src="images/powered/php4_powered.gif" alt="Langage PHP" /></a><p>Tous les logos et marques de commerce sur ce site sont la propriété de leur auteur respectif. Les commentaires sont la propriété des intervenants, tout le reste (c) '.date("Y").' par moi<br />Ce site Internet a été créé avec <a href="http://www.postnuke.com" title="PostNuke">PostNuke</a>, un système de portail Web écrit en PHP. PostNuke est distribué sous la licence <a href="http://www.gnu.org" title="GNU/GPL">GNU/GPL</a>.</p>Vous avez accès au Fil de syndication de ce site en utilisant le fichier <a href="backend.php" title="RSS">backend.php</a>');
define('_BLOCKTITLE_INCOMING','Contenu en Attente');
define('_BLOCKTITLE_WHOISONLINE','En Ligne');
define('_BLOCKTITLE_OTHERSTORIES','Autres Actualités');
define('_BLOCKTITLE_USERSBLOCK','Bloc Utilisateurs');
define('_BLOCKTITLE_SEARCHBOX','Boîte de recherche');
define('_BLOCKTITLE_LANGUAGES','Langues');
define('_BLOCKTITLE_CATMENU','Menu Catégories');
define('_BLOCKTITLE_RANHEAD','Actualités en vrac');
define('_BLOCKTITLE_POLL','Sondage');
define('_BLOCKTITLE_BIGSTORY','La Une du jour');
define('_BLOCKTITLE_USERSLOGIN','Indentification');
define('_BLOCKTITLE_PASTART','Actualité récentes');
define('_BLOCKTITLE_ADMINMESS','Messages Administratifs');
define('_BLOCKTITLE_USERSBLOCK_TEXTE','Inscrivez ce que vous voulez ici');
define('_BLOCKTITLE_MAINMENU','Menu Principal');
define('_BLOCKTITLE_MAINMENU_HOME','Accueil');
define('_BLOCKTITLE_MAINMENU_HOMEALT',"Retour à la page d\'accueil.");
define('_BLOCKTITLE_MAINMENU_USER','Votre compte');
define('_BLOCKTITLE_MAINMENU_USERALT','Administrer votre compte personnel.');
define('_BLOCKTITLE_MAINMENU_ADMIN','Administration');
define('_BLOCKTITLE_MAINMENU_ADMINALT','Administrer votre site PostNuke.');
define('_BLOCKTITLE_MAINMENU_USEREXIT','Déconnexion');
define('_BLOCKTITLE_MAINMENU_USEREXITALT','Déconnexion de votre compte.');
define('_BLOCKTITLE_MAINMENU_DL','Téléchargements');
define('_BLOCKTITLE_MAINMENU_DLALT','Parcourez les téléchargements de ce site.');
define('_BLOCKTITLE_MAINMENU_FAQ','FAQ');
define('_BLOCKTITLE_MAINMENU_FAQALT','Questions fréquemment posées');
define('_BLOCKTITLE_MAINMENU_NEWS','Actualités');
define('_BLOCKTITLE_MAINMENU_NEWSALT','Dernières Actualités du site.');
define('_BLOCKTITLE_MAINMENU_RWS','Comptes-rendus');
define('_BLOCKTITLE_MAINMENU_RWSALT','Section des Comptes-rendus du site.');
define('_BLOCKTITLE_MAINMENU_SEARCH','Recherche');
define('_BLOCKTITLE_MAINMENU_SEARCHALT','Rechercher sur le site.');
define('_BLOCKTITLE_MAINMENU_SECTIONS','Sections');
define('_BLOCKTITLE_MAINMENU_SECTIONSALT','Autre contenu du site.');
define('_BLOCKTITLE_MAINMENU_SNEWS','Proposer une Actualité');
define('_BLOCKTITLE_MAINMENU_SNEWSALT','Proposer une Actualité');
define('_BLOCKTITLE_MAINMENU_TOPICS','Sujets');
define('_BLOCKTITLE_MAINMENU_TOPICSALT',"Liste des sujets d\'actualité du site.");
define('_BLOCKTITLE_MAINMENU_WLINKS','Liens Web');
define('_BLOCKTITLE_MAINMENU_WLINKSALT',"Liens vers d\'autres sites.");
define('_POLLDATATEXT1',"C\'est quoi PostNuke ?");
define('_POLLDATATEXT2','Ce dont on avait besoin.');
define('_POLLDATATEXT3',"Réfléchis ? Je l\'utilise !");
define('_POLLDESCTEXT','Que pensez-vous de PostNuke ?');
define('_REVIEWSMAINTITLE','Titre de la Section des Comptes-rendus');
define('_REVIEWSMAINDESC','Description longue de la Section des Comptes-rendus');
// Groups
define('_GROUPS_1_a','Membres');
define('_GROUPS_2_a','Admins');

define('_PNDOCSLINKURL', 'http://www.postnuke-france.org/index.php?module=pnWiki&func=view');
define('_PNDOCSLINKTEXT', 'Documentation PostNuke (Français)');
define('_PNDOCSLINKTITLE', 'Documentation PostNuke (Français)');
define('_PNSUPPORTLINKURL', 'http://www.postnuke-france.org/');
define('_PNSUPPORTLINKTEXT', 'Support francophone (Français)');
define('_PNSUPPORTLINKTITLE', 'Support francophone (Français)');

define('_INSTALLGUIDEREF1', 'Svp., référez-vous au');
define('_INSTALLGUIDEREF2', 'Guide d\'installation');
define('_INSTALLGUIDEREF3', 'durant le processus');

/* pn0.76 */
define('_INSTALL_ANONYMOUS','Anonyme');
define('_INSTALL_BACKENDLANG','fr-fr');
define('_INSTALL_CENSORLIST','fuck,cunt,fucker,fucking,pussy,cock,c0ck,cum,twat,clit,bitch,fuk,fuking,motherfucker');
define('_INSTALL_ILLEGALNAMES','root adm linux webmaster admin god dieu administrateur personne anonymous anonimo anonyme anonymes');
define('_INSTALL_METAKEYWORDS','nuke, postnuke, free, communauté, php, portail, opensource, open source, gpl, mysql, sql, database, web site, website, weblog, gestion, contenu, content management, contentmanagement, web content, contenu web, gestion, gestioncontenu');
define('_INSTALL_NOTIFYFRM','webmaster');
define('_INSTALL_NOTIFYMAIL','vous@votresite.com');
define('_INSTALL_NOTIFYMSG','Bonjour ! Vous avez une nouvelle proposition sur votre site.');
define('_INSTALL_NOTIFYSBJ','Actualités de mon site');
define('_INSTALL_PNPOWERED','Site Généré par PostNuke');
define('_INSTALL_REASONS','Tel Quel,Hors Sujet,Provocateur,Troll,Redondant,Inspiré,Intéressant,Informatif,Drôle,Surévalué,Sous-évalué');
define('_INSTALL_REGDISABLED','Désolé, les inscriptions sont présentement désactivées.');
define('_INSTALL_YOURSITENAME','Nom de Votre Site');
define('_INSTALL_YOURSLOGAN','Slogan de Votre Site');
/* Modules Descriptions */
define('_MODDESC_ADDSTORY','Ajouter une Actualité');
define('_MODDESC_ADMIN','Administration');
define('_MODDESC_ADMMESSAGES','Affiche les messages automatiques/programmés.');
define('_MODDESC_AUTOLINKS','Liens générés à partir de Mots-Clés');
define('_MODDESC_AVANTGO','Actualités pour votre PDA');
define('_MODDESC_BANNERS','Bannières');
define('_MODDESC_BLOCKS','Administration de vos blocs centres et de côtés');
define('_MODDESC_CENSOR','Contrôle de Censure du Site');
define('_MODDESC_COMMENTS','Commentaires sur les articles');
define('_MODDESC_CREDITS','Affiche les crédits des Modules, les licences, aides et contacts');
define('_MODDESC_DOWNLOADS','Fichiers en téléchargement');
define('_MODDESC_EPHEM','Evénements journaliers');
define('_MODDESC_FAQ','Questions Fréquemment Posées');
define('_MODDESC_GROUPS','Gestion des Groupes');
define('_MODDESC_HEADFOOT','En-tête et Pied de page Postnuke');
define('_MODDESC_LANGUAGES','Liste des Langues par leur nom et leur Code ISO');
define('_MODDESC_LEGAL','Déclaration générique du site - Conditions Utilisation');
define('_MODDESC_LOSTPASS','Récupération de mot passe utilisateur.');
define('_MODDESC_MAILER','Mailer Postnuke');
define('_MODDESC_MAILUSERS','Envoi de mail aux membres de votre site.');
define('_MODDESC_MBLIST','Information sur les membres du site');
define('_MODDESC_MESSAGES','Messages Privés des membres du site');
define('_MODDESC_MODULES','Activer/désactiver des modules, voir les crédits/docs/install.');
define('_MODDESC_MULTISITES','Créer plusieurs sites en utilisant la même installation de PN.');
define('_MODDESC_NEWS','Actualités');
define('_MODDESC_NEWUSER','Nouveau membre.');
define('_MODDESC_PERMISSIONS','Configuration des permissions');
define('_MODDESC_PNRENDER','Intégration Smarty pour PostNuke');
define('_MODDESC_POLLS','Sondages');
define('_MODDESC_QUOTES','Citations');
define('_MODDESC_RATINGS','Utilitaire des évaluations');
define('_MODDESC_RECOMMENDUS','Nous Recommander');
define('_MODDESC_REFERERS','Référants');
define('_MODDESC_REVIEWS','Comptes-Rendus');
define('_MODDESC_SEARCH','Recherche sur le site');
define('_MODDESC_SECTIONS','Sections');
define('_MODDESC_SETTINGS','Préférences');
define('_MODDESC_STATS','Statistiques du Site');
define('_MODDESC_SUBMITNEWS','Proposer une Actualité');
define('_MODDESC_TOPICS','Sujets des Articles');
define('_MODDESC_TOPLIST','Palmarès Top 10');
define('_MODDESC_TYPETOOL','Editeur Visuel TypeTool');
define('_MODDESC_USER','Administration des Membres');
define('_MODDESC_WEBLINKS','Liens vers des sites extérieurs');
define('_MODDESC_WIKI','Extension Wiki');
define('_MODDESC_XMLRPC','Utilitaire XML-RPC');
define('_MODDESC_YOURACCOUNT','Options des membres');
define('_MODDESC_XANTHIA','Moteur de thème Xanthia');
/* Modules Aliases */
define('_MODNAME_ADDSTORY','Actualité-Publier');
define('_MODNAME_ADMIN','Administration');
define('_MODNAME_ADMMESSAGES','Messages Admin');
define('_MODNAME_AUTOLINKS','Liens_Automatiques');
define('_MODNAME_AVANTGO','AvantGo');
define('_MODNAME_BANNERS','Bannières');
define('_MODNAME_BLOCKS','Blocs');
define('_MODNAME_CENSOR','Censure');
define('_MODNAME_COMMENTS','Commentaires');
define('_MODNAME_CREDITS','Crédits');
define('_MODNAME_DOWNLOADS','Téléchargements');
define('_MODNAME_EPHEM','Ephémérides');
define('_MODNAME_FAQ','FAQ');
define('_MODNAME_GROUPS','Groupes');
define('_MODNAME_HEADFOOT','Header_Footer');
define('_MODNAME_LANGUAGES','Langues');
define('_MODNAME_LEGAL','Déclaration');
define('_MODNAME_LOSTPASS','Récupération MDP');
define('_MODNAME_MAILER','Mailer');
define('_MODNAME_MAILUSERS','Mailing');
define('_MODNAME_MBLIST','Liste des Membres');
define('_MODNAME_MESSAGES','Messages');
define('_MODNAME_MODULES','Modules');
define('_MODNAME_MULTISITES','Multisites');
define('_MODNAME_NEWS','Actualités');
define('_MODNAME_NEWUSER','Nouveau Membre');
define('_MODNAME_PERMISSIONS','Permissions');
define('_MODNAME_PNRENDER','pnRender');
define('_MODNAME_POLLS','Sondages');
define('_MODNAME_QUOTES','Citations');
define('_MODNAME_RATINGS','Evaluation');
define('_MODNAME_RECOMMENDUS','Recommandation');
define('_MODNAME_REFERERS','Référants');
define('_MODNAME_REVIEWS','Comptes-Rendus');
define('_MODNAME_SEARCH','Recherches');
define('_MODNAME_SECTIONS','Sections');
define('_MODNAME_SETTINGS','Préférences');
define('_MODNAME_STATS','Statistiques');
define('_MODNAME_SUBMITNEWS','Actualité-Proposition');
define('_MODNAME_TOPICS','Sujets');
define('_MODNAME_TOPLIST','Palmarès');
define('_MODNAME_TYPETOOL','TypeTool');
define('_MODNAME_USER','Membres');
define('_MODNAME_WEBLINKS','Liens Web');
define('_MODNAME_WIKI','Wiki');
define('_MODNAME_XMLRPC','xmlrpc');
define('_MODNAME_YOURACCOUNT','Votre Compte');
define('_MODNAME_XANTHIA','Xanthia');

/* admin module default categories */
define('_ADMIN_CATEGORY_00_a',  'Système');
define('_ADMIN_CATEGORY_00_b',  'Modules Systèmes');
define('_ADMIN_CATEGORY_01_a',  'Contenu');
define('_ADMIN_CATEGORY_01_b',  'Modules de Contenu');
define('_ADMIN_CATEGORY_02_a',  'Pack Ressources');
define('_ADMIN_CATEGORY_02_b',  'Modules du Pack Ressources');
define('_ADMIN_CATEGORY_03_a',  'Utilitaires');
define('_ADMIN_CATEGORY_03_b',  'Modules Utilitaires');
define('_ADMIN_CATEGORY_04_a',  'Complémentaires');
define('_ADMIN_CATEGORY_04_b',  'Modules Complémentaires');

/* language defines as taken from language/xxx/language.php */
define('_LANGUAGE_ARA','Arabe');
define('_LANGUAGE_BUL','Bulgare');
define('_LANGUAGE_CAT','Catalan');
define('_LANGUAGE_CES','Tchèque');
define('_LANGUAGE_CRO','Croate CRO');
define('_LANGUAGE_HRV','Croate HRV ');
define('_LANGUAGE_DAN','Danois');
define('_LANGUAGE_DEU','Allemand');
define('_LANGUAGE_ELL','Grec');
define('_LANGUAGE_ENG','Anglais');
define('_LANGUAGE_EPO','Esperanto');
define('_LANGUAGE_EST','Estonien');
define('_LANGUAGE_FIN','Finnois');
define('_LANGUAGE_FRA','Français');
define('_LANGUAGE_HEB','Hébreu');
define('_LANGUAGE_HUN','Hongrois');
define('_LANGUAGE_IND','Indonésien');
define('_LANGUAGE_ISL','Islandais');
define('_LANGUAGE_ITA','Italien');
define('_LANGUAGE_JPN','Japonais');
define('_LANGUAGE_KOR','Coréen');
define('_LANGUAGE_LAV','Letton');
define('_LANGUAGE_LIT','Lituanien');
define('_LANGUAGE_MAS','Massa');
define('_LANGUAGE_MKD','Macédonien');
define('_LANGUAGE_NLD','Néerlandais');
define('_LANGUAGE_NOR','Norvégien');
define('_LANGUAGE_POL','Polonais');
define('_LANGUAGE_POR','Portugais');
define('_LANGUAGE_RON','Roumain');
define('_LANGUAGE_RUS','Russe');
define('_LANGUAGE_SLV','Slovène');
define('_LANGUAGE_SPA','Espagnol');
define('_LANGUAGE_SWE','Suédois');
define('_LANGUAGE_THA','Thaï');
define('_LANGUAGE_TUR','Turque');
define('_LANGUAGE_UKR','Ukrainien');
define('_LANGUAGE_X_BRAZILIAN_PORTUGUESE','Portugais du Brésil');
define('_LANGUAGE_X_KLINGON','Klingon');
define('_LANGUAGE_X_RUS_KOI8R','Russe KOI8-R');
define('_LANGUAGE_YID','Yiddish');
define('_LANGUAGE_ZHO','Chinois (Simp.)');

?>