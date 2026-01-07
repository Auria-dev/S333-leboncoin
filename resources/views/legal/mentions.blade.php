@extends('layout')

@section('title', 'Mentions Légales - Leboncoin')

@section('content')
<div class="legal-wrapper">
    <div class="legal-card">
        <h1 style="font-size: 32px; font-weight: 800; color: #2b323e; margin-bottom: 40px;">Mentions Légales</h1>

        <div style="line-height: 1.8; color: #4a4a4a; font-size: 15px;">
            
            <p style="margin-bottom: 30px;">
                Conformément aux dispositions des articles 6-III et 19 de la Loi n° 2004-575 du 21 juin 2004 pour la Confiance dans l'Économie Numérique (LCEN), nous portons à la connaissance des utilisateurs et visiteurs du site <strong>leboncoin.fr</strong> les informations suivantes :
            </p>

            <div style="margin-bottom: 40px; border: 1px solid #e2e8f0; border-radius: 12px; padding: 30px;">
                <h3 style="color: #ec5a13; font-size: 18px; text-transform: uppercase; margin-top: 0; margin-bottom: 20px; letter-spacing: 0.5px;">1. Identification de l'Éditeur</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
                    <div>
                        <strong style="display: block; color: #2b323e; margin-bottom: 5px;">Raison sociale</strong>
                        LEBONCOIN SAS (Société par Actions Simplifiée)
                        
                        <strong style="display: block; color: #2b323e; margin-top: 15px; margin-bottom: 5px;">Capital social</strong>
                        8 000 000 €
                        
                        <strong style="display: block; color: #2b323e; margin-top: 15px; margin-bottom: 5px;">RCS</strong>
                        Paris B 888 555 111
                    </div>
                    <div>
                        <strong style="display: block; color: #2b323e; margin-bottom: 5px;">Siège social</strong>
                        24 rue des Jeûneurs<br>75002 Paris - France
                        
                        <strong style="display: block; color: #2b323e; margin-top: 15px; margin-bottom: 5px;">Numéro de TVA Intracommunautaire</strong>
                        FR 12 888 555 111
                        
                        <strong style="display: block; color: #2b323e; margin-top: 15px; margin-bottom: 5px;">Contact</strong>
                        support@leboncoin.fr / +33 1 40 00 00 00
                    </div>
                </div>
                <p style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px;">
                    <strong>Directeur de la publication :</strong> Monsieur le Directeur Général du Groupe Adevinta.<br>
                    <strong>Responsable de la rédaction :</strong> Service Communication Leboncoin.
                </p>
            </div>

            <div style="margin-bottom: 40px; border: 1px solid #e2e8f0; border-radius: 12px; padding: 30px;">
                <h3 style="color: #ec5a13; font-size: 18px; text-transform: uppercase; margin-top: 0; margin-bottom: 20px; letter-spacing: 0.5px;">2. Hébergement du Site</h3>
                <p style="margin-bottom: 15px;">
                    Le site est hébergé exclusivement sur le territoire français par la société :
                </p>
                <div style="background-color: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                    <strong>OVHcloud SAS</strong><br>
                    Siège social : 2 rue Kellermann - 59100 Roubaix - France.<br>
                    Capital social : 10 174 560 €<br>
                    RCS Lille Métropole 424 761 419 00045<br>
                    Téléphone : 1007
                </div>
                <p style="font-size: 14px; color: #64748b;">
                    <strong>Note technique :</strong> Notre infrastructure hébergée chez OVH bénéficie d'une protection avancée contre les attaques par déni de service (Anti-DDoS via technologie VAC). Les centres de données sont certifiés ISO/IEC 27001, HDS (Hébergement de Données de Santé) et PCI-DSS, garantissant un niveau maximal de sécurité physique et logique. Un contrat d'engagement de niveau de service (SLA) assure une disponibilité de 99.9% des serveurs.
                </p>
            </div>

            <div style="margin-bottom: 20px; border: 1px solid #e2e8f0; border-radius: 12px; padding: 30px;">
                <h3 style="color: #ec5a13; font-size: 18px; text-transform: uppercase; margin-top: 0; margin-bottom: 20px; letter-spacing: 0.5px;">3. Propriété Intellectuelle</h3>
                <p style="margin-bottom: 15px;">
                    La structure générale du site <em>leboncoin.fr</em>, ainsi que les textes, graphiques, images, sons et vidéos la composant, sont la propriété exclusive de la société Leboncoin SAS ou de ses partenaires. Toute représentation et/ou reproduction et/ou exploitation partielle ou totale de ce site, par quelque procédé que ce soit, sans l'autorisation préalable et par écrit de la société Leboncoin SAS est strictement interdite et serait susceptible de constituer une contrefaçon au sens des articles L 335-2 et suivants du Code de la propriété intellectuelle.
                </p>
                <p>
                    Les marques "Leboncoin", "Adevinta" sont des marques déposées. Toute représentation et/ou reproduction et/ou exploitation partielle ou totale de ces marques, de quelque nature que ce soit, est totalement prohibée.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection