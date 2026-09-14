<div x-data="{
    content: @entangle('howto'),
    init() {
        const setupEditor = () => {
            if (typeof window.initTipTap === 'function') {
                const editor = window.initTipTap('editor');
                if (editor) {
                    editor.on('update', () => {
                        this.content = editor.getHTML();
                    });
                } else {
                    setTimeout(setupEditor, 50);
                }
            } else {
                setTimeout(setupEditor, 50);
            }
        };

        this.$nextTick(() => setupEditor());
    }
}">
    <x-form.label for="howto" :value="__('How to')"/>

    <x-tiptapeditor.editor :enable-image-upload="$allowImageUpload ?? true">
        <div id="editor"
             wire:ignore
             data-initial="{{ $howto }}"
             data-upload-url="{{ route('editor.uploads.images') }}"
             data-delete-url="{{ route('editor.uploads.images.destroy') }}"
             class="min-h-37.5 prose dark:prose-invert max-w-none">
        </div>
    </x-tiptapeditor.editor>

    <x-form.error :messages="$errors->get('howto')"/>
</div>
