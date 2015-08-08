<?php

App::uses('Component', 'Controller');

class EmailLib{

  var $mailer = 'mail';
  var $from = '';
  var $fromName = '';
  var $smtpUserName = '';
  var $smtpPassword = '';
  var $smtpHostNames = 'smtp.gmail.com'; //smtp.gmail.com
  var $smtpPort = 465;
  var $text_body = null;
  var $html_body = null;
  var $to = null;
  var $toName = null;
  var $subject = null;
  var $cc = null;
  var $bcc = null;
  var $template = 'email/default';
  var $attachments = null;
  var $controller;
  var $error_info;

  public function startup(Controller $controller) {
    $this->controller = $controller;
  }

  public function bodyText() {
    ob_start();
    $temp_layout = $this->controller->layout;
    $this->controller->layout = '';
    $this->controller->render($this->template . '_text');
    $mail = ob_get_clean();
    $this->controller->layout = $temp_layout;
    return $mail;
  }

  public function bodyHtml() {
    ob_start();
    $temp_layout = $this->controller->layout;
    $this->controller->layout = 'email';
    $this->controller->render($this->template . '_html');
    $mail = ob_get_clean();
    $this->controller->layout = $temp_layout;
    return $mail;
  }

  public function getError() {
    return $this->error_info;
  }

  public function setError($msg) {
    $this->error_info = $msg;
  }

  public function getMailer() {
    return $this->mailer;
  }

  public function setMailer($mailer) {
    $this->mailer = $mailer;
  }

  public function getSubject() {
    return $this->subject;
  }

  public function setSubject($subject) {
    $this->subject = $subject;
  }

  public function getTextBody() {
    return $this->text_body;
  }

  public function setTextBody($text_body) {
    $this->text_body = $text_body;
  }

  public function getHtmlBody() {
    return $this->html_body;
  }

  public function setHtmlBody($html_body) {
    $this->html_body = $html_body;
  }

  public function attach($filename, $asfile = '') {
    if (empty($this->attachments)) {
      $this->attachments = array();
      $this->attachments[0]['filename'] = $filename;
      $this->attachments[0]['asfile'] = $asfile;
    } else {
      $count = count($this->attachments);
      $this->attachments[$count + 1]['filename'] = $filename;
      $this->attachments[$count + 1]['asfile'] = $asfile;
    }
  }

  public function send() {
    $plugin_path =  App::pluginPath('EmailTemplate');
    require_once $plugin_path . "Vendor" . DS . "phpmailer" . DS . "class.phpmailer.php";

    $mail = new PHPMailer();
    $mail->Mailer = $this->getMailer();

    if ($mail->Mailer == 'smtp') {
      $mail->SMTPAuth = true;
      $mail->Host = $this->smtpHostNames;
      $mail->Username = $this->smtpUserName;
      $mail->Password = $this->smtpPassword;
      $mail->Port = $this->smtpPort;
      //$mail->SMTPDebug = 1;
      $mail->SMTPSecure = 'ssl';
    }


    $mail->setFrom($this->from, $this->fromName);
    $mail->AddAddress($this->to, $this->toName);
    $mail->AddReplyTo($this->from, $this->fromName);

    $mail->CharSet = 'UTF-8';
    //$mail->WordWrap = 50;

    if (!empty($this->attachments)) {
      foreach ($this->attachments as $attachment) {
        if (empty($attachment['asfile'])) {
          $mail->AddAttachment($attachment['filename']);
        } else {
          $mail->AddAttachment($attachment['filename'], $attachment['asfile']);
        }
      }
    }

    $mail->IsHTML(true);

    $mail->Subject = $this->getSubject();
    $mail->Body = $this->getHtmlBody();
    //$mail->AltBody = $this->getTextBody();

    $result = $mail->Send();

    if ($result == false) {
      $this->setError($mail->ErrorInfo);
    }

    return $result;
  }

}
