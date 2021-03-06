<?php
namespace comuni;
/**
 * Deceased Coll Class
 *
 * @author caiofior
 */
class ComuniColl extends \ContentColl {
      public function __construct($db) {
         parent::__construct(new \comuni\Comuni($db));
      }
      /**
      * Customizes select statement
      * @param Zend_Db_Select $select Zend Db Select
      * @param array $criteria Filtering criteria
      * @return Zend_Db_Select Select is expected
      */
    protected function customSelect( \Zend\Db\Sql\Select $select,array $criteria ) {
        $select ->columns(array('*',
                'provincia_sigla' => new \Zend\Db\Sql\Predicate\Expression('
                ( SELECT `provincia_sigla` FROM `province`  WHERE `province`.`provincia_cod`=`comuni`.`provincia_cod`) ')
                ));
       $select = $this->setFilter($select,$criteria);
       return $select;
    }
     /**
     * Count items
     * @return int
     */
    public function countAll($criteria = array()) {
      $select = $this->content->getTable()->getSql()->select()->columns(array(new \Zend\Db\Sql\Expression('COUNT(*)')));
      $select = $this->setFilter($select,$criteria);
      $statement = $this->content->getTable()->getSql()->prepareStatementForSqlObject($select);
      $results = $statement->execute();
      $resultSet = new \Zend\Db\ResultSet\ResultSet();
      $resultSet->initialize($results);
      $data = $resultSet->current()->getArrayCopy();
      return intval(array_pop($data));
    }
    /**
     * Sets the filter
     * @param \Zend\Db\Sql\Select $select
     * @param array $criteria
     * @return \Zend\Db\Sql\Select
     */
    private function setFilter ($select,$criteria) {
       if (array_key_exists('sSearch', $criteria) && $criteria['sSearch'] != '') {
          $select->where(' ( `denominazione_it` LIKE "'.addslashes($criteria['sSearch']).'%" OR `denominazione_de` LIKE "'.addslashes($criteria['sSearch']).'%") ');
       }
      return $select;
    }
}