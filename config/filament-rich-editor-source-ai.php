<?php

use Prism\Prism\Enums\Provider;

return [
    'default' => [
        'provider' => Provider::OpenAI,
        'model' => 'gpt-5-mini',
    ],

    'system-prompt' => 'You are an expert HTML content transformer. You will be provided with HTML content and a specific prompt. 
        Your task is to transform the HTML content according to the given prompt, ensuring that the output is clean, well-structured, and adheres to best practices for web content. Focus on improving readability, accessibility, and overall user experience while maintaining the original intent of the content.
        Do not wrap in tag like <main> - content are coming from WYSIWYG editor so they are already in the right context.
        Always respond with only the transformed HTML content without any additional explanations or comments.
        Do not modify meaning of the content unless explicitly instructed in the prompt.
        Ensure that the transformed HTML is free from unnecessary inline styles or deprecated tags unless specified otherwise in the prompt.',

    'prompts' => [
        'tailwind-css-typography-optimization' => [
            'label' => 'Tailwind CSS Typography optimization',
            'description' => 'Optimize HTML content for Tailwind CSS typography plugin (prose)',
            'prompt' => 'cleanup this file - Tailwindcss typography plugin (prose) is used so there is no need for any custom styling, 
                also improve ui/ux for this content, and try to improve readability, make this content WCAG ready,
                do not wrap in prose class, do not add any tailwind classes, skip H1 and H2, start headers with H3,
                do not add header in the begining if not present, keep the original meaning of the content',
        ],
    ],
];
