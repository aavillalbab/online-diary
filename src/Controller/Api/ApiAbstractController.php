<?php

namespace App\Controller\Api;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class ApiAbstractController extends AbstractController implements ServiceSubscriberInterface
{
    protected function getSerializer(): SerializerInterface
    {
        return $this->container->get('jms_serializer');
    }

    public function serializedResponse($data, $groups = [], $statusCode = 200): Response
    {
        $request = $this->get('request_stack')->getCurrentRequest();

        if (is_array($request->get('expand'))) {
            $groups = array_merge($groups, $request->get('expand'));
        } elseif (is_array($request->get('expand[]'))) {
            $groups = array_merge($groups, $request->get('expand[]'));
        }

        $context = SerializationContext::create();

        if (is_array($groups) && count($groups)) {
            $context->setGroups($groups);
        }

        $response = new Response($this->getSerializer()->serialize(
            $data,
            'json',
            $context
        ), $statusCode
        );

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Symfony llamará automáticamente a esta función para saber
     * qué servicios puede inyectar en este controlador.
     */
    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            'jms_serializer' => SerializerInterface::class
        ]);
    }
}
