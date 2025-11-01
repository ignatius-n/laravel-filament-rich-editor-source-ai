<?php

namespace NaturalGroove\Filament\RichEditorSourceAI;

use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor\Plugins\Contracts\RichContentPlugin;
use Filament\Forms\Components\RichEditor\RichEditorTool;
use Filament\Support\Facades\FilamentAsset;
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
                ->label(__('rich-editor-source-ai::editor.source'))
                ->jsHandler(<<<'JS'
                    $getEditor()?.commands.toggleSource()
                JS),
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
        return [];
    }

    public static function get(): static
    {
        if (! ($currentPanel = filament()->getCurrentPanel())) {
            return app(static::class);
        }

        /** @var static */
        return $currentPanel->getPlugin(app(static::class)->getId());
    }
}
