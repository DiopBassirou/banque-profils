import { Component, OnInit, inject, ChangeDetectorRef } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule } from '@angular/forms';
import { ProfilService, ProfilData } from '../../core/services/profil';

@Component({
  selector: 'app-liste-profils',
  imports: [ReactiveFormsModule],
  templateUrl: './liste-profils.html',
  styleUrl: './liste-profils.scss'
})
export class ListeProfils implements OnInit {
  private profilService = inject(ProfilService);
  private cdr = inject(ChangeDetectorRef);
  private fb = inject(FormBuilder);
  
  profils: ProfilData[] = [];
  loading = true;
  searchForm: FormGroup;

  constructor() {
    this.searchForm = this.fb.group({
      domaine: [''],
      type_recherche: ['']
    });
  }

  ngOnInit() {
    this.chargerProfils();
  }

  onSearch() {
    const values = this.searchForm.value;
    // Retirer les valeurs vides
    const filters = Object.fromEntries(Object.entries(values).filter(([_, v]) => v !== ''));
    this.chargerProfils(filters);
  }

  chargerProfils(filters?: any) {
    this.loading = true;
    this.profilService.getProfils(filters).subscribe({
      next: (response) => {
        this.profils = response?.data ? response.data : (Array.isArray(response) ? response : []);
        this.loading = false;
        this.cdr.detectChanges();
      },
      error: (err) => {
        console.error('Erreur de chargement des profils', err);
        this.loading = false;
        this.cdr.detectChanges();
      }
    });
  }

  // Sépare les types multiples (ex: "Stage,CDD" → ["Stage", "CDD"])
  getTypes(typeRecherche: string): string[] {
    if (!typeRecherche) return [];
    return typeRecherche.split(',').map(t => t.trim());
  }

  // Sépare les compétences (ex: "Laravel, Vue, CSS" → ["Laravel", "Vue", "CSS"])
  getCompetencesArray(competences?: string | null): string[] {
    if (!competences) return [];
    return competences.split(',').map(c => c.trim()).filter(c => c.length > 0);
  }
}
