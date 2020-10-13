<?php namespace Waka\Segator\Models;

use Model;
use Waka\Utils\Classes\DataSource;

/**
 * Tag Model
 */
class Tag extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;
    use \October\Rain\Database\Traits\Sortable;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'waka_segator_tags';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = ['calculs', 'only_tag'];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function getCalculModelAttribute()
    {
        if ($this->auto_class_calculs) {
            $ds = new DataSource($this->data_source_id, 'id');
            return '\\' . $ds->author . '\\' . $ds->plugin . '\\functions\\' . $ds->name . 'Tags';
        } else {
            return $this->tag->classCalculs;
        }
    }

    public function getTagList()
    {
        return Tag::lists('name', 'id');
    }

    /**
     * LIST
     */
    public function listDataSource()
    {
        return \Waka\Utils\Classes\DataSourceList::lists();
    }
}
