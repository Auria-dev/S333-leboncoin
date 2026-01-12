import psutil
import time
from datetime import datetime

def monitorer():
    print("=" * 70)
    print("MONITORING RESSOURCES SYSTÈME")
    print("=" * 70)
    print(f"Début : {datetime.now()}")
    print("-" * 70)
    print(f"{'Temps':>8} | {'CPU %':>8} | {'Mémoire %':>10} | {'Mémoire MB':>12}")
    print("-" * 70)
    
    mesures = []
    debut = time.time()
    
    try:
        while time.time() - debut < 90:  # 90 secondes de monitoring
            cpu = psutil.cpu_percent(interval=1)
            mem = psutil.virtual_memory()
            mem_pct = mem.percent
            mem_mb = mem.used / (1024 * 1024)
            
            mesures.append({'cpu': cpu, 'mem': mem_pct, 'mem_mb': mem_mb})
            
            temps = int(time.time() - debut)
            print(f"{temps:>8}s | {cpu:>7.1f}% | {mem_pct:>9.1f}% | {mem_mb:>10.0f} MB")
    
    except KeyboardInterrupt:
        print("\n\nArrêt demandé par l'utilisateur (Ctrl+C)")
    
    if mesures:
        print("\n" + "=" * 70)
        print("STATISTIQUES FINALES")
        print("=" * 70)
        cpus = [m['cpu'] for m in mesures]
        mems = [m['mem'] for m in mesures]
        mem_mbs = [m['mem_mb'] for m in mesures]
        print(f"CPU     - Min: {min(cpus):>6.1f}%  Max: {max(cpus):>6.1f}%  Moyen: {sum(cpus)/len(cpus):>6.1f}%")
        print(f"Mémoire - Min: {min(mems):>6.1f}%  Max: {max(mems):>6.1f}%  Moyen: {sum(mems)/len(mems):>6.1f}%")
        print(f"Mem(MB) - Min: {min(mem_mbs):>6.0f}MB Max: {max(mem_mbs):>6.0f}MB Moyen: {sum(mem_mbs)/len(mem_mbs):>6.0f}MB")
        print("=" * 70)

if __name__ == "__main__":
    monitorer()