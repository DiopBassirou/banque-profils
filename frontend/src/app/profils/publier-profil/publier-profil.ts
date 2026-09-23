import { Component, inject } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { ProfilService } from '../../core/services/profil';

@Component({
  selector: 'app-publier-profil',
  imports: [ReactiveFormsModule],
  templateUrl: './publier-profil.html',
  styleUrl: './publier-profil.scss'
})
export class PublierProfil {
  profilForm: FormGroup;
  submitted = false;
  successMessage = false;
  errorMessage = '';
  loading = false;
  private profilService = inject(ProfilService);
  private router = inject(Router);

  niveaux = ['Bac', 'BTS', 'Licence', 'Master'];
  typesRecherche = ['Stage', 'CDD', 'CDI', 'Alternance'];
  selectedTypes: string[] = [];

  constructor(private fb: FormBuilder) {
    this.profilForm = this.fb.group({
      nom_affiche: ['', [Validators.required, Validators.minLength(2)]],
      niveau: ['', Validators.required],
      type_recherche: ['', Validators.required],
      domaine: ['', Validators.required],
      region: ['', Validators.required],
      description: ['', [Validators.required, Validators.minLength(10), Validators.maxLength(1000)]],
      email_contact: ['', Validators.email],
      whatsapp: ['', Validators.pattern('^[0-9]{8,15}$')],
      consentement: [false, Validators.requiredTrue]
    }, { validators: this.contactRequisValidator });
  }

  contactRequisValidator(group: FormGroup) {
    const email = group.get('email_contact')?.value;
    const whatsapp = group.get('whatsapp')?.value;
    if (!email && !whatsapp) {
      return { contactRequired: true };
    }
    return null;
  }

  setNiveau(val: string) {
    this.profilForm.patchValue({ niveau: val });
  }

  toggleTypeRecherche(val: string) {
    const index = this.selectedTypes.indexOf(val);
    if (index > -1) {
      this.selectedTypes.splice(index, 1);
    } else {
      this.selectedTypes.push(val);
    }
    // Stocke comme chaîne séparée par des virgules pour le backend
    this.profilForm.patchValue({ type_recherche: this.selectedTypes.join(',') });
  }

  isTypeSelected(val: string): boolean {
    return this.selectedTypes.includes(val);
  }

  onSubmit() {
    this.submitted = true;
    this.errorMessage = '';
    
    if (this.profilForm.invalid) {
      this.errorMessage = 'Veuillez remplir correctement tous les champs obligatoires.';
      return;
    }

    this.loading = true;
    const { consentement, ...profilData } = this.profilForm.value;

    this.profilService.createProfil(profilData).subscribe({
      next: (res) => {
        this.loading = false;
        this.successMessage = true;
        this.profilForm.reset();
        this.selectedTypes = [];
        this.submitted = false;
        
        setTimeout(() => {
          this.router.navigate(['/']);
        }, 5000);
      },
      error: (err) => {
        console.error('Erreur', err);
        this.loading = false;
        this.errorMessage = err.error?.message || 'Une erreur est survenue lors de la publication. Veuillez vérifier vos informations.';
      }
    });
  }
}
