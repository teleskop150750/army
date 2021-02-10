<?php

namespace app\models;

use app\models\base\UserBaseModel;
use Imagick;
use RedBeanPHP\R;

class UserModel extends UserBaseModel
{
    public array $attributes = [
        'login' => '',
        'password' => '',
        'email' => '',
        'img' => '',
        'role' => 'user',
    ];

    public array $rules = [
        'required' => [
            ['login'],
            ['password'],
            ['email'],
        ],
        'email' => [
            ['email'],
        ],
        'lengthMin' => [
            ['password', 6],
        ],
        'lengthMax' => [
            ['login', 20],
        ]
    ];

    /**
     * зарегестрирован?
     * @return bool
     */
    public static function checkAuth(): bool
    {
        return isset($_SESSION['user']);
    }

    /**
     * проверить на уникальность
     * @return bool
     */
    public function checkUnique(): bool
    {
        /** @var object $user */
        $user = R::findOne('user', 'login = ? OR email = ?', [$this->attributes['login'], $this->attributes['email']]);
        if ($user) {
            if ($user->login === $this->attributes['login']) {
                $this->errors['unique'][] = 'Этот логин уже занят';
            }
            if ($user->email === $this->attributes['email']) {
                $this->errors['unique'][] = 'Этот email уже занят';
            }
            return false;
        }
        return true;
    }

    public function uploadImg($name, $length): void
    {
        $uploadDir = WWW . '/upload/images/avatars/';
        $ext = strtolower(preg_replace("#.+\.([a-z]+)$#i", "$1", $_FILES[$name]['name'])); // расширение картинки
        $types = array("image/gif", "image/png", "image/jpeg", "image/jpeg", "image/x-png"); // массив допустимых расширений
        if ($_FILES[$name]['error']) {
            $res = array("error" => "Ошибка! Возможно, файл слишком большой.");
            exit(json_encode($res));
        }
        if (!in_array($_FILES[$name]['type'], $types)) {
            $res = array("error" => "Допустимые расширения - .gif, .jpg, .png");
            exit(json_encode($res));
        }
        $new_name = md5(time()) . ".$ext";
        $uploadFile = $uploadDir . $new_name;
        if (@move_uploaded_file($_FILES[$name]['tmp_name'], $uploadFile)) {
            $_SESSION['avatar'] = $new_name;
            self::resize($uploadFile, $uploadFile, $length, $ext);
            $res = array("file" => $new_name);
            exit(json_encode($res));
        }
    }

    /**
     * @param string $target путь к оригинальному файлу
     * @param string $dest путь сохранения обработанного файла
     * @param string $length максимальная ширина
     * @param string $ext расширение файла
     */
    public static function resize($target, $dest, $length, $ext): void
    {
        [$w_orig, $h_orig] = getimagesize($target);
        try {
            $img = new Imagick($target);
        } catch (ImagickException $e) {
            $ext;
        }
        if ($h_orig > $w_orig) {
            $img->thumbnailImage($length, 0);
        } else {
            $img->thumbnailImage(0, $length);
        }
        $img->writeImages($dest, false);
        $w = $img->getImageWidth();
        $h = $img->getImageHeight();
        $x = round($w / 2) - round($length / 2);
        $y = round($h / 2) - round($length / 2);
        $img->cropImage($length, $length, $x, $y);

//        $compressionList = [Imagick::COMPRESSION_UNDEFINED,
//            Imagick::COMPRESSION_BZIP,
//            Imagick::COMPRESSION_LZW,
//            Imagick::COMPRESSION_RLE,
//            Imagick::COMPRESSION_ZIP,
//            Imagick::COMPRESSION_JPEG2000,
//            Imagick::COMPRESSION_LOSSLESSJPEG,
//            Imagick::COMPRESSION_NO
//        ];
        $compressionList = [ Imagick::COMPRESSION_JPEG2000];

//        for ($s = 0, $sMax = count($compressionList); $s < $sMax; $s++) {
//            for ($i = 0; $i < 100; $i++) {
        $imagickDst = new Imagick();
        $imagickDst->setCompression(75);
        $imagickDst->setCompressionQuality(75);
        $imagickDst->newPseudoImage(
            $img->getImageWidth(),
            $img->getImageHeight(),
            'canvas:white'
        );

        $imagickDst->compositeImage(
            $img,
            Imagick::COMPOSITE_ATOP,
            0,
            0
        );
        $imagickDst->setImageFormat("jpg");
        $imagickDst->writeImages($dest, false);
//            }
//        }
//        $img->writeImages($dest, false);
    }
}
