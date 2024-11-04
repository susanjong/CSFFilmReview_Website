function initMap() {
    // Lokasi default (Medan) jika geolokasi tidak diizinkan
    var defaultLocation = { lat: 3.5626, lng: 98.6591 }; //lat itu latitude lng artinya longtitude 
    
    // Inisialisasi peta
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 13,
        center: defaultLocation
    });

    // Marker default pada lokasi Medan
    var marker = new google.maps.Marker({
        position: defaultLocation,
        map: map,
        draggable: true
    });

    // Fungsi untuk memperbarui marker dan pusat peta
    function updateMapLocation(lat, lng) {
        var newLocation = { lat: lat, lng: lng };
        map.setCenter(newLocation);  
        marker.setPosition(newLocation);  // Pindahkan marker ke lokasi baru
        
        // Update nilai latitude dan longitude ke input tersembunyi
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
    }
}