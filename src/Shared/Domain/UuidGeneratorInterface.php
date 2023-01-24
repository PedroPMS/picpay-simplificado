<?php

namespace Picpay\Shared\Domain;

interface UuidGeneratorInterface
{
    public function generate(): string;
}
