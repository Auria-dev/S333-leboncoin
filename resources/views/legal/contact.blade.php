@extends('layout')

@section('title', 'Centre d\'Aide - Leboncoin')

@section('content')
<div class="legal-wrapper">
    <div class="legal-card">
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: start;">
            
            <div style="color: #4a4a4a;">
                <span style="font-size: 12px; font-weight: 700; color: #ec5a13; text-transform: uppercase; letter-spacing: 1px;">Support Client</span>
                <h1 style="font-size: 36px; font-weight: 900; color: #2b323e; margin-top: 10px; margin-bottom: 20px; line-height: 1.2;">Comment pouvons-nous vous aider ?</h1>
                <p style="font-size: 16px; line-height: 1.6; margin-bottom: 40px;">
                    Nos équipes basées en France sont à votre disposition pour vous accompagner. Que vous soyez un hôte cherchant à optimiser son annonce ou un voyageur préparant son séjour, nous nous engageons à vous apporter une réponse personnalisée.
                </p>

                <div style="margin-bottom: 40px;">
                    <div style="margin-bottom: 20px;">
                        <div>
                            <strong style="display: block; color: #2b323e; margin-bottom: 5px;">Disponibilité</strong>
                            Nos services sont ouverts du Lundi au Samedi, de 9h à 19h.
                            <br><span style="font-size: 13px; color: #717171;">Temps de réponse moyen : 4 heures.</span>
                        </div>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <div>
                            <strong style="display: block; color: #2b323e; margin-bottom: 5px;">Urgences Sécurité</strong>
                            Pour tout signalement de fraude ou problème de sécurité critique, votre demande sera traitée en priorité par notre équipe Trust & Safety.
                        </div>
                    </div>
                    <div>
                        <div>
                            <strong style="display: block; color: #2b323e; margin-bottom: 5px;">Données Personnelles (DPO)</strong>
                            Pour exercer vos droits RGPD, contactez directement : 
                            <a href="mailto:privacy@leboncoin.fr" style="color: #ec5a13; font-weight: 600; text-decoration: none;">privacy@leboncoin.fr</a>
                        </div>
                    </div>
                </div>

                <div style="background: #f8fafc; padding: 25px; border-radius: 12px; border: 1px solid #e2e8f0;">
                    <strong style="display: block; color: #2b323e; margin-bottom: 5px; font-size: 14px;">Adresse Postale (Siège)</strong>
                    <span style="font-size: 14px; color: #64748b;">
                        Leboncoin SAS - Service Relations Membres<br>
                        24 rue des Jeûneurs<br>
                        75002 Paris - France
                    </span>
                </div>
            </div>

            <div style="background-color: #fff; border: 1px solid #e0e0e0; border-radius: 16px; padding: 40px; box-shadow: 0 10px 25px rgba(0,0,0,0.05);">
                <h2 style="font-size: 20px; font-weight: 700; color: #2b323e; margin-top: 0; margin-bottom: 30px;">Envoyer un message</h2>
                
                <form action="#" method="POST">
                    @csrf
                    
                    <div style="margin-bottom: 20px;">
                        <label for="email" style="display: block; font-size: 13px; font-weight: 700; color: #4a5568; margin-bottom: 8px; text-transform: uppercase;">Votre Email</label>
                        <input type="email" id="email" name="email" placeholder="exemple@email.com" required style="width: 100%; padding: 14px; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 15px; background-color: #f8fafc; transition: border-color 0.2s;">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="subject" style="display: block; font-size: 13px; font-weight: 700; color: #4a5568; margin-bottom: 8px; text-transform: uppercase;">Motif de contact</label>
                        <div style="position: relative;">
                            <select id="subject" name="subject" style="width: 100%; padding: 14px; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 15px; background-color: #f8fafc; appearance: none; cursor: pointer;">
                                <option value="" disabled selected>Sélectionnez un sujet...</option>
                                <option value="booking">Problème avec une réservation</option>
                                <option value="payment">Question sur un paiement / Facture</option>
                                <option value="account">Accès au compte / Sécurité</option>
                                <option value="tech">Bug technique sur le site</option>
                                <option value="rgpd">Données personnelles / DPO</option>
                            </select>
                        </div>
                    </div>

                    <div style="margin-bottom: 30px;">
                        <label for="message" style="display: block; font-size: 13px; font-weight: 700; color: #4a5568; margin-bottom: 8px; text-transform: uppercase;">Message</label>
                        <textarea id="message" name="message" rows="5" placeholder="Merci de détailler votre demande pour nous aider à vous répondre efficacement..." required style="width: 100%; padding: 14px; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 15px; background-color: #f8fafc; resize: vertical; min-height: 120px;"></textarea>
                    </div>

                    <button type="submit" style="width: 100%; background-color: #ec5a13; color: white; padding: 16px; border: none; border-radius: 6px; font-size: 16px; font-weight: 700; cursor: pointer; transition: background-color 0.2s; box-shadow: 0 4px 6px rgba(236, 90, 19, 0.2);">
                        Envoyer ma demande
                    </button>
                    
                    <p style="margin-top: 20px; font-size: 12px; color: #718096; text-align: center;">
                        Vos données sont traitées conformément à notre <a href="{{ route('legal.privacy') }}" style="color: #ec5a13; text-decoration: none;">Politique de Confidentialité</a>.
                    </p>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection