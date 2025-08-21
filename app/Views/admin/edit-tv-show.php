<?php require_once __DIR__ . '/partials/header.php'; ?>

<style>
    /* İki sütunlu ana yapı */
    .edit-container { display: flex; flex-wrap: wrap; gap: 30px; }
    .main-column { flex: 2; min-width: 450px; }
    .side-column { flex: 1; min-width: 300px; }

    /* Genel form stilleri ve kart yapısı */
    .card { background-color: #1f2937; padding: 25px; border-radius: 8px; margin-bottom: 20px; }
    .card h3 { margin-top: 0; border-bottom: 1px solid #374151; padding-bottom: 15px; margin-bottom: 25px; font-size: 18px; }
    .form-group { margin-bottom: 25px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #ccc; font-size: 14px; }
    .form-group input, .form-group textarea, .form-group select {
        width: 100%;
        box-sizing: border-box;
        padding: 12px;
        background-color: #374151;
        border: 1px solid #4a5568;
        color: #fff;
        border-radius: 4px;
        font-size: 14px;
    }
    .form-group textarea { min-height: 150px; resize: vertical; }

    /* Sağ sütun ve Medya Yönetimi */
    .media-item { margin-bottom: 15px; }
    .media-preview img { max-width: 100%; height: auto; border-radius: 4px; margin-bottom: 10px; }
    .media-input-group { display: flex; gap: 10px; }
    .media-input-group input { flex-grow: 1; font-size: 12px; padding: 8px; }
    .media-input-group button { background-color: #4a5568; border:none; color:#fff; padding: 0 15px; border-radius:4px; cursor:pointer; font-size: 12px; }
    
    /* Platform & Kategori Seçme Kutucuğu Stilleri */
    .custom-select-container { position: relative; }
    .selected-items {
        background-color: #374151;
        border: 1px solid #4a5568;
        border-radius: 4px;
        padding: 5px;
        min-height: 44px;
        cursor: pointer;
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }
    .item-tag {
        background-color: #42ca1a;
        color: #111;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .item-tag .remove-tag { cursor: pointer; font-weight: bold; }
    .items-dropdown {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #374151;
        border: 1px solid #4a5568;
        z-index: 100;
        max-height: 150px;
        overflow-y: auto;
        border-radius: 0 0 4px 4px;
    }
    .items-dropdown div { padding: 10px; cursor: pointer; }
    .items-dropdown div:hover { background-color: #4a5568; }

    /* Oyuncu Listesi */
    .cast-list { display: flex; flex-wrap: wrap; gap: 15px; margin-top: 5px; }
    .cast-member { display: flex; align-items: center; gap: 8px; background-color: #374151; padding: 5px; border-radius: 4px; font-size: 14px; }
    .cast-member img { width: 40px; height: 60px; object-fit: cover; border-radius: 3px; }
    .cast-member em { color: #ccc; font-style: normal; }
    
    /* Sağ alt butonlar */
    .form-actions-footer { margin-top: 20px; display: flex; flex-direction: column; gap: 10px; }
    .action-button { width: 100%; padding: 12px; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; text-align: center; text-decoration: none; }
    .save-button { background-color: #42ca1a; }
    .seasons-button { background-color: #555; }
</style>

<main>
    <h1>Edit TV Show: <?php echo htmlspecialchars($tvShow['title']); ?></h1>
    <p><a href="/admin/tv-shows">&larr; Back to TV Show List</a></p>
    <hr style="border-color: #374151; margin: 20px 0;">
    
    <form action="/admin/tv-shows/edit/<?php echo $tvShow['id']; ?>" method="POST">
        <div class="edit-container">
            <div class="main-column">
                <div class="card">
                    <h3>TV Show Info</h3>
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($tvShow['title']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="overview">Description</label>
                        <textarea id="overview" name="overview"><?php echo htmlspecialchars($tvShow['overview']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="genres">Genres</label>
                        <div class="custom-select-container" id="genres-container">
                            <div class="selected-items" id="selected-genres">
                                <?php 
                                $selectedGenreIds = array_map(fn($g) => $g['id'], $genres);
                                foreach ($genres as $genre): ?>
                                    <span class="item-tag" data-id="<?php echo $genre['id']; ?>">
                                        <?php echo htmlspecialchars($genre['name']); ?>
                                        <span class="remove-tag" data-id="<?php echo $genre['id']; ?>">&times;</span>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                            <div class="items-dropdown" id="genre-dropdown">
                                <?php foreach ($allGenres as $genre): ?>
                                    <div data-id="<?php echo $genre['id']; ?>" data-name="<?php echo htmlspecialchars($genre['name']); ?>"><?php echo htmlspecialchars($genre['name']); ?></div>
                                <?php endforeach; ?>
                            </div>
                            <div id="hidden-genre-inputs">
                                <?php foreach ($selectedGenreIds as $id): ?>
                                    <input type="hidden" name="genre_ids[]" value="<?php echo $id; ?>">
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="first_air_date">First Air Date</label>
                        <input type="date" id="first_air_date" name="first_air_date" value="<?php echo $tvShow['first_air_date']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <?php
                            $statuses = ['Returning Series', 'Ended', 'Canceled', 'In Production', 'Planned'];
                            foreach ($statuses as $statusOption) {
                                $selected = ($statusOption == $tvShow['status']) ? 'selected' : '';
                                echo "<option value=\"{$statusOption}\" {$selected}>{$statusOption}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="platforms">Content Networks</label>
                        <div class="custom-select-container" id="platforms-container">
                            <div class="selected-items" id="selected-platforms">
                                <?php 
                                $selectedPlatformIds = array_map(fn($p) => $p['id'], $tvShowPlatforms);
                                foreach ($tvShowPlatforms as $platform): ?>
                                    <span class="item-tag" data-id="<?php echo $platform['id']; ?>">
                                        <?php echo htmlspecialchars($platform['name']); ?>
                                        <span class="remove-tag" data-id="<?php echo $platform['id']; ?>">&times;</span>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                            <div class="items-dropdown" id="platform-dropdown">
                                <?php foreach ($allPlatforms as $platform): ?>
                                    <div data-id="<?php echo $platform['id']; ?>" data-name="<?php echo htmlspecialchars($platform['name']); ?>"><?php echo htmlspecialchars($platform['name']); ?></div>
                                <?php endforeach; ?>
                            </div>
                            <div id="hidden-platform-inputs">
                                <?php foreach ($selectedPlatformIds as $id): ?>
                                    <input type="hidden" name="platform_ids[]" value="<?php echo $id; ?>">
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Cast</label>
                        <div class="cast-list">
                            <?php if (!empty($cast)): ?>
                                <?php foreach (array_slice($cast, 0, 10) as $person): ?>
                                    <div class="cast-member">
                                        <img src="<?php echo !empty($person['profile_path']) ? 'https://image.tmdb.org/t/p/w45'.$person['profile_path'] : 'https://via.placeholder.com/40x60.png?text=N/A' ?>" alt="<?php echo htmlspecialchars($person['name']); ?>">
                                        <span><?php echo htmlspecialchars($person['name']); ?> <em>as <?php echo htmlspecialchars($person['character_name']); ?></em></span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>N/A</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="side-column">
                <div class="card">
                    <h3>Media Management</h3>
                    <div class="media-item">
                        <label>Poster</label>
                        <div class="media-preview" id="poster-preview"><img src="https://image.tmdb.org/t/p/w300<?php echo htmlspecialchars($tvShow['poster_path']); ?>" alt="Poster"></div>
                        <div class="media-input-group">
                            <input type="text" id="manual_poster_path" placeholder="https://..." value="https://image.tmdb.org/t/p/original<?php echo htmlspecialchars($tvShow['poster_path']); ?>">
                            <button type="button" onclick="updatePreview('poster')">Set</button>
                        </div>
                    </div>
                    <div class="media-item">
                        <label>Texted Backdrop (for Lists)</label>
                        <div class="media-preview" id="logo_backdrop-preview">
                            <img src="https://image.tmdb.org/t/p/w300<?php echo htmlspecialchars($tvShow['logo_backdrop_path'] ?? $tvShow['backdrop_path']); ?>" alt="Texted Backdrop">
                        </div>
                        <div class="media-input-group">
                            <input type="text" id="manual_logo_backdrop_path" placeholder="https://..." value="https://image.tmdb.org/t/p/original<?php echo htmlspecialchars($tvShow['logo_backdrop_path'] ?? ''); ?>">
                            <button type="button" onclick="updatePreview('logo_backdrop')">Set</button>
                        </div>
                    </div>
                    <div class="media-item">
                        <label>Backdrop</label>
                        <div class="media-preview" id="backdrop-preview"><img src="https://image.tmdb.org/t/p/w300<?php echo htmlspecialchars($tvShow['backdrop_path']); ?>" alt="Backdrop"></div>
                        <div class="media-input-group">
                            <input type="text" id="manual_backdrop_path" placeholder="https://..." value="https://image.tmdb.org/t/p/original<?php echo htmlspecialchars($tvShow['backdrop_path']); ?>">
                            <button type="button" onclick="updatePreview('backdrop')">Set</button>
                        </div>
                    </div>
                     <div class="media-item">
                        <label>Logo</label>
                        <div class="media-preview" id="logo-preview" style="background: #1f2937; padding: 10px;">
                            <?php if (!empty($tvShow['logo_path'])): ?><img src="https://image.tmdb.org/t/p/w300<?php echo htmlspecialchars($tvShow['logo_path']); ?>" alt="Logo"><?php else: ?><p>No logo found.</p><?php endif; ?>
                        </div>
                        <div class="media-input-group">
                            <input type="text" id="manual_logo_path" placeholder="https://..." value="https://image.tmdb.org/t/p/original<?php echo htmlspecialchars($tvShow['logo_path'] ?? ''); ?>">
                            <button type="button" onclick="updatePreview('logo')">Set</button>
                        </div>
                    </div>
                    <div class="media-item">
                        <label>Trailer URL</label>
                         <div class="media-input-group">
                             <input type="text" id="manual_trailer_key" placeholder="https://www.youtube.com/watch?v=..." value="<?php echo !empty($tvShow['trailer_key']) ? 'https://www.youtube.com/watch?v=' . htmlspecialchars($tvShow['trailer_key']) : ''; ?>">
                            <button type="button" onclick="updatePreview('trailer')">Set</button>
                        </div>
                    </div>
                    <div class="form-actions-footer">
                        <a href="/admin/manage-seasons/<?php echo $tvShow['id']; ?>" class="action-button seasons-button">Manage Seasons</a>
                        <button type="submit" class="action-button save-button">Update</button>
                    </div>
                </div>
            </div>
        </div>
        
        <input type="hidden" id="poster_path" name="poster_path" value="<?php echo htmlspecialchars($tvShow['poster_path']); ?>">
        <input type="hidden" id="logo_backdrop_path" name="logo_backdrop_path" value="<?php echo htmlspecialchars($tvShow['logo_backdrop_path'] ?? ''); ?>">
        <input type="hidden" id="backdrop_path" name="backdrop_path" value="<?php echo htmlspecialchars($tvShow['backdrop_path']); ?>">
        <input type="hidden" id="logo_path" name="logo_path" value="<?php echo htmlspecialchars($tvShow['logo_path'] ?? ''); ?>">
        <input type="hidden" id="trailer_key" name="trailer_key" value="<?php echo htmlspecialchars($tvShow['trailer_key'] ?? ''); ?>">
    </form>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Platform ve Kategori seçme kutucukları için JavaScript
    function initializeCustomSelect(containerId, selectedContainerId, dropdownId, hiddenInputContainerId, initialSelectedIds, inputName) {
        const container = document.getElementById(containerId);
        const selectedContainer = document.getElementById(selectedContainerId);
        const dropdown = document.getElementById(dropdownId);
        const hiddenInputsContainer = document.getElementById(hiddenInputContainerId);
        let selectedIds = initialSelectedIds.map(String); 

        function updateHiddenInputs() {
            hiddenInputsContainer.innerHTML = '';
            selectedIds.forEach(id => {
                const newInput = document.createElement('input');
                newInput.type = 'hidden';
                newInput.name = inputName;
                newInput.value = id;
                hiddenInputsContainer.appendChild(newInput);
            });
        }

        selectedContainer.addEventListener('click', () => { dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block'; });

        dropdown.addEventListener('click', (e) => {
            const target = e.target;
            if (target.dataset.id) {
                const id = target.dataset.id;
                const name = target.dataset.name;
                if (!selectedIds.includes(id)) {
                    selectedIds.push(id);
                    const tag = document.createElement('span');
                    tag.className = 'item-tag';
                    tag.dataset.id = id;
                    tag.innerHTML = `${name} <span class="remove-tag" data-id="${id}">&times;</span>`;
                    selectedContainer.appendChild(tag);
                    updateHiddenInputs();
                }
                dropdown.style.display = 'none';
            }
        });

        selectedContainer.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-tag')) {
                e.stopPropagation();
                const id = e.target.dataset.id;
                const index = selectedIds.indexOf(id);
                if (index > -1) { selectedIds.splice(index, 1); }
                e.target.parentElement.remove();
                updateHiddenInputs();
            }
        });

        document.addEventListener('click', (e) => {
            if (container && !container.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });
    }

    initializeCustomSelect('platforms-container', 'selected-platforms', 'platform-dropdown', 'hidden-platform-inputs', <?php echo json_encode($selectedPlatformIds); ?>, 'platform_ids[]');
    initializeCustomSelect('genres-container', 'selected-genres', 'genre-dropdown', 'hidden-genre-inputs', <?php echo json_encode($selectedGenreIds); ?>, 'genre_ids[]');
});

// Medya önizlemesi için JavaScript
function updatePreview(type) {
        const inputElement = document.getElementById('manual_' + type + '_path') || document.getElementById('manual_' + type + '_key');
        const hiddenInputElement = document.getElementById(type + '_path') || document.getElementById(type + '_key');
        const previewElement = document.getElementById(type + '-preview');
        
        let newValue = inputElement.value.trim();

        if (type === 'trailer') {
            let videoId = '';
            if (newValue.includes('youtube.com/watch?v=')) { videoId = newValue.split('v=')[1].split('&')[0]; } 
            else if (newValue.includes('youtu.be/')) { videoId = newValue.split('youtu.be/')[1].split('?')[0]; } 
            else { videoId = newValue; }
            hiddenInputElement.value = videoId;
            if (videoId) {
                previewElement.innerHTML = `<a href="https://www.youtube.com/watch?v=${videoId}" target="_blank">Watch new trailer</a>`;
            } else {
                previewElement.innerHTML = '<p>No trailer found.</p>';
            }
        } else {
            let imagePath = newValue;
            let fullImageUrl = newValue;
            if (newValue.startsWith('http')) {
                if (newValue.includes('themoviedb.org')) {
                     imagePath = '/' + newValue.split('/').slice(5).join('/');
                }
            } else {
                fullImageUrl = 'https://image.tmdb.org/t/p/w300' + newValue;
            }
            hiddenInputElement.value = imagePath;

            if (previewElement.querySelector('img')) {
                previewElement.querySelector('img').src = fullImageUrl;
            } else {
                previewElement.innerHTML = `<img src="${fullImageUrl}" alt="${type}">`;
            }
        }
    }
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>