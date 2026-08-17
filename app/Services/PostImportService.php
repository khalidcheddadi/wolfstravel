<?php

namespace App\Services;

use App\Models\Listing\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;

class PostImportService
{
    public function import(int $limit = 10): array
    {
        $articles = $this->fetchArticles($limit);
        $created = [];
        $skipped = 0;

        $categoryId = Category::query()->value('id');
        $userId = User::query()->whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->value('id') ?? User::query()->value('id');

        foreach ($articles as $article) {
            $slug = $this->makeUniqueSlug($article['title']);

            $existing = Post::query()->where('slug', $slug)->exists();
            if ($existing) {
                $skipped++;
                continue;
            }

            $post = Post::create([
                'title' => $article['title'],
                'slug' => $slug,
                'excerpt' => $article['excerpt'],
                'content' => $article['content'],
                'category_id' => $categoryId,
                'user_id' => $userId,
                'is_published' => true,
                'published_at' => now(),
            ]);

            if (!empty($article['image_url'])) {
                try {
                    $post->addMediaFromUrl($article['image_url'])->toMediaCollection('images');
                } catch (\Throwable $e) {
                    // Keep the post even if the image download fails.
                }
            }

            $created[] = $post->id;
        }

        return [
            'created' => count($created),
            'skipped' => $skipped,
            'post_ids' => $created,
        ];
    }

    private function fetchArticles(int $limit): array
    {
        $remoteArticles = $this->tryFetchRemoteArticles();
        if (!empty($remoteArticles)) {
            return array_slice($remoteArticles, 0, $limit);
        }

        return $this->buildFallbackArticles($limit);
    }

    private function tryFetchRemoteArticles(): array
    {
        try {
            $response = @file_get_contents('https://api.rss2json.com/v1/api.json?rss_url=https://www.theguardian.com/travel/rss');
            if ($response === false) {
                return [];
            }

            $data = json_decode($response, true);
            if (empty($data['items']) || !is_array($data['items'])) {
                return [];
            }

            $articles = [];
            foreach ($data['items'] as $item) {
                $title = strip_tags((string) ($item['title'] ?? ''));
                if ($title === '' || str_contains(strtolower($title), 'europe')) {
                    continue;
                }

                $articles[] = [
                    'title' => $this->cleanTitle($title),
                    'excerpt' => $this->cleanText($item['description'] ?? ''),
                    'content' => $this->buildContent($title, $item['description'] ?? ''),
                    'image_url' => $item['thumbnail'] ?? null,
                ];

                if (count($articles) >= 10) {
                    break;
                }
            }

            return $articles;
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function buildFallbackArticles(int $limit): array
    {
        $templates = [
            [
                'title' => 'Experiencias inolvidables en Marrakech, Marruecos',
                'excerpt' => 'Una escapada cultural con zocos, jardines secretos y noches bajo las estrellas en el desierto.',
                'content' => '<p>Explora Marrakech como un destino de lujo cultural donde los zocos, las fuentes y los patios tradicionales se mezclan con experiencias modernas.</p><p>Los viajeros pueden disfrutar de recorridos por palacios históricos, cenas en terrazas con vistas al Atlas y excursiones al desierto para vivir una experiencia auténtica.</p><ul><li>Recorridos guiados por la medina</li><li>Excursiones al desierto de Sahara</li><li>Clases de cocina marroquí</li></ul>',
                'image_url' => 'https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'title' => 'Caminatas y aventuras en Cusco, Perú',
                'excerpt' => 'Descubre montañas, ruinas y pueblos andinos en una ruta llena de historia y naturaleza.',
                'content' => '<p>Cusco combina paisajes impresionantes con una rica historia inca que se vive en cada calle y en cada vista panorámica.</p><p>Desde caminatas por los Andes hasta visitas a ruinas milenarias, esta ruta es ideal para quienes desean combinar aventura y cultura.</p><ul><li>Ruta por la montaña de colores</li><li>Visita a Machu Picchu</li><li>Experiencia con comunidades locales</li></ul>',
                'image_url' => 'https://images.unsplash.com/photo-1526392060635-9d6019884377?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'title' => 'Globo aerostático sobre Cappadocia, Turquía',
                'excerpt' => 'Una de las experiencias más impresionantes del mundo para ver formaciones rocosas únicas.',
                'content' => '<p>El amanecer sobre los valles de Cappadocia es uno de los momentos más memorables para cualquier viajero.</p><p>La combinación de paisajes surrealistas, hoteles trogloditas y recorridos por pueblos antiguos convierte esta región en un destino fascinante.</p><ul><li>Vuelo en globo aerostático</li><li>Visita a pueblos trogloditas</li><li>Cena bajo estrellas</li></ul>',
                'image_url' => 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'title' => 'Rutas de ciclismo en Bali, Indonesia',
                'excerpt' => 'Una mezcla de cultura, arrozales y playas en una escapada activa y relajante.',
                'content' => '<p>Bali ofrece recorridos por terrazas de arroz, templos y pueblos costeros que permiten vivir un turismo mucho más profundo.</p><p>Es perfecto para quienes buscan un equilibrio entre actividad física, naturaleza y descanso.</p><ul><li>Ciclismo por arrozales</li><li>Visita a templos espirituales</li><li>Playa y spa al final del día</li></ul>',
                'image_url' => 'https://images.unsplash.com/photo-1537996194471-eb320f8f0f02?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'title' => 'Safari costero en Ciudad del Cabo, Sudáfrica',
                'excerpt' => 'Mar, montañas y fauna en un destino que une aventura y relajación.',
                'content' => '<p>Ciudad del Cabo ofrece paisajes espectaculares con rutas por la costa, playas vírgenes y safaris que conectan con la naturaleza.</p><p>Es una opción ideal para quienes quieren experimentar una mezcla de mar, montaña y vida salvaje.</p><ul><li>Safaris por la región</li><li>Recorridos por la costa atlántica</li><li>Degustación de vinos locales</li></ul>',
                'image_url' => 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'title' => 'Aventura invernal en Queenstown, Nueva Zelanda',
                'excerpt' => 'Esquí, lagos azules y paisajes de montaña para los amantes del aire libre.',
                'content' => '<p>Queenstown es uno de los destinos más completos para los viajeros que buscan adrenalina y paisajes únicos.</p><p>Desde deportes de invierno hasta cruises por lagos, cada día ofrece una experiencia diferente en un entorno espectacular.</p><ul><li>Esquí y snowboard</li><li>Excursiones en barco</li><li>Senderismo por montañas</li></ul>',
                'image_url' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'title' => 'Ruta gastronómica en Oaxaca, México',
                'excerpt' => 'Un viaje sensorial donde la comida, la cultura y las tradiciones se mezclan.',
                'content' => '<p>Oaxaca es una joya cultural donde los mercados, las recetas tradicionales y las celebraciones locales crean un ambiente único.</p><p>Los viajeros pueden disfrutar de experiencias culinarias auténticas, talleres y recorridos por ciudades históricas.</p><ul><li>Clases de cocina tradicional</li><li>Mercados locales</li><li>Festivales culturales</li></ul>',
                'image_url' => 'https://images.unsplash.com/photo-1541625602330-2277a4c46182?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'title' => 'Escapada de playa y naturaleza en Phuket, Tailandia',
                'excerpt' => 'Islas cristalinas, mercados locales y actividades acuáticas para una ruta completa.',
                'content' => '<p>Phuket ofrece una combinación interesante entre playas, vida marina y cultura local.</p><p>Es perfecto para quienes quieren disfrutar de actividades en el mar y al mismo tiempo explorar mercados, templos y pueblos tradicionales.</p><ul><li>Snorkel y buceo</li><li>Excursiones en islas cercanas</li><li>Recorridos culturales por la ciudad</li></ul>',
                'image_url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80',
            ],
        ];

        return array_slice($templates, 0, $limit);
    }

    private function cleanTitle(string $title): string
    {
        return trim(strip_tags($title));
    }

    private function cleanText(string $text): string
    {
        $text = strip_tags($text);
        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text);
    }

    private function buildContent(string $title, string $description): string
    {
        $summary = $this->cleanText($description);
        if (strlen($summary) < 20) {
            $summary = 'Una experiencia única para quienes aman descubrir destinos auténticos y llenos de cultura.';
        }

        return sprintf(
            '<p>%s</p><p>Este artículo destaca por qué este destino sigue captando la atención de viajeros que buscan experiencias memorables, actividades originales y paisajes sorprendentes.</p><p>La ruta propuesta mezcla cultura local, gastronomía, naturaleza y momentos de relajación para crear un viaje equilibrado y enriquecedor.</p>',
            e($summary)
        );
    }

    private function makeUniqueSlug(string $title): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 2;

        while (Post::query()->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
