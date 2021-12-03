<?php namespace Waka\Segator\Behaviors;

use Backend\Classes\ControllerBehavior;
use Waka\Segator\Classes\TagCreator;
use Waka\Segator\Models\Tag;
use Waka\Utils\Classes\DataSource;
use Session;

class ManualTagBehavior extends ControllerBehavior
{
    public $model;

    protected $tagBehaviorWidget;

    public function __construct($controller)
    {
        parent::__construct($controller);
        $modelClass = post('modelClass');
        if($modelClass) {
            $this->tagBehaviorWidget = $this->createTagBehaviorWidget($modelClass);
        }
        
    }


    public function onLoadTagBehaviorContentForm()
    {
        $modelClass = post('modelClass');
        $this->vars['modelClass'] = $modelClass;
        $this->vars['tagBehaviorWidget'] = $this->tagBehaviorWidget;
        return ['#popupActionContent' => $this->makePartial('$/waka/segator/behaviors/manualtagbehavior/_content.htm')];
    } 

    public function onTagBehaviorValidate() {$lotType = post('lotType');
        $modelClass = post('modelClass');
        $lotType = post('lotType');
        $listIds = null;
        if ($lotType == 'filtered') {
            $listIds = Session::get('lot.listId');
        } elseif ($lotType == 'checked') {
            $listIds = Session::get('lot.checkedIds');
        }
        Session::forget('lot.listId');
        Session::forget('lot.checkedIds');

        $mode = post('tagBehavior_array.add_remove');
        $tags = post('tagBehavior_array.tagList');

        $models = $modelClass::whereIn('id', $listIds)->get();

        
        foreach($tags as $tagId) {
            $tag = \Waka\Segator\Models\Tag::find($tagId);
            foreach($models as $model) {
                if($mode == 'add') {
                    $model->wakatags()->add($tag);
                }
                if($mode == 'remove') {
                    $model->wakatags()->remove($tag);
                }
            }
        }
        return \Redirect::refresh();


    }


    public function createTagBehaviorWidget($modelClass)
    {
        $config = $this->makeConfig('$/waka/segator/models/widgets/add_tag.yaml');
        $config->alias = 'tagBehaviorformWidget';
        $config->arrayName = 'tagBehavior_array';
        $config->model = new $modelClass;
        $widget = $this->makeWidget('Backend\Widgets\Form', $config);
        $widget->bindToController();
        return $widget;
    }



    

}
