<?php

namespace Mnl\View\Helper;

use Mnl\View\HelperAbstract;

class FormHelper extends HelperAbstract
{
    private function __construct()
    {
    }

    public function textField($name, $value = '', array $attributes = array())
    {
        return $this->parseInputField('text', $name, $value, $attributes);
    }

    public function submitButton(
        $name = '',
        $value = '',
        array $attributes = array()
    ) {
        return $this->parseInputField('submit', $name, $value, $attributes);
    }

    public function passwordField($name, array $attributes = array())
    {
        return $this->parseInputField('password', $name, '', $attributes);
    }

    private function parseInputField($type, $name, $value, array $attributes)
    {
        $field = '<input ';
        $field .= 'type="'.$type.'" ';
        $field .= 'name="'.$name.'" ';
        if ($value != '') {
            $field .= 'value="'.$value.'" ';
        }
        foreach ($attributes as $attribute => $attributeValue) {
            $field .= $attribute.'="'.$attributeValue.'" ';
        }
        $field .= '/>';

        return $field;
    }

    public static function run($args)
    {
        $formHelper = new self();

        return $formHelper;
    }
}

