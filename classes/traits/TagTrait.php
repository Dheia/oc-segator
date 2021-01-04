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
            $model->morphToMany['tags'] = [
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
        return $query->whereHas('tags', function ($q) use ($filtered) {
            $q->whereIn('tag_id', $filtered);
        });
    }

}
