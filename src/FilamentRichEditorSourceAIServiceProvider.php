<?php

namespace NaturalGroove\Filament\RichEditorSourceAI;

use Filament\Forms\Components\RichEditor;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use NaturalGroove\Filament\RichEditorSourceAI\Testing\TestsRichEditorSourceAI;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class RichEditorSourceAIServiceProvider extends PackageServiceProvider
{
    public static string $name = 'rich-editor-source-ai';

    public static string $viewNamespace = 'rich-editor-source-ai';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('naturalgroove/laravel-filament-rich-editor-source-ai');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register([
            // Main plugin assets
            Css::make('rich-editor-source-ai-styles', __DIR__ . '/../resources/dist/rich-editor-source-ai.css'),

            // TipTap extension for source-ai functionality - register without package name
            Js::make('rich-content-plugins/source-ai', __DIR__ . '/../resources/dist/rich-content-plugins/source-ai.js')->loadedOnRequest(),
        ]);

        // Publish assets
        $this->publishes([
            __DIR__ . '/../resources/dist' => public_path('js/filament/rich-editor-source-ai'),
        ], 'rich-editor-source-ai-assets');

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Register the plugin globally with RichEditor
        RichEditor::configureUsing(function (RichEditor $richEditor) {
            $richEditor->plugins([
                RichEditorSourceAIPlugin::make(),
            ]);
        });

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/rich-editor-source-ai/{$file->getFilename()}"),
                ], 'rich-editor-source-ai-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsRichEditorSourceAI());
    }

    protected function getAssetPackageName(): ?string
    {
        return 'naturalgroove/laravel-filament-rich-editor-source-ai';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // Main plugin assets
            Css::make('rich-editor-source-ai-styles', __DIR__ . '/../resources/dist/rich-editor-source-ai.css'),

            // TipTap extension for source-ai functionality
            Js::make('rich-content-plugins/source-ai', __DIR__ . '/../resources/dist/rich-content-plugins/source-ai.js')->loadedOnRequest(),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [];
    }
}
