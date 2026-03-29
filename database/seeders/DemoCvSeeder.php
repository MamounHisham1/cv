<?php

namespace Database\Seeders;

use App\Models\Cv;
use App\Models\CvCertification;
use App\Models\CvEducation;
use App\Models\CvExperience;
use App\Models\CvProject;
use App\Models\CvSkill;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoCvSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create();

        $templates = ['professional-classic', 'technical-ats', 'modern-minimal', 'creative', 'executive'];

        $cvs = [
            [
                'title' => 'Senior Software Engineer',
                'template' => $templates[0],
                'personal_info' => [
                    'first_name' => 'Ahmed',
                    'last_name' => 'Khalil',
                    'email' => 'ahmed.khalil@email.com',
                    'phone' => '+20 100 555 1234',
                    'location' => 'Cairo, Egypt',
                    'linkedin' => 'https://linkedin.com/in/ahmedkhalil',
                    'github' => 'https://github.com/ahmedkhalil',
                    'website' => '',
                ],
                'summary' => 'Senior Software Engineer with 7+ years of experience building scalable web applications and microservices architectures. Proficient in Laravel, Python, and cloud technologies (AWS, GCP). Led teams of up to 8 developers and delivered projects that reduced operational costs by 40%. Passionate about clean code, TDD, and mentoring junior developers.',
                'experiences' => [
                    [
                        'title' => 'Senior Software Engineer',
                        'company' => 'TechVista Solutions',
                        'location' => 'Cairo, Egypt',
                        'start_date' => '2022-03',
                        'end_date' => null,
                        'is_current' => true,
                        'description' => 'Leading backend development for a SaaS platform serving 50K+ users. Architecting microservices with Laravel, Python, and AWS.',
                        'achievements' => ['Reduced API response time by 60% through caching and query optimization', 'Led migration from monolith to microservices architecture', 'Mentored 5 junior developers resulting in 2 promotions within the team'],
                        'aws_services' => json_encode(['Laravel', 'Python', 'AWS', 'Docker', 'Redis', 'MySQL']),
                    ],
                    [
                        'title' => 'Software Engineer',
                        'company' => 'DigitalCraft Agency',
                        'location' => 'Giza, Egypt',
                        'start_date' => '2019-06',
                        'end_date' => '2022-02',
                        'is_current' => false,
                        'description' => 'Full-stack development for enterprise clients in e-commerce and fintech sectors.',
                        'achievements' => ['Built payment processing system handling $2M+ monthly transactions', 'Implemented CI/CD pipeline reducing deployment time from 4 hours to 15 minutes', 'Received "Employee of the Quarter" award twice'],
                        'aws_services' => json_encode(['Laravel', 'Vue.js', 'MySQL', 'Docker', 'Jenkins']),
                    ],
                    [
                        'title' => 'Junior Developer',
                        'company' => 'StartUp Hub',
                        'location' => 'Cairo, Egypt',
                        'start_date' => '2017-01',
                        'end_date' => '2019-05',
                        'is_current' => false,
                        'description' => 'Developed features for a ride-sharing application and internal management tools.',
                        'achievements' => ['Developed real-time tracking feature using WebSockets', 'Contributed to 30% increase in daily active users'],
                        'aws_services' => json_encode(['PHP', 'JavaScript', 'MySQL', 'Redis']),
                    ],
                ],
                'skills' => [
                    ['name' => 'PHP', 'category' => 'programming', 'level' => 'expert'],
                    ['name' => 'Laravel', 'category' => 'programming', 'level' => 'expert'],
                    ['name' => 'Python', 'category' => 'programming', 'level' => 'advanced'],
                    ['name' => 'JavaScript', 'category' => 'programming', 'level' => 'advanced'],
                    ['name' => 'AWS', 'category' => 'cloud', 'level' => 'advanced'],
                    ['name' => 'Docker', 'category' => 'infrastructure', 'level' => 'advanced'],
                    ['name' => 'MySQL', 'category' => 'infrastructure', 'level' => 'expert'],
                    ['name' => 'Redis', 'category' => 'infrastructure', 'level' => 'intermediate'],
                    ['name' => 'Git', 'category' => 'infrastructure', 'level' => 'expert'],
                    ['name' => 'CI/CD', 'category' => 'infrastructure', 'level' => 'advanced'],
                ],
                'projects' => [
                    [
                        'name' => 'E-Commerce Microservices Platform',
                        'description' => 'Designed and built a scalable e-commerce platform using microservices architecture with Laravel, Redis caching, and AWS ECS.',
                        'key_achievements' => ['Handles 10K+ concurrent users', '99.9% uptime', 'Reduced infrastructure costs by 35%'],
                        'github_url' => 'https://github.com/ahmedkhalil/ecommerce-platform',
                    ],
                    [
                        'name' => 'DevOps Dashboard',
                        'description' => 'Real-time monitoring dashboard for CI/CD pipelines, server health, and deployment tracking.',
                        'key_achievements' => ['Used by 3 teams across the organization', 'Reduced incident response time by 50%'],
                        'github_url' => 'https://github.com/ahmedkhalil/devops-dashboard',
                    ],
                ],
                'education' => [
                    [
                        'institution' => 'Cairo University',
                        'degree' => 'Bachelor of Science',
                        'field_of_study' => 'Computer Science',
                        'location' => 'Cairo, Egypt',
                        'start_date' => '2013-09',
                        'end_date' => '2017-06',
                        'is_current' => false,
                        'description' => 'Graduated with honors (GPA: 3.7/4.0). Thesis on microservices performance optimization.',
                    ],
                ],
                'certifications' => [
                    ['name' => 'AWS Certified Solutions Architect - Associate', 'issuing_organization' => 'Amazon Web Services', 'issue_date' => '2023-06'],
                    ['name' => 'Certified Laravel Developer', 'issuing_organization' => 'Laravel LLC', 'issue_date' => '2022-01'],
                ],
            ],
            [
                'title' => 'Cloud & DevOps Engineer',
                'template' => $templates[1],
                'personal_info' => [
                    'first_name' => 'Sara',
                    'last_name' => 'El-Masry',
                    'email' => 'sara.elmasry@email.com',
                    'phone' => '+20 102 444 5678',
                    'location' => 'Alexandria, Egypt',
                    'linkedin' => 'https://linkedin.com/in/saraelmasry',
                    'github' => 'https://github.com/saraelmasry',
                    'website' => 'https://saraelmasry.dev',
                ],
                'summary' => 'Cloud & DevOps Engineer with 5+ years of experience designing and managing cloud infrastructure on AWS and Azure. Skilled in Infrastructure as Code (Terraform, CloudFormation), containerization (Docker, Kubernetes), and automation. Improved deployment frequency by 300% and reduced infrastructure costs by 45% across multiple projects.',
                'experiences' => [
                    [
                        'title' => 'Senior DevOps Engineer',
                        'company' => 'CloudPeak Technologies',
                        'location' => 'Remote',
                        'start_date' => '2023-01',
                        'end_date' => null,
                        'is_current' => true,
                        'description' => 'Managing cloud infrastructure for a fintech company processing $500M+ in transactions. Leading the DevOps team and establishing SRE practices.',
                        'achievements' => ['Achieved 99.99% uptime for critical payment services', 'Reduced infrastructure costs by $200K/year through rightsizing and reserved instances', 'Implemented zero-downtime deployments reducing customer-facing incidents by 80%'],
                        'aws_services' => json_encode(['AWS', 'Kubernetes', 'Terraform', 'Datadog', 'Prometheus']),
                    ],
                    [
                        'title' => 'Cloud Engineer',
                        'company' => 'DataFlow Systems',
                        'location' => 'Alexandria, Egypt',
                        'start_date' => '2020-08',
                        'end_date' => '2022-12',
                        'is_current' => false,
                        'description' => 'Built and maintained cloud infrastructure for data processing pipelines and analytics platforms.',
                        'achievements' => ['Migrated 20+ services from on-premise to AWS', 'Designed disaster recovery plan reducing RPO from 24 hours to 15 minutes', 'Automated infrastructure provisioning with Terraform saving 40 hours/month'],
                        'aws_services' => json_encode(['AWS', 'Terraform', 'Docker', 'Python', 'CloudFormation']),
                    ],
                ],
                'skills' => [
                    ['name' => 'AWS', 'category' => 'cloud', 'level' => 'expert'],
                    ['name' => 'Azure', 'category' => 'cloud', 'level' => 'advanced'],
                    ['name' => 'Kubernetes', 'category' => 'infrastructure', 'level' => 'advanced'],
                    ['name' => 'Docker', 'category' => 'infrastructure', 'level' => 'expert'],
                    ['name' => 'Terraform', 'category' => 'infrastructure', 'level' => 'expert'],
                    ['name' => 'Python', 'category' => 'programming', 'level' => 'advanced'],
                    ['name' => 'CI/CD', 'category' => 'infrastructure', 'level' => 'expert'],
                    ['name' => 'Linux', 'category' => 'infrastructure', 'level' => 'expert'],
                    ['name' => 'Monitoring', 'category' => 'infrastructure', 'level' => 'advanced'],
                    ['name' => 'Bash', 'category' => 'programming', 'level' => 'advanced'],
                ],
                'projects' => [
                    [
                        'name' => 'Multi-Region Infrastructure Automation',
                        'description' => 'Built a Terraform-based infrastructure automation framework supporting multi-region deployments across AWS.',
                        'key_achievements' => ['Reduced provisioning time from 2 days to 30 minutes', 'Used by 5 product teams', 'Open-sourced with 200+ GitHub stars'],
                        'github_url' => 'https://github.com/saraelmasry/infra-automation',
                    ],
                ],
                'education' => [
                    [
                        'institution' => 'Alexandria University',
                        'degree' => 'Bachelor of Science',
                        'field_of_study' => 'Computer Engineering',
                        'location' => 'Alexandria, Egypt',
                        'start_date' => '2016-09',
                        'end_date' => '2020-06',
                        'is_current' => false,
                    ],
                ],
                'certifications' => [
                    ['name' => 'AWS Certified DevOps Engineer - Professional', 'issuing_organization' => 'Amazon Web Services', 'issue_date' => '2023-09'],
                    ['name' => 'Certified Kubernetes Administrator', 'issuing_organization' => 'CNCF', 'issue_date' => '2022-11'],
                    ['name' => 'HashiCorp Terraform Associate', 'issuing_organization' => 'HashiCorp', 'issue_date' => '2022-03'],
                ],
            ],
            [
                'title' => 'Frontend Developer',
                'template' => $templates[2],
                'personal_info' => [
                    'first_name' => 'Omar',
                    'last_name' => 'Fathy',
                    'email' => 'omar.fathy@email.com',
                    'phone' => '+20 115 333 9012',
                    'location' => 'Cairo, Egypt',
                    'linkedin' => 'https://linkedin.com/in/omarfathy',
                    'github' => 'https://github.com/omarfathy',
                    'website' => 'https://omarfathy.com',
                ],
                'summary' => 'Creative Frontend Developer with 4+ years of experience crafting pixel-perfect, responsive web interfaces. Expert in React, Vue.js, and modern CSS. Passionate about accessibility, performance optimization, and creating delightful user experiences. Built design systems used across multiple products serving 100K+ users.',
                'experiences' => [
                    [
                        'title' => 'Frontend Developer',
                        'company' => 'PixelPerfect Studio',
                        'location' => 'Cairo, Egypt',
                        'start_date' => '2022-06',
                        'end_date' => null,
                        'is_current' => true,
                        'description' => 'Building responsive web applications and design systems for clients in healthcare and education sectors.',
                        'achievements' => ['Built a component library with 50+ reusable components', 'Improved Lighthouse performance score from 45 to 95', 'Reduced bundle size by 60% through code splitting and lazy loading'],
                        'aws_services' => json_encode(['React', 'TypeScript', 'Next.js', 'Tailwind CSS', 'Storybook']),
                    ],
                    [
                        'title' => 'Junior Frontend Developer',
                        'company' => 'WebCraft Agency',
                        'location' => 'Giza, Egypt',
                        'start_date' => '2020-03',
                        'end_date' => '2022-05',
                        'is_current' => false,
                        'description' => 'Developed responsive websites and landing pages for marketing campaigns and small businesses.',
                        'achievements' => ['Delivered 20+ client projects on time and within budget', 'Introduced component-based architecture improving development speed by 40%'],
                        'aws_services' => json_encode(['Vue.js', 'JavaScript', 'SCSS', 'Bootstrap']),
                    ],
                ],
                'skills' => [
                    ['name' => 'React', 'category' => 'programming', 'level' => 'expert'],
                    ['name' => 'TypeScript', 'category' => 'programming', 'level' => 'advanced'],
                    ['name' => 'Vue.js', 'category' => 'programming', 'level' => 'advanced'],
                    ['name' => 'Next.js', 'category' => 'programming', 'level' => 'advanced'],
                    ['name' => 'Tailwind CSS', 'category' => 'programming', 'level' => 'expert'],
                    ['name' => 'JavaScript', 'category' => 'programming', 'level' => 'expert'],
                    ['name' => 'HTML/CSS', 'category' => 'programming', 'level' => 'expert'],
                    ['name' => 'Git', 'category' => 'infrastructure', 'level' => 'advanced'],
                    ['name' => 'Figma', 'category' => 'general', 'level' => 'intermediate'],
                    ['name' => 'Accessibility', 'category' => 'general', 'level' => 'advanced'],
                ],
                'projects' => [
                    [
                        'name' => 'React Design System',
                        'description' => 'A comprehensive design system built with React, TypeScript, and Storybook featuring 50+ accessible components.',
                        'key_achievements' => ['Adopted by 3 product teams', 'Reduced UI development time by 50%'],
                        'github_url' => 'https://github.com/omarfathy/design-system',
                        'project_url' => 'https://design-system.omarfathy.com',
                    ],
                    [
                        'name' => 'E-Commerce Storefront',
                        'description' => 'High-performance e-commerce storefront built with Next.js and Stripe integration.',
                        'key_achievements' => ['95+ Lighthouse score', 'Sub-second page loads', 'Handles 5K+ daily visitors'],
                        'github_url' => 'https://github.com/omarfathy/storefront',
                    ],
                ],
                'education' => [
                    [
                        'institution' => 'Cairo University',
                        'degree' => 'Bachelor of Science',
                        'field_of_study' => 'Information Systems',
                        'location' => 'Cairo, Egypt',
                        'start_date' => '2016-09',
                        'end_date' => '2020-06',
                        'is_current' => false,
                    ],
                ],
                'certifications' => [
                    ['name' => 'Google Professional Cloud Developer', 'issuing_organization' => 'Google Cloud', 'issue_date' => '2023-03'],
                    ['name' => 'Meta Frontend Developer Professional Certificate', 'issuing_organization' => 'Meta', 'issue_date' => '2022-08'],
                ],
            ],
            [
                'title' => 'Data Scientist',
                'template' => $templates[3],
                'personal_info' => [
                    'first_name' => 'Nour',
                    'last_name' => 'Hassan',
                    'email' => 'nour.hassan@email.com',
                    'phone' => '+20 109 222 3456',
                    'location' => 'Cairo, Egypt',
                    'linkedin' => 'https://linkedin.com/in/nourhassan',
                    'github' => 'https://github.com/nourhassan',
                    'website' => '',
                ],
                'summary' => 'Data Scientist with 4+ years of experience in machine learning, statistical analysis, and data visualization. Built predictive models that improved customer retention by 25% and revenue forecasting accuracy by 30%. Experienced with Python, SQL, TensorFlow, and big data tools. Strong communicator who translates complex data insights into actionable business strategies.',
                'experiences' => [
                    [
                        'title' => 'Data Scientist',
                        'company' => 'InsightAI Analytics',
                        'location' => 'Cairo, Egypt',
                        'start_date' => '2022-09',
                        'end_date' => null,
                        'is_current' => true,
                        'description' => 'Developing machine learning models for customer analytics, churn prediction, and recommendation systems.',
                        'achievements' => ['Built churn prediction model with 89% accuracy saving $1.2M annually', 'Developed recommendation engine increasing cross-sell revenue by 18%', 'Automated reporting pipeline saving 20 hours/week of manual analysis'],
                        'aws_services' => json_encode(['Python', 'TensorFlow', 'SQL', 'Apache Spark', 'Tableau']),
                    ],
                    [
                        'title' => 'Junior Data Analyst',
                        'company' => 'MarketPulse Research',
                        'location' => 'Giza, Egypt',
                        'start_date' => '2020-07',
                        'end_date' => '2022-08',
                        'is_current' => false,
                        'description' => 'Conducted market research analysis and built dashboards for executive decision-making.',
                        'achievements' => ['Created interactive dashboards used by C-suite executives', 'Identified market trends leading to 2 new product launches', 'Processed and analyzed datasets of 10M+ records'],
                        'aws_services' => json_encode(['Python', 'SQL', 'Pandas', 'Tableau', 'Excel']),
                    ],
                ],
                'skills' => [
                    ['name' => 'Python', 'category' => 'programming', 'level' => 'expert'],
                    ['name' => 'Machine Learning', 'category' => 'data', 'level' => 'advanced'],
                    ['name' => 'SQL', 'category' => 'programming', 'level' => 'expert'],
                    ['name' => 'TensorFlow', 'category' => 'data', 'level' => 'advanced'],
                    ['name' => 'Pandas', 'category' => 'data', 'level' => 'expert'],
                    ['name' => 'Apache Spark', 'category' => 'data', 'level' => 'intermediate'],
                    ['name' => 'Tableau', 'category' => 'data', 'level' => 'advanced'],
                    ['name' => 'Statistics', 'category' => 'data', 'level' => 'advanced'],
                    ['name' => 'Deep Learning', 'category' => 'data', 'level' => 'intermediate'],
                    ['name' => 'Git', 'category' => 'infrastructure', 'level' => 'intermediate'],
                ],
                'projects' => [
                    [
                        'name' => 'Customer Churn Prediction System',
                        'description' => 'End-to-end ML pipeline for predicting customer churn using gradient boosting and feature engineering.',
                        'key_achievements' => ['89% prediction accuracy', 'Reduced churn rate by 15% through targeted interventions'],
                        'github_url' => 'https://github.com/nourhassan/churn-prediction',
                    ],
                    [
                        'name' => 'Real-Time Analytics Dashboard',
                        'description' => 'Dashboard processing streaming data for real-time business metrics visualization.',
                        'key_achievements' => ['Processes 100K events/minute', 'Sub-5-second data refresh latency'],
                        'github_url' => 'https://github.com/nourhassan/realtime-analytics',
                    ],
                ],
                'education' => [
                    [
                        'institution' => 'American University in Cairo',
                        'degree' => 'Master of Science',
                        'field_of_study' => 'Data Science',
                        'location' => 'Cairo, Egypt',
                        'start_date' => '2018-09',
                        'end_date' => '2020-06',
                        'is_current' => false,
                        'description' => 'Thesis on "Deep Learning Approaches for Customer Behavior Prediction." GPA: 3.8/4.0.',
                    ],
                    [
                        'institution' => 'Cairo University',
                        'degree' => 'Bachelor of Science',
                        'field_of_study' => 'Mathematics',
                        'location' => 'Cairo, Egypt',
                        'start_date' => '2014-09',
                        'end_date' => '2018-06',
                        'is_current' => false,
                    ],
                ],
                'certifications' => [
                    ['name' => 'Google Data Analytics Professional Certificate', 'issuing_organization' => 'Google', 'issue_date' => '2022-01'],
                    ['name' => 'TensorFlow Developer Certificate', 'issuing_organization' => 'Google', 'issue_date' => '2023-05'],
                ],
            ],
            [
                'title' => 'Product Manager',
                'template' => $templates[4],
                'personal_info' => [
                    'first_name' => 'Yasmine',
                    'last_name' => 'Abdel-Rahim',
                    'email' => 'yasmine.pm@email.com',
                    'phone' => '+20 101 666 7890',
                    'location' => 'Cairo, Egypt',
                    'linkedin' => 'https://linkedin.com/in/yasminepm',
                    'github' => '',
                    'website' => 'https://yasminepm.com',
                ],
                'summary' => 'Strategic Product Manager with 6+ years of experience leading cross-functional teams to deliver customer-centric products from 0 to 1. Track record of launching products that generated $5M+ in revenue. Expert in agile methodologies, user research, data-driven decision making, and stakeholder management. Led teams of 5-15 across engineering, design, and marketing.',
                'experiences' => [
                    [
                        'title' => 'Senior Product Manager',
                        'company' => 'FinEdge Technologies',
                        'location' => 'Cairo, Egypt',
                        'start_date' => '2023-02',
                        'end_date' => null,
                        'is_current' => true,
                        'description' => 'Leading product strategy for a B2B fintech platform serving 500+ enterprise clients. Managing a team of 12 across engineering and design.',
                        'achievements' => ['Launched 3 new product features generating $3M ARR', 'Increased customer NPS from 32 to 67', 'Reduced time-to-market by 40% through process improvements'],
                        'aws_services' => json_encode(['Jira', 'Figma', 'Mixpanel', 'SQL', 'Notion']),
                    ],
                    [
                        'title' => 'Product Manager',
                        'company' => 'HealthTech Solutions',
                        'location' => 'Cairo, Egypt',
                        'start_date' => '2020-06',
                        'end_date' => '2023-01',
                        'is_current' => false,
                        'description' => 'Managed a telemedicine platform connecting patients with doctors across the MENA region.',
                        'achievements' => ['Grew user base from 10K to 200K in 18 months', 'Led pivot from B2C to B2B model increasing revenue by 250%', 'Established product-led growth strategy reducing CAC by 35%'],
                        'aws_services' => json_encode(['Amplitude', 'Figma', 'Jira', 'Google Analytics']),
                    ],
                    [
                        'title' => 'Associate Product Manager',
                        'company' => 'RideNow',
                        'location' => 'Giza, Egypt',
                        'start_date' => '2018-01',
                        'end_date' => '2020-05',
                        'is_current' => false,
                        'description' => 'Managed rider experience features including booking flow, ratings, and safety features.',
                        'achievements' => ['Improved booking completion rate by 20%', 'Launched safety feature reducing incidents by 45%', 'Conducted 100+ user interviews informing product roadmap'],
                        'aws_services' => json_encode(['Mixpanel', 'Jira', 'Hotjar']),
                    ],
                ],
                'skills' => [
                    ['name' => 'Product Strategy', 'category' => 'general', 'level' => 'expert'],
                    ['name' => 'Agile/Scrum', 'category' => 'general', 'level' => 'expert'],
                    ['name' => 'User Research', 'category' => 'general', 'level' => 'advanced'],
                    ['name' => 'Data Analysis', 'category' => 'data', 'level' => 'advanced'],
                    ['name' => 'Stakeholder Management', 'category' => 'soft', 'level' => 'expert'],
                    ['name' => 'Roadmap Planning', 'category' => 'general', 'level' => 'expert'],
                    ['name' => 'A/B Testing', 'category' => 'data', 'level' => 'advanced'],
                    ['name' => 'SQL', 'category' => 'programming', 'level' => 'intermediate'],
                    ['name' => 'Figma', 'category' => 'general', 'level' => 'intermediate'],
                    ['name' => 'Team Leadership', 'category' => 'soft', 'level' => 'expert'],
                ],
                'projects' => [
                    [
                        'name' => 'Enterprise Dashboard Launch',
                        'description' => 'Led end-to-end launch of an analytics dashboard for enterprise clients, from discovery to go-to-market.',
                        'key_achievements' => ['Acquired 50 enterprise clients in first quarter', '$1.5M ARR in first year', 'Featured in G2 "High Performer" category'],
                    ],
                    [
                        'name' => 'Mobile App Redesign',
                        'description' => 'Spearheaded complete redesign of a health-tech mobile app, improving usability and engagement.',
                        'key_achievements' => ['App store rating increased from 3.2 to 4.7', '30% increase in daily active users', '50% reduction in customer support tickets'],
                    ],
                ],
                'education' => [
                    [
                        'institution' => 'American University in Cairo',
                        'degree' => 'Master of Business Administration',
                        'field_of_study' => 'Marketing & Strategy',
                        'location' => 'Cairo, Egypt',
                        'start_date' => '2016-09',
                        'end_date' => '2018-06',
                        'is_current' => false,
                    ],
                    [
                        'institution' => 'Cairo University',
                        'degree' => 'Bachelor of Business Administration',
                        'field_of_study' => 'Management',
                        'location' => 'Cairo, Egypt',
                        'start_date' => '2012-09',
                        'end_date' => '2016-06',
                        'is_current' => false,
                    ],
                ],
                'certifications' => [
                    ['name' => 'Certified Scrum Product Owner (CSPO)', 'issuing_organization' => 'Scrum Alliance', 'issue_date' => '2021-06'],
                    ['name' => 'Pragmatic Institute Certified - PMC Level III', 'issuing_organization' => 'Pragmatic Institute', 'issue_date' => '2022-09'],
                    ['name' => 'Google Analytics Certification', 'issuing_organization' => 'Google', 'issue_date' => '2020-03'],
                ],
            ],
        ];

        foreach ($cvs as $cvData) {
            $cv = Cv::create([
                'user_id' => $user->id,
                'title' => $cvData['title'],
                'template_id' => $cvData['template'],
                'personal_info' => $cvData['personal_info'],
                'summary' => $cvData['summary'],
                'status' => 'draft',
            ]);

            foreach ($cvData['experiences'] as $i => $exp) {
                CvExperience::create(array_merge($exp, ['cv_id' => $cv->id, 'sort_order' => $i + 1]));
            }

            foreach ($cvData['skills'] as $i => $skill) {
                CvSkill::create(array_merge($skill, ['cv_id' => $cv->id, 'sort_order' => $i + 1]));
            }

            foreach ($cvData['projects'] as $i => $project) {
                CvProject::create(array_merge($project, ['cv_id' => $cv->id, 'sort_order' => $i + 1]));
            }

            foreach ($cvData['education'] as $i => $edu) {
                CvEducation::create(array_merge($edu, ['cv_id' => $cv->id, 'sort_order' => $i + 1]));
            }

            foreach ($cvData['certifications'] as $i => $cert) {
                CvCertification::create(array_merge($cert, ['cv_id' => $cv->id, 'sort_order' => $i + 1]));
            }
        }
    }
}
