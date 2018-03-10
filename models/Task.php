<?php namespace Smartshop\Import\Models;

use Model;
use Exception;

/**
 * Task Model
 *
 * @property int $id
 * @property string $status
 * @property boolean $is_processing
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Backend\Models\User $author
 * @property \SmartShop\Import\Models\Template $template
 * @property \System\Models\File $file
 *
 * @method \October\Rain\Database\Relations\BelongsTo author
 * @method \October\Rain\Database\Relations\BelongsTo template
 * @method \October\Rain\Database\Relations\AttachOne file
 *
 * @mixin \Eloquent
 */
class Task extends Model
{
    use \October\Rain\Database\Traits\Validation;

    const STATUS_WAITING = 'waiting';
    const STATUS_PROCESSING = 'processing';
    const STATUS_DONE = 'done';
    const STATUS_FAILED = 'failed';

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
        'status' => '',
        'file' => 'required',
        'template' => 'required',
    ];

    //
    // Events
    //

    public function beforeCreate()
    {
        $this->status = self::STATUS_WAITING;
    }

    //
    // Setter
    //

    public function getStatusTextAttribute()
    {
        return trans('smartshop.import::lang.task.status_'.$this->status);
    }

    //
    // Scope
    //

    /**
     * Get Tasks available for processing
     *
     * @param \October\Rain\Database\Builder $query
     * @return mixed
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_WAITING);
    }

    //
    //
    //

    /**
     * Get import data
     *
     * @return array
     * @throws \Exception
     */
    public function getImportData()
    {
        if ($this->template && $this->file) {
            return $this->template->getImportData($this->file);
        }

        throw new Exception('Template or file does not found');
    }

    /**
     * Mark Task as failed
     *
     * @return void
     */
    public function failed()
    {
        $this->status = self::STATUS_FAILED;
        $this->save();
    }

    /**
     * Mark Task as in processing
     * @return void
     * @throws \Exception
     */
    public function processing()
    {
        if ($this->status === self::STATUS_PROCESSING) {
            throw new Exception('Task is in processing');
        }

        $this->status = self::STATUS_PROCESSING;
        $this->save();
    }
}
