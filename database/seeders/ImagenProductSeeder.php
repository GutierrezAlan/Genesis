<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Seeder;

class ImagenProductSeeder extends Seeder
{
    public function run(): void
    {
        $file = storage_path('app/productos_imagenes.json');

        if (!file_exists($file)) {
            $this->command->error('No existe el archivo JSON');
            return;
        }

        $images = json_decode(file_get_contents($file), true);

        foreach ($images as $image) {

            Image::create([
                'product_id' => $image['product_id'],
                'filename'   => $image['filename'],
                'path'       => $image['path'],
                'mime_type'  => $image['mime_type'],
                'size'       => $image['size'],
            ]);
        }

        $this->command->info('Imágenes importadas correctamente.');
    }
}