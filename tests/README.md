# Tests

This directory contains the test suite for the Laravel Filament Rich Editor Source AI package using Pest PHP.

## Test Structure

### Architecture Tests (`tests/Arch/`)
- **ArchTest.php**: Ensures code quality standards:
  - No debugging functions (dd, dump, ray, etc.)
  - Services follow naming conventions
  - Facades extend the correct base class
  - No direct `env()` usage in production code
  - No dangerous globals (die, exit, eval)
  - Strict types declaration enforcement

### Feature Tests (`tests/Feature/`)
- **RichEditorSourceAIPluginTest.php**: Tests for the main plugin class:
  - Plugin ID and instantiation
  - TipTap extensions registration
  - Editor tools availability
  - Editor actions functionality
  - Configuration validation

### Unit Tests (`tests/Unit/`)

#### Helpers (`tests/Unit/Helpers/`)
- **FormatHtmlTest.php**: Tests for the `formatHTML()` helper function:
  - HTML formatting and indentation
  - Self-closing tags handling
  - Nested structures
  - Tag attributes preservation
  - Edge cases (empty input, whitespace handling)

#### Services (`tests/Unit/Services/`)
- **HtmlTransformServiceTest.php**: Tests for the HTML transformation service:
  - Service instantiation
  - Method signatures
  - Return types validation

## Running Tests

```bash
# Run all tests
composer test

# Run tests with coverage
composer test-coverage

# Run specific test file
vendor/bin/pest tests/Unit/Helpers/FormatHtmlTest.php

# Run tests in a specific directory
vendor/bin/pest tests/Feature/
```

## Test Configuration

- **Pest.php**: Main Pest configuration file that applies the TestCase to all tests
- **TestCase.php**: Base test case class that:
  - Extends Orchestra Testbench for Laravel package testing
  - Sets up the service provider
  - Configures test environment
  - Loads helper functions

## Writing New Tests

When adding new tests:

1. Place them in the appropriate directory (Arch/Feature/Unit)
2. Use Pest's `it()` syntax for test descriptions
3. Follow the existing naming conventions
4. Ensure tests are isolated and don't depend on each other
5. Mock external dependencies (like AI services)

Example:
```php
it('does something useful', function () {
    $result = someFunction();
    
    expect($result)->toBeTrue();
});
```

## Test Coverage

The test suite covers:
- ✅ Plugin registration and configuration
- ✅ HTML helper functions
- ✅ Service layer architecture
- ✅ Code quality standards
- ⏭️ Full Filament integration (skipped - requires panel setup)

## Notes

- One test is skipped (`it can be retrieved using get`) as it requires a full Filament panel setup
- The `HtmlTransformService` tests focus on structure validation rather than mocking the Prism AI service
- All source files use strict type declarations as enforced by architecture tests
