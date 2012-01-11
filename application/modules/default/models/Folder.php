<?php

/** 
* @Entity 
* @Table(name="folder")
*/
class App_Model_Folder
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
    * @OneToMany(targetEntity="App_Model_Folder", mappedBy="parent")
    */
    private $subFolders;

    /**
    * @OneToMany(targetEntity="App_Model_Page", mappedBy="folder")
    */
    private $pages;

    /**
    * @OneToMany(targetEntity="App_Model_File", mappedBy="folder")
    */
    private $files;

    /**
    * @Column(type="integer")
    */    
    private $parent_id;

    /**
     * @ManyToOne(targetEntity="App_Model_Folder", inversedBy="subFolders")
     * @JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    public function __construct()
    {
        $this->subFolders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getSubFolders()
    {
        return $this->subFolders;
    }

    public function addToSubFolders(App_Model_Folder $folder)
    {
        $this->subFolders[] = $folder;
    }

    public function addToPages(App_Model_Page $page)
    {
        $this->pages[] = $page;
    }

    public function getPages()
    {
        return $this->pages;
    }

    public function addToFiles(App_Model_File $file)
    {
        $this->files[] = $files;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent(App_Model_Folder $parent)
    {
        $this->parent = $parent;
    }
}
