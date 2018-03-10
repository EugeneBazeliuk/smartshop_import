<?php namespace Smartshop\Import\Models;

use Model;
use ApplicationException;
use SmartShop\Import\Classes\ImportFileProvider;

/**
 * Template Model
 *
 * @property string $name
 * @property string $description
 * @property array $mapping
 * @property \System\Models\File $file
 *
 * @method \October\Rain\Database\Relations\AttachOne file
 *
 * @mixin \Eloquent
 */
class Template extends Model
{
    const FIRST_LEVEL_DELIMETER = '|';
    const SECOND_LEVEL_DELIMETER = '::';

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
        'file' => '',
        'name' => 'required|between:1,255',
        'mapping' => '',
        'description' => '',
    ];

    /**
     * @var array
     */
    public $firstLevelAttributes = [
        'categories',
    ];

    /**
     * @var
     */
    public $secondLevelAttributes = [
        'bindings',
        'properties',
    ];

    //
    //
    //

    /**
     * Update mapping from file
     *
     * @param \System\Models\File $file
     * @throws \ApplicationException
     */
    public function updateMapping($file)
    {
        $this->mapping = $this->getFileMapping($file);
    }

    /**
     * @param \System\Models\File $file
     *
     * @return array
     * @throws \ApplicationException
     */
    public function getImportData($file)
    {
        $data = [];
        $mapping = $this->getMapping();
        $provider = $this->getFileProvider($file);

        // Check mapping
        if (!$provider->checkFileMapping($mapping)) {
            throw new ApplicationException('Wrong mapping');
        }

        // Process import data
        for ($i = 0; $i < $provider->getTotalRowsCount(); $i++)
        {
            $row = $provider->getFileRow($i);

            foreach ($mapping as $file_col => $db_col)
            {
                if (empty($row[$file_col])) {
                    continue;
                }

                if($this->isFirstLevelAttributes($db_col)) {
                    $data[$i][$db_col][] = $row[$file_col];
                } elseif ($this->isSecondLevelAttributes($db_col)) {
                    $data[$i][$db_col][] = implode(self::SECOND_LEVEL_DELIMETER, [$file_col, $db_col]);
                } else {
                    $data[$i][$db_col] = $row[$file_col];
                }
            }

            foreach (array_merge($this->firstLevelAttributes, $this->secondLevelAttributes) as $attribute)
            {
                if (isset($data[$i][$attribute])) {
                    $data[$i][$attribute] = implode(self::FIRST_LEVEL_DELIMETER, $data[$i][$attribute]);
                }
            }
        }

        return $data;
    }

    /**
     * Get mapping
     *
     * @return array
     */
    public function getMapping()
    {
        $mapping = [];

        foreach ($this->mapping as $map)
        {
            if (!isset($map['db_column']) && empty($map['db_column'])) {
                continue;
            }

            $mapping[$map['file_column']] = $map['db_column'];
        }

        return $mapping;
    }

    /**
     * Check multiple attribute
     *
     * @param string $attribute
     * @return bool
     */
    public function isFirstLevelAttributes($attribute)
    {
        return in_array($attribute, $this->firstLevelAttributes);
    }

    /**
     * Check second level attributes
     *
     * @param $attribute
     * @return bool
     */
    public function isSecondLevelAttributes($attribute)
    {
        return in_array($attribute, $this->secondLevelAttributes);
    }

    /**
     * @param \System\Models\File $file
     *
     * @return array
     * @throws \ApplicationException
     */
    public function getFileMapping($file)
    {
        $mapping = [];
        $firstRow = $this->getFileProvider($file)->getFileFirstRow();

        foreach ($firstRow as $k => $v)
        {
            array_push($mapping, [
                'file_column' => $k,
                'file_value' => $v,
            ]);
        }

        return $mapping;
    }

    /**
     * @param \System\Models\File $file
     *
     * @return \SmartShop\Import\Classes\ImportFileProvider
     * @throws \ApplicationException
     */
    private function getFileProvider($file)
    {
        return ImportFileProvider::getInstance($file);
    }
}
