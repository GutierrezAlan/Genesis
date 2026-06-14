<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    private $productTemplates = [
        'Pantalon' => [
            'titles' => [
                'Pantalón Cargo {color}',
                'Pantalón Jean {color}',
                'Pantalón Deportivo {color}',
                'Pantalón Chino {color}',
                'Pantalón Slim Fit {color}',
            ],
            'descriptions' => [
                'Material 100% algodón, cómodo y duradero. Perfecta para el día a día.',
                'Tela elastizada que se adapta a tu cuerpo. Bolsillos laterales y traseros.',
                'Diseño moderno con cierre de botón. Ideal para trabajo o casual.',
                'Confeccionado en denim de calidad. Resistente y versátil.',
                'Con cintura ajustable. Múltiples bolsillos prácticos.',
            ],
            'colors' => ['Negro', 'Azul Oscuro', 'Gris', 'Beige', 'Blanco', 'Caqui'],
            'priceRange' => [15000, 35000],
        ],
        'Medias' => [
            'titles' => [
                'Medias Deportivas {color}',
                'Medias Largas {color}',
                'Medias Cortas {color}',
                'Medias Térmicas {color}',
                'Medias de Algodón {color}',
            ],
            'descriptions' => [
                'Algodón puro, suaves y transpirables. Perfectas para usar diariamente.',
                'Con tecnología anti-olor. Ideales para entrenamiento.',
                'Elasticidad perfecta, no se resbalan. Cómodas y duraderas.',
                'Material premium que mantiene tus pies secos y frescos.',
                'Costura reforzada que prolonga su vida útil.',
            ],
            'colors' => ['Blanco', 'Negro', 'Gris', 'Azul', 'Rojo'],
            'priceRange' => [2000, 8000],
        ],
        'Buzo' => [
            'titles' => [
                'Buzo Con Capucha {color}',
                'Buzo Oversize {color}',
                'Buzo Deportivo {color}',
                'Buzo Frizado {color}',
                'Buzo Urbano {color}',
            ],
            'descriptions' => [
                'Interior frizado para máxima calidez. Bolsillos al frente.',
                'Algodón de alta calidad, suave y duradero. Perfecto para invierno.',
                'Diseño moderno con detalles contrastantes. Cómodo y abrigado.',
                'Cierre frontal con cremallera. Capucha ajustable.',
                'Puños y borde acanalados. Material transpirable.',
            ],
            'colors' => ['Negro', 'Gris', 'Azul', 'Blanco', 'Marrón'],
            'priceRange' => [18000, 40000],
        ],
        'Camisa' => [
            'titles' => [
                'Camisa Casual {color}',
                'Camisa Formal {color}',
                'Camisa Cuadrille {color}',
                'Camisa Lino {color}',
                'Camisa Social {color}',
            ],
            'descriptions' => [
                'Tela de alta calidad, ideal para eventos formales. Fácil de cuidar.',
                'Ajuste clásico, versátil para múltiples ocasiones.',
                'Patrón a cuadros, perfecta para look casual. Manga larga.',
                'Tela 100% lino, respirable y cómoda. Ideal para climas cálidos.',
                'Confección impecable con botones de madreperla. Elegante.',
            ],
            'colors' => ['Blanco', 'Azul', 'Rojo', 'Negro', 'Beige'],
            'priceRange' => [15000, 45000],
        ],
        'Remera' => [
            'titles' => [
                'Remera Básica {color}',
                'Remera Estampada {color}',
                'Remera Deportiva {color}',
                'Remera Premium {color}',
                'Remera Ajustada {color}',
            ],
            'descriptions' => [
                'Algodón 100% orgánico, suave al tacto y transpirable.',
                'Corte clásico que combina con todo. De fácil lavado.',
                'Material técnico que absorbe la humedad. Perfecta para deporte.',
                'Tela premium de máxima calidad. Durabilidad garantizada.',
                'Diseño ajustado que resalta la figura. Estampado de moda.',
            ],
            'colors' => ['Blanco', 'Negro', 'Gris', 'Azul', 'Rojo', 'Verde'],
            'priceRange' => [8000, 20000],
        ],
        'Chaqueta' => [
            'titles' => [
                'Chaqueta Deportiva {color}',
                'Chaqueta Cuero {color}',
                'Chaqueta Nylon {color}',
                'Chaqueta Bomber {color}',
                'Chaqueta Invierno {color}',
            ],
            'descriptions' => [
                'Material resistente al agua. Mantiene el calor sin peso excesivo.',
                'Cierre con cremallera y botones. Bolsillos profundos.',
                'Diseño moderno con detalles urbanos. Versátil y práctica.',
                'Forro térmico aislante. Protección contra el frío intenso.',
                'Corte elegante, ideal para varios estilos.',
            ],
            'colors' => ['Negro', 'Gris', 'Azul', 'Marrón', 'Verde Oscuro'],
            'priceRange' => [35000, 80000],
        ],
    ];

    public function definition(): array
    {
        $category = $this->faker->randomElement(array_keys($this->productTemplates));
        $template = $this->productTemplates[$category];
        $color = $this->faker->randomElement($template['colors']);
        $titulo = $this->faker->randomElement($template['titles']);
        $titulo = str_replace('{color}', $color, $titulo);
        
        [$minPrice, $maxPrice] = $template['priceRange'];
        
        return [
            'titulo' => $titulo,
            'description' => $this->faker->randomElement($template['descriptions']),
            'category' => $category,
            'price' => $this->faker->randomFloat(2, $minPrice, $maxPrice),
            'stock' => $this->faker->numberBetween(5, 150),
        ];
    }

}