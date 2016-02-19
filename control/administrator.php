<?php
require __DIR__.'/../lib/log/Autoload.php';
log\Autoload::getInstance();
if (!array_key_exists('task', $_REQUEST)) {
   $_REQUEST['task']=null;
}
if (
      !isset($GLOBALS['profile']) ||
      !is_object($GLOBALS['profile']) ||
      !$GLOBALS['profile'] instanceof \login\user\Profile
      ) {
   header('Location: '.$GLOBALS['db']->config->baseUrl.'user.php');
   exit;
}
switch ($_REQUEST['task']) {
   case 'beloved':
      require __DIR__.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'beloved.php';    
      break;
   case 'graveyard':
      require __DIR__.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'graveyard.php';    
      break;
   case 'parlour':
      require __DIR__.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'parlour.php';    
      break;
   case 'memento':
      require __DIR__.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'memento.php';    
      break;
   case 'user':
      require __DIR__.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'user.php';    
      break;
   case 'content':
      require __DIR__.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'content.php';       
      break;
   case 'contact':
      require __DIR__.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'contact.php';       
      break;
   case 'log':
      require __DIR__.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'log.php';       
      break;
   case 'backup':
      require __DIR__.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'backup.php';       
      break;
   default:
      $this->getTemplate()->setBlock('middle','administrator/dashboard.phtml');
      break;
}


