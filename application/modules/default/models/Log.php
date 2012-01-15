<?php

/** 
* @Entity 
* @Table(name="log")
*/
class App_Model_Log
{

    /**
    * @Id @Column(type="integer")
    * @GeneratedValue
    */
    private $id;

    /**
     * @ManyToOne(targetEntity="App_Model_Page", inversedBy="logEntries")
     * @JoinColumn(name="page_id", referencedColumnName="id")
     */
    private $page;

    /**
    * @Column(type="datetime")
    */
    private $logged_timestamp;

    public function __construct()
    {     
        
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function setPage(App_Model_Page $page)
    {
        $this->page = $page;
    }

    public function getLoggedTimestamp()
    {
        return $this->logged_timestamp;
    }
}

