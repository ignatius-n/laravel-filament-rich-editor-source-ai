<?php

declare(strict_types=1);

namespace NaturalGroove\Filament\RichEditorSourceAI\Services;

use Prism\Prism\Enums\Provider;
use Prism\Prism\Facades\Prism;

class HtmlTransformService
{
    public function transformHtml(string $htmlContent, string $prompt, Provider $provider, string $model): string
    {
        $response = Prism::text()
            ->using($provider, $model)
            ->withSystemPrompt(config('filament-rich-editor-source-ai.system-prompt'))
            ->withPrompt("Transform the following HTML content according to this prompt: {$prompt}\n\nHTML Content:\n{$htmlContent}")
            ->asText();

        return $response->text;
    }
}
