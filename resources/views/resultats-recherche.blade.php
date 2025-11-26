@extends('layout')

@section('title', 'Résultat(s)')

@section('content')

<style>
.results-grid{display:grid;grid-template-columns:repeat(auto-fill, minmax(300px, 1fr));gap:var(--spacing);width:100%;margin-top:2rem}
.annonce-card .annonce-info{background-color:#fff;display:flex;flex-direction:column; width: 100%;}
.annonce-card{gap: 1rem;background-color:#fff;border:1px solid var(--input-border);border-radius:var(--radius);padding:1.5rem;display:flex;flex-direction:row;transition:transform 0.2s ease, box-shadow 0.2s ease}
.annonce-card:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(0, 0, 0, 0.05);border-color:var(--text-muted)}
.annonce-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1rem;gap:1rem;width: 100%;}
.annonce-title{font-size:1.1rem;font-weight:700;color:var(--text-main);line-height:1.3;margin:0}
.annonce-date{font-size:0.75rem;color:var(--text-muted);white-space:nowrap;background:var(--bg-body);padding:0.2rem 0.6rem;border-radius:4px;font-weight:600; height:fit-content;}
.annonce-details{margin-top:auto;padding-top:1rem;border-top:1px solid var(--bg-body)}
.annonce-location{font-size:0.9rem;color:var(--text-muted);display:flex;align-items:center;gap:0.5rem;margin-top:0.5rem;}
.icon-pin{width:16px;height:16px;stroke:var(--text-muted)}
.annonce-photo {width: 30%; border: 1px solid var(--input-border); border-radius: 8px;}
.df {display:flex; justify-content: space-between; align-items:end;}
.fdr {flex-direction: row;}
</style>


<!-- 
Champs de la table "annonce":
    "idannonce"
    "idtypehebergement"
    "idville"
    "idproprietaire"
    "titre_annonce"
    "prix_nuit"
    "nb_nuit_min"
    "nb_bebe_max"
    "nb_personnes_max"
    "nb_animaux_max"
    "adresse_annonce"
    "description_annonce"
    "date_publication"
-->


<div class="results-grid">
    @foreach($annonces as $annonce)
        <article class="annonce-card">
            <div class="annonce-photo">
                <img src="{{ $annonce->photo[0]->nomphoto }}" alt="{{ $annonce->photo[0]->nomphoto }}"/>
            </div>

            <div class="annonce-info">
                <div class="annonce-header">
                    <h2 class="annonce-title">{{ $annonce->titre_annonce }}</h2>
                    <p>{{ ceil($annonce->prix_nuit) }}€ / nuit</p>
                </div>
                
                <div class="df fdr">
                    <div class="annonce-details">
                        <p>{{ $annonce->type_hebergement->nom_type_hebergement }} &bull; {{ $annonce->nb_personnes_max }} pers &bull; {{ $annonce->nb_bebe_max }} bébé</p>
                        <p class="annonce-location">
                            <svg class="icon-pin" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            {{ $annonce->ville->nomville }} &bull; {{ $annonce->adresse_annonce }}
                        </p>
                    </div>
                    <span class="annonce-date">
                        {{ \Carbon\Carbon::parse($annonce->date_publication)->format('d M Y') }}
                    </span>
                </div>
            </div>
        </article>
    @endforeach
</div>

@endsection

<!-- 
user story:
En tant que visiteur, je veux effectuer une recherche d’annonces par la localisation (le lieu) afin d’obtenir
 la liste des annonces avec leur présentation générale (pas de photo juste 
 - lieu, 
 - précisions, 
 - type (T2…)
 - adresse précise 
 - date de dépôt.
-->