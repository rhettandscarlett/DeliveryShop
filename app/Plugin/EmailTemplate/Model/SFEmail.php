<?php

App::uses('EmailTemplate', 'EmailTemplate.Model');
App::uses('EmailLib', 'EmailTemplate.Lib');

class SFEmail{

  public function sendEmail($template_key, $to_email, $to_name, $params){
    $email_template = new EmailTemplate();
    $template_record = $email_template->findByTemplateKey($template_key);

    $email_body = $template_record['EmailTemplate']['body'];
    foreach ($params['values'] as $token => $value){
      $email_body = str_replace($token, $value, $email_body);
    }

    $emailLib = new EmailLib();
    //$emailLib->setMailer('smtp');
    $emailLib->from = EMAIL_FROM;
    $emailLib->fromName = EMAIL_FROM_NAME;
    $emailLib->to = $to_email;
    $emailLib->toName = $to_name;
    $emailLib->setSubject($template_record['EmailTemplate']['subject']);

    $emailLib->setHtmlBody($email_body);

    $errors = array();
    if (!$emailLib->send()){
      $errors[] = sprintf('Error sending email (%s)', $emailLib->getError());
    }

    return array('errors' => $errors);
  }

}
