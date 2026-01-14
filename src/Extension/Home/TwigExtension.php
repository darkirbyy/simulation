<?php

declare(strict_types=1);

namespace App\Extension\Home;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Twig\Attribute\AsTwigFunction;

class TwigExtension
{
    private PropertyAccessorInterface $accessor;

    public function __construct()
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    #[AsTwigFunction('attribute_deep')]
    public function getAttributeDeep(mixed $object, string $path): mixed
    {
        try {
            return $this->accessor->getValue($object, $path);
        } catch (\Exception $e) {
            return null;
        }
    }
}
