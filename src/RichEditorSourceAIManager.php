<?php

namespace NaturalGroove\Filament\RichEditorSourceAI;

class RichEditorSourceAIPluginManager
{
    public function getPlugin(): RichEditorSourceAIPlugin
    {
        return RichEditorSourceAIPlugin::get();
    }
}
