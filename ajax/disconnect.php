<?php
if(session_id()=='')
	@session_start();
@session_destroy();
setcookie('username','',0,'/');
header('Location: /');
