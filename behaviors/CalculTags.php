<?php namespace Waka\Segator\Behaviors;

use Backend\Classes\ControllerBehavior;
use Waka\Segator\Classes\TagCreator;
use Waka\Segator\Models\Tag;
use Waka\Utils\Classes\DataSource;

class CalculTags extends ControllerBehavior
{
    public $model;

    public function __construct($controller)
    {
        parent::__construct($controller);
        $this->model = $controller->formGetModel();

    }

    //ci dessous tous les calculs pour permettre l'import excel.

    public function onCallTagCalculsModel($model = null)
    {
        if (!$model) {
            $modelClass = post('modelClass');
        }
        $ds = new DataSource($modelClass, 'class');
        $dataSourceCode = $ds->code;

        $allTagsId = Tag::where('data_source', $dataSourceCode)->orderBy('sort_order')->pluck('id');
        trace_log($allTagsId);
        foreach ($allTagsId as $tagId) {
            $tag = TagCreator::find($tagId)->calculate();
        }
        return $this->makePartial('$/waka/segator/behaviors/calcultags/_confirm.htm');

        //\Flash::info("Le calcul des tags est en cours, vous pouvez verifier la progression des calculs dans REGLAGES->TACHES");

    }

    public function onCallTagCalculsAll($model = null)
    {
        $allTags = Tag::orderBy('sort_order')->pluck('id');
        foreach ($allTags as $tag) {
            $tag = TagCreator::find($tag->id)->calculate();
        }
        //\Flash::info("Le calcul des tags est en cours, vous pouvez verifier la progression des calculs dans REGLAGES->TACHES");

    }

    public function onCallManualCalculs()
    {

        $model_id = post('model_id');

        $tag = Tag::find($model_id);

        $calc = new TagCreator();
        $calc = $calc->launch($tag);

    }

}
