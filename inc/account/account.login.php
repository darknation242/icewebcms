<?php
if(INCLUDED!==true)exit;
// ==================== //
$pathway_info[] = array('title'=>$lang['login'],'link'=>'');
// ==================== //
if($_POST['action']=='login'){
  $login = $_POST['login'];
  $pass = sha_password($login,$_POST['pass']);
  if($auth->login(array('username'=>$login,'sha_pass_hash'=>$pass)))
  {
    redirect($_SERVER['HTTP_REFERER'],1);
  }
}elseif($_POST['action']=='logout'){
  $auth->logout();
  redirect($_SERVER['HTTP_REFERER'],1);
}
?>
