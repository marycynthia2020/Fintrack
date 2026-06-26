<?php

namespace FinTrack\Core\Traits;

use FinTrack\Core\Models\Attachment;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasAttachments
{
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function attach(string $name, UploadedFile $file, string $folder = 'attachments', string $storage = 'local'): Attachment
    {
        $filename = "{$name}.{$file->getClientOriginalExtension()}";
        $path     = $file->storeAs($folder, $filename, $storage);
        $url      = $this->disk($storage)->url($path);

        return $this->attachments()->create([
            'organization_id' => $this->organization_id ?? null,
            'name'            => $filename,
            'original_name'   => $file->getClientOriginalName(),
            'path'            => $path,
            'url'             => $url,
            'mime_type'       => $file->getMimeType(),
            'size'            => $file->getSize(),
            'disk'            => $storage,
            'metadata'        => null,
        ]);
    }

    public function attachMany(array $files, string $folder = 'attachments', string $storage = 'local'): array
    {
        return array_map(
            fn(string $name, UploadedFile $file) => $this->attach($name, $file, $folder, $storage),
            array_keys($files),
            $files
        );
    }

    public function hasAttachment(string $attachmentId): bool
    {
        return $this->attachments()->where('id', $attachmentId)->exists();
    }

    public function remove(string $attachmentId): bool
    {
        /** @var Attachment|null $attachment */
        $attachment = $this->attachments()->where('id', $attachmentId)->first();

        if (! $attachment) {
            return false;
        }

        $this->disk($attachment->disk)->delete($attachment->path);

        return (bool) $attachment->delete();
    }

    public function removeAll(): void
    {
        $this->attachments()->get()->each(function (Attachment $attachment) {
            $this->disk($attachment->disk)->delete($attachment->path);
            $attachment->delete();
        });
    }

    public function getAttachmentUrl(string $attachmentId): ?string
    {
        /** @var Attachment|null $attachment */
        $attachment = $this->attachments()->where('id', $attachmentId)->first();

        return $attachment ? $this->disk($attachment->disk)->url($attachment->path) : null;
    }

    public function getTemporaryUrl(string $attachmentId, \DateTimeInterface $expiry): ?string
    {
        /** @var Attachment|null $attachment */
        $attachment = $this->attachments()->where('id', $attachmentId)->first();

        return $attachment ? $this->disk($attachment->disk)->temporaryUrl($attachment->path, $expiry) : null;
    }

    public function attachmentsByMimeType(string $mimeType): MorphMany
    {
        return $this->attachments()->where('mime_type', $mimeType);
    }

    private function disk(string $disk): FilesystemAdapter
    {
        $fs = Storage::disk($disk);
        assert($fs instanceof FilesystemAdapter);
        return $fs;
    }
}
