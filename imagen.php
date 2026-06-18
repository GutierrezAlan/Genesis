use App\Models\Image;
use Illuminate\Support\Facades\Storage;

$images = Image::all();

$data = $images->map(function ($image) {
    return [
        'product_id' => $image->product_id,
        'image_url'  => $image->url,
    ];
});

Storage::disk('local')->put(
    'imagenes.json',
    json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
);

dd('Archivo generado correctamente');