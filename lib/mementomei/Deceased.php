<?php
namespace mementomei;
/**
 * Deceased class
 *
 * @author caiofior
 */
class Deceased extends \Content
{
   /**
    * Associates the database table
    * @param \Zend\Db\Adapter\Adapter $db
    */
   public function __construct(\Zend\Db\Adapter\Adapter $db) {
      parent::__construct($db, 'deceased');
   }
   /**
    * Loads a content from its label
    * @param string $label
    */
   public function loadFromLabel ($label) {
        $data = $this->table->select(array('label'=>$label))->current();
        if (is_object($data))
            $this->data = $data->getArrayCopy();
    }
  
}