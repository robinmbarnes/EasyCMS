<?php

class App_Model_TemplateParser
{
    protected $raw_text;

    public function __construct($raw_text)
    {
        $this->raw_text = $raw_text;
    }

    public function parse()
    {
        $sections = array();
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

            $sections[] = $section;
        } 
        return $sections;
    }

    public function render(\Doctrine\Common\Collections\ArrayCollection $sections)
    {
        $text = $this->raw_text;
        foreach($sections as $section)
        {
            if(!preg_match('|<easy_cms_section name="'.$section->getName().'">(.+?)</easy_cms_section>|', $text))
            {
                throw new App_Model_TemplateParserException('Section tag for section: ' . $section->getName() . ' not found');
            }
            $css_id_name = preg_replace('/[^0-9a-zA-Z]/', '-', $section->getName());    
            $text = preg_replace(
                '|<easy_cms_section name="'.$section->getName().'">(.+?)</easy_cms_section>|',            
                '<div id="easy_cms_section_'.$css_id_name.'">'.$section->getContent().'</div>',
                $text
            );
        }
        if(preg_match('|<easy_cms_section name="(.+?)">(.+?)</easy_cms_section>|', $text))
        {
            throw new App_Model_TemplateParserException('Missing section content');
        }
        echo $text;
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
