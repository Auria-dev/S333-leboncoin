@extends('layout')

@section('title', 'Tous les avis - ' . $annonce->titre_annonce)

@section('content')

<div class="container" style="min-width: 800px; margin: 0 auto; padding: 40px 20px;">
    
    <div style="margin-bottom: 20px;">
        <a href="{{ url('/annonce/' . $annonce->idannonce) }}" style="text-decoration: none; color: #333; display: flex; align-items: center; gap: 5px; font-weight: 500;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Retour à l'annonce
        </a>
    </div>

    <div style="border-bottom: 1px solid #ddd; padding-bottom: 20px; margin-bottom: 30px;">
        <h1 style="font-size: 1.8rem; margin-bottom: 10px;">
            Avis deposé sur cette annonce
        </h1>
        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 2rem; font-weight: bold; color: #333;">
                ★ {{ number_format($annonce->avisValides->avg('note'), 1) }}
            </span>
            <span style="color: #666; font-size: 1.2rem;">
                &bull; {{ $annonce->avisValides->count() }} avis
            </span>
        </div>
    </div>

    <div class="avis-list">
        @forelse($annonce->avisValides as $avis)
            <div class="avis-card" style="margin-bottom: 30px; padding-bottom: 30px; border-bottom: 1px solid #eee;">
                
                <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 15px;">
                    {{-- Info Auteur --}}
                    <div style="display: flex; align-items: center; gap: 15px;">
                        {{-- Avatar rond avec la 1ère lettre --}}
                        <div style="width: 48px; height: 48px; background-color: #f0f0f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #555; font-size: 1.2rem;">
                            {{ substr($avis->utilisateur->prenom_utilisateur ?? 'V', 0, 1) }}
                        </div>
                        <div>
                            <div style="font-weight: bold; font-size: 1.1rem; color: #222;">
                                {{ $avis->utilisateur->prenom_utilisateur ?? 'Voyageur' }} {{ $avis->utilisateur->nom_utilisateur ?? '' }}
                            </div>
                            <div style="color: #717171; font-size: 0.9rem;">
                                {{ \Carbon\Carbon::parse($avis->date_depot)->format('d F Y') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 10px; color: #ffb400; font-size: 1.1rem;">
                    @for($i = 0; $i < 5; $i++)
                        @if($i < $avis->note) 
                            ★ @else 
                            ☆ @endif
                    @endfor
                </div>
                
                <div style="font-size: 1.05rem; line-height: 1.6; color: #222;">
                    {{ $avis->commentaire }}
                </div>

                @if($avis->reponse_avis)
                    <div style="margin-top: 20px; padding: 20px; background-color: #f8f9fa; border-radius: 8px;">
                        <div style="font-weight: bold; margin-bottom: 5px; font-size: 0.95rem;">
                            Réponse du propriétaire :
                        </div>
                        <div style="color: #555; line-height: 1.5;">
                            {{ $avis->reponse_avis }}
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div style="text-align: center; padding: 60px 0; color: #717171;">
                <p>Aucun commentaire n'a encore été validé pour cette annonce.</p>
            </div>
        @endforelse
    </div>
</div>

@endsection