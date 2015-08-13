Hello <?= $name ?>,
<br/><br/>
<div>Thanks for registering. Please click <a href="<?= Router::url(array('plugin' => 'User', 'controller' => 'UserAccount', 'action' => 'activate',$token), true)?>">HERE</a> to active your account.</div>
<br/><br/>
Regards,
<br/>
Noreply
