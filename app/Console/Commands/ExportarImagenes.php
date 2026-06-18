<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class ExportarImagenes extends Command
{
    protected $signature = 'exportar:imagenes';

    protected $description = 'Exporta las imágenes de productos a un archivo JSON';

    public function handle()
    {
        $data = Image::all()->map(function ($image) {

            return [
                'product_id' => $image->product_id,
                'filename'   => $image->filename,
                'path'       => $image->path,
                'mime_type'  => $image->mime_type,
                'size'       => $image->size,
            ];
        });

        Storage::disk('local')->put(
            'productos_imagenes.json',
            json_encode(
                $data,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );

        $this->info('Archivo productos_imagenes.json generado correctamente.');

        return Command::SUCCESS;
    }
}
