<?php

class App_Model_TemplateParser
{
    protected $raw_text;
    protected $sections = array();

    public function __construct($raw_text)
    {
        $this->raw_text = file_get_contents($raw_text);
    }

    public function parse()
    {
        $matches = array();
        preg_match_all('|<easy_cms_section.*?</easy_cms_section>|', $this->raw_text, $matches);
        foreach($matches as $section_tag)
        {
            $inner_matches = array();
            if(!preg_match('|<easy_cms_section name="(.+?)">(.+?)</easy_cms_section>|', $section_tag, $inner_matches))
            {
                throw new App_Model_TemplateParserException('Invalid section tag: ' .$section_tag);
            }
            $section = new App_Model_Section();
            $section->setName($inner_matches[1]);
            $section->setDescription($inner_matches[2]);

            if(!$this->isSectionNameUnique($section))
            {
                throw new App_Model_TemplateParserException('Section name: ' . $section->getName() . ' is not unique');
            }            

            $this->sections[] = $section;
        } 
    }

    public function getSections()
    {
        return $this->sections;
    }

    public function isSectionNameUnique(App_Model_Section $section)
    {
        foreach($this->sections as $s)
        {
            if($s->getName() == $section->getName())
            {
                return false;
            }   
        }
        return true;
    }
}
