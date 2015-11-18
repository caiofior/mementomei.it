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
    * Deleted a beloved and its relations
    */
   public function delete() {
       if (array_key_exists('id', $this->data) && $this->data['id'] != '')
            $this->db->query('DELETE FROM `beloved_beloving` 
              WHERE `beloved_id`=' . intval($this->data['id'])
                    , \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
       parent::delete();
   }
   /**
    * Update the coordinates
    */
   private function updateCoordinates() {
       if (is_object($this->point) && $this->point instanceof \Point) {
           $this->data['main_place']=new \Zend\Db\Sql\Expression('PointFromText("POINT('.$this->point->y().' '.$this->point->x().')")');
       } else {
           $this->data['main_place']=new \Zend\Db\Sql\Expression('PointFromText("POINT(0 0)")');
       }
   }
   /**
     * Returns the associated collection of beloving profiles
     * @return \login\user\ProfileColl()
     */
    public function getBelovingColl() {
        $belovingColl = new \login\user\ProfileColl($this->db);
        if (array_key_exists('id', $this->data) && intval($this->data['id']) >0) {
            $resultSet = $this->db->query('SELECT `profile`.* FROM `profile` 
                    LEFT JOIN `beloved_beloving` ON `beloved_beloving`.`profile_id`=`profile`.`id`
                    WHERE `beloved_id`=' . intval($this->data['id'])
                        , \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
            $beloving = new \login\user\Profile($this->db);
            foreach ($resultSet->toArray() as $belovingData) {
                $newBeloving = clone $beloving;
                $newBeloving->setData($belovingData);
                $belovingColl->appendItem($newBeloving);
            }
        }
        return $belovingColl;
    }
    /**
     * Gets associated agency collection
     * @return \mementomei\agency\AgencyColl
     */
    public function getAgencyColl() {
        $agencyColl = new \mementomei\agency\AgencyColl($this->db);
        $agencyColl->loadAll(array('beloved_id'=>$this->data['id']));
        return $agencyColl;
    }
    /**
     * Gets associated graveyard collection
     * @return \mementomei\agency\GraveyardColl
     */
    public function getGraveyardColl() {
        $graveyardColl = new \mementomei\agency\GraveyardColl($this->db);
        $graveyardColl->loadAll(array('beloved_id'=>$this->data['id']));
        return $graveyardColl;
    }
    /**
     * Gets associated parlour collection
     * @return \mementomei\agency\ParlourColl
     */
    public function getParlourColl() {
        $parlourColl = new \mementomei\agency\ParlourColl($this->db);
        $parlourColl->loadAll(array('beloved_id'=>$this->data['id']));
        return $parlourColl;
    }
    /**
     * Sets the beloving associated with beloved
     * @param array $belovings
     */
    public function setBeloving(array $belovings) {
        if (!array_key_exists('id', $this->data) && $this->data['id'] == '') {
            return;
        }
        $this->db->query('DELETE FROM `beloved_beloving` 
          WHERE `beloved_id`=' . intval($this->data['id'])
                , \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        foreach ($belovings as $beloving) {
            $this->db->query('INSERT INTO `beloved_beloving` 
              (`beloved_id`,`profile_id`)
              VALUES
              (' . intval($this->data['id']) . ',"' . addslashes($beloving) . '")'
                    , \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        }
    }
    /**
     * Sets the graveyard associated with beloved
     * @param array $greveyards
     */
    public function setGraveyard(array $greveyards) {
        $storedAgencies = array();
        $storedAgencyResultset = $this->db->query('SELECT `agency_id` FROM `beloved_agency` 
          WHERE (SELECT `type` FROM `agency` WHERE `id` = `beloved_agency`.`agency_id`) = "graveyard" AND `beloved_id`=' . intval($this->data['id'])
                , \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        while ($storedAgencyResultset->valid()){
            $storedAgencies[] = $storedAgencyResultset->current()->agency_id;
            $storedAgencyResultset->next();
        }
        $this->setAgencies($greveyards,$storedAgencies);
    }
    /**
     * Sets the parlour associated with beloved
     * @param array $parlour
     */
    public function setParlour(array $parlours) {
        $storedAgencies = array();
        $storedAgencyResultset = $this->db->query('SELECT `agency_id` FROM `beloved_agency` 
          WHERE (SELECT `type` FROM `agency` WHERE `id` = `beloved_agency`.`agency_id`) = "parlour" AND `beloved_id`=' . intval($this->data['id'])
                , \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        while ($storedAgencyResultset->valid()){
            $storedAgencies[] = $storedAgencyResultset->current()->agency_id;
            $storedAgencyResultset->next();
        }
        $this->setAgencies($parlours,$storedAgencies);
    }
    /**
     * Sets the agency associated with beloved
     * @param array $agencies
     */
    public function setAgencies(array $agencies,$storedAgencies=null) {
        if (!array_key_exists('id', $this->data) || $this->data['id'] == ''){
            return;
        }
        if (!is_array($storedAgencies)) {
            $storedAgencies = array();
            $storedAgencyResultset = $this->db->query('SELECT `agency_id` FROM `beloved_agency` 
              WHERE `beloved_id`=' . intval($this->data['id'])
                    , \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
            while ($storedAgencyResultset->valid()){
                $storedAgencies[] = $storedAgencyResultset->current()->agency_id;
                $storedAgencyResultset->next();
            }
        }
        
        foreach(array_diff($storedAgencies, $agencies) as $agency) {
          $this->db->query('DELETE FROM `beloved_agency` 
          WHERE `beloved_id`=' . intval($this->data['id']).' AND `agency_id`='.intval($agency)
                , \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        }        
        foreach (array_diff($agencies,$storedAgencies)  as $agency) {
            $this->db->query('INSERT INTO `beloved_agency` 
              (`beloved_id`,`agency_id`,`datetime`)
              VALUES
              (' . intval($this->data['id']) . ',"' . addslashes($agency) . '",NOW())'
                    , \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        }        
    }
}