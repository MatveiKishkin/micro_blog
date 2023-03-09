<?php


namespace App\Base\Image;

use App\Base\Image\Compress\Compress;
use App\Base\Image\SimpleImage\SimpleImage;
use Illuminate\Http\Request;
use Throwable;

class Image
{
    /**
     * @var \App\Base\Image\SimpleImage\SimpleImage
     */
    protected $simple_image;

    /**
     * Image constructor.
     *
     * @param SimpleImage $simple_image
     */
    public function __construct(SimpleImage $simple_image)
    {
        $this->simple_image = $simple_image;
    }

    /**
     * Сжатие изображения.
     *
     * @param string $path
     * @param string $new_path
     * @param int $width
     * @param int|bool $height
     * @param int|bool $min_width
     * @param int|bool $min_height
     * @param int $quality
     * @return bool
     */
    public function compression(string $path, string $new_path, $width, $height = false, $min_width = false, $min_height = false, $quality = 75) : bool
    {
        if($min_width) {
            $this->simple_image->setMinWidth($min_width);
        }
        if($min_height) {
            $this->simple_image->setMinHeight($min_height);
        }

        $path = public_path($path);
        $new_path = public_path($new_path);

        try {

            $this->simple_image->load($path);

            if($this->simple_image->checkDownSizesOriginalToResize($width, $height)) {
                if (!empty($height)) {
                    if (($width == $height) && ($this->simple_image->getWidth() != $this->simple_image->getHeight())) {
                        $this->simple_image->cutSquareImage($width)
                            ->save($new_path, $quality);
                    } else {
                        $this->simple_image->resize($width, $height)
                            ->save($new_path, $quality);
                    }
                } else {
                    $this->simple_image->resizeToWidth($width)
                        ->save($new_path, $quality);
                }
            } else {
                $this->simple_image->save($new_path, $quality);
            }

        } catch (Throwable $e) {
            alt_log()->file('error_resize_image')->exception($e, $e->getMessage());
            return false;
        }

        $this->compressQuality($new_path, $quality);

        return true;
    }

    /**
     * Сжатие изображения по качеству.
     *
     * @param string $path
     * @param int $quality
     * @return bool
     */
    public function compressQuality(string $path, $quality = 75) : bool
    {
        try {

            /**
             * Сжатие изображения библиотеками.
             */
            Compress::compress(public_path($path), $quality, $quality);

        } catch (Throwable $e) {
            alt_log()->file('error_compress_image_library')->exception($e, $e->getMessage());
        }

        return true;
    }

    /**
     * Сжатие изображения для аудита с параметрами.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function compressionForAudit(Request $request)
    {
        /**
         * Установка минимально допустимой ширины и высоты для изображения.
         */
        $this->simple_image->setMinWidth($request->resize_min_width)->setMinHeight($request->resize_min_height);

        $path = public_path($request->resize_path);

        $new_path = public_path('compression'.$request->resize_new_path);

        /**
         * Удаление файла по пути с сохранением, если существует.
         */
        if(file_exists($new_path)) {
            unlink($new_path);
        }

        /**
         * Сжатие изображения при условии что флаг no_resize отстуствует.
         */
        if(empty($request->no_resize)) {

            try {

                $this->simple_image->load($path);

                if (isset($request->cut_square)) {
                    $this->simple_image->cutSquareImage($request->resize_width)
                                       ->save($new_path, $request->resize_quality);
                } elseif (isset($request->resize_height)) {
                    $this->simple_image->resize($request->resize_width, $request->resize_height)
                                       ->save($new_path, $request->resize_quality);
                } else {
                    $this->simple_image->resizeToWidth($request->resize_width)
                                       ->save($new_path, $request->resize_quality);
                }

            } catch (Throwable $e) {

                alt_log()->file('error_resize_image')->exception($e, 'Ошибка при изменении размеров изображения с помощью GD,ImageMagick');

                return [
                    'status' => 'error',
                    'old_path' => url($request->resize_path),
                    'message' => 'Ошибка при изменении размеров изображения с помощью GD,ImageMagick',
                ];
            }

        /**
         * Изменение качества изображения.
         */
        } elseif($request->resize_quality < 100) {

            try {
                $this->simple_image->load($path)
                    ->save($new_path, $request->resize_quality);
            } catch (Throwable $e) {

                alt_log()->file('error_resize_image')->exception($e, 'Ошибка при изменении качества изображения с помощью GD,ImageMagick');

                return [
                    'status' => 'error',
                    'old_path' => url($request->resize_path),
                    'message' => 'Ошибка при изменении качества изображения с помощью GD,ImageMagick',
                ];
            }
        /**
         * Копирование изображения в новую папку.
         */
        } else {
            if(file_exists($new_path)) {
                unlink($new_path);
            } elseif (!file_exists(dirname($new_path))) {
                mkdir(dirname($new_path), 0775, true);
            }

            copy($path, $new_path);
        }

        /**
         * Сжатие изображения библиотеками при условии что флаг compression был включен.
         */
        if(!empty($request->compression)) {
            try {
                Compress::compress($new_path,
                    !empty($request->resize_min_quality_library) ? $request->resize_min_quality_library : $request->resize_quality,
                    !empty($request->resize_max_quality_library) ? $request->resize_max_quality_library : $request->resize_quality);
            } catch (Throwable $e) {

                alt_log()->file('error_compress_image_library')->exception($e, 'Ошибка сжатия изображения библиотеками');

                /**
                 * Удаление файла по пути с сохранением, если существует.
                 */
                if(file_exists($new_path)) {
                    unlink($new_path);
                }

                return [
                    'status' => 'error',
                    'old_path' => url($request->resize_path),
                    'message' => 'Ошибка сжатия изображения библиотеками',
                ];
            }
        }

        /**
         * Получение размеров нового изображения.
         */
        $this->simple_image->load($new_path);

        $width = $this->simple_image->getWidth();
        $height = $this->simple_image->getHeight();

        /**
         * Очистка памяти от загруженного ресурса изображения.
         */
        $this->simple_image->clearMemory();

        return [
            'status' => 'success',
            'sizes' => [
                'width' => $width,
                'height' => $height,
            ],
            'old_path' => url($request->resize_path),
            'new_path' => url('compression'.$request->resize_new_path),
            'old_size' => filesize($path),
            'new_size' => filesize($new_path),
        ];
    }
}