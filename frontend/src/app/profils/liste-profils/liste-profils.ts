import { Component, OnInit, inject, ChangeDetectorRef } from '@angular/core';
import { ProfilService, ProfilData } from '../../core/services/profil';

@Component({
  selector: 'app-liste-profils',
  imports: [],
  templateUrl: './liste-profils.html',
  styleUrl: './liste-profils.scss'
})
export class ListeProfils implements OnInit {
  private profilService = inject(ProfilService);
  private cdr = inject(ChangeDetectorRef);
  
  profils: ProfilData[] = [];
  loading = true;

  constructor() {}

  ngOnInit() {
    this.chargerProfils();
  }

  chargerProfils() {
    this.profilService.getProfils().subscribe({
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
}
