<?php

namespace HelloMotto\Silex\Config\Exception;

class LoaderException extends \RuntimeException
{
    const FILE_NOT_FOUND = 106;
    const NOT_READABLE_FILE = 205;
    const NO_LOADER_FOUND = 432;
    const YAML_NOT_LOADED = 648;
    const UNKNOW_ERROR = 899;

    public function __construct($code, $argument = '')
    {
        $message = sprintf($this->getErrorMessage($code), $argument);

        parent::__construct($message, $code);
    }

    public function getErrorMessage($code)
    {
        $errors = [
            JSON_ERROR_NONE => 'No error',
            JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
            JSON_ERROR_STATE_MISMATCH => 'State mismatch (invalid or malformed JSON)',
            JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
            JSON_ERROR_SYNTAX => 'Syntax error',
            JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded',
            self::FILE_NOT_FOUND => 'The file %s does not exist.',
            self::NOT_READABLE_FILE => 'The file %s cannot be read.',
            self::NO_LOADER_FOUND => 'There is no installed loader for the file %s.',
            self::YAML_NOT_LOADED => 'The Symfony YAML Component is not installed.',
            self::UNKNOW_ERROR => 'Unknown loader error. So let\'s eat some Beaufort !'
        ];

        return array_key_exists($code, $errors) ? $errors[$code] : $errors['unkown_error'];
    }
}