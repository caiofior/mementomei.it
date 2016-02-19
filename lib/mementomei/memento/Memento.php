<?php
namespace mementomei\memento;
/**
 * Memento class
 *
 * @author caiofior
 */
class Memento extends \Content
{
   /**
    * Associates the database table
    * @param \Zend\Db\Adapter\Adapter $db
    */
   public function __construct(\Zend\Db\Adapter\Adapter $db) {
      parent::__construct($db, 'memento');
   }
}