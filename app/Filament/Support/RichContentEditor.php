<?php

namespace App\Filament\Support;

use App\Models\Media;
use Filament\Forms\Components\RichEditor;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class RichContentEditor
{
    public static function make(string $name, string $label, string $directory): RichEditor
    {
        return RichEditor::make($name)
            ->label($label)
            ->fileAttachmentsDisk('public')
            ->fileAttachmentsDirectory($directory)
            ->fileAttachmentsVisibility('public')
            ->fileAttachmentsAcceptedFileTypes([
                'image/jpeg',
                'image/png',
                'image/webp',
                'image/gif',
            ])
            ->fileAttachmentsMaxSize(300)
            ->saveUploadedFileAttachmentUsing(function (TemporaryUploadedFile $file) use ($directory): string {
                $fileName = StoredFileName::uniqueFromUpload($file, $directory);
                $path = $file->storeAs($directory, $fileName, 'public');

                Media::create([
                    'filename' => $fileName,
                    'path' => $path,
                    'uploaded_by' => auth()->id(),
                ]);

                return $path;
            })
            ->floatingToolbars([
                'table' => [
                    'tableAddColumnBefore', 'tableAddColumnAfter', 'tableDeleteColumn',
                    'tableAddRowBefore', 'tableAddRowAfter', 'tableDeleteRow',
                    'tableMergeCells', 'tableSplitCell',
                    'tableToggleHeaderRow', 'tableToggleHeaderCell',
                    'tableDelete',
                ],
            ])
            ->resizableImages();
    }
}
