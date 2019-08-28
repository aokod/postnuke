<?php
// $Id: pnuserapi.php 18049 2006-03-04 22:27:01Z markwest $
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
// Original Author of file: Mark West
// Uses PHPMailer http://phpmailer.sourceforge.net
// Purpose of file:  Mailer user API
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage Mailer
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * API function to send e-mail message
 * @author Mark West
 * @param string args['fromname'] name of the sender
 * @param string args['fromaddress'] address of the sender
 * @param string args['toname '] name to the recipient
 * @param string args['toaddress'] the address of the recipient
 * @param string args['subject'] message subject
 * @param string args['body'] message body
 * @param array  args['cc'] addresses to add to the cc list
 * @param array  args['bcc'] addresses to add to the bcc list
 * @param array/string args['headers'] custom headers to add
 * @param int args['html'] HTML flag
 * @param array args['attachments'] array of absolute filenames to attach to the mail
 * @todo Loading of language file based on PN language
 * @return bool true if successful, false otherwise
 */
function Mailer_userapi_sendmessage($args)
{
    // Admin functions of this type can be called by other modules.  If this
    // happens then the calling module will be able to pass in arguments to
    // this function through the $args parameter.  Hence we extract these
    // arguments *after* we have obtained any form-based input through
    // pnVarCleanFromInput().
    extract($args);

    $pnversion = pnConfigGetVar('Version_Num');
    // include php mailer class file
    if (strpos($pnversion, '0.8')===false) {
        require_once('modules/Mailer/pnincludes/class.phpmailer.php');
    } else {
        require_once('system/Mailer/pnincludes/class.phpmailer.php');
    }

    // create new instance of mailer class
    $mail = new phpmailer();

    // set default message parameters
    if (strpos($pnversion, '0.8')===false) {
        $mail->PluginDir = 'modules/Mailer/pnincludes/';
    } else {
        $mail->PluginDir = 'system/Mailer/pnincludes/';
    }
    $mail->ClearAllRecipients();
    $mail->ContentType = pnModGetVar('Mailer', 'contenttype');
    $mail->CharSet = pnModGetVar('Mailer', 'charset');
    $mail->Encoding = pnModGetVar('Mailer', 'encoding');
    $mail->WordWrap = pnModGetVar('Mailer', 'wordwrap');

    // load the language file
    $mail->SetLanguage('en', $mail->PluginDir . 'language/');

    // get MTA configuration
    if (pnModGetVar('Mailer', 'mailertype') == 4) {
        $mail->IsSMTP();  // set mailer to use SMTP
        $mail->Host = pnModGetVar('Mailer', 'smtpserver');  // specify server
        $mail->Port = pnModGetVar('Mailer', 'smtpport');    // specify port
    } else if (pnModGetVar('Mailer', 'mailertype') == 3) {
        $mail->IsQMail();  // set mailer to use QMail
        $mail->Host = pnModGetVar('Mailer', 'smtpserver');  // specify server
    } else if (pnModGetVar('Mailer', 'mailertype') == 2) {
        ini_set("sendmail_from", $from);
        $mail->IsSendMail();  // set mailer to use SendMail
    } else {
        $mail->IsMail();  // set mailer to use php mail
    }

    // set authentication paramters if required
    if (pnModGetVar('Mailer', 'smtpauth') == 1) {
        $mail->SMTPAuth = true; // turn on SMTP authentication
        $mail->Username = pnModGetVar('Mailer', 'smtpusername');  // SMTP username
        $mail->Password = pnModGetVar('Mailer', 'smtppassword');  // SMTP password
    }

    // set HTML mail if required
    if ((isset($html) && $html) || $mail->ContentType == 'text/html') {
        $mail->IsHTML(true);  // set email format to HTML
    } else {
        $mail->IsHTML(false);  // set email format to plain text
    }

    // set from address
    if (!isset($fromaddress)) {
        $fromaddress = pnConfigGetVar('adminmail');
    }
    $mail->From = $fromaddress;

    // set from name
    if (!isset($fromname)) {
        $fromname = pnConfigGetVar('sitename');
    }
    $mail->FromName = $fromname;

    // add to information
    if (!isset($toname)) {
        $toname = $toaddress;
    }
    $mail->AddAddress($toaddress, $toname);
    $mail->AddReplyTo($fromaddress, $fromname);

    // add any cc addresses
    if (isset($cc) && is_array($cc)) {
        foreach ($cc as $email) {
            if (isset($email['name'])) {
                $mail->AddCC($email['address'], $email['name']);
            } else {
                $mail->AddCC($email['address']);
            }
        }
    }

    // add any bcc addresses
    if (isset($bcc) && is_array($bcc)) {
        foreach ($bcc as $email) {
            if (isset($email['name'])) {
                $mail->AddBCC($email['address'], $email['name']);
            } else {
                $mail->AddBCC($email['address']);
            }
        }
    }

    // add any custom headers
    if (isset($headers) && is_string($headers)) {
        $headers = explode ("\n", $headers);
    }
    if (isset($headers) && is_array($headers)) {
        foreach ($headers as $header) {
            $mail->AddCustomHeader($header);
        }
    }

    // add message subject and body
    $mail->Subject = $subject;
    $mail->Body = $body;

    // add attachments
    if (isset($attachments) && !empty($attachments)) {
        foreach( $attachments as $attachment ) {
            $mail->AddAttachment($attachment);
        }
    }

    // send message
    if(!$mail->Send()) {
        if ($mail->IsError()) {
            return $mail->ErrorInfo; //message not sent and valid error generated
        } else {
            return false; // message not sent
        }
    }
    return true; //message sent
}

?>