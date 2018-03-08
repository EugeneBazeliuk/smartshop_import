<?php namespace Smartshop\Import\Models;

use Model;

/**
 * Log Model
 *
 * @property int $createdCount
 * @property int $updatedCount
 * @property int $errorCount
 * @property int $warningCount
 * @property int $skipperCount
 * @property array $details
 * @property \System\Models\File $file
 * @property \Backend\Models\User $author
 * @property \SmartShop\Import\Models\Template $template
 *
 * @method \October\Rain\Database\Relations\BelongsTo author
 * @method \October\Rain\Database\Relations\BelongsTo template
 * @method \October\Rain\Database\Relations\AttachOne file
 *
 * @mixin \Eloquent
 */
class Log extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'smartshop_import_logs';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'details'
    ];

    /**
     * @var array Json fields
     */
    protected $jsonable  = [
        'details'
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

    /**
     * @var array Relations AttachOne
     */
    public $attachOne = [
        'file' => [
            \System\Models\File::class
        ],
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'details' => 'required',
    ];

    //
    // Attributes
    //

    public function getCreatedCountAttribute()
    {
        return $this->details['created'];
    }

    public function getUpdatedCountAttribute()
    {
        return $this->details['updated'];
    }

    public function getErrorCountAttribute()
    {
        return $this->details['errorCount'];
    }

    public function getWarningCountAttribute()
    {
        return $this->details['warningCount'];
    }

    public function getSkipperCountAttribute()
    {
        return $this->details['skipperCount'];
    }
}
