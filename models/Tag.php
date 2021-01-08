<?php namespace Waka\Segator\Models;

use Model;
use Waka\Utils\Classes\DataSource;

/**
 * tag Model
 */

class Tag extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;
    use \Waka\Utils\Classes\Traits\DataSourceHelpers;

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
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:waka_segator_tags',
        'data_source' => 'required',
    ];

    /**
     * @var array attributes send to datasource for creating document
     */
    public $attributesToDs = [
    ];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = [
        'parent_incs',
        'calculs',
    ];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [
    ];

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
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
    ];
    public $hasOneThrough = [];
    public $hasManyThrough = [

    ];
    public $belongsTo = [
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [
    ];
    public $morphMany = [
    ];
    public $attachOne = [
    ];
    public $attachMany = [
    ];

    /**
     *EVENTS
     **/

    /**
     * LISTS
     **/
    public function getTagList()
    {
        return Tag::where('id', '!=', $this->id)->lists('name', 'id');
    }
    public function getAutoTagList()
    {
        return Tag::where('is_manual', false)->where('data_source', $this->data_source)->lists('name', 'id');
    }

    /**
     * GETTERS
     **/

    /**
     * SCOPES
     */

    /**
     * SETTERS
     */

    /**
     * FILTER FIELDS
     */

    /**
     * OTHERS
     */
    public function getCalculModelAttribute()
    {
        if ($this->is_auto_class_calculs) {
            $ds = new DataSource($this->data_source);
            return '\Waka\Wconfig\Functions\Tags\\' . $ds->name . 'Tags';
        } else {
            return $this->class_calculs;
        }
    }

}
