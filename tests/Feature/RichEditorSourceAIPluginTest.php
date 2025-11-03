<?php

use NaturalGroove\Filament\RichEditorSourceAI\RichEditorSourceAIPlugin;
use Prism\Prism\Enums\Provider;

it('has a unique ID', function () {
    $plugin = RichEditorSourceAIPlugin::make();

    expect($plugin->getId())->toBe('source-ai');
});

it('can be instantiated using make', function () {
    $plugin = RichEditorSourceAIPlugin::make();

    expect($plugin)->toBeInstanceOf(RichEditorSourceAIPlugin::class);
});

it('can be retrieved using get', function () {
    $plugin = RichEditorSourceAIPlugin::get();

    expect($plugin)->toBeInstanceOf(RichEditorSourceAIPlugin::class);
})->skip('Requires full Filament panel setup');

it('provides TipTap JS extensions', function () {
    $plugin = RichEditorSourceAIPlugin::make();
    $extensions = $plugin->getTipTapJsExtensions();

    expect($extensions)->toBeArray()
        ->and($extensions)->not->toBeEmpty()
        ->and($extensions[0])->toContain('source-ai');
});

it('provides editor tools', function () {
    $plugin = RichEditorSourceAIPlugin::make();
    $tools = $plugin->getEditorTools();

    expect($tools)->toBeArray()
        ->and($tools)->not->toBeEmpty()
        ->and(count($tools))->toBeGreaterThanOrEqual(2);
});

it('has source editor tool', function () {
    $plugin = RichEditorSourceAIPlugin::make();
    $tools = $plugin->getEditorTools();

    $sourceTools = array_filter($tools, fn($tool) => str_contains($tool->getName(), 'source-ai'));

    expect($sourceTools)->not->toBeEmpty();
});

it('has transform HTML tool', function () {
    $plugin = RichEditorSourceAIPlugin::make();
    $tools = $plugin->getEditorTools();

    $transformTools = array_filter($tools, fn($tool) => str_contains($tool->getName(), 'transform'));

    expect($transformTools)->not->toBeEmpty();
});

it('provides editor actions', function () {
    $plugin = RichEditorSourceAIPlugin::make();
    $actions = $plugin->getEditorActions();

    expect($actions)->toBeArray()
        ->and($actions)->not->toBeEmpty();
});

it('has transformHtmlUsingAI action', function () {
    $plugin = RichEditorSourceAIPlugin::make();
    $actions = $plugin->getEditorActions();

    $transformAction = array_filter($actions, fn($action) => $action->getName() === 'transformHtmlUsingAI');

    expect($transformAction)->not->toBeEmpty();
});

it('returns empty array for TipTap PHP extensions', function () {
    $plugin = RichEditorSourceAIPlugin::make();
    $extensions = $plugin->getTipTapPhpExtensions();

    expect($extensions)->toBeArray()
        ->and($extensions)->toBeEmpty();
});

it('can access configuration', function () {
    $provider = config('filament-rich-editor-source-ai.default.provider');
    $model = config('filament-rich-editor-source-ai.default.model');

    expect($provider)->toBeInstanceOf(Provider::class)
        ->and($model)->toBeString()
        ->and($model)->not->toBeEmpty();
});

it('has configured prompts', function () {
    $prompts = config('filament-rich-editor-source-ai.prompts');

    expect($prompts)->toBeArray()
        ->and($prompts)->not->toBeEmpty()
        ->and($prompts)->toHaveKey('test-prompt');
});

it('has system prompt configured', function () {
    $systemPrompt = config('filament-rich-editor-source-ai.system-prompt');

    expect($systemPrompt)->toBeString()
        ->and($systemPrompt)->not->toBeEmpty();
});

it('validates prompt structure', function () {
    $prompts = config('filament-rich-editor-source-ai.prompts');

    foreach ($prompts as $key => $prompt) {
        expect($prompt)->toHaveKey('label')
            ->and($prompt)->toHaveKey('description')
            ->and($prompt)->toHaveKey('prompt')
            ->and($prompt['label'])->toBeString()
            ->and($prompt['description'])->toBeString()
            ->and($prompt['prompt'])->toBeString();
    }
});
