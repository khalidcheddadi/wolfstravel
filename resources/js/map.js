// resources/js/map.js

export function initCreateMap(lat = 40.4168, lng = -3.7038) {
    const mapContainer = document.getElementById('map');
    if (!mapContainer) return;

    const map = L.map('map').setView([lat, lng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    let marker = L.marker([lat, lng], { draggable: true }).addTo(map);

    marker.on('dragend', function(e) {
        const pos = marker.getLatLng();
        document.getElementById('latitude').value = pos.lat.toFixed(7);
        document.getElementById('longitude').value = pos.lng.toFixed(7);
    });

    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        document.getElementById('latitude').value = e.latlng.lat.toFixed(7);
        document.getElementById('longitude').value = e.latlng.lng.toFixed(7);
    });

    return { map, marker };
}

export function initShowMap(lat, lng, title) {
    const mapContainer = document.getElementById('map-show');
    if (!mapContainer) return;

    const map = L.map('map-show').setView([lat, lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    L.marker([lat, lng]).addTo(map)
        .bindPopup(title || 'الموقع')
        .openPopup();
}
