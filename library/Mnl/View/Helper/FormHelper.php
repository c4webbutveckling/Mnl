<?php
/**
 * Form helper
 *
 * @category Mnl
 * @package  Mnl\View
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
namespace Mnl\View\Helper;

use Mnl\View\HelperAbstract;

/**
 * Form helper
 *
 * A collection of methods for outputting form elements
 *
 * Example for use in view file
 * <code>
 * $this->formHelper()->textField("foo", "bar");
 * </code>
 *
 * @category Mnl
 * @package  Mnl\View
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
class FormHelper extends HelperAbstract
{
    /**
     * Private constructor
     */
    private function __construct()
    {
    }

    /**
     * Text input
     *
     * @param string $name Name of input
     * @param string $value Value of input
     * @param array $attributes Key value array of attributes (class, id, ...)
     * @return string Html input element
     */
    public function textField($name, $value = '', array $attributes = array())
    {
        return $this->parseInputField('text', $name, $value, $attributes);
    }

    /**
     * Submit button
     *
     * @param string $name Name of input
     * @param string $value Value of input
     * @param array $attributes Key value array of attributes (class, id, ...)
     * @return string Html submit button
     */
    public function submitButton(
        $name = '',
        $value = '',
        array $attributes = array()
    ) {
        return $this->parseInputField('submit', $name, $value, $attributes);
    }

    /**
     * Password input
     *
     * @param string $name Name of input
     * @param array $attributes Key value array of attributes (class, id, ...)
     * @return string Html password element
     */
    public function passwordField($name, array $attributes = array())
    {
        return $this->parseInputField('password', $name, '', $attributes);
    }

    /**
     * Textarea
     *
     * @param string $name Name of input
     * @param string $value Value of input
     * @param array $attributes Key value array of attributes (class, id, ...)
     * @return string Html password element
     */
    public function textArea($name, $value = '', array $attributes = array())
    {
        $field = '<textarea ';
        $field .= 'name="'.$name.'" ';
        foreach ($attributes as $attribute => $attributeValue) {
            $field .= $attribute.'="'.$attributeValue.'" ';
        }
        $field .= '>'."\n";
        $field .= $value;
        $field .= '</textarea>';
        return $field;
    }

    /**
     * Create input field
     *
     * @param string $type Input type
     * @param string $name Input name
     * @param string $value Input Value
     * @param array $attributes Key value array of attributes (class, id, ...)
     * @return string Html input element
     */
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
        $field .= '/>'."\n";

        return $field;
    }

    /**
     * Runner
     *
     * Instantiates helper
     *
     * @param array $args Should be empty
     * @return FormHelper
     */
    public static function run($args)
    {
        $formHelper = new self();

        return $formHelper;
    }
}

