<?php

namespace App\Base\Resources\PublicImages;

class BlogPost extends PublicImages
{
    /**
     * Тип сущности для файла.
     *
     * @var string
     */
    protected $entity_type = 'blog_post';

    /**
     * Использовать компрессию.
     *
     * @var bool
     */
    protected $compression = true;

    /**
     * Путь к папке с изображениями.
     *
     * @param bool $need_original
     * @return string
     */
    protected function getImagePath($need_original = false)
    {
        $path = implode('/', [config('lfm.folder_categories.image.folder_name'), config('lfm.shared_folder_name')]);

        if(!empty($need_original)) {
            $path .= '_original';
        }

        return $path;
    }
}