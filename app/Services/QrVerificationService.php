<?php

namespace App\Services;

use App\Models\RisHeader;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\SvgWriter;

class QrVerificationService
{
    public function verificationUrl(RisHeader $ris): string
    {
        return url("/verify-ris/{$ris->qr_token}");
    }

    public function svgDataUri(RisHeader $ris): string
    {
        $result = Builder::create()
            ->writer(new SvgWriter())
            ->data($this->verificationUrl($ris))
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(120)
            ->margin(4)
            ->build();

        return 'data:image/svg+xml;base64,'.base64_encode($result->getString());
    }
}
