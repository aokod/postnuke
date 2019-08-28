<?php
// File: $Id: global.php 20451 2006-11-08 17:26:58Z larsneo $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001-2003 by the PostNuke Development Team.
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
// Original Author of file: Translation team
// Purpose of file: Translation files
// Translation team: Read credits in /docs/CREDITS.txt
// Traducido por dev-postnuke Team - -http://www.dev-postnuke.com
// ----------------------------------------------------------------------

/* pn0.76 */

define('_REGISTER_GLOBALS_ON', 'register_global is on - you should turn this off for enhanced security, for more information click <a href="http://php.net/manual/en/security.globals.php" title="more about register_globals">here</a>');
define('_REGISTER_GLOBALS_ON_HINT', 'Important note: PostNuke itself does not need register_globals=on, but some old modules might need this in order to work. You might consider to not use such modules.');

define('_CHANGE_INFO_0','Información Erronea');
define('_MODULE', 'Módulo');
define('_ERROR', 'Error:');
define('_INSTALLED', 'Se instaló con éxito');
define('_NOTINIT', 'no se inicializó');
define('_NOTACTIVATED', 'no se activa');
define('_NOTLOCALIZED', 'no lo localiza');
define('_NOTCATEGORISED', 'no lo categoriza');
define('_MODULES', 'Módulos');


define('_PNDOCSLINKURL', 'http://www.docs.dev-postnuke.com');
define('_PNDOCSLINKTEXT', 'Documentación sobre PostNuke en español ');
define('_PNDOCSLINKTITLE', 'Aquí enlazas a documentación en español de PostNuke');
define('_PNSUPPORTLINKURL', 'http://www.dev-postnuke.com/dpForum.html');
define('_PNSUPPORTLINKTEXT', 'Foros de ayuda en español');
define('_PNSUPPORTLINKTITLE', 'Aquí enlazas a los foros de ayuda en lengua española');

define('_INSTALLGUIDEREF1', 'Por favor, considera leer esta');
define('_INSTALLGUIDEREF2', 'guía de instalación');
define('_INSTALLGUIDEREF3', 'antes de continuar con el proceso de instalación');
define('_INSTALL_ANONYMOUS','Invitado');
define('_INSTALL_BACKENDLANG','es-es');
define('_INSTALL_CENSORLIST','fuck,cunt,fucker,fucking,pussy,cock,c0ck,cum,twat,clit,bitch,fuk,fuking,motherfucker');
define('_INSTALL_ILLEGALNAMES','root adm linux webmaster admin god administrator administrador nobody anonymous anonimo');
define('_INSTALL_METAKEYWORDS','nuke, postnuke, free, community, php, portal, opensource, open source, gpl, mysql, sql, database, web site, website, weblog, content management, contentmanagement, web content management, webcontentmanagement');
define('_INSTALL_NOTIFYFRM','webmaster');
define('_INSTALL_NOTIFYMAIL','webmaster@mi-dominio.com');
define('_INSTALL_NOTIFYMSG','¡Hola! Tienes un nuevo envío en tu sitio web');
define('_INSTALL_NOTIFYSBJ','Noticias desde mi sitio');
define('_INSTALL_PNPOWERED','Esta página funciona con PostNuke');
define('_INSTALL_REASONS','Tal y como está,Offtopic,Flamebait,Troll,Redundante,Profundo,Interesante,Informativo,Divertido,Overrated,Subestimado');
define('_INSTALL_REGDISABLED','Lo sentimos, pero el sistema de registro no está actualmente disponible.');
define('_INSTALL_YOURSITENAME','Tu sitio PostNuke');
define('_INSTALL_YOURSLOGAN','Bienvenido al nuevo sitio PostNuke');
/* Modules Descriptions and names */
define('_MODNAME_ADDSTORY','Enviar_Noticias');
define('_MODDESC_ADDSTORY','Enviar una nueva historia');
define('_MODNAME_ADMIN','Administracion');
define('_MODDESC_ADMIN','Administración');
define('_MODNAME_ADMMESSAGES','Mensajes_Adm.');
define('_MODDESC_ADMMESSAGES','Muestra los mensajes programados por el Administrador');
define('_MODNAME_AUTOLINKS','Autoenlaces');
define('_MODDESC_AUTOLINKS','Palabras claves enlazadas');
define('_MODNAME_AVANTGO','AvantGo');
define('_MODDESC_AVANTGO','Navegación con PDAs');
define('_MODNAME_BANNERS','Anuncios');
define('_MODDESC_BANNERS','Administración de Anuncios publicitarios');
define('_MODNAME_BLOCKS','Bloques');
define('_MODDESC_BLOCKS','Administración de los bloques');
define('_MODNAME_CENSOR','Censor');
define('_MODDESC_CENSOR','Administración del sistema de censura');
define('_MODNAME_COMMENTS','Comentarios');
define('_MODDESC_COMMENTS','Administración de los comentarios');
define('_MODNAME_CREDITS','Creditos');
define('_MODDESC_CREDITS','Muestra los créditos de los módulos, licencias, ayuda e información de contacto.');
define('_MODNAME_DOWNLOADS','Descargas');
define('_MODDESC_DOWNLOADS','Administración de descargas');
define('_MODNAME_EPHEM','Efemerides');
define('_MODDESC_EPHEM','¿Qué sucedió un día como hoy?'); // ¿Qué sucedió la fecha de hoy?
define('_MODNAME_FAQ','FAQ');
define('_MODDESC_FAQ','Preguntas más frecuentes (FAQ)');
define('_MODNAME_GROUPS','Grupos');
define('_MODDESC_GROUPS','Administración de grupos');
define('_MODNAME_HEADFOOT','Cabecera_y_pie_de_página');
define('_MODDESC_HEADFOOT','Cabeza y pie de página de tu sitio PostNuke');
define('_MODNAME_LANGUAGES','Idiomas');
define('_MODDESC_LANGUAGES','Administración de los idiomas');
define('_MODNAME_LEGAL','Avisos_Legales');
define('_MODDESC_LEGAL','Política de privacidad y Términos de Uso');
define('_MODNAME_LOSTPASS','Claves_olvidadas');
define('_MODDESC_LOSTPASS','Petición y recuperación de contraseñas olvidadas');
define('_MODNAME_MAILER','Correo');
define('_MODDESC_MAILER','Sistema de Correo de PostNuke');
define('_MODNAME_MAILUSERS','Correo_Usuarios.');
define('_MODDESC_MAILUSERS','Envio de correo a los usuarios de este sitio');
define('_MODNAME_MBLIST','Lista_de_Miembros');
define('_MODDESC_MBLIST','Información de los usuarios de este sitio');
define('_MODNAME_MESSAGES','Mensajes_Privados');
define('_MODDESC_MESSAGES','Mensajes privados a los usuarios');
define('_MODNAME_MODULES','Modulos');
define('_MODDESC_MODULES','Administración de modulos (Activar/desactivar módulos, instalación/docs/creditos)');
define('_MODNAME_MULTISITES','Multisites');
define('_MODDESC_MULTISITES','Varias páginas Web con una única instalación de PostNuke');
define('_MODNAME_NEWS','Noticias');
define('_MODDESC_NEWS','Administración de Noticias');
define('_MODNAME_NEWUSER','Nuevo_Usuario');
define('_MODDESC_NEWUSER','Administración del registro de Nuevos Usuarios');
define('_MODNAME_PERMISSIONS','Permisos');
define('_MODDESC_PERMISSIONS','Configuración del sistema de Permisos');
define('_MODNAME_PNRENDER','pnRender');
define('_MODDESC_PNRENDER','Implementación del sistema de plantillas Smarty para PostNuke');
define('_MODNAME_POLLS','Encuestas');
define('_MODDESC_POLLS','Administración de las Encuestas');
define('_MODNAME_QUOTES','Citas');
define('_MODDESC_QUOTES','Citas y proverbios');
define('_MODNAME_RATINGS','Puntuaciones');
define('_MODDESC_RATINGS','Utilidad para realizar puntuaciones');
define('_MODNAME_RECOMMENDUS','Recomiendanos');
define('_MODDESC_RECOMMENDUS','Recomienda esta página a un amigo');
define('_MODNAME_REFERERS','Referencias');
define('_MODDESC_REFERERS','¿Quién nos están enlazando?');
define('_MODNAME_REVIEWS','Analisis');
define('_MODDESC_REVIEWS','Análisis');
define('_MODNAME_SEARCH','Buscar');
define('_MODDESC_SEARCH','Buscar en este sitio');
define('_MODNAME_SECTIONS','Secciones');
define('_MODDESC_SECTIONS','Secciones');
define('_MODNAME_SETTINGS','Configuracion');
define('_MODDESC_SETTINGS','Configuración y Ajustes generales del sitio PostNuke');
define('_MODNAME_STATS','Estadisticas');
define('_MODDESC_STATS','Estadísticas de este sitio Web');
define('_MODNAME_SUBMITNEWS','Enviar_Noticias');
define('_MODDESC_SUBMITNEWS','Envía una Noticia a este sitio');
define('_MODNAME_TOPICS','Temas');
define('_MODDESC_TOPICS','Administración de temas para noticias');
define('_MODNAME_TOPLIST','Top_10');
define('_MODDESC_TOPLIST','Lista Top 10');
define('_MODNAME_TYPETOOL','TypeTool');
define('_MODDESC_TYPETOOL','TypeTool Editor visual');
define('_MODNAME_USER','Usuarios');
define('_MODDESC_USER','Administración de usuarios');
define('_MODNAME_WEBLINKS','Enlaces_Web');
define('_MODDESC_WEBLINKS','Enlaces a otros sitios Web');
define('_MODNAME_WIKI','Wiki');
define('_MODDESC_WIKI','Soporte Wiki');
define('_MODNAME_XMLRPC','XML-RPC');
define('_MODDESC_XMLRPC','Módulo auxiliar XML-RPC');
define('_MODNAME_YOURACCOUNT','Tu_Cuenta');
define('_MODDESC_YOURACCOUNT','Configuración y opciones de la cuenta de usuarios');
define('_MODNAME_XANTHIA','Xanthia');
define('_MODDESC_XANTHIA','Motor para Temas Xanthia');
define('_MODNAME_PNBBSMILE','pn_bbsmile');
define('_MODDESC_PNBBSMILE','Smilie Hook');
define('_MODNAME_RSS','RSS');
define('_MODDESC_RSS','Integración de contenidos de páginas externas como noticias por RSS ');

define("_INSTALL_REMINDERBLOCK","Por favor, no olvides eliminar el archivo <strong>install.php</strong> y la carpeta <strong>install</strong> del directorio raíz de PostNuke al termino de la instalación. <br /> Sin su eliminación previa, no te será permitido administrar la Web, aparte de suponer un riesgo por el que alguna persona mal intencionada puede acceder a tu base de datos.<br /><br /><em>Nota: Se puede editar este bloque desde la administración de bloques</em>");
define('_MESSAGE_00_a', 'PostNuke 0.764 Platinum');
define("_MESSAGE_00_b","PostNuke es un sistema de Gestión de Contenidos (CMS)  que separa el contenido del diseño y la tecnología. Además, el contenido de la página web creada (por ejemplo, contribuciones, enlaces, descargas, FAQs, galerías de imágenes, foros etc.) puede ser administrado directamente vía navegador Web. Por sus prestaciones, su sistema de gestión de contenidos y su organización, PostNuke ayuda a reducir el tiempo y el coste en la construcción de una página Web.<br /><br />PostNuke está basado en una construcción modular - las funciones centrales (la dirección del usuario, el sistema de autorización, API) son asumidas desde el panel de administración, además, el funcionamiento completo puede ser adaptado individualmente y puede ser ampliado arbitrariamente<br /><br /><strong>El paquete de idioma español, así como módulos, bloques, temas gráficos, ayuda o documentación sobre Postnuke, puede encontrarlo en <a href=\"http://www.dev-postnuke.com/\">Dev-PostNuke.com</a>.</strong><br /><br /><em>Nota: Se puede editar este mensaje desde  la administración / mensajes del administrador.</em>");
define('_MESSAGE_00_d','0');
define('_MESSAGE_00_e','1');
define('_MESSAGE_00_f','1');
define('_MESSAGE_00_g','');

define('_FOOTMSGTEXT','<br /><a href="http://www.postnuke.com"><img src="images/powered/postnuke.butn.gif" alt="Sitio Web impulsado por PostNuke" /></a> <a href="http://php.weblogs.com/ADODB"><img src="images/powered/adodb2.gif" alt="ADODB biblioteca base de datos" /></a> <a href="http://www.php.net"><img src="images/powered/php4_powered.gif" alt="Lenguaje PHP" /></a><br /><br />Todos los logos y marcas registradas en este sitio son propiedad de sus respectivos dueños. Los comentarios son propiedad de sus autores, el resto es de este sitio Web (c) 2004,<br />que fue creado con <a href="http://www.postnuke.com">PostNuke</a>, un sistema portal Web escrito en PHP. PostNuke es Software Libre liberado bajo la licencia <a href="http://www.gnu.org">GNU/GPL</a>.<br /><br />Usted puede sindicar nuestras noticias usando el archivo <a href="backend.php">backend.php</a>');

define('_BLOCKTITLE_INCOMING','En Espera');
define('_BLOCKTITLE_WHOISONLINE','En línea');
define('_BLOCKTITLE_OTHERSTORIES','Otras historias');
define('_BLOCKTITLE_USERSBLOCK','Bloque del usuario');
define('_BLOCKTITLE_SEARCHBOX','Buscar');
define('_BLOCKTITLE_EPHEMERIDS','Efemérides');
define('_BLOCKTITLE_LANGUAGES','Idiomas');
define('_BLOCKTITLE_CATMENU','Categorías');
define('_BLOCKTITLE_RANHEAD','Titulares Aleatorios');
define('_BLOCKTITLE_POLL','Encuestas');
define('_BLOCKTITLE_BIGSTORY','Noticia más leida');
define('_BLOCKTITLE_USERSLOGIN','Entrar en Tu Cuenta');
define('_BLOCKTITLE_PASTART','Noticias Antiguas');
define('_BLOCKTITLE_ADMINMESS','Mensaje del Administrador');
define('_BLOCKTITLE_REMINDER','Recuerda');
define('_BLOCKTITLE_USERSBLOCK_TEXTE','Escribe lo que quieras aquí');

define('_POLLDESCTEXT','¿Qué opinas de Postnuke?');
define('_POLLDATATEXT1','¿Qué es PostNuke?');
define('_POLLDATATEXT2','¡es justo lo que necesito!');
define('_POLLDATATEXT3','¡hace tempo que lo uso!');

define('_REWIEWSMAINTITLE','Análisis');
define('_REWIEWSMAINDESC','Descripción de los análisis');

define('_BLOCKTITLE_MAINMENU','Menú principal');
define('_BLOCKTITLE_MAINMENU_ADMIN','Administración');
define('_BLOCKTITLE_MAINMENU_ADMINALT','Administración del sitio Web');
define('_BLOCKTITLE_MAINMENU_AVANTGO','AvantGo');
define('_BLOCKTITLE_MAINMENU_AVANTGOALT','Navegación por el sitio con PDA');
define('_BLOCKTITLE_MAINMENU_DL','Descargas');
define('_BLOCKTITLE_MAINMENU_DLALT','Listado de Descargas de este sitio Web...');
define('_BLOCKTITLE_MAINMENU_FAQ','FAQ');
define('_BLOCKTITLE_MAINMENU_FAQALT','Preguntas más frecuentes ');
define('_BLOCKTITLE_MAINMENU_HOME','Inicio');
define('_BLOCKTITLE_MAINMENU_HOMEALT','enlace a la página de inicio');
define('_BLOCKTITLE_MAINMENU_MLIST','Lista de miembros');
define('_BLOCKTITLE_MAINMENU_MLISTALT','Lista de usuarios de este sitio Web');
define('_BLOCKTITLE_MAINMENU_NEWS','Noticias');
define('_BLOCKTITLE_MAINMENU_NEWSALT','Noticias de este sitio Web');
define('_BLOCKTITLE_MAINMENU_RUS','Recomiéndanos');
define('_BLOCKTITLE_MAINMENU_RUSALT','Recomienda este sitio Web a un amigo');
define('_BLOCKTITLE_MAINMENU_RWS','Análisis');
define('_BLOCKTITLE_MAINMENU_RWSALT','Análisis del Web');
define('_BLOCKTITLE_MAINMENU_SEARCH','Buscar');
define('_BLOCKTITLE_MAINMENU_SEARCHALT','Buscar en este sitio Web');
define('_BLOCKTITLE_MAINMENU_SECTIONS','Secciones');
define('_BLOCKTITLE_MAINMENU_SECTIONSALT','Secciones del sitio Web');
define('_BLOCKTITLE_MAINMENU_SNEWS','Enviar noticias');
define('_BLOCKTITLE_MAINMENU_SNEWSALT','Enviar noticias para su publicación');
define('_BLOCKTITLE_MAINMENU_STATS','Estadísticas');
define('_BLOCKTITLE_MAINMENU_STATSALT','Estadísticas del sitio Web');
define('_BLOCKTITLE_MAINMENU_TLIST','Lista Top 10');
define('_BLOCKTITLE_MAINMENU_TLISTALT','Lista Top 10 del sitio Web');
define('_BLOCKTITLE_MAINMENU_TOPICS','Temas');
define('_BLOCKTITLE_MAINMENU_TOPICSALT','Lista de Temas de este portal');
define('_BLOCKTITLE_MAINMENU_USER','Mi Cuenta');
define('_BLOCKTITLE_MAINMENU_USERALT','Administra tu cuenta de usuario');
define('_BLOCKTITLE_MAINMENU_USEREXIT','Salir');
define('_BLOCKTITLE_MAINMENU_USEREXITALT','Salir de Tu Cuenta...');
define('_BLOCKTITLE_MAINMENU_WLINKS','Enlaces Web');
define('_BLOCKTITLE_MAINMENU_WLINKSALT','Enlaces a otros sitios Web');


define("_ADMIN_EMAIL","Dirección e-Mail del Administrador");
define("_ADMIN_LOGIN","Nombre de usuario del administrador (Login para la conexión)");
define("_ADMIN_NAME","Nombre del Administrador");
define("_ADMIN_PASS","Contraseña del Administrador");
define("_ADMIN_REPEATPASS","Contraseña del administrador (de nuevo)");
define("_ADMIN_URL","Sitio Web del Administrador");
define('_BTN_CHANGEINFO','Cambiar la información');
define("_BTN_CONTINUE","Continuar");
define("_BTN_FINISH","Finalizado");
define("_BTN_NEXT","Siguiente");
define('_BTN_NEWINSTALL','Nueva instalación');
define("_BTN_RECHECK","volver a verificar");
define("_BTN_SET_LANGUAGE","Configurar Idioma");
define("_BTN_SET_LOGIN","Identificación");
define("_BTN_START","Comenzar");
define("_BTN_SUBMIT","Enviar");
define('_BTN_UPGRADE','Actualización');
define("_CHANGE_INFO_1","Por favor, corrija la información que ha introducido  para poder acceder a su base de datos.");
define("_CHMOD_CHECK_1","Comprobación de Permisos CHMOD");
define("_CHMOD_CHECK_2","Lo primero que haremos es comprobar que los permisos actuales son correctos para que el programa de instalación pueda escribir en el archivo correspondiente. Si la configuración no es correcta, el programa de instalación no será capaz de «encriptar» tu información en el archivo 'config.php' y 'config-old.php'. Encriptar los datos de la conexión a la base de datos es útil para añadir seguridad a tu sitio web. Recuerda que puedes actualizar tus datos desde la Administración una vez que el sitio web esté funcionando.");
define("_CHMOD_CHECK_3","La configuración de permisos para el archivo config.php es 666 -- correcto, este script puede escribir en el fichero");
define("_CHMOD_CHECK_4","Por favor, cambie los permisos de el archivo config.php a 666 para que este script pueda escribir y encriptar los datos de la BD (Este cambio lo puede hacer con la orden «chmod 666 config.php»)");
define("_CHMOD_CHECK_5","La configuración de permisos para el archivo config-old.php es 666 -- correcto, este script puede escribir en el fichero");
define("_CHMOD_CHECK_6","Por favor, configure los permisos para el archivo config-old.php a 666 para que este script pueda escribir y encriptar los datos de la BD (Este cambio lo puede hacer con la orden «chmod 666 config-old.php)");
define("_CHM_CHECK_1", "<br /><br />En el caso de que usted no sea el Administrador de su Base de Datos o en el caso de que el Administrador de su Base de Datos no le haya concedido los permisos adecuados para poder crear sus propias Bases de Datos, este script no podrá crear la Base de Datos para PostNuke y necesitará que haya sido creada previamente para poder proseguir con el resto de la instalación.");
define("_CONTINUE_1","Configuración de las Preferencias de su Base de Datos");
define("_CONTINUE_2","<br />Ahora puedes configurar tu cuenta de Administrador. Si decides saltarte este paso de la configuración, tu login para tu cuenta de administrador será <b>Admin</b> y la contraseña <b>Password</b> (fijate en el uso de mayúsculas y minúsculas). <b>Lo aconsejable es que lo configures ahora y no esperes a más tarde.</b>");
define("_DBHOST","Servidor de la BD");
define("_DBINFO","Información de la BD");
define("_DBNAME","Nombre de la BD");
define("_DBPASS","Contraseña de la BD");
define("_DBPREFIX","Prefijo de las tablas (para tablas compartidas)");
define("_DBTYPE","Tipo de la BD");
define("_DBTABLETYPE","Tipo de Tabla");
define("_DBUNAME","Nombre de usuario en la BD");
define("_DEFAULT_1","A partir de esta página se instalará la base de datos de PostNuke y se te ayudará a configurar las variables que necesites para comenzar. Serás guiado a través de un proceso de instalación que está formado por varias páginas. Cada página configura una parte diferente de la instalación de PostNuke. Estimamos que el proceso entero puede tardar no más de 10 minutos. En caso de dudas, por favor, visita este <a href='http://www.dev-postnuke.com/dpForum.html'><b>foro de soporte</b></a> para conseguir ayuda en español.");
define("_DEFAULT_2","Licencia:");
define("_DEFAULT_3","Por favor, lee atentamente la licencia GNU General Public License. <br />PostNuke es Software Libre, y es en la licencia donde se especifican ciertos requerimientos con respecto a su edición y distribución.");
define("_DONE","Acabado.");
define("_FINISH_1","Créditos");
define("_FINISH_2","<br />Estos son los scripts y las personas que han desarrollado PostNuke - estamos satisfechos con la participación de los usuarios. <br />Si quieres participar en su desarrollo y aparecer aquí listado, contacta con <a href=\'www.postnuke.com\'>http://www.postnuke.com</a>.");
define("_FINISH_3","<b>La instalación ahora esta finalizada.<br /><br />Puedes encontrar ayuda y documentación en lengua española sobre PostNuke en <a href=\"www.dev-postnuke.com\">http://www.dev-postnuke.com</a><br /><br /><font color=\"#990000\">El script de instalación ya no será necesario, por seguridad y para poder administrar tu nueva página, debes eliminarlo del directorio de PostNuke <br />(elimina el archivo install.php y también el directorio intall).</font></b>");
define("_FINISH_4","Ir a la página inicial de tu nuevo Sitio PostNuke");
define("_FOOTER_1","Bienvenido a la Comunidad Hispana de PostNuke<br />Gracias por escoger PostNuke como su Sistema de Administración de Contenidos");
define("_FORUM_INFO_1","Las tablas de su foro no se han modificado.<br />Se refiere a las tablas siguientes:");
define("_FORUM_INFO_2","Estas tablas se pueden suprimir si no vas a usar ningun foro. En http://mods.postnuke.com puedes encontrar módulos para crear tu foro si estas interesado.");
define("_INPUT_DATA_1","Datos enviados");
define("_INSTALLATION","Instalación de PostNuke");
define("_MADE"," hecho.");
define("_MAKE_DB_1","Incapaz de crear la base de datos.");
define("_MAKE_DB_2","ha sido creada.");
define("_MAKE_DB_3","No se ha creado la base de datos.");
define("_MODIFY_FILE_1","Error: incapaz de abrir para lectura:");
define("_MODIFY_FILE_2","Error: incapaz de abrir para escritura:");
define("_MODIFY_FILE_3","0 líneas cambiadas, no hay cambios.");
define("_MYPHPNUKE_1","¿Actualizando desde MyPHPNuke 1.8.7?");
define("_MYPHPNUKE_2"," Presione el botón <b>MyPHPNuke 1.8.7</b>.");
define("_MYPHPNUKE_3","¿Actualizando desde MyPHPNuke 1.8.8b2?");
define("_MYPHPNUKE_4"," Presione el botón <b>MyPHPNuke 1.8.8</b>.");
define("_NEWINSTALL","Nueva instalación");
define("_NEW_INSTALL_1","Has seleccionado hacer una <b>instalación nueva</b>. Examina por favor la información siguiente..");
define("_NEW_INSTALL_2","<strong>Nota</strong>: Para crear tu nueva base de datos para PostNuke, tienes dos opciones:<ol><li>Si tienes acceso como administrador a la Base de Datos,al marcar la casilla <b>Crear una nueva Base de Datos</b> este script será capaz de crearla vaciá para ti de forma automatizada, para después insertar las tablas.<br /></li><li>Si solo eres un usuario en la Base de Datos, primero debes crear manualmente la Base de Datos y el script creará y rellenará la Base de Datos con las tablas necesarias.</li></ol>");
define("_NEW_INSTALL_3","Crear una nueva base de datos");
define("_NOTMADE","Incapaz de hacerlo ");
define("_NOTSELECT","Incapaz de seleccionar la base de datos.");
define("_NOTUPDATED","Incapaz de actualizar: ");
define("_PHPNUKE_1","¿Actualizando desde PHP-Nuke 4.4?");
define('_PHPNUKE_10','Presione el botón <b>PHP-Nuke 5.3.1</b>.');
define('_PHPNUKE_11','¿Actualizando desde PHP-Nuke 5.4?');
define('_PHPNUKE_12','Presione el botón <b>PHP-Nuke 5.4</b>.');
define("_PHPNUKE_2","Por favor, lee la siguiente nota y presiona el botón <b>PHP-Nuke 4.4</b> cuando estes preparado..<br /><br />Este script dejará intacta tu base de datos del foro, pero esta versión no gestiona los datos. <i>Hay un script de actualización para los datos de este foro que está siendo probado actualmente. Puede ser localizado en cvs de pn-modules</i><br><br> Nosotros no hemos incluido phpBB en esta versión, pero el script de actualización es el mismo. No destruirá ninguno de tus datos.");
define("_PHPNUKE_3","¿Actualizando desde PHP-Nuke 5?");
define("_PHPNUKE_4","Presione el botón <b>PHP-Nuke 5</b>.");
define("_PHPNUKE_5","¿Actualizando desde PHP-Nuke 5.2?");
define("_PHPNUKE_6","Presione el botón <b>PHP-Nuke 5.2</b>.");
define("_PHPNUKE_7","¿Actualizando desde PHP-Nuke 5.3?");
define("_PHPNUKE_8","Presione el botón <b>PHP-Nuke 5.3</b>.");
define('_PHPNUKE_9','¿Actualizando desde PHP-Nuke 5.3.1?');
define("_PHP_CHECK_1","Su versión de PHP es ");
define("_PHP_CHECK_2","Necesitas actualizar el PHP, al menos hasta la versión 4.0.1 - <a href=\'http://www.php.net\'>http://www.php.net</a>");
define("_PHP_CHECK_3","¡Nota! magic_quotes_gpc está a Off.<br />A menudo, esto se puede corregir usando un fichero <B>.htaccess</B> que contenga esta línea:<br />php_flag magic_quotes_gpc On<p>");
define("_PHP_CHECK_4","¡Nota! magic_quotes_runtime está a On.<br />A menudo, esto se puede corregir usando un fichero <B>.htaccess</B> que contenga la línea:<br />php_flag magic_quotes_runtime Off<p>");
define("_PN6_1","Nota para el Admin: Examina por favor la configuración general de tu sitio web en la administración lo antes posible");
define("_PN6_2","(Nos disculpamos por estos inconvenientes.)");
define("_PN6_3","Error: Archivo no encontrado: ");
define("_PN6_4","Finalizada la conversión de los bloques con estilo antiguo.");
define('_PNTEMP_DIRNOTWRITABLE', 'Por favor cambia los permisos en este directorio a 777 para que este script pueda escribir en este directorio (Pista: Usa "chmod")');
define('_PNTEMP_DIRWRITABLE', 'Correcto, el script pueda escribir en este directorio');
define("_POSTNUKE_1","¿Actualizando desde PostNuke .5x?");
define("_POSTNUKE_10","Presione el botón <b>PostNuke .64</b>.");
define("_POSTNUKE_11","¿Actualizando desde PostNuke .7?");
define("_POSTNUKE_12","Presione el botón <b>Upgrade .7</b>.");
define("_POSTNUKE_13","¿Actualizando desde PostNuke .71?");
define("_POSTNUKE_14","Presione el botón <b>Upgrade .71</b>.");
define('_POSTNUKE_15','¿Comprobando el idioma de su sistema?');
define("_POSTNUKE_16","Presione el botón <b>Comprobar</b>.");
define("_POSTNUKE_17","¿Validando la estructura de sus tablas?");
define("_POSTNUKE_18","Presione el botón <b>Comprobar</b>.");
# added for 0.7.2.2 Neo
define('_POSTNUKE_19','¿Actualizando desde PostNuke .72?');
define('_POSTNUKE_20','Presione el botón <b>PostNuke .72</b>');
define("_POSTNUKE_2","Presione el botón <b>PostNuke .5</b>.");
define("_POSTNUKE_3","¿Actualizando desde PostNuke .6 / .61?");
define("_POSTNUKE_4","Presione el botón <b>PostNuke .6</b>.");
define("_POSTNUKE_5","¿Actualizando desde PostNuke .62?");
define("_POSTNUKE_6","Presione el botón <b>PostNuke .62</b>.");
define("_POSTNUKE_7","¿Actualizando desde PostNuke .63?");
define("_POSTNUKE_8","Presione el botón <b>PostNuke .63</b>.<br />");
define("_POSTNUKE_9","¿Actualizando desde PostNuke .64?");
define('_PWBADMATCH', 'Ha habido algún error y las contraseñas que has introducido no coinciden. Vuelve atrás y reescribe las contraseñas, asegurandote de que son las mismas.');
define('_QUOTESCHECK_1','Verificando NS-Quotes');
define('_QUOTESCHECK_2','El antiguo módulo NS-Quotes va a ser sustituido por el nuevo módulo <b>Quotes</b>.<br /> Por favor, elimine el directorio <i>modules/NS-Quotes</i>');
define("_SELECT_LANGUAGE_1","Por favor, selecciona el idioma");
define("_SELECT_LANGUAGE_2","Idioma: ");
define("_SHOW_ERROR_INFO_1","<b>Error de Escritura</b> Incapaz de actualizar el archivo \"config.php\".<br />Tu puedes modificar este fichero usando un editor de texto.<br/> Aquí están los cambios requeridos:");
define("_SKIPPED","Saltando.");
define("_SUBMIT_1","Por favor, revisa la información y asegurate de que es correcta.");
define("_SUBMIT_2","<b>Has introducido la siguiente información:</b>");
define("_SUBMIT_3","<br />Selecciona <b>Nueva Instalación</b> o <b>Actualización</b> para continuar.");
define("_SUCCESS_1","Finalizado.");
define("_SUCCESS_2","Tu actualización a la última versión de PostNuke ha finalizado.<br />Recuerda cambiar tu configuración del config.php antes de usarlo por primera vez");
define("_UPDATED"," actualizado.");
define("_UPDATING","Actualizando las tablas: ");
define("_UPGRADETAKESALONGTIME", "Según el tamaño (cantidad de información) de la anterior base de datos, el proceso de actualización seguramente pueda necesitar mucho tiempo. Cuando selecciones una opción de actualización, por favor, selecciónela solamente una vez y espere a que aparezca la siguiente pantalla. Pulsando varias veces en la opción puede provocar que el proceso de actualización falle.");
define("_UPGRADE_1","Actualizaciones");
define("_UPGRADE_2","Aquí es donde usted puede elegir desde que CMS va a actualizarse.<br /><br /><center> Seleccione <b>PHP-Nuke</b> para actualizar una instalación ya existente.<br /> Seleccione <b>PostNuke</b> para actualizar una instalación de Postnuke.<br /> Seleccione <b>MyPHPNuke</b> para actualizar una instalación de MyPHPNuke.");
define('_VERSION_WARNING','Las Distribuciones Oficiales de PostNuke solo están disponibles en <a href="http://download.postnuke.com/">download.postnuke.com</a>.<br />Ayuda y Servicios para la Comunidad Hispana de PostNuke en <a href="http://www.dev-postnuke.com/">dev-postnuke.com</a>.');


/* admin module default categories */
define('_ADMIN_CATEGORY_00_a',  'Sistema');
define('_ADMIN_CATEGORY_00_b',  'Módulos del Sistema');
define('_ADMIN_CATEGORY_01_a',  'Contenido');
define('_ADMIN_CATEGORY_01_b',  'Módulos para la administración del contenido');
define('_ADMIN_CATEGORY_02_a',  'Pack de Recursos');
define('_ADMIN_CATEGORY_02_b',  'Módulos del Rack de Recursos');
define('_ADMIN_CATEGORY_03_a',  'Herramientas');
define('_ADMIN_CATEGORY_03_b',  'Herramientas útiles');
define('_ADMIN_CATEGORY_04_a',  'Terceras personas');
define('_ADMIN_CATEGORY_04_b',  'Módulos de terceros');
?>
