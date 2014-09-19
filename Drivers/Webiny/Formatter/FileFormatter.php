<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @copyright Copyright Webiny LTD
 */

namespace Webiny\Component\Logger\Driver\Webiny\Formatter;

use Webiny\Component\Logger\Driver\Webiny\Formatter\Exception\FileFormatterException;
use Webiny\Component\Logger\Driver\Webiny\Record;
use Webiny\Component\Logger\Logger;


/**
 * Formats incoming records into a one-line string
 *
 * @package Webiny\Component\Logger\Driver\Webiny\Formatter
 */
class FileFormatter extends FormatterAbstract
{
    protected $_format;

    protected $_dateFormat;

    /**
     * @param string $format     The format of the message
     * @param string $dateFormat The format of the timestamp: one supported by DateTime::format
     *
     * @throws Exception\FileFormatterException
     */
    public function __construct($format = null, $dateFormat = null)
    {
        $this->_config = Logger::getConfig()->get('Configs.Formatter.File');
        if ($this->isNull($this->_config)) {
            throw new FileFormatterException(FileFormatterException::CONFIG_NOT_FOUND);
        }
        if ($this->isNull($format)) {
            $format = str_replace('\n', "\n", $this->_config->RecordFormat);
        }

        $this->_format = $format;
        $this->_dateFormat = $dateFormat !== null ? $dateFormat : $this->_config->DateFormat;
    }

    public function formatRecord(Record $record)
    {

        // Call this to execute standard value normalization
        $this->normalizeValues($record);

        $output = $this->str($this->_format);

        // Handle extra values if case specific values are given in record format
        $extraData = $record->getExtra();
        foreach ($extraData as $var => $val) {
            if ($output->contains('%extra.' . $var . '%')) {
                $output->replace('%extra.' . $var . '%', $val);
                unset($extraData[$var]);
            }
        }
        $record->setExtra($extraData);

        // Handle main record values
        foreach ($record as $var => $val) {
            if ($this->isDateTimeObject($val)) {
                $val = $val->format($this->_dateFormat);
            }
            if (is_object($val)) {
                if (method_exists($val, '__toString')) {
                    $val = '' . $val;
                }
            } elseif (is_array($val)) {
                $val = json_encode($val);
            }
            $output->replace('%' . $var . '%', $val);
        }

        $record->setFormattedRecord($output->val());

        return $output->val();
    }

    public function formatRecords(array $records, Record $record)
    {
        $message = '';
        foreach ($records as $r) {
            $message .= $this->formatRecord($r);
        }

        $record->setFormattedRecord($message);
    }
}
