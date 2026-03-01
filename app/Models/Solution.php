<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Solution extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'title',
        'body',
        'category_id',
        'tags',
        'source_ticket_id',
        'created_by',
        'is_published',
        'view_count',
        'helpful_count',
        'not_helpful_count',
    ];

    protected function casts(): array
    {
        return [
            'is_published'      => 'boolean',
            'view_count'        => 'integer',
            'helpful_count'     => 'integer',
            'not_helpful_count' => 'integer',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function category()
    {
        return $this->belongsTo(TicketCategory::class, 'category_id');
    }

    public function sourceTicket()
    {
        return $this->belongsTo(Ticket::class, 'source_ticket_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Scopes ────────────────────────────────────────────────────────

    public function scopeForCompany($query, int $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('tags',  'like', "%{$term}%")
              ->orWhere('body',  'like', "%{$term}%");
        });
    }
}
