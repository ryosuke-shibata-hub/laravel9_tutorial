<?php
declare(strict_types=1);
namespace App\Modules\ImageUpload;

interface ImageManagerInterface
{
    public function save($file): string;
    public function delete(string $name): void;
}