<?php

class EasyCMS_Form_Decorator_Composite extends Zend_Form_Decorator_Abstract
{
    public function buildLabel()
    {
        $element = $this->getElement();
        $label = $element->getLabel();
        if(empty($label))
        {
            return '';
        }
        if ($translator = $element->getTranslator()) 
        {
            $label = $translator->translate($label);
        }
        if ($element->isRequired()) {
            $label .= '*';
        }
        $label .= ':';
        return 
            '<div class="form-label-wrapper">'
            .$element->getView()->formLabel($element->getName(), $label)
            .'</div>'
        ;
    }
        
    public function buildInput()
    {
        $element = $this->getElement();
        $helper  = $element->helper;
        return 
        '<div class="form-element-wrapper">'
        . $element->getView()->$helper(
            $element->getName(),
            $element->getValue(),
            $element->getAttribs(),
            $element->options
        )
        .'</div>';
    }
        
    public function buildErrors()
    {
        $element  = $this->getElement();
        $messages = $element->getMessages();
        if (empty($messages)) {
            return '';
        }
        return '<div class="errors">' . $element->getView()->formErrors($messages) . '</div>';
    }

    public function buildDescription()
    {
        //Description is not shown on an element which has errors
        $element = $this->getElement();
        $messages = $element->getMessages();
        if(!empty($messages))
        {
            return '';
        }
        $desc = $element->getDescription();
        if(empty($desc)) {
            return '';
        }
        return '<div class="description"><div class="description-inner">' . nl2br($desc) . '</div></div>';
    }
    
    public function render($content)
    {
        $element = $this->getElement();
        if(!$element instanceof Zend_Form_Element) 
        {
            return $content;
        }
        if(null === $element->getView()) 
        {
            return $content;
        }
        $label = $this->buildLabel();
        $input = $this->buildInput();
        $errors = $this->buildErrors();
        $desc = $this->buildDescription();
        return 
            '<div class="form-element">'
            . $label
            . $input
            . $errors
            . $desc
            . '</div>'
        ;
        switch ($placement) {
            case (self::PREPEND):
                return $output . $separator . $content;
            case (self::APPEND):
            default:
                return $content . $separator . $output;
        }
    }
}
