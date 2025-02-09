function initMap() {
    const mapOptions = {
        center: { lat: 10.3157, lng: 123.8854 }, // Coordinates for Cebu City
        zoom: 12,
    };

    const map = new google.maps.Map(document.getElementById("map"), mapOptions);

    // Marker for the refilling station
    const marker = new google.maps.Marker({
        position: { lat: 10.3157, lng: 123.8854 }, // Replace with actual coordinates