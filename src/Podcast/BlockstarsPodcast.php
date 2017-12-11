<?php

namespace Blockstars\Podcast;

use Concrete\Core\Entity\Express\Entity;
use Concrete\Core\File\File;

class BlockstarsPodcast implements PodcastInterface
{

    protected $episodes;
    protected $publishDate;
    protected $entryRepository;

    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }

    public function getTitle(): string
    {
        return 'Blockstars';
    }

    public function getDescription(): string
    {
        return "Blockstars Podcast: Where the concrete5 community goes to hear about the concrete5 community.";
    }

    public function getImage(): File
    {
        return File::getByID(12);
    }

    public function getPublishDate(): \DateTime
    {
        if (!$this->publishDate) {

            $latestDate = new \DateTime();
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
        return \URL::to('/feed.rss');
    }

    public function getLanguage(): string
    {
        return 'en_us';
    }

    public function getCopyright(): string
    {
        return 'Copyright ' . date('Y') . ' ' . $this->getTitle();
    }

    /**
     * @return iterable|\Concrete\Core\Entity\Express\Entry[]
     */
    public function getEpisodes(): iterable
    {
        if (!$this->episodes) {
            $this->episodes = $this->entity->getEntries();
        }
        return $this->episodes;
    }
}
