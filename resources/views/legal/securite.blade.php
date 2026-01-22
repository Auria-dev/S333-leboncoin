@extends('layout')

@section('title', 'Sécurité et Confiance - Lemauvaiscoin')

@section('content')
<div class="legal-wrapper">
    <div class="legal-card">
        <h1 style="font-size: 32px; font-weight: 800; color: #2b323e; margin-bottom: 10px;">Centre de Sécurité et de Confiance</h1>
        <p style="color: #717171; font-size: 14px; margin-bottom: 40px;">Protection des transactions et lutte contre la fraude</p>

        <div style="line-height: 1.8; color: #4a4a4a; font-size: 15px; text-align: justify;">
            
            <section style="margin-bottom: 50px;">
                <h2 style="font-size: 22px; font-weight: 700; color: #2b323e; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">1. LE PAIEMENT SÉCURISÉ INTÉGRÉ</h2>
                <p style="margin-bottom: 20px;">
                    Pour garantir la sérénité absolue des transactions entre particuliers, Lemauvaiscoin agit en tant que Tiers de Confiance. Nous avons développé une infrastructure de paiement qui séquestre les fonds jusqu'à la bonne exécution du service.
                </p>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 30px;">
                    <div style="border: 1px solid #eee; padding: 20px; border-radius: 8px; background: #fafafa;">
                        <strong style="display: block; color: #ec5a13; font-size: 16px; margin-bottom: 10px; text-transform: uppercase;">Étape 1 : Cantonnement</strong>
                        <p style="font-size: 13px;">Lorsque le locataire règle en ligne, l'argent n'est pas versé au propriétaire. Il est bloqué sur un compte technique sécurisé (compte séquestre) géré par notre partenaire bancaire.</p>
                    </div>
                    <div style="border: 1px solid #eee; padding: 20px; border-radius: 8px; background: #fafafa;">
                        <strong style="display: block; color: #ec5a13; font-size: 16px; margin-bottom: 10px; text-transform: uppercase;">Étape 2 : Validation</strong>
                        <p style="font-size: 13px;">Les fonds restent sécurisés pendant toute la durée qui précède le séjour. Aucune fraude financière n'est possible puisque l'argent est chez nous.</p>
                    </div>
                    <div style="border: 1px solid #eee; padding: 20px; border-radius: 8px; background: #fafafa;">
                        <strong style="display: block; color: #ec5a13; font-size: 16px; margin-bottom: 10px; text-transform: uppercase;">Étape 3 : Libération</strong>
                        <p style="font-size: 13px;">24 heures après l'arrivée dans les lieux, si aucun litige n'est déclaré, le virement est déclenché automatiquement vers le compte bancaire de l'hôte.</p>
                    </div>
                </div>

                <p>
                    Toutes les transactions sont protégées par le protocole <strong>3D-Secure V2</strong> (double authentification bancaire) garantissant que le porteur de la carte est bien à l'origine du paiement.
                </p>
            </section>

            <section style="margin-bottom: 50px;">
                <h2 style="font-size: 22px; font-weight: 700; color: #2b323e; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">2. VIGILANCE ET LUTTE CONTRE LE PHISHING</h2>
                <p>
                    Les tentatives d'hameçonnage (phishing) sont la première cause de fraude sur internet. Des individus malveillants peuvent tenter de vous contacter pour récupérer vos coordonnées bancaires ou vos identifiants.
                </p>
                <div style="background-color: #fff5f5; border-left: 4px solid #c53030; padding: 20px; margin-top: 20px;">
                    <strong style="display: block; color: #c53030; font-size: 16px; margin-bottom: 10px;">RÈGLES D'OR DE SÉCURITÉ :</strong>
                    <ul style="list-style-type: disc; margin-left: 20px; color: #2b323e;">
                        <li style="margin-bottom: 10px;"><strong>Ne sortez jamais de la messagerie sécurisée :</strong> Si un interlocuteur insiste pour communiquer par WhatsApp, SMS ou Email personnel, coupez court à la conversation. C'est le signe précurseur d'une arnaque.</li>
                        <li style="margin-bottom: 10px;"><strong>Méfiez-vous des liens :</strong> Lemauvaiscoin ne vous enverra jamais de SMS contenant un lien pour "valider un paiement" ou "confirmer une vente". Connectez-vous toujours directement via l'application ou le site officiel.</li>
                        <li style="margin-bottom: 10px;"><strong>Modes de paiement interdits :</strong> Refusez systématiquement les paiements par coupons PCS, Transcash, Toneo, Mandat Cash ou Western Union. Ces moyens sont in-traçables et utilisés exclusivement par les escrocs.</li>
                    </ul>
                </div>
            </section>

            <section>
                <h2 style="font-size: 22px; font-weight: 700; color: #2b323e; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">3. CERTIFICATION DES COMPTES</h2>
                <p>
                    Pour assainir la plateforme, nous déployons progressivement la vérification d'identité obligatoire.
                    <br><br>
                    <strong>Le processus KYC (Know Your Customer) :</strong>
                    Nous faisons appel à un prestataire certifié par l'ANSSI pour analyser les documents d'identité. La technologie utilise la reconnaissance optique de caractères (OCR) et l'analyse de micro-éléments de sécurité (hologrammes) pour détecter les faux documents.
                    Une fois le compte certifié, un badge "Identité Vérifiée" est apposé sur le profil, augmentant drastiquement la confiance des autres utilisateurs.
                </p>
            </section>

        </div>
    </div>
</div>
@endsection