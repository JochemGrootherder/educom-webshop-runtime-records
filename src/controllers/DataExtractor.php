<?php

class DataExtractor
{
    public function getDataFromPost($metaArray)
    {
        $formResults = [];
        foreach($metaArray as $key => $metaData)
        {
            $value = $this->getPostVar($key);
            $formResult = ['value' => $value, 'error' => ''];
            $formResults[$key] = $formResult;
        }
        return $formResults;
    }

    public function getArrayVar($array, $key, $default="")
    {
        if(!isset($array[$key]))
        {
            return $default;
        }
        $value = $array[$key];
        if(getType($value) == 'array')
        {
            $value = implode('||', $value);
        }
        $value = trim($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value);
        return $value;
    }

    public function getPostVar($key, $default="")
    {
        return $this->getArrayVar($_POST, $key, $default);
    }

    public function getUrlVar($key, $default="")
    {
        return $this->getArrayVar($_GET, $key, $default);
    }
}