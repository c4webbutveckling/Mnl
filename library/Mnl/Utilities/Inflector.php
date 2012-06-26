<?php
namespace Mnl\Utilities;
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
            if ($input[$i] == '_') {
                continue;
            } elseif ($input[$i] === strtoupper($input[$i]) && $i > 0) {
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
        if (preg_match('/(alias|status)$/i', $input)) {
            $input = preg_replace('/(alias|status)$/i', '\1es', $input);
        } elseif (preg_match('/.s$/', $input)) {
            return $input;
        } elseif (preg_match('/([^aeiouy]|qu)y$/i', $input)) {
            $input = preg_replace('/([^aeiouy]|qu)y$/i', '\1ies', $input);
        } else {
            $input = $input.'s';
        }

        return $input;
    }

    public function tableize($input)
    {
        $input = preg_replace('/[^A-Za-z0-9]/', '', $input);

        return $this->underscoreize($this->pluralize($input));
    }
}

