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

    public function onCallAllCalculs($model = null)
    {
        if (!$model) {
            $model = post('model');
        }
        $ds = new DataSource($model, 'class');
        $dataSourceCode = $ds->code;

        $allTags = Tag::where('data_source', $dataSourceCode)->orderBy('sort_order')->get();
        foreach ($allTags as $tag) {
            $jobId = \Queue::push('Waka\Segator\Classes\TagCreator@fire', $tag->id);
            \Event::fire('job.create.tag', [$jobId, 'Tag en attente de calcul']);
        }
        \Flash::info("Le calcul des tags est en cours, vous pouvez verifier la progression des calculs dans REGLAGES->TACHES");

    }

    public function onCallManualCalculs()
    {

        $model_id = post('model_id');

        $tag = Tag::find($model_id);

        $calc = new TagCreator();
        $calc = $calc->launch($tag);

    }

}
