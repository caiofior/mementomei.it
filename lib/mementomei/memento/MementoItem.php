<?php
namespace mementomei\memento;
/**
 * Memento class
 *
 * @author caiofior
 */
class MementoItem extends \Content
{
   /**
    * Associates the database table
    * @param \Zend\Db\Adapter\Adapter $db
    */
   public function __construct(\Zend\Db\Adapter\Adapter $db) {
      set_error_handler(create_function('', ''),E_USER_WARNING);
      parent::__construct($db, 'beloved_memento');
      restore_error_handler();
   }
}