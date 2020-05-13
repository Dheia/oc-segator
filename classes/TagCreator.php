<?php namespace Waka\Segator\Classes;

use Waka\Segator\Models\Tag;

class TagCreator
{

    private $dataSourceModel;
    private $dataSourceId;
    private $model;
    public $classCalculs;

    public function __construct()
    {

    }

    public function launch(Tag $tag)
    {
        $calculClass = new $tag->calculModel;
        $calculs = $tag->calculs;
        $ids = []; // list des ids du scope.
        foreach ($calculs as $calcul) {
            $calculName = $calcul['calculCode'];
            $ids = $calculClass->{$calculName}($calcul, $ids);
        }
        $models = new $tag->data_source->modelClass;
        trace_log($tag->data_source->modelClass);
        $models = $models::whereIn('id', $ids)->get();
        foreach ($models as $model) {
            trace_log($model->name);
            $model->taggable()->add($tag);
        }
    }

}
