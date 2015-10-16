<?php
namespace comuni;
/**
 * Deceased class
 *
 * @author caiofior
 */
class Comuni extends \Content
{
   /**
    * Associates the database table
    * @param \Zend\Db\Adapter\Adapter $db
    */
   public function __construct(\Zend\Db\Adapter\Adapter $db) {
      parent::__construct($db, 'comuni');
   }
}