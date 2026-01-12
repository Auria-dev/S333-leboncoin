import requests
import time
from concurrent.futures import ThreadPoolExecutor, as_completed
from datetime import datetime

# Configuration
URL = "http://51.83.36.122:5152"  # Remplacez par votre URL
NB_REQUETES_TOTAL = 1000
NB_UTILISATEURS_SIMULTANES = 200

def faire_requete(numero):
    """Effectue une requête et mesure le temps de réponse"""
    try:
        debut = time.time()
        reponse = requests.get(URL, timeout=10)
        duree = time.time() - debut
        return {
            'numero': numero,
            'status': reponse.status_code,
            'duree': duree,
            'succes': reponse.status_code == 200
        }
    except requests.exceptions.Timeout:
        return {'numero': numero, 'succes': False, 'erreur': 'Timeout'}
    except Exception as e:
        return {'numero': numero, 'succes': False, 'erreur': str(e)}

def lancer_test():
    """Lance le test de charge complet"""
    print(f"Début du test : {datetime.now()}")
    print(f"URL cible : {URL}")
    print(f"Nombre de requêtes : {NB_REQUETES_TOTAL}")
    print(f"Utilisateurs simultanés : {NB_UTILISATEURS_SIMULTANES}")
    print("-" * 60)
    
    resultats = []
    debut_total = time.time()
    
    with ThreadPoolExecutor(max_workers=NB_UTILISATEURS_SIMULTANES) as executor:
        futures = [executor.submit(faire_requete, i) for i in range(NB_REQUETES_TOTAL)]
        
        for future in as_completed(futures):
            resultats.append(future.result())
            if len(resultats) % 100 == 0:
                print(f"Progression : {len(resultats)}/{NB_REQUETES_TOTAL} requêtes")
    
    duree_totale = time.time() - debut_total
    
    # Analyse des résultats
    reussies = [r for r in resultats if r.get('succes')]
    echouees = [r for r in resultats if not r.get('succes')]
    durees = [r['duree'] for r in reussies if 'duree' in r]
    
    print("\n" + "=" * 60)
    print("RÉSULTATS DU TEST")
    print("=" * 60)
    print(f"Durée totale du test : {duree_totale:.2f} secondes")
    print(f"Requêtes totales : {NB_REQUETES_TOTAL}")
    print(f"Requêtes réussies : {len(reussies)} ({len(reussies)/NB_REQUETES_TOTAL*100:.1f}%)")
    print(f"Requêtes échouées : {len(echouees)} ({len(echouees)/NB_REQUETES_TOTAL*100:.1f}%)")
    
    if durees:
        print(f"\nTemps de réponse moyen : {sum(durees)/len(durees)*1000:.2f} ms")
        print(f"Temps de réponse minimum : {min(durees)*1000:.2f} ms")
        print(f"Temps de réponse maximum : {max(durees)*1000:.2f} ms")
        durees_triees = sorted(durees)
        p95 = durees_triees[int(len(durees_triees) * 0.95)]
        print(f"Temps de réponse P95 : {p95*1000:.2f} ms")
    
    print(f"\nRequêtes par seconde : {NB_REQUETES_TOTAL/duree_totale:.2f}")
    print("=" * 60)
    
    # Afficher les types d'erreurs si présentes
    if echouees:
        print("\nDétail des erreurs :")
        erreurs = {}
        for e in echouees:
            erreur = e.get('erreur', 'Erreur inconnue')
            erreurs[erreur] = erreurs.get(erreur, 0) + 1
        for erreur, count in erreurs.items():
            print(f"  - {erreur}: {count} fois")

if __name__ == "__main__":
    lancer_test()