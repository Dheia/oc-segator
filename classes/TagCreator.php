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
        $this->handle($tag);
    }

    public function fire($job, $tagId)
    {

        $tag = Tag::find($tagId);
        \Event::fire('job.start.tag', [$job, 'Tag : ' . $tag->name]);
        $this->handle($tag);

        if ($job) {
            \Event::fire('job.end.tag', [$job]);
            $job->delete();
        }

    }

    public function handle(Tag $tag)
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
            //trace_log("il y a de l'only tag on recherche le ou les tags prescedents");
            $tagIds = [];
            foreach ($tag->only_tag as $previousTag) {
                $tempIds = $models::TagFilter([$previousTag])->get()->pluck('id')->toArray();
                //trace_log($tempIds);
                if ($tempIds) {
                    $tagIds = array_merge($tagIds, $tempIds);
                }
            }
            $ids = array_unique($tagIds);
            //trace_log($ids);
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
