<?php

namespace App\Base\Image\Compress;

use App\Base\Image\SimpleImage\SimpleImage;
use Symfony\Component\Process\Process;
use Exception;

class Compress
{
    /**
     * Сжатие изображение с помощью библиотек.
     *
     * @param string $path
     * @param int $min_quality
     * @param int $max_quality
     * @return bool|null
     */
    public static function compress($path, $min_quality = 75, $max_quality = 80)
    {
        if (!file_exists($path)) {
            return false;
        }

        $img_type = (new SimpleImage())->load($path)->getImgType();

        return match (intval($img_type)) {
            IMAGETYPE_JPEG => self::compressJpg($path),
            IMAGETYPE_PNG => self::compressPng($path, $min_quality, $max_quality),
            default => false,
        };
    }

    /**
     * Сжатие png с помощью PNGQuant.
     *
     * @param string $path
     * @param int $min_quality
     * @param int $max_quality
     * @return bool
     * @throws Exception
     */
    private static function compressPng($path, $min_quality, $max_quality)
    {
        $binary_path = config('app_audit.library_pathes.pngquant');

        if($binary_path == null) {
            throw new Exception('Не указан путь к команде pngquant');
        }

        $flags = [
            "--output {$path}",
            "--force",
            "--quality {$min_quality}-{$max_quality}",
        ];

        return self::commandLine($binary_path, $path, $flags, true, true);
    }

    /**
     * Сжатие jpg с помощью JPegTran.
     *
     * @param string $path
     * @return bool
     * @throws Exception
     */
    private static function compressJpg($path)
    {
        $binary_path = config('app_audit.library_pathes.jpegtran');

        if($binary_path == null) {
            throw new Exception('Не указан путь к команде jpegtran');
        }

        /**
         * Ставим нужные флаги, в этих флагах удаляем служебную информацию, оптимизируем
         * и делаем изображение прогрессивным.
         */
        $flags = [
            '-copy none',
            '-perfect',
            '-optimize',
            '-progressive',
            '-outfile',
        ];

        return self::commandLine($binary_path, $path.' '.$path, $flags, true, true);
    }

    /**
     * Запуск командной строки.
     *
     * @param string $binary_path
     * @param string $command
     * @param array $flags
     * @param bool $flags_before
     * @param bool $without_escape
     * @return bool
     */
    private static function commandLine($binary_path, $command, array $flags, $flags_before = false, $without_escape = false)
    {
        $command = $flags_before ? array_merge([$binary_path], $flags, [$command]) : array_merge([$binary_path], [$command], $flags);

        $process = $without_escape ? Process::fromShellCommandline(implode(' ', $command)) : new Process($command);

        $result = $process->run();

        if($result !== 0) {
            throw new Exception($process->getExitCodeText());
        }

        return true;
    }

}