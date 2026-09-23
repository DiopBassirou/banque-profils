import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../../environments/environment';
import { Observable } from 'rxjs';

export interface ProfilData {
  id?: number;
  nom_affiche: string;
  niveau: string;
  type_recherche: string;
  domaine: string;
  region: string;
  description: string;
  email_contact?: string | null;
  whatsapp?: string | null;
  email_gestion?: string | null;
  status?: string;
}

@Injectable({
  providedIn: 'root'
})
export class ProfilService {
  private http = inject(HttpClient);
  private apiUrl = `${environment.apiUrl}/profils`;

  getProfils(filters?: any): Observable<any> {
    return this.http.get(this.apiUrl, { params: filters });
  }

  createProfil(data: Partial<ProfilData>): Observable<any> {
    return this.http.post(this.apiUrl, data);
  }

  signalerProfil(id: number): Observable<any> {
    return this.http.post(`${this.apiUrl}/${id}/signaler`, {});
  }
}
