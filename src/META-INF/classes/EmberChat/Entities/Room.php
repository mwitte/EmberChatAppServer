<?php

namespace EmberChat\Entities;

/**
 * @Entity @Table(name="room")
 */
class Room
{


    /**
     * @Id @Column(type="string")
     * @var string
     */
    protected $id;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $name;

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}