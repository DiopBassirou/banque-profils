import { Routes } from '@angular/router';

export const routes: Routes = [
  { 
    path: '', 
    loadChildren: () => import('./profils/profils-module').then(m => m.ProfilsModule) 
  },
  { 
    path: 'compte', 
    loadChildren: () => import('./compte/compte-module').then(m => m.CompteModule) 
  },
  { 
    path: 'admin', 
    loadChildren: () => import('./admin/admin-module').then(m => m.AdminModule) 
  },
  { 
    path: '**', 
    redirectTo: '' 
  }
];
