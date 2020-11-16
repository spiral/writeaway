<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Directive;

use Spiral\Stempler\Directive\AbstractDirective;
use Spiral\Stempler\Exception\DirectiveException;
use Spiral\Stempler\Node\Dynamic\Directive;

class EditorDirective extends AbstractDirective
{
    public function renderEditor(Directive $directive): string
    {
        if (count($directive->values) < 2) {
            throw new DirectiveException(
                'Unable to call @editor directive, `type` and `name` values are required',
                $directive->getContext()
            );
        }
        return sprintf(
            '<?php if($this->container->get(\Spiral\Writeaway\Editor::class)->allows(%s)): ?>',
            $directive->body
        );
    }
}
