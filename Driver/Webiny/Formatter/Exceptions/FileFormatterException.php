<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link         http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright    Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license      http://www.webiny.com/framework/license
 * @package      WebinyFramework
 */
namespace Webiny\Component\Logger\Driver\Webiny\Formatter\Exception;

use Webiny\Component\StdLib\Exception\ExceptionAbstract;


/**
 * Udp handler exception class.
 *
 * @package      Webiny\Component\Logger\Driver\Webiny\Handler\Exception
 */
class FileFormatterException extends ExceptionAbstract
{
    const CONFIG_NOT_FOUND = 101;

    static protected $_messages = [
        101 => 'File formatter config was not found!',
    ];
}