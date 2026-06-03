<?php

namespace App\Http\Controllers\Api;

use App\Actions\Ris\ApproveRisAction;
use App\Actions\Ris\CreateRisAction;
use App\Actions\Ris\IssueRisAction;
use App\Actions\Ris\SubmitRisAction;
use App\DTOs\RisData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRisRequest;
use App\Http\Resources\RisResource;
use App\Models\RisHeader;
use App\Repositories\RisRepository;
use App\Services\DigitalSignatureService;
use App\Services\QrVerificationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RisController extends Controller
{
    public function index(Request $request, RisRepository $repository)
    {
        return RisResource::collection($repository->paginate($request->only(['status', 'per_page'])));
    }

    public function store(StoreRisRequest $request, CreateRisAction $action)
    {
        return new RisResource($action->execute(RisData::fromArray($request->validated()), $request->user()->id));
    }

    public function submit(RisHeader $ris, Request $request, SubmitRisAction $action)
    {
        return new RisResource($action->execute($ris, $request->user()->id));
    }

    public function approve(RisHeader $ris, Request $request, ApproveRisAction $action)
    {
        return new RisResource($action->execute($ris, $request->user()->id, $request->input('remarks')));
    }

    public function issue(RisHeader $ris, Request $request, IssueRisAction $action)
    {
        $data = $request->validate([
            'details' => ['required', 'array', 'min:1'],
            'details.*.id' => ['required', 'exists:ris_details,id'],
            'details.*.qty_issued' => ['required', 'numeric', 'min:0'],
            'details.*.remarks' => ['nullable', 'string'],
        ]);

        return new RisResource($action->execute($ris, $request->user()->id, $data['details']));
    }

    public function update(RisHeader $ris, StoreRisRequest $request, RisRepository $repository)
    {
        $user = $request->user();

        // requesters may update their own RIS only when it's still a draft
        if ($user->id !== $ris->requested_by && ! $user->can('manage users')) {
            abort(403);
        }

        if ($ris->status !== 'draft' && ! $user->can('manage users')) {
            abort(403, 'Only draft RIS can be updated.');
        }

        $data = $request->validated();

        return DB::transaction(function () use ($ris, $data, $repository) {
            $headerData = [
                'entity_name' => $data['entity_name'],
                'fund_cluster' => $data['fund_cluster'] ?? null,
                'division_id' => $data['division_id'],
                'office' => $data['office'],
                'responsibility_center_code' => $data['responsibility_center_code'] ?? null,
                'purpose' => $data['purpose'],
            ];

            $repository->updateHeader($ris, $headerData);
            $repository->replaceDetails($ris, $data['details']);

            return new RisResource($ris->fresh(['division', 'details.item']));
        });
    }

    public function pdf(RisHeader $ris, QrVerificationService $qr, DigitalSignatureService $signatures)
    {
        $ris->load(['division', 'details.item', 'requestedBy', 'approvedBy', 'issuedBy', 'receivedBy']);

        return Pdf::loadView('pdf.ris', [
            'ris' => $ris,
            'qrCode' => $qr->svgDataUri($ris),
            'verificationUrl' => $qr->verificationUrl($ris),
            'signatures' => $signatures->forRisPdf($ris),
        ])->download("{$ris->ris_no}.pdf");
    }
}
