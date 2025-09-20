<?php

namespace Database\Seeders;

use App\Models\Incident;
use App\Models\IncidentComment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdditionalIncidentsSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing users
        $users = User::all();
        $admin = $users->where('role', 'admin')->first();
        $regularUsers = $users->where('role', 'user');

        if ($regularUsers->isEmpty()) {
            $this->command->warn('No regular users found. Please run the main seeder first.');
            return;
        }

        // Create additional incidents for pagination testing
        $additionalIncidents = [
            [
                'user_id' => $regularUsers->first()->id,
                'title' => 'Network Connectivity Issues',
                'description' => 'Users reporting intermittent network connectivity problems in the main office building. Affecting productivity and causing frustration among staff.',
                'severity' => 'high',
                'status' => 'open',
                'assigned_to' => null,
            ],
            [
                'user_id' => $regularUsers->skip(1)->first()->id ?? $regularUsers->first()->id,
                'title' => 'Email Server Performance Degradation',
                'description' => 'Email server response times have increased significantly over the past week. Users experiencing delays in sending and receiving emails.',
                'severity' => 'medium',
                'status' => 'in-progress',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $regularUsers->first()->id,
                'title' => 'Database Backup Failure',
                'description' => 'Scheduled database backup failed for the third consecutive day. Need immediate investigation to prevent data loss.',
                'severity' => 'critical',
                'status' => 'open',
                'assigned_to' => null,
            ],
            [
                'user_id' => $regularUsers->skip(1)->first()->id ?? $regularUsers->first()->id,
                'title' => 'Printer Configuration Error',
                'description' => 'New printer installation causing configuration conflicts with existing network printers. Users unable to print to specific departments.',
                'severity' => 'low',
                'status' => 'resolved',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $regularUsers->first()->id,
                'title' => 'Security Certificate Expiration Warning',
                'description' => 'SSL certificates for several domains will expire in 30 days. Need to renew certificates to prevent service disruptions.',
                'severity' => 'high',
                'status' => 'open',
                'assigned_to' => null,
            ],
            [
                'user_id' => $regularUsers->skip(1)->first()->id ?? $regularUsers->first()->id,
                'title' => 'Mobile App Login Issues',
                'description' => 'Mobile application users reporting login failures and authentication errors. Affecting remote workers and field staff.',
                'severity' => 'medium',
                'status' => 'in-progress',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $regularUsers->first()->id,
                'title' => 'Server Disk Space Critical',
                'description' => 'Main application server disk space is at 95% capacity. Immediate cleanup or expansion required to prevent system failure.',
                'severity' => 'critical',
                'status' => 'open',
                'assigned_to' => null,
            ],
            [
                'user_id' => $regularUsers->skip(1)->first()->id ?? $regularUsers->first()->id,
                'title' => 'VPN Connection Timeout',
                'description' => 'VPN server experiencing frequent timeouts during peak hours. Remote workers unable to maintain stable connections.',
                'severity' => 'medium',
                'status' => 'resolved',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $regularUsers->first()->id,
                'title' => 'File Upload Size Limit Error',
                'description' => 'Users unable to upload files larger than 10MB due to server configuration limits. Business documents exceed this limit.',
                'severity' => 'low',
                'status' => 'open',
                'assigned_to' => null,
            ],
            [
                'user_id' => $regularUsers->skip(1)->first()->id ?? $regularUsers->first()->id,
                'title' => 'Database Query Performance Issue',
                'description' => 'Customer portal experiencing slow response times due to inefficient database queries. Reports taking 30+ seconds to load.',
                'severity' => 'high',
                'status' => 'in-progress',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $regularUsers->first()->id,
                'title' => 'Backup Storage Full',
                'description' => 'Backup storage server has reached capacity. New backups are failing and old backups may be overwritten.',
                'severity' => 'critical',
                'status' => 'open',
                'assigned_to' => null,
            ],
            [
                'user_id' => $regularUsers->skip(1)->first()->id ?? $regularUsers->first()->id,
                'title' => 'User Interface Responsiveness',
                'description' => 'Web application interface becoming unresponsive during peak usage hours. Users experiencing lag and timeouts.',
                'severity' => 'medium',
                'status' => 'resolved',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $regularUsers->first()->id,
                'title' => 'API Rate Limiting Issues',
                'description' => 'Third-party API integrations hitting rate limits more frequently. Causing data synchronization failures.',
                'severity' => 'low',
                'status' => 'open',
                'assigned_to' => null,
            ],
            [
                'user_id' => $regularUsers->skip(1)->first()->id ?? $regularUsers->first()->id,
                'title' => 'Database Connection Pool Exhaustion',
                'description' => 'Application running out of database connections during high traffic periods. Causing service unavailability.',
                'severity' => 'high',
                'status' => 'in-progress',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $regularUsers->first()->id,
                'title' => 'Log File Rotation Failure',
                'description' => 'Log rotation script failing to execute properly. Log files growing too large and consuming disk space.',
                'severity' => 'low',
                'status' => 'resolved',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $regularUsers->skip(1)->first()->id ?? $regularUsers->first()->id,
                'title' => 'Memory Leak in Application',
                'description' => 'Application showing signs of memory leak. Server memory usage increasing over time without corresponding load increase.',
                'severity' => 'high',
                'status' => 'open',
                'assigned_to' => null,
            ],
            [
                'user_id' => $regularUsers->first()->id,
                'title' => 'CDN Configuration Error',
                'description' => 'Content Delivery Network misconfiguration causing static assets to load from origin server instead of CDN.',
                'severity' => 'medium',
                'status' => 'open',
                'assigned_to' => null,
            ],
            [
                'user_id' => $regularUsers->skip(1)->first()->id ?? $regularUsers->first()->id,
                'title' => 'Database Index Corruption',
                'description' => 'Database indexes showing signs of corruption. Query performance degraded and some queries returning incorrect results.',
                'severity' => 'critical',
                'status' => 'in-progress',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $regularUsers->first()->id,
                'title' => 'Load Balancer Health Check Failure',
                'description' => 'Load balancer health checks failing intermittently. Causing traffic to be routed to unhealthy servers.',
                'severity' => 'high',
                'status' => 'open',
                'assigned_to' => null,
            ],
            [
                'user_id' => $regularUsers->skip(1)->first()->id ?? $regularUsers->first()->id,
                'title' => 'Cache Invalidation Issues',
                'description' => 'Application cache not invalidating properly after data updates. Users seeing stale data in the interface.',
                'severity' => 'medium',
                'status' => 'resolved',
                'assigned_to' => $admin->id,
            ],
        ];

        $this->command->info('Creating additional incidents for pagination testing...');

        foreach ($additionalIncidents as $incidentData) {
            $incident = Incident::create($incidentData);
            
            // Add some comments to random incidents
            if (rand(0, 1)) {
                $commentCount = rand(1, 3);
                for ($i = 0; $i < $commentCount; $i++) {
                    $commenter = $regularUsers->random();
                    IncidentComment::create([
                        'incident_id' => $incident->id,
                        'user_id' => $commenter->id,
                        'comment' => $this->getRandomComment(),
                    ]);
                }
            }
        }

        $this->command->info('Successfully created ' . count($additionalIncidents) . ' additional incidents!');
    }

    private function getRandomComment(): string
    {
        $comments = [
            'This issue has been reported by multiple users. Need immediate attention.',
            'I\'ve been experiencing this problem for the past few days. Very frustrating.',
            'Can we get an update on the resolution timeline?',
            'I found a workaround that might help other users temporarily.',
            'This is affecting our daily operations significantly.',
            'Has anyone else noticed this issue in their department?',
            'The problem seems to be getting worse over time.',
            'I\'ve tried the suggested solutions but none worked.',
            'This is a critical issue that needs to be escalated.',
            'Thanks for the quick response on this matter.',
            'I can provide more details if needed for troubleshooting.',
            'The issue appears to be intermittent in nature.',
            'This is blocking my work progress completely.',
            'I\'ve documented the steps to reproduce this issue.',
            'Can we schedule a meeting to discuss this further?',
        ];

        return $comments[array_rand($comments)];
    }
}
