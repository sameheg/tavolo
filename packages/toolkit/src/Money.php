<?php
namespace CafeSaaS\Toolkit;

final class Money
{
    private function __construct(private int $minor, private string $currency) {}
    public static function zero(string $currency = 'EGP'): self { return new self(0, $currency); }
    public static function fromFloat(float $amount, string $currency = 'EGP'): self { return new self((int) round($amount * 100), $currency); }
    public function add(self $other): self { $this->assertSameCurrency($other); return new self($this->minor + $other->minor, $this->currency); }
    public function toFloat(): float { return $this->minor / 100.0; }
    private function assertSameCurrency(self $o): void { if ($this->currency !== $o->currency) throw new \InvalidArgumentException('Currency mismatch'); }
}
