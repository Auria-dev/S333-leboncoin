@extends('layout')

@section('title', 'Politique de Confidentialité - Lemauvaiscoin')

@section('content')
<div class="legal-wrapper">
    <div class="legal-card">
        <div style="border-bottom: 1px solid #ebebeb; padding-bottom: 24px; margin-bottom: 32px;">
            <h1 style="font-size: 32px; font-weight: 800; color: #2b323e; margin: 0 0 8px 0;">Politique de Protection des Données Personnelles</h1>
            <p style="color: #717171; font-size: 14px; margin: 0;">Version 4.2 — Entrée en vigueur le 01 Janvier 2026</p>
        </div>

        <div style="line-height: 1.8; color: #4a4a4a; text-align: justify; font-size: 15px;">
            <p style="margin-bottom: 24px;">
                La société <strong>Lemauvaiscoin SAS</strong>, filiale du groupe Adevinta, accorde une importance primordiale à la protection de la vie privée de ses utilisateurs. Dans un environnement numérique en constante évolution, nous nous engageons à garantir la confidentialité, l'intégrité et la disponibilité de vos données personnelles, conformément au Règlement (UE) 2016/679 du Parlement européen et du Conseil du 27 avril 2016 (RGPD) et à la Loi n° 78-17 du 6 janvier 1978 modifiée dite « Informatique et Libertés ».
            </p>
            <p style="margin-bottom: 40px;">
                La présente Politique de Confidentialité a pour objet d'exposer en toute transparence les modalités de collecte, de traitement et de conservation de vos données lors de votre navigation sur notre plateforme de location saisonnière. Elle détaille également les droits dont vous disposez et la manière de les exercer auprès de nos services.
            </p>

            <section style="margin-bottom: 50px;">
                <h2 style="font-size: 20px; font-weight: 700; color: #2b323e; margin-bottom: 20px; border-left: 4px solid #ec5a13; padding-left: 16px;">ARTICLE 1. RESPONSABLE DE TRAITEMENT ET GOUVERNANCE</h2>
                <p style="margin-bottom: 15px;">
                    Le responsable du traitement des données est la société <strong>Lemauvaiscoin SAS</strong>, dont le siège social est situé au 24 rue des Jeûneurs, 75002 Paris.
                </p>
                <p>
                    Conscients des enjeux, nous avons nommé un <strong>Délégué à la Protection des Données (Data Protection Officer - DPO)</strong>, enregistré auprès de la CNIL. Ce dernier est chargé de veiller à la conformité de nos traitements et d'être l'interlocuteur privilégié des utilisateurs et de l'autorité de contrôle. Il peut être contacté pour toute question relative à la protection des données à l'adresse électronique dédiée : <em>privacy@Lemauvaiscoin.fr</em>.
                </p>
            </section>

            <section style="margin-bottom: 50px;">
                <h2 style="font-size: 20px; font-weight: 700; color: #2b323e; margin-bottom: 20px; border-left: 4px solid #ec5a13; padding-left: 16px;">ARTICLE 2. NATURE ET FINALITÉS DES TRAITEMENTS</h2>
                <p style="margin-bottom: 20px;">
                    En application du principe de minimisation (Article 5.1.c du RGPD), nous ne collectons que les données strictement nécessaires à l'accomplissement des finalités déterminées, explicites et légitimes suivantes :
                </p>

                <div style="margin-left: 20px; border-left: 1px solid #ddd; padding-left: 20px;">
                    <h4 style="font-size: 16px; font-weight: 700; color: #2b323e; margin-bottom: 10px;">2.1. Gestion du Compte Utilisateur et des Réservations</h4>
                    <p style="margin-bottom: 15px;">
                        <strong>Données traitées :</strong> Identité (Nom, Prénom, Civilité), Coordonnées (Adresse email, Numéro de téléphone mobile, Adresse postale de facturation), Identifiants de connexion (Email, Mot de passe chiffré).<br>
                        <strong>Finalité :</strong> Création de l'espace personnel, authentification sécurisée, mise en relation entre Hôtes et Voyageurs, gestion du cycle de vie des annonces et des réservations.<br>
                        <strong>Base Légale :</strong> Exécution du contrat (Article 6.1.b du RGPD) auquel la personne concernée est partie.
                    </p>

                    <h4 style="font-size: 16px; font-weight: 700; color: #2b323e; margin-bottom: 10px;">2.2. Sécurisation des Transactions et Lutte contre la Fraude (KYC)</h4>
                    <p style="margin-bottom: 15px;">
                        <strong>Données traitées :</strong> Copie numérique de la pièce d'identité officielle (Carte Nationale d'Identité ou Passeport en cours de validité), justificatif de domicile de moins de 3 mois.<br>
                        <strong>Finalité :</strong> Vérification de l'identité des Hôtes lors de la première publication d'annonce afin de prévenir l'usurpation d'identité, le blanchiment de capitaux et le financement du terrorisme (LCB-FT).<br>
                        <strong>Base Légale :</strong> Respect d'une obligation légale (Article 6.1.c du RGPD) et Intérêt légitime de la plateforme à assurer la sécurité des biens et des personnes (Article 6.1.f du RGPD).
                    </p>

                    <h4 style="font-size: 16px; font-weight: 700; color: #2b323e; margin-bottom: 10px;">2.3. Gestion des Paiements et Facturation</h4>
                    <p style="margin-bottom: 15px;">
                        <strong>Données traitées :</strong> Données relatives aux moyens de paiement (PAN tronqué, date d'expiration), Relevé d'Identité Bancaire (IBAN/BIC), Historique des transactions.<br>
                        <strong>Finalité :</strong> Encaissement des loyers, séquestre des fonds, versement aux Hôtes, gestion des réclamations et remboursements, édition des factures de commission.<br>
                        <strong>Base Légale :</strong> Exécution du contrat (Article 6.1.b du RGPD).
                    </p>
                </div>
            </section>

            <section style="margin-bottom: 50px;">
                <h2 style="font-size: 20px; font-weight: 700; color: #2b323e; margin-bottom: 20px; border-left: 4px solid #ec5a13; padding-left: 16px;">ARTICLE 3. DESTINATAIRES DES DONNÉES</h2>
                <p>
                    Vos données personnelles sont destinées aux services internes habilités de Lemauvaiscoin (Service Client, Direction Technique, Service Juridique). Elles peuvent toutefois être communiquées à nos sous-traitants agissant sur nos instructions strictes :
                </p>
                <ul style="list-style-type: disc; margin-left: 20px; margin-top: 15px;">
                    <li style="margin-bottom: 8px;"><strong>OVHcloud SAS (France) :</strong> Hébergement des serveurs, stockage des bases de données et sauvegardes chiffrées.</li>
                    <li style="margin-bottom: 8px;"><strong>Stripe Payments Europe Ltd (Irlande) :</strong> Prestataire de services de paiement sécurisé (PSP) certifié PCI-DSS niveau 1.</li>
                    <li style="margin-bottom: 8px;"><strong>Google Ireland Ltd (Irlande) :</strong> Solutions d'analyse d'audience (Google Analytics) et services de cartographie (Google Maps).</li>
                </ul>
                <p style="margin-top: 15px;">
                    Aucune donnée n'est vendue, louée ou commercialisée à des tiers à des fins publicitaires sans votre consentement explicite préalable.
                </p>
            </section>

            <section style="margin-bottom: 50px;">
                <h2 style="font-size: 20px; font-weight: 700; color: #2b323e; margin-bottom: 20px; border-left: 4px solid #ec5a13; padding-left: 16px;">ARTICLE 4. SÉCURITÉ ET CONSERVATION</h2>
                <p style="margin-bottom: 15px;">
                    Nous mettons en œuvre des mesures techniques et organisationnelles de pointe pour protéger vos données : chiffrement TLS 1.3 de tous les échanges, hachage des mots de passe via l'algorithme Argon2id, pseudonymisation des bases de données, et politique stricte de gestion des accès.
                </p>
                <p>
                    <strong>Durées de conservation :</strong>
                    <br>- <u>Compte actif :</u> Durée de la relation contractuelle.
                    <br>- <u>Compte inactif :</u> Suppression après 3 ans sans activité (Recommandation CNIL).
                    <br>- <u>Pièces d'identité :</u> Suppression immédiate et automatisée dès la validation du compte Hôte.
                    <br>- <u>Données comptables :</u> Archivage probatoire pendant 10 ans (Article L.123-22 du Code de commerce).
                </p>
            </section>
            
            <section>
                <h2 style="font-size: 20px; font-weight: 700; color: #2b323e; margin-bottom: 20px; border-left: 4px solid #ec5a13; padding-left: 16px;">ARTICLE 5. VOS DROITS</h2>
                <p>
                    Vous disposez des droits d'accès, de rectification, d'effacement (« droit à l'oubli »), de limitation, de portabilité et d'opposition. Pour exercer ces droits, veuillez adresser votre demande via notre formulaire de contact ou par courrier postal, accompagnée d'un justificatif d'identité si nécessaire. En cas de litige, vous avez le droit d'introduire une réclamation auprès de la Commission Nationale de l'Informatique et des Libertés (CNIL).
                </p>
            </section>
        </div>
    </div>
</div>
@endsection