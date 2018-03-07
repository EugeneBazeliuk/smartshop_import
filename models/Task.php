<?php namespace Smartshop\Import\Models;

use Model;

/**
 * Task Model
 *
 * @property \Backend\Models\User $author
 * @property \System\Models\File $file
 *
 * @method \October\Rain\Database\Relations\BelongsTo author
 * @method \October\Rain\Database\Relations\AttachOne file
 *
 * @mixin \Eloquent
 */
class Task extends Model
{
    use \October\Rain\Database\Traits\Validation;

    const STATUS_DONE = 'done';
    const STATUS_FAILED = 'failed';
    const STATUS_WAITING = 'waiting';

    /** @var string The database table used by the model. */
    public $table = 'smartshop_import_tasks';

    /** @var array Guarded fields */
    protected $guarded = ['*'];

    /** @var array Fillable fields */
    protected $fillable = [
        'template'
    ];

    /** @var array Relations BelongTo */
    public $belongsTo = [
        'author' => [
            \Backend\Models\User::class,
        ],
        'template' => [
            \SmartShop\Import\Models\Template::class,
        ],
    ];

    /** @var array Relations AttachOne */
    public $attachOne = [
        'file' => [
            \System\Models\File::class
        ],
    ];

    /** @var array Validation rules */
    public $rules = [
        'file' => 'required',
        'template' => 'required',
    ];

    //
    // Events
    //

    public function beforeCreate()
    {
        $this->isWaiting();
    }

    //
    // Attributes
    //

    public function getStatusTextAttribute()
    {
        return trans('smartshop.import::lang.task.status_'.$this->status);
    }

    //
    //
    //

    public function isDone()
    {
        $this->status = self::STATUS_DONE;
    }

    public function isFailed()
    {
        $this->status = self::STATUS_FAILED;
    }

    private function isWaiting()
    {
        $this->status = self::STATUS_WAITING;
    }
}
