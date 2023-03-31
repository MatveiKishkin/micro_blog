<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;

class PostImage implements Rule
{
    /**
     * Максимальный размер файла.
     */
    const IMAGE_MAX_SIZE = 1024*1024*10;

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  UploadedFile  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $is_image = in_array($value->getMimeType(), [
            'image/jpg',
            'image/jpeg',
            'image/png',
        ]);

        if (!$is_image) {
            $fail('Получен не поддерживаемый формат изображения или видео.');
        }

        if ($is_image) {
            if ($value->getSize() > self::IMAGE_MAX_SIZE) {
                $fail('Изображение должно быть размером менее 10МБ');
            }
        }

    }

    public function passes($attribute, $value)
    {
        // TODO: Implement passes() method.
    }

    public function message()
    {
        // TODO: Implement message() method.
    }
}
