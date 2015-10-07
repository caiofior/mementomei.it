<?php
namespace mementomei;
/**
 * Deceased class
 *
 * @author caiofior
 */
class Beloved extends \Content
{
     /**
     * GeoPHP Point reference
     * @var /Point
     */
    private $point;
   /**
    * Associates the database table
    * @param \Zend\Db\Adapter\Adapter $db
    */
   public function __construct(\Zend\Db\Adapter\Adapter $db) {
      parent::__construct($db, 'beloved');
   }
   /**
    * Load data from id
    * @param int $id
    */
   public function loadFromId($id) {
       $select = $this->table->getSql()->select();
       $select->columns(array('*',
           'point'=>new \Zend\Db\Sql\Expression('asText(main_place)')
           ));
       $select->where(array($this->primary=>$id));
       $data = $this->table->select($this->table->selectWith($select))->current();
       if (is_object($data)) {
            $this->data = $data->getArrayCopy();
            $this->rawData = $this->data;
        }
        else {
           $mysqli = $this->table->getAdapter()->getDriver()->getConnection()->getResource();  
           throw new \Exception('Error on query '.$select->getSqlString($this->table->getAdapter()->getPlatform()).' '.$mysqli->errno.' '.$mysqli->error,1401301242);
       }

       
       $this->getCoordinates();
   }
    /**
    * Gets coordinates from point data
    */
   private function getCoordinates() {
       if (
               array_key_exists('point', $this->rawData) &&
               $this->rawData['point'] != '' &&
               $this->rawData['point'] != 'POINT(0 0)'
        ) {
         $this->point = \geoPHP::load($this->rawData['point'],'wkt');
       }
   }
   /**
    * Inserts the data and add the coordinates
    */
   public function insert() {
       $this->updateCoordinates();
       $this->data['creation_datetime'] = date('Y-m-d H:i:s');
       $this->data['change_datetime'] = date('Y-m-d H:i:s');
       parent::insert();
   }
   /**
    * Updates data and add the coordinates
    */
   public function update() {
        if (array_key_exists('main_place',$this->data)) {
           unset($this->data['main_place']);
       }
       $this->updateCoordinates();
       $this->data['change_datetime'] = date('Y-m-d H:i:s');
       parent::update();
   }
   /**
    * Update the coordinates
    */
   private function updateCoordinates() {
       if (is_object($this->point) && $this->point instanceof \Point) {
           $this->data['main_place']=new \Zend\Db\Sql\Expression('PointFromText("POINT('.$this->point->x().' '.$this->point->y().')")');
       } else {
           $this->data['main_place']=new \Zend\Db\Sql\Expression('PointFromText("POINT(0 0)")');
       }
   }
}