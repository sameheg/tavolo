<?php
namespace {{namespace}}\Domain\Entity;
final class {{Module}}Aggregate { private string $id; public static function new(string $id): self { $s=new self(); $s->id=$id; return $s; } public function id(): string { return $this->id; } }
