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
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

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

    public function pdf(RisHeader $ris)
    {
        $ris->load(['division', 'details.item', 'requestedBy', 'approvedBy', 'issuedBy', 'receivedBy']);
        return Pdf::loadView('pdf.ris', ['ris' => $ris])->download("{$ris->ris_no}.pdf");
    }
}
