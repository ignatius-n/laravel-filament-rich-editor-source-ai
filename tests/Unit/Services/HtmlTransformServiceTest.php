<?php

use NaturalGroove\Filament\RichEditorSourceAI\Services\HtmlTransformService;
use Prism\Prism\Enums\Provider;

beforeEach(function () {
    $this->service = new HtmlTransformService();
});

it('is instantiable', function () {
    expect($this->service)->toBeInstanceOf(HtmlTransformService::class);
});

it('has a transformHtml method', function () {
    expect(method_exists($this->service, 'transformHtml'))->toBeTrue();
});

it('transformHtml method accepts correct parameters', function () {
    $method = new ReflectionMethod(HtmlTransformService::class, 'transformHtml');
    $parameters = $method->getParameters();

    expect($parameters)->toHaveCount(4)
        ->and($parameters[0]->getName())->toBe('htmlContent')
        ->and($parameters[1]->getName())->toBe('prompt')
        ->and($parameters[2]->getName())->toBe('provider')
        ->and($parameters[3]->getName())->toBe('model');
});

it('transformHtml returns a string', function () {
    $method = new ReflectionMethod(HtmlTransformService::class, 'transformHtml');
    $returnType = $method->getReturnType();

    expect($returnType)->not->toBeNull()
        ->and($returnType->getName())->toBe('string');
});
