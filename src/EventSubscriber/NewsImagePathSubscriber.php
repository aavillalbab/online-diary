<?php

namespace App\EventSubscriber;

use App\Entity\News;
use dsarhoya\DSYFilesBundle\Services\DSYFilesService;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\Metadata\StaticPropertyMetadata;

class NewsImagePathSubscriber implements EventSubscriberInterface
{
    public function __construct(private DSYFilesService $filesService) { }

    public static function getSubscribedEvents(): array
    {
        return [
            [
                'event' => 'serializer.post_serialize',
                'method' => 'onPostSerialize',
                'class' => News::class,
            ],
        ];
    }

    public function onPostSerialize(ObjectEvent $event): void
    {
        $news = $event->getObject();

        if (!$news instanceof News) {
            return;
        }

        $fileKey = $news->getFileKey();

        if ($fileKey) {
            $imageUrl = $this->filesService->getObjectUrl($fileKey);

            $visitor = $event->getVisitor();
            $visitor->visitProperty(
                new StaticPropertyMetadata(
                    News::class,
                    'imagePath',
                    $imageUrl
                ),
                $imageUrl
            );
        }
    }
}
