<?php

/** 
* @Entity 
* @Table(name="section")
*/
class App_Model_Section
{
    /**
    * @Id @Column(type="integer")
    * @GeneratedValue
    */
    private $id;

    /**
     * @ManyToOne(targetEntity="App_Model_Page", inversedBy="sections")
     * @JoinColumn(name="page_id", referencedColumnName="id")
     */
    private $page;

    /**
    * @Column(type="string")
    */
    private $name;

    /**
    * @Column(type="text")
    */
    private $content;

    /**
    * @Column(type="text")
    */
    private $description;

    public function __construct()
    {
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

    public function getPage()
    {
        return $this->page;
    }

    public function setPage(App_Model_Page $page)
    {
        $this->page = $page;
    }

    public function getContent()
    {
        return $this->content;
    }
    
    public function setContent($content)
    {
        $this->content = $content;
    }

    public function render()
    {
        echo $this->getContent();
    }

    public function renderForEdit()
    {
        if(empty($this->content))
        {
            return $this->getDescription() . ' (This section is currently empty)';
        }
        return $this->content;
    }
}
