import { Component, OnInit, inject } from '@angular/core';
import { Router } from '@angular/router';
import { AdminService } from '../../core/services/admin.service';
import { DatePipe } from '@angular/common';

@Component({
  selector: 'app-admin-dashboard',
  standalone: true,
  imports: [DatePipe],
  templateUrl: './admin-dashboard.html',
  styleUrl: './admin-dashboard.scss'
})
export class AdminDashboard implements OnInit {
  profils: any[] = [];
  loading = true;

  private adminService = inject(AdminService);
  private router = inject(Router);

  ngOnInit() {
    this.loadProfils();
  }

  loadProfils() {
    this.loading = true;
    this.adminService.getProfils().subscribe({
      next: (res) => {
        this.profils = res.data;
        this.loading = false;
      },
      error: (err) => {
        console.error('Erreur chargement profils admin', err);
        this.loading = false;
        if (err.status === 401 || err.status === 403) {
          this.router.navigate(['/admin/login']);
        }
      }
    });
  }

  updateStatus(id: number, status: string) {
    if (confirm(`Voulez-vous vraiment passer ce profil en statut : ${status} ?`)) {
      this.adminService.updateStatus(id, status).subscribe({
        next: () => this.loadProfils(),
        error: (err) => alert('Erreur lors de la mise à jour.')
      });
    }
  }

  deleteProfil(id: number) {
    if (confirm('Voulez-vous vraiment supprimer ce profil ?')) {
      this.adminService.deleteProfil(id).subscribe({
        next: () => this.loadProfils(),
        error: (err) => alert('Erreur lors de la suppression.')
      });
    }
  }

  logout() {
    this.adminService.logout().subscribe({
      next: () => this.router.navigate(['/admin/login']),
      error: () => this.router.navigate(['/admin/login'])
    });
  }
}
