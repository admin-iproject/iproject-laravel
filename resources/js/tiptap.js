/**
 * tiptap.js — Vite-managed Tiptap setup
 * Exposes window.createTiptapEditor() globally for tickets.js
 */
import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Underline from '@tiptap/extension-underline';
import Image from '@tiptap/extension-image';
import Link from '@tiptap/extension-link';
import Placeholder from '@tiptap/extension-placeholder';

window.createTiptapEditor = function(elementId, options = {}) {
    const el = document.getElementById(elementId);
    if (!el) { console.warn('[Tiptap] Element not found:', elementId); return null; }

    return new Editor({
        element: el,
        extensions: [
            // Explicitly exclude Link and Underline from StarterKit to avoid
            // duplicate extension warning (StarterKit bundles them in newer versions)
            StarterKit.configure({
                history: true,
                link: false,
                underline: false,
            }),
            Underline,
            Image.configure({ inline: false, allowBase64: true }),
            Link.configure({ openOnClick: false, autolink: true }),
            Placeholder.configure({
                placeholder: options.placeholder || 'Type here…',
            }),
        ],
        content: options.content || '',
        onUpdate({ editor }) {
            if (options.onUpdate) options.onUpdate(editor.getHTML());
        },
        editorProps: {
            handlePaste(view, event) {
                const items = event.clipboardData?.items;
                if (!items) return false;
                for (const item of items) {
                    if (item.type.startsWith('image/')) {
                        const file = item.getAsFile();
                        const reader = new FileReader();
                        reader.onload = e => {
                            view.dispatch(
                                view.state.tr.replaceSelectionWith(
                                    view.state.schema.nodes.image.create({ src: e.target.result })
                                )
                            );
                        };
                        reader.readAsDataURL(file);
                        return true;
                    }
                }
                return false;
            },
        },
    });
};
