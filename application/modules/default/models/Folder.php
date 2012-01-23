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
    * @OneToMany(targetEntity="App_Model_Folder", mappedBy="parent", cascade={"ALL"})
    */
    private $subFolders;

    /**
    * @OneToMany(targetEntity="App_Model_Page", mappedBy="folder", cascade={"ALL"})
    */
    private $pages;

    /**
    * @OneToMany(targetEntity="App_Model_File", mappedBy="folder", cascade={"ALL"})
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

    public function isRoot()
    {
        return ((!$this->getParent()) && $this->name == 'Root');
    }

    public function getTotalChildCount(Doctrine\ORM\EntityManager $db)
    {
        return self::getTotalChildCountRecursive($this->id, $db);
    }

    private static function getTotalChildCountRecursive($folder_id, $db, array $results=null)
    {
$sql = <<<SQL
        SELECT COUNT(id) AS folder_count,
(SELECT COUNT(id) AS page_count FROM page WHERE folder_id = ?) AS page_count,
(SELECT COUNT(id) AS file_count FROM file WHERE folder_id = ?) AS file_count
FROM folder 
WHERE parent_id = ?
SQL;
        $result_mapping = new Doctrine\ORM\Query\ResultSetMapping();
        $result_mapping->addScalarResult('folder_count', 'folder_count');
        $result_mapping->addScalarResult('page_count', 'page_count');
        $result_mapping->addScalarResult('file_count', 'file_count');
        $query = $db->createNativeQuery($sql, $result_mapping);
        $query->setParameters(array(1=>$folder_id, 2=>$folder_id, 3=>$folder_id));
        $query_results = $query->getResult();
        $current_result = reset($query_results);
        if($results)
        {
            $results['folder_count'] += $current_result['folder_count'];
            $results['page_count'] += $current_result['page_count'];
            $results['file_count'] += $current_result['file_count'];
        }
        else
        {
            $results = $current_result;
        }   

        $result_mapping = new Doctrine\ORM\Query\ResultSetMapping();
        $result_mapping->addScalarResult('id', 'id');
        $query = $db->createNativeQuery('SELECT id FROM folder WHERE parent_id = ?', $result_mapping);
        $query->setParameters(array(1=>$folder_id));
        $query_results = $query->getResult();
        foreach($query_results as $r)
        {
            $results = self::getTotalChildCountRecursive($r['id'], $db, $results);
        }  

        return $results;
    }

    public function getAllChildFilePaths($base_path, Doctrine\ORM\EntityManager $db)
    {
        return self::getAllChildFilePathsRecursive($base_path, $this->id, $db);
    }

    private static function getAllChildFilePathsRecursive($base_path, $folder_id, $db, array $paths=null)
    {
        $result_mapping = new Doctrine\ORM\Query\ResultSetMapping();
        $result_mapping->addEntityResult('App_Model_File', 'f');
        $result_mapping->addFieldResult('f', 'id', 'id');   
        $result_mapping->addFieldResult('f', 'extension', 'extension');     
        $query = $db->createNativeQuery('SELECT id, extension FROM file WHERE folder_id = ?', $result_mapping);
        $query->setParameters(array(1=>$folder_id));
        $files = $query->getResult();       
        if(!$paths)
        {
            $paths = array();
        }
        foreach($files as $file)
        {
            $paths[] = $file->getFullPath($base_path);
        }
        unset($files);

        $result_mapping = new Doctrine\ORM\Query\ResultSetMapping();
        $result_mapping->addScalarResult('id', 'id');
        $query = $db->createNativeQuery('SELECT id FROM folder WHERE parent_id = ?', $result_mapping);
        $query->setParameters(array(1=>$folder_id));
        $query_results = $query->getResult();
        foreach($query_results as $r)
        {
            $paths = self::getAllChildFilePathsRecursive($base_path, $r['id'], $db, $paths);
        }  

        return $paths;
    }
}
