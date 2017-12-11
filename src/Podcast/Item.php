<?php

namespace Blockstars\Podcast;

use Suin\RSSWriter\SimpleXMLElement;

class Item extends \Suin\RSSWriter\Item
{

    protected $namespace = 'http://www.itunes.com/dtds/podcast-1.0.dtd';
    protected $episode = 1;
    protected $season = 1;
    protected $duration = '00:00';
    protected $explicit = true;
    protected $subtitle = '';
    protected $author = 'The Blockstars';
    protected $type = 'full';

    /**
     * @return string
     */
    public function getDuration(): string
    {
        return $this->duration;
    }

    /**
     * @param string $duration
     * @return Item
     */
    public function duration(string $duration): Item
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return bool
     */
    public function isExplicit(): bool
    {
        return $this->explicit;
    }

    /**
     * @param bool $explicit
     * @return Item
     */
    public function explicit(bool $explicit): Item
    {
        $this->explicit = $explicit;
        return $this;
    }

    /**
     * @return int
     */
    public function getEpisode(): int
    {
        return $this->episode;
    }

    /**
     * @param int $episode
     * @return Item
     */
    public function episode(int $episode): Item
    {
        $this->episode = $episode;
        return $this;
    }

    /**
     * @return int
     */
    public function getSeason(): int
    {
        return $this->season;
    }

    /**
     * @param int $season
     * @return Item
     */
    public function season(int $season): Item
    {
        $this->season = $season;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubtitle(): string
    {
        return $this->subtitle;
    }

    /**
     * @param string $subtitle
     * @return Item
     */
    public function subtitle(string $subtitle): Item
    {
        $this->subtitle = $subtitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Item
     */
    public function type(string $type): Item
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return \Suin\RSSWriter\SimpleXMLElement
     */
    public function asXML()
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><item></item>', LIBXML_NOERROR | LIBXML_ERR_NONE | LIBXML_ERR_FATAL);

        if ($this->preferCdata) {
            $xml->addCdataChild('title', $this->title);
        } else {
            $xml->addChild('title', $this->title);
        }

        $xml->addChild('itunes:episodeType', $this->getType(), $this->namespace);
        $xml->addChild('itunes:title', $this->title, $this->namespace);
        $xml->addChild('itunes:episode', $this->getEpisode(), $this->namespace);
        $xml->addChild('itunes:season', $this->getSeason(), $this->namespace);
        $xml->addChild('itunes:author', $this->author, $this->namespace);
        $xml->addChild('itunes:subtitle', $this->getSubtitle(), $this->namespace);
        $xml->addChild('itunes:duration', $this->getDuration(), $this->namespace);
        $xml->addChild('itunes:explicit', $this->isExplicit() ? 'yes' : 'no', $this->namespace);

        if ($this->preferCdata) {
            $xml->addCdataChild('description', $this->description);
        } else {
            $xml->addChild('description', $this->description);
        }

        if ($this->contentEncoded) {
            $xml->addCdataChild('xmlns:content:encoded', $this->contentEncoded);
        }

        foreach ($this->categories as $category) {
            $element = $xml->addChild('category', $category[0]);

            if (isset($category[1])) {
                $element->addAttribute('domain', $category[1]);
            }
        }

        if ($this->guid) {
            $guid = $xml->addChild('guid', $this->guid);

            if ($this->isPermalink === false) {
                $guid->addAttribute('isPermaLink', 'false');
            }
        }

        if ($this->pubDate !== null) {
            $xml->addChild('pubDate', date(DATE_RSS, $this->pubDate));
        }

        if (is_array($this->enclosure) && (count($this->enclosure) == 3)) {
            $element = $xml->addChild('enclosure');
            $element->addAttribute('url', $this->enclosure['url']);
            $element->addAttribute('type', $this->enclosure['type']);

            if ($this->enclosure['length']) {
                $element->addAttribute('length', $this->enclosure['length']);
            }
        }

        if (!empty($this->author)) {
            $xml->addChild('author', $this->author);
        }

        if (!empty($this->creator)) {
            $xml->addChild('dc:creator', $this->creator,"http://purl.org/dc/elements/1.1/");
        }

        return $xml;
    }

}
