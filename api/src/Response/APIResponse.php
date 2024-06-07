<?php

namespace App\Response;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class APIResponse extends Response
{
    /**
     * @param array<string, int|string> $headers
     */
    public function __construct(mixed $data, int $status = 200, array $headers = [])
    {
        parent::__construct($this->serialize($data), $status, $headers);

        $this->headers->set('Content-Type', 'application/json');
    }

    protected function serialize(mixed $data): mixed
    {
        return $this->getSerializer()->serialize($data, 'json', ['groups' => 'public:read']);
    }

    protected function getSerializer(): Serializer
    {
        $classMetadataFactory = new ClassMetadataFactory(new AttributeLoader());
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer($classMetadataFactory)];

        return new Serializer($normalizers, $encoders);
    }
}
