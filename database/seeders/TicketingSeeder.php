<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TicketingSeeder extends Seeder
{
    public function run(): void
    {
        // ── Assumes at least one company (id=1) and a few users exist ──────
        $companyId = DB::table('companies')->value('id') ?? 1;
        $users     = DB::table('users')->pluck('id')->toArray();
        $deptId    = DB::table('departments')->where('company_id', $companyId)->value('id');

        if (empty($users)) {
            $this->command->warn('No users found — skipping TicketingSeeder.');
            return;
        }

        $agent1 = $users[0];
        $agent2 = $users[1] ?? $users[0];
        $agent3 = $users[2] ?? $users[0];

        // ── 1. Ticket Priorities ──────────────────────────────────────────
        $priorities = [
            ['level' => 1, 'name' => 'Critical', 'color' => '#dc2626', 'sort_order' => 1],
            ['level' => 2, 'name' => 'High',     'color' => '#ea580c', 'sort_order' => 2],
            ['level' => 3, 'name' => 'Medium',   'color' => '#ca8a04', 'sort_order' => 3],
            ['level' => 4, 'name' => 'Low',      'color' => '#16a34a', 'sort_order' => 4],
        ];
        foreach ($priorities as $p) {
            DB::table('ticket_priorities')->insertOrIgnore(array_merge($p, [
                'company_id' => $companyId,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // ── 2. Ticket Statuses ────────────────────────────────────────────
        $statuses = [
            ['name' => 'New',               'color' => '#6366f1', 'sort_order' => 1, 'stops_sla_clock' => false, 'is_default_open' => true,  'is_resolved' => false, 'is_closed' => false],
            ['name' => 'Open',              'color' => '#3b82f6', 'sort_order' => 2, 'stops_sla_clock' => false, 'is_default_open' => false, 'is_resolved' => false, 'is_closed' => false],
            ['name' => 'In Progress',       'color' => '#f59e0b', 'sort_order' => 3, 'stops_sla_clock' => false, 'is_default_open' => false, 'is_resolved' => false, 'is_closed' => false],
            ['name' => 'Awaiting Customer', 'color' => '#8b5cf6', 'sort_order' => 4, 'stops_sla_clock' => true,  'is_default_open' => false, 'is_resolved' => false, 'is_closed' => false],
            ['name' => 'Awaiting 3rd Party','color' => '#ec4899', 'sort_order' => 5, 'stops_sla_clock' => true,  'is_default_open' => false, 'is_resolved' => false, 'is_closed' => false],
            ['name' => 'On Hold',           'color' => '#94a3b8', 'sort_order' => 6, 'stops_sla_clock' => true,  'is_default_open' => false, 'is_resolved' => false, 'is_closed' => false],
            ['name' => 'Resolved',          'color' => '#22c55e', 'sort_order' => 7, 'stops_sla_clock' => false, 'is_default_open' => false, 'is_resolved' => true,  'is_closed' => false],
            ['name' => 'Closed',            'color' => '#64748b', 'sort_order' => 8, 'stops_sla_clock' => false, 'is_default_open' => false, 'is_resolved' => false, 'is_closed' => true],
        ];
        $statusIds = [];
        foreach ($statuses as $s) {
            $id = DB::table('ticket_statuses')->insertGetId(array_merge($s, [
                'company_id' => $companyId,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
            $statusIds[$s['name']] = $id;
        }

        // ── 3. Ticket Categories ──────────────────────────────────────────
        $categories = [
            ['name' => 'Hardware',           'parent_id' => null],
            ['name' => 'Software',           'parent_id' => null],
            ['name' => 'Network',            'parent_id' => null],
            ['name' => 'Access & Security',  'parent_id' => null],
            ['name' => 'Email',              'parent_id' => null],
            ['name' => 'General IT',         'parent_id' => null],
        ];
        $categoryIds = [];
        foreach ($categories as $c) {
            $id = DB::table('ticket_categories')->insertGetId(array_merge($c, [
                'company_id'  => $companyId,
                'department_id' => $deptId,
                'is_active'   => true,
                'sort_order'  => 0,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]));
            $categoryIds[$c['name']] = $id;
        }

        // ── 4. Close Reasons ──────────────────────────────────────────────
        $closeReasons = ['Resolved by Support', 'User Self-Resolved', 'Duplicate', 'No Response', 'Not an Issue', 'Workaround Provided'];
        $closeReasonIds = [];
        foreach ($closeReasons as $i => $r) {
            $closeReasonIds[] = DB::table('ticket_close_reasons')->insertGetId([
                'company_id' => $companyId,
                'name'       => $r,
                'sort_order' => $i,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ── 5. SLA Policies ───────────────────────────────────────────────
        $slaPolicies = [
            ['name' => 'Critical SLA', 'priority' => 1, 'first_response_minutes' => 15,   'resolution_minutes' => 240],
            ['name' => 'High SLA',     'priority' => 2, 'first_response_minutes' => 60,   'resolution_minutes' => 480],
            ['name' => 'Medium SLA',   'priority' => 3, 'first_response_minutes' => 240,  'resolution_minutes' => 1440],
            ['name' => 'Low SLA',      'priority' => 4, 'first_response_minutes' => 480,  'resolution_minutes' => 2880],
        ];
        $slaPolicyIds = [];
        foreach ($slaPolicies as $s) {
            $slaPolicyIds[$s['priority']] = DB::table('sla_policies')->insertGetId(array_merge($s, [
                'company_id'  => $companyId,
                'ticket_type' => null,
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]));
        }

        // ── 6. Routing Rules ──────────────────────────────────────────────
        DB::table('ticket_routing_rules')->insert([
            [
                'company_id'             => $companyId,
                'name'                   => 'Network issues → IT Ops',
                'priority_order'         => 1,
                'match_department_id'    => $deptId,
                'match_category_id'      => $categoryIds['Network'],
                'match_ticket_type'      => null,
                'match_priority'         => null,
                'match_company_id'       => null,
                'assign_to_user_id'      => $agent1,
                'assign_to_department_id'=> $deptId,
                'round_robin'            => false,
                'is_active'              => true,
                'created_at'             => now(),
                'updated_at'             => now(),
            ],
            [
                'company_id'             => $companyId,
                'name'                   => 'Critical tickets → Senior Tech',
                'priority_order'         => 2,
                'match_department_id'    => null,
                'match_category_id'      => null,
                'match_ticket_type'      => 'incident',
                'match_priority'         => 1,
                'match_company_id'       => null,
                'assign_to_user_id'      => $agent1,
                'assign_to_department_id'=> null,
                'round_robin'            => false,
                'is_active'              => true,
                'created_at'             => now(),
                'updated_at'             => now(),
            ],
        ]);

        // ── 7. Content Rules ──────────────────────────────────────────────
        $ruleId1 = DB::table('ticket_content_rules')->insertGetId([
            'company_id'          => $companyId,
            'name'                => 'URGENT keyword → P1',
            'priority_order'      => 1,
            'match_field'         => 'subject',
            'match_type'          => 'contains',
            'match_value'         => 'URGENT',
            'match_case_sensitive'=> false,
            'stop_processing'     => false,
            'is_active'           => true,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);
        DB::table('ticket_content_rule_actions')->insert([
            ['rule_id' => $ruleId1, 'action_type' => 'set_priority', 'action_value' => '1', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $ruleId2 = DB::table('ticket_content_rules')->insertGetId([
            'company_id'          => $companyId,
            'name'                => 'Out of Office auto-discard',
            'priority_order'      => 2,
            'match_field'         => 'subject',
            'match_type'          => 'starts_with',
            'match_value'         => 'Out of Office',
            'match_case_sensitive'=> false,
            'stop_processing'     => true,
            'is_active'           => true,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);
        DB::table('ticket_content_rule_actions')->insert([
            ['rule_id' => $ruleId2, 'action_type' => 'discard', 'action_value' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $ruleId3 = DB::table('ticket_content_rules')->insertGetId([
            'company_id'          => $companyId,
            'name'                => 'Server/network body → Incident type',
            'priority_order'      => 3,
            'match_field'         => 'body',
            'match_type'          => 'regex',
            'match_value'         => '/server.*(down|outage|offline)/i',
            'match_case_sensitive'=> false,
            'stop_processing'     => false,
            'is_active'           => true,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);
        DB::table('ticket_content_rule_actions')->insert([
            ['rule_id' => $ruleId3, 'action_type' => 'set_type',     'action_value' => 'incident', 'created_at' => now(), 'updated_at' => now()],
            ['rule_id' => $ruleId3, 'action_type' => 'set_priority', 'action_value' => '1',        'created_at' => now(), 'updated_at' => now()],
        ]);

        // ── 8. Assets ─────────────────────────────────────────────────────
        $assetIds = [];
        $assetData = [
            ['name' => 'Web Server 01',     'type' => 'server',      'serial_number' => 'SRV-001', 'status' => 'active',  'lat' => 42.9634,  'lng' => -85.6681],
            ['name' => 'DB Server 01',      'type' => 'server',      'serial_number' => 'SRV-002', 'status' => 'active',  'lat' => 42.9634,  'lng' => -85.6681],
            ['name' => 'Office Printer 1',  'type' => 'printer',     'serial_number' => 'PRN-001', 'status' => 'active',  'lat' => 42.9634,  'lng' => -85.6682],
            ['name' => 'Dev Laptop - Agent','type' => 'workstation', 'serial_number' => 'WS-001',  'status' => 'active',  'lat' => 42.9635,  'lng' => -85.6683],
            ['name' => 'Old Switch',        'type' => 'network',     'serial_number' => 'NET-001', 'status' => 'maintenance', 'lat' => 42.9636, 'lng' => -85.6680],
        ];
        foreach ($assetData as $a) {
            $assetIds[] = DB::table('assets')->insertGetId(array_merge($a, [
                'company_id'       => $companyId,
                'department_id'    => $deptId,
                'assigned_user_id' => $agent1,
                'location'         => 'Server Room A',
                'created_at'       => now(),
                'updated_at'       => now(),
            ]));
        }

        // ── 9. Sample Tickets ─────────────────────────────────────────────
        $reporter1Email = DB::table('users')->where('id', $agent2)->value('email')
                       ?? DB::table('users')->where('id', $agent2)->value('email')
                       ?? 'reporter@example.com';

        $ticketsData = [
            [
                'type'         => 'incident',
                'subject'      => 'Web server is down — production outage',
                'body'         => '<p>The production web server has been unresponsive since 09:15 AM. All users are affected. Please escalate immediately.</p>',
                'priority'     => 1,
                'status'       => 'In Progress',
                'reporter_id'  => $agent2,
                'assignee_id'  => $agent1,
                'category'     => 'Network',
                'sla_priority' => 1,
                'lat'          => 42.9634, 'lng' => -85.6681,
                'source'       => 'email',
                'created_at'   => Carbon::now()->subHours(3),
                'asset_index'  => 0,
            ],
            [
                'type'         => 'request',
                'subject'      => 'New laptop setup for new hire starting Monday',
                'body'         => '<p>We have a new hire starting on Monday. Please set up a laptop with standard software package and create their AD account.</p>',
                'priority'     => 3,
                'status'       => 'New',
                'reporter_id'  => $agent3,
                'assignee_id'  => null,
                'category'     => 'Hardware',
                'sla_priority' => 3,
                'lat'          => 42.9710, 'lng' => -85.6550,
                'source'       => 'portal',
                'created_at'   => Carbon::now()->subHours(1),
                'asset_index'  => null,
            ],
            [
                'type'         => 'problem',
                'subject'      => 'Intermittent network drops affecting multiple users',
                'body'         => '<p>Over the past week, multiple users have reported intermittent network connectivity drops. Appears to affect users on the 3rd floor primarily. Root cause investigation needed.</p>',
                'priority'     => 2,
                'status'       => 'Open',
                'reporter_id'  => $agent1,
                'assignee_id'  => $agent2,
                'category'     => 'Network',
                'sla_priority' => 2,
                'lat'          => 42.9580, 'lng' => -85.6720,
                'source'       => 'manual',
                'created_at'   => Carbon::now()->subDays(2),
                'asset_index'  => 4,
            ],
            [
                'type'         => 'change',
                'subject'      => 'Upgrade database server from MySQL 5.7 to 8.0',
                'body'         => '<p>Planned maintenance window required. Estimated downtime 2 hours. Full backup required before proceeding. Change window: Saturday 11PM - 1AM.</p>',
                'priority'     => 2,
                'status'       => 'Awaiting Customer',
                'reporter_id'  => $agent1,
                'assignee_id'  => $agent1,
                'category'     => 'Software',
                'sla_priority' => 2,
                'lat'          => 42.9800, 'lng' => -85.6600,
                'source'       => 'manual',
                'created_at'   => Carbon::now()->subDays(1),
                'asset_index'  => 1,
            ],
            [
                'type'         => 'request',
                'subject'      => 'Password reset request — locked out of email',
                'body'         => '<p>I am unable to log into my email account. Getting "account locked" message. Need urgent access restored.</p>',
                'priority'     => 2,
                'status'       => 'Resolved',
                'reporter_id'  => $agent3,
                'assignee_id'  => $agent2,
                'category'     => 'Access & Security',
                'sla_priority' => 2,
                'lat'          => 42.9450, 'lng' => -85.6800,
                'source'       => 'email',
                'created_at'   => Carbon::now()->subDays(3),
                'asset_index'  => null,
            ],
            [
                'type'         => 'incident',
                'subject'      => 'Office printer not responding on network',
                'body'         => '<p>The main office printer (HP LaserJet 4th floor) has been offline since this morning. Print jobs are queuing. Tried restarting — no change.</p>',
                'priority'     => 3,
                'status'       => 'Open',
                'reporter_id'  => $agent2,
                'assignee_id'  => $agent3,
                'category'     => 'Hardware',
                'sla_priority' => 3,
                'lat'          => 42.9900, 'lng' => -85.6400,
                'source'       => 'portal',
                'created_at'   => Carbon::now()->subHours(5),
                'asset_index'  => 2,
            ],
        ];

        $ticketIds = [];
        foreach ($ticketsData as $i => $t) {
            $statusId  = $statusIds[$t['status']] ?? array_values($statusIds)[0];
            $catId     = $categoryIds[$t['category']] ?? null;
            $slaId     = $slaPolicyIds[$t['sla_priority']] ?? null;
            $number    = str_pad($i + 1, 5, '0', STR_PAD_LEFT);
            $resolveBy = $t['created_at']->copy()->addMinutes(
                $slaPolicies[$t['sla_priority'] - 1]['resolution_minutes']
            );

            $ticketId = DB::table('tickets')->insertGetId([
                'company_id'             => $companyId,
                'ticket_number'          => 'TK-' . $number,
                'type'                   => $t['type'],
                'status_id'              => $statusId,
                'priority'               => $t['priority'],
                'category_id'            => $catId,
                'department_id'          => $deptId,
                'subject'                => $t['subject'],
                'body'                   => $t['body'],
                'reporter_id'            => $t['reporter_id'],
                'reporter_email'         => $reporter1Email,
                'reporter_name'          => 'Test Reporter',
                'assignee_id'            => $t['assignee_id'],
                'assigned_by'            => $t['assignee_id'] ? $agent1 : null,
                'assigned_at'            => $t['assignee_id'] ? $t['created_at'] : null,
                'sla_policy_id'          => $slaId,
                'resolve_by'             => $resolveBy,
                'first_response_at'      => $t['status'] !== 'New' ? $t['created_at']->copy()->addMinutes(30) : null,
                'first_response_breached'=> false,
                'resolved_at'            => in_array($t['status'], ['Resolved', 'Closed']) ? Carbon::now()->subDay() : null,
                'resolution_breached'    => false,
                'sla_paused_minutes'     => $t['status'] === 'Awaiting Customer' ? 45 : 0,
                'sla_paused_at'          => $t['status'] === 'Awaiting Customer' ? Carbon::now()->subHours(2) : null,
                'lat'                    => $t['lat'],
                'lng'                    => $t['lng'],
                'source'                 => $t['source'],
                'last_activity_at'       => $t['created_at']->copy()->addMinutes(rand(10, 120)),
                'created_at'             => $t['created_at'],
                'updated_at'             => $t['created_at'],
            ]);

            $ticketIds[] = $ticketId;

            // Link asset if applicable
            if ($t['asset_index'] !== null && isset($assetIds[$t['asset_index']])) {
                DB::table('ticket_assets')->insert([
                    'ticket_id'  => $ticketId,
                    'asset_id'   => $assetIds[$t['asset_index']],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Add watcher
            DB::table('ticket_watchers')->insert([
                'ticket_id'            => $ticketId,
                'user_id'              => $agent1,
                'email'                => null,
                'notify_replies'       => true,
                'notify_status_change' => true,
                'created_at'           => now(),
                'updated_at'           => now(),
            ]);
        }

        // ── 10. Ticket Replies ────────────────────────────────────────────
        DB::table('ticket_replies')->insert([
            [
                'ticket_id'  => $ticketIds[0],
                'author_id'  => $agent1,
                'author_email'=> null,
                'author_name' => null,
                'body'       => '<p>I have identified the issue — the Apache service crashed due to a memory leak. Restarting now and monitoring.</p>',
                'is_public'  => true,
                'source'     => 'agent',
                'created_at' => Carbon::now()->subHours(2),
                'updated_at' => Carbon::now()->subHours(2),
            ],
            [
                'ticket_id'  => $ticketIds[0],
                'author_id'  => $agent1,
                'author_email'=> null,
                'author_name' => null,
                'body'       => '<p><strong>Internal note:</strong> Will need to review memory allocation settings after service is stable. Consider upgrading server RAM.</p>',
                'is_public'  => false,
                'source'     => 'agent',
                'created_at' => Carbon::now()->subHours(1),
                'updated_at' => Carbon::now()->subHours(1),
            ],
            [
                'ticket_id'  => $ticketIds[4],
                'author_id'  => $agent2,
                'author_email'=> null,
                'author_name' => null,
                'body'       => '<p>Password has been reset and account unlocked. Please check your email for the temporary password link. Let us know if you have any issues.</p>',
                'is_public'  => true,
                'source'     => 'agent',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
        ]);

        // ── 11. Ticket Logs ───────────────────────────────────────────────
        DB::table('ticket_logs')->insert([
            [
                'ticket_id'   => $ticketIds[0],
                'user_id'     => $agent1,
                'hours'       => 1.5,
                'description' => 'Investigated and restarted Apache service',
                'logged_at'   => Carbon::now()->subHours(2)->toDateString(),
                'cost_code'   => 'IT-OPS',
                'hourly_rate' => 85.00,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'ticket_id'   => $ticketIds[4],
                'user_id'     => $agent2,
                'hours'       => 0.5,
                'description' => 'Reset user password and verified access',
                'logged_at'   => Carbon::now()->subDays(2)->toDateString(),
                'cost_code'   => 'IT-SUPPORT',
                'hourly_rate' => 75.00,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);

        // ── 12. Solutions (Knowledge Base) ────────────────────────────────
        DB::table('solutions')->insert([
            [
                'company_id'       => $companyId,
                'title'            => 'How to reset a locked Active Directory account',
                'body'             => '<h3>Steps to unlock an AD account</h3><ol><li>Open Active Directory Users and Computers</li><li>Search for the user account</li><li>Right-click and select Properties</li><li>Go to the Account tab and check "Unlock account"</li><li>Reset the password if required and notify the user</li></ol>',
                'category_id'      => $categoryIds['Access & Security'],
                'tags'             => 'password,reset,locked,active directory,ad,account',
                'source_ticket_id' => $ticketIds[4],
                'created_by'       => $agent1,
                'is_published'     => true,
                'view_count'       => 12,
                'helpful_count'    => 8,
                'not_helpful_count'=> 1,
                'created_at'       => Carbon::now()->subDays(2),
                'updated_at'       => Carbon::now()->subDays(2),
            ],
            [
                'company_id'       => $companyId,
                'title'            => 'Apache service crash — memory leak diagnosis',
                'body'             => '<h3>Symptoms</h3><p>Apache web server becomes unresponsive, logs show memory exhaustion errors.</p><h3>Resolution</h3><ol><li>SSH into server</li><li>Run <code>sudo systemctl restart apache2</code></li><li>Check <code>/var/log/apache2/error.log</code> for leak source</li><li>Review MaxRequestWorkers setting in apache2.conf</li></ol>',
                'category_id'      => $categoryIds['Software'],
                'tags'             => 'apache,server,down,memory,leak,restart,web server',
                'source_ticket_id' => $ticketIds[0],
                'created_by'       => $agent1,
                'is_published'     => true,
                'view_count'       => 5,
                'helpful_count'    => 4,
                'not_helpful_count'=> 0,
                'created_at'       => Carbon::now()->subHours(1),
                'updated_at'       => Carbon::now()->subHours(1),
            ],
            [
                'company_id'       => $companyId,
                'title'            => 'Network printer offline — basic troubleshooting',
                'body'             => '<h3>Quick checklist</h3><ul><li>Verify printer is powered on and connected to network cable/WiFi</li><li>Print a test page from the printer control panel</li><li>Check IP address has not changed (ping the printer IP)</li><li>Restart the print spooler service on the affected workstation: <code>net stop spooler && net start spooler</code></li><li>Re-add the printer if IP has changed</li></ul>',
                'category_id'      => $categoryIds['Hardware'],
                'tags'             => 'printer,offline,network,print spooler,troubleshoot',
                'source_ticket_id' => null,
                'created_by'       => $agent2,
                'is_published'     => true,
                'view_count'       => 23,
                'helpful_count'    => 19,
                'not_helpful_count'=> 2,
                'created_at'       => Carbon::now()->subDays(10),
                'updated_at'       => Carbon::now()->subDays(10),
            ],
        ]);

        // ── 13. Ticket Mailbox ─────────────────────────────────────────────
        DB::table('ticket_mailboxes')->insert([
            'company_id'            => $companyId,
            'name'                  => 'IT Support',
            'email_address'         => 'support@helpdesk.example.com',
            'protocol'              => 'imap',
            'host'                  => 'mail.example.com',
            'port'                  => 993,
            'use_ssl'               => true,
            'username'              => 'support@helpdesk.example.com',
            'password'              => encrypt('changeme'),
            'default_status_id'     => $statusIds['New'],
            'default_priority'      => 3,
            'is_active'             => true,
            'created_at'            => now(),
            'updated_at'            => now(),
        ]);

        $this->command->info('✅ TicketingSeeder complete:');
        $this->command->info('   - ' . count($statuses)    . ' ticket statuses');
        $this->command->info('   - ' . count($categories)  . ' ticket categories');
        $this->command->info('   - ' . count($priorities)  . ' priority levels');
        $this->command->info('   - ' . count($slaPolicies) . ' SLA policies');
        $this->command->info('   - ' . count($ticketsData) . ' sample tickets');
        $this->command->info('   - ' . count($assetData)   . ' assets');
        $this->command->info('   - 3 knowledge base solutions');
        $this->command->info('   - 2 content rules + 1 routing rule');
    }
}
