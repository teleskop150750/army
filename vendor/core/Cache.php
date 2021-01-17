<?php

namespace core;

class Cache
{
    use TSingleton;

    /**
     * закэшировать
     * @param string $key название
     * @param mixed $data данные
     * @param int $seconds время
     * @return bool
     */
    public function set(string $key, $data, int $seconds = 3600): bool
    {
        // указано время?
        if ($seconds) {
            $content['data'] = $data;
            $content['end_time'] = time() + $seconds;

            // записано в кэш?
            if (file_put_contents(CACHE . '/' . md5($key) . '.txt', serialize($content)) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * получить кэш
     * @param string $key ключ
     * @return false|array
     */
    public function get(string $key)
    {
        $file = CACHE . '/' . md5($key) . '.txt';
        if (file_exists($file)) {
            $content = unserialize(file_get_contents($file), ['allowed_classes' => false]);
            if (time() <= $content['end_time']) {
                return $content['data'];
            }
            unlink($file);
        }
        return false;
    }

    /**
     * удалить кэш
     * @param string $key ключ
     */
    public function delete(string $key): void
    {
        $file = CACHE . '/' . md5($key) . '.txt';
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
