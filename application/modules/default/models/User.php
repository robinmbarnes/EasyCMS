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
}