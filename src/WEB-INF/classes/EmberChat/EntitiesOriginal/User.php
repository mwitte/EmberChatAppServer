<?php

namespace EmberChat\EntitiesOriginal;

/**
 * @Entity @Table(name="user")
 */
class User extends AbstractEntity
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
     * @Column(type="string")
     * @var string
     */
    protected $auth;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $password;

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

    /**
     * @param string $auth
     */
    public function setAuth($auth)
    {
        $this->auth = $auth;
    }

    /**
     * @return string
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword($password)
    {
        return $this->password;
    }


    public function __sleep(){
        return array('id', 'name', 'auth', 'password');
    }

}