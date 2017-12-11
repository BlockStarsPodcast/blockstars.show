<?php

namespace Blockstars\Podcast;

use Blockstars\Fractal\Transformer\PodcastTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use Suin\RSSWriter\Feed;

class PodcastFeedRenderer
{

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    public function renderPodcast(PodcastInterface $podcast, Feed $feed=null)
    {
        $data = $this->manager->createData(new Item($podcast, new PodcastTransformer()));
        $dataArray = $data->toArray();

        if (!$feed) {
            $feed = new Feed();
        }

        $channel = new Channel();
        $channel
            ->title($dataArray['title'])
            ->description($dataArray['description'])
            ->copyright($dataArray['copyright'])
            ->language($dataArray['language'])
            ->url($dataArray['link'])
            ->pubDate($dataArray['pubDate']->getTimestamp())
            ->appendTo($feed);

        foreach ($dataArray['items']['episodes'] as $episode) {
            $item = new \Blockstars\Podcast\Item();
            $item
                ->enclosure($episode['enclosure']['url'], $episode['enclosure']['length'], $episode['enclosure']['type'])
                ->title($episode['title'])
                ->contentEncoded(h($episode['description']))
                ->description($episode['description'])
                ->pubDate($episode['pubDate']->getTimestamp())
                ->episode($episode['episode'])
                ->season($episode['season'])
                ->subtitle($episode['subtitle'])
                ->type($episode['type'])
                ->explicit($episode['explicit'])
                ->duration($episode['duration'])
                ->appendTo($channel);
        }

        return $feed->render();
    }

}
