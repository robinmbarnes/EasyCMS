<?php

class App_Model_TemplateParser
{
    protected $template;

    public function __construct(App_Model_Template $template)
    {
        $this->template = $template;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function generateSections()
    {
        $sections = array();
        $matches = array();
        preg_match_all('|<easy_cms_section.*?</easy_cms_section>|', $this->template->getContent(), $matches);
        $section_matches = (isset($matches[0]) ? $matches[0] : array());
        foreach($section_matches as $section_tag)
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

    public function renderForEdit($sections)
    {
        $text = $this->template->getContent();

        //Insert editor css tag
        if(!preg_match('|<head.*?>.*?</head>|msU', $text))
        {
            throw new App_Model_TemplateParserException('Missing html head tags');
        }
       
        $text = preg_replace('|(<head.*?>)(.*?</head>)|msU', 
            '$1'
            ."\n" 
            . '<link rel="stylesheet" media="all" type="text/css" href="/admin/stylesheets/jhtml/jHtmlArea.css" />'
            ."\n" 
            .'$2', 
            $text
        );

        //Sections
        foreach($sections as $section)
        {
            if(!preg_match('|<easy_cms_section name="'.$section->getName().'">(.+?)</easy_cms_section>|', $text))
            {
                throw new App_Model_TemplateParserException('Section tag for section: ' . $section->getName() . ' not found');
            }
            $css_id_name = preg_replace('/[^0-9a-zA-Z]/', '-', $section->getName());    
            $text = preg_replace(
                '|<easy_cms_section name="'.$section->getName().'">(.+?)</easy_cms_section>|',            
                '<div class="easy_cms_editable_section" style="position:relative;" id="easy_cms_section_'.$css_id_name.'">'.$section->renderForEdit().'</div>',
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
