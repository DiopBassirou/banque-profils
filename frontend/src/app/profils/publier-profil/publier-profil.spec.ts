import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PublierProfil } from './publier-profil';

describe('PublierProfil', () => {
  let component: PublierProfil;
  let fixture: ComponentFixture<PublierProfil>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [PublierProfil]
    })
    .compileComponents();

    fixture = TestBed.createComponent(PublierProfil);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
