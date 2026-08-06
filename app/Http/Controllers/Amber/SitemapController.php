<?php

namespace App\Http\Controllers\Amber;

use App\Models\Articles\Article;
use App\Models\Articles\Rubric;
use App\Models\Catalog;
use App\Models\Catalog\Category;
use App\Models\Catalog\Product;
use App\Models\Page;
use App\Models\Service;
use App\Services\Localization;
use XMLWriter;

class SitemapController
{
    public function index()
    {
        $xml = new XMLWriter;
        $xml->openMemory();
        $xml->startDocument('1.0', 'UTF-8');
        $xml->setIndent(true);
        $xml->setIndentString("\t");
        $xml->startElement('urlset');
        $xml->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $xml->writeAttribute('xmlns:xhtml', 'http://www.w3.org/1999/xhtml');

        // Pages
        foreach (Page::site()->published()->get() as $page) {
            foreach ((new Localization)->available() as $locale) {
                $this->addUrl($xml, entity: $page, locale: $locale['locale']);
            }
        }

        // Articles
        foreach (Rubric::items() as $rubric) {
            foreach ((new Localization)->available() as $locale) {
                $this->addUrl($xml, entity: $rubric, locale: $locale['locale']);
            }
        }

        foreach (Article::whereHas('rubrics')->published()->get() as $article) {
            foreach ((new Localization)->available() as $locale) {
                $this->addUrl($xml, entity: $article, locale: $locale['locale']);
            }
        }

        // Services
        foreach (Service::published()->get() as $item) {
            foreach ((new Localization)->available() as $locale) {
                $this->addUrl($xml, entity: $item, locale: $locale['locale']);
            }
        }

        // Catalog
        foreach ((new Localization)->available() as $locale) {
            $this->addUrl($xml, entity: (object) [
                'raw_url' => '/catalog',
            ], locale: $locale['locale']);
        }
        foreach (Catalog::visible()->get() as $item) {
            foreach ((new Localization)->available() as $locale) {
                $this->addUrl($xml, entity: $item, locale: $locale['locale']);
            }
        }

        $xml->endElement(); // urlset
        $xml->endDocument();
        $content = $xml->outputMemory(true);

        return response($content, 200, ['Content-Type' => 'application/xml']);
    }

    protected function addUrl(XMLWriter $xml, $entity, $locale)
    {
        $xml->startElement('url');
        $xml->writeElement('loc', locale_url($entity->raw_url, absolute: true, locale: $locale));
        $default = (new Localization)->default();

        foreach ((new Localization)->available() as $item) {
            $xml->startElementNS('xhtml', 'link', 'http://www.w3.org/1999/xhtml');
            $xml->writeAttribute('rel', 'alternate');
            $xml->writeAttribute('hreflang', $item['code']);
            $xml->writeAttribute('href', locale_url($entity->raw_url, absolute: true, locale: $item['locale']));
            $xml->endElement();
        }

        $xml->startElementNS('xhtml', 'link', 'http://www.w3.org/1999/xhtml');
        $xml->writeAttribute('rel', 'alternate');
        $xml->writeAttribute('hreflang', 'x-default');
        $xml->writeAttribute('href', locale_url($entity->raw_url, absolute: true, locale: $default['locale']));
        $xml->endElement();

        $xml->endElement(); // url
    }
}
