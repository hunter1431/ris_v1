<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SignatureImage;
use App\Services\DigitalSignatureService;
use Illuminate\Http\Request;

class SignatureController extends Controller
{
    public function index(Request $request)
    {
        return SignatureImage::where('user_id', $request->user()->id)->latest()->get();
    }

    public function store(Request $request, DigitalSignatureService $service): SignatureImage
    {
        $data = $request->validate([
            'signature' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'signature_type' => ['required', 'in:requester,approver,issuer,receiver'],
            'label' => ['nullable', 'string', 'max:100'],
        ]);

        return $service->store(
            $request->file('signature'),
            $request->user()->id,
            $data['signature_type'],
            $data['label'] ?? 'default',
        );
    }
}
