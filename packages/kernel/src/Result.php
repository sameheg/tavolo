<?php
namespace CafeSaaS\Kernel;

final class Result
{
    private function __construct(private bool $ok, private mixed $value = null, private ?string $error = null) {}
    public static function ok(mixed $value = null): self { return new self(true, $value, null); }
    public static function err(string $error): self { return new self(false, null, $error); }
    public function isOk(): bool { return $this->ok; }
    public function unwrap(): mixed { if (!$this->ok) throw new \RuntimeException($this->error ?? 'error'); return $this->value; }
}
