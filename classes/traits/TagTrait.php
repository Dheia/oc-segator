<?php namespace Waka\Segator\Classes\Traits;

use \Waka\Informer\Models\Inform;

trait TagTrait
{
    /*
     * Constructor
     */
    public static function bootTagTrait()
    {
        static::extend(function ($model) {
            /*
             * Define relationships
             */
            $model->morphToMany['wakatags'] = [
                'Waka\Segator\Models\Tag',
                'name' => 'taggable',
                'table' => 'waka_segator_taggables',
                'delete' => true,
            ];

            $model->bindEvent('model.afterSave', function () use ($model) {
                //$model->updateCloudiRelations('attach');
            });

            $model->bindEvent('model.beforeDelete', function () use ($model) {
                //$model->clouderDeleteAll();
            });
        });

    }

    /**
     * SCOPE
     */
    public function scopeTagFilter($query, $filtered)
    {
        return $query->whereHas('wakatags', function ($q) use ($filtered) {
            $q->whereIn('tag_id', $filtered);
        });
    }

    /**
     * List
     */
    public function getTagList()
    {
        return Tag::lists('name', 'id');
    }

    public function getManualTagList()
    {
        return Tag::where('is_manual', true)->lists('name', 'id');
    }
    public function getAutoTagList()
    {
        return Tag::where('is_manual', false)->where('data_source', $this->data_source)->lists('name', 'id');
    }

}
