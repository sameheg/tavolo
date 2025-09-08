<?php
namespace CafeSaaS\Contracts;

interface EventBus { public function publish(object $event): void; }
interface MessageBus { public function dispatch(object $message): mixed; }
