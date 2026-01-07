@extends('layout')

@section('title', 'CGU - Leboncoin')

@section('content')
<div class="legal-wrapper">
    <div class="legal-card">
        <h1 style="font-size: 32px; font-weight: 800; color: #2b323e; margin-bottom: 10px;">Conditions Générales d'Utilisation et de Vente</h1>
        <p style="color: #717171; font-size: 14px; margin-bottom: 40px;">Dernière mise à jour juridique : 15 Décembre 2025</p>

        <div style="line-height: 1.8; color: #4a4a4a; text-align: justify; font-size: 15px;">
            <p style="margin-bottom: 30px;">
                Les présentes Conditions Générales d'Utilisation et de Vente (ci-après « CGUV ») ont pour objet de définir les modalités et conditions dans lesquelles la société Leboncoin SAS (ci-après « l'Opérateur ») met à la disposition des utilisateurs (ci-après « les Utilisateurs ») sa plateforme numérique de location saisonnière. L'accès et l'utilisation des services impliquent l'acceptation sans réserve des présentes CGUV par l'Utilisateur.
            </p>

            <section style="margin-bottom: 40px;">
                <h3 style="font-size: 18px; font-weight: 700; color: #2b323e; border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px;">ARTICLE 1. DÉFINITIONS</h3>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 10px;"><strong>« Hôte » :</strong> désigne toute personne physique ou morale proposant à la location un bien immobilier via la Plateforme.</li>
                    <li style="margin-bottom: 10px;"><strong>« Voyageur » :</strong> désigne toute personne physique effectuant une réservation de séjour via la Plateforme.</li>
                    <li style="margin-bottom: 10px;"><strong>« Annonce » :</strong> désigne l'ensemble des éléments (textes, photos, disponibilités, prix) publiés par l'Hôte pour décrire le bien loué.</li>
                    <li style="margin-bottom: 10px;"><strong>« Frais de Service » :</strong> désigne la commission perçue par l'Opérateur en rémunération de ses services d'intermédiation.</li>
                </ul>
            </section>

            <section style="margin-bottom: 40px;">
                <h3 style="font-size: 18px; font-weight: 700; color: #2b323e; border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px;">ARTICLE 2. DESCRIPTION DES SERVICES</h3>
                <p>
                    L'Opérateur fournit une plateforme technique de mise en relation. Il n'est pas partie au contrat de location conclu directement entre l'Hôte et le Voyageur. L'Opérateur ne saurait être qualifié d'agent immobilier, d'administrateur de biens ou de loueur. Son rôle se limite à l'hébergement des Annonces, à la fourniture d'outils de recherche et de réservation, et à la sécurisation des flux financiers via un Prestataire de Services de Paiement (PSP) agréé.
                </p>
            </section>

            <section style="margin-bottom: 40px;">
                <h3 style="font-size: 18px; font-weight: 700; color: #2b323e; border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px;">ARTICLE 3. DISPOSITIONS FINANCIÈRES</h3>
                
                <h4 style="font-size: 16px; font-weight: 700; color: #2b323e; margin-bottom: 10px;">3.1. Prix de la Location et Frais de Service</h4>
                <p style="margin-bottom: 15px;">
                    Le prix de la location est fixé librement par l'Hôte. En sus de ce prix, l'Opérateur facture au Voyageur des <strong>Frais de Service correspondants à 10% TTC</strong> du montant total de la réservation (hors taxe de séjour). Ces frais couvrent les coûts de maintenance technique, de sécurisation des paiements, d'assistance utilisateur et d'assurance annulation basique.
                </p>

                <h4 style="font-size: 16px; font-weight: 700; color: #2b323e; margin-bottom: 10px;">3.2. Taxe de Séjour</h4>
                <p style="margin-bottom: 15px;">
                    Conformément à l'article L. 2333-29 du Code général des collectivités territoriales, l'Opérateur collecte la taxe de séjour au réel pour le compte des Hôtes non professionnels dans les communes l'ayant instituée. Cette taxe est prélevée lors du paiement de la réservation et reversée périodiquement aux trésoreries communales compétentes.
                </p>

                <h4 style="font-size: 16px; font-weight: 700; color: #2b323e; margin-bottom: 10px;">3.3. Modalités de Paiement et Séquestre</h4>
                <p>
                    Le paiement s'effectue par carte bancaire (Visa, MasterCard, CB). Les fonds versés par le Voyageur sont placés sur un compte de cantonnement (séquestre) géré par le PSP jusqu'à 24 heures après l'arrivée effective dans les lieux. Ce mécanisme vise à protéger le Voyageur en cas d'annulation de dernière minute ou de non-conformité substantielle du bien.
                </p>
            </section>

            <section style="margin-bottom: 40px;">
                <h3 style="font-size: 18px; font-weight: 700; color: #2b323e; border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px;">ARTICLE 4. RESPONSABILITÉ ET OBLIGATIONS</h3>
                <p style="margin-bottom: 15px;">
                    <strong>Obligations de l'Hôte :</strong> L'Hôte garantit que le bien proposé est conforme aux normes de décence et de sécurité en vigueur. Il s'engage à ne pas publier d'Annonce mensongère. Il reconnaît devoir se conformer à la réglementation locale (déclaration en mairie, changement d'usage, numéro d'enregistrement).
                </p>
                <p style="margin-bottom: 15px;">
                    <strong>Vérification d'identité :</strong> Pour des raisons de sécurité, l'Hôte accepte de se soumettre à une procédure de vérification d'identité (KYC) avant toute transaction, impliquant la transmission d'un document officiel d'identité.
                </p>
                <p>
                    <strong>Responsabilité de l'Opérateur :</strong> En sa qualité d'hébergeur de contenu (Article 6-I-2 de la LCEN), l'Opérateur ne peut être tenu responsable des contenus publiés par les Utilisateurs, sauf s'il n'a pas agi promptement pour les retirer après avoir été notifié de leur caractère illicite. L'Opérateur décline toute responsabilité en cas d'interruption temporaire du service pour maintenance.
                </p>
            </section>

            <section>
                <h3 style="font-size: 18px; font-weight: 700; color: #2b323e; border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px;">ARTICLE 5. DROIT APPLICABLE ET JURIDICTION</h3>
                <p>
                    Les présentes CGUV sont soumises au droit français. En cas de litige relatif à l'interprétation ou à l'exécution des présentes, et à défaut d'accord amiable suite à une médiation, les tribunaux du ressort de la Cour d'Appel de Paris seront seuls compétents, nonobstant pluralité de défendeurs ou appel en garantie.
                </p>
            </section>
        </div>
    </div>
</div>
@endsection