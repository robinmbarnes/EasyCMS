<?php

/** 
* @Entity 
* @Table(name="page")
*/
class App_Model_File
{

    /**
    * @Id @Column(type="integer")
    * @GeneratedValue
    */
    private $id;

    /**
    * @Column(type="string")
    */    
    private $name;

    /**
     * @ManyToOne(targetEntity="App_Model_Folder", inversedBy="files")
     * @JoinColumn(name="folder_id", referencedColumnName="id")
     */
    private $folder;

    public function __construct()
    {     
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getFolder()
    {
        return $this->folder;
    }
}

