<?php namespace Waka\Segator\FormWidgets;

use Backend\Classes\FormWidgetBase;

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
        $classCalculs = $this->model->calculModel;
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
        $classCalculs = new $this->model->calculModel;
        //liste des fonctions de la classe
        $this->vars['calculTagList'] = $classCalculs->getTagsList();

        return $this->makePartial('popup');

    }

    public function onChooseCalcul()
    {
        //recuperation de la fonction
        $calculCode = post('calculCode');

        //recuperation de la classe function du data_source et des attributs de la fonction
        $classCalculs = new $this->model->calculModel;
        $attributes = $classCalculs->getTagCalculAttribute($calculCode);

        //création du widget
        $attributeWidget = $this->createFormWidget();
        //ajout des whamps via les attributs

        $attributeWidget->getField('name')->value = $classCalculs->getTagCalculName($calculCode);

        if ($attributes) {
            foreach ($attributes as $key => $value) {
                //trace_log($value['options'] ?? null);
                $attributeWidget->addFields([$key => $value]);
            }

        }

        $this->vars['attributeWidget'] = $attributeWidget;

        //trace_log($attributes);
        $this->vars['attributes'] = $attributes;
        return [
            '#calculAttributes' => $this->makePartial('attributes'),
        ];

    }

    public function onCreateCalculTagValidation()
    {
        $uniqueId = uniqid();
        //mis d'en une collection des données existantes
        //trace_log(post());
        $data;
        $modelValues = $this->getLoadValue();
        if ($modelValues && count($modelValues)) {
            $datas = new \October\Rain\Support\Collection($modelValues);
            // foreach ($datas as $key => $data) {
            //     if ($data['calculCode'] == post('calculCode')) {
            //         throw new \ApplicationException('Il existe déjà un calcul de ce type. ');
            //     }
            // }

        } else {
            $datas = new \October\Rain\Support\Collection();
        }

        //preparatio de l'array a ajouter
        $widgetArray = post('attributes_array');
        //ajout du code qui n'est pas dans le widget_array
        $widgetArray['calculCode'] = post('calculCode');
        $widgetArray['tagId'] = $uniqueId;
        $datas->push($widgetArray);

        //enregistrement du model
        $field = $this->fieldName;
        $this->model[$field] = $datas;
        $this->model->save();

        //rafraichissement de la liste
        return [
            '#list' => $this->makePartial('list', ['values' => $datas]),
        ];
    }

    public function onUpdateFunction()
    {

        $tagId = post('tagId');

        $modelValues = $this->getLoadValue();
        //trace_log($modelValues);
        $datas = new \October\Rain\Support\Collection($modelValues);
        $data = $datas->where('tagId', $tagId)->first();

        //trace_log($data);

        $calculCode = $data['calculCode'];

        $classCalculs = new $this->model->calculModel;
        $attributes = $classCalculs->getTagCalculAttribute($calculCode);

        //trace_log($data);

        //création du widget
        $attributeWidget = $this->createFormWidget();
        $attributeWidget->getField('name')->value = $data['name'];

        if ($attributes) {
            foreach ($attributes as $key => $value) {
                $attributeWidget->addFields([$key => $value]);
                $type = $value['type'] ?? false;
                if ($type == 'taglist') {
                    $val = $data[$key] ?? [];
                    $attributeWidget->getField($key)->value = implode(",", $val);
                } else {
                    $attributeWidget->getField($key)->value = $data[$key] ?? null;
                }

            }

        }

        $this->vars['calculCode'] = $calculCode;
        $this->vars['attributeWidget'] = $attributeWidget;
        $this->vars['tagId'] = $tagId;

        return $this->makePartial('popup_update');

    }

    public function onDeleteFunction()
    {

        $tagId = post('tagId');
        $datas = $this->getLoadValue();

        $updatedDatas = [];
        foreach ($datas as $key => $data) {
            if ($data['tagId'] != $tagId) {
                $updatedDatas[$key] = $data;
            }
        }

        //enregistrement du model
        $field = $this->fieldName;
        $this->model[$field] = $updatedDatas;
        $this->model->save();

        return [
            '#list' => $this->makePartial('list', ['values' => $updatedDatas]),
        ];

    }

    public function onUpdateTagCalculValidation()
    {
        $tagId = post('tagId');
        $calculCode = post('calculCode');

        if (!$tagId) {
            return;
        }

        //mis d'en une collection des données existantes
        $datas = $this->getLoadValue();

        //preparatio de l'array a ajouter
        $widgetArray = post('attributes_array');
        $widgetArray['calculCode'] = $calculCode;
        $widgetArray['tagId'] = $tagId;

        foreach ($datas as $key => $data) {
            if ($data['tagId'] == $tagId) {
                $datas[$key] = $widgetArray;
            }
        }

        //enregistrement du model
        $field = $this->fieldName;
        $this->model[$field] = $datas;
        $this->model->save();

        //rafraichissement de la liste
        return [
            '#list' => $this->makePartial('list', ['values' => $datas]),
        ];
    }

    public function createFormWidget()
    {
        $config = $this->makeConfig('$/waka/segator/models/scopecalcul/fields.yaml');
        $config->alias = 'attributeWidget';
        $config->arrayName = 'attributes_array';
        $config->model = new \Waka\Segator\Models\ScopeCalcul();
        $widget = $this->makeWidget('Backend\Widgets\Form', $config);
        $widget->bindToController();
        return $widget;
    }
}
