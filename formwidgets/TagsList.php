<?php namespace Waka\Segator\FormWidgets;

use Backend\Classes\FormWidgetBase;
use Waka\Segator\Classes\TagCreator;

/**
 * TagsList Form Widget
 */
class TagsList extends FormWidgetBase
{
    /**
     * @inheritDoc
     */
    protected $defaultAlias = 'waka_segator_tags_list';

    /**
     * @inheritDoc
     */
    public function init()
    {
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('tagslist');
    }

    /**
     * Prepares the form widget view data
     */
    public function prepareVars()
    {
        $noFunction = true;
        $tagCreator = new TagCreator($this->model);
        $classCalculs = $tagCreator->classCalculs;
        if ($classCalculs) {
            $noFunction = false;
        }
        $this->jsonValues = $this->getLoadValue();
        $this->vars['noFunction'] = $noFunction;
        $this->vars['classCalculs'] = $classCalculs;
        $this->vars['name'] = $this->formField->getName();
        //trace_log($this->getLoadValue());
        $this->vars['values'] = $this->getLoadValue();
        $this->vars['model'] = $this->model;
    }

    /**
     * @inheritDoc
     */
    public function loadAssets()
    {
        $this->addCss('css/tagslist.css', 'waka.segator');
        $this->addJs('js/tagslist.js', 'waka.segator');
    }

    /**
     * @inheritDoc
     */
    public function getSaveValue($value)
    {
        return \Backend\Classes\FormField::NO_SAVE_DATA;
    }

    public function onShowTagCalculs()
    {
        $tagCreator = new TagCreator($this->model);

        $classCalculs = $tagCreator->classCalculs;

        //liste des fonctions de la classe
        $this->vars['calculTagList'] = new $classCalculs->getTagsList();

        return $this->makePartial('popup');

    }
}
