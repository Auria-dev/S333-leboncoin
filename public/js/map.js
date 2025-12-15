/**
 * * @param {string} mapId
 * @param {Array} annoncesData
 */


function initMapAnnonce(mapId, annoncesData) {

    if (!document.getElementById(mapId)) {
        return;
    }


    var map = L.map('maCarte', {
        zoomControl: false
    }).setView([48.8566, 2.3522], 10);

    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Light_Gray_Base/MapServer/tile/{z}/{y}/{x}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 16
        
    }).addTo(map);



    L.control.zoom({
        position: 'topright'
    }).addTo(map);



    const annoncesListe = Array.isArray(annoncesData) ? annoncesData : Object.values(annoncesData);

    var markersGroup = new L.featureGroup();
    
    annoncesListe.forEach(annonce => {
        if (annonce.latitude && annonce.longitude) {
            console.log('Ajout du marqueur pour l\'annonce ID:', annonce.idannonce);
            const marker = L.marker([annonce.latitude, annonce.longitude]).addTo(map);
            const popupContent = `
                <strong>${annonce.titre_annonce}</strong><br>
                Prix: ${annonce.prix_nuit}€ / nuit<br>
                <a href="/annonce/${annonce.idannonce}">Voir l'annonce</a>
            `;
            marker.bindPopup(popupContent);
            marker.addTo(markersGroup);
        }
    });

    if (annoncesListe.length > 0 && markersGroup.getLayers().length > 0) {
        map.fitBounds(markersGroup.getBounds(), { padding: [50, 50] 
        });
    }
}