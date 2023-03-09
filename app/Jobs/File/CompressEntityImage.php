<?php

namespace App\Jobs\File;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class CompressEntityImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Количество раз, которое можно попробовать выполнить задачу.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * @var int
     */
    public $timeout = 60;

    /**
     * @var string
     */
    private $entity_type;

    private $file_name;

    /**
     * CompressEntityImage constructor.
     *
     * @param string $file_name
     * @param string $entity_type
     */
    public function __construct($file_name, $entity_type)
    {
        $this->entity_type = $entity_type;
        $this->file_name = $file_name;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        \App\Base\Resources\PublicImages\PublicImages::create($this->entity_type)->compressImage($this->file_name);
    }
}
