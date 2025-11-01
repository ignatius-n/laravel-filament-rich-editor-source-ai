# Rich Editor Source

[![Latest Version on Packagist](https://img.shields.io/packagist/v/naturalgroove/laravel-filament-rich-editor-source-ai.svg?style=flat-square)](https://packagist.org/packages/naturalgroove/laravel-filament-rich-editor-source-ai)
[![Total Downloads](https://img.shields.io/packagist/dt/naturalgroove/laravel-filament-rich-editor-source-ai.svg?style=flat-square)](https://packagist.org/packages/naturalgroove/laravel-filament-rich-editor-source-ai/downloads)

A Filament 4.x plugin that adds view content as source HTML functionality to the Rich Editor component.

<img class="filament-hidden" alt="Rich Editor Source plugin cover" src="img/cover.jpg">

## Installation

You can install the package via composer:

```bash
composer require naturalgroove/laravel-filament-rich-editor-source-ai
```

## Usage

Once installed, the source functionality is automatically available in all Rich Editor components. The plugin registers itself globally, so no additional configuration is needed.

### Basic Usage

The plugin automatically adds the source button to your Rich Editor toolbar:

```php
use Filament\Forms\Components\RichEditor;

RichEditor::make('content')
    ->toolbarButtons([
        'source-ai',
    ])
```

### Keyboard Shortcuts

- `Cmd + Shift + S` (Mac) or `Ctrl + Shift + S` (Windows/Linux): Toggle source mode
- `Esc`: Exit source mode

## Screenshots



## Customization

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
