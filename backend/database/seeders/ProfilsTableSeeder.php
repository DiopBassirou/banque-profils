<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfilsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nettoyer la table avant d'insérer (supprime les anciens tests)
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \App\Models\ModerationLog::truncate();
        \App\Models\Profil::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $profils = [
            [
                'nom_affiche' => 'Ibrahima Faye',
                'niveau' => 'Master',
                'experience' => 'Intermédiaire (3-5 ans)',
                'type_recherche' => 'CDI, CDD',
                'domaine' => 'Ingénieur Génie Civil',
                'competences' => 'AutoCAD, Revit, Gestion de projet, Calcul de structures',
                'region' => 'Dakar',
                'description' => "Ingénieur en génie civil spécialisé dans la conduite de travaux et le suivi de chantiers de BTP. J'ai supervisé la construction de plusieurs bâtiments à usage commercial et résidentiel au cours des 4 dernières années. Rigoureux sur le respect des normes de sécurité et des délais.",
                'email_contact' => 'ibrahima.faye@example.com',
                'whatsapp' => '221770000001',
                'status' => 'publie',
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'nom_affiche' => 'Fatou Diop',
                'niveau' => 'Licence',
                'experience' => 'Senior (5 ans et +)',
                'type_recherche' => 'CDI',
                'domaine' => 'Développeuse Fullstack',
                'competences' => 'Laravel, Angular, Vue.js, MySQL, Docker',
                'region' => 'Thiès',
                'description' => "Développeuse web passionnée avec 6 ans d'expérience dans la création d'applications SaaS et d'outils de gestion sur mesure. Forte capacité à travailler en équipe Agile et à concevoir des architectures backend robustes et des interfaces utilisateur intuitives.",
                'email_contact' => 'fatou.diop@example.com',
                'whatsapp' => '221770000002',
                'status' => 'publie',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'nom_affiche' => 'Moussa Ndiaye',
                'niveau' => 'Master',
                'experience' => 'Junior (0-2 ans)',
                'type_recherche' => 'Stage, Alternance',
                'domaine' => 'Marketing Digital',
                'competences' => 'SEO, Google Ads, Copywriting, Canva, Meta Ads',
                'region' => 'Dakar',
                'description' => "Jeune diplômé en marketing stratégique et digital. Au cours de mes stages, j'ai pu gérer des budgets publicitaires et augmenter de 30% l'acquisition client pour des e-commerces locaux. Créatif et orienté résultats, je cherche une opportunité pour exprimer mon talent.",
                'email_contact' => 'moussa.marketer@example.com',
                'whatsapp' => null,
                'status' => 'publie',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'nom_affiche' => 'Awa Sow',
                'niveau' => 'BTS',
                'experience' => 'Intermédiaire (3-5 ans)',
                'type_recherche' => 'CDI',
                'domaine' => 'Comptabilité et Finances',
                'competences' => 'Sage, Excel Avancé, Rapprochement bancaire, Fiscalité',
                'region' => 'Saint-Louis',
                'description' => "Comptable avec 4 ans d'expérience en cabinet. Je maîtrise l'ensemble du cycle comptable, des saisies jusqu'à la préparation des bilans. Excellente maîtrise de Sage et de la fiscalité sénégalaise. Disponible immédiatement pour un poste à responsabilité.",
                'email_contact' => 'awa.sow.compta@example.com',
                'whatsapp' => '221770000004',
                'status' => 'publie',
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],
            [
                'nom_affiche' => 'Cheikh Fall',
                'niveau' => 'Bac',
                'experience' => 'Senior (5 ans et +)',
                'type_recherche' => 'CDD, CDI',
                'domaine' => 'Design Graphique / UI',
                'competences' => 'Figma, Adobe Illustrator, Photoshop, UI/UX Design',
                'region' => 'Dakar',
                'description' => "Designer UI/UX et graphiste chevronné. Plus de 7 ans d'expérience en agence de communication et en freelance. Je conçois des identités visuelles percutantes et des interfaces web centrées sur l'utilisateur. Portfolio disponible sur demande.",
                'email_contact' => 'cheikh.design@example.com',
                'whatsapp' => '221770000005',
                'status' => 'publie',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'nom_affiche' => 'Khady Bâ',
                'niveau' => 'Licence',
                'experience' => 'Junior (0-2 ans)',
                'type_recherche' => 'CDI',
                'domaine' => 'Ressources Humaines',
                'competences' => 'Recrutement, Gestion de paie, Droit du travail, Formation',
                'region' => 'Dakar',
                'description' => "Professionnelle RH polyvalente, je m'occupe de l'intégration, du suivi du personnel et du recrutement. Mon excellent relationnel et mon sens de l'écoute me permettent de gérer efficacement les relations sociales au sein de l'entreprise.",
                'email_contact' => null,
                'whatsapp' => '221770000006',
                'status' => 'publie',
                'created_at' => now()->subDays(6),
                'updated_at' => now()->subDays(6),
            ],
            [
                'nom_affiche' => 'Ousmane Cissé',
                'niveau' => 'Master',
                'experience' => 'Intermédiaire (3-5 ans)',
                'type_recherche' => 'CDI',
                'domaine' => 'Data Scientist / Analyste',
                'competences' => 'Python, SQL, PowerBI, Machine Learning, Excel',
                'region' => 'Dakar',
                'description' => "Passionné par la valorisation de la donnée. J'accompagne les entreprises dans la prise de décision grâce à la modélisation prédictive et la création de dashboards interactifs sous PowerBI. Expérience probante dans le secteur bancaire et télécom.",
                'email_contact' => 'ousmane.data@example.com',
                'whatsapp' => '221770000007',
                'status' => 'publie',
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],
            [
                'nom_affiche' => 'Aminata Sy',
                'niveau' => 'Licence',
                'experience' => 'Senior (5 ans et +)',
                'type_recherche' => 'CDI, CDD',
                'domaine' => 'Commercial B2B & Vente',
                'competences' => 'Prospection, Négociation, CRM (Salesforce), Fidélisation',
                'region' => 'Dakar',
                'description' => "Forte d'une expérience de 8 ans dans la vente de solutions logicielles (SaaS). Je suis une chasseuse dans l'âme, capable d'ouvrir de nouveaux comptes clients et de développer un portefeuille existant. Excellente aisance relationnelle.",
                'email_contact' => 'aminata.sy.sales@example.com',
                'whatsapp' => '221770000008',
                'status' => 'publie',
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(8),
            ],
        ];

        \App\Models\Profil::insert($profils);
    }
}
