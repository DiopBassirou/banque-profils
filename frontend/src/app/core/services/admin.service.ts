import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../../environments/environment';
import { Observable, tap } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class AdminService {
  private http = inject(HttpClient);
  private apiUrl = environment.apiUrl;

  // Récupère le cookie CSRF nécessaire pour Sanctum
  getCsrfCookie(): Observable<any> {
    return this.http.get(`${environment.apiUrl.replace('/api', '')}/sanctum/csrf-cookie`);
  }

  login(credentials: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/admin/login`, credentials);
  }

  logout(): Observable<any> {
    return this.http.post(`${this.apiUrl}/admin/logout`, {});
  }

  getProfils(params?: any): Observable<any> {
    return this.http.get(`${this.apiUrl}/admin/profils`, { params });
  }

  updateStatus(profilId: number, status: string, raison?: string): Observable<any> {
    return this.http.patch(`${this.apiUrl}/admin/profils/${profilId}/status`, { status, raison });
  }

  deleteProfil(profilId: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/admin/profils/${profilId}`);
  }
}
