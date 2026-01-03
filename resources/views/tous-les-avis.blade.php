@extends('layout')

@section('title', 'Avis - ' . $annonce->titre_annonce)

@section('content')

<div style="min-width: 800px; margin: 0 auto; padding: 40px 20px;">
    
    <div style="margin-bottom: 20px;">
        <a href="{{ url('/annonce/' . $annonce->idannonce) }}" style="text-decoration: none; color: #333; display: flex; align-items: center; gap: 5px; font-weight: 500;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Retour à l'annonce
        </a>
    </div>

    <div>
        <h2 style="margin-bottom: 10px;">Avis deposés sur cette annonce</h2>
        <div style="display: flex; align-items: baseline; gap: 10px; margin-bottom: 20px;">
            <span style="font-size: 30px; font-weight: bold;">
                ★ {{ number_format($annonce->avisValides->avg('note'), 1) }}
            </span>
            <span style="color: var(--text-muted);">
                ({{ $annonce->avisValides->count() }} avis)
            </span>
        </div>
    </div>

        <div>
        @forelse($annonce->avisValides as $avis)
        <hr class="res-divider">
            <div style="padding-top: 10px; padding-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 12px; font-weight: bold;">
                        
                        @if($avis->utilisateur->photo_profil === null)
                            <img src="/images/photo-profil.jpg" class="profile-img" style="width: 50px; height: 50px; border-radius: 50%; ">
                        @else
                            <img src="{{ $avis->utilisateur->photo_profil }}" class="profile-img" style="width: 50px; height: 50px; border-radius: 50%;">
                        @endif

                        <div>
                            {{ $avis->utilisateur->prenom_utilisateur }} {{ $avis->utilisateur->nom_utilisateur }}
                            <span style="color: var(--text-muted); font-size: 0.8em; font-weight: normal; display: block;">
                                {{ \Carbon\Carbon::parse($avis->date_depot)->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>

                    <div style="color: var(--primary-hover); font-size: 1.2em;">
                        @for($i = 0; $i < 5; $i++)
                            @if($i < $avis->note) ★ @else ☆ @endif
                        @endfor
                    </div>
                </div>
                
                <p style="line-height: 2">
                    {{ $avis->commentaire }}
                </p>

                @if($avis->reponse_avis)
                    <div style="margin-top: 10px; padding: 8px 0 5px 10px; background-color: var(--bg-response); border-left: 3px solid var(--text-muted); font-size: 0.9sem; border-top-right-radius: 30px; border-bottom-right-radius: 30px;">
                        <p>{{$annonce->utilisateur->prenom_utilisateur}} {{$annonce->utilisateur->nom_utilisateur}} : </p>
                        <p style="line-height: 2">{{ $avis->reponse_avis }} </p>
                    </div>
                @endif
            </div>
        @empty
            <p style="color: var(--text-muted);; font-style: italic;">Aucun commentaire pour le moment.</p>
        @endforelse
    </div>
</div>

@endsection