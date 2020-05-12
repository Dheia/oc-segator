<?php namespace Waka\Utils\Classes;

class BaseFunction
{
    public function setModel($model)
    {
        $this->model = $model;
    }

    public function getTagsList()
    {
        $data = [];
        $functions = $this->listTagAttributes();
        foreach ($functions as $key => $values) {
            $data[$key] = $values['name'];
        }
        return $data;
    }
    public function getTagAttribute($value)
    {
        $functions = $this->listFunctionAttributes();
        foreach ($functions as $key => $values) {
            if ($key == $value) {
                return $values['attributes'] ?? null;
            }
        }

    }
}
