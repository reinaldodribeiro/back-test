<?php

namespace App\Traits;


trait GenerateCSV
{
    private $nameFile;

    private $extension = '.csv';

    /**
     * @return string
     */
    public function getNameFile()
    {
        return $this->nameFile;
    }

    /**
     * @param string $nameFile
     */
    public function setNameFile($nameFile)
    {
        $this->nameFile = $nameFile.$this->extension;
    }

    protected function generateFile($message)
    {
        $exists = false;
        $this->setNameFile(date('d-m-Y H'));
        if(file_exists(base_path($this->getNameFile()))){
            $exists = true;
        }
        $file = fopen(base_path($this->getNameFile()), 'a+');
        if(!$exists){
            fwrite($file, "DATA;STEPS\n");
        }
        fwrite($file, $message."\n");
        fclose($file);

        return true;
    }

}
