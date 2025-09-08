<?php
namespace CafeSaaS\Contracts; interface EventBus{public function publish(object $e):void;} interface MessageBus{public function dispatch(object $m):mixed;}
