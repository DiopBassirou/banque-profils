import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ListeProfils } from './liste-profils/liste-profils';
import { PublierProfil } from './publier-profil/publier-profil';

const routes: Routes = [
  { path: '', component: ListeProfils },
  { path: 'publier', component: PublierProfil }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ProfilsRoutingModule { }
