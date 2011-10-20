<?php
namespace Mnl\ActiveRecord;
class Inflector
{

    public function __construct()
    {
    }

    public function camelize($input, $firstLowerCase = true)
    {
        $output = '';
        foreach (explode('_', $input) as $part) {
            $output .= ucwords($part);
        }

        if ($firstLowerCase) {
            $output = lcfirst($output);
        }
        return $output;
    }

    public function underscoreize($input)
    {
        $underscored = '';
        for ($i = 0; $i < strlen($input); $i++) {
            if ($input[$i] === strtoupper($input[$i]) && $i > 0) {
                $underscored .= "_".$input[$i];
            } else {
                $underscored .= $input[$i];
            }
        }
        $underscored = strtolower($underscored);
        return $underscored;
    }

    public function pluralize($input)
    {
        $input = $input.'s';
        return $input;
    }

    public function tableize($input)
    {
        return $this->underscoreize($this->pluralize($input));
    }
}

