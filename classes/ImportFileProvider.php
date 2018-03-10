<?php namespace SmartShop\Import\Classes;

use ApplicationException;

abstract class ImportFileProvider
{
    protected $content;

    public function __construct($path)
    {
        $this->content = $this->getFileContent($path);
    }

    /**
     * @param $file \System\Models\File
     *
     * @return self
     * @throws \ApplicationException
     */
    public static function getInstance($file)
    {
        if (!$file instanceof \System\Models\File) {
            throw new ApplicationException('Wrong file object');
        }

        switch ($extension = $file->getExtension())
        {
            case 'xml':
                return new ImportFileProviderXml($file->getLocalPath());
                break;
            default:
                throw new ApplicationException('Unsupported file format');
        }
    }

    /**
     *
     */
    abstract public function getFileFirstRow();

    /**
     * Process File row
     *
     * @param int $index
     * @return array
     */
    abstract public function getFileRow($index);

    /**
     * Check File mapping
     *
     * @param array $mapping
     * @return bool
     */
    abstract public function checkFileMapping($mapping);

    /**
     * Load file content
     *
     * @param $path
     * @return mixed
     */
    abstract public function getFileContent($path);

    /**
     * Get total rows count
     *
     * @return int
     */
    abstract public function getTotalRowsCount();
}