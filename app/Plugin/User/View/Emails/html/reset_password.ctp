Hello <?= $name ?>,
<br/><br/>
<div>Please click <a href="<?= Router::url(array('plugin' => 'User', 'controller' => 'UserAccount', 'action' => 'resetPassword',$token), true)?>">HERE</a> to reset your password.</div>
<br/><br/>
<div>Please kindly ignore this email if you do not request reset your password</div>
<br/><br/>
Regards,
<br/>
Noreply
