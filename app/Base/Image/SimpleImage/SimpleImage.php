<?php


namespace App\Base\Image\SimpleImage;

use Exception;
use Symfony\Component\Process\Process;

class SimpleImage
{
    /**
     * Ресурс изображения.
     *
     * @var false|resource
     */
    protected $img;

    /**
     * Тип изображения.
     *
     * @var string
     */
    protected $img_type;

    /**
     * Путь к изображению.
     *
     * @var string
     */
    protected $path;

    /**
     * Минимальная допустимая ширина для изображения.
     *
     * @var bool|integer
     */
    protected $min_width;

    /**
     * Минимальная допустимая высота для изображения.
     *
     * @var bool|integer
     */
    protected $min_height;

    /**
     * Необходимые параметры для изменения размеров библиотекой imagemagick.
     *
     * @var array
     */
    protected $imagemagick_params = [];

    /**
     * Загрузка изображения и получение его типа.
     *
     * @param string $path
     * @return $this|bool|false
     */
    public function load($path)
    {
        if(!$this->isGDEnabled()) {
            throw new Exception("Библиотека GD не установлена");
        }

        /**
         * Получение типа изображения через функцию с помощью которой можно узнать размер изображения.
         */
        $image_info = getimagesize($path);

        /**
         * Сохранение текущего пути изображения.
         */
        $this->path = $path;

        /**
         * Получение типа изображения.
         */
        $this->img_type = $image_info[2];

        /**
         * Обнуление параметров для imagemagick.
         */
        $this->imagemagick_params = [];

        /**
         * Загрузка ресурса изображения.
         */
        switch ($this->img_type) {
            case IMAGETYPE_JPEG:
                $this->img = imagecreatefromjpeg($path);
                break;
            case IMAGETYPE_GIF:
                $this->img = imagecreatefromgif($path);
                imagesavealpha($this->img, true);
                break;
            case IMAGETYPE_PNG:
                $this->img = imagecreatefrompng($path);
                imagesavealpha($this->img, true);
                break;
            default:
                return false;
        }

        return $this;
    }

    /**
     * Сохранение изображения.
     *
     * @param string $path
     * @param int $compression
     * @return string|bool|false
     */
    function save($path, $compression = 75)
    {
        $directory = explode('/', $path);
        array_pop($directory);
        $directory = implode('/', $directory);

        if(!file_exists($directory)) {
            mkdir($directory, 0775, true);
        }

        switch ($this->img_type) {

            case IMAGETYPE_JPEG:
                imagejpeg($this->img, $path, $compression);
                break;

            case IMAGETYPE_GIF:
                /** @noinspection PhpMethodParametersCountMismatchInspection */
                imagegif($this->img, $path, $compression);
                break;

            case IMAGETYPE_PNG:
                $this->resizeImageMagic($path, $compression);
                break;

            default:
                return false;
        }

        if(!file_exists($path)) {
            throw new Exception("Ошибка сохранения изображения");
        }

        $this->clearMemory();

        return $path;
    }

    /**
     * Перезапись текущего изображения.
     *
     * @param int $compression
     * @return string|bool|false
     */
    public function overwrite($compression = 75)
    {
        switch ($this->img_type) {

            case IMAGETYPE_JPEG:
                imagejpeg($this->img, $this->path, $compression);
                break;

            case IMAGETYPE_GIF:
                /** @noinspection PhpMethodParametersCountMismatchInspection */
                imagegif($this->img, $this->path, $compression);
                break;

            case IMAGETYPE_PNG:
                $this->resizeImageMagic($this->path, $compression);
                break;

            default:
                return false;

        }

        $this->clearMemory();

        return $this->path;
    }

    /**
     * Изменение размеров изображения по ширине, сохраняя пропорции.
     *
     * @param int $width
     * @return $this|bool|false
     */
    public function resizeToWidth($width)
    {
        if($width < $this->min_width) {
            return false;
        }

        /**
         * Подсчет соотношения между новой шириной и старой.
         */
        $ratio = $width / $this->getWidth();

        /**
         * Получаем высоту на основе соотношения
         */
        $height = $this->getHeight() * $ratio;

        /**
         * Запустить изменение размеров путем вырезки квадрата из изображения, если у полученных размеров изображения
         * высота меньше минимальной высоты изображения.
         */
        if($height < $this->min_height) {
            $this->cutSquareImage($width);
        } else {
            if($this->img_type != IMAGETYPE_PNG) {
                $this->resize($width, $height);
            } else {
                /**
                 * Сохранение в свойство для дальнейшего изменения размера изображения при сохранении с помощью библиотеки.
                 */
                $this->imagemagick_params = [
                    'width' => $width,
                    'height' => $height,
                    'cut_square' => false,
                ];
            }
        }

        return $this;
    }

    /**
     * Вырезка квадрата из центра изображения.
     *
     * @param int $lenght
     * @return $this
     */
    public function cutSquareImage($lenght)
    {
        if($this->img_type != IMAGETYPE_PNG) {
            $width = $this->getWidth();
            $height = $this->getHeight();

            /**
             * Создание пустое изображения в ввиде нужного квадрата по ширине и высоте.
             */
            $new_image = imagecreatetruecolor($lenght, $lenght);

            if ($this->img_type == IMAGETYPE_GIF || $this->img_type == IMAGETYPE_PNG) {
                $this->saveAlphaImage($new_image);
            }

            /**
             * Изображение горизонтальное.
             */
            if ($width > $height) {

                /**
                 * Вычисление координаты x верхнего левого угла квадратного блока.
                 */
                $src_x = round((max($width, $height) - min($width, $height)) / 2);

                /**
                 * Вырезка середины горизонтального изображения в квадрат.
                 */
                imagecopyresized($new_image, $this->img, 0, 0, $src_x,
                    0, $lenght, $lenght, min($width, $height), min($width, $height));
                /**
                 * Изображение вертикальное.
                 */
            } elseif ($height > $width) {
                /**
                 * Вырезка вверхушки вертикального изображения в квадрат ( посколько в вертикальных изображениях обычно важна верхняя часть ).
                 */
                imagecopyresized($new_image, $this->img, 0, 0, 0, 0, $lenght, $lenght,
                    min($width, $height), min($width, $height));

                /**
                 * Вырезка середины вертикального изображения.
                 * imagecopyresized($dest, $src, 0, 0, 0, round((max($w_src,$h_src)-min($w_src,$h_src))/2),
                 * $w, $w, min($w_src,$h_src), min($w_src,$h_src));
                 */
            }

            $this->clearMemory();

            /**
             * Сохранение полученного изображения в свойство класса.
             */
            $this->img = $new_image;
        } else {
            /**
             * Сохранение в свойство для дальнейшего изменения размера изображения при сохранении с помощью библиотеки.
             */
            $this->imagemagick_params = [
                'width' => $lenght,
                'height' => $lenght,
                'cut_square' => true,
            ];
        }

        return $this;
    }

    /**
     * Изменение размеров текущего изображения.
     *
     * @param int $width
     * @param int $height
     * @return $this
     */
    public function resize($width, $height)
    {

        if($this->img_type != IMAGETYPE_PNG) {
            /**
             * Создание пустого изображения нужной ширины и высоты.
             */
            $new_image = imagecreatetruecolor($width, $height);

            if ($this->img_type == IMAGETYPE_GIF || $this->img_type == IMAGETYPE_PNG) {
                $this->saveAlphaImage($new_image);
            }

            /**
             * Изменение размеров без потерь.
             */
            imagecopyresampled($new_image, $this->img, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());

            $this->clearMemory();

            /**
             * Сохранение полученного изображения в свойство класса.
             */
            $this->img = $new_image;
        } else {
            /**
             * Сохранение в свойство для дальнейшего изменения размера изображения при сохранении с помощью библиотеки.
             */
            $this->imagemagick_params = [
                'width' => $width,
                'height' => $height,
                'cut_square' => false,
            ];
        }

        return $this;
    }

    /**
     * Проверка на изменение размеров в меньшую сторону.
     *
     * @param int $width
     * @param int|bool $height
     * @return bool
     */
    public function checkDownSizesOriginalToResize($width, $height = false)
    {
        if($width < $this->getWidth()) {
            return true;
        }

        if($height !== false && $height < $this->getHeight()) {
            return true;
        }

        return false;
    }

    /**
     * Изменение размеров изображения с помощью библиотеки imagemagick.
     *
     * @param string $new_path
     * @param int $quality
     * @return bool
     */
    private function resizeImageMagic($new_path, $quality = 100)
    {
        if(!count($this->imagemagick_params)) {
            $command = [$this->path, '-quality', $quality, $new_path];
        } elseif ($this->imagemagick_params['cut_square']) {
            $command = [$this->path, '-geometry', "{$this->imagemagick_params['width']}x{$this->imagemagick_params['height']}^", '-gravity', 'center', '-crop', "{$this->imagemagick_params['width']}x{$this->imagemagick_params['height']}+0+0", '-quality', $quality, $new_path];
        } else {
            $command = [$this->path, '-resize', "{$this->imagemagick_params['width']}x{$this->imagemagick_params['height']}!", '-quality', $quality, $new_path];
        }

        $command_path = config('app_audit.library_pathes.imagemagick');

        if($command_path == null) {
            throw new Exception('Не указан путь к комманде convert ( imagemagick )');
        }

        $process = new Process(array_merge([$command_path], $command));

        $result = $process->run();

        if($result !== 0) {
            throw new Exception($process->getExitCodeText());
        }

        return !$result;
    }

    /**
     * Установка параметров необходимых для сохранения альфа фона у PNG и GIF.
     *
     * @param $img
     */
    private function saveAlphaImage(&$img)
    {
        imagealphablending($img, false);
        imagesavealpha($img, true);
        $transparent = imagecolorallocatealpha($img, 255, 255, 255, 127);
        imagefill($img, 0, 0, $transparent);
        imagecolortransparent($img, $transparent);
    }

    /**
     * Очистка памяти от текущего ресурса изображения.
     *
     * @return bool
     */
    public function clearMemory()
    {
        return imagedestroy($this->img);
    }

    //****************************************************************
    //************************** Setters *****************************
    //****************************************************************

    /**
     * Установка минимально допустимой ширины для изображения.
     *
     * @param int $min_width
     * @return $this
     */
    public function setMinWidth($min_width)
    {
        $this->min_width = $min_width;

        return $this;
    }

    /**
     * Установка минимально допустимой высоты для изображения.
     *
     * @param int $min_height
     * @return $this
     */
    public function setMinHeight($min_height)
    {
        $this->min_height = $min_height;

        return $this;
    }

    //****************************************************************
    //************************** Getters *****************************
    //****************************************************************

    /**
     * Получение ширины изображения.
     *
     * @return false|int
     */
    public function getWidth()
    {
        return imagesx($this->img);
    }

    /**
     * Получение высоты изображения.
     *
     * @return false|int
     */
    public function getHeight()
    {
        return imagesy($this->img);
    }

    /**
     * Получение типа изображения.
     *
     * @return string
     */
    public function getImgType()
    {
        return $this->img_type;
    }

    //****************************************************************
    //************************** Support *****************************
    //****************************************************************

    /**
     * Проверка включена ли библиотека GD.
     *
     * @return bool
     */
    private function isGDEnabled()
    {
        return extension_loaded('gd');
    }
}