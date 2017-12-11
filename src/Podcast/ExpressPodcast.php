<?php

namespace Blockstars\Podcast;

use Concrete\Core\Entity\Express\Entry;
use Concrete\Core\Entity\Express\EntryRepository;

class ExpressPodcast implements PodcastInterface
{

    protected $podcast;
    protected $episodes;
    protected $publishDate;

    public function __construct(Entry $podcast)
    {
        if ($podcast->getEntity()->getHandle() !== 'podcast'){
            throw new \RuntimeException('The passed entry must be a Podcast entry.');
        }

        $this->podcast = $podcast;
    }

    public function getTitle(): string
    {
        return $this->podcast->getLabel();
    }

    public function getDescription(): string
    {
        return $this->podcast->getAttributeValue('description')->value;
    }

    public function getPublishDate(): \DateTime
    {
        if (!$this->publishDate) {

            $latestDate = new Date();
            $latestDate->setTimestamp(0);

            foreach ($this->getEpisodes() as $episode) {
                $episodeDate = $episode->getDateCreated();
                if ($episodeDate > $latestDate) {
                    $latestDate = $episodeDate;
                }
            }

            $this->publishDate = $latestDate;
        }

        return $this->publishDate;
    }

    public function getLink(): string
    {
        return 'NO LINK YET';
    }

    public function getLanguage(): string
    {
        return $this->podcast->getLanguage();
    }

    public function getCopyright(): string
    {
        return 'Copyright ' . date('y') . ' ' . $this->podcast->getAuthor();
    }

    /**
     * @return iterable|\Concrete\Core\Entity\Express\Entry[]
     */
    public function getEpisodes(): iterable
    {
        if (!$this->episodes) {
            $this->episodes = $this->podcast->getEpisodes();
        }
        return $this->episodes;
    }
}
