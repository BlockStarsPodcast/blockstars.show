<?php

namespace Blockstars\Fractal\Transformer;

use Blockstars\Podcast\PodcastInterface;
use Concrete\Core\Entity\Express\Entry;
use League\Fractal\TransformerAbstract;

class PodcastTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [
        'items'
    ];

    public function transform(PodcastInterface $podcast)
    {
        return [
            'title' => $podcast->getTitle(),
            'description' => $podcast->getDescription(),
            'link' => $podcast->getLink(),
            'language' => $podcast->getLanguage(),
            'copyright' => $podcast->getCopyright(),
            'pubDate' => $podcast->getPublishDate()
        ];
    }

    public function transformEpisode(Entry $entry)
    {
        return [
            'title' => $entry->getTitle(),
            'description' => $entry->getDescription(),
            'enclosure' => $this->getEnclosure($entry),
            'pubDate' => $entry->getDateCreated(),
            'duration' => $entry->getDuration(),
            'episode' => $entry->getEpisodeNumber() ?: 1,
            'season' => $entry->getSeasonNumber() ?: 1,
            'subtitle' => $entry->getSubtitle(),
            'explicit' => $entry->getExplicit(),
            'type' => $entry->getEpisodeType()
        ];
    }

    public function includeItems(PodcastInterface $podcast)
    {
        $episodes = $podcast->getEpisodes();
        return $this->collection($episodes, [$this, 'transformEpisode'], 'episodes');
    }

    private function getEnclosure($entry)
    {
        /** @var \Concrete\Core\Entity\File\File $file */
        if (!$file = $entry->getEpisodeAudio()) {
            return [
                'length' => 0,
                'type' => 'text/plain',
                'url' => ''
            ];
        }

        $version = $file->getVersion();
        return [
            'length' => $version->getFullSize(),
            'type' => $version->getFileResource()->getMimetype(),
            'url' => $version->getDownloadURL()
        ];
    }

}
