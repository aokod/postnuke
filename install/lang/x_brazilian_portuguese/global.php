<?php
// $Id: global.php 20429 2006-11-07 19:53:57Z landseer $
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
// Original Author of file: Gregor J. Rothfuss
// Changes by:
// Author of Brazilian Portuguese version: Pedro Innecco
// Corretions/additions by:
// Pedro Innecco
// Valdemar Biondo Junior
// Purpose of file: Translation files
// Translation team: Read credits in /docs/CREDITS.txt
// ----------------------------------------------------------------------

define('_REGISTER_GLOBALS_ON', 'register_global is on - you should turn this off for enhanced security, for more information click <a href="http://php.net/manual/en/security.globals.php" title="more about register_globals">here</a>');
define('_REGISTER_GLOBALS_ON_HINT', 'Important note: PostNuke itself does not need register_globals=on, but some old modules might need this in order to work. You might consider to not use such modules.');

define('_ADMIN_EMAIL','E-mail do administrador');
define('_ADMIN_LOGIN','Nome de Usurio do administrador');
define('_ADMIN_NAME','Nome do administrador');
define('_ADMIN_PASS','Senha do administrador');
define('_ADMIN_REPEATPASS','Verifique a senha do administrador');
define('_ADMIN_URL','Site do Administrador');
define('_BTN_CONTINUE','Continuar');
define('_BTN_FINISH','Concluir');
define('_BTN_NEXT','Prosseguir');
define('_BTN_RECHECK','Verificar novamente');
define('_BTN_SET_LANGUAGE','Selecione o idioma');
define('_BTN_SET_LOGIN','Definir Login');
define('_BTN_START','Iniciar');
define('_BTN_SUBMIT','Enviar');
define('_CHANGE_INFO_1',' Por favor corrija as informaes sobre o seu banco de dados.');
define('_CHMOD_CHECK_1','Verificao CHMOD');
define('_CHMOD_CHECK_2','Primeiro precisamos verificar se os seus valores CHMOD esto corretos para que o script possa modificar os arquivos necessrios.  Se os valores no estiverem corretos, este script no poder criptografar os seus dados no seu arquivo de configurao.  Criptografia de dados SQL  uma adio de segurana efetuada por este script. Voc no poder atualizar as suas preferncias de administrador uma vez que o seu site esteja instalado.');
define('_CHMOD_CHECK_3','Valor CHMOD para o config.php  666 -- correto, o script tem acesso de modificao ao arquivo');
define('_CHMOD_CHECK_4','Por favor, atribua o valor CHMOD 666 ao config.php para que o script possa modificar e criptografar o banco de dados');
define('_CHMOD_CHECK_5','Valor CHMOD para o config-old.php  666 -- correto, o script tem acesso de modificao ao arquivo');
define('_CHMOD_CHECK_6','Por favor, atribua o valor CHMOD 666 ao config-old.php para que o script possa modificar e criptografar o banco de dados');
define('_CHM_CHECK_1','Por favor, insira as informaes do seu banco de dados.  Se voc no tem acesso \'root\' ao seu banco de dados (hospedagem compartilhada etc), voc deve informar o banco de dados existente. Como regra bsica, se voc no pode criar um banco de dados utilizando o phpMyAdmin pelo fato de estar utilizando hosting virtual, ou por motivos de segurana do mySQL, ento este script no poder criar o banco de dados para voc.  No entanto, este script ainda precisa ser executado para preencher um banco de dados existente.');
define('_CONTINUE_1','Definindo suas preferncias do banco de dados');
define('_CONTINUE_2','Voc agora pode configurar uma conta administrativa. Se voc pular esta parte da configurao, o seu login para a conta administrativa ser Admin / Password (respeite maiscula e minsculas).   recomendvel que voc configure a conta administrativa agora e no espere para mais tarde.');
define('_DBHOST','Servidor do banco de dados (host)');
define('_DBINFO','Informaes sobre o banco de dados');
define('_DBNAME','Nome do banco de dados (name)');
define('_DBPASS','Senha do banco de dados (password)');
define('_DBPREFIX','Prefixo da tabela (para compartilhamento de tabelas)');
define('_DBTYPE','Tipo de banco de dados');
define('_DBTABLETYPE','Tipo das tabelas do banco de dados');
define('_DBUNAME','Nome de usurio do banco de dados (username)');
define('_DEFAULT_1','Este script instalar o banco de dados do PostNuke e lhe ajudar a definir as variveis necessrias para comear. Voc ser conduzido por diversas pginas. Cada pgina efetuar diferentes pores do script. Estimamos que todo o processo levar aproximadamente dez minutos. Se voc tiver dvidas em qualquer momento, por favor visite nossos fruns de suporte para obter ajuda. Ajuda em Portugus disponvel pelo e-mail: pn@emlondrina.com.');
define('_DEFAULT_2','Nossa licena');
define('_DEFAULT_3','Por favor, leia com ateno a licena GNU General Public License. PostNuke  desenvolvido como software livre, mas existem certos requerimentos para distribuio e modificao.');
define('_DONE','Concludo.');
define('_FINISH_1','Crditos');
define('_FINISH_2','Estes so os scripts e as pessoas que fazem com que o PostNuke siga em frente. Use seu tempo para permitir que essas pessoas saibam o quanto voc aprecia o trabalho delas. Se voc deseja ser listado aqui, entre em contato com a gente para mais informaes sobre como fazer parte do time de desenvolvimento. Ns estamos sempre aceitando ajuda.');
define('_FINISH_3','A instalao do PostNuke terminou. Se voc encontrou algum problema, por favor informe-nos.  Assegure-se de remover este script. Voc no precisar dele novamente.');
define('_FINISH_4','Ir ao seu site PostNuke');
define('_FOOTER_1','Obrigado por utilizar o PostNuke e seja bem-vindo  nossa comunidade. Encontre sempre as tradues mais recente do PostNuke em http://sf.net/projects/pnlanguages');
define('_FORUM_INFO_1','Suas tabelas para fruns no foram modificadas.<br><br>Para sua informao, estas tabelas so as seguintes:');
define('_FORUM_INFO_2','Portanto, voc pode remover estas tabelas se voc no deseja utilizar fruns.');
define('_INPUT_DATA_1','Dados enviados');
define('_INSTALLATION','Instalao do Postnuke');
define('_MAKE_DB_1','No foi possvel criar o novo banco de dados');
define('_MAKE_DB_2','foi criado com sucesso.');
define('_MAKE_DB_3','No foi necessrio criar um novo banco de dados.');
define('_MODIFY_FILE_1','Ocorreu um erro, pois no foi possvel abrir o seguinte arquivo para leitura:');
define('_MODIFY_FILE_2','Ocorreu um erro, pois no foi possvel abrir o seguinte arquivo para gravao:');
define('_MODIFY_FILE_3','0 linhas modificadas, nada ocorreu');
define('_MYPHPNUKE_1','Atualizando o MyPHPNuke 1.8.7?');
define('_MYPHPNUKE_2','Pressione o boto <b>MyPHPNuke 1.8.7</b>');
define('_MYPHPNUKE_3','Atualizando o MyPHPNuke 1.8.8b2?');
define('_MYPHPNUKE_4','Pressione o boto <b>MyPHPNuke 1.8.8</b>');
define('_NEWINSTALL','Nova Instalao');
define('_NEW_INSTALL_1','Voc optou por realizar uma nova instalao. Seguem abaixo as informaes que voc indicou.');
define('_NEW_INSTALL_2','Se voc tem acesso \'root\', selecione a opo <b>criar o banco de dados</b>. De outra forma, simplesmente clique no boto iniciar.<br>Se voc no tem acesso \'root\' voc deve criar o banco de dados manualmente e este script adicionar as tabelas necessrias para voc.');
define('_NEW_INSTALL_3','Criar o banco de dados');
define('_NOTMADE','No foi possvel criar ');
define('_NOTSELECT','No foi possvel selecionar o banco de dados.');
define('_NOTUPDATED','No foi possvel atualizar ');
define('_PHPNUKE_1','Atualizando o PHP-Nuke 4.4?');
define('_PHPNUKE_10','Pressione o boto <b>PHP-Nuke 5.3.1</b>');
define('_PHPNUKE_11','Atualizando o PHP-Nuke 5.4?');
define('_PHPNUKE_12','Pressione o boto <b>PHP-Nuke 5.4</b>');
define('_PHPNUKE_2','Por favor, leia a seguinte nota, e pressione o boto <b>PHP-Nuke 4.4</b> ao terminar.<br><br> Este script deixar os dados do seu banco de dados de fruns intactos, mas esta verso no ir gerenciar os dados.<i> H um script de upgrade para os dados de fruns que est sendo testado. No momento este se encontra no CVS pn-modules</i><br><br> Ns no temos o PHPBB includo neste lanamento, mas o script de upgrade  o mesmo. O script no destruir nehum dos seus dados.');
define('_PHPNUKE_3','Atualizando o PHP-Nuke 5?');
define('_PHPNUKE_4','Pressione o boto <b>PHP-Nuke 5</b>');
define('_PHPNUKE_5','Atualizando o PHP-Nuke 5.2?');
define('_PHPNUKE_6','Pressione o boto <b>PHP-Nuke 5.2</b>');
define('_PHPNUKE_7','Atualizando o PHP-Nuke 5.3?');
define('_PHPNUKE_8','Pressione o boto <b>PHP-Nuke 5.3</b>');
define('_PHPNUKE_9','Atualizando o PHP-Nuke 5.3.1?');
define('_PHP_CHECK_1','A verso do PHP de seu servidor:');
define('_PHP_CHECK_2','Voc deve atualizar o PHP para a verso 4.1.0, no mnimo  - <a href=\'http://www.php.net\'>http://www.php.net</a>');
define('_PHP_CHECK_3','Isto no  bom! magic_quotes_gpc est desligado (Off).<br>Isto normalmente pode ser reparado utilizando-se um arquivo .htaccess com a seguinte linha:<br>php_flag magic_quotes_gpc On');
define('_PHP_CHECK_4','Isto no  bom! magic_quotes_runtime est ligado (On).<br>Isto normalmente pode ser reparado utilizando-se um arquivo .htaccess com a seguinte linha:<br>php_flag magic_quotes_runtime Off');
define('_PN6_1','Admin: Voc dever gravar novamente as preferncias do seu Website na pgina de administrao o mais rpido possvel!');
define('_PN6_2','(lamentamos por ainda no termos automatizado essa tarefa)');
define('_PN6_3','ERRO: arquivo no encontrado: ');
define('_PN6_4','Concluda a converso de blocos.');
define('_PNTEMP_DIRNOTWRITABLE','Por favor, altere as permisses deste diretrio para 777, de modo que o script possa escrever nele. (DICA: use "chmod")');
define('_PNTEMP_DIRWRITABLE','correto, o script pode escrever neste diretrio');
define('_POSTNUKE_1','Atualizando do PostNuke .5x?');
define('_POSTNUKE_10','Pressione o boto <b>PostNuke .64</b>');
define('_POSTNUKE_11','Atualizando do PostNuke .7?');
define('_POSTNUKE_12','Pressione o boto <b>Upgrade 7</b>');
define('_POSTNUKE_13','Atualizando do PostNuke .71?');
define('_POSTNUKE_14','Pressione o boto <b>PostNuke .71</b>');
define('_POSTNUKE_15','Para confirmar a linguagem de seu sistema');
define('_POSTNUKE_16','Pressione o boto <b>Validate</b>');
define('_POSTNUKE_17','Validar a sua estrutura de tabela?');
define('_POSTNUKE_18','Pressione o boto <b>Validar</b>');

# added for 0.7.2.2 Neo
define('_POSTNUKE_19','Atualizando do PostNuke .72?');
define('_POSTNUKE_20','Pressione o boto <b>PostNuke .72</b>');

define('_POSTNUKE_2','Pressione o boto <b>PostNuke .5</b>');
define('_POSTNUKE_3','Atualizando do PostNuke .6 / .61?');
define('_POSTNUKE_4','Pressione o boto <b>PostNuke .6</b>');
define('_POSTNUKE_5','Atualizando do PostNuke .62?');
define('_POSTNUKE_6','Pressione o boto <b>PostNuke .62</b>');
define('_POSTNUKE_7','Atualizando do PostNuke .63?');
define('_POSTNUKE_8','Pressione o boto <b>PostNuke .63</b><br>');
define('_POSTNUKE_9','Atualizando do PostNuke .64?');
define('_PWBADMATCH','As senhas de acesso fornecidas no conferem.  Por favor, volte para a pgina anterior e verifique que as senhas fornecidas so idnticas.');
define('_QUOTESCHECK_1','Verificao NS-Quotes');
define('_QUOTESCHECK_2','O mdulo NS-Quotes foi declarado obsoleto em favor do novo mdulo Quotes.<br> Por favor, remova o diretrio modules/NS-Quotes.');
define('_SELECT_LANGUAGE_1','Selecione o seu idioma.');
define('_SELECT_LANGUAGE_2','Idioma: ');
define('_SHOW_ERROR_INFO_1','Ocorreu um erro de gravao, pois </b>no foi possvel atualizar o arquivo \'config.php\'<br> Voc dever modificar este arquivo manualmente utilizando um editor de texto.<br> Seguem as modificaes necessrias:');
define('_SKIPPED','Ignorado.');
define('_SUBMIT_1','Por favor, confirme que as informaes so corretas.');
define('_SUBMIT_2','Voc forneceu as seguinte informaes:');
define('_SUBMIT_3','Selecione <b>Nova Instalao</b> ou <b>Atualizao</b> para continuar.');
define('_SUCCESS_1','Finalizado');
define('_SUCCESS_2','A sua atualizao para a ltima verso do PostNuke terminou.<br> Lembre-se de modificar as preferncias do seu config.php antes de utiliz-lo pela primeira vez.');
define('_UPDATED',' atualizada.');
define('_UPDATING','Atualizando a tabela: ');
define('_UPGRADETAKESALONGTIME','Prosseguir com uma atualizao do PostNuke pode demorar vrios minutos.  Ao selecionar uma opo de atualizao, por favor, selecione-a somente uma vez e espere pela prxima tela.  Clicar sobre uma opo de atualizao diversas vezes poder causar uma falha no processo.');
define('_UPGRADE_1','Atualizaes');
define('_UPGRADE_2','Aqui voc pode selecionar que sistema CMS voc deseja atualizar.<br><br><center> Selecione <b>PHP-Nuke</b> para atualizar uma instalao PHP-Nuke existente.<br> Selecione <b>PostNuke</b> para atualizar uma instalao PostNuke existente.<br> Selecione <b>MyPHPNuke</b> para atualizar uma instalao MyPHPNuke existente.');
?>