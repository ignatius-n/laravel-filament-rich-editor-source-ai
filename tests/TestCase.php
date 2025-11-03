<?php

namespace NaturalGroove\Filament\RichEditorSourceAI\Tests;

use NaturalGroove\Filament\RichEditorSourceAI\FilamentRichEditorSourceAIServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // Load helpers file
        require_once __DIR__ . '/../src/helpers.php';
    }

    protected function getPackageProviders($app): array
    {
        return [
            FilamentRichEditorSourceAIServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');

        // Set test configuration for the package
        config()->set('filament-rich-editor-source-ai', [
            'default' => [
                'provider' => \Prism\Prism\Enums\Provider::OpenAI,
                'model' => 'gpt-4',
            ],
            'system-prompt' => 'Test system prompt',
            'prompts' => [
                'test-prompt' => [
                    'label' => 'Test Prompt',
                    'description' => 'Test Description',
                    'prompt' => 'Test prompt content',
                ],
            ],
        ]);
    }
}
