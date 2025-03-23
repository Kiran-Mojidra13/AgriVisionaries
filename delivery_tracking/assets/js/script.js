// Initialize map for delivery tracking
var map = L.map('map').setView([23.0225, 72.5714], 10); // Default to Ahmedabad
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Function to update the delivery status
function updateStatus() {
    let location = document.getElementById('location').value;
    if (location) {
        $.ajax({
            url: 'update_status.php',
            type: 'POST',
            data: { location: location },
            success: function(response) {
                let listItem = document.createElement('li');
                listItem.classList.add('list-group-item');
                listItem.innerText = `Reached: ${location}`;
                document.getElementById('statusList').appendChild(listItem);

                // Optionally, update the map with the new location (using a geocoding API)
                // Here we use a simple marker on the map for the entered location
                var geocodeUrl = `https://nominatim.openstreetmap.org/search?q=${location}&format=json`;
                $.getJSON(geocodeUrl, function(data) {
                    if (data && data[0]) {
                        var lat = parseFloat(data[0].lat);
                        var lon = parseFloat(data[0].lon);
                        L.marker([lat, lon]).addTo(map).bindPopup(`<b>${location}</b>`).openPopup();
                        map.setView([lat, lon], 12); // Center the map on the location
                    }
                });
            }
        });
    }
}

// Optional: Dynamic updates for map position (could be used for real-time tracking with location services)
navigator.geolocation.getCurrentPosition(function(position) {
    var lat = position.coords.latitude;
    var lon = position.coords.longitude;

    L.marker([lat, lon]).addTo(map).bindPopup("You are here").openPopup();
    map.setView([lat, lon], 12);
});
