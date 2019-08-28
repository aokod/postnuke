<?php
// $Id: outputfilter.shorturls.php 19371 2006-07-04 12:05:56Z markwest $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2002 by the PostNuke Development Team.
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
/**
 * Xanthia plugin
 *
 * This file is a plugin for pnRender, the PostNuke implementation of Smarty
 *
 * @package      Xanthia_Templating_Environment
 * @subpackage   Xanthia
 * @version      $Id: outputfilter.shorturls.php 19371 2006-07-04 12:05:56Z markwest $
 * @author       Craig R. Saunders (coldrolledsteel)
 * @author       Martin Anderson (msanderson)
 * @author       Luca Longinotti (CHTEKK)
 * @author       Mark West
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */


/**
 * Smarty short urls outputfilter plugin
 *
 * File:      outputfilter.shorturls.php<br>
 * Type:      outputfilter<br>
 * Name:      shorturls<br>
 * Date:      Nov 7, 2003<br>
 * Purpose:   Generate short urls for the PN CMS
 * @author      Craig R. Saunders (coldrolledsteel)
 * @author      Martin Anderson (msanderson)
 * @author      Luca Longinotti (CHTEKK)
 * @author      Mark West
 * @version   1.3
 * @param     string
 * @param     Smarty
 */
function smarty_outputfilter_shorturls($source, &$smarty)
{
  // Credits to / Crediti a:
  // ColdRolledSteel: for creating this file and the rewrite rules / per aver creato questo file e le regole di riscrittura
  // msandersen: for tweaking this file and the rewrite rules / per aver aggiornato questo file e le regole di riscrittura
  // CHTEKK: adaptation for eNvolution, writing/rewriting of many rules, italian translation and on/off variable / adattazione ad eNvolution, scrittura/riscrittura di diverse regole, traduzione italiana e variabile on/off
  //
  // ENG
  // If you control the server, it is preferable for better performance to put rewrite rules
  // from the .htaccess file into main configuration file, httpd.conf.
  //
  // Rule Legend (the order doesn't match the one in .htaccess)
  //
  //  1-5: Modules with url's like index.php?name=MODULE and PNphpBB2 v1.1, v1.1a
  //  6: Outbox in Private Messages: Messages-outbox.html
  //  7: Inbox in Private Messages: Messages-inbox.html
  //  8: Search by author: Search-author-[user].html
  //  9: Search by topic: Search-topics-[topicID].html
  // 10: Print function for subjects module: Printsubjects-[subjectID]-[pagesmode].html
  // 11: General function for subjects module: Subjectsfuncs-[function].html
  // 12: Daily Archive functions: daily_archive-[function].html
  // 13: Daily Archive with year and month: daily_archive-[year]-[month].html
  // 14: Daily Archive with year, month and day: daily_archive-[year]-[month]-[day].html
  // 15: eNvolution News Extension in index.php: News-hometopicmore-[startrow].html
  // 16: eNvolution News Extension: News-topicmore-[topicID]-[startrow].html
  // 17: NS-User_Points module index: User_Points.html
  // 18: PostCalendar events: PostCalendar-event-[eventID]-[day]-[month]-[year].html
  // 19: PostCalendar monthly calendar view: PostCalendar-[day]-[month]-[year].html
  // 20: PostCalendar calendar view with template: PostCalendar-[day]-[month]-[year]-['day'|'week'|'month'|'year']-[template].html
  // 21: PostCalendar calendar view: PostCalendar-[day]-[month]-[year]-['day'|'week'|'month'|'year'].html
  // 22: PostCalendar general function, like 'search' or 'submit'
  // 23: Poll-[pollID].html
  // 24: Module (old style call) with a single req parameter: [module]+[parameter].html eg Downloads+MostPopular.html
  // 25: Module (old style call) default action: [module].html, eg News.html
  // 26: Module (new style call) default action: [module]-main.html
  // 27: Article Full Story: displayarticle[StoryID].html
  // 24: Article Full Story, comment mode specified: displayarticle[StoryID]-[mode].html
  // 29: Send an email about an article: sendarticle[storyID].html
  // 30: Section-X.html
  // 31: Sections-articleX-p[page].html
  // 32: Print an article: printarticle[StoryID].html
  // 33: List category: Category[CatID].html
  // 34: List stories for a topic: Category[CatID]-All.html
  // 35: List all sories on a topic: Topic[Topic]allstories.html
  // 36: FAQ-CategoryX-[Category]-ParentX-myfaq-[yes].html
  // 37: Content Express menu-only links
  // 38: Content Express content links
  // 39-41: Renders generic links for old-style modules
  // 42: New-style module calls with one parameter
  // 43: User informations: UserInfo-[user].html
  // 44: User registration: UserReg.html
  // 45: User registration with age check: UserReg-CheckAge.html
  // 46: User login: UserLogin.html
  // 47: User "find lost password": User-LostPassword.html
  // 48: Module (new style call eNvolution, based on rule 21) default action: [module]-main.html
  //
  // For PostCalendar 3, use this format in the $in array:
  // $prefix . 'index.php\?module=PostCalendar&(?:amp;)?func=view(?:&(?:amp;)?tplview=)?&(?:amp;)?viewtype=([^&]\w+)&(?:amp;)?Date=(\d{2})/(\d{2})/(\d{4})"|',
  // and the following in the corresponding position in the $out array:
  // '"PostCalendar3-$3-$2-$4-$1.html"|',
  // and add this to the .htaccess file:
  // RewriteRule ^PostCalendar3-([0-9]{2})-([0-9]{2})-([0-9]{4})-([a-zA-Z_]+)\.html$ index.php?module=PostCalendar&func=view&viewtype=$4&Date=$2/$1/$3 [L,NC]
  //
  // ITA
  // Se controlli tu il server, è preferibile inserire le regole di riscrittura dal file
  // .htaccess nel file di configurazione principale, httpd.conf, per aumentare la velocità.
  //
  // Legenda regole (l'ordine non corrisponde a quello in .htaccess)
  //
  //  1-5: Moduli con url del tipo index.php?name=MODULO e PNphpBB2 v1.1, v1.1a
  //  6: Posta Inviata dei Messaggi Privati: Messages-outbox.html
  //  7: Posta In Arrivo dei Messaggi Privati: Messages-inbox.html
  //  8: Ricerca per autore: Search-author-[utente].html
  //  9: Ricerca per argomento: Search-topics-[IDargomento].html
  // 10: Stampa pagine per il modulo subjects: Printsubjects-[IDcontenuto]-[modalitàpagine].html
  // 11: Funzione generale per il modulo subjects: Subjectsfuncs-[funzione].html
  // 12: Funzioni Diario News: daily_archive-[funzione].html
  // 13: Diario News con anno e mese: daily_archive-[anno]-[mese].html
  // 14: Diario News con anno, mese e giorno: daily_archive-[anno]-[mese]-[giorno].html
  // 15: Estensione News di eNvolution nell'index.php: News-hometopicmore-[filadipartenza].html
  // 16: Estensione News di eNvolution: News-topicmore-[IDargomento]-[filadipartenza].html
  // 17: Pagina principale modulo NS-User_Points: User_Points.html
  // 18: Eventi PostCalendar: PostCalendar-event-[IDevento]-[giorno]-[mese]-[anno].html
  // 19: Vista mensile calendario PostCalendar: PostCalendar-[giorno]-[mese]-[anno].html
  // 20: Vista calendario con template PostCalendar: PostCalendar-[giorno]-[mese]-[anno]-['giorno'|'settimana'|'mese'|'anno']-[template].html
  // 21: Vista calendario PostCalendar: PostCalendar-[giorno]-[mese]-[anno]-['giorno'|'settimana'|'mese'|'anno'].html
  // 22: Funzione generale di PostCalendar, come 'cerca' o 'invia'
  // 23: Polls-[IDsondaggio].html
  // 24: Modulo (chiamata vecchio stile) con un singolo parametro: [modulo]+[parametro].html es Downloads+MostPopular.html
  // 25: Azione predefinita Modulo (chiamata vecchio stile): [modulo].html, eg News.html
  // 26: Azione predefinita Modulo (chiamata nuovo stile): [modulo]-main.html
  // 27: Articolo leggi tutto: displayarticle[IDarticolo].html
  // 28: Articolo leggi tutto, modalità commento specificata: displayarticle[IDarticolo]-[modalità].html
  // 29: Invia un'E-Mail riguardo ad un'articolo: sendarticle[IDarticolo].html
  // 30: Section-X.html
  // 31: Sections-articleX-p[pagina].html
  // 32: Stampa un'articolo: printarticle[IDarticolo].html
  // 33: Lista categoria: Category[IDcategoria].html
  // 34: Lista articolo per un'argomento: Category[IDcategoria]-All.html
  // 35: Lista tutte le storie su un dato argomento: Topic[argomento]allstories.html
  // 36: FAQ-CategoryX-[categoria]-ParentX-myfaq-[si].html
  // 37: Link solo-menu di Content Express
  // 38: Link contenuti di Content Express
  // 39-41: Creano i link generali per i moduli vecchio stile
  // 42: Chiamata modulo nuovo stile con un singolo parametro
  // 43: Informazioni utente: UserInfo-[utente].html
  // 44: Registrazione utenti: UserReg.html
  // 45: Registrazione utenti con controllo età: UserReg-CheckAge.html
  // 46: Login utenti: UserLogin.html
  // 47: Utenti "ritrova password dimenticata": User-LostPassword.html
  // 48: Azione predefinita Modulo (chiamata nuovo stile eNvolution, si basa sulla 9): [modulo]-main.html
  //
  // Per PostCalendar 3, usa questo formato nell'array $in:
  // $prefix . 'index.php\?module=PostCalendar&(?:amp;)?func=view(?:&(?:amp;)?tplview=)?&(?:amp;)?viewtype=([^&]\w+)&(?:amp;)?Date=(\d{2})/(\d{2})/(\d{4})"|',
  // ed il seguente nella posizione corrispondente dell'array $out:
  // '"PostCalendar3-$3-$2-$4-$1.html"|',
  // ed aggiungi questo al file .htaccess:
  // RewriteRule ^PostCalendar3-([0-9]{2})-([0-9]{2})-([0-9]{4})-([a-zA-Z_]+)\.html$ index.php?module=PostCalendar&func=view&viewtype=$4&Date=$2/$1/$3 [L,NC]
  //

    // get the sites base url
  $base_url =  pnGetBaseURL();
  $prefix = '|"(?:'.$base_url.')?';

    // determine the extension the site admin wants for site urls
  $extension = pnModGetVar('Xanthia', 'shorturlsextension');

  // ENG
  // (?i) means case insensitive; \w='word' character; \d=digit; (amp;)? means optional; (?:catid=)? means optional and won't capture string for backreferences
  // ITA
  // (?i) significa insensibile al caso; \w='parola' carattere; \d=numero; (amp;)? significa opzionale; (?:catid=)? significa opzionale e non catturerà la stringa per referenze precedenti

  $in = array(
  $prefix . 'index.php\?newlang=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?theme=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php"|',
  $prefix . 'user.php"|',
  $prefix . 'index.php\?module=ppnews&(?:amp;)?choix=index"|',
  $prefix . 'index.php\?module=ppnews&(?:amp;)?choix=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?module=My_eGallery&(?:amp;)?do=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?module=My_eGallery&(?:amp;)?do=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?module=My_eGallery&(?:amp;)?do=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?module=My_eGallery&(?:amp;)?do=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)"|',
  $prefix . 'modules.php\?op=modload&(?:amp;)?name=NS-User_Points&(?:amp;)?file=index"|',
  $prefix . 'modules.php\?op=modload&(?:amp;)?name=NS-Link_To_Us&(?:amp;)?file=index"|',
  $prefix . 'index.php\?name=Polls(?:&(?:amp;)?req=(\w*))?&(?:amp;)?pollID=(\d{1,2})?"|',
  $prefix . 'index.php\?name=Messages&(?:amp;)?file=outbox"|',
  $prefix . 'index.php\?name=Messages&(?:amp;)?file=index"|',
  $prefix . 'index.php\?name=Search&(?:amp;)?action=search&(?:amp;)?overview=1&(?:amp;)?active_stories=1&(?:amp;)?stories_author=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?name=Search&(?:amp;)?action=search&(?:amp;)?overview=1&(?:amp;)?active_stories=1&(?:amp;)?stories_topics\[0\]=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?name=Search&(?:amp;)?action=search&(?:amp;)?active_stories=1&(?:amp;)?stories_author=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?name=Search&(?:amp;)?action=search&(?:amp;)?active_stories=1&(?:amp;)?stories_topics\[0\]=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?module=daily_archive&(?:amp;)?func=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?module=daily_archive&(?:amp;)?func=display&(?:amp;)?req=get&(?:amp;)?year=([\w\d\.\:\_\/]+)&(?:amp;)?month=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?module=daily_archive&(?:amp;)?func=display&(?:amp;)?req=get&(?:amp;)?year=([\w\d\.\:\_\/]+)&(?:amp;)?month=([\w\d\.\:\_\/]+)&(?:amp;)?day=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?module=daily_archive&(?:amp;)?func=display&(?:amp;)?req=get&(?:amp;)?year=([\w\d\.\:\_\/]+)&(?:amp;)?month=([\w\d\.\:\_\/]+)&(?:amp;)?day=([\w\d\.\:\_\/]+)&(?:amp;)?page=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?name=News&(?:amp;)?startrow=(\d{1,2})"|',
  $prefix . 'index.php\?name=News&(?:amp;)?topic=(\d{1,2})&(?:amp;)?startrow=(\d{1,2})"|',
  $prefix . 'index.php\?module=PostCalendar&(?:amp;)?func=view&(?:amp;)?Date=(\d{4})(\d{2})(\d{2})(?:&(?:amp;)?tplview=)?&(?:amp;)?viewtype=details&(?:amp;)?eid=(\d+)&(?:amp;)?print="|',
  $prefix . 'index.php\?module=PostCalendar&(?:amp;)?func=view&(?:amp;)?tplview=default&(?:amp;)?viewtype=month&(?:amp;)?Date=(\d{4})(\d{2})(\d{2})"|',
  $prefix . 'index.php\?module=PostCalendar&(?:amp;)?func=view&(?:amp;)?tplview=(\w+)&(?:amp;)?viewtype=(\w+)&(?:amp;)?Date=(\d{4})(\d{2})(\d{2})(?:&(?:amp;)?pc_username=)?(?:&(?:amp;)?pc_category=)?(?:&(?:amp;)?pc_topic=)?(?:&(?:amp;)?print=)?"|',
  $prefix . 'index.php\?module=PostCalendar&(?:amp;)?func=view(?:&(?:amp;)?tplview=)?&(?:amp;)?viewtype=(\w+)&(?:amp;)?Date=(\d{4})(\d{2})(\d{2})(?:&(?:amp;)?pc_username=)?(?:&(?:amp;)?pc_category=)?(?:&(?:amp;)?pc_topic=)?(?:&(?:amp;)?print=)?"|',
  $prefix . 'index.php\?module=PostCalendar&(?:amp;)?func=(\w+[^&])"|',
  $prefix . 'index.php\?name=News&(?:amp;)?file=article&(?:amp;)?sid=(\d+)"|',
  $prefix . 'index.php\?name=Recommend_Us&(?:amp;)?req=FriendSend&(?:amp;)?sid=(\d+)"|',
  $prefix . 'index.php\?name=Sections&(?:amp;)?req=listarticles&(?:amp;)?secid=(\d+)"|',
  $prefix . 'index.php\?name=Sections&(?:amp;)?req=viewarticle&(?:amp;)?artid=(\d+)(?:&(?:amp;)?page=(\d*))?"|',
  $prefix . 'print.php\?sid=(\d+)"|',
  $prefix . 'index.php\?(?:&(?:amp;)?)?catid=(\d+)"|',
  $prefix . 'index.php\?name=News&(?:amp;)?catid=(\d{1,2})(?:&(?:amp;)?topic=(\d{0,3}))?(?:&(?:amp;)?allstories=1)?"|',
  $prefix . 'index.php\?name=News&(?:amp;)?catid=?&(?:amp;)?topic=(\d{1,3})(?:&(?:amp;)?(all)stories=1)?"|',
  $prefix . 'index.php\?name=FAQ&(?:amp;)?myfaq=(\w+)&(?:amp;)?id_cat=(\d+)&(?:amp;)?categories=([^&-]+)&(?:amp;)?parent_id=(\d+)"|',
  $prefix . 'index.php\?name=PNphpBB2"|',
  $prefix . 'index.php\?name=PNphpBB2&(?:amp;)?file=index"|',
  $prefix . 'index.php\?name=PNphpBB2&(?:amp;)?file=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?name=PNphpBB2&(?:amp;)?file=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?name=PNphpBB2&(?:amp;)?file=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?name=PNphpBB2&(?:amp;)?file=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?name=PNphpBB2&(?:amp;)?file=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)"|',
  $prefix . 'user.php\?op=userinfo&(?:amp;)?uname=([\w\d\.\:\_\/]+)"|',
  $prefix . 'user.php\?op=register&(?:amp;)?module=NewUser"|',
  $prefix . 'user.php\?op=check_age&(?:amp;)?module=NewUser"|',
  $prefix . 'user.php\?op=loginscreen&(?:amp;)?module=User"|',
  $prefix . 'user.php\?op=lostpassscreen&(?:amp;)?module=LostPassword"|',
  $prefix . 'user.php\?module=User&(?:amp;)?op=logout"|',
  $prefix . 'index.php\?name=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?name=([\w\d\.\:\_\/]+)&(?:amp;)?file=index"|',
  $prefix . 'index.php\?name=([\w\d\.\:\_\/]+)&(?:amp;)?file=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?name=([\w\d\.\:\_\/]+)&(?:amp;)?file=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?name=([\w\d\.\:\_\/]+)&(?:amp;)?file=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?name=([\w\d\.\:\_\/]+)&(?:amp;)?file=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?name=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?name=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?name=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?name=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?name=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?module=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?module=([\w\d\.\:\_\/]+)[#]([\w\d]*)"|',
  $prefix . 'index.php\?module=([\w\d\.\:\_\/]+)&(?:amp;)?func=main"|',
  $prefix . 'index.php\?module=([\w\d\.\:\_\/]+)&(?:amp;)?func=main[#]([\w\d]*)"|',
  $prefix . 'index.php\?module=([\w\d\.\:\_\/]+)&(?:amp;)?func=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?module=([\w\d\.\:\_\/]+)&(?:amp;)?func=([\w\d\.\:\_\/]+)[#]([\w\d]*)"|',
  $prefix . 'index.php\?module=([\w\d\.\:\_\/]+)&(?:amp;)?func=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?module=([\w\d\.\:\_\/]+)&(?:amp;)?func=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)[#]([\w\d]*)"|',
  $prefix . 'index.php\?module=([\w\d\.\:\_\/]+)&(?:amp;)?func=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?module=([\w\d\.\:\_\/]+)&(?:amp;)?func=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)[#]([\w\d]*)"|',
  $prefix . 'index.php\?module=([\w\d\.\:\_\/]+)&(?:amp;)?func=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)"|',
  $prefix . 'index.php\?module=([\w\d\.\:\_\/]+)&(?:amp;)?func=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)"[#]([\w\d]*)|',
  $prefix . 'modules.php\?op=modload&(?:amp;)?name=([\w\d\.\:\_\/]+)&(?:amp;)?file=index&(?:amp;)?req=([\w\d\.\:\_\/]+)"|',
  $prefix . 'modules.php\?op=modload&(?:amp;)?name=([\w\d\.\:\_\/]+)&(?:amp;)?file=index"|',
  $prefix . 'modules.php\?op=modload&(?:amp;)?name=([\w\d\.\:\_\/]+)&(?:amp;)?file=([\w\d\.\:\_\/]+)"|',
  $prefix . 'modules.php\?op=modload&(?:amp;)?name=([\w\d\.\:\_\/]+)&(?:amp;)?file=([\w\d\.\:\_\/]+)[#]([\w\d]*)"|',
  $prefix . 'modules.php\?op=modload&(?:amp;)?name=([\w\d\.\:\_\/]+)&(?:amp;)?file=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)"|',
  $prefix . 'modules.php\?op=modload&(?:amp;)?name=([\w\d\.\:\_\/]+)&(?:amp;)?file=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)[#]([\w\d]*)"|',
  $prefix . 'modules.php\?op=modload&(?:amp;)?name=([\w\d\.\:\_\/]+)&(?:amp;)?file=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)"|',
  $prefix . 'modules.php\?op=modload&(?:amp;)?name=([\w\d\.\:\_\/]+)&(?:amp;)?file=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)[#]([\w\d]*)"|',
  $prefix . 'modules.php\?op=modload&(?:amp;)?name=([\w\d\.\:\_\/]+)&(?:amp;)?file=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)"|',
  $prefix . 'modules.php\?op=modload&(?:amp;)?name=([\w\d\.\:\_\/]+)&(?:amp;)?file=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)&(?:amp;)?([\w\d\.\:\_\/]+)=([\w\d\.\:\_\/]+)[#]([\w\d]*)"|'
  );

  //$base_url = '';
  $out = array(
  '"'.$base_url.'changelang-$1.'.$extension.'"',
  '"'.$base_url.'changetheme-$1.'.$extension.'"',
  '"'.$base_url.'index.'.$extension.'"',
  '"'.$base_url.'user.'.$extension.'"',
  '"'.$base_url.'ppnews-index.'.$extension.'"',
  '"'.$base_url.'ppnews-$1.'.$extension.'"',
  '"'.$base_url.'MeG-$1.'.$extension.'"',
  '"'.$base_url.'MeG-$1-$2-$3.'.$extension.'"',
  '"'.$base_url.'MeG-$1-$2-$3-$4-$5.'.$extension.'"',
  '"'.$base_url.'MeG-$1-$2-$3-$4-$5-$6-$7.'.$extension.'"',
  '"'.$base_url.'User_Points.'.$extension.'"',
  '"'.$base_url.'Link_To_Us.'.$extension.'"',
  '"'.$base_url.'Poll-$2$1.'.$extension.'"',
  '"'.$base_url.'Messages-outbox.'.$extension.'"',
  '"'.$base_url.'Messages-inbox.'.$extension.'"',
  '"'.$base_url.'Search-author-$1.'.$extension.'"',
  '"'.$base_url.'Search-topics-$1.'.$extension.'"',
  '"'.$base_url.'Search-author-$1-no.'.$extension.'"',
  '"'.$base_url.'Search-topics-$1-no.'.$extension.'"',
  '"'.$base_url.'daily_archive-$1.'.$extension.'"',
  '"'.$base_url.'daily_archive-$1-$2.'.$extension.'"',
  '"'.$base_url.'daily_archive-$1-$2-$3.'.$extension.'"',
  '"'.$base_url.'daily_archive-$1-$2-$3-$4.'.$extension.'"',
  '"'.$base_url.'News-hometopicmore-$1.'.$extension.'"',
  '"'.$base_url.'News-topicmore-$1-$2.'.$extension.'"',
  '"'.$base_url.'PostCalendar-$3-$2-$1-event-$4.'.$extension.'"',
  '"'.$base_url.'PostCalendar-$3-$2-$1.'.$extension.'"',
  '"'.$base_url.'PostCalendar-$5-$4-$3-$2-$1.'.$extension.'"',
  '"'.$base_url.'PostCalendar-$4-$3-$2-$1.'.$extension.'"',
  '"'.$base_url.'PostCalendar-$1.'.$extension.'"',
  '"'.$base_url.'Article$1.'.$extension.'"',
  '"'.$base_url.'SendArticle$1.'.$extension.'"',
  '"'.$base_url.'Section-$1.'.$extension.'"',
  '"'.$base_url.'Sections-article$1-p$2.'.$extension.'"',
  '"'.$base_url.'PrintArticle$1.'.$extension.'"',
  '"'.$base_url.'Category$1.'.$extension.'"',
  '"'.$base_url.'Category$1-All.'.$extension.'"',
  '"'.$base_url.'Topic$1$2.'.$extension.'"',
  '"'.$base_url.'FAQ-Category$2-$3-Parent$4-myfaq-$1.'.$extension.'"',
  '"'.$base_url.'PNphpBB2.'.$extension.'"',
  '"'.$base_url.'PNphpBB2.'.$extension.'"',
  '"'.$base_url.'PNphpBB2-$1.'.$extension.'"',
  '"'.$base_url.'PNphpBB2-$1-$2-$3.'.$extension.'"',
  '"'.$base_url.'PNphpBB2-$1-$2-$3-$4-$5.'.$extension.'"',
  '"'.$base_url.'PNphpBB2-$1-$2-$3-$4-$5-$6-$7.'.$extension.'"',
  '"'.$base_url.'PNphpBB2-$1-$2-$3-$4-$5-$6-$7-$8-$9.'.$extension.'"',
  '"'.$base_url.'UserInfo-$1.'.$extension.'"',
  '"'.$base_url.'UserReg.'.$extension.'"',
  '"'.$base_url.'UserReg-CheckAge.'.$extension.'"',
  '"'.$base_url.'User-Login.'.$extension.'"',
  '"'.$base_url.'User-LostPassword.'.$extension.'"',
  '"'.$base_url.'User-Logout.'.$extension.'"',
  '"'.$base_url.'$1.'.$extension.'"',
  '"'.$base_url.'$1.'.$extension.'"',
  '"'.$base_url.'$1-$2.'.$extension.'"',
  '"'.$base_url.'$1-$2-$3-$4.'.$extension.'"',
  '"'.$base_url.'$1-$2-$3-$4-$5-$6.'.$extension.'"',
  '"'.$base_url.'$1-$2-$3-$4-$5-$6-$7-$8.'.$extension.'"',
  '"'.$base_url.'$1.'.$extension.'"',
  '"'.$base_url.'$1-$2-$3.'.$extension.'"',
  '"'.$base_url.'$1-$2-$3-$4-$5.'.$extension.'"',
  '"'.$base_url.'$1-$2-$3-$4-$5-$6-$7.'.$extension.'"',
  '"'.$base_url.'$1-$2-$3-$4-$5-$6-$7-$8-$9.'.$extension.'"',
  '"'.$base_url.'module-$1.'.$extension.'"',
  '"'.$base_url.'module-$1.'.$extension.'#$2"',
  '"'.$base_url.'module-$1.'.$extension.'"',
  '"'.$base_url.'module-$1.'.$extension.'#$2"',
  '"'.$base_url.'module-$1-$2.'.$extension.'"',
  '"'.$base_url.'module-$1-$2.'.$extension.'#$3"',
  '"'.$base_url.'module-$1-$2-$3-$4.'.$extension.'"',
  '"'.$base_url.'module-$1-$2-$3-$4.'.$extension.'#$5"',
  '"'.$base_url.'module-$1-$2-$3-$4-$5-$6.'.$extension.'"',
  '"'.$base_url.'module-$1-$2-$3-$4-$5-$6.'.$extension.'#$7"',
  '"'.$base_url.'module-$1-$2-$3-$4-$5-$6-$7-$8.'.$extension.'"',
  '"'.$base_url.'module-$1-$2-$3-$4-$5-$6-$7-$8.'.$extension.'#$9"',
  '"'.$base_url.'$1+$2.'.$extension.'"',
  '"'.$base_url.'$1.'.$extension.'"',
  '"'.$base_url.'$1-$2.'.$extension.'"',
  '"'.$base_url.'$1-$2.'.$extension.'#$3"',
  '"'.$base_url.'$1-$2-$3-$4.'.$extension.'"',
  '"'.$base_url.'$1-$2-$3-$4.'.$extension.'#$5"',
  '"'.$base_url.'$1-$2-$3-$4-$5-$6.'.$extension.'"',
  '"'.$base_url.'$1-$2-$3-$4-$5-$6.'.$extension.'#$7"',
  '"'.$base_url.'$1-$2-$3-$4-$5-$6-$7-$8.'.$extension.'"',
  '"'.$base_url.'$1-$2-$3-$4-$5-$6-$7-$8.'.$extension.'#$9"'
  );

  $source = preg_replace($in, $out, $source);

  // Debugging / Ricerca errori
  // echo"<div align=\"left\"><pre>"; print_r($in); echo "</pre></div>";
  // echo"<div align=\"left\"><pre>"; print_r($out); echo "</pre></div>";
  // ENG
  // Debugging code to save what is generated, in case the output is mangled
  // so horribly that the browser won't display it.
  // Make sure that the path and file specified in the fopen() exist and is writeable.
  // ITA
  // Codice ricerca errori per salvare ciò che viene generato, nel caso il risultato fosse
  // così orribilmente sbagliato che il browser non lo visualizzi.
  // Assicurati che il percorso specificato in fopen() esista e sia scrivibile.
  //
  // $fp=fopen("/var/www/html/themes/$GLOBALS[thename]/debug.log", "a");
  // fwrite($fp, $s);
  // fclose($fp);
  // $s .= "<!-- simplify_urls -->";

  return $source;
}

?>