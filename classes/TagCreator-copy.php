<?php namespace Waka\Segator\Classes;

use Waka\Segator\Models\Tag;
use Waka\Utils\Classes\DataSource;

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

        $ds = new DataSource($tag->data_source_id, 'id');
        $models = new $ds->class;
        $morphName = $models->getMorphClass();

        //suppresion de ce tag pour tt les model de ce type
        \DB::table('waka_segator_taggables')->where('taggable_type', $morphName)->where('tag_id', $tag->id)->delete();

        $ids = []; // list des ids du scope. il va diminuer à chaque calcul + l'only tag initial si existe.

        //Filtrage des modèles en fonction des parents
        if ($tag->parent_incs) {
            //trace_log("il y a de l'only tag on recherche le ou les tags prescedents");
            $tagIds = [];
            foreach ($tag->parent_incs as $previousTag) {
                $tempIds = $models::TagFilter([$previousTag])->get()->pluck('id')->toArray();
                //trace_log($tempIds);
                if ($tempIds) {
                    $tagIds = array_merge($tagIds, $tempIds);
                }
            }
            $ids = array_unique($tagIds);
        }
        foreach ($calculs as $calcul) {
            $calculName = $calcul['calculCode'];
            $ids = $calculClass->{$calculName}($calcul, $ids);
        }
        $models = new $ds->class;
        $models = $models::whereIn('id', $ids)->get();
        foreach ($models as $model) {
            $model->wakatags()->add($tag);
        }
    }

}
