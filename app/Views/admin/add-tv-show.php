<?php require_once __DIR__ . '/partials/header.php'; ?>

<main>
    <h1>Add New TV Show</h1>

    <div style="background: #2a2a2a; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
        <h3>Import from TMDb</h3>
        <label for="tmdb_id">TMDb TV Show ID:</label>
        <input type="text" id="tmdb_id" name="tmdb_id" placeholder="e.g., 1396 for Breaking Bad">
        <button type="button" id="fetch-tmdb-btn">Fetch Info</button>
        <p id="tmdb-status" style="color: yellow; margin-top: 10px;"></p>
    </div>

    <form action="/admin/tv-shows/add" method="POST">
        <input type="hidden" id="tmdb_id_hidden" name="tmdb_id">
        <input type="hidden" id="logo_backdrop_path" name="logo_backdrop_path">
        <input type="hidden" id="poster_path" name="poster_path">
        <input type="hidden" id="backdrop_path" name="backdrop_path">
        <input type="hidden" id="logo_path" name="logo_path">
        <input type="hidden" id="trailer_key" name="trailer_key">
        <input type="hidden" id="status" name="status">
        <input type="hidden" id="genres" name="genres">
        <input type="hidden" id="cast" name="cast">
        
        <div class="image-previews" style="float: right; margin-left: 20px;">
            <div id="poster-preview" class="preview-box"></div>
            <div id="logo-preview" class="preview-box logo-preview-bg"></div>
            <div id="backdrop-preview" class="preview-box"></div>
        </div>
        
        <div>
            <label for="title">Title:</label><br>
            <input type="text" id="title" name="title" required style="width: 300px;"><br><br>
            
            <label for="overview">Overview:</label><br>
            <textarea id="overview" name="overview" rows="5" style="width: 300px;"></textarea><br><br>

            <label for="first_air_date">First Air Date:</label><br>
            <input type="date" id="first_air_date" name="first_air_date"><br><br>

            <div id="trailer-preview" style="margin-bottom: 20px;"></div>
            <div id="genres-preview" style="margin-bottom: 20px; max-width: 500px;"></div>
            <div id="cast-preview" style="margin-bottom: 20px; max-width: 500px;"></div>
            
            <button type="submit">Save TV Show</button>
        </div>
    </form>
</main>

<script>
    document.getElementById('fetch-tmdb-btn').addEventListener('click', function() {
        const tmdbId = document.getElementById('tmdb_id').value;
        const statusP = document.getElementById('tmdb-status');
        if (!tmdbId) { statusP.textContent = 'Please enter a TMDb ID.'; return; }
        statusP.textContent = 'Fetching data...';

        fetch('/admin/tv-shows/tmdb-import', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'tmdb_id=' + encodeURIComponent(tmdbId)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success === false || data.error) {
                statusP.textContent = 'Error: ' + (data.status_message || data.error || 'TV show not found.');
            } else {
                // Populate form fields
                document.getElementById('title').value = data.name || '';
                document.getElementById('overview').value = data.overview || '';
                document.getElementById('first_air_date').value = data.first_air_date || '';
                
                // Populate hidden fields
                document.getElementById('logo_backdrop_path').value = data.logo_backdrop_path || '';
                document.getElementById('tmdb_id_hidden').value = tmdbId;
                document.getElementById('poster_path').value = data.poster_path || '';
                document.getElementById('backdrop_path').value = data.backdrop_path || '';
                document.getElementById('status').value = data.status || '';

                // Add Genres
                const genresPreview = document.getElementById('genres-preview');
                if (data.genres && data.genres.length > 0) {
                    document.getElementById('genres').value = JSON.stringify(data.genres);
                    genresPreview.innerHTML = '<strong>Genres:</strong> ' + data.genres.map(g => g.name).join(', ');
                } else { genresPreview.innerHTML = ''; document.getElementById('genres').value = ''; }

                // Add Cast
                const castPreview = document.getElementById('cast-preview');
                if (data.credits && data.credits.cast && data.credits.cast.length > 0) {
                    const cast = data.credits.cast.slice(0, 5);
                    document.getElementById('cast').value = JSON.stringify(cast);
                    let castHtml = '<strong>Cast:</strong><div class="cast-list">';
                    cast.forEach(person => {
                        const photoUrl = person.profile_path ? `https://image.tmdb.org/t/p/w45${person.profile_path}` : 'https://via.placeholder.com/40x60.png?text=N/A';
                        castHtml += `<div class="cast-member"><img src="${photoUrl}" alt="${person.name}"><span>${person.name} <em>as ${person.character}</em></span></div>`;
                    });
                    castHtml += '</div>';
                    castPreview.innerHTML = castHtml;
                } else { castPreview.innerHTML = ''; document.getElementById('cast').value = ''; }

                // Show Poster Preview
                const posterPreview = document.getElementById('poster-preview');
                if (data.poster_path) {
                    posterPreview.innerHTML = '<h4>Poster</h4><img src="https://image.tmdb.org/t/p/w200' + data.poster_path + '" alt="Poster" style="width: 100%;">';
                } else { posterPreview.innerHTML = ''; }

                // Show Backdrop Preview
                const backdropPreview = document.getElementById('backdrop-preview');
                if (data.backdrop_path) {
                    backdropPreview.innerHTML = '<h4>Backdrop</h4><img src="https://image.tmdb.org/t/p/w200' + data.backdrop_path + '" alt="Backdrop" style="width: 100%;">';
                } else { backdropPreview.innerHTML = ''; }

                // Find and show the best Logo
                const logoPreview = document.getElementById('logo-preview');
                if (data.images && data.images.logos && data.images.logos.length > 0) {
                    const bestLogo = data.images.logos.find(logo => logo.iso_639_1 === 'en') || data.images.logos[0];
                    if (bestLogo) {
                        document.getElementById('logo_path').value = bestLogo.file_path;
                        logoPreview.innerHTML = '<h4>Logo</h4><img src="https://image.tmdb.org/t/p/w200' + bestLogo.file_path + '" alt="Logo" style="max-width: 100%;">';
                    } else {
                        logoPreview.innerHTML = '';
                        document.getElementById('logo_path').value = '';
                    }
                } else {
                    logoPreview.innerHTML = '';
                    document.getElementById('logo_path').value = '';
                }

                // Find the official trailer
                const trailerPreview = document.getElementById('trailer-preview');
                if (data.videos && data.videos.results && data.videos.results.length > 0) {
                    const officialTrailer = data.videos.results.find(video => video.type === 'Trailer' && video.official === true) || data.videos.results.find(video => video.type === 'Trailer') || data.videos.results[0];
                    if (officialTrailer && officialTrailer.site === 'YouTube') {
                        document.getElementById('trailer_key').value = officialTrailer.key;
                        trailerPreview.innerHTML = '<strong>Trailer Found:</strong> <a href="https://www.youtube.com/watch?v=' + officialTrailer.key + '" target="_blank">Watch on YouTube</a>';
                    }
                } else {
                    trailerPreview.innerHTML = '';
                    document.getElementById('trailer_key').value = '';
                }

                statusP.textContent = 'Success! Data has been filled below.';
            }
        })
        .catch(error => {
            statusP.textContent = 'A network error occurred.';
            console.error('Error:', error);
        });
    });
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>