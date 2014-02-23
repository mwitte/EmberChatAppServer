<?php

namespace EmberChat\Entities\Original;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 *
 * @MappedSuperclass
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
    protected $forename;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $surname;

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
     * @Column(type="boolean")
     * @var boolean
     */
    protected $admin;

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
     * @param string $forename
     */
    public function setForename($forename)
    {
        $this->forename = $forename;
    }

    /**
     * @return string
     */
    public function getForename()
    {
        return $this->forename;
    }

    /**
     * @param string $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
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
        $this->password = hash('sha256', $password);
    }

    /**
     * @return string
     */
    public function getPassword($password)
    {
        return $this->password;
    }

    /**
     * @param boolean $admin
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;
    }

    /**
     * @return boolean
     */
    public function getAdmin()
    {
        return $this->admin;
    }


    public function __sleep(){
        return array('id', 'forename', 'surname', 'auth', 'password', 'admin');
    }

}