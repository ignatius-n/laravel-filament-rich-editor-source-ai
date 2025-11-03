<?php

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'ray', 'var_dump', 'print_r'])
    ->not->toBeUsed();

arch('services')
    ->expect('NaturalGroove\Filament\RichEditorSourceAI\Services')
    ->toBeClasses()
    ->toHaveSuffix('Service');

arch('facades')
    ->expect('NaturalGroove\Filament\RichEditorSourceAI\Facades')
    ->toBeClasses()
    ->toExtend('Illuminate\Support\Facades\Facade');

arch('it does not use env helper in code')
    ->expect('NaturalGroove\Filament\RichEditorSourceAI')
    ->not->toUse('env')
    ->ignoring('NaturalGroove\Filament\RichEditorSourceAI\Tests');

arch('globals')
    ->expect('NaturalGroove\Filament\RichEditorSourceAI')
    ->not->toUse(['die', 'exit', 'eval'])
    ->ignoring('NaturalGroove\Filament\RichEditorSourceAI\Tests');

arch('strict types')
    ->expect('NaturalGroove\Filament\RichEditorSourceAI')
    ->toUseStrictTypes()
    ->ignoring([
        'NaturalGroove\Filament\RichEditorSourceAI\Tests',
        'NaturalGroove\Filament\RichEditorSourceAI\Facades',
    ]);
