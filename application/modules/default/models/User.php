<?php

/** 
* @Entity 
* @Table(name="user")
*/
class App_Model_User
{
    /**
    * @Id @Column(type="integer")
    * @GeneratedValue
    */
    private $id;

    /**
    * @Column(type="string")
    */
    private $email;

    /**
    * @Column(type="string")
    */
    private $password;

    /**
    * @Column(type="boolean")
    */
    private $is_super_admin;

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $this->encryptPassword($password);
    }

    public function doesPasswordEqual($password)
    {
        return ($this->encryptPassword($password) == $this->getPassword());
    }

    private function encryptPassword($password)
    {
        return md5($password);
    }

    public function getIsSuperAdmin()
    {
        return $this->is_super_admin;
    }
        
    public function setIsSuperAdmin($is_super_admin)
    {
        $this->is_super_admin = $is_super_admin;
    }
}
