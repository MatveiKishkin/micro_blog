<?php

namespace App\Base\Resources\PublicImages;

use App\Models\BlogPost;
use ProfilanceGroup\BackendSdk\Exceptions\OperationError;
use App\Jobs\File\CompressEntityImage;
use Illuminate\Filesystem\FilesystemManager as Filesystem;
use Illuminate\Http\UploadedFile as File;
use Illuminate\Support\Arr;

abstract class PublicImages
{
    /**
     * Тип сущности для файла.
     *
     * @var string
     */
    protected $entity_type;

    /**
     * Использовать компрессию.
     *
     * @var bool
     */
    protected $compression = false;

    /**
     * Создание объекта реализации.
     *
     * @param string $entity_type
     * @return \App\Base\Resources\PublicImages\Employee|\App\Base\Resources\PublicImages\Testimonial|\Illuminate\Contracts\Foundation\Application|mixed
     * @throws \Exception
     */
    public static function create($entity_type)
    {
        return match ($entity_type) {
            'blog_post' => app(BlogPost::class),
            default => throw new \Exception("Неизвестный тип сущности {$entity_type}"),
        };
    }

    /**
     * Замена изображения.
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @param null|string $old_path
     * @param int|null $entity_id
     * @return string
     */
    public function replaceImage(File $image, $old_path, $entity_id = null)
    {
        if(!empty($old_path)) {
            $this->removeImage($old_path);
        }

        return $this->uploadImage($image, $entity_id);
    }

    /**
     * Загрузка изображения.
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @param int|null $entity_id
     * @return string
     */
    public function uploadImage(File $image, $entity_id = null)
    {
        /**
         * Проверка изображения.
         */
        $this->checkImage($image);

        /**
         * Сохранение изображения
         * @var \Illuminate\Contracts\Filesystem\Filesystem $filesystem
         */
        $filesystem = app(FileSystem::class)->disk('public');

        $path = $filesystem->putFile($this->getImagePath($this->compression), $image);

        /**
         * Сжатие.
         */
        if($this->compression) {

            $file_name = last(explode('/', $path));

            $path = $this->copyOriginalImage($file_name);

            CompressEntityImage::dispatch($file_name, $this->entity_type)->onQueue('queue_2');

        }

        return $path;
    }

    /**
     * Загрузка закодированного изображения.
     *
     * @param string $file
     * @return string
     */
    public function uploadOfBase64Image($file)
    {
        /**
         * Декодирование файла.
         */
        $file_data = base64_decode(Arr::last(explode(',', $file)));

        /**
         * Создание временного файла и получение абсолютного пути.
         */
        $temp_file_dir = storage_path('app/tmp/cropped_images');

        if (!file_exists($temp_file_dir)) {
            mkdir($temp_file_dir, 0755, true);
        }

        $temp_file_path = tempnam($temp_file_dir, 'cropped_image');

        /**
         * Сохранение во временный файл.
         */
        file_put_contents($temp_file_path, $file_data);

        $temp_file_object = new \Illuminate\Http\File($temp_file_path);
        $file = new File(
            $temp_file_object->getPathname(),
            $temp_file_object->getFilename(),
            $temp_file_object->getMimeType(),
            0,
            true
        );

        try {

            /**
             * Загрузка изображения.
             */
            return $this->uploadImage($file);
        } finally {

            /**
             * Удаление временного файла.
             */
            unlink($temp_file_path);
        }
    }

    /**
     * Удаление изображения.
     *
     * @param string $path
     * @param bool $full_remove
     */
    public function removeImage($path, $full_remove = true)
    {
        /**
         * Удаление используемого изображения
         */
        if(empty($path)) {
            return;
        }

        $path = public_path($path);

        if (!is_file($path) || !file_exists($path)) {
            return;
        }

        unlink($path);

        /**
         * Удаление миниатюры и оригинального изображения.
         */
        if($this->compression && $full_remove) {
            $file_name = last(explode('/', $path));
            $this->removeImage($this->getImagePath(true) . '/' . $file_name, false);
            $this->removeImage($this->getImagePath() . '/' . \Template::tools()->addPrefixImagePath($file_name), false);
        }

    }

    /**
     * Сжатие изображения.
     *
     * @param string $file_name
     * @return string
     */
    public function compressImage($file_name)
    {
        $path = $this->getImagePath().'/'.$file_name;

        switch($this->entity_type) {

            case 'employees':

                try {

                    /**
                     * Сжатие изображения до размеров 828x828 с минимально допустимой ширной и высотой при сжатии 414.
                     */
                    $this->compressOrCopy($path, $path, 828, 828, 414, 414);

                    /**
                     * Сжатие изображения для миниатюры.
                     */
                    $features_path = \Template::tools()->addPrefixImagePath($path);

                    /**
                     * Сжатие изображения до размеров 148x148 с минимально допустимой ширной и высотой при сжатии 74.
                     */
                    $this->compressOrCopy($features_path, $features_path, 148, 148, 74, 74);
                } catch (\Throwable $e) {
                    alt_log()->file('error_compress_image_library')->exception($e, "Ошибка при попытке сжать изображения сотрудника ".json_encode([$path, $features_path ?? 'Ошибка на сжатии основного изображения']));
                }

                break;

            case 'zoo_hotels':

                try {
                    /**
                     * Сжатие изображения для миниатюры.
                     */
                    $features_path = \Template::tools()->addPrefixImagePath($path);

                    /**
                     * Сжатие изображения до размеров 240x240 с минимально допустимой ширной и высотой при сжатии 120.
                     */
                    $this->compressOrCopy($features_path, $features_path, 240, 240, 120, 120);

                } catch (\Throwable $e) {
                    alt_log()->file('error_compress_image_library')->exception($e, "Ошибка при попытке сжать изображения зоогостиницы ".json_encode([$path, $features_path ?? 'Ошибка на сжатии основного изображения']));
                }

                break;

            case 'testimonial':

                /**
                 * Сжатие изображения до размеров 160x160 с минимально допустимой ширной и высотой при сжатии 80.
                 */
                $this->compressOrCopy($path, $path, 160, 160, 80, 80);

                break;

            case 'employee_review':

                /**
                 * Сжатие изображения до размеров 120x120 с минимально допустимой ширной и высотой при сжатии 80.
                 */
                $this->compressOrCopy($path, $path, 120, 120, 60, 60);

                break;

            default:
                app(\App\Base\Image\Image::class)->compressQuality($path);
        }

        return $path;
    }

    //****************************************************************
    //************************** Support *****************************
    //****************************************************************

    /**
     * Копирование оригинального файла в папку с изображениями сущности.
     *
     * @param string $file_name
     * @return string
     * @throws \Exception
     */
    private function copyOriginalImage($file_name)
    {
        $original_path = $this->getImagePath(true).'/'.$file_name;
        $new_path = $this->getImagePath().'/'.$file_name;

        if(!copy(public_path($original_path), public_path($new_path))) {
            alt_log()->file('error_copy_file')->error("Ошибка при копировании изображения {$original_path} > {$new_path}");
        }

        if($this->entity_type == 'employees' || $this->entity_type == 'zoo_hotels') {

            $features_path = \Template::tools()->addPrefixImagePath($new_path);

            if (!copy(public_path($original_path), public_path($features_path))) {
                alt_log()->file('error_copy_file')->error("Ошибка при копировании изображения {$original_path} > {$new_path}");
            }

        }

        return $new_path;
    }

    /**
     * Путь к папке с изображениями.
     *
     * @param bool $need_original
     * @return string
     */
    protected function getImagePath($need_original = false)
    {
        $original = $need_original ? '_original' : '';

        return "assets/server/{$this->entity_type}{$original}_images";
    }

    /**
     * Проверка файла.
     *
     * @param File $image
     * @throws OperationError
     */
    protected function checkImage(File $image)
    {
        /**
         * Тип файла - валидный.
         */
        if (!$this->checkMimeType($image->getMimeType())) {
            throw new OperationError('Не поддерживаемый тип файла.');
        }

        /**
         * Размер файла не превышает допустимые значения.
         */
        if (!$this->checkSize($image->getSize())) {
            throw new OperationError('Размер файла превышает максимально допустимое значение.');
        }
    }

    /**
     * Проверка mime типа аватара.
     *
     * @param string $mime_type
     * @return bool
     */
    protected function checkMimeType($mime_type)
    {
        $permitted = [
            'image/pjpeg',
            'image/jpeg',
            'image/gif',
            'image/x-png',
            'image/png',
        ];

        return in_array($mime_type, $permitted);
    }

    /**
     * Проверка размера аватара.
     *
     * @param int $size
     * @return bool
     */
    protected function checkSize($size)
    {
        return $size < 83886080; // 10Mb
    }

    /**
     * Сжатие изображения, в случае ошибки при сжатии скопировать файл по новому пути.
     *
     * @param string $path
     * @param string $new_path
     * @param int $width
     * @param int $height
     * @param int $min_width
     * @param int $min_height
     */
    protected function compressOrCopy($path, $new_path, $width, $height, $min_width, $min_height)
    {
        if(!app(\App\Base\Image\Image::class)->compression($path, $new_path, $width, $height, $min_width, $min_height)) {
            copy(public_path($path), public_path($new_path));
        }
    }

}