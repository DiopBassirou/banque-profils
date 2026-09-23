import { Component, inject } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { AdminService } from '../../core/services/admin.service';
import { switchMap } from 'rxjs';

@Component({
  selector: 'app-admin-login',
  standalone: true,
  imports: [ReactiveFormsModule],
  templateUrl: './admin-login.html',
  styleUrl: './admin-login.scss'
})
export class AdminLogin {
  loginForm: FormGroup;
  loading = false;
  errorMessage = '';

  private adminService = inject(AdminService);
  private router = inject(Router);

  constructor(private fb: FormBuilder) {
    this.loginForm = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      password: ['', Validators.required]
    });
  }

  onSubmit() {
    if (this.loginForm.invalid) return;

    this.loading = true;
    this.errorMessage = '';

    // D'abord on récupère le cookie CSRF, puis on login
    this.adminService.getCsrfCookie().pipe(
      switchMap(() => this.adminService.login(this.loginForm.value))
    ).subscribe({
      next: (res) => {
        this.loading = false;
        // Connecté avec succès, redirection vers le dashboard
        this.router.navigate(['/admin/dashboard']);
      },
      error: (err) => {
        this.loading = false;
        this.errorMessage = 'Identifiants incorrects ou accès refusé.';
      }
    });
  }
}
