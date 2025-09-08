<?php
namespace CafeSaaS\Kernel;
use DateTimeImmutable;

final class Clock
{
    public static function now(): DateTimeImmutable { return new DateTimeImmutable('now'); }
}
