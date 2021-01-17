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
        $this->logErrors($e->getMessage(), $e->getFile(), $e->getLine());
        $errorNumber = $e->errorNumber ?? 'Exception';
        $this->displayError($errorNumber, $e->getMessage(), $e->getFile(), $e->getLine(), $e->getCode());
    }

    /**
     * логирование ошибок
     * @param string $message сообщение
     * @param string $file файл
     * @param string $line строка
     */
    protected function logErrors(string $message = '', string $file = '', string $line = ''): void
    {
        error_log(
            "[" . date('Y-m-d H:i:s') . "] Текст ошибки: {$message} | Файл: {$file} | Строка: {$line}
        \n=================\n",
            3,
            ROOT . '/tmp/errors.log'
        );
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
        } else if ($response === 404) {
            require_once WWW . '/errors/404.php';
        } else {
            require_once WWW . '/errors/prod.php';
        }

        die;
    }
}
