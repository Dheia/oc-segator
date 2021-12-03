<?php namespace Waka\Segator\Classes\Traits;

use Waka\Segator\Models\Tag;

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
        if(is_string($filtered)) {
            $filtered = [$filtered];
        }
        return $query->whereHas('wakatags', function ($q) use ($filtered) {
            $q->whereIn('tag_id', $filtered);
        });
    }

    public function scopeNoTagFilter($query, $filtered)
    {
        if(is_string($filtered)) {
            $filtered = [$filtered];
        }
        return $query->whereHas('wakatags', function ($q) use ($filtered) {
            trace_log($filtered);
            $q->whereNotIn('tag_id', $filtered);
        })->orDoesntHave('wakatags');
    }

    /**
     * List
     */
    public function getTagList()
    {
        $class = get_class($this);
        $dsCode = \Datasources::findByClass($class)->code;
        $tags = Tag::where('is_manual', true)->where('data_source', $dsCode);
        if($tags) {
            return $tags->lists('name', 'id');
        } else {
            return [];
        }
    }

    public function getManualTagList()
    {
        $class = get_class($this);
        $dsCode = \Datasources::findByClass($class)->code;
        return Tag::where('is_manual', true)->where('data_source', $dsCode)->lists('name', 'id');
    }
    public function getAutoTagList()
    {
        $class = get_class($this);
        $dsCode = \Datasources::findByClass($class)->code;
        return Tag::where('is_manual', false)->where('data_source', $dsCode)->lists('name', 'id');
    }

}
