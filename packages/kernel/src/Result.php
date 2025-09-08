<?php
namespace CafeSaaS\Kernel;
final class Result{private function __construct(private bool $ok,private mixed $v=null,private ?string $e=null){}public static function ok(mixed $v=null):self{return new self(true,$v,null);}public static function err(string $e):self{return new self(false,null,$e);}public function isOk():bool{return $this->ok;}public function unwrap():mixed{if(!$this->ok)throw new \RuntimeException($this->e??'error');return $this->v;}}
