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

        $models = new $tag->data_source->modelClass;

        $morphName = $models->getMorphClass();

        //suppresion de ce tag pour tt les model de ce type
        \DB::table('waka_segator_taggables')->where('taggable_type', $morphName)->where('tag_id', $tag->id)->delete();

        $ids = []; // list des ids du scope. il va diminuer à chaque calcul + l'only tag initial si existe.

        //Filtrage des modèles en fonction des only_tag
        if ($tag->only_tag) {
            trace_log("il y a de l'only tag on recherche le ou les tags prescedents");
            $ids = $models::TagFilter([$tag->only_tag])->get()->pluck('id');
        }
        foreach ($calculs as $calcul) {
            $calculName = $calcul['calculCode'];
            $ids = $calculClass->{$calculName}($calcul, $ids);
        }
        $models = new $tag->data_source->modelClass;
        $models = $models::whereIn('id', $ids)->get();
        foreach ($models as $model) {
            $model->taggables()->add($tag);
        }
    }

}
