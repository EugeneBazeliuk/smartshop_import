<?php namespace SmartShop\Import\Classes;

/**
 * Class ImportFileProviderXml
 *
 * @property \SimpleXMLElement $content
 */
class ImportFileProviderXml extends ImportFileProvider
{
    /**
     * Get first child row
     *
     * @return array
     */
    public function getFileFirstRow()
    {
        return $this->getFileRow(0);
    }

    /**
     * Process File row
     *
     * @param int   $index
     * @param array $mapping
     *
     * @return array
     */
    public function getFileRow($index)
    {
        $data = [];
        $row = $this->content->children()[$index];

        foreach ($row as $key => $val) {
            $data[$key] = trim((string) $val);
        }

        return $data;
    }

    /**
     * Check File mapping
     *
     * @param array $mapping
     * @return bool
     */
    public function checkFileMapping($mapping)
    {
        $row = $this->getFileFirstRow();

        foreach ($mapping as $file_col => $db_col) {
            if (!array_key_exists($file_col, $row)){
                return false;
            }
        }

        return true;
    }

    /**
     * Get total rows count
     *
     * @return int
     */
    public function getTotalRowsCount()
    {
        return $this->content->children()->count();
    }

    /**
     * Load file content
     *
     * @param $path
     *
     * @return \SimpleXMLElement
     */
    public function getFileContent($path)
    {
        return @simplexml_load_file($path, 'SimpleXMLElement', LIBXML_COMPACT);
    }
}
