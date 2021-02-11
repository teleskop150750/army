<?php

namespace core;

class ErrorHandler
{
    public function __construct()
    {
        if (DEBUG) {
            error_reporting(-1);
        } else {
            error_reporting(0);
        }
        set_exception_handler([$this, 'exceptionHandler']);
    }

    /**
     * Обрабочик исключений
     * @param object $e
     */
    public function exceptionHandler(object $e): void
    {
        $errorNumber = $e->errorNumber ?? 'Exception';
        $this->displayError($errorNumber, $e->getMessage(), $e->getFile(), $e->getLine(), $e->getCode());
    }

    /**
     * отобразить ошибки
     * @param string $errorNumber Код ошибки
     * @param string $message сообщение
     * @param string $file файл
     * @param int $line строка
     * @param int $response ответ
     */
    protected function displayError(
        string $errorNumber,
        string $message,
        string $file,
        int $line,
        int $response = 404
    ): void {
        // устрановить код ответа
        http_response_code($response);

        if (DEBUG) {
            require_once WWW . '/errors/dev.php';
        } elseif ($response === 404) {
            require_once WWW . '/errors/404.php';
        } else {
            require_once WWW . '/errors/prod.php';
        }

        die;
    }
}
