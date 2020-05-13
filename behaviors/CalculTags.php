<?php namespace Waka\Segator\Behaviors;

use Backend\Classes\ControllerBehavior;
use Waka\Segator\Classes\TagCreator;
use Waka\Segator\Models\Tag;

class CalculTags extends ControllerBehavior
{
    public $model;

    public function __construct($controller)
    {
        parent::__construct($controller);
        $this->model = $controller->formGetModel();

    }

    //ci dessous tous les calculs pour permettre l'import excel.

    public function onCallCalculs()
    {
        $model_id = post('model_id');
        $tag = Tag::find($model_id);

        $calc = new TagCreator();
        $calc = $calc->launch($tag);

    }

}
