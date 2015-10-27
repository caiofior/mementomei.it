<?php
namespace mementomei\agency;
/**
 * Graveyard class
 *
 * @author caiofior
 */
class Parlour extends \mementomei\agency\Agency
{
    /**
     * Sets type to graveyard
     */
    public function insert() {
        $this->data['type']='parlour';
        parent::insert();
    }
    /**
     * Sets type to graveyard
     */
    public function update() {
        $this->data['type']='parlour';
        parent::update();
    }
}