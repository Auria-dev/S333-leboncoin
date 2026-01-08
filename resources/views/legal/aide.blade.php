
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title></title>

<style>
    :root {
  --transition-aide: background .25s, color .25s;
  --bg-card: #fffaf6;
  --text-main: #4A3B32;
  --primary-hover: #F27A55;
  --input-placeholder: #ccc;
  --bg-body: #FFF8F5;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html {
    text-rendering: optimizeLegibility;
    font-family: 'Outfit', system-ui, -apple-system, sans-serif;
}

body {
  display: flex;
  justify-content: center; 
  align-items: center;     
  height: 100vh;
  margin: 0;
  background-color: var(--bg-body);
}

.aide-menu {
  width: 80vw;            
  height: auto;            
  max-height: 90vh;        
  background: var(--bg-card);
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); 
  border-radius: 8px;
}

.aide-menu ul {
  list-style: none;
}


.aide-menu li > a {
  text-decoration: none;
  text-transform: uppercase;
  display: block; 
  color: var(--text-main);
  transition: var(--transition-aide);
  font-weight: 600;
  font-size: 20px;
  letter-spacing: 0.02em;
}

.aide-menu nav > ul > li > a {
  line-height: 60px;
  border-bottom: 1px solid var(--input-placeholder);
  padding-left: 30px;
  position: relative;
}

.aide-menu nav > ul > li > a:hover {
  background-color: var(--primary-hover);
  color: var(--bg-card) !important;
}

.aide-menu ul ul {
  display: none; 
  border-top: 1px solid var(--input-placeholder);
}

.aide-menu ul ul li {
  border-bottom: 1px solid var(--input-placeholder);
}

.aide-menu ul ul li > a {
  padding-left: 40px;
  line-height: 40px;
  text-transform: none;
  font-size: 18px;
  letter-spacing: 0.02em;
  font-weight: 400;
}

.aide-menu ul ul li > a:hover, 
.aide-menu ul ul li a.active-link {
  background-color: var(--primary-hover);
  color: var(--bg-card);
}

.aide-text {
  display: none; 
  background-color: var(--bg-card);
  padding: 15px 30px;
  color: var(--text-main);
  font-size: 18px;
  line-height: 1.6;
  font-weight: 400;
}

.aide-menu .aide-text a {
  display: inline !important; 
  text-transform: none !important; 
  text-decoration: underline !important; 
  color: var(--text-main) !important;
  font-size: 18px !important;
  padding: 0 !important; 
  background: none !important; 
  letter-spacing: normal !important;
  transition: color 0.2s;
}

.aide-menu .aide-text a:hover {
  color: var(--primary-hover) !important;
  text-decoration: none !important;
}

.caret:after {
  content: '▽';
  position: absolute;
  top: 0;
  right: 20px;
  color: inherit; 
}

.last-aide {
  border-bottom: none !important;
}

.aide-menu .aide-text img {
    display: block;          
    margin: 25px 0;         
    border-radius: 5px;    
    border: 1px solid var(--input-placeholder); 
    max-width: 60%;        
    max-height: 400px;      
    width: auto;       
    height: auto;           
    object-fit: contain;    
}

.aide-menu .aide-text div {
    display: flex;
    gap: 20px;
    align-items: flex-start; 
    margin: 25px 0;          
}

.aide-menu .aide-text div img {
    display: inline-block; 
    margin: 0; 
    max-width: calc(50% - 10px); 
    max-height: 400px;
    width: auto;
    height: auto;
}

h2 {
    margin-bottom: 10px;
    font-size: 30px;
}

h3 {
    margin-bottom: 5px;
    margin-top: 40px;
    font-size: 25px;
}

.gras {
    font-size: 20px;
    font-weight: 600;
}

</style>
</head>
<body>
  <div class="aide-menu">
	<nav>
		<ul>
			<li>
				<a>Mon compte</a>
				<ul>
					<li><a>Comment s’inscrire sur le site leboncoin ?</a>
						<div class="aide-text">
							<h2>Comment s’inscrire sur le site leboncoin ?</h2>
							<p class="gras">Vous avez le choix entre 2 options :</p>
							<p>- <a href="#aide-mail">Option 1 : saisir votre adresse mail</a></p>
							<p>- <a href="#aide-google">Option 2 : se connecter avec Google</a></p>
							<h3 id="aide-mail">Option 1 : saisir votre adresse mail</h3>
							<p>1. Cliquez sur le bouton « Se connecter » en haut à droite ;</p>
							<img src="/Leboncoin/Compte/creation-btn-connexion.png" alt="bouton connexion">
							<p>2. Cliquez sur le lien « Créez-en un ici » en bas ;</p>
							<img src="/Leboncoin/Compte/creation-lien.png" alt="lien création de compte">
							<p class="gras" style="margin-top: 10px;">Si vous êtes un particulier :</p>
                            <p>Par défaut le type de compte est de type particulier.</p>
							<p>1. Vérifiez que le type de compte est « particulier », si ce n’est pas le cas, cliquez sur le bouton « Particulier » ;</p>
							<img src="/Leboncoin/Compte/compte-particulier.png" alt="bouton type de compte particulier">
							<p>2. Entrez les informations obligatoires (prénom, nom, email, numéro de téléphone, adresse et mot de passe).</p>
							<p>3. Optionnel : vous pouvez transmettre votre pièce d’identité dès la création de votre compte afin de faire vérifier votre identité et pouvoir déposer des annonces. Pour transmettre votre pièce d’identité ultérieurement, suivez les étapes <a href="aide-verif-id">« comment vérifier mon identité ? »</a>.</p>
							<p>4. Pour finaliser la création de votre compte, cliquez sur le bouton « S’inscrire » en bas.</p>
							<p class="gras" style="margin-top: 15px;">Si vous êtes une entreprise :</p>
							<p>1. Cliquez sur le bouton « Entreprise » </p>
							<img src="/Leboncoin/Compte/compte-entreprise.png" alt="bouton type de compte entreprise">
							<p>2. Entrez les informations obligatoires (prénom, nom, email, numéro de téléphone, adresse, numéro de SIRET, secteur d’activité et mot de passe). Les informations obligatoires sont désignées par un astérisque « * » ; </p>
							<p>3. Pour finaliser la création de votre compte, cliquez sur le bouton « S’inscrire » en bas. </p>
							<h3 id="aide-google">Option 2 : se connecter avec Google</h3>
							<p>1. Cliquez sur le bouton « Se connecter avec Google » ou « S’inscrire avec Google » en passant par le lien « Créez-en un ici » en bas ;</p>
							<div>
								<img src="/Leboncoin/Compte/creation-google.png" alt="bouton se connecter avec Google">
								<img src="/Leboncoin/Compte/google-inscription.png" alt="bouton s'inscrire avec Google">
							</div>
							<p>2. Saisissez votre adresse mail Google, ou sélectionnez votre compte, puis cliquez sur le bouton « Suivant » en bas à droite ;</p>
							<img src="/Leboncoin/Compte/email-google.png" alt="champ saisie mail Google">
							<p>3. Entrez votre mot de passe, puis cliquez sur le bouton « Suivant ».</p>
							<img src="/Leboncoin/Compte/mdp-google.png" alt="champ saisie mot de passe compte Google">
						</div>
          			</li>
					<li><a>Comment me connecter à mon compte ?</a>
						<div class="aide-text">
							<h2>Comment me connecter à mon compte ?</h2>
							<p>Si vous avez créé votre compte à partir d’une adresse mail, suivez ces étapes :</p>
							<p>1. Saisissez l’adresse mail choisie lors de la création ;</p>
							<p>2. Saisissez le mot de passe ;</p>
							<img src="/Leboncoin/Compte/champ-saisie-id.png" alt="champ saisie mail et mot de passe">
							<p>3. Cliquez sur le bouton « Se connecter ».</p>
							<p>Si vous avez créé votre compte en cliquant sur « Se connecter avec Google », suivez ces étapes :</p>
							<p>1. Cliquez sur « Se connecter avec Google ;</p>
							<img src="/Leboncoin/Compte/creation-google.png" alt="bouton se connecter avec Google">
							<p>2. Sélectionnez le même compte que celui choisi lors de la création ;</p>
							<p>3. Cliquez sur le bouton « Se connecter ».</p>
							<p>4. Si vous avez <a href="aide-activ-auth">activé la double authentification</a>, vous devrez  scanner le QR Code avec l’application choisie et saisir le code à 6 chiffres.</p>
						</div>
					</li>
					<li><a>Qu'est ce que l'espace compte et comment y accéder ?</a>
						<div class="aide-text">
							<h2>Qu'est ce que l'espace compte ?</h2>
							<p>L'espace compte est l'espace regroupant les services proposés par leboncoin :</p>
							<p>- Gestion de vos informations personnelles ;</p>
							<p>- Gestion de vos annonces ;</p>
							<p>- Gestion de vos locations ;</p>
							<p>- Gestion de vos locations ;</p>
							<p>- Gestion de vos réservations ;</p>
							<p>- Gestion de vos incidents ;</p>
							<p>- Accès à la messagerie leboncoin ;</p>
							<p>- Sauvegarder des recherches ;</p>
							<p>- Ajouter des annonces en favoris ;</p>
							<h2>Comment accéder à mon espace compte ?</h2>
							<p>1. Connectez-vous à votre compte leboncoin ;</p>
							<p>2. Cliquez sur le bouton « Mon compte » en haut à droite pour accéder à votre espace compte.</p>
							<img src="/Leboncoin/Compte/btn-compte.png" alt="bouton espace compte">
						</div>
					</li>
					<li><a>Je souhaite modifier mon mot de passe que dois-je faire ?</a>
						<div class="aide-text">
							<h2>Je souhaite modifier mon mot de passe que dois-je faire ?</h2>
							<p>1. Connectez-vous à votre compte leboncoin ;</p>
							<p>2. Cliquez sur le bouton « Mon compte » en haut à droite pour accéder à votre espace compte ;</p>
							<p>3. Cliquez sur le bouton « Modifier mon compte »</p>
							<img src="/Leboncoin/Compte/btn-modif.png" alt="bouton modifier compte">
							<p>4. Défilez la page vers le bas jusqu’au champ « Sécurité », puis cliquez sur « Modifier le mot de passe » ;</p>
							<img src="/Leboncoin/Compte/modif-mdp-lien.png" alt="lien modifier mot de passe">
							<p>5. Saisissez votre nouveau mot de passe ;</p>
							<p>6. Pour finaliser, cliquez sur le bouton « Enregistrer les modifications » en bas à droite.</p>
						</div>
					</li>
					<li><a>Où puis-je modifier mes informations personnelles ?</a>
						<div class="aide-text">
							<h2>Où puis-je modifier mes informations personnelles ?</h2>
							<p>1. Connectez-vous à votre compte leboncoin ;</p>
							<p>2. Accédez à votre espace compte ;</p>
							<p>3. Cliquez sur le bouton « Modifier mon compte » ;</p>
							<img src="/Leboncoin/Compte/btn-modif.png" alt="bouton modifier compte">
							<p>4. Cliquez sur le crayon à droite, dans les champs que vous souhaitez modifier, et saisissez vos nouvelles informations ;</p>
							<img src="/Leboncoin/Compte/modif-info.png" alt="bouton modifier champs">
							<p>5. Pour finaliser, cliquez sur le bouton « Enregistrer les modifications » en bas à droite.</p>
						</div>
					</li>
					<li><a>Comment modifier ma photo de profil ?</a>
						<div class="aide-text">
							<h2>Comment modifier ma photo de profil ?</h2>
							<p>1. Connectez-vous à votre compte leboncoin ;</p>
							<p>2. Accédez à votre espace compte ;</p>
							<p>3. Cliquez sur le bouton « Modifier mon compte » ;</p>
							<img src="/Leboncoin/Compte/btn-modif.png" alt="bouton modifier compte">
							<p>4. Cliquez sur le bouton « Choisir » et sélectionnez une image ;</p>
							<img src="/Leboncoin/Compte/choisir-pdp.png" alt="bouton choisir photo de profil">
							<p>5. Pour finaliser, cliquez sur le bouton « Modifier ». </p>
							<img src="/Leboncoin/Compte/modif-pdp.png" alt="bouton modifier photo de profil">
						</div>
					</li>
					<li><a>Dois-je m’inscrire pour utiliser leboncoin ?</a>
						<div class="aide-text">
							<h2>Dois-je m’inscrire pour utiliser leboncoin ?</h2>
							<p class="gras">Vous êtes un particulier et vous souhaitez :</p>
							<p>- Réserver un séjour</p>
							<p>- Ajouter des annonces aux favoris</p>
							<p>- Sauvegarder des recherches</p>
							<p>- Déposer une annonce </p>
							<p>- Utiliser la messagerie leboncoin</p>
							<p>Alors il sera nécessaire de créer un compte leboncoin. Il vous sera demandé vos noms, adresse email et numéro de téléphone. Pour déposer une annonce il vous sera également demandé de transmettre une pièce d’identité.</p>
							<p class="gras">Vous êtes une entreprise :</p>
							<p>Vous devrez impérativement créer un compte entreprise leboncoin pour déposer des annonces.</p>
						</div>
					</li>
					<li><a>Comment activer l’authentification à 2 facteurs ?</a>
						<div class="aide-text">
							<h2>Comment activer l’authentification à 2 facteurs ?</h2>
							<p>1. Connectez-vous à votre compte leboncoin ;</p>
							<p>2. Cliquez sur le bouton « Mon compte » en haut à droite pour accéder à votre espace compte ;</p>
							<p>3. Cliquez sur le bouton « Modifier mon compte »</p>
							<img src="/Leboncoin/Compte/btn-modif.png" alt="bouton modifier compte">
							<p>4. Défilez la page vers le bas jusqu’au champ « Sécurité du compte », puis cliquez sur le bouton « Configurer la 2FA » ;</p>
							<img src="/Leboncoin/Compte/active-2fa.png" alt="bouton activer 2fa">
							<p>5. Suivez les étapes de configuration : </p>
							<p>- Installez l'application Google Authenticator ou Authy sur votre téléphone ;</p>
							<img src="/Leboncoin/Compte/appli-auth.png" alt="application Google Authenticator">
							<p>- Ouvrez l'application et Scannez le QR Code qui s'affichera sur votre ordinateur ;</p>
							<img src="/Leboncoin/Compte/debut-2fa.png" alt="QR Code Google Authenticator">
							<p>- Entrez le code à 6 chiffres affiché sur l'application;</p>
							<div>
								<img src="/Leboncoin/Compte/code-2fa.jpg" alt="code 2fa">
								<img src="/Leboncoin/Compte/saisie-code-2fa.png" alt="saisie code 2fa">
							</div>
							<p>- Pour finaliser l'activation, cliquez sur le bouton « Activer maintenant »</p>
							<p>Vous désactivez à tout moment l'authentification à 2 facteurs en retournant dans l'espace « Sécurité du compte », puis en cliquant sur le bouton « Désactiver »</p>
							<img src="/Leboncoin/Compte/statut-2fa.png" alt="supprimer 2fa">
						</div>
					</li>
					<li><a>Comment supprimer l’authentification à 2 facteurs ?</a>
						<div class="aide-text">
							<h2>Comment supprimer l’authentification à 2 facteurs ?</h2>
							<p>1. Connectez-vous à votre compte leboncoin ;</p>
							<p>2. Cliquez sur le bouton « Mon compte » en haut à droite pour accéder à votre espace compte ;</p>
							<p>3. Cliquez sur le bouton « Modifier mon compte »</p>
							<img src="/Leboncoin/Compte/btn-modif.png" alt="bouton modifier compte">
							<p>4. Défilez la page vers le bas jusqu’au champ « Sécurité du compte », puis cliquez sur le bouton « Désactiver » ;</p>
							<img src="/Leboncoin/Compte/statut-2fa.png" alt="supprimer 2fa">
						</div>
					</li>
					<li><a>Comment fonctionne l’authentification à 2 facteurs ?</a>
						<div class="aide-text">
							<h2>Comment fonctionne l’authentification à 2 facteurs ?</h2>
							<p>La validation en deux étapes ajoute une "double serrure" à votre compte. Pour vous connecter, il ne suffira plus de connaître votre mot de passe, il faudra aussi connaître la clé secrète.</p>
							<p>Lors de votre prochaine connexion : </p>
							<p>1. Connectez-vous à votre compte leboncoin ;</p>
							<p>2. Il vous serez demandé de saisir le code à 6 chiffres envoyé par l'application ;</p>
							<p>3. Ouvrez l'application sur votre téléphone ;</p>
							<p>4. Recopiez le code à 6 chiffres ;</p>
							<p>5. Pour finaliser, cliquez sur le bouton « Valider la connexion » ;</p>
						</div>
					</li>
					<li><a>Comment utiliser ma messagerie ?</a>
						<div class="aide-text">
							<h2>Comment utiliser ma messagerie ?</h2>
							<p class="gras">Vous avez 3 options :</p>
							<p>- <a href="#aide-msg-direct">Option 1 : accès direct</a></p>
							<p>- <a href="#aide-msg-resa">Option 2 : Locataire : accès via une réservation</a></p>							
							<p>- <a href="#aide-msg-loc">Option 3 : Propriétaire : accès via une location</a></p>							
							<h3 id="aide-msg-direct">Option 1 : accès direct</h3>
							<p>1. Connectez-vous à votre compte leboncoin ;</p>
							<p>2. Cliquez sur le bouton « Messagerie » en haut au milieu ;</p>
							<p>Si aucune discussion n'est en cours, suivez les <a href="#aide-msg-resa">étapes de l'option 2</a> ou <a href="#aide-msg-loc">étapes de l'option 3</a>, sinon :</p>
							<p>3. Cliquez sur la discussion dans laquelle vous souhaitez envoyer un message ;</p>
							<p>4. Saisissez votre message ;</p>
							<p>7. Pour finaliser, cliquez sur le bouton « Envoyer » en bas à droite.</p>
							<h3 id="aide-msg-resa">Option 2 : Locataire : accès via une réservation</h3>
							<p>1. Connectez-vous à votre compte leboncoin ;</p>
							<p>2. Accédez à votre espace compte ;</p>
							<p>3. Dans l'espace « Mes réservations », cliquez sur la réservation dont vous souhaitez contacter le propriétaire ;</p>
							<p>4. Cliquez sur le bouton « Contacter Prénom Nom » ;</p>
							<img src="/Leboncoin/Compte/contact-resa.png" alt="bouton contacter depuis réservation">
							<p>5. La messagerie s'ouvre, saisissez votre message ;</p>
							<p>6. Pour finaliser, cliquez sur le bouton « Envoyer » en bas à droite.</p>
							<img src="/Leboncoin/Location/message.png" alt="messagerie leboncoin">
							<h3 id="aide-msg-loc">Option 3 : Propriétaire : accès via une location</h3>
							<p>1. Connectez-vous à votre compte leboncoin ;</p>
							<p>2. Accédez à votre espace compte ;</p>
							<p>3. Dans l'espace « Mes locations », cliquez sur la location dont vous souhaitez contacter le locataire ;</p>
							<p>4. Cliquez sur le bouton « Contacter Prénom Nom » ;</p>
							<img src="/Leboncoin/Compte/contact-loc.png" alt="bouton contacter depuis location">
							<p>5. La messagerie s'ouvre, saisissez votre message ;</p>
							<p>6. Pour finaliser, cliquez sur le bouton « Envoyer » en bas à droite.</p>
							<img src="/Leboncoin/Location/message.png" alt="messagerie leboncoin">
						</div>
					</li>
				</ul>
			</li>
			<li>
				<a>Mes annonces</a>
				<ul>
					<li><a>Propriétaire Particulier & Entreprise : comment déposer une annonce ?</a>
						<div class="aide-text">
							<h2>Propriétaire Particulier & Entreprise : comment déposer une annonce ?</h2>
							<p>1. Connectez-vous à votre compte leboncoin ;</p>
							<p>2. Cliquez sur le bouton « Déposer une annonce » en haut au milieu ;</p>
							<img src="/Leboncoin/Annonce/btn-deposer.png" alt="bouton déposer annonce">
							<p>3. Si vous n’avez jamais transmis votre pièce d’identité, il vous sera demandé de la transmettre à cette étape-là ;</p>
							<p>4. Sélectionnez une ou plusieurs photos ;</p>
							<p>5. Choisissez le titre de l’annonce ;</p>
							<p>6. Complétez les différents champs ;</p>
							<p>Pour sélectionner un type d'hébergement :</p>
							<p>- Cliquez sur la flèche à droite dans le champ ;</p>
							<img src="/Leboncoin/Annonce/select-heb.png" alt="sélectionner type d'hébergement">
							<p>- Cliquez sur le type d'hébergement de votre bien.</p>
							<p>Attention : veillez à bien sélectionner le type d'hébergement correspondant à votre bien, s'il ne figure pas dans la liste, choisissez « Autre »</p>
							<img src="/Leboncoin/Annonce/autre-type-heb.png" alt="sélectionner type d'hébergement autre">
							<p>Pour sélectionner l'adresse de votre bien :</p>
							<p>- Commencez à saisir l'adresse ;</p>
							<p>- Lorsque vous voyez votre adresse s'affichée, cliquez dessus pour la sélectionner.</p>
							<img src="/Leboncoin/Annonce/select-adresse.png" alt="sélectionner adresse">
							<p>Pour sélectionner les équipement et les services proposés :</p>
							<p>- Cliquez sur l'équipement, ou le service, il devient orange lorsqu'il est sélectionné, pour le désélectionner, re-cliquiez dessus.</p>
							<div>
								<img src="/Leboncoin/Annonce/select-eq.png" alt="sélectionner équipement">
								<img src="/Leboncoin/Annonce/select-serv.png" alt="sélectionner service">
							</div>
							<p>7. Complétez la description. Elle doit être lisible et contenir toutes les informations utiles pour la location de votre bien.</p>
							<p>8. Pour finaliser, cliquez sur le bouton « Déposer » en bas ;</p>
							<p>9. Si votre numéro de téléphone n’est pas encore vérifié, une vérification vous sera envoyée par SMS à cette étape-là.  </p>
						</div>
					</li>
					<li><a>Propriétaire Particulier : comment vérifier mon identité ?</a>
						<div class="aide-text">
							<h2>Propriétaire Particulier : comment vérifier mon identité ?</h2>
							<p class="gras">Vous avez le choix entre 3 options :</p>
							<p>- <a href="#aide-cni-creation">Option 1 : transmettre votre pièce d’identité dès la création de votre compte</a></p>
							<p>- <a href="#aide-cni-compte">Option 2 : transmettre votre pièce d’identité depuis votre espace personnel</a></p>
							<p>- <a href="#aide-cni-deposer">Option 3 : transmettre votre pièce d’identité lors du premier dépôt d’une annonce</a></p>
							<h3 id="aide-cni-creation">Option 1 : transmettre votre pièce d’identité dès la création de votre compte</h3>
							<p>Lors de la création de votre compte, en bas, avant le bouton « S’inscrire », un champ est dédié à la transmission de votre pièce d’identité.</p>
							<p>1. Cliquez sur le bouton « Sélectionner un fichier » ;</p>
							<img src="/Leboncoin/Identite/cni-form.png" alt="transmettre CNI dès inscription">
							<p>2. Sélectionnez le fichier correspond à votre pièce d’identité. Attention, vous ne pouvez transmettre qu’un seul fichier et il doit être au format PDF.</p>
							<p>3. Complétez le reste de champs, puis cliquez sur le bouton « S’inscrire » pour finaliser.</p>
							<h3 id="aide-cni-compte">Option 2 : transmettre votre pièce d’identité depuis votre espace compte</h3>
							<p>1. Connectez-vous à votre compte leboncoin ;</p>
							<p>2. Cliquez sur le bouton « Mon compte » en haut à droite pour accéder à votre espace personnel ;</p>
							<p>3. Cliquez sur le bouton « Modifier mon compte »</p>
							<p>4. Défilez la page vers le bas jusqu’au champ « Transmettre votre pièce d’identité sous forme de PDF », puis cliquez sur « Sélectionner un fichier » ;</p>
							<img src="/Leboncoin/Identite/cni-compte.png" alt="transmettre CNI depuis espace compte">
							<p>Si ce champ n’apparaît pas, c’est que vous avez déjà transmis votre pièce d’identité.</p>
							<p>5. Sélectionnez le fichier correspond à votre pièce d’identité. Attention, vous ne pouvez transmettre qu’un seul fichier et il doit être au format PDF ;</p>
							<p>6. Pour finaliser, cliquez sur le bouton « Enregistrer les modifications ».</p>
							<h3 id="aide-cni-deposer">Option 3 : transmettre votre pièce d’identité lors du premier dépôt d’une annonce</h3>
							<p>1. Connectez-vous à votre compte leboncoin ;</p>
							<p>2. Cliquez sur le bouton « Déposer une annonce » en haut au milieu ;</p>
							<p>3. Une fenêtre s’ouvrira pour vous demander de transmettre votre pièce d’identité. Cliquez sur le bouton « Sélectionner un fichier » ;</p>
							<img src="/Leboncoin/Identite/cni-deposer.png" alt="transmettre CNI avant dépot annonce">
							<p>Si aucune fenêtre ne s’ouvre, c’est que vous avez déjà transmis votre pièce d’identité.</p>
							<p>4. Sélectionnez le fichier correspond à votre pièce d’identité. Attention, vous ne pouvez transmettre qu’un seul fichier et il doit être au format PDF ;</p>
							<p>5. Pour finaliser, cliquez sur le bouton « Transmettre et continuer ».</p>
						</div>
					</li>
					<li><a>Propriétaire Particulier : en quoi consiste la vérification d’identité ?</a>
						<div class="aide-text">
							<h2>Propriétaire Particulier : en quoi consiste la vérification d’identité ?</h2>
							<p>La vérification d’identité consiste à identifier et vérifier l'identité de tout utilisateur ayant recours aux services leboncoin.</p>
							<p>Chaque utilisateur souhaitant en bénéficier doit transmettre une pièce d’identité.</p>
							<p>Le service Petite Annonce se charge de vérifier l’identité des utilisateurs en analysant la pièce d’identité transmise afin d’éviter toute fraude ou utilisateur suspect.</p>
							<p>Le service Petite Annonce vérifie la cohérence entre le nom, le prénom et l’adresse renseignés sur leboncoin et présents sur la pièce d’identité.</p>
						</div>
					</li>
					<li><a>Propriétaire Particulier & Entreprise : que mettre dans le texte de mon annonce ?</a>
						<div class="aide-text">
							<h2>Propriétaire Particulier & Entreprise : que mettre dans le texte de mon annonce ?</h2>
							<p>Afin que les utilisateurs disposent d'un maximum d'informations, nous vous conseillons de détailler précisément le bien mis en location dans le texte de votre annonce.</p>
							<p>Choisissez avec soin le titre de votre annonce et renseignez l’ensemble des cri-tères proposés.</p>
							<p>Lors du dépôt, des bulles d’informations vous indiquent les éléments à fournir pour réussir le dépôt d’annonce.</p>
						</div>
					</li>
          			<li><a>Propriétaire Particulier & Entreprise : comment insérer des photos dans mon annonce ?</a>
						<div class="aide-text">
							<h2>Propriétaire Particulier & Entreprise : comment insérer des photos dans mon annonce ?</h2>
							<p>1. Cliquez sur « Choisir des images » en haut du formulaire de la création d’une annonce ;</p>
							<img src="/Leboncoin/Annonce/btn-choisir-img-annonce.png" alt="bouton choisir photos">
							<p>2. Choisissez les images correspondant à votre bien ;</p>
							<p>3. Complétez le reste des champs,  puis cliquez sur « Déposer » en bas, pour finaliser.</p>
						</div>
					</li>
					<li><a>Propriétaire Particulier & Entreprise : quelle règles doit respecter mon annonce ?</a>
						<div class="aide-text" id="aide-regle">
							<h2>Propriétaire Particulier & Entreprise : quelle règles doit respecter mon annonce ?</h2>
							<h3>Contenu</h3>
							<p>Le contenu de votre annonce doit respecter les règles suivantes :</p>
							<p>- L’annonce doit être rédigée en français (obligation légale imposée par la loi n°94-345 du 4 août 1994) ;</p>
							<p>- Être en lien avec le bien proposé ;</p>
							<p>- Ne pas comporter de numéro de téléphone ou d’adresse mail (ni dans le titre, ni dans la description, ni sur une photo) ;</p>
							<p>- Ne pas comporter de numéro de téléphone surtaxé ;</p>
							<p>- Ne présenter aucun élément sectaire, discriminatoire, sexiste, ou en lien avec des organisations et des personnes responsables de crimes contre l’humanité.</p>
							<h3>Photo</h3>
							<p>- Les photos de votre annonce doivent être ajoutées au format JPG, PNG ou JPEG et leur poids ne doit pas dépasser 2048Ko soit 2Mo. Elles doivent illustrer votre bien et ne pas être déjà utilisées sur une autre de vos annonces.</p>
							<p>Les photos qui comportent les éléments suivants ne sont pas acceptées et peuvent empêcher le dépôt de votre annonce :</p>
							<p>- Enfants mineurs ;</p>
							<p>- Numéro de téléphone ou adresse email ;</p>
							<p>- Représentations sans lien avec l’offre proposée ;</p>
							<p>- Pornographie, nudité, tout autre contenu pouvant heurter la sensibilité de tout utilisateur.</p>
							<p></p>
						</div>
					</li>
					<li><a>Propriétaire Particulier & Entreprise : comment modifier mon annonce ?</a>
						<div class="aide-text" id="aide-modif-annonce">
							<h2>Propriétaire Particulier & Entreprise : comment supprimer mon annonce ?</h2>
							<p>1. Connectez-vous à votre compte leboncoin ;</p>
							<p>2. Accédez à votre espace compte ;</p>
							<p>3. Accédez à l'espace « Mes annonces » ;</p>
							<p>4. Pour modifier votre annonce, cliquez sur le crayon dans le coin haut gauche de l'annonce.</p>
							<p>5. Le formulaire de création d'une annonce s'affiche avec les champs remplis par les informations actuelles ;</p>
							<p>6. Cliquez sur le crayon à droite, dans les champs que vous souhaitez modifier, et saisissez les nouvelles informations ;</p>
							<p>7. Si vous souhaitez modifier les photos, suivies les <a href="#aide-modif-img-annonce">étapes pour modifier les photos d'une annonce</a> ;</p>
							<p>7. Pour finaliser, cliquez sur le bouton « Déposer » en bas.</p>
						</div>
					</li>
					<li><a>Propriétaire Particulier & Entreprise : comment modifier les photos de mon annonce ?</a>
						<div class="aide-text" id="aide-modif-img-annonce">
							<h2>Propriétaire Particulier & Entreprise : comment modifier les photos de mon annonce ?</h2>
							<p>1. Connectez-vous à votre compte leboncoin ;</p>
							<p>2. Accédez à votre espace compte ;</p>
							<p>3. Accédez à l'espace « Mes annonces » ;</p>
							<p>4. Pour modifier votre annonce, cliquez sur le crayon dans le coin haut gauche de l'annonce.</p>
							<p>5. Le formulaire de création d'une annonce s'affiche avec les informations actuelles ;</p>
							<p>6. Si vous souhaitez ajouter des photos :</p>
							<p>- Cliquez sur « Ajouter des images » en haut du formulaire ;</p>
							<p>- Choisissez les images correspondant à votre bien ;</p>
							<p>7. Si vous souhaitez supprimer des photos :</p>
							<p>- Cliquez sur la croix dans le coin haut droit des photos ;</p>
							<p>8. Pour finaliser, cliquez sur le bouton « Déposer » en bas.</p>
						</div>
					</li>
					<li><a>Propriétaire Particulier & Entreprise : comment supprimer mon annonce ?</a>
						<div class="aide-text" id="aide-supp-annonce">
							<h2>Propriétaire Particulier & Entreprise : comment supprimer mon annonce ?</h2>
							<p>1. Connectez-vous à votre compte leboncoin ;</p>
							<p>2. Accédez à votre espace compte ;</p>
							<p>3. Accédez à l'espace « Mes annonces » ;</p>
							<p>4. Pour supprimer votre annonce, cliquez sur la corbeille dans le coin haut droit de l'annonce.</p>
							<p>Attention : une fois supprimée, l'action est irréversible, vous serez obligé de recréer l'annonce si vous souhaitez la remettre en location.</p>
						</div>
					</li>
					<li><a>Propriétaire Particulier & Entreprise : mon annonce a été refusée, que faire ?</a>
					<div class="aide-text">
							<h2>Propriétaire Particulier & Entreprise : mon annonce a été refusée, que faire ?</h2>
							<p>Suite à votre dépôt d’annonce, nos services ont refusé votre annonce. Nous vous invitons à prendre connaissance des <a href="#aide-regle">règles de diffusion</a> afin de comprendre les raisons du refus de publication de votre annonce.</p>
							<p>Si votre annonce a été refusée, vous pouvez la déposer à nouveau après avoir vérifié qu’elle respecte bien nos <a href="#aide-regle">règles de diffusion</a>.</p>
							<p>Pour ce faire, vous pouvez :</p>
							<p>- <a href="#aide-modif-annonce">Modifier votre annonce</a></p>
							<p>- <a href="#aide-supp-annonce">Supprimer votre et la recréer</a></p>							
						</div>
					</li>
				</ul>
			</li>
			<li>
				<a>Mes locations</a>
				<ul>
					<li><a>Propriétaire Particulier & Entreprise : où trouver le récapitulatif de mes locations ?</a>
						<div class="aide-text">
							<h2>Propriétaire Particulier & Entreprise : où trouver le récapitulatif de mes locations ?</h2>
							<p>1. Connectez-vous à votre compte leboncoin ;</p>
							<p>2. Accédez à votre espace compte ;</p>
							<p>3. Défilez la page vers le bas jusqu’à l'espace « Mes locations » ;</p>
							<img src="/Leboncoin/Location/espace-location.png" alt="espace location"/>
							<p>4. Pour voir plus de détails, cliquez sur la location qui vous intéresse.</p>
						</div>
					</li>
					<li><a>Propriétaire Particulier & Entreprise : comment accepter ou refuser une demande de location ?</a>
						<div class="aide-text">
							<h2>Propriétaire Particulier & Entreprise : comment accepter ou refuser une demande de location ?</h2>
							<p>1. Connectez-vous à votre compte leboncoin ;</p>
							<p>2. Accédez à votre espace compte ;</p>
							<p>3. Défilez la page vers le bas jusqu’à l'espace « Mes locations » ;</p>
							<p>4. Cliquez sur la location qui vous intéresse ;</p>
							<p>5. Pour accepter une demande de location, cliquez sur le bouton « Accepter », sinon sur le bouton « Refuser ».</p>
							<p>Attention, une fois le bouton cliqué vous ne pourrez pas revenir sur votre décision.</p>
							<img src="/Leboncoin/Location/action.png" alt="boutons action">
						</div>
					</li>
          			<li><a>Propriétaire Particulier & Entreprise : comment contacter le locataire ?</a>
						<div class="aide-text">
							<h2>Propriétaire Particulier & Entreprise : comment contacter le locataire ?</h2>
							<p>1. Cliquez sur la location dont vous souhaitez contacter le locataire ;</p>
							<p>2. Cliquez sur le bouton « Contacter Prénom Nom » ;</p>
							<img src="/Leboncoin/Location/contact.png" alt="bouton contacter locataire">
							<p>3. La messagerie s'ouvre, saisissez votre message ;</p>
							<p>7. Pour finaliser, cliquez sur le bouton « Envoyer » en bas à droite.</p>
							<img src="/Leboncoin/Location/message.png" alt="messagerie leboncoin">
						</div>
					</li>
				</ul>
			</li>
			<li>
				<a>Mes réservations</a>
				<ul>
					<li><a>Locataire : où trouver le récapitulatif de ma réservation ?</a>
						<div class="aide-text">
							<h2>Propriétaire Particulier & Entreprise : où trouver le récapitulatif de mes locations ?</h2>
							<p>1. Connectez-vous à votre compte leboncoin ;</p>
							<p>2. Accédez à votre espace compte ;</p>
							<p>3. Défilez la page vers le bas jusqu’à l'espace « Mes réservations » ;</p>
							<img src="/Leboncoin/Reservation/resa.png" alt="espace réservation">
							<p>4. Pour voir plus de détails, cliquez sur la réservation qui vous intéresse.</p>
						</div>
					</li>
					<li><a>Locataire : comment modifier ma réservation ?</a>
						<div class="aide-text">
							<h2>Locataire : comment modifier ma réservation ?</h2>
							<p>Vous pouvez modifier votre réservation uniquement si elle est en attente.</p>
							<p>1. Accédez à votre espace compte ;</p>
							<p>2. Défilez la page vers le bas jusqu’à l'espace « Mes réservations » ;</p>
							<p>3. Cliquez sur la réservation que vous souhaitez modifier ;</p>
							<p>4. Modifiez les éléments que vous souhaitez modifier ;</p>
							<p>Attention : vous ne pourrez pas modifier les éléments si les nouvelles informations ne respectent pas les limites fixées par le propriétaire.</p>
							<p>5. Cliquez sur le bouton « Enregistrer » en bas ;</p>
							<p>Un supplément peut s'ajouter, dans ce cas il vous sera demandé de le payer à cette étape-là.</p>
							<p>6. Choisissez votre carte de paiement ;</p>
							<p>7. Pour finaliser et payer, cliquez sur le bouton « Payer et mettre à jour ».</p>
							<img src="/Leboncoin/Reservation/supplement.png" alt="payer supllément réservation">
						</div>
					</li>
					<li><a>Locataire : comment annuler ma réservation ?</a>
						<div class="aide-text">
							<h2>Locataire : comment annuler ma réservation ?</h2>
							<p>Vous pouvez annuler votre réservation uniquement si elle est en attente.</p>
							<img src="/Leboncoin/Reservation/attente.png" alt="statut en attente réservation">
							<p>1. Accédez à votre espace compte ;</p>
							<p>2. Défilez la page vers le bas jusqu’à l'espace « Mes réservations » ;</p>
							<p>3. Cliquez sur la réservation que vous souhaitez annuler ;</p>
							<p>4. Cliquez sur le bouton « Annuler la demande » ;</p>
							<img src="/Leboncoin/Reservation/annuler.png" alt="bouton annuler réservation">
							<p>5. Une demande de confirmation s'affichera, cliquez sur « OK » pour annuler la demande. </p>
							<p>Attention : une fois cliqué sur « OK », la réservation sera définitivement annulée, vous ne pourrez pas revenir sur votre décision.</p>
						</div>
					</li>
					<li><a>Locataire : comment contacter le propriétaire ?</a>
						<div class="aide-text">
							<h2>Locataire : comment contacter le propriétaire ?</h2>
							<p>1. Cliquez sur la réservation dont vous souhaitez contacter le propriétaire ;</p>
							<p>2. Cliquez sur le bouton « Contacter Prénom Nom » ;</p>
							<img src="/Leboncoin/Compte/contact-resa.png" alt="bouton contacter propriétaire">
							<p>3. La messagerie s'ouvre, saisissez votre message ;</p>
							<p>4. Pour finaliser, cliquez sur le bouton « Envoyer » en bas à droite.</p>
							<img src="/Leboncoin/Location/message.png" alt="messagerie leboncoin">
						</div>
					</li>
					<li><a>Locataire : comment noter le propriétaire ?</a>
						<div class="aide-text">
							<h2>Locataire : comment noter le propriétaire ?</h2>
							<p>Vous pouvez noter le propriétaire une seule fois et uniquement si le séjour est terminé.</p>
							<img src="/Leboncoin/Reservation/valide.png" alt="statut validée réservation">
							<p>1. Accédez à votre espace compte ;</p>
							<p>2. Défilez la page vers le bas jusqu’à l'espace « Mes réservations » ;</p>
							<p>3. Cliquez sur le bouton « Noter ce séjour » situé sur la réservation que vous souhaitez noter ;</p>
							<img src="/Leboncoin/Reservation/noter.png" alt="bouton noter séjour">
							<p>4. Une page s'ouvrira, choisissez la note que vous souhaitez donner au propriétaire et saisissez votre commentaire ;</p>
							<p>5. Pour finaliser, cliquez sur le bouton « Envoyer mon avis »</p>
							<p>Avant d'être publié, votre avis sera analysé par les services leboncoin, vous serez informé lorsqu'il sera publié, s'il n'est pas refusé.</p>
							<div>
								<img src="/Leboncoin/Reservation/statut-avis.png" alt="statut vérification avis">
								<img src="/Leboncoin/Reservation/valide-avis.png" alt="statut publié avis">
							</div>
						</div>
					</li>
				</ul>
			</li>
      <li>
				<a>Réserver un séjour</a>
				<ul>
					<li><a>Locataire : comment réserver une annonce ?</a>
						<div class="aide-text">
							<h2>Locataire : comment réserver une annonce ?</h2>
							<p>1. Connectez-vous à votre compte leboncoin ;</p>
							<p><a href="#aide-annonce">2. Choisissez une annonce qui vous intéresse ;</a></p>
							<p><a href="#aide-date">3. Choisissez les dates de votre séjour ;</a></p>
							<p>4. Cliquez sur le bouton « Réserver » à droite, en bas du calendrier ;</p>
							<p>5. Saisissez les informations du séjour (nombre de personnes, d'enfants, de bébés, d'animaux)</p>
							<p>6. Saisissez les informations du locataire qui s'occupe de la réservation ;</p>
							<p>7. Choisissez votre <a href="#aide-cb">carte de paiement</a>, si vous en avez enregistrée une, ou saisissez les informations de votre carte de paiement ;</p>
							<p>8. Choisissez votre <a href="#aide-methode-paie">méthode de paiement</a> (en une ou plusieurs fois) ;</p>
							<p>Pour vous aider à procéder au paiement, <a href="#aide-payer">suivez ces étapes</a>.</p>
							<p>9. Pour finaliser, cliquez sur le bouton « Payer et envoyer la demande ».</p>
							<p>Le propriétaire se chargera d'accepter ou de refuser votre demande.</p>
						</div>
					</li>
					<li><a>Locataire : comment choisir les dates pour la réservation ?</a>
						<div class="aide-text" id="aide-date">
							<h2>Locataire : comment choisir les dates pour la réservation ?</h2>
							<p>1. Connectez-vous à votre compte leboncoin ;</p>
							<p><a href="#aide-annonce">2. Choisissez une annonce qui vous intéresse ;</a></p>
							<p>3. Si vous souhaitez changer de mois, cliquez sur une des flèches à droite ou à gauche, à côté du mois en haut du calendrier ;</p>
							<img src="/Leboncoin/Reserver/mois.png" alt="fléches changement de mois">
							<p>4. Pour choisir la date de début du séjour, cliquez sur une première date ;</p>
							<p>5. Pour choisir la date de fin du séjour, cliquez sur une deuxième date ;</p>
							<p>Attention :</p>
							<p>- Les dates grisées ne peuvent pas être sélectionnées ;</p>
							<p>- Par défaut, si le séjour est de minimum 2 nuits, la date de fin du séjour sera préselectionnée, vous ne pouvez choisir une date de fin qu'après cette préselection ;</p>
							<p>Exemple d'une réservation à partir du 7 janvier 2026, avec un séjour de minimum 2 nuits : la date de fin, au plus tôt, est le 9 janvier 2026</p>
							<img src="/Leboncoin/Reserver/min.png" alt="exemple séjour minimum 2 nuits">
							<p>6. Pour revenir à la date courante, cliquez sur le bouton « Auj » à gauche, en bas du calendrier ;</p>
							<img src="/Leboncoin/Reserver/auj.png" alt="bouton date courante">
							<p>7. Pour déselectionner les dates sélectionnées, cliquez sur le bouton blanc « Déselectionner » ;</p>
							<img src="/Leboncoin/Reserver/deselect.png" alt="bouton déselectionner dates">
						</div>
					</li>
					<li><a>Locataire : comment procéder au paiement ?</a>
						<div class="aide-text" id="aide-payer">
							<h2>Locataire : comment procéder au paiement ?</h2>
							<p>Après avoir complété les informations concernant la réservation : </p>
							<p>Défilez la page vers le bas jusqu'à la section « Paiement » ;</p>
							<p>1. Si aucune carte n'est enregistrée, cochez « Ajouter une nouvelle carte », sinon cochez la carte que vous souhaitez utiliser et saisissez votre CVV;</p>
							<img src="/Leboncoin/Reserver/ajout-card.png" alt="ajouter carte bancaire">
							<img src="/Leboncoin/Reserver/select-card.png" alt="sélectionner carte bancaire">
							<p>2. Saissez vos informations bancaires dans les champs correspondants (nom du titulaire, numéro de carte, date d'expiration, CVV) ;</p>
							<p>3. Si vous souhaitez enregistrer cette carte pour des futures réservations, cochez « Sauvegarder cette carte pour de futurs paiements » ;</p>
							<p>4. Choisissez votre méthode de paiement, vous pouvez <a href="#aide-cb">payer en une ou plusieurs fois</a> :</p>
							<p>- Cliquez sur la flèche à droite dans le champ ;</p>
							<img src="/Leboncoin/Reserver/mode-paie.png" alt="méthode de paiement">
							<p>- Cliquez sur la méthode de paiement que vous souhaitez utiliser.</p>
							<p>5. Pour fianliser le paiement, cliquez sur le bouton « Payer et envoyer la demande ».</p>
						</div>
					</li>
				</ul>
			</li>
      <li>
				<a>Paiement</a>
				<ul>
					<li><a>Locataire : quels sont les moyens de paiements acceptés ?</a>
					<div class="aide-text" id="aide-cb">
							<h2>Locataire : quels sont les moyens de paiements acceptés ?</h2>
							<p>Dans le cadre des réservations en ligne, voici les moyens de paiement acceptés sur leboncoin :</p>
							<p>- La carte de paiement </p>
							<p>- Le paiement en 3 ou 4 fois par carte bancaire</p>
							<p></p>
							<h3>La carte de paiement</h3>
							<p>Sont acceptées les cartes de paiement en cours de validité de type :</p>
							<img src="/Leboncoin/Paiement/cb.png">
							<h3>Le paiement en 3 ou 4 fois par carte bancaire</h3>
							<p>Vous avez la possibilité de payer en plusieurs fois avec votre carte bancaire Visa ou MasterCard.</p>
							<p>Pour en savoir plus, vous pouvez consulter notre article dédié : </p>
							<p><a href="#aide-methode-paie">Locataire : comment fonctionne le paiement en plusieurs fois par carte bancaire ?</a></p>
						</div>
					</li>
					<li><a>Locataire : comment fonctionne le paiement en plusieurs fois par carte bancaire ?</a>
						<div class="aide-text" id="aide-methode-paie">
							<h2>Locataire : comment procéder au paiement ?</h2>
							<h3>Paiement en 3x</h3>
							<p>L'apport représente 1/3 du montant total. Il vous sera débité le jour suivant la fin de votre réservation.</p>
							<p>La première mensualité représente 1/3 du montant total qui vous sera débité 30 jours après la fin de votre réservation.</p>
							<p>La deuxième mensualité représente 1/3 du montant total qui vous sera débité 60 jours après la fin de votre réservation.</p>
							<h3>Paiement en 4x</h3>
							<p>L'apport représente 1/4 du montant total. Il vous sera débité le jour suivant la fin de votre réservation.</p>
							<p>La première mensualité représente 1/4 du montant total qui vous sera débité 30 jours après la fin de votre réservation.</p>
							<p>La deuxième mensualité représente 1/4 du montant total qui vous sera débité 60 jours après la fin de votre réservation.</p>
							<p>La troisième mensualité représente 1/4 du montant total qui vous sera débité 90 jours après la fin de votre réservation.</p>
						</div>
					</li>
					<li><a>Locataire : à quel moment suis-je débité ?</a>
						<div class="aide-text" id="aide-methode-paie">
							<h2>Locataire : à quel moment suis-je débité ?</h2>
							<p>Pour une réservation en ligne, vous serez débité le jour suivant la fin de votre réservation, si aucun incident n'a été déclaré durant le séjour. En cas d'incident, le paiement est suspendu.</p>
						</div>
					</li>
				</ul>
			</li>
      <li>
				<a>Rechercher et consulter une annonces</a>
				<ul>
					<li><a>Comment rechercher une annonce ?</a>
						<div class="aide-text" id="aide-annonce">
							<h2>Comment rechercher une annonce ?</h2>
							<p>1. Cliquez sur le bouton « Rechercher une annonce » en haut au milieu ;</p>
							<p>2. Saisissez le lieu dans lequel rechercher une annonce ;</p>
							<img src="/Leboncoin/Rechercher/rechercher.png" alt="champ de recherche">
							<p>3. Vous pouvez également <a href="#aide-date">sélectionner les dates du séjour</a> ;</p>
							<p>4. Cliquez sur le bouton « Rechercher » ;</p>
							<p>Afin d'affiner votre recherche vous pouvez ajouter des filtres.</p>
							<p>5. Cliquez sur l'un des filtres et sélectionnez, ou saisissez, l'information correspondant à votre recherche ;</p>
							<img src="/Leboncoin/Rechercher/filtres.png" alt="ensemble des filtres">
							<p>6. Pour finaliser, cliquez sur le bouton « Filtrer » à droite.</p>
							<img src="/Leboncoin/Rechercher/btn-filtrer.png" alt="bouton filtrer">
							<p>Attention : si vous modifiez votre recherche, il est nécessaire que vous re-cliquiez sur le bouton « Filtrer ».</p>
						</div>
					</li>
					<li><a>Comment sauvegarder une recherche ?</a>
						<div class="aide-text">
							<h2>Comment sauvegarder une recherche ?</h2>
							<p>1. Vous devez être connecté à votre compte leboncoin</p>
							<p>2. Faites une recherche et ajoutez, si vous le souhaitez, des filtres supplémentaires ;</p>
							<p>3. Sur la page des résultats de la rechercher, cliquez sur le bouton « Sauvegarder cette recherche » ;</p>
							<img src="/Leboncoin/Rechercher/btn-save-recherche.png" alt="bouton sauvegarder recherche">
							<p>4. Donnez lui un nom afin de mieux la retrouver sur votre espace compte, puis cliquez sur « Confirmer » en bas à droite;</p>
							<img src="/Leboncoin/Rechercher/recap-rech-save.png" alt="récapitulatif rechercher sauvegardée">
						</div>
					</li>
					<li><a>Comment consulter mes recherches sauvegardées ?</a>
						<div class="aide-text">
							<h2>Comment consulter mes annonces en favoris ?</h2>
							<p>1. Connectez-vous à votre compte leboncoin ;</p>
							<p>2. Accédez à votre espace compte ;</p>
							<p>3. Défilez la page vers le bas jusqu’à l'espace « Mes recherches sauvegardées » ;</p>
							<img src="/Leboncoin/Rechercher/recherche-save.png" alt="espace rechercher sauvegardée">
							<p>4. Pour lancer la rechercher, cliquez sur la recherche sauvegardée.</p>
						</div>
					</li>
					<li><a>Comment supprimer une recherche sauvegardée ?</a>
						<div class="aide-text">
							<h2>Comment consulter mes annonces en favoris ?</h2>
							<p>1. Connectez-vous à votre compte leboncoin ;</p>
							<p>2. Accédez à votre espace compte ;</p>
							<p>3. Défilez la page vers le bas jusqu’à l'espace « Mes recherches sauvegardées » ;</p>
							<p>4. Pour supprimer la recherche sauvegardée, cliquez sur la corbeille dans le coin haut droit de la recherche.</p>
							<img src="/Leboncoin/Rechercher/supp-recherche.png" alt="bouton supprimer recherche">
						</div>
					</li>
          			<li><a>Comment ajouter une annonce aux favoris ?</a>
						<div class="aide-text">
							<h2>Comment ajouter une annonce aux favoris ?</h2>
							<p>1. Vous devez être connecté à votre compte leboncoin</p>
							<p>2. Faites une recherche, lorsqu'une annonce vous plait, cliquez sur celle-ci ;</p>
							<p>3. Sur la page de l'annonce, cliquez sur le coeur pour l'ajouter à vos favoris, le coeur est plein lorsque l'annonce est ajoutée à vos favoris ;</p>
							<img src="/Leboncoin/Rechercher/ajout-fav.png" alt="bouton ajouter favoris">
						</div>
					</li>
					<li><a>Comment consulter mes annonces en favoris ?</a>
						<div class="aide-text">
							<h2>Comment consulter mes annonces en favoris ?</h2>
							<p>1. Connectez-vous à votre compte leboncoin ;</p>
							<p>2. Accédez à votre espace compte ;</p>
							<p>3. Défilez la page vers le bas jusqu’à l'espace « Mes favoris » ;</p>
							<img src="/Leboncoin/Rechercher/favoris.png" alt="espace favoris">
							<p>4. Pour visualiser l'annonce, cliquez sur l'annonce.</p>
						</div>
					</li>
					<li><a>Comment supprimer une annonce des favoris ?</a>
						<div class="aide-text">
							<h2>Comment consulter mes annonces en favoris ?</h2>
							<p>1. Connectez-vous à votre compte leboncoin ;</p>
							<p>2. Accédez à votre espace compte ;</p>
							<p>3. Défilez la page vers le bas jusqu’à l'espace « Mes favoris » ;</p>
							<p>4. Cliquez sur l'annonce que vous souhaitez supprimer de vos favoris ;</p>
							<p>5. Sur la page de l'annonce, cliquez sur le coeur pour la supprimer de vos favoris, le coeur est vide lorsque l'annonce est supprimée de vos favoris ;</p>
							<img src="/Leboncoin/Rechercher/supp-fav.png" alt="bouton supprimer favoris">
						</div>
					</li>
				</ul>
			</li>
      <li>
				<a>Incident</a>
				<ul class="last">
					<li><a>Locataire : comment déclarer un incident ?</a>
						<div class="aide-text">
							<h2>Locataire : comment déclarer un incident ?</h2>
							<p>1. Cliquez sur la réservation sur laquelle a lieu l'incident ;</p>
							<p>2. Cliquez sur le bouton « Déclarer un incident » ;</p>
							<img src="/Leboncoin/Incident/declarer.png" alt="bouton déclarer incident">
							<p>3. Une page s'ouvre, décrivez précisément l'incident ;</p>
							<p>4. Pour finaliser, cliquez sur le bouton « Déclarer » en bas à droite.</p>
						</div>
					</li>
					<li><a>Propriétaire Particulier & Entreprise : que faire si le locataire déclare un incident ?</a>
						<div class="aide-text">
							<h2>Propriétaire Particulier & Entreprise : que faire si le locataire déclare un incident ?</h2>
							<p class="gras">Vous avez 2 options :</p>
							<p>- <a href="#aide-justifier">Option 1 : Justifier l'incident sans le clore</a></p>
							<p>- <a href="#aide-clore">Option 2 : Clore l'incident</a></p>
							<h3 id="aide-justifier">Option 1 : Justifier l'incident sans le clore</h3>
							<p>Si vous n'êtes pas d'accord avec l'incident déclaré, vous devez le justifier afin que les services leboncoin s'en chargent.</p>
							<p>1. Cliquez sur la location concernée ou sur l'incident dans l'espace « Mes incidents » ;</p>
							<p>2. Saisissez votre justification dans le champ dédié ;</p>
							<p>3. Pour finaliser, cliquez sur le bouton blanc « Donner une justification sans clore ».</p>
							<img src="/Leboncoin/Incident/justifier.png" alt="bouton justifier incident">
							<h3 id="aide-clore">Option 2 : Clore l'incident</h3>
							<p>Si vous reconnaissez l'incident, vous pouvez vous même le clore, le service Comptable se chargera du remboursement.</p>
							<p>1. Cliquez sur la location concernée ou sur l'incident dans l'espace « Mes incidents » ;</p>
							<p>2. Optionnel : Si vous souhaitez donner une explication au locataire, vous pouvez saisir une justification ;</p>
							<p>3. Pour finaliser, cliquez sur le bouton orange « Reconnaître l'incident et clore ».</p>
							<img src="/Leboncoin/Incident/clore.png" alt="bouton clore incident">
						</div>
					</li>
					<li><a>Que faire en cas de désaccord entre propriétaire et locataire concernant un incident ?</a>
						<div class="aide-text">
							<h2>Que faire en cas de désaccord entre propriétaire et locataire concernant un incident ?</h2>
							<p>En cas de désaccord entre les deux parties, leboncoin se chargera d'analyser et de résoudre l'incident.</p>
							<p>Si l'incident est justifié et donne raison au locataire, le service Comptable se chargera de rembourser le locataire.</p>
							<p>Si l'incident n'est pas justifié et donne raison au propriétaire, le paiement sera transféré au propriétaire.</p>
						</div>
					</li>
					<li class="last-aide"><a>Que se passe-t-il lorsqu'un incident est déclaré ?</a>
						<div class="aide-text">
							<h2>Que se passe-t-il lorsqu'un incident est déclaré ?</h2>
							<p>Le paiement est suspendu jusqu'à ce que l'incident soit résolu par le propriétaire ou par les services leboncoin.</p>
							<p>Si l'incident est justifié et donne raison au locataire, ou si propriétaire reconnaît l'incident, le service Comptable se chargera de rembourser le locataire.</p>
							<p>Si l'incident n'est pas justifié et donne raison au propriétaire, le paiement sera transféré au propriétaire.</p>
						</div>
					</li>
				</ul>
			</li>
		</ul>
	</nav>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
    $('.aide-menu nav > ul > li > a').on('click', function(e) {
        e.stopPropagation();
        const nextSubMenu = $(this).next('ul');
        $('.aide-menu nav ul ul').not(nextSubMenu).slideUp();
        if (nextSubMenu.length > 0) {
            e.preventDefault();
            nextSubMenu.slideToggle();
        }
    });

    $('.aide-menu ul ul li > a').on('click', function(e) {
        const textBlock = $(this).next('.aide-text');
        if (textBlock.length > 0) {
            e.preventDefault();
            e.stopPropagation();
            $(this).closest('ul').find('a').not($(this)).removeClass('active-link');
            $(this).toggleClass('active-link');
            $('.aide-text').not(textBlock).slideUp();
            textBlock.slideToggle();
        }
    });

    $('.aide-menu .aide-text a[href^="#"]').on('click', function(e) {
        const targetId = $(this).attr('href'); 
        const $targetElement = $(targetId);

        if ($targetElement.length > 0) {
            e.preventDefault();
            
            const $parentTextBlock = $targetElement.closest('.aide-text');
            const $parentSubMenu = $parentTextBlock.closest('ul');
            const $triggerLink = $parentTextBlock.prev('a');

            if (!$parentSubMenu.is(':visible')) {
                $parentSubMenu.slideDown();
            }

            $('.aide-text').not($parentTextBlock).slideUp();
            $('.aide-menu ul ul li > a').removeClass('active-link');
            $triggerLink.addClass('active-link');

            $parentTextBlock.slideDown(400, function() {
                const container = $('.aide-menu');
                const scrollTo = $targetElement.offset().top - container.offset().top + container.scrollTop() - 20;
                
                container.animate({
                    scrollTop: scrollTo
                }, 500);
            });
        }
    });

    $('.aide-menu li').has('ul').find('> a').addClass('caret');
});
</script>
</body>
</html>
