@props(['enableImageUpload' => false])
<div class="flex items-center flex-wrap gap-y-2">

    <div class="flex items-center gap-2 pt-2 flex-wrap">
        <button id="typographyDropdownButton" data-dropdown-toggle="typographyDropdown"
                class="flex items-center justify-center rounded-lg bg-surface px-3 py-1.5 text-sm font-medium text-primary hover:bg-surface-hover hover:text-primary focus:z-50 focus:outline-none"
                type="button">
            Format
            <svg class="-me-0.5 ms-1.5 h-3.5 w-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="m19 9-7 7-7-7"/>
            </svg>
        </button>
        <div class="ps-1.5">
            <span class="block w-px h-4 bg-muted"></span>
        </div>
        <!-- Heading Dropdown -->
        <div id="typographyDropdown" class="z-50 hidden w-72 rounded-lg border border-border bg-surface p-2 shadow-sm">
            <ul class="space-y-1 text-sm font-medium" aria-labelledby="typographyDropdownButton">
                <li>
                    <button id="toggleParagraphButton" type="button"
                            class="flex justify-between items-center w-full text-base rounded-sm px-3 py-2 hover:bg-surface-hover text-primary">
                        Paragraph
                    </button>
                </li>
                <li>
                    <button data-heading-level="1" type="button"
                            class="flex justify-between items-center w-full text-base rounded-sm px-3 py-2 hover:bg-surface-hover text-primary">
                        Heading 1
                    </button>
                </li>
                <li>
                    <button data-heading-level="2" type="button"
                            class="flex justify-between items-center w-full text-base rounded-sm px-3 py-2 hover:bg-surface-hover text-primary">
                        Heading 2
                    </button>
                </li>
                <li>
                    <button data-heading-level="3" type="button"
                            class="flex justify-between items-center w-full text-base rounded-sm px-3 py-2 hover:bg-surface-hover text-primary">
                        Heading 3
                    </button>
                </li>
            </ul>
        </div>
    </div>
    <x-tiptapeditor.toolbar-item id="Bold" icon="bold" sr="Bold" title="Text Bold"/>
    <x-tiptapeditor.toolbar-item id="Italic" icon="italic" sr="Italic" title="Text Italic"/>
    <x-tiptapeditor.toolbar-item id="Underline" icon="underline" sr="Underline" title="Text Underline"/>

    <div class="px-1">
        <span class="block w-px h-4 bg-muted"></span>
    </div>

    <x-tiptapeditor.toolbar-item id="List" icon="list" sr="List" title="Unordered List"/>
    <x-tiptapeditor.toolbar-item id="OrderedList" icon="list-ordered" sr="OrderedList" title="Ordered List"/>
    <x-tiptapeditor.toolbar-item id="Blockquote" icon="quote" sr="Blockquote" title="Blockquote"/>

    <div class="px-1">
        <span class="block w-px h-4 bg-muted"></span>
    </div>

    <x-tiptapeditor.toolbar-item id="LeftAlign" icon="align-left" sr="LeftAlign" title="Text Left"/>
    <x-tiptapeditor.toolbar-item id="CenterAlign" icon="align-center" sr="CenterAlign" title="Text Center"/>
    <x-tiptapeditor.toolbar-item id="RightAlign" icon="align-right" sr="RightAlign" title="Text Right"/>
    <x-tiptapeditor.toolbar-item id="Code" icon="code" sr="Code" title="Code"/>

    <div class="px-1">
        <span class="block w-px h-4 bg-muted"></span>
    </div>

    <x-tiptapeditor.toolbar-item id="Link" icon="link" sr="Link" Title="Add Link"/>
    <button id="removeLinkButton"
            data-tooltip-target="tooltip-remove-link"
            type="button"
            class="p-1.5 text-muted rounded-sm cursor-pointer hover:text-primary hover:bg-surface-hover">
        <x-lucide-unlink class="w-5 h-5"/>
        <span class="sr-only">Remove link</span>
    </button>
    <div id="tooltip-remove-link"
         role="tooltip"
         class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-primary transition-opacity duration-300 bg-surface rounded-lg shadow-xs opacity-0 tooltip">
        Remove link
        <div class="tooltip-arrow" data-popper-arrow></div>
    </div>
    @if($enableImageUpload)
        <button id="addImageButton"
                type="button"
                data-tooltip-target="tooltip-image"
                class="p-1.5 text-muted rounded-sm cursor-pointer hover:text-primary hover:surface-hover">
            <x-lucide-image class="w-5 h-5"/>
            <span class="sr-only">Add image</span>
        </button>
        <input type="file" id="editorImageInput" accept="image/*" class="hidden"/>
        <div id="tooltip-image"
             role="tooltip"
             class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-primary transition-opacity duration-300 bg-surface rounded-lg shadow-xs opacity-0 tooltip">
            Add image
            <div class="tooltip-arrow" data-popper-arrow></div>
        </div>
    @endif
</div>
