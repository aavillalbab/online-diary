<?php

namespace App\Tests\EventSubscriber;

use App\Entity\News;
use App\EventSubscriber\NewsImagePathSubscriber;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Metadata\StaticPropertyMetadata;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class NewsImagePathSubscriberTest extends TestCase
{
    private NewsImagePathSubscriber $subscriber;
    private ParameterBagInterface $params;

    protected function setUp(): void
    {
        $this->params = $this->createMock(ParameterBagInterface::class);
        $this->subscriber = new NewsImagePathSubscriber($this->params);
    }

    public function testGetSubscribedEvents(): void
    {
        $events = NewsImagePathSubscriber::getSubscribedEvents();

        $this->assertCount(1, $events);
        $this->assertEquals('serializer.post_serialize', $events[0]['event']);
        $this->assertEquals('onPostSerialize', $events[0]['method']);
        $this->assertEquals(News::class, $events[0]['class']);
    }

    public function testOnPostSerializeWithImage(): void
    {
        $news = new News();
        $news->setImagePath('news/test-image.jpg');

        $visitor = $this->createMock(JsonSerializationVisitor::class);
        $event = $this->createMock(ObjectEvent::class);

        $this->params->expects($this->once())
            ->method('get')
            ->with('s3_base_url')
            ->willReturn('https://my-bucket.s3.amazonaws.com');

        $event->expects($this->once())
            ->method('getObject')
            ->willReturn($news);

        $event->expects($this->once())
            ->method('getVisitor')
            ->willReturn($visitor);

        $visitor->expects($this->once())
            ->method('visitProperty')
            ->with(
                $this->callback(function ($metadata) {
                    return $metadata instanceof StaticPropertyMetadata &&
                           $metadata->name === 'imageUrl' &&
                           $metadata->serializedName === 'imageUrl';
                }),
                'https://my-bucket.s3.amazonaws.com/news/test-image.jpg'
            );

        $this->subscriber->onPostSerialize($event);
    }

    public function testOnPostSerializeWithoutImage(): void
    {
        $news = new News();
        $news->setImagePath(null);

        $event = $this->createMock(ObjectEvent::class);
        $event->expects($this->once())
            ->method('getObject')
            ->willReturn($news);

        $event->expects($this->never())
            ->method('getVisitor');

        $this->subscriber->onPostSerialize($event);
    }

    public function testOnPostSerializeWithDifferentObject(): void
    {
        $event = $this->createMock(ObjectEvent::class);
        $event->expects($this->once())
            ->method('getObject')
            ->willReturn(new \stdClass());

        $event->expects($this->never())
            ->method('getVisitor');

        $this->subscriber->onPostSerialize($event);
    }

    public function testOnPostSerializeWithTrailingSlashes(): void
    {
        $news = new News();
        $news->setImagePath('/news/test-image.jpg');

        $visitor = $this->createMock(JsonSerializationVisitor::class);
        $event = $this->createMock(ObjectEvent::class);

        $this->params->expects($this->once())
            ->method('get')
            ->with('s3_base_url')
            ->willReturn('https://my-bucket.s3.amazonaws.com/');

        $event->expects($this->once())
            ->method('getObject')
            ->willReturn($news);

        $event->expects($this->once())
            ->method('getVisitor')
            ->willReturn($visitor);

        $visitor->expects($this->once())
            ->method('visitProperty')
            ->with(
                $this->callback(function ($metadata) {
                    return $metadata instanceof StaticPropertyMetadata &&
                           $metadata->name === 'imageUrl' &&
                           $metadata->serializedName === 'imageUrl';
                }),
                'https://my-bucket.s3.amazonaws.com/news/test-image.jpg'
            );

        $this->subscriber->onPostSerialize($event);
    }
} 