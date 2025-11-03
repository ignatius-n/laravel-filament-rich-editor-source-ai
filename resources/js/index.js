import { Extension } from '@tiptap/core'
import { html as beautifyHtml } from 'js-beautify'

// WeakMap to store editor-specific state
const editorStates = new WeakMap()

// Format HTML using js-beautify
window.formatHtml = function (html) {
    try {
        const formatted = beautifyHtml(html, {
            indent_size: 2,
            indent_char: ' ',
            max_preserve_newlines: 1,
            preserve_newlines: true,
            wrap_line_length: 0,
            wrap_attributes: 'auto',
            unformatted: ['code', 'pre', 'em', 'strong', 'span'],
            content_unformatted: ['pre', 'textarea'],
            indent_inner_html: true,
            indent_body_inner_html: true,
            indent_head_inner_html: true,
            end_with_newline: false,
            extra_liners: [],
        })
        return formatted
    } catch (error) {
        console.warn('Failed to format HTML:', error)
        return html
    }
}

export default Extension.create({
    name: 'source-ai',

    addOptions() {
        return {
            isSourceMode: false,
        }
    },

    onCreate() {
        // Initialize state for this specific editor instance
        const state = {
            isSourceMode: false,
            sourceContent: null,
            textareaElement: null,
        };

        editorStates.set(this.editor, state);

        // Store reference to the editor element
        this.editorElement = this.editor.options.element;

        // Store the original getHTML implementation
        const originalGetHTML = this.editor.getHTML.bind(this.editor);

        // Override getHTML to return proper content when in source mode
        this.editor.getHTML = () => {
            const currentState = editorStates.get(this.editor);
            if (currentState && currentState.isSourceMode) {
                // Get the latest value from the textarea if it exists
                if (currentState.textareaElement) {
                    currentState.sourceContent = currentState.textareaElement.value;
                }
                return currentState.sourceContent || '';
            }
            return originalGetHTML();
        };

        // Store the original method for later use
        state.originalGetHTML = originalGetHTML;

        // Listen for Livewire event to exit source mode
        window.addEventListener('exit-source-mode', (event) => {
            // Check if this event is for this editor
            const statePath = event.detail?.statePath;

            if (statePath) {
                const editorWrapper = this.editor.options.element.closest('.fi-fo-rich-editor');
                const wireId = editorWrapper?.closest('[wire\\:id]')?.getAttribute('wire:id');

                // Exit source mode if currently in it
                const currentState = editorStates.get(this.editor);

                if (currentState && currentState.isSourceMode) {
                    this.editor.commands.toggleSource();

                    const hasSourceClass = editorWrapper.classList.contains('source-ai');

                    // If state says we're in source mode but class is missing, re-add it
                    if (currentState.isSourceMode && !hasSourceClass) {
                        editorWrapper.classList.add('source-ai');
                    }
                }
            }
        });
    },

    addCommands() {
        return {
            toggleSource: (forceState = null) => ({ editor }) => {
                const editorWrapper = editor.options.element.closest('.fi-fo-rich-editor');
                if (!editorWrapper) return false;

                const state = editorStates.get(editor);
                if (!state) return false;

                if (state.isSourceMode === forceState) {
                    // Already in desired state
                    return true;
                }

                // Toggle the state
                state.isSourceMode = forceState !== null ? forceState : !state.isSourceMode;

                if (state.isSourceMode) {
                    // Switching TO source mode
                    // Use the original getHTML to get actual content from editor
                    const htmlContent = state.originalGetHTML();

                    // Format the HTML with js-beautify
                    const formattedHtml = formatHtml(htmlContent);
                    state.sourceContent = formattedHtml;

                    // Create a textarea element to display the HTML
                    const textarea = document.createElement('textarea');
                    textarea.value = formattedHtml;
                    textarea.className = 'source-view-textarea';
                    textarea.style.cssText = 'width: 100%; height: 100%; min-height: 310px; font-family: monospace; font-size: 13px; padding: 10px; border: none; outline: none; resize: vertical; background: transparent; color: inherit;';

                    // Listen for changes in the textarea
                    textarea.addEventListener('input', (e) => {
                        state.sourceContent = e.target.value;

                        // Silently update the editor content in the background
                        // This ensures when Livewire reads it, it gets the updated HTML
                        const currentContent = state.originalGetHTML();
                        if (currentContent !== state.sourceContent) {
                            // Use setContent but without emitting update to avoid re-renders
                            editor.commands.setContent(state.sourceContent, false);
                        }
                    });

                    // Hide the ProseMirror editor and show textarea
                    const proseMirrorElement = editorWrapper.querySelector('.tiptap.ProseMirror');
                    if (proseMirrorElement) {
                        proseMirrorElement.style.display = 'none';
                        proseMirrorElement.insertAdjacentElement('afterend', textarea);
                        state.textareaElement = textarea;
                    }

                    editorWrapper.classList.add('source-ai');
                } else {
                    // Switching FROM source mode back to normal
                    const htmlContent = state.sourceContent;

                    // Remove textarea and restore ProseMirror
                    if (state.textareaElement) {
                        state.textareaElement.remove();
                        state.textareaElement = null;
                    }

                    const proseMirrorElement = editorWrapper.querySelector('.tiptap.ProseMirror');
                    if (proseMirrorElement) {
                        proseMirrorElement.style.display = '';
                    }

                    editorWrapper.classList.remove('source-ai');

                    // Set the content from the textarea
                    editor.commands.setContent(htmlContent);
                    state.sourceContent = null;
                }

                return true;
            },
        };
    },

    addStorage() {
        return {};
    },

    addKeyboardShortcuts() {
        return {
            'Escape': () => {
                const state = editorStates.get(this.editor);
                if (state && state.isSourceMode) {
                    return this.editor.commands.toggleSource();
                }
            },
            'Mod-Shift-l': () => this.editor.commands.toggleSource(),
        };
    },

    onDestroy() {
        // Clean up the state when editor is destroyed
        editorStates.delete(this.editor);
    },
})