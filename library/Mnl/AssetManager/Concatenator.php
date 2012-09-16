<?php

namespace Mnl\AssetManager;

class Concatenator
{
    public function __construct($sourceFiles, $path = '', $targetFilename)
    {
        $this->prepareSourceFiles($sourceFiles);
        $this->targetPath = $path;

        $this->targetFilename  = $targetFilename;
    }

    public function getFileNames()
    {
        if (!file_exists($this->targetPath.$this->targetFilename)) {
            $this->concatenateFiles();
        }
        $result = $this->nonProcessable;
        $result[] = $this->targetFilename;
        return $result;
    }

    private function concatenateFiles()
    {
        $result = '';
        $files = "";
        foreach ($this->processable as $file) {
            if(\Mnl\Url::isRelative($file)) {
                $path = $this->targetPath.$file;
            } else {
                $path = $file;
            }
            $files .= "//".$file."\n";
            $contents = file_get_contents($path);
            $contents = str_replace("\r\n", "\n", $contents);
            $result .= "\n\n;".$contents;

        }
        $this->write($files."\n".$result);
    }

    private function write($content)
    {
        file_put_contents($this->targetPath.$this->targetFilename, $content);
    }

    private function prepareSourceFiles($files)
    {
        $nonProcessable = array();
        $processable = array();
        foreach ($files as $file) {
            $processable[] = $file;
        }

        $this->nonProcessable = $nonProcessable;
        $this->processable = $processable;
    }
}
