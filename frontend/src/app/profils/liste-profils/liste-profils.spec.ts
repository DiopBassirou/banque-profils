import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ListeProfils } from './liste-profils';

describe('ListeProfils', () => {
  let component: ListeProfils;
  let fixture: ComponentFixture<ListeProfils>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ListeProfils]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ListeProfils);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
