<?php 
// File: $Id: global.php 20429 2006-11-07 19:53:57Z landseer $
// ----------------------------------------------------------------------
// Original Author of file: Gregor J. Rothfuss
// Purpose of file: Installer language defines.
// ----------------------------------------------------------------------
//

define('_REGISTER_GLOBALS_ON', 'register_global is on - you should turn this off for enhanced security, for more information click <a href="http://php.net/manual/en/security.globals.php" title="more about register_globals">here</a>');
define('_REGISTER_GLOBALS_ON_HINT', 'Important note: PostNuke itself does not need register_globals=on, but some old modules might need this in order to work. You might consider to not use such modules.');
	
define('_ADMIN_EMAIL','管理员Email');
define('_ADMIN_LOGIN','管理员登录名');
define('_ADMIN_NAME','管理员姓名');
define('_ADMIN_PASS','管理员密码');
define('_ADMIN_REPEATPASS','管理员密码(确认)');
define('_ADMIN_URL','管理员URL');
define('_BTN_CHANGEINFO','更改信息');
define('_BTN_NEWINSTALL','全新安装');
define('_BTN_UPGRADE','升级安装');
define('_BTN_CONTINUE','继续');
define('_BTN_FINISH','完成');
define('_BTN_NEXT','下一步');
define('_BTN_RECHECK','重新检查');
define('_BTN_SET_LANGUAGE','设置语言');
define('_BTN_SET_LOGIN','设置登录');
define('_BTN_START','开始');
define('_BTN_SUBMIT','提交');
define('_CHANGE_INFO_1','请更正您的数据库信息。');
define('_CHANGEINFO','更改信息');
define('_CHARSET','gb2312');
define('_CHMOD_CHECK_1','CHMOD 权限检查');
define('_CHMOD_CHECK_2','我们将首先检查您的CHMOD设置是否正确，以便安装程序能够自动更新配置文件。如果您的设置不正确，安装程序就不能在配置文件中加密您的数据库信息。加密数据库信息使得数据更加安全，但这只能由安装程序完成。一旦您的网站正式运行，您也不能从管理面板修改数据库参数。');
define('_CHMOD_CHECK_3','config.php 的 CHMOD 设置为 666 -- 正确，安装程序可以写入文件');
define('_CHMOD_CHECK_4','<font color="red">请设置 config.php 的 CHMOD 为 666 以便安装程序能够写入加密的数据库信息</font>');
define('_CHMOD_CHECK_5','config-old.php 的 CHMOD 设置为 666 -- 正确，安装程序可以写入文件');
define('_CHMOD_CHECK_6','<font color="red">请设置 config-old.php 的 CHMOD 为 666 以便安装程序能够写入加密的数据库信息</font>');
define('_CHM_CHECK_1', '请输入您的数据库信息。如果您没有您数据库的root权限(比如您使用的是虚拟主机)，您需要在进入下一步之前创建好您的数据库。一个很好的判断方法是，如果您由于使用虚拟主机或是MySQL的安全问题不能通过phpMyAdmin创建数据库，本安装程序也不能为您自动创建数据库。但是安装程序依旧可以向数据库中输入数据。它仍然需要被运行。');
define('_CONTINUE_1','设置您的数据库参数');
define('_CONTINUE_2','您现在可以设置您的管理员帐号。如果您跳过了该步骤，您的管理员登录帐号和密码分别为 Admin 和 [空密码] (大小写敏感)。建议您在安装时就设置好，而不是等到安装完成后再设置。');
define('_DBHOST','数据库主机');
define('_DBINFO','数据库信息');
define('_DBNAME','数据库名');
define('_DBPASS','数据库密码');
define('_DBPREFIX','数据表前缀(用于区别公用数据库的其它程序)');
define('_DBTYPE','数据库类型');
define('_DBUNAME','数据库用户名');
define('_DEFAULT_1','本程序将安装Postnuke的数据库并帮助您设置Postnuke的初始变量。您将会进入不同的页面。每个页面都会设置一些不同的信息。我们估计整个过程将会花上十分钟左右的时间。无论何时您无法继续安装下去，请访问我们的技术支持论坛以寻求帮助。');
define('_DEFAULT_2','许可协议');
define('_DEFAULT_3','请仔细阅读GNU公共许可协议。PostNuke虽然是免费软件，但是对它的分发和修改都需要遵循一定的条件。');
define('_DONE','完成。');
define('_FINISH_1','参与开发人员名单');
define('_FINISH_2','以下是参与开发Postnuke的成员名单。花一些时间让这些人知道您对他们工作的意见和建议。如果您也想将您的名字列入其中，请联系我们，使您成为开发小组中的一员。我们始终在寻求帮助。');
define('_FINISH_3','您现在已经完成Postnuke的安装。如果您在运行中出现任何问题，请让我们知道。<br><br><b>请一定要删除这个安装程序。将来您不会再需要它了。</b>');
define('_FINISH_4','进入您的Postnuke网站');
define('_FOOTER_1','感谢您使用 PostNuke 并欢迎您到我们的社区来。');
define('_FORUM_INFO_1','您的论坛数据表没有改变。<br><br>供参考，这些数据表是:');
define('_FORUM_INFO_2','所以，如果您不想再使用论坛，您可以删除这些数据表。<br> phpBB将会作为一个模块发布在http://mods.postnuke.com');
define('_INPUT_DATA_1','正在更新数据');
define('_INSTALLATION','PostNuke 安装程序');
define('_MADE','已创建。');
define('_MAKE_DB_1','无法创建数据库。');
define('_MAKE_DB_2','已经被创建。');
define('_MAKE_DB_3','没有数据库被创建。');
define('_MODIFY_FILE_1','错误: 无法打开文件:');
define('_MODIFY_FILE_2','错误: 无法写入文件:');
define('_MODIFY_FILE_3','没有修改任一行');
define('_MYPHPNUKE_1','从MyPHPNuke 1.8.7升级？');
define('_MYPHPNUKE_2','只要点击<b>MyPHPNuke 1.8.7</b>按钮');
define('_MYPHPNUKE_3','从MyPHPNuke 1.8.8b2升级？');
define('_MYPHPNUKE_4','只要点击<b>MyPHPNuke 1.8.8</b>按钮');
define('_NEWTABLES_1','无法选择数据库。您必须手动创建数据库，或者如果您有root权限您可以让安装程序自动为您创建数据库。');
define('_NEW_INSTALL_1','您选择了全新安装。下面是您输入的数据库信息。');
define('_NEW_INSTALL_2','如果您拥有root权限，您可以钩选<b>创建数据库</b>选择框，否则请直接点击<b>开始</b>按钮。<br>如果您没有root权限，您需要事先手动创建数据库，之后安装程序可以为数据库添加必要的数据表。');
define('_NEW_INSTALL_3','创建数据库');
define('_NEWINSTALL','全新安装');
define('_NO','否');
define('_NOTMADE','无法创建');
define('_NOTSELECT','无法选择数据库。');
define('_NOTUPDATED','无法升级');
define('_PHPNUKE_1','从PHP-Nuke 4.4升级？');
define('_PHPNUKE_2','请阅读如下注意事项，当准备好后点击<b>PHP-Nuke 4.4</b>按钮。<br><br>安装程序不会更改您的论坛数据库。<i> 现在有一个论坛数据库升级程序正在pn-modules CVS中测试。</i><br><br> 我们并没有将PHPBB包含在Postnuke的发布版本中，但是升级程序是相同的。它不会毁坏您的任何数据。');
define('_PHPNUKE_3','从PHP-Nuke 5升级？');
define('_PHPNUKE_4','只要点击<b>PHP-Nuke 5</b>按钮');
define('_PHPNUKE_5','从PHP-Nuke 5.2升级？');
define('_PHPNUKE_6','只要点击<b>PHP-Nuke 5.2</b>按钮');
define('_PHPNUKE_7','从PHP-Nuke 5.3升级？');
define('_PHPNUKE_8','只要点击<b>PHP-Nuke 5.3</b>按钮');
define('_PHPNUKE_9','从PHP-Nuke 5.3.1升级？');
define('_PHPNUKE_10','只要点击<b>PHP-Nuke 5.3.1</b>按钮');
define('_PHPNUKE_11','从PHP-Nuke 5.4升级？');
define('_PHPNUKE_12','只要点击<b>PHP-Nuke 5.4</b>按钮');
define('_PHP_CHECK_1','您的PHP版本是');
define('_PHP_CHECK_2','您需要升级您的PHP到至少4.0.1版本 - <a href=\'http://www.php.net\'>http://www.php.net</a>');
define('_PHP_CHECK_3','错误！magic_quotes_gpc设置为Off。<br>这可以通过使用.htaccess 文件修正。只要在.htaccess中加入如下行:<br>php_flag magic_quotes_gpc On');
define('_PHP_CHECK_4','错误！magic_quotes_runtime设置为On.<br>这可以通过使用.htaccess 文件修正。只要在.htaccess中加入如下行:<br>php_flag magic_quotes_runtime Off');
define('_PN6_1','管理员: 您需要在管理面板中重新保存您的设置！');
define('_PN6_2','(我们对此感到抱歉)');
define('_PN6_3','错误: 文件未找到: ');
define('_PN6_4','转换旧样式的按钮区块完成。');
define('_POSTNUKE_1','从PostNuke .5x升级？');
define('_POSTNUKE_10','只要点击<b>PostNuke .64</b>按钮');
define('_POSTNUKE_11','从PostNuke .7升级？');
define('_POSTNUKE_12','只要点击<b>PostNuke 7</b>按钮');
define('_POSTNUKE_13','从PostNuke .71升级？');
define('_POSTNUKE_14','只要点击<b>PostNuke 71</b>按钮');
define('_POSTNUKE_15','要确认您系统的语言？');
define('_POSTNUKE_16','Just press the <b>Validate</b> button');
define('_POSTNUKE_17','Validate your table structure?');
define('_POSTNUKE_18','Just press the <b>Validate</b> button');
define('_POSTNUKE_19','从PostNuke .72升级？');
define('_POSTNUKE_20','只要点击<b>PostNuke 72</b>按钮');
define('_POSTNUKE_2','只要点击<b>PostNuke .5</b>按钮');
define('_POSTNUKE_3','从PostNuke .6 / .61升级？');
define('_POSTNUKE_4','只要点击<b>PostNuke .6</b>按钮');
define('_POSTNUKE_5','从PostNuke .62升级？');
define('_POSTNUKE_6','只要点击<b>PostNuke .62</b>按钮');
define('_POSTNUKE_7','从PostNuke .63升级？');
define('_POSTNUKE_8','只要点击<b>PostNuke .63</b>按钮<br>');
define('_POSTNUKE_9','从PostNuke .64升级？');
define('_PWBADMATCH', '两次输入的密码不一致。请后退并重新输入密码。');
define('_SELECT_LANGUAGE_1','请选择安装程序的语言(Select your language)。');
define('_SELECT_LANGUAGE_2','语言(Language): ');
define('_SHOW_ERROR_INFO_1','<b>写入错误</b> 无法更新您的\'config.php\'文件。<br/> 您需要通过文本编辑器手动修改它。<br/> 下面是需要修改的地方:');
define('_SKIPPED','已跳过。');
define('_SUBMIT_1','请再浏览一下以确认您提交的数据库信息是否正确。');
define('_SUBMIT_2','您提交了如下数据库信息：');
define('_SUBMIT_3','如果以上信息正确无误，请选择<b>全新安装</b>或<b>升级安装</b>以继续。');
define('_SUCCESS_1','完成');
define('_SUCCESS_2','您已经升级到最新版本的Postnuke。<br> 在第一次运行前请记住修改您的config.php设置。');
define('_UPDATED',' 已更新。');
define('_UPDATING','正在升级数据表: ');
define('_UPGRADE','升级安装');
define('_UPGRADE_1','升级安装 ');
define('_UPGRADE_2','这里您可以选择您从哪种内容管理系统升级。<br><br><center> 选择<b>PHP-Nuke</b>会从已经存在的PHP-Nuke系统升级。<br> 选择<b>PostNuke</b>会从已经存在的PostNuke系统升级。<br> 选择<b>MyPHPNuke</b>会从已经存在的MyPHPNuke系统升级。');
define('_UPGRADETAKESALONGTIME','请注意，升级程序将会花上不少的时间，很可能为几分钟。每当您选择了一个升级选项，请只点击继续的按钮一次，而不是多次。然后等待下一个页面的出现。<br>点击继续的按钮多次会导致升级失败。<b>升级前请备份原有数据库！</b>');
define('_WARNING', '警告');
define('_YES', '是');

define('_QUOTESCHECK_1','NS-Quotes 检查');
define('_QUOTESCHECK_2','以前的 NS-Quotes 模块不推荐使用，请使用新版本的 Quotes 模块。<br> 请删除 modules/NS-Quotes 目录。');
define('_PERCENT','百分比');

// .726 RC3
define('_BLOCKTITLE_INCOMING','等待的内容');
define('_BLOCKTITLE_WHOISONLINE','谁在线？');
define('_BLOCKTITLE_OTHERSTORIES','其它文章');
define('_BLOCKTITLE_USERSBLOCK','用户自定义区块');
define('_BLOCKTITLE_SEARCHBOX','搜索');
define('_BLOCKTITLE_EPHEMERIDS','历史上的今天');
define('_BLOCKTITLE_LANGUAGES','Languages');
define('_BLOCKTITLE_CATMENU','分类菜单');
define('_BLOCKTITLE_RANHEAD','随机头条');
define('_BLOCKTITLE_POLL','投票');
define('_BLOCKTITLE_BIGSTORY','最受欢迎');
define('_BLOCKTITLE_USERSLOGIN','用户登录');
define('_BLOCKTITLE_PASTART','过去的文章');
define('_BLOCKTITLE_ADMINMESS','管理员消息');
define('_BLOCKTITLE_REMINDER','提醒');
define('_BLOCKTITLE_USERSBLOCK_TEXTE','将您想要的东西放到这里');
define('_BLOCKTITLE_MAINMENU','主菜单');
//define('_BLOCKTITLE_MAINMENU_TEXT','style:=1\ndisplaymodules:=0\ndisplaywaiting:=0\ncontent:=index.php|Home|Back to the home page.LINESPLITuser.php|My Account|Administer your personal account.LINESPLITadmin.php|Administration|Administer your PostNuked site.LINESPLITuser.php?module=NS-User&op=logout|Logout|Logout of your account.LINESPLIT|Modules|LINESPLIT[AvantGo]|AvantGo|Stories formatted for PDAs.LINESPLIT[Downloads]|Downloads|Find downloads listed on this website.LINESPLIT[FAQ]|FAQ|Frequently Asked QuestionsLINESPLIT[Members_List]|Members List|Listing of registered users on this site.LINESPLIT[News]|News|Latest News on this site.LINESPLIT[Recommend_Us]|Recommend Us|Recommend this website to a friend.LINESPLIT[Reviews]|Reviews|Reviews Section on this website.LINESPLIT[Search]|Search|Search our website.LINESPLIT[Sections]|Sections|Other content on this website.LINESPLIT[Stats]|Stats|Detailed traffic statistics.LINESPLIT[Submit_News]|Submit News|Submit an article.LINESPLIT[Topics]|Topics|Listing of news topics on this website.LINESPLIT[Top_List]|Top List|Top 10list.LINESPLIT[Web_Links]|Web Links|Links to other sites.');
//define('_FOOTMSG','<br /><a href=\"http://www.postnuke.com\" target=\"_blank\"><img src=\"images/powered/postnuke.butn.gif\" border=\"0\" alt=\"Web site powered by PostNuke\" hspace=\"10\" /></a> <a href=\"http://php.weblogs.com/ADODB\" target=\"_blank\"><img src=\"images/powered/adodb2.gif\" alt=\"ADODB database library\" border=\"0\" hspace=\"10\" /></a><a href=\"http://www.php.net\" target=\"_blank\"><img src=\"images/powered/php2.gif\" alt=\"PHP Language\" border=\"0\" hspace=\"10\" /></a><br /><br />All logos and trademarks in this site are property of their respective owner. The comments are property of their posters, all the rest (c) 2003 by me<br />This web site was made with <a href=\"http://www.postnuke.com\" target=\"_blank\">PostNuke</a>, a web portal system written in PHP. PostNuke is Free Software released under the <a href=\"http://www.gnu.org\" target=\"_blank\">GNU/GPL license</a>.<br />You can syndicate our news using the file <a href=\"backend.php\">backend.php</a>');

define('_POLLDATATEXT1','什么是PostNuke？');
define('_POLLDATATEXT2','这正是我所需要的。');
define('_POLLDATATEXT3','还用想？我已经在用它了！');
define('_POLLDESCTEXT','您认为PostNuke如何？');


define('_REWIEWSMAINTITLE','评论区标题');
define('_REWIEWSMAINDESC','评论区描述');

define('_DBTABLETYPE', '数据表类型');

define('_INSTALL_REMINDERBLOCK', '请记住从您的PostNuke目录中删除如下文件和目录<p>&middot;<b>install.php</b> 文件<p>&middot;<b>install</b> 目录<p>如果您不删除这些文件，用户便可以轻易获得您的数据库密码！<br /><i>注意：本区块可以在管理面板中编辑或者删除。</i>');
define('_INSTALL_ADMINMESSAGE_TEXTE', '<a target="_blank" href="http://www.postnuke.com">PostNuke</a>是一个内容管理系统。同时<a target="_blank" href="http://www.postnuke.com"> PostNuke</a> 是<a target="_blank" href="http://www.phpnuke.org">PHP-Nuke</a>的folk版本，但是所有的代码都已经被替换，使之更加安全和稳定，可以工作在高负荷下。<br /><br /> PostNuke的一些精彩之处在于 <ul /> <li /> 定制网站所有部分的样式主题，包括CSS支持 <li /> 绝大部分模块都支持多语言 <li /> 完全兼容HTML 4.01，在所有的浏览器中都能很好的显示 <li /> 一系列标准的API和完善的文档，使得您可以通过开发区块和模块，轻易扩展网站的功能 </ul><br /><br /> Postnuke 在<a target="_blank" href="http://www.postnuke.com/">www.postnuke.com</a>有着非常活跃的开发群体和支持中心。 <br /> <br /> 我们希望您会喜欢Postnuke。 <br /> <br /> <b>Postnuke开发小组</b><br /><br /><i>注意：本消息可以在管理面板的“消息管理”中修改</i>');

// 0.726 final
define('_INSTALL_ADMINMESSAGE_TITLE', '欢迎来到 PostNuke,  =-Phoenix-= 发布版本 (0.726)');
define('_FOOTMSGTEXT','<br /><a href="http://www.postnuke.com" target="_blank"><img src="images/powered/postnuke.butn.gif" border="0" alt="Web site powered by PostNuke" hspace="10" /></a> <a href="http://php.weblogs.com/ADODB" target="_blank"><img src="images/powered/adodb2.gif" alt="ADODB database library" border="0" hspace="10" /></a><a href="http://www.php.net" target="_blank"><img src="images/powered/php2.gif" alt="PHP Scripting Language" border="0" hspace="10" /></a><br /><br />所有的图标和商标分别由各自所有网站所有。这些注释所有人为注释的发表者，其余的所有人均为&copy;2003 我<br />网站基于 <a href="http://www.postnuke.com" target="_blank">Postnuke</a> 建立，它是一个用PHP编写的网络门户系统。Postnuke 是遵循<a href="http://www.gnu.org" target="_blank">GNU/GPL 协议</a>的免费软件。<br />您可以通过<a href="backend.php">backend.php</a>同步本站的文章');
define('_BLOCKTITLE_MAINMENU_HOME','首页');
define('_BLOCKTITLE_MAINMENU_HOMEALT','回到站点首页。');
define('_BLOCKTITLE_MAINMENU_USER','我的帐号');
define('_BLOCKTITLE_MAINMENU_USERALT','管理您的帐号信息。');
define('_BLOCKTITLE_MAINMENU_ADMIN','管理');
define('_BLOCKTITLE_MAINMENU_ADMINALT','站点管理入口。');
define('_BLOCKTITLE_MAINMENU_USEREXIT','注销/离站');
define('_BLOCKTITLE_MAINMENU_USEREXITALT','注销您的帐号并清空所有Cookie。');
define('_BLOCKTITLE_MAINMENU_AVANTGO','AvantGo');
define('_BLOCKTITLE_MAINMENU_AVANTGOALT','您可以通过这个链接在PDA上阅读本站文章。');
define('_BLOCKTITLE_MAINMENU_DL','下载');
define('_BLOCKTITLE_MAINMENU_DLALT','本站下载资源。');
define('_BLOCKTITLE_MAINMENU_FAQ','常见问题解答');
define('_BLOCKTITLE_MAINMENU_FAQALT','常见问题解答');
define('_BLOCKTITLE_MAINMENU_MLIST','本站成员列表');
define('_BLOCKTITLE_MAINMENU_MLISTALT','本站注册成员的列表。');
define('_BLOCKTITLE_MAINMENU_NEWS','新闻');
define('_BLOCKTITLE_MAINMENU_NEWSALT','本站中发表的文章。');
define('_BLOCKTITLE_MAINMENU_RUS','推荐我们');
define('_BLOCKTITLE_MAINMENU_RUSALT','如果您觉得我们的网站好，您可以在这里推荐本站给您的朋友。');
define('_BLOCKTITLE_MAINMENU_RWS','评论');
define('_BLOCKTITLE_MAINMENU_RWSALT','您可以在这里对某产品发表评论。');
define('_BLOCKTITLE_MAINMENU_SEARCH','搜索');
define('_BLOCKTITLE_MAINMENU_SEARCHALT','本站所有资源的搜索。');
define('_BLOCKTITLE_MAINMENU_SECTIONS','精华区');
define('_BLOCKTITLE_MAINMENU_SECTIONSALT','本站一些精华的文章。');
define('_BLOCKTITLE_MAINMENU_STATS','本站访问统计');
define('_BLOCKTITLE_MAINMENU_STATSALT','本站访问流量的详细统计。');
define('_BLOCKTITLE_MAINMENU_SNEWS','提交文章');
define('_BLOCKTITLE_MAINMENU_SNEWSALT','您可以提交您的文章到本站发表。');
define('_BLOCKTITLE_MAINMENU_TOPICS','主题');
define('_BLOCKTITLE_MAINMENU_TOPICSALT','本站文章的主题。');
define('_BLOCKTITLE_MAINMENU_TLIST','Top 排行');
define('_BLOCKTITLE_MAINMENU_TLISTALT','本站的Top排行。');
define('_BLOCKTITLE_MAINMENU_WLINKS','网站链接');
define('_BLOCKTITLE_MAINMENU_WLINKSALT','一些优秀的网站链接。');

define('_VERSION_WARNING','注意：官方发布版本的 PostNuke 只在 <a href="http://download.postnuke.com/" target="_blank">PostNuke.com</a> 提供下载。<br>如果您需要本软件质量的保证，请安装官方发布版本。');

?>
