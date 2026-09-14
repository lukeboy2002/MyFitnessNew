import {Editor} from 'https://esm.sh/@tiptap/core@2.6.6';
import StarterKit from 'https://esm.sh/@tiptap/starter-kit@2.6.6';
import Highlight from 'https://esm.sh/@tiptap/extension-highlight@2.6.6';
import Underline from 'https://esm.sh/@tiptap/extension-underline@2.6.6';
import Link from 'https://esm.sh/@tiptap/extension-link@2.6.6';
import TextAlign from 'https://esm.sh/@tiptap/extension-text-align@2.6.6';
import Image from 'https://esm.sh/@tiptap/extension-image@2.6.6';
import YouTube from 'https://esm.sh/@tiptap/extension-youtube@2.6.6';
import TextStyle from 'https://esm.sh/@tiptap/extension-text-style@2.6.6';
import FontFamily from 'https://esm.sh/@tiptap/extension-font-family@2.6.6';
import {Color} from 'https://esm.sh/@tiptap/extension-color@2.6.6';
import Bold from 'https://esm.sh/@tiptap/extension-bold@2.6.6'; // Import the Bold extension


function initTipTap(elementId = 'editor') {
    const editorRoot = document.getElementById(elementId);
    if (!editorRoot) return null;

    // Geef de bestaande instantie terug als deze al is geïnitialiseerd
    if (editorRoot.editorInstance) {
        return editorRoot.editorInstance;
    }

    if (editorRoot.dataset.tiptapInitialized === '1') return null;
    editorRoot.dataset.tiptapInitialized = '1';

    const safeOn = (id, event, handler) => {
        const el = document.getElementById(id);
        if (el) el.addEventListener(event, handler);
    };

    const FontSizeTextStyle = TextStyle.extend({
        addAttributes() {
            return {
                ...(this.parent?.() || {}),
                fontSize: {
                    default: null,
                    parseHTML: element => element.style.fontSize,
                    renderHTML: attributes => {
                        if (!attributes.fontSize) {
                            return {};
                        }
                        return {style: `font-size: ${attributes.fontSize}`};
                    },
                },
            };
        },
    });
    const CustomBold = Bold.extend({
        renderHTML({mark, HTMLAttributes}) {
            const {style, ...rest} = HTMLAttributes;
            const newStyle = 'font-weight: bold;' + (style ? ' ' + style : '');
            return ['span', {...rest, style: newStyle.trim()}, 0];
        },
        addOptions() {
            return {
                ...this.parent?.(),
                HTMLAttributes: {},
            };
        },
    });

    const hiddenInput = document.getElementById('body') || document.getElementsByName('description')[0];
    const initial = editorRoot.getAttribute('data-initial') || (hiddenInput ? hiddenInput.value : '');
    const uploadUrl = editorRoot.getAttribute('data-upload-url') || '/editor/uploads/images';
    const deleteUrl = editorRoot.getAttribute('data-delete-url') || '/editor/uploads/images';

    let currentImages = [];
    const getImagesFromHTML = (html) => {
        const div = document.createElement('div');
        div.innerHTML = html;
        return Array.from(div.querySelectorAll('img')).map(img => img.src);
    };

    if (initial) {
        currentImages = getImagesFromHTML(initial);
    }

    const deleteImageFromServer = async (url) => {
        try {
            // We expect the URL to be something like https://.../storage/editor/filename.ext
            // We need to extract the 'editor/filename.ext' part.
            // In Laravel, asset('storage/editor/...') usually gives this structure.
            const urlObj = new URL(url);
            const pathParts = urlObj.pathname.split('/storage/');
            if (pathParts.length < 2) return;

            const path = pathParts[1];
            if (!path.startsWith('editor/')) return;

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            await fetch(deleteUrl, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    ...(token ? {'X-CSRF-TOKEN': token} : {}),
                },
                body: JSON.stringify({path}),
            });
        } catch (err) {
            console.error('Failed to delete image from server', err);
        }
    };

    const editor = new Editor({
        element: editorRoot,
        extensions: [
            StarterKit.configure({
                bold: false,
            }),
            CustomBold,
            Color,
            FontSizeTextStyle,
            FontFamily,
            Highlight,
            Underline,
            Link.configure({
                openOnClick: false,
                autolink: true,
                defaultProtocol: 'https',
            }),
            TextAlign.configure({
                types: ['heading', 'paragraph'],
            }),
            Image,
            YouTube,
        ],
        content: initial || '',
        editorProps: {
            attributes: {
                class: 'format lg:format-lg dark:format-invert focus:outline-none format-blue max-w-none',
            },
        },

    });

    editorRoot.editorInstance = editor;

    if (hiddenInput) {
        if (!hiddenInput.value && initial) {
            hiddenInput.value = initial;
        }
    }

    editor.on('update', () => {
        const newHTML = editor.getHTML();
        if (hiddenInput) {
            hiddenInput.value = newHTML;
            hiddenInput.dispatchEvent(new Event('input', {bubbles: true}));
            hiddenInput.dispatchEvent(new Event('change', {bubbles: true}));
        }

        const newImages = getImagesFromHTML(newHTML);
        currentImages.forEach(oldUrl => {
            if (!newImages.includes(oldUrl)) {
                deleteImageFromServer(oldUrl);
            }
        });
        currentImages = newImages;
    });

    const setBtnActive = (btnId, isActive) => {
        const el = document.getElementById(btnId);
        if (!el) return;
        if (isActive) {
            el.classList.add('text-secondary', 'bg-surface-hover');
            el.classList.remove('text-muted');
        } else {
            el.classList.remove('text-secondary', 'bg-surface-hover');
            el.classList.add('text-muted');
        }
    };

    const updateToolbarState = () => {
        setBtnActive('toggleBoldButton', editor.isActive('bold'));
        setBtnActive('toggleItalicButton', editor.isActive('italic'));
        setBtnActive('toggleUnderlineButton', editor.isActive('underline'));
        setBtnActive('toggleCodeButton', editor.isActive('code'));
        setBtnActive('toggleLinkButton', editor.isActive('link'));
        setBtnActive('toggleListButton', editor.isActive('bulletList'));
        setBtnActive('toggleOrderedListButton', editor.isActive('orderedList'));
        setBtnActive('toggleBlockquoteButton', editor.isActive('blockquote'));
        setBtnActive('toggleLeftAlignButton', editor.isActive({textAlign: 'left'}));
        setBtnActive('toggleCenterAlignButton', editor.isActive({textAlign: 'center'}));
        setBtnActive('toggleRightAlignButton', editor.isActive({textAlign: 'right'}));
    };

    editor.on('selectionUpdate', updateToolbarState);
    editor.on('update', updateToolbarState);
    setTimeout(updateToolbarState, 0);

    safeOn('toggleBoldButton', 'click', () => editor.chain().focus().toggleBold().run());
    safeOn('toggleItalicButton', 'click', () => editor.chain().focus().toggleItalic().run());
    safeOn('toggleUnderlineButton', 'click', () => editor.chain().focus().toggleUnderline().run());
    safeOn('toggleStrikeButton', 'click', () => editor.chain().focus().toggleStrike().run());
    safeOn('toggleHighlightButton', 'click', () => {
        const isHighlighted = editor.isActive('highlight');
        editor.chain().focus().toggleHighlight({
            color: isHighlighted ? undefined : '#ffc078'
        }).run();
    });

    safeOn('toggleLinkButton', 'click', () => {
        const url = window.prompt('Enter link URL:', 'https://');
        if (url) editor.chain().focus().toggleLink({href: url}).run();
    });
    safeOn('removeLinkButton', 'click', () => {
        editor.chain().focus().unsetLink().run();
    });
    safeOn('toggleCodeButton', 'click', () => {
        editor.chain().focus().toggleCode().run();
    });

    safeOn('toggleLeftAlignButton', 'click', () => {
        editor.chain().focus().setTextAlign('left').run();
    });
    safeOn('toggleCenterAlignButton', 'click', () => {
        editor.chain().focus().setTextAlign('center').run();
    });
    safeOn('toggleRightAlignButton', 'click', () => {
        editor.chain().focus().setTextAlign('right').run();
    });
    safeOn('toggleListButton', 'click', () => {
        editor.chain().focus().toggleBulletList().run();
    });
    safeOn('toggleOrderedListButton', 'click', () => {
        editor.chain().focus().toggleOrderedList().run();
    });
    safeOn('toggleBlockquoteButton', 'click', () => {
        editor.chain().focus().toggleBlockquote().run();
    });
    safeOn('toggleHRButton', 'click', () => {
        editor.chain().focus().setHorizontalRule().run();
    });

    const imageInput = document.getElementById('editorImageInput');
    if (imageInput && !imageInput.dataset.listenerAttached) {
        imageInput.addEventListener('change', async (e) => {
            const file = e.target.files && e.target.files[0];
            if (!file) return;
            try {
                const formData = new FormData();
                formData.append('image', file);
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const res = await fetch(uploadUrl, {
                    method: 'POST',
                    headers: token ? {'X-CSRF-TOKEN': token} : {},
                    body: formData,
                });
                if (!res.ok) throw new Error('Upload failed');
                const data = await res.json();
                if (data?.url) {
                    editor.chain().focus().setImage({src: data.url}).run();
                }
            } catch (err) {
                console.error('Image upload error', err);
                alert('Image upload failed. Please try again.');
            } finally {
                e.target.value = '';
            }
        });
        imageInput.dataset.listenerAttached = '1';
    }
    safeOn('addImageButton', 'click', () => {
        if (imageInput) {
            imageInput.click();
        } else {
            const url = window.prompt('Enter image URL:', 'https://placehold.co/600x400');
            if (url) editor.chain().focus().setImage({src: url}).run();
        }
    });
    safeOn('addVideoButton', 'click', () => {
        const url = window.prompt('Enter YouTube URL:', 'https://www.youtube.com/watch?v=');
        if (url) {
            editor.commands.setYoutubeVideo({
                src: url,
                width: 640,
                height: 480,
            })
        }
    });

    const typographyDropdown = window.FlowbiteInstances?.getInstance?.('Dropdown', 'typographyDropdown');
    safeOn('toggleParagraphButton', 'click', () => {
        editor.chain().focus().setParagraph().run();
        typographyDropdown?.hide?.();
    });

    document.querySelectorAll('[data-heading-level]').forEach((button) => {
        button.addEventListener('click', () => {
            const level = button.getAttribute('data-heading-level');
            editor.chain().focus().toggleHeading({level: parseInt(level)}).run();
            typographyDropdown?.hide?.();
        });
    });

    const textSizeDropdown = window.FlowbiteInstances?.getInstance?.('Dropdown', 'textSizeDropdown');
    document.querySelectorAll('[data-text-size]').forEach((button) => {
        button.addEventListener('click', () => {
            const fontSize = button.getAttribute('data-text-size');
            editor.chain().focus().setMark('textStyle', {fontSize}).run();
            textSizeDropdown?.hide?.();
        });
    });

    const colorPicker = document.getElementById('color');
    if (colorPicker) {
        colorPicker.addEventListener('input', (event) => {
            const selectedColor = event.target.value;
            editor.chain().focus().setColor(selectedColor).run();
        });
    }

    document.querySelectorAll('[data-hex-color]').forEach((button) => {
        button.addEventListener('click', () => {
            const selectedColor = button.getAttribute('data-hex-color');
            editor.chain().focus().setColor(selectedColor).run();
        });
    });

    safeOn('reset-color', 'click', () => {
        editor.commands.unsetColor();
    });

    const fontFamilyDropdown = window.FlowbiteInstances?.getInstance?.('Dropdown', 'fontFamilyDropdown');
    document.querySelectorAll('[data-font-family]').forEach((button) => {
        button.addEventListener('click', () => {
            const fontFamily = button.getAttribute('data-font-family');
            editor.chain().focus().setFontFamily(fontFamily).run();
            fontFamilyDropdown?.hide?.();
        });
    });

    return editor;
}

window.initTipTap = initTipTap;

document.addEventListener('livewire:navigated', () => {
    initTipTap();
});


// TODO: Remove Image in directory
