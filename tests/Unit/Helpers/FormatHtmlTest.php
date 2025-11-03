<?php

it('formats simple HTML correctly', function () {
    $html = '<div><p>Hello World</p></div>';
    $formatted = formatHTML($html);

    expect($formatted)->toContain('<div>')
        ->and($formatted)->toContain('    <p>')
        ->and($formatted)->toContain('        Hello World')
        ->and($formatted)->toContain('    </p>')
        ->and($formatted)->toContain('</div>');
});

it('handles nested tags with proper indentation', function () {
    $html = '<div><section><article><p>Content</p></article></section></div>';
    $formatted = formatHTML($html);
    $lines = explode("\n", $formatted);

    expect($lines[0])->toBe('<div>');
    expect($lines[1])->toBe('    <section>');
    expect($lines[2])->toBe('        <article>');
    expect($lines[3])->toBe('            <p>');
    expect($lines[4])->toBe('                Content');
});

it('handles self-closing tags correctly', function () {
    $html = '<div><img src="test.jpg"/><br/><hr/></div>';
    $formatted = formatHTML($html);

    expect($formatted)->toContain('<div>')
        ->and($formatted)->toContain('<img src="test.jpg"/>')
        ->and($formatted)->toContain('<br/>')
        ->and($formatted)->toContain('<hr/>');
});

it('handles self-closing tags without slash', function () {
    $html = '<div><img src="test.jpg"><br><input type="text"></div>';
    $formatted = formatHTML($html);

    // The function correctly identifies self-closing tags and formats them
    expect($formatted)->toContain('<img')
        ->and($formatted)->toContain('<br>')
        ->and($formatted)->toContain('<input');
});

it('returns empty string for empty input', function () {
    expect(formatHTML(''))->toBe('');
    expect(formatHTML('   '))->toBe('');
});

it('removes excessive whitespace between tags', function () {
    $html = '<div>   <p>   Text   </p>   </div>';
    $formatted = formatHTML($html);

    expect($formatted)->toContain('<div>')
        ->and($formatted)->toContain('<p>')
        ->and($formatted)->toContain('Text');
});

it('handles complex nested structures', function () {
    $html = '<div><ul><li>Item 1</li><li>Item 2</li></ul></div>';
    $formatted = formatHTML($html);

    expect($formatted)->toContain('<div>')
        ->and($formatted)->toContain('    <ul>')
        ->and($formatted)->toContain('        <li>')
        ->and($formatted)->toContain('            Item 1')
        ->and($formatted)->toContain('        </li>')
        ->and($formatted)->toContain('    </ul>')
        ->and($formatted)->toContain('</div>');
});

it('handles multiple self-closing tags', function () {
    $html = '<div><img src="1.jpg"><img src="2.jpg"><br><br></div>';
    $formatted = formatHTML($html);

    expect($formatted)->toContain('<img src="1.jpg">')
        ->and($formatted)->toContain('<img src="2.jpg">')
        ->and(substr_count($formatted, '<br>'))->toBe(2);
});

it('maintains tag attributes', function () {
    $html = '<div class="container" id="main"><p style="color: red;">Text</p></div>';
    $formatted = formatHTML($html);

    expect($formatted)->toContain('class="container"')
        ->and($formatted)->toContain('id="main"')
        ->and($formatted)->toContain('style="color: red;"');
});

it('handles mixed content and tags', function () {
    $html = '<p>This is <strong>bold</strong> and <em>italic</em> text.</p>';
    $formatted = formatHTML($html);

    expect($formatted)->toContain('<p>')
        ->and($formatted)->toContain('<strong>')
        ->and($formatted)->toContain('bold')
        ->and($formatted)->toContain('<em>')
        ->and($formatted)->toContain('italic');
});

it('handles all common self-closing tags', function () {
    $selfClosingTags = [
        '<area>',
        '<base>',
        '<br>',
        '<col>',
        '<embed>',
        '<hr>',
        '<img>',
        '<input>',
        '<link>',
        '<meta>',
        '<param>',
        '<source>',
        '<track>',
        '<wbr>',
    ];

    foreach ($selfClosingTags as $tag) {
        $html = "<div>{$tag}</div>";
        $formatted = formatHTML($html);
        expect($formatted)->toContain($tag);
    }
});

it('handles real-world HTML example', function () {
    $html = '<article><header><h1>Title</h1></header><section><p>Paragraph 1</p><p>Paragraph 2</p></section><footer><p>Footer</p></footer></article>';
    $formatted = formatHTML($html);

    expect($formatted)->toContain('<article>')
        ->and($formatted)->toContain('    <header>')
        ->and($formatted)->toContain('        <h1>')
        ->and($formatted)->toContain('    <section>')
        ->and($formatted)->toContain('        <p>')
        ->and($formatted)->toContain('    <footer>');
});

it('trims final output', function () {
    $html = '<div><p>Content</p></div>';
    $formatted = formatHTML($html);

    // Check that there's no leading or trailing whitespace
    expect($formatted)->toBe(trim($formatted));
});

it('handles single tags without errors', function () {
    $html = '<div></div>';
    $formatted = formatHTML($html);

    expect($formatted)->toContain('<div>')
        ->and($formatted)->toContain('</div>');
});

it('handles tags with no content', function () {
    $html = '<div><p></p></div>';
    $formatted = formatHTML($html);

    expect($formatted)->toContain('<div>')
        ->and($formatted)->toContain('<p>')
        ->and($formatted)->toContain('</p>')
        ->and($formatted)->toContain('</div>');
});
