<?php namespace Smartshop\Import\Models;

use Model;
use ApplicationException;

/**
 * Template Model
 *
 * @property \System\Models\File $file
 *
 * @method \October\Rain\Database\Relations\AttachOne file
 *
 * @mixin \Eloquent
 */
class Template extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'smartshop_import_templates';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'mapping',
        'description',
    ];

    /**
     * @var array Json fields
     */
    protected $jsonable  = ['mapping'];

    /**
     * @var array Relations AttachOne
     */
    public $attachOne = [
        'file' => [
            \System\Models\File::class
        ]
    ];

    /**
     * Validation
     */
    public $rules = [
        'name' => 'required|between:1,255',
        'mapping' => '',
        'description' => '',
        'file' => '',
    ];

    /**
     * Get file mapping
     *
     * @param \System\Models\File $file
     * @throws \ApplicationException
     * @return array
     */
    public function getFileMapping($file)
    {
        $mapping = [];

        foreach ($this->getFileFirstRow($file) as $key => $val)
        {
            $mapping[] = [
                'file_column' => $key,
                'file_value' => $val,
                'db_column' => '',
            ];
        }

        return $mapping;
    }

    /**
     * Get file Data
     */
    public function getFileData($file)
    {
        $data = [];
        $content = $this->getFileContent($file);

        for ($i = 0; $i < $content->children()->count(); $i++)
        {
            $children = $content->children()[$i];

            foreach ($this->mapping as $map) {

                if (empty($map['db_column']) && empty($children->{$map['file_column']})) {
                    continue;
                }

                switch ($map['db_column']) {
                    case 'name':
                    case 'slug':
                    case 'sku':
                    case 'isbn':
                    case 'price':
                    case 'description':
                    case 'width':
                    case 'height':
                    case 'depth':
                    case 'weight':
                        break;
                    default:
                        $data[$i][$map['db_column']] = trim((string) $children->{$map['file_column']});
                }
            }
        }

        return $data;
    }

    /**
     *
     */
    public function updateMapping($file)
    {
        $this->mapping = $this->getFileMapping($file);
    }

    /**
     * Get file first row
     *
     * @param \System\Models\File $file
     * @throws \ApplicationException
     * @return array
     */
    private function getFileFirstRow($file)
    {
        $data = [];
        $content = $this->getFileContent($file);

        if ($content->count()) {
            foreach ($content->children()[0] as $key => $val) {
                $data[$key] = trim((string) $val);
            }
        }

        return $data;
    }

    /**
     * Get file content
     *
     * @param \System\Models\File $file
     *
     * @return \SimpleXMLElement
     * @throws \ApplicationException
     */
    private function getFileContent($file)
    {
        if (!$file instanceof \System\Models\File) {
            throw new ApplicationException('Wrong file object');
        }

        return @simplexml_load_file($file->getLocalPath(), 'SimpleXMLElement', LIBXML_COMPACT);
    }
}
