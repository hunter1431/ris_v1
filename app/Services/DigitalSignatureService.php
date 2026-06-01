<?php

namespace App\Services;

use App\Models\RisHeader;
use App\Models\SignatureImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DigitalSignatureService
{
    public function store(UploadedFile $file, int $userId, string $type, string $label = 'default'): SignatureImage
    {
        $path = $file->store("signatures/{$userId}", 'public');

        SignatureImage::where('user_id', $userId)
            ->where('signature_type', $type)
            ->update(['is_active' => false]);

        return SignatureImage::create([
            'user_id' => $userId,
            'label' => $label,
            'signature_type' => $type,
            'path' => $path,
            'is_active' => true,
        ]);
    }

    public function forRisPdf(RisHeader $ris): array
    {
        return [
            'requester' => $this->activeSignatureUrl($ris->requested_by, 'requester'),
            'approver' => $this->activeSignatureUrl($ris->approved_by, 'approver'),
            'issuer' => $this->activeSignatureUrl($ris->issued_by, 'issuer'),
            'receiver' => $this->activeSignatureUrl($ris->received_by, 'receiver'),
        ];
    }

    private function activeSignatureUrl(?int $userId, string $type): ?string
    {
        if (! $userId) {
            return null;
        }

        $signature = SignatureImage::where('user_id', $userId)
            ->where('signature_type', $type)
            ->where('is_active', true)
            ->latest()
            ->first();

        return $signature ? Storage::disk('public')->url($signature->path) : null;
    }
}
