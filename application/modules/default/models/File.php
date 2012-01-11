<?php

/** 
* @Entity 
* @Table(name="file")
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
    * @Column(type="string")
    */    
    private $extension;

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

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getFolder()
    {
        return $this->folder;
    }

    public function setFolder(App_Model_Folder $folder)
    {
        $this->folder = $folder;
    }

    public function getExtension()
    {
        return $this->extension;
    }

    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    public function getFullPath($base_path)
    {
        assert('$this->id > 0');
        return $base_path .'/' . $this->id . (strlen($this->getExtension()) ? '.' . $this->getExtension() : '');
    }
}

