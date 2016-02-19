<?php
namespace mementomei;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Autoload
 *
 * @author caiofior
 */
class Autoload {
   private static $instance = null;
   private function __construct() {
      if (!class_exists('Autoload')) {
         require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'Autoload.php';
         \Autoload::getInstance();
      
      }

       if (!class_exists('login\Autoload')) {
           require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'login'.DIRECTORY_SEPARATOR.'Autoload.php';
           \login\Autoload::getInstance();

       }
       
       if (!class_exists('content\Autoload')) {
           require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'content'.DIRECTORY_SEPARATOR.'Autoload.php';
           \content\Autoload::getInstance();
       }
             
      $firePhpDir = $GLOBALS['db']->baseDir.'/lib/firephp/lib/FirePHPCore';
      if (!class_exists('FirePHP') && is_dir($firePhpDir)) {
         require $firePhpDir.'/FirePHP.class.php';
         require $firePhpDir.'/fb.php';
         $GLOBALS['firephp'] = \FirePHP::getInstance(true);
      }

   }
   public static function getInstance()
   {
      if(self::$instance == null)
      {   
         $class = __CLASS__;
         self::$instance = new $class();
      }
      require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'geoPHP'.DIRECTORY_SEPARATOR.'geoPHP.inc';
      
      require __DIR__.DIRECTORY_SEPARATOR.'Beloved.php';
      require __DIR__.DIRECTORY_SEPARATOR.'BelovedColl.php';
      require __DIR__.DIRECTORY_SEPARATOR.'agency'.DIRECTORY_SEPARATOR.'Agency.php';
      require __DIR__.DIRECTORY_SEPARATOR.'agency'.DIRECTORY_SEPARATOR.'AgencyColl.php';
      require __DIR__.DIRECTORY_SEPARATOR.'agency'.DIRECTORY_SEPARATOR.'Graveyard.php';
      require __DIR__.DIRECTORY_SEPARATOR.'agency'.DIRECTORY_SEPARATOR.'GraveyardColl.php';
      require __DIR__.DIRECTORY_SEPARATOR.'agency'.DIRECTORY_SEPARATOR.'Parlour.php';
      require __DIR__.DIRECTORY_SEPARATOR.'agency'.DIRECTORY_SEPARATOR.'ParlourColl.php';
      require __DIR__.DIRECTORY_SEPARATOR.'memento'.DIRECTORY_SEPARATOR.'Memento.php';
      require __DIR__.DIRECTORY_SEPARATOR.'memento'.DIRECTORY_SEPARATOR.'MementoColl.php';
      return self::$instance;
   }
}
