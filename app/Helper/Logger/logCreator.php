<?php

namespace app\Helper\Logger;

use  app\Helper\Logger\logger;

class logCreator extends logger
{
    public function writeLog()
    {
        $str = '['.$_SERVER['REMOTE_ADDR'].'=>'.$_COOKIE['user'].'] '.date('Y-m-d H:i:s\: ', time()).str_replace('/<\w+>/', '', $this->returnRunerFunc()).preg_replace('/[ \\t]+/', ' ', $this->context)."\n\n\n";

        if ($f = $this->checkfile()) {
            fwrite($f, $str.$this->content);
            fclose($f);
        }
    }

    protected function checkfile()
    {
        if (!file_exists($this->file)) {
            return false;
        }

        if ($this->way) {
            $this->content = file_get_contents($this->file);
        }

        if (filesize($this->file) / 1024 > $this->maxsize || $this->way) {
            $f = fopen($this->file, 'w');
            fwrite($f, '');
            fclose($f);
        }

        return fopen($this->file, 'a');
    }
}
