<!DOCTYPE html>
<html>
<head>
  <title>Location Distance Map</title>
  <style>
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
      font-family: sans-serif;
    }

    #map {
      height: 100%;
      width: 100%;
    }

    #controls {
      position: absolute;
      top: 10px;
      left: 10px;
      background: rgba(255, 255, 255, 0.95);
      padding: 15px;
      border-radius: 10px;
      box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
      z-index: 5;
      max-width: 300px;
    }

    input, button, select {
      display: block;
      margin: 6px 0;
      width: 100%;
      padding: 6px;
    }
  </style>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

  <div id="controls">
    <h3>Location Tools</h3>
    <form id="location-form">
      <input type="text" id="name" placeholder="Location Name" required>
      <input type="number" step="any" id="lat" placeholder="Latitude" required>
      <input type="number" step="any" id="lng" placeholder="Longitude" required>
      <button type="submit">Add Location</button>
    </form>
    <hr>
    <label for="location-select">Select Location:</label>
    <select id="location-select">
      <option value="">-- Select Location --</option>
    </select>
    <button onclick="calculateSelectedDistances()">Show Distances</button>
  </div>

  <div id="map"></div>

  <script>
    let map;
    let locations = [];
    let markers = [];
    let markerMap = {}; // map of id: marker

    function initMap() {
      map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 6.9271, lng: 79.8612 },
        zoom: 8
      });

      loadLocations();

      document.getElementById("location-form").addEventListener("submit", function(e) {
        e.preventDefault();
        const name = document.getElementById("name").value;
        const lat = parseFloat(document.getElementById("lat").value);
        const lng = parseFloat(document.getElementById("lng").value);

        fetch("save_location.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ name, lat, lng })
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === "success") {
            Swal.fire({
              icon: 'success',
              title: 'Location Added!',
              text: 'The new location has been saved successfully.',
              timer: 2000,
              showConfirmButton: false
            });
            document.getElementById("location-form").reset();
            loadLocations();
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Something went wrong while saving the location!'
            });
          }
        });
      });
    }

    function loadLocations() {
      fetch("get_locations.php")
        .then(res => res.json())
        .then(data => {
          locations = data;
          clearMarkers();
          const select = document.getElementById("location-select");
          select.innerHTML = `<option value="">-- Select Location --</option>`;

          data.forEach(loc => {
            const marker = new google.maps.Marker({
              position: { lat: parseFloat(loc.lat), lng: parseFloat(loc.lng) },
              map,
              title: loc.name
            });
            markers.push(marker);
            markerMap[loc.id] = marker;

            const option = document.createElement("option");
            option.value = loc.id;
            option.textContent = loc.name;
            select.appendChild(option);

            marker.addListener("click", () => {
              showDistances(loc);
            });
          });
        });
    }

    function showDistances(fromLoc) {
      const from = new google.maps.LatLng(parseFloat(fromLoc.lat), parseFloat(fromLoc.lng));
      const msg = locations
        .filter(loc => loc.id != fromLoc.id)
        .map(loc => {
          const to = new google.maps.LatLng(parseFloat(loc.lat), parseFloat(loc.lng));
          const distance = google.maps.geometry.spherical.computeDistanceBetween(from, to);
          return `${loc.name}: ${(distance / 1000).toFixed(2)} km`;
        }).join('<br>');

      Swal.fire({
        title: `Distances from ${fromLoc.name}`,
        html: msg,
        width: 600,
        backdrop: false,
        didOpen: () => highlightMarker(fromLoc.id)
      });
    }

    function highlightMarker(id) {
      // Stop animations on all markers
      Object.values(markerMap).forEach(m => m.setAnimation(null));

      const marker = markerMap[id];
      if (marker) {
        marker.setAnimation(google.maps.Animation.BOUNCE);
        setTimeout(() => {
          marker.setAnimation(null);
        }, 3000);
      }
    }

    function calculateSelectedDistances() {
      const selectedId = document.getElementById("location-select").value;
      if (!selectedId) {
        Swal.fire({
          icon: 'warning',
          title: 'Select Location',
          text: 'Please choose a location to calculate distances.'
        });
        return;
      }

      const selected = locations.find(loc => loc.id == selectedId);
      showDistances(selected);
    }

    function clearMarkers() {
      markers.forEach(m => m.setMap(null));
      markers = [];
      markerMap = {};
    }

    window.onload = () => {
      const script = document.createElement('script');
      script.src = "https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY_HERE&libraries=geometry&callback=initMap";
      script.async = true;
      script.defer = true;
      document.head.appendChild(script);
    };
  </script>
</body>
</html>
