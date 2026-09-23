<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profil;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== ADMIN =====
        User::create([
            'name' => 'Admin',
            'email' => 'admin@banqueprofils.sn',
            'password' => bcrypt('Admin2026!'),
            'role' => 'admin',
        ]);

        // ===== PROFILS DE TEST =====
        $profils = [
            [
                'nom_affiche' => 'Aminata Sow',
                'niveau' => 'Master',
                'type_recherche' => 'CDI',
                'domaine' => 'Finance & Comptabilité',
                'region' => 'Dakar',
                'description' => 'Titulaire d\'un Master en Finance d\'entreprise, je suis passionnée par l\'analyse financière et le contrôle de gestion. 3 ans d\'expérience en cabinet d\'audit. Maîtrise de SAP, Excel avancé et Power BI.',
                'email_contact' => 'aminata.sow@email.com',
                'whatsapp' => '221771234567',
                'status' => 'publie',
            ],
            [
                'nom_affiche' => 'Moussa Ndiaye',
                'niveau' => 'Licence',
                'type_recherche' => 'Stage,CDD',
                'domaine' => 'Développement Web',
                'region' => 'Thiès',
                'description' => 'Développeur frontend passionné, spécialisé en Angular et React. Je recherche un stage ou un CDD pour approfondir mes compétences en environnement professionnel. Portfolio disponible sur demande.',
                'email_contact' => 'moussa.ndiaye@email.com',
                'whatsapp' => '221776543210',
                'status' => 'publie',
            ],
            [
                'nom_affiche' => 'Fatou Diallo',
                'niveau' => 'BTS',
                'type_recherche' => 'Alternance',
                'domaine' => 'Communication Digitale',
                'region' => 'Saint-Louis',
                'description' => 'Créative et rigoureuse, je suis en BTS Communication et je recherche une alternance pour mettre en pratique mes compétences en community management, création de contenu et stratégie digitale.',
                'email_contact' => null,
                'whatsapp' => '221709876543',
                'status' => 'publie',
            ],
            [
                'nom_affiche' => 'Ibrahima Fall',
                'niveau' => 'Master',
                'type_recherche' => 'CDI,CDD',
                'domaine' => 'Génie Civil',
                'region' => 'Dakar',
                'description' => 'Ingénieur Génie Civil avec un Master de l\'ESP. Expérience de 2 ans sur des chantiers de construction au Sénégal. Compétences en AutoCAD, calcul de structures et suivi de chantier.',
                'email_contact' => 'ibrahima.fall@email.com',
                'whatsapp' => '221778901234',
                'status' => 'publie',
            ],
            [
                'nom_affiche' => 'Aïssatou Ba',
                'niveau' => 'Licence',
                'type_recherche' => 'Stage',
                'domaine' => 'Ressources Humaines',
                'region' => 'Ziguinchor',
                'description' => 'Étudiante en Gestion des Ressources Humaines, je recherche un stage de fin d\'études. Je suis motivée, organisée et prête à m\'investir dans une équipe RH dynamique.',
                'email_contact' => 'aissatou.ba@email.com',
                'whatsapp' => null,
                'status' => 'publie',
            ],
            [
                'nom_affiche' => 'Ousmane Diop',
                'niveau' => 'Bac',
                'type_recherche' => 'Stage,Alternance',
                'domaine' => 'Électricité & Maintenance',
                'region' => 'Kaolack',
                'description' => 'Bachelier en Sciences Techniques, je suis passionné par l\'électricité industrielle et la maintenance. Je cherche un stage ou une alternance pour acquérir une première expérience terrain.',
                'email_contact' => null,
                'whatsapp' => '221704567890',
                'status' => 'publie',
            ],
            [
                'nom_affiche' => 'Mame Diarra Niang',
                'niveau' => 'Master',
                'type_recherche' => 'CDI',
                'domaine' => 'Marketing Digital',
                'region' => 'Dakar',
                'description' => 'Spécialiste en marketing digital avec 4 ans d\'expérience. Expertise en SEO/SEA, Google Ads, Meta Ads et stratégie de contenu. Certifiée Google Analytics et HubSpot.',
                'email_contact' => 'mame.niang@email.com',
                'whatsapp' => '221775551234',
                'status' => 'publie',
            ],
            [
                'nom_affiche' => 'Cheikh Tidiane Mbaye',
                'niveau' => 'BTS',
                'type_recherche' => 'CDD',
                'domaine' => 'Logistique & Transport',
                'region' => 'Mbour',
                'description' => 'Diplômé en Logistique et Transport, j\'ai une bonne maîtrise de la gestion des stocks, de la chaîne d\'approvisionnement et des outils ERP. Disponible immédiatement.',
                'email_contact' => 'cheikh.mbaye@email.com',
                'whatsapp' => '221772223344',
                'status' => 'publie',
            ],
        ];

        foreach ($profils as $profil) {
            Profil::create($profil);
        }
    }
}
