<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'logo',
        'phone1',
        'phone2',
        'fax',
        'email',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'zip',
        'country',
        'primary_url',
        'owner_id',
        'description',
        'type',
        'custom',
        'category',
        'num_of_licensed_users',
        'ticket_priorities',
        'ticket_categories',
        'ticket_notification',
        'ticket_notify_email',
        'ticket_close_reasons',
        'tracker_categories',
        'tracker_priorities',
        'tracker_notification',
        'tracker_notify_email',
        'tracker_close_reasons',
        'tracker_phase',
        'otrs_categories',
        'otrs_priorities',
        'otrs_notification',
        'otrs_notify_email',
        'otrs_close_reasons',
        'otrs_phase',
        'user_roles',
        'rss',
        'last_edited',
        'last_edited_by',
    ];

    protected $casts = [
        'last_edited' => 'datetime',
        'type' => 'integer',
        'num_of_licensed_users' => 'integer',
    ];

    /**
     * Get the owner of the company.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the user who last edited the company.
     */
    public function lastEditor()
    {
        return $this->belongsTo(User::class, 'last_edited_by');
    }

    /**
     * Get the departments for the company.
     */
    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    /**
     * Get the projects for the company.
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get the users belonging to the company.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the contacts for the company.
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    /**
     * Get the tickets for the company.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get the news items for the company.
     */
    public function news()
    {
        return $this->hasMany(CompanyNews::class);
    }

    /**
     * Scope a query to only include active companies.
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }
}
