import { Component, inject } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { NgIf } from '@angular/common';
import { AuthService } from '../../core/services/auth.service';

@Component({
  selector: 'app-mon-compte',
  imports: [FormsModule, NgIf],
  templateUrl: './mon-compte.html',
  styleUrl: './mon-compte.scss',
})
export class MonCompte {
  email = '';
  loading = false;
  successMessage = false;
  errorMessage = '';

  private authService = inject(AuthService);

  onSubmit() {
    if (!this.email) return;
    
    this.loading = true;
    this.errorMessage = '';
    this.successMessage = false;

    this.authService.sendMagicLink(this.email).subscribe({
      next: (res) => {
        this.loading = false;
        this.successMessage = true;
      },
      error: (err) => {
        this.loading = false;
        this.errorMessage = "Une erreur est survenue lors de l'envoi du lien.";
      }
    });
  }
}
