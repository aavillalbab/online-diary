<?php

namespace App\EventSubscriber;

use App\Entity\News;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\Metadata\StaticPropertyMetadata;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class NewsImagePathSubscriber implements EventSubscriberInterface
{
    public function __construct(private ParameterBagInterface $params) { }

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

        $imagePath = $news->getImagePath();

        if ($imagePath) {
            $s3BaseUrl = $this->params->get('s3_base_url');

            $imageUrl = rtrim($s3BaseUrl, '/') . '/' . ltrim($imagePath, '/');

            $visitor = $event->getVisitor();
            $visitor->visitProperty(
                new StaticPropertyMetadata('', 'imageUrl', $imageUrl),
                $imageUrl
            );
        }
    }
}
