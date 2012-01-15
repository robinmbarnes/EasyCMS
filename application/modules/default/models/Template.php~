<?php

/** 
* @Entity 
* @Table(name="template")
*/
class App_Model_Template
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
    private $description;

    /**
    * @OneToMany(targetEntity="App_Model_Page", mappedBy="template")
    */
    private $pages;

    /**
    * @Column(type="text")
    */
    private $content;

    public function __construct()
    {
        $this->pages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getPages()
    {
        return $this->pages;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }
}
