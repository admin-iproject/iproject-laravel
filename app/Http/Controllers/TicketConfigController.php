<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TicketStatus;
use App\Models\TicketPriority;
use App\Models\TicketCategory;
use App\Models\TicketCloseReason;
use App\Models\SlaPolicy;

class TicketConfigController extends Controller
{
    private function companyId(): int
    {
        return Auth::user()->company_id;
    }

    // ── Load all config data for the slideout ──────────────────────────
    public function index()
    {
        $cid = $this->companyId();
        return response()->json([
            'statuses'      => TicketStatus::forCompany($cid)->orderBy('sort_order')->get(),
            'priorities'    => TicketPriority::forCompany($cid)->orderBy('sort_order')->get(),
            'categories'    => TicketCategory::forCompany($cid)->orderBy('sort_order')->get(),
            'close_reasons' => TicketCloseReason::forCompany($cid)->orderBy('sort_order')->get(),
            'sla_policies'  => SlaPolicy::forCompany($cid)->orderBy('name')->get(),
        ]);
    }

    // ── STATUSES ───────────────────────────────────────────────────────
    public function storeStatus(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:100',
            'color'            => 'required|string|max:7',
            'sort_order'       => 'integer',
            'is_default_open'  => 'boolean',
            'is_resolved'      => 'boolean',
            'is_closed'        => 'boolean',
            'stops_sla_clock'  => 'boolean',
        ]);

        // Only one default open status allowed
        if (!empty($data['is_default_open'])) {
            TicketStatus::forCompany($this->companyId())->update(['is_default_open' => false]);
        }

        $status = TicketStatus::create(array_merge($data, [
            'company_id' => $this->companyId(),
            'is_active'  => true,
            'sort_order' => $data['sort_order'] ?? TicketStatus::forCompany($this->companyId())->max('sort_order') + 1,
        ]));

        return response()->json($status, 201);
    }

    public function updateStatus(Request $request, TicketStatus $status)
    {
        $this->authorizeCompany($status->company_id);

        $data = $request->validate([
            'name'            => 'sometimes|string|max:100',
            'color'           => 'sometimes|string|max:7',
            'sort_order'      => 'sometimes|integer',
            'is_default_open' => 'sometimes|boolean',
            'is_resolved'     => 'sometimes|boolean',
            'is_closed'       => 'sometimes|boolean',
            'stops_sla_clock' => 'sometimes|boolean',
            'is_active'       => 'sometimes|boolean',
        ]);

        if (!empty($data['is_default_open'])) {
            TicketStatus::forCompany($this->companyId())->where('id', '!=', $status->id)->update(['is_default_open' => false]);
        }

        $status->update($data);
        return response()->json($status);
    }

    public function destroyStatus(TicketStatus $status)
    {
        $this->authorizeCompany($status->company_id);
        $status->delete();
        return response()->json(['ok' => true]);
    }

    // ── PRIORITIES ─────────────────────────────────────────────────────
    public function storePriority(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'color'      => 'required|string|max:7',
            'level'      => 'required|integer|min:1|max:10',
            'sort_order' => 'integer',
        ]);

        $priority = TicketPriority::create(array_merge($data, [
            'company_id' => $this->companyId(),
            'is_active'  => true,
            'sort_order' => $data['sort_order'] ?? TicketPriority::forCompany($this->companyId())->max('sort_order') + 1,
        ]));

        return response()->json($priority, 201);
    }

    public function updatePriority(Request $request, TicketPriority $priority)
    {
        $this->authorizeCompany($priority->company_id);

        $data = $request->validate([
            'name'       => 'sometimes|string|max:100',
            'color'      => 'sometimes|string|max:7',
            'level'      => 'sometimes|integer|min:1|max:10',
            'sort_order' => 'sometimes|integer',
            'is_active'  => 'sometimes|boolean',
        ]);

        $priority->update($data);
        return response()->json($priority);
    }

    public function destroyPriority(TicketPriority $priority)
    {
        $this->authorizeCompany($priority->company_id);
        $priority->delete();
        return response()->json(['ok' => true]);
    }

    // ── CATEGORIES ─────────────────────────────────────────────────────
    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'parent_id'  => 'nullable|exists:ticket_categories,id',
            'sort_order' => 'integer',
        ]);

        $category = TicketCategory::create(array_merge($data, [
            'company_id' => $this->companyId(),
            'is_active'  => true,
            'sort_order' => $data['sort_order'] ?? 0,
        ]));

        return response()->json($category, 201);
    }

    public function updateCategory(Request $request, TicketCategory $category)
    {
        $this->authorizeCompany($category->company_id);

        $data = $request->validate([
            'name'       => 'sometimes|string|max:100',
            'parent_id'  => 'sometimes|nullable|exists:ticket_categories,id',
            'sort_order' => 'sometimes|integer',
            'is_active'  => 'sometimes|boolean',
        ]);

        $category->update($data);
        return response()->json($category);
    }

    public function destroyCategory(TicketCategory $category)
    {
        $this->authorizeCompany($category->company_id);
        $category->delete();
        return response()->json(['ok' => true]);
    }

    // ── CLOSE REASONS ──────────────────────────────────────────────────
    public function storeCloseReason(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'sort_order' => 'integer',
        ]);

        $reason = TicketCloseReason::create(array_merge($data, [
            'company_id' => $this->companyId(),
            'is_active'  => true,
            'sort_order' => $data['sort_order'] ?? 0,
        ]));

        return response()->json($reason, 201);
    }

    public function updateCloseReason(Request $request, TicketCloseReason $closeReason)
    {
        $this->authorizeCompany($closeReason->company_id);

        $data = $request->validate([
            'name'       => 'sometimes|string|max:100',
            'sort_order' => 'sometimes|integer',
            'is_active'  => 'sometimes|boolean',
        ]);

        $closeReason->update($data);
        return response()->json($closeReason);
    }

    public function destroyCloseReason(TicketCloseReason $closeReason)
    {
        $this->authorizeCompany($closeReason->company_id);
        $closeReason->delete();
        return response()->json(['ok' => true]);
    }

    // ── SLA POLICIES ───────────────────────────────────────────────────
    public function storeSlaPolicy(Request $request)
    {
        $data = $request->validate([
            'name'                     => 'required|string|max:100',
            'priority'                 => 'nullable|string|max:50',
            'ticket_type'              => 'nullable|string|max:50',
            'first_response_minutes'   => 'required|integer|min:1',
            'resolution_minutes'       => 'required|integer|min:1',
        ]);

        $policy = SlaPolicy::create(array_merge($data, [
            'company_id' => $this->companyId(),
            'is_active'  => true,
        ]));

        return response()->json($policy, 201);
    }

    public function updateSlaPolicy(Request $request, SlaPolicy $slaPolicy)
    {
        $this->authorizeCompany($slaPolicy->company_id);

        $data = $request->validate([
            'name'                   => 'sometimes|string|max:100',
            'priority'               => 'sometimes|nullable|string|max:50',
            'ticket_type'            => 'sometimes|nullable|string|max:50',
            'first_response_minutes' => 'sometimes|integer|min:1',
            'resolution_minutes'     => 'sometimes|integer|min:1',
            'is_active'              => 'sometimes|boolean',
        ]);

        $slaPolicy->update($data);
        return response()->json($slaPolicy);
    }

    public function destroySlaPolicy(SlaPolicy $slaPolicy)
    {
        $this->authorizeCompany($slaPolicy->company_id);
        $slaPolicy->delete();
        return response()->json(['ok' => true]);
    }

    // ── Guard ──────────────────────────────────────────────────────────
    private function authorizeCompany(int $recordCompanyId): void
    {
        if ($recordCompanyId !== $this->companyId()) {
            abort(403);
        }
    }
}
