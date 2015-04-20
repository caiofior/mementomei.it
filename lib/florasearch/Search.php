<?php
namespace florasearch;
/**
 * Search if flora taxa
 *
 * @author caiofior
 */
class Search {
    /**
    * Zend DB
    * @var Zend_Db
    */
    private $db;
    /**
     * Request parameters
     * @var array
     */
    private $request=array();
    /**
     * taxa reference
     * @var \flora\taxa\Taxa
     */
    private $content;
    /**
     * Reference to complete region Collection
     * @var \flora\region\RegionColl
     */
    private $regionColl;
    /**
     * Instantiates the search
     * @param \Zend\Db\Adapter\Adapter $db
     */
    public function __construct(\Zend\Db\Adapter\Adapter $db) {
        $this->db = $db;
        $this->content = new \flora\taxa\Taxa($this->db);
        $this->regionColl = new \flora\region\RegionColl($this->db);
        $this->regionColl->loadAll();
    }
    /**
     * Sets the request
     * @param array $request
     */
    public function setRequest (array $request) {
        $this->request = $request;
    }
    /**
     * Count all the items
     * @throws \Exception
     */
    public function getTaxaCountAll() {
        $select=$this->createSelect();
        $select->columns(array('count'=>new \Zend\Db\Sql\Expression('COUNT(taxa.id)')));
        try{
                $statement = $this->content->getTable()->getSql()->prepareStatementForSqlObject($select);
                $results = $statement->execute();
                $resultSet = new \Zend\Db\ResultSet\ResultSet();
                $resultSet->initialize($results);
                return $resultSet->current()->count;
        }
        catch (\Exception $e) {
               $mysqli = $this->db->getDriver()->getConnection()->getResource();  
               if (array_key_exists('firephp', $GLOBALS) && !headers_sent())
                   $GLOBALS['firephp']->error('Error in '. get_called_class().' on query '.$select->getSqlString($this->db->getPlatform()).' '.$e->getMessage().' '.$mysqli->errno.' '.$mysqli->error);
               throw new \Exception('Error in '. get_called_class().' on query '.$select->getSqlString($this->db->getPlatform()).' '.$e->getMessage().' '.$mysqli->errno.' '.$mysqli->error,1401301242);
        }
        
    }
    /**
     * Gets the taxa collection
     * @return \flora\taxa\TaxaColl
     */
    public function getTaxaColl() {
        $data = array();
        $select=$this->createSelect();
        $select->columns(array(
            '*',
            'taxa_kind_initials'=>new \Zend\Db\Sql\Expression('taxa_kind.initials'),
            'taxa_kind_id_name'=>new \Zend\Db\Sql\Expression('taxa_kind.name')
        ));
        if (
                array_key_exists('start', $this->request) &&
                $this->request['start']!= '' &&
                array_key_exists('pagelength', $this->request) &&
                $this->request['pagelength']!= ''
            ) {
             $select->offset($this->request['start']);
        }
        if (
                array_key_exists('pagelength', $this->request) &&
                $this->request['pagelength']!= ''
            ) {
             $select->limit($this->request['pagelength']);
        }
        try{
                $statement = $this->content->getTable()->getSql()->prepareStatementForSqlObject($select);
                $results = $statement->execute();
                $resultSet = new \Zend\Db\ResultSet\ResultSet();
                $resultSet->initialize($results);
                $data = $resultSet->toArray(); 
            }
            catch (\Exception $e) {
               $mysqli = $this->db->getDriver()->getConnection()->getResource();  
               if (array_key_exists('firephp', $GLOBALS) && !headers_sent())
                   $GLOBALS['firephp']->error('Error in '. get_called_class().' on query '.$select->getSqlString($this->db->getPlatform()).' '.$e->getMessage().' '.$mysqli->errno.' '.$mysqli->error);
               throw new \Exception('Error in '. get_called_class().' on query '.$select->getSqlString($this->db->getPlatform()).' '.$e->getMessage().' '.$mysqli->errno.' '.$mysqli->error,1401301242);
            }
        $taxaColl = new \flora\taxa\TaxaColl($this->db);
        foreach($data as $taxaData) {
            $taxa = $taxaColl->addItem();
            $taxa->setData($taxaData);
        }
        return $taxaColl;
    }
     /**
     * Gets a collection of regions
     * @return \flora\region\RegionColl
     * @throws \Exception
     */
    public function getRegionColl() {
        return $this->regionColl;
    }
    
    /**
     * Gets a collection of filtered regions
     * @return \flora\region\RegionColl
     * @throws \Exception
     */
    public function getFilteredRegionColl(){
        $select=$this->createSelect();
        $select->columns(array(
            'id'=>new \Zend\Db\Sql\Expression('taxa_region.id_region'),
            'name'=>new \Zend\Db\Sql\Expression('region.name'),
            'count'=>new \Zend\Db\Sql\Expression('COUNT(taxa_region.id_taxa)')
            ));
        $select->join('region', 'region.id=taxa_region.id_region',array(), \Zend\Db\Sql\Select::JOIN_LEFT);
        $select->group('taxa_region.id_region');
        try{
               $statement = $this->content->getTable()->getSql()->prepareStatementForSqlObject($select);
               $results = $statement->execute();
               $resultSet = new \Zend\Db\ResultSet\ResultSet();
               $resultSet->initialize($results);
               $data = $resultSet->toArray(); 
           }
           catch (\Exception $e) {
              $mysqli = $this->db->getDriver()->getConnection()->getResource();  
              if (array_key_exists('firephp', $GLOBALS) && !headers_sent())
                  $GLOBALS['firephp']->error('Error in '. get_called_class().' on query '.$select->getSqlString($this->db->getPlatform()).' '.$e->getMessage().' '.$mysqli->errno.' '.$mysqli->error);
              throw new \Exception('Error in '. get_called_class().' on query '.$select->getSqlString($this->db->getPlatform()).' '.$e->getMessage().' '.$mysqli->errno.' '.$mysqli->error,1401301242);
           }
        foreach($data as $regionData) {
            if ($regionData['name'] == '') continue;
            if (
                    array_key_exists('region', $this->request) && 
                    is_array($this->request['region']) && 
                    in_array($regionData['id'], $this->request['region'])
                ) {
                   $regionData['selected']=true;
                }
            foreach($this->regionColl->getItems() as $region) {
                if ($region->getData('id')==$regionData['id']) {
                    $region->setData($regionData);
                }
            }    
        }
        return $this->regionColl;
    }
    /**
     * Create the select
     * @return \Zend\Db\Sql\Select
     */
    private function createSelect() {
        $select = $this->content->getTable()->getSql()->select();
        $select->join('taxa_kind', 'taxa.taxa_kind_id=taxa_kind.id',array(), \Zend\Db\Sql\Select::JOIN_LEFT);
        $select->join('dico_item', 'taxa.id=dico_item.taxa_id',array(), \Zend\Db\Sql\Select::JOIN_LEFT);
        $select->join('taxa_attribute_value', 'taxa.id=taxa_attribute_value.id_taxa',array(), \Zend\Db\Sql\Select::JOIN_LEFT);
        $select->join('taxa_image', 'taxa.id=taxa_image.id_taxa',array(), \Zend\Db\Sql\Select::JOIN_LEFT);
        $select->join('taxa_region', 'taxa.id=taxa_region.id_taxa',array(), \Zend\Db\Sql\Select::JOIN_LEFT);
        if (array_key_exists('text', $this->request) && $this->request['text']!= '') {
            $select->where('
                MATCH (`taxa`.`name`,`taxa`.`description`) AGAINST ( "'.addslashes($this->request['text']).'" IN NATURAL LANGUAGE MODE) OR
                MATCH (`dico_item`.`text`) AGAINST ( "'.addslashes($this->request['text']).'" IN NATURAL LANGUAGE MODE) OR
                MATCH (`taxa_attribute_value`.`value`) AGAINST ( "'.addslashes($this->request['text']).'" IN NATURAL LANGUAGE MODE)
                ');            
        }
        if (
                array_key_exists('region', $this->request) && 
                is_array($this->request['region']) && 
                $this->regionColl->count() != sizeof($this->request['region'])) {
            $select->where('`taxa_region`.`id_region` IN ("'.implode('","',$this->request['region']).'")');
        }
        $select->where('
                (               
                    IFNULL(LENGTH(taxa.description),0)+
                    IFNULL((SELECT COUNT(`value`) FROM `taxa_attribute_value` WHERE `taxa_attribute_value`.`id_taxa`=`taxa`.`id`),0)+
                    IFNULL((SELECT COUNT(`filename`) FROM `taxa_image` WHERE `taxa_image`.`id_taxa`=`taxa`.`id`),0)+
                    IFNULL((SELECT COUNT(`id`) FROM `dico_item` WHERE `dico_item`.`parent_taxa_id`=`taxa`.`id`),0)
                ) > 0
             '); 
        return $select;
    }
}