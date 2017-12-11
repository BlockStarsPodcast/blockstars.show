<?php

namespace Blockstars\Podcast;

use Suin\RSSWriter\SimpleXMLElement;

class Channel extends \Suin\RSSWriter\Channel
{

    protected $namespace = 'http://www.itunes.com/dtds/podcast-1.0.dtd';
    protected $summary = '';
    protected $owner = 'Korvin Szanto <korvin@blockstars.show>';
    protected $type = 'episodic';
    protected $author = 'The Blockstars';
    protected $subtitle = '';
    protected $category = ['Technology' => 'Software How-To'];
    protected $explicit = true;
    protected $image = '';

    /**
     * Return XML object
     * @return SimpleXMLElement
     */
    public function asXML()
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><channel></channel>', LIBXML_NOERROR | LIBXML_ERR_NONE | LIBXML_ERR_FATAL);
        $xml->addChild('title', $this->title);
        $xml->addChild('link', $this->url);
        $xml->addChild('description', $this->description);

        $this->addIf($xml, 'itunes:summary', $this->getSummary(), $this->namespace);
        $this->addIf($xml, 'itunes:type', $this->getType(), $this->namespace);
        $this->addIf($xml, 'itunes:author', $this->getAuthor(), $this->namespace);
        $this->addIf($xml, 'itunes:subtitle', $this->getSubtitle(), $this->namespace);
        $this->addIf($xml, 'itunes:image', $this->getImage(), $this->namespace);

        $xml->addChild('itunes:explicit', $this->isExplicit() ? 'yes' : 'no', $this->namespace);

        if ($owner = $this->getOwner()){
            preg_match_all('/^(.+?)(?: <(.+?)>)?$/', $owner, $matches);
            $name = head($matches[1]);
            $email = head($matches[2]);

            $ownerWrapper = $xml->addChild('itunes:owner', null, $this->namespace);
            $ownerWrapper->addChild('itunes:name', $name, $this->namespace);
            $ownerWrapper->addChild('itunes:email', $email, $this->namespace);
        }

        foreach ($this->getCategory() as $key => $category) {
            $categoryElement = $xml->addChild('itunes:category', null, $this->namespace);
            if (!is_numeric($key)) {
                $categoryElement->addAttribute('text', $key);

                $subCategoryElement = $categoryElement->addChild('itunes:category', null, $this->namespace);
                $subCategoryElement->addAttribute('text', $category);
            } else {
                $categoryElement->addAttribute('text', $category);
            }
        }

        if($this->feedUrl !== null) {
            $link = $xml->addChild('atom:link', '', "http://www.w3.org/2005/Atom");
            $link->addAttribute('href',$this->feedUrl);
            $link->addAttribute('type','application/rss+xml');
            $link->addAttribute('rel','self');
        }

        if ($this->language !== null) {
            $xml->addChild('language', $this->language);
        }

        if ($this->copyright !== null) {
            $xml->addChild('copyright', $this->copyright);
        }

        if ($this->pubDate !== null) {
            $xml->addChild('pubDate', date(DATE_RSS, $this->pubDate));
        }

        if ($this->lastBuildDate !== null) {
            $xml->addChild('lastBuildDate', date(DATE_RSS, $this->lastBuildDate));
        }

        if ($this->ttl !== null) {
            $xml->addChild('ttl', $this->ttl);
        }

        if ($this->pubsubhubbub !== null) {
            $feedUrl = $xml->addChild('xmlns:atom:link');
            $feedUrl->addAttribute('rel', 'self');
            $feedUrl->addAttribute('href', $this->pubsubhubbub['feedUrl']);
            $feedUrl->addAttribute('type', 'application/rss+xml');

            $hubUrl = $xml->addChild('xmlns:atom:link');
            $hubUrl->addAttribute('rel', 'hub');
            $hubUrl->addAttribute('href', $this->pubsubhubbub['hubUrl']);
        }

        foreach ($this->items as $item) {
            $toDom = dom_import_simplexml($xml);
            $fromDom = dom_import_simplexml($item->asXML());
            $toDom->appendChild($toDom->ownerDocument->importNode($fromDom, true));
        }

        return $xml;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function author(string $author): Channel
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function category($category): Channel
    {
        $this->category = $category;
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
     */
    public function explicit(bool $explicit): Channel
    {
        $this->explicit = $explicit;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * @param mixed $summary
     */
    public function summary(string $summary): Channel
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     * @return string
     */
    public function getOwner(): string
    {
        return $this->owner;
    }

    /**
     * @param string $owner
     */
    public function owner(string $owner): Channel
    {
        $this->owner = $owner;
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
     */
    public function type(string $type): Channel
    {
        $this->type = $type;
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
     */
    public function subtitle(string $subtitle): Channel
    {
        $this->subtitle = $subtitle;
        return $this;
    }

    private function addIf($xml, $string, $conditional, $namespace = null)
    {
        if ($conditional) {
            $xml->addChild($string, $conditional, $namespace);
        }
    }

    /**
     * @param string $image
     * @return Channel
     */
    public function setImage(string $image): Channel
    {
        $this->image = $image;
        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

}
