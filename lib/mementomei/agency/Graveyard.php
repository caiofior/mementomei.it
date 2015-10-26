<?php
namespace mementomei\agency;
/**
 * Graveyard class
 *
 * @author caiofior
 */
class Graveyard extends \mementomei\agency\Agency
{
    /**
     * Sets type to graveyard
     */
    public function insert() {
        $this->data['type']='graveyard';
        parent::insert();
    }
    /**
     * Sets type to graveyard
     */
    public function update() {
        $this->data['type']='graveyard';
        parent::update();
    }
}