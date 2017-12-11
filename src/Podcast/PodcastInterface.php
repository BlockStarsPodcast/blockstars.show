<?php

namespace Blockstars\Podcast;

use Concrete\Core\Entity\Express\EntryRepository;
use Concrete\Core\File\File;

interface PodcastInterface
{

    public function getTitle(): string;

    public function getDescription(): string;

    public function getPublishDate(): \DateTime;

    public function getLink(): string;

    public function getImage(): File;

    public function getLanguage(): string;

    public function getCopyright(): string;

    /**
     * @return iterable|\Concrete\Core\Entity\Express\Entry[]
     */
    public function getEpisodes(): iterable;

}
