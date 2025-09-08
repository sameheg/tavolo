<?php
namespace {{namespace}}\Application\Handler;

use {{namespace}}\Application\Command\Create{{Module}};
use {{namespace}}\Domain\Entity\{{Module}}Aggregate;

final class Create{{Module}}Handler
{
    public function __invoke(Create{{Module}} $cmd): string
    {
        $agg = {{Module}}Aggregate::new($cmd->id);
        // persist via repository (implement later)
        return $agg->id();
    }
}
