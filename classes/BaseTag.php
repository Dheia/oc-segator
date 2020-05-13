<?php namespace Waka\Segator\Classes;

class BaseTag
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
    public function getTagCalculAttribute($value)
    {
        $functions = $this->listTagAttributes();
        foreach ($functions as $key => $values) {
            if ($key == $value) {
                return $values['attributes'] ?? null;
            }
        }

    }
    public function getTagCalculName($value)
    {
        $functions = $this->listTagAttributes();
        foreach ($functions as $key => $values) {
            if ($key == $value) {
                return $values['name'] ?? null;
            }
        }

    }
}
