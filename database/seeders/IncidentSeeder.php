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

        // Create additional users for more variety
        $users = collect([$user1, $user2, $user3, $admin]);
        
        // Create more users
        for ($i = 4; $i <= 15; $i++) {
            $users->push(User::create([
                'name' => "User $i",
                'email' => "user$i@example.com",
                'password' => Hash::make('password'),
                'role' => 'user',
            ]));
        }

        // Create sample incidents (70+ incidents)
        $incidents = [
            // Critical incidents
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
                'title' => 'Ransomware Attack Detected',
                'description' => 'Ransomware has been detected on multiple workstations in the finance department. Files are being encrypted and ransom notes are appearing. Immediate isolation and response required.',
                'severity' => 'critical',
                'status' => 'open',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user2->id,
                'title' => 'Customer Database Compromised',
                'description' => 'Evidence suggests that the customer database has been compromised. Personal information including names, emails, and payment details may have been accessed by unauthorized parties.',
                'severity' => 'critical',
                'status' => 'in-progress',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user3->id,
                'title' => 'Production Server Down',
                'description' => 'Main production server is completely down due to a suspected DDoS attack. All customer-facing services are offline. Emergency response team activated.',
                'severity' => 'critical',
                'status' => 'open',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user1->id,
                'title' => 'Insider Threat Detected',
                'description' => 'Employee has been caught attempting to download sensitive company data to personal devices. Investigation reveals potential data exfiltration over several weeks.',
                'severity' => 'critical',
                'status' => 'in-progress',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user2->id,
                'title' => 'Zero-Day Exploit in Use',
                'description' => 'Security researchers have identified a zero-day exploit being used against our systems. The vulnerability affects our web application and allows remote code execution.',
                'severity' => 'critical',
                'status' => 'open',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user3->id,
                'title' => 'Supply Chain Attack',
                'description' => 'Third-party software vendor has been compromised and malicious code has been distributed through their update mechanism. Our systems may be affected.',
                'severity' => 'critical',
                'status' => 'in-progress',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user1->id,
                'title' => 'Advanced Persistent Threat',
                'description' => 'Long-term sophisticated attack campaign detected. Attackers have been in the network for months, slowly escalating privileges and gathering intelligence.',
                'severity' => 'critical',
                'status' => 'open',
                'assigned_to' => $admin->id,
            ],
            
            // High severity incidents
            [
                'user_id' => $user2->id,
                'title' => 'Malware Detection',
                'description' => 'Antivirus software detected malware on a workstation in the marketing department. The malware appears to be a trojan that was downloaded from a suspicious email attachment.',
                'severity' => 'high',
                'status' => 'open',
                'assigned_to' => null,
            ],
            [
                'user_id' => $user3->id,
                'title' => 'Website Defacement',
                'description' => 'The company website was defaced with inappropriate content. The attack occurred during off-hours and was discovered by the morning shift. The website has been taken offline for investigation.',
                'severity' => 'high',
                'status' => 'in-progress',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user1->id,
                'title' => 'Lost USB Drive',
                'description' => 'A USB drive containing sensitive customer data was lost during a business trip. The drive was not encrypted and may contain personally identifiable information of approximately 500 customers.',
                'severity' => 'high',
                'status' => 'open',
                'assigned_to' => null,
            ],
            [
                'user_id' => $user2->id,
                'title' => 'Email Server Compromise',
                'description' => 'Email server has been compromised and is being used to send spam and phishing emails. Customer complaints are increasing and our domain reputation is at risk.',
                'severity' => 'high',
                'status' => 'in-progress',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user3->id,
                'title' => 'Privilege Escalation Attack',
                'description' => 'Unauthorized user has successfully escalated privileges from standard user to administrator level. Investigation shows they gained access through a vulnerable service.',
                'severity' => 'high',
                'status' => 'open',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user1->id,
                'title' => 'Network Intrusion Detected',
                'description' => 'Intrusion detection system has flagged multiple suspicious network activities. Unauthorized access attempts and data exfiltration patterns have been observed.',
                'severity' => 'high',
                'status' => 'in-progress',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user2->id,
                'title' => 'API Key Compromise',
                'description' => 'API keys for third-party services have been compromised and are being used by unauthorized parties. Immediate rotation and monitoring required.',
                'severity' => 'high',
                'status' => 'open',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user3->id,
                'title' => 'Cloud Storage Breach',
                'description' => 'Unauthorized access to cloud storage containing sensitive documents. Investigation shows potential data download and sharing with external parties.',
                'severity' => 'high',
                'status' => 'in-progress',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user1->id,
                'title' => 'Mobile Device Theft',
                'description' => 'Company mobile device containing sensitive business data was stolen from an employee during a business trip. Device was not properly secured with encryption.',
                'severity' => 'high',
                'status' => 'open',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user2->id,
                'title' => 'Social Media Account Hijacking',
                'description' => 'Official company social media accounts have been hijacked and are posting inappropriate content. Brand reputation is at risk and customer confusion is increasing.',
                'severity' => 'high',
                'status' => 'in-progress',
                'assigned_to' => $admin->id,
            ],
            
            // Medium severity incidents
            [
                'user_id' => $user3->id,
                'title' => 'Social Engineering Attempt',
                'description' => 'An employee received a phone call from someone claiming to be from IT support asking for their password. The caller was persistent and used technical jargon to appear legitimate.',
                'severity' => 'medium',
                'status' => 'resolved',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user1->id,
                'title' => 'Database Backup Failure',
                'description' => 'Automated database backup failed for the third consecutive day. This could result in data loss if the primary database fails. The backup system needs immediate attention.',
                'severity' => 'medium',
                'status' => 'open',
                'assigned_to' => null,
            ],
            [
                'user_id' => $user2->id,
                'title' => 'Suspicious Email Activity',
                'description' => 'Multiple employees have received suspicious emails with malicious attachments. While no infections were detected, the pattern suggests a targeted phishing campaign.',
                'severity' => 'medium',
                'status' => 'in-progress',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user3->id,
                'title' => 'Weak Password Policy Violation',
                'description' => 'Security audit revealed that several employees are using weak passwords that do not meet company policy. This creates a significant security risk.',
                'severity' => 'medium',
                'status' => 'open',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user1->id,
                'title' => 'Unpatched Software Vulnerability',
                'description' => 'Critical security patches have not been applied to several servers. The unpatched vulnerabilities could be exploited by attackers.',
                'severity' => 'medium',
                'status' => 'in-progress',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user2->id,
                'title' => 'Unauthorized Software Installation',
                'description' => 'Employee installed unauthorized software on company computer without IT approval. The software may contain security vulnerabilities or malicious code.',
                'severity' => 'medium',
                'status' => 'resolved',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user3->id,
                'title' => 'Data Classification Error',
                'description' => 'Sensitive data was incorrectly classified and stored in an unsecured location accessible to all employees. Data has been moved but audit trail is needed.',
                'severity' => 'medium',
                'status' => 'open',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user1->id,
                'title' => 'VPN Connection Issues',
                'description' => 'Multiple employees are experiencing VPN connection problems. Some are connecting through unsecured networks as a workaround, creating security risks.',
                'severity' => 'medium',
                'status' => 'in-progress',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user2->id,
                'title' => 'Outdated Security Certificates',
                'description' => 'SSL certificates for several internal services have expired. This could lead to man-in-the-middle attacks and data interception.',
                'severity' => 'medium',
                'status' => 'open',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user3->id,
                'title' => 'Insufficient Access Controls',
                'description' => 'Security review found that some employees have excessive access permissions beyond their job requirements. Access should be reviewed and reduced.',
                'severity' => 'medium',
                'status' => 'resolved',
                'assigned_to' => $admin->id,
            ],
            
            // Low severity incidents
            [
                'user_id' => $user1->id,
                'title' => 'Minor Website Defacement',
                'description' => 'Small section of the company website was defaced with graffiti. No sensitive data was affected, but the incident needs to be documented.',
                'severity' => 'low',
                'status' => 'resolved',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user2->id,
                'title' => 'Suspicious Network Traffic',
                'description' => 'Network monitoring detected unusual traffic patterns that may indicate reconnaissance activities. No actual breach detected but monitoring increased.',
                'severity' => 'low',
                'status' => 'open',
                'assigned_to' => null,
            ],
            [
                'user_id' => $user3->id,
                'title' => 'Employee Policy Violation',
                'description' => 'Employee was caught sharing login credentials with a colleague, violating company security policy. Training and disciplinary action required.',
                'severity' => 'low',
                'status' => 'resolved',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user1->id,
                'title' => 'Minor Data Leak',
                'description' => 'Small amount of non-sensitive data was accidentally shared in a public forum. Data has been removed but incident needs to be documented.',
                'severity' => 'low',
                'status' => 'resolved',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user2->id,
                'title' => 'Outdated Documentation',
                'description' => 'Security procedures documentation is outdated and does not reflect current practices. This could lead to confusion during incident response.',
                'severity' => 'low',
                'status' => 'open',
                'assigned_to' => null,
            ],
            [
                'user_id' => $user3->id,
                'title' => 'Minor System Glitch',
                'description' => 'Temporary system glitch caused some security logs to be incomplete for a 2-hour period. No security impact but logging needs improvement.',
                'severity' => 'low',
                'status' => 'resolved',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user1->id,
                'title' => 'Unused Account Cleanup',
                'description' => 'Security audit identified several unused user accounts that should be disabled or removed to reduce attack surface.',
                'severity' => 'low',
                'status' => 'open',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user2->id,
                'title' => 'Minor Configuration Error',
                'description' => 'Minor misconfiguration in firewall rules was discovered. No security impact but configuration should be corrected.',
                'severity' => 'low',
                'status' => 'resolved',
                'assigned_to' => $admin->id,
            ],
            [
                'user_id' => $user3->id,
                'title' => 'Security Training Overdue',
                'description' => 'Several employees have not completed mandatory security training. This creates compliance and awareness issues.',
                'severity' => 'low',
                'status' => 'open',
                'assigned_to' => null,
            ],
            [
                'user_id' => $user1->id,
                'title' => 'Minor Software Bug',
                'description' => 'Minor bug in security software is causing false positive alerts. No security impact but needs to be fixed to reduce alert fatigue.',
                'severity' => 'low',
                'status' => 'in-progress',
                'assigned_to' => $admin->id,
            ],
        ];

        // Generate additional random incidents to reach 70+
        $additionalIncidents = [];
        $incidentTemplates = [
            ['title' => 'Suspicious Network Activity', 'severity' => 'medium', 'status' => 'open'],
            ['title' => 'Failed Authentication Attempts', 'severity' => 'low', 'status' => 'resolved'],
            ['title' => 'Security Policy Violation', 'severity' => 'medium', 'status' => 'in-progress'],
            ['title' => 'System Performance Issue', 'severity' => 'low', 'status' => 'open'],
            ['title' => 'Data Backup Verification Failed', 'severity' => 'medium', 'status' => 'open'],
            ['title' => 'Unauthorized Access Attempt', 'severity' => 'high', 'status' => 'resolved'],
            ['title' => 'Security Tool Malfunction', 'severity' => 'low', 'status' => 'in-progress'],
            ['title' => 'Employee Security Training Required', 'severity' => 'low', 'status' => 'open'],
            ['title' => 'Network Configuration Change', 'severity' => 'medium', 'status' => 'resolved'],
            ['title' => 'Suspicious File Upload', 'severity' => 'high', 'status' => 'open'],
        ];

        for ($i = 0; $i < 40; $i++) {
            $template = $incidentTemplates[array_rand($incidentTemplates)];
            $user = $users->random();
            $assignedTo = (rand(0, 1) ? $admin->id : null);
            
            $additionalIncidents[] = [
                'user_id' => $user->id,
                'title' => $template['title'] . ' #' . ($i + 1),
                'description' => 'This is a generated incident for testing purposes. ' . $template['title'] . ' has been detected and requires attention. The incident was automatically created to populate the system with sample data for demonstration and testing.',
                'severity' => $template['severity'],
                'status' => $template['status'],
                'assigned_to' => $assignedTo,
            ];
        }

        $allIncidents = array_merge($incidents, $additionalIncidents);

        foreach ($allIncidents as $incidentData) {
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
