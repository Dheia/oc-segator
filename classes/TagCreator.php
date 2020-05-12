<?php namespace Waka\Segator\Classes;

use Waka\Segator\Models\Tag;

class TagCreator
{

    private $dataSourceModel;
    private $dataSourceId;
    private $model;
    public $classCalculs;

    public function __construct(Tag $tag)
    {
        trace_log($tag->name);
        $this->tag = $tag;
        $this->model = $this->tag->data_source->modelClass;
        if ($this->tag->auto_class_calculs) {
            $author = $this->tag->data_source->author;
            $plugin = $this->tag->data_source->plugin;
            $model = $this->tag->data_source->model;
            $this->classCalculs = '\\' . $author . '\\' . $plugin . '\\functions\\' . $model . 'Tags';
        } else {
            $this->classCalculs = $this->tag->classCalculs;
        }
        trace_log($this->classCalculs);
    }

}
