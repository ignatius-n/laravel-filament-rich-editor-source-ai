import { Extension } from '@tiptap/core'

export default Extension.create({
    name: 'source-ai',

    onCreate() {
        if (this.storage.classObserver) return;

        const editorWrapper = this.editor.options.element.closest('.fi-fo-rich-editor');
        if (!editorWrapper) return;

        const observer = new MutationObserver(() => {
            if (this.storage.isSource && !editorWrapper.classList.contains('source-ai')) {
                editorWrapper.classList.add('source-ai');
            }
            editorWrapper.dispatchEvent(new CustomEvent('fi-fo-rich-editor:classchange', {
                detail: {
                    classList: editorWrapper.className,
                    isSource: editorWrapper.classList.contains('source-ai'),
                }
            }));
        });

        observer.observe(editorWrapper, { attributes: true, attributeFilter: ['class'] });
        this.storage.classObserver = observer;
    },

    addCommands() {
        return {
            toggleSource: () => ({ editor }) => {
                const showSource = () => {
                    editor.value.commands.setContent(`<textarea>${editor.value.getHTML()}</textarea>`)
                }

                const showHTML = () => {
                    editor.value.commands.setContent(editor.value.getText())
                }

                const editorWrapper = editor.options.element.closest('.fi-fo-rich-editor');

                if (editorWrapper) {
                    editorWrapper.classList.toggle('source-ai');

                    // Update storage state
                    this.storage.isSource = editorWrapper.classList.contains('source-ai');

                    if (this.storage.isSource) {
                        showSource();
                    } else {
                        showHTML();
                    }

                    // Focus the editor after toggling
                    editor.view.focus();
                }
                return true;
            },
        };
    },

    addStorage() {
        return {
            isSource: false,
        };
    },

    addKeyboardShortcuts() {
        return {
            'Escape': () => {
                if (this.storage.isSource) {
                    return this.editor.commands.toggleSource();
                }
            },
            'Mod-Shift-j': () => this.editor.commands.toggleSource(),
        };
    },
})
