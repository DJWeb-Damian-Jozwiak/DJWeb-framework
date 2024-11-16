<?php

declare(strict_types=1);

namespace DJWeb\Framework\View\Directives;

class SlotDirective extends Directive
{
    public string $name {
        get => 'slot';
}

    public function compile(string $content): string
    {
        return $this->compilePattern(
            '/\@slot\([\'"](.*?)[\'"]\)(.*?)\@endslot/s',
            $content,
            fn ($matches) => "<?php \$this->slot('{$matches[1]}', function() { ?>{$matches[2]}<?php }); ?>"
        );
    }

}