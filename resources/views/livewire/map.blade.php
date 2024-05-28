<div>
    <div id="map" style="height:95vh;"></div>
    <button onclick="createRegion()">Submit</button>
    <script>
        var map = L.map('map').setView([{{$loc['lat']}}, {{$loc['lng']}}], {{$zoom}});
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>' }).addTo(map);
        var locations = [];
        map.on('click', function(e) {
            locations.push([e.latlng.lat, e.latlng.lng]);
            L.marker([e.latlng.lat, e.latlng.lng]).addTo(map).on('click', function (e) {
                locations = locations.filter(function (value, index, arr) {
                    return value[0] != e.latlng.lat && value[1] != e.latlng.lng;
                });
                map.removeLayer(this);
            });
        });
        function createRegion() {
            var data = {
                locations: locations
            };
            var latlng = {
                lat: locations[0][0],
                lng: locations[0][1]
            }
            @this.createRegion(data, latlng, map.getZoom());
        }
    </script>
    @foreach($regions as $region)
        <script>
            var x = L.polygon([
                @foreach($region->locations as $location)

                    [{{$location->lat}}, {{$location->lng}}],
                @endforeach
                @foreach($region->locations->reverse() as $location)
                    [{{$location->lat}}, {{$location->lng}}],
                @endforeach
            ]).addTo(map);
            x.bindPopup("{{$region->created_at->diffForHumans()}}");
        </script>
    @endforeach
</div>