<?php

namespace App\Listeners;

use Nuwave\Lighthouse\Events\ManipulateAST;
use GraphQL\Language\AST\DocumentNode;

class AddCustomErrorHandling
{
    /**
     * Handle the event.
     *
     * @param  \Nuwave\Lighthouse\Events\ManipulateAST  $event
     * @return void
     */
    public function handle(ManipulateAST $event)
    {
        $documentAST = $event->documentAST;

        // Add custom error handling logic to the AST
        // This can include custom directives, etc.
    }
}
