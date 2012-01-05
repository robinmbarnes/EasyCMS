<?php

/** 
* @Entity 
* @Table(name="page")
*/
class App_Model_Page
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
     * @ManyToOne(targetEntity="App_Model_Folder", inversedBy="pages")
     * @JoinColumn(name="folder_id", referencedColumnName="id")
     */
    private $folder;

    /**
     * @ManyToOne(targetEntity="App_Model_Template", inversedBy="pages")
     * @JoinColumn(name="template_id", referencedColumnName="id")
     */
    private $template;

    /**
    * @OneToMany(targetEntity="App_Model_Section", mappedBy="page")
    */
    private $sections;

    public function __construct()
    {
        $this->sections = new \Doctrine\Common\Collections\ArrayCollection();        
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSections()
    {
        return $this->sections;
    }

    public function getFolder()
    {
        return $this->folder;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function addToSections(App_Model_Section $section)
    {
        $this->sections[] = $section;
    }

    public function setSections(array $sections)
    {
        $this->sections->clear();
        foreach($sections as $section)
        {
            $this->addToSections($section);
        }
    }
}

