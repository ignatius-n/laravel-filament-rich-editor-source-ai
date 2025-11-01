<?php

namespace NaturalGroove\Filament\RichEditorSourceAI\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \NaturalGroove\Filament\RichEditorSourceAI\RichEditorSourceAIPluginManager
 */
class RichEditorSourceAI extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \NaturalGroove\Filament\RichEditorSourceAI\RichEditorSourceAIPluginManager::class;
    }
}
