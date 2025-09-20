<?php

namespace Database\Seeders;

use App\Models\Incident;
use App\Models\IncidentComment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class IncidentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create regular users
        $user1 = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        $user2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        $user3 = User::create([
            'name' => 'Bob Johnson',
            'email' => 'bob@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Create sample incidents
        $incidents = [
            [
                'user_id' => $user1->id,
                'title' => 'Suspicious Login Attempts',
                'description' => 'Multiple failed login attempts detected from unknown IP addresses. The attempts were made between 2:00 AM and 4:00 AM from various locations. No successful logins were recorded, but the pattern suggests a potential brute force attack.',
                'severity' => 'high',
                'status' => 'open',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user2->id,
                'title' => 'Data Breach Notification',
                'description' => 'Customer reported receiving an email claiming to be from our company asking for password reset. The email contained suspicious links and appears to be a phishing attempt. Several customers have reported similar emails.',
                'severity' => 'critical',
                'status' => 'in-progress',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user3->id,
                'title' => 'Unauthorized Access to Server',
                'description' => 'System logs show unauthorized access to the development server. The intruder gained access through a misconfigured SSH key. Immediate action was taken to revoke access and secure the server.',
                'severity' => 'critical',
                'status' => 'resolved',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user1->id,
                'title' => 'Malware Detection',
                'description' => 'Antivirus software detected malware on a workstation in the marketing department. The malware appears to be a trojan that was downloaded from a suspicious email attachment.',
                'severity' => 'medium',
                'status' => 'open',
                'assigned_to' => null,
            ],
            [
                'user_id' => $user2->id,
                'title' => 'Website Defacement',
                'description' => 'The company website was defaced with inappropriate content. The attack occurred during off-hours and was discovered by the morning shift. The website has been taken offline for investigation.',
                'severity' => 'high',
                'status' => 'in-progress',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user3->id,
                'title' => 'Lost USB Drive',
                'description' => 'A USB drive containing sensitive customer data was lost during a business trip. The drive was not encrypted and may contain personally identifiable information of approximately 500 customers.',
                'severity' => 'high',
                'status' => 'open',
                'assigned_to' => null,
            ],
            [
                'user_id' => $user1->id,
                'title' => 'Social Engineering Attempt',
                'description' => 'An employee received a phone call from someone claiming to be from IT support asking for their password. The caller was persistent and used technical jargon to appear legitimate.',
                'severity' => 'medium',
                'status' => 'resolved',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user2->id,
                'title' => 'Database Backup Failure',
                'description' => 'Automated database backup failed for the third consecutive day. This could result in data loss if the primary database fails. The backup system needs immediate attention.',
                'severity' => 'medium',
                'status' => 'open',
                'assigned_to' => null,
            ],
        ];

        foreach ($incidents as $incidentData) {
            $incident = Incident::create($incidentData);
            
            // Add some comments to incidents
            if (in_array($incident->id, [1, 2, 3])) {
                IncidentComment::create([
                    'incident_id' => $incident->id,
                    'user_id' => $admin->id,
                    'comment' => 'I\'ve reviewed the logs and can confirm this is a legitimate security concern. I\'ll investigate further and provide updates.',
                ]);
            }
            
            if ($incident->id == 2) {
                IncidentComment::create([
                    'incident_id' => $incident->id,
                    'user_id' => $user2->id,
                    'comment' => 'I\'ve contacted the affected customers and advised them to change their passwords immediately. We should also send out a security advisory.',
                ]);
            }
        }
    }
}
