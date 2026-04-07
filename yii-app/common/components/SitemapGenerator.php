<?php

namespace common\components;

use common\helpers\StringHelpers;
use common\models\{Applications, Gaz, Manufacture, News, Product};
use Yii;

class SitemapGenerator
{
    private const SITEMAP_MAIN = 'sitemap-main.xml';
    private const SITEMAP_NEWS = 'sitemap-news.xml';
    private const SITEMAP_APPLICATIONS = 'sitemap-applications.xml';
    private const SITEMAP_MANUFACTURE = 'sitemap-manufacture.xml';
    private const SITEMAP_MANUFACTURE_PRODUCTS = 'sitemap-manufacture-products.xml';
    private const SITEMAP_GASES = 'sitemap-gases.xml';
    private const SITEMAP_PRODUCTS = 'sitemap-products.xml';
    private const SITEMAP_INDEX = 'sitemap.xml';

    /**
     * @param string $baseUrl
     * @param string $dir
     * @return array<string,int> counts of URLs per file
     */
    public function writeAll(string $baseUrl, string $dir): array
    {
        $baseUrl = rtrim($baseUrl, '/');

        $counts = [];
        $counts[self::SITEMAP_MAIN] = $this->writeUrlset($dir . '/' . self::SITEMAP_MAIN, $this->buildMainUrls($baseUrl));
        $counts[self::SITEMAP_NEWS] = $this->writeUrlset($dir . '/' . self::SITEMAP_NEWS, $this->buildNewsUrls($baseUrl));
        $counts[self::SITEMAP_APPLICATIONS] = $this->writeUrlset($dir . '/' . self::SITEMAP_APPLICATIONS, $this->buildApplicationsUrls($baseUrl));
        $counts[self::SITEMAP_MANUFACTURE] = $this->writeUrlset($dir . '/' . self::SITEMAP_MANUFACTURE, $this->buildManufactureUrls($baseUrl));
        $counts[self::SITEMAP_MANUFACTURE_PRODUCTS] = $this->writeUrlset($dir . '/' . self::SITEMAP_MANUFACTURE_PRODUCTS, $this->buildManufactureProductsUrls($baseUrl));
        $counts[self::SITEMAP_GASES] = $this->writeUrlset($dir . '/' . self::SITEMAP_GASES, $this->buildGasesUrls($baseUrl));
        $counts[self::SITEMAP_PRODUCTS] = $this->writeUrlset($dir . '/' . self::SITEMAP_PRODUCTS, $this->buildProductsUrls($baseUrl));

        $sitemapIndexUrls = [
            $baseUrl . '/' . self::SITEMAP_MAIN,
            $baseUrl . '/' . self::SITEMAP_NEWS,
            $baseUrl . '/' . self::SITEMAP_APPLICATIONS,
            $baseUrl . '/' . self::SITEMAP_MANUFACTURE,
            $baseUrl . '/' . self::SITEMAP_MANUFACTURE_PRODUCTS,
            $baseUrl . '/' . self::SITEMAP_GASES,
            $baseUrl . '/' . self::SITEMAP_PRODUCTS,
        ];

        $this->writeSitemapIndex($dir . '/' . self::SITEMAP_INDEX, $sitemapIndexUrls);

        return $counts;
    }

    /**
     * @param string $filePath
     * @param iterable<string> $urls
     * @return int
     */
    private function writeUrlset(string $filePath, iterable $urls): int
    {
        $count = 0;
        $fh = fopen($filePath, 'wb');
        if ($fh === false) {
            throw new \RuntimeException("Не удалось открыть файл {$filePath}");
        }

        fwrite($fh, "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n");
        fwrite($fh, "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n");

        foreach ($urls as $url) {
            $url = trim((string)$url);
            if ($url === '') {
                continue;
            }
            $loc = htmlspecialchars($url, ENT_QUOTES | ENT_XML1, 'UTF-8');
            fwrite($fh, "  <url><loc>{$loc}</loc></url>\n");
            $count++;
        }

        fwrite($fh, "</urlset>\n");
        fclose($fh);

        return $count;
    }

    /**
     * @param string $filePath
     * @param array<string> $sitemapUrls
     * @return void
     */
    private function writeSitemapIndex(string $filePath, array $sitemapUrls): void
    {
        $fh = fopen($filePath, 'wb');
        if ($fh === false) {
            throw new \RuntimeException("Не удалось открыть файл {$filePath}");
        }

        $lastmod = date('c');
        fwrite($fh, "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n");
        fwrite($fh, "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n");
        foreach ($sitemapUrls as $url) {
            $loc = htmlspecialchars($url, ENT_QUOTES | ENT_XML1, 'UTF-8');
            fwrite($fh, "  <sitemap><loc>{$loc}</loc><lastmod>{$lastmod}</lastmod></sitemap>\n");
        }
        fwrite($fh, "</sitemapindex>\n");
        fclose($fh);
    }

    /**
     * @param string $baseUrl
     * @return iterable<string>
     */
    private function buildMainUrls(string $baseUrl): iterable
    {
        $paths = [
            '/',
            '/gases',
            '/catalog',
            '/news',
            '/applications',
            '/manufacture',
            '/converter',
            '/remains',
            '/page/accessories',
            '/page/vacancy',
            '/page/privacy',
            '/page/contacts',
        ];

        $paths = array_values(array_unique($paths));

        foreach ($paths as $path) {
            yield $baseUrl . $path;
        }
    }

    /**
     * @param string $baseUrl
     * @return iterable<string>
     */
    private function buildNewsUrls(string $baseUrl): iterable
    {
        $query = News::find()
            ->select(['slug'])
            ->where(['not', ['slug' => null]])
            ->andWhere(['<>', 'slug', ''])
            ->orderBy(['id' => SORT_ASC])
            ->asArray();

        foreach ($query->batch(1000) as $rows) {
            foreach ($rows as $row) {
                $slug = trim((string)($row['slug'] ?? ''));
                if ($slug !== '') {
                    yield $baseUrl . '/news/' . $slug;
                }
            }
        }
    }

    /**
     * @param string $baseUrl
     * @return iterable<string>
     */
    private function buildApplicationsUrls(string $baseUrl): iterable
    {
        $query = Applications::find()
            ->select(['slug'])
            ->where(['not', ['slug' => null]])
            ->andWhere(['<>', 'slug', ''])
            ->orderBy(['id' => SORT_ASC])
            ->asArray();

        foreach ($query->batch(1000) as $rows) {
            foreach ($rows as $row) {
                $slug = trim((string)($row['slug'] ?? ''));
                if ($slug !== '') {
                    yield $baseUrl . '/applications/' . $slug;
                }
            }
        }
    }

    /**
     * @param string $baseUrl
     * @return iterable<string>
     */
    private function buildManufactureUrls(string $baseUrl): iterable
    {
        $query = Manufacture::find()
            ->select(['slug'])
            ->where(['not', ['slug' => null]])
            ->andWhere(['<>', 'slug', ''])
            ->orderBy(['id' => SORT_ASC])
            ->asArray();

        foreach ($query->batch(1000) as $rows) {
            foreach ($rows as $row) {
                $slug = trim((string)($row['slug'] ?? ''));
                if ($slug !== '') {
                    yield $baseUrl . '/manufacture/' . $slug;
                }
            }
        }
    }

    /**
     * @param string $baseUrl
     * @return iterable<string>
     */
    private function buildManufactureProductsUrls(string $baseUrl): iterable
    {
        $query = Manufacture::find()
            ->select(['title'])
            ->orderBy(['id' => SORT_ASC])
            ->asArray();

        foreach ($query->batch(1000) as $rows) {
            foreach ($rows as $row) {
                $title = trim((string)($row['title'] ?? ''));
                if ($title === '') {
                    continue;
                }
                $slug = StringHelpers::slug($title);
                if ($slug !== '') {
                    yield $baseUrl . '/catalog/' . $slug;
                }
            }
        }
    }

    /**
     * @param string $baseUrl
     * @return iterable<string>
     */
    private function buildGasesUrls(string $baseUrl): iterable
    {
        $query = Gaz::find()
            ->select(['slug'])
            ->where(['not', ['slug' => null]])
            ->andWhere(['<>', 'slug', ''])
            ->orderBy(['id' => SORT_ASC])
            ->asArray();

        foreach ($query->batch(1000) as $rows) {
            foreach ($rows as $row) {
                $slug = trim((string)($row['slug'] ?? ''));
                if ($slug !== '') {
                    yield $baseUrl . '/catalog/' . $slug;
                }
            }
        }
    }

    /**
     * @param string $baseUrl
     * @return iterable<string>
     */
    private function buildProductsUrls(string $baseUrl): iterable
    {
        $query = Product::find()
            ->alias('product')
            ->select(['product.slug AS product_slug', 'gaz.slug AS gaz_slug'])
            ->leftJoin('product_gaz', 'product_gaz.product_id = product.id AND product_gaz.is_main = 1')
            ->leftJoin('gaz', 'gaz.id = product_gaz.gaz_id')
            ->where(['not', ['product.slug' => null]])
            ->andWhere(['<>', 'product.slug', ''])
            ->orderBy(['product.id' => SORT_ASC])
            ->asArray();

        foreach ($query->batch(1000) as $rows) {
            foreach ($rows as $row) {
                $productSlug = trim((string)($row['product_slug'] ?? ''));
                if ($productSlug === '') {
                    continue;
                }
                $gazSlug = trim((string)($row['gaz_slug'] ?? ''));
                if ($gazSlug !== '') {
                    yield $baseUrl . '/catalog/' . $gazSlug . '/' . $productSlug;
                } else {
                    yield $baseUrl . '/product/' . $productSlug;
                }
            }
        }
    }
}
