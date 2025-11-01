<?php

namespace NaturalGroove\Filament\RichEditorSourceAI;

use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\EditorCommand;
use Filament\Forms\Components\RichEditor\Plugins\Contracts\RichContentPlugin;
use Filament\Forms\Components\RichEditor\RichEditorTool;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Support\Enums\Width;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\HtmlString;
use NaturalGroove\Filament\RichEditorSourceAI\Services\HtmlTransformService;
use Tiptap\Core\Extension;

class RichEditorSourceAIPlugin implements RichContentPlugin
{
    public function getId(): string
    {
        return 'source-ai';
    }

    public static function make(): static
    {
        return app(static::class);
    }

    /**
     * @return array<string>
     */
    public function getTipTapJsExtensions(): array
    {
        return [
            FilamentAsset::getScriptSrc('rich-content-plugins/source-ai'),
        ];
    }

    /**
     * @return array<RichEditorTool>
     */
    public function getEditorTools(): array
    {
        return [
            RichEditorTool::make('source-ai')
                ->icon('heroicon-o-code-bracket')
                ->label(__('filament-rich-editor-source-ai::editor.source'))
                ->extraAttributes(['data-tiptap-menu-item-name' => 'source-ai'])
                ->jsHandler(<<<'JS'
                    $getEditor()?.commands.toggleSource()
                JS),
            RichEditorTool::make('source-ai-transform')
                ->label(__('filament-rich-editor-source-ai::editor.transform-html'))
                ->extraAttributes(['data-tiptap-menu-item-name' => 'source-ai-transform'])
                ->action('transformHtmlUsingAI')
                ->icon(new HtmlString(view('filament-rich-editor-source-ai::rich-editor.toolbar.transform-html')->render())),
        ];
    }

    /**
     * @return array<Extension>
     */
    public function getTipTapPhpExtensions(): array
    {
        return [];
    }

    /**
     * @return array<Action>
     */
    public function getEditorActions(): array
    {
        return [
            Action::make('transformHtmlUsingAI')
                ->modalWidth(Width::SixExtraLarge)
                ->modalHeading(__('filament-rich-editor-source-ai::editor.transform-html-modal.heading'))
                ->extraAttributes(['class' => 'gallery-picker'])
                ->fillForm(function (RichEditor $component): array {
                    $html = html_entity_decode($component->getState() ?? '', ENT_QUOTES | ENT_HTML5);

                    $prompts = config('filament-rich-editor-source-ai.prompts', []);
                    $defaultPrompt = !empty($prompts) ? array_key_first($prompts) : null;

                    return [
                        'htmlContent' => $html,
                        'prompt' => $defaultPrompt,
                    ];
                })
                ->schema([
                    Select::make('prompt')
                        ->label(__('filament-rich-editor-source-ai::editor.transform-html-modal.prompt-label'))
                        ->helperText(__('filament-rich-editor-source-ai::editor.transform-html-modal.prompt-helper-text'))
                        ->options(function () {
                            $prompts = config('filament-rich-editor-source-ai.prompts', []);
                            return array_map(fn($item) => $item['label'], $prompts);
                        })
                        ->required()
                        ->selectablePlaceholder(false)
                        ->default(function () {
                            $prompts = config('filament-rich-editor-source-ai.prompts', []);
                            return !empty($prompts) ? array_key_first($prompts) : null;
                        }),
                    Textarea::make('htmlContent')
                        ->label(__('filament-rich-editor-source-ai::editor.transform-html-modal.html-content-label'))
                        ->helperText(__('filament-rich-editor-source-ai::editor.transform-html-modal.html-content-helper-text'))
                        ->required()
                        ->rows(10)
                        ->live()
                        ->hintActions([
                            Action::make('transformHtml')
                                ->label(__('filament-rich-editor-source-ai::editor.transform-html-modal.transform-button'))
                                ->icon('heroicon-o-sparkles')
                                ->color('warning')
                                ->size('sm')
                                ->button()
                                ->requiresConfirmation(false)
                                ->action(function ($set, $get, $livewire) {
                                    // Validate the form before transforming
                                    $prompt = $get('prompt');

                                    if (empty($prompt)) {
                                        \Filament\Notifications\Notification::make()
                                            ->title(__('filament-rich-editor-source-ai::editor.transform-html-modal.prompt-required'))
                                            ->danger()
                                            ->send();
                                        return;
                                    }

                                    $service = app(HtmlTransformService::class);

                                    // Get the full prompt configuration
                                    $promptConfig = config('filament-rich-editor-source-ai.prompts.' . $prompt);

                                    $transformedHtml = $service->transformHtml(
                                        htmlContent: $get('htmlContent'),
                                        prompt: $promptConfig['prompt'],
                                        provider: config('filament-rich-editor-source-ai.default.provider'),
                                        model: config('filament-rich-editor-source-ai.default.model')
                                    );

                                    // Update the htmlContent field
                                    $set('htmlContent', $transformedHtml);
                                })
                        ]),
                ])
                ->modalSubmitActionLabel(__('filament-rich-editor-source-ai::editor.transform-html-modal.submit-label'))
                ->modalFooterActionsAlignment('right')
                ->action(function (array $arguments, array $data, RichEditor $component, $livewire): void {
                    // Set the RichEditor content with the HTML from the textarea
                    $component->state($data['htmlContent']);

                    // Dispatch event to exit source mode
                    $livewire->dispatch('exit-source-mode', statePath: $component->getStatePath());
                }),
        ];
    }

    public static function get(): static
    {
        if (! ($currentPanel = filament()->getCurrentPanel())) {
            return app(static::class);
        }

        return app(static::class);

        // /** @var static */
        // return $currentPanel->getPlugin(app(static::class)->getId());
    }
}
