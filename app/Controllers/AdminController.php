<?php
namespace App\Controllers;
use App\Models\MovieModel;
use App\Models\TvShowModel;
use App\Models\SeasonModel;
use App\Models\EpisodeModel;
use App\Models\GenreModel;
use App\Models\PersonModel;
use App\Models\SettingModel;
use App\Models\MenuModel;
use App\Models\UserModel;
use App\Models\RequestModel;
use App\Services\TMDbService;
use App\Services\MailService;
use App\Models\HomepageModel;
use App\Models\EmailTemplateModel;
use App\Models\VideoLinkModel;
use App\Models\CommentModel;
use App\Models\ReportModel;
use App\Models\PlatformModel;
use App\Models\SubtitleModel;
class AdminController {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
            header('Location: /login');
            exit();
        }
    }

    public function index() {
        $movieModel = new \App\Models\MovieModel();
        $tvShowModel = new \App\Models\TvShowModel();
        $episodeModel = new \App\Models\EpisodeModel();
        $userModel = new \App\Models\UserModel();

        $stats = [
            'movies' => $movieModel->countAll(),
            'tv_shows' => $tvShowModel->countAll(),
            'episodes' => $episodeModel->countAll(),
            'users' => $userModel->countAllUsers()
        ];

        return view('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'stats' => $stats
        ]);
    }

    // --- Movie Methods ---
    public function listMovies() {
        $movieModel = new MovieModel();
        $movies = $movieModel->getAll();
        return view('admin/list-movies', ['title' => 'Manage Movies', 'movies' => $movies]);
    }

    public function showAddMovieForm() {
        return view('admin/add-movie', ['title' => 'Add New Movie']);
    }

    public function storeMovie() {
        // Find this in your storeMovie() function
        $data = [
        'tmdb_id'            => $_POST['tmdb_id'] ?? null,
        'title'              => $_POST['title'] ?? '',
        'overview'           => $_POST['overview'] ?? '',
        'release_date'       => $_POST['release_date'] ?? null,
        'poster_path'        => $_POST['poster_path'] ?? null,
        'backdrop_path'      => $_POST['backdrop_path'] ?? null,
        'logo_backdrop_path' => $_POST['logo_backdrop_path'] ?? null,
        'logo_path'          => $_POST['logo_path'] ?? null,
        'runtime'            => $_POST['runtime'] ?? null,
        'trailer_key'        => $_POST['trailer_key'] ?? null,
        'genres'             => isset($_POST['genres']) ? json_decode($_POST['genres'], true) : [],
        'cast'               => isset($_POST['cast']) ? json_decode($_POST['cast'], true) : [],
        // ADD THE LINES BELOW
        'director'           => $_POST['director'] ?? null,
        'budget'             => $_POST['budget'] ?? null,
        'revenue'            => $_POST['revenue'] ?? null,
        'homepage'           => $_POST['homepage'] ?? null,
        'facebook_id'        => $_POST['facebook_id'] ?? null,
        'instagram_id'       => $_POST['instagram_id'] ?? null,
        'twitter_id'         => $_POST['twitter_id'] ?? null
        ];
        
        $data['slug'] = slugify($data['title']); // YENİ: Başlıktan slug oluştur

        if (empty($data['title'])) { die('Title is required.'); }

        $movieModel = new MovieModel();
        $genreModel = new GenreModel();
        $personModel = new PersonModel();
        $newMovieId = $movieModel->createAndGetId($data);

        if ($newMovieId && !empty($data['genres'])) {
            $genreIds = [];
            foreach ($data['genres'] as $tmdbGenre) {
                $genreIds[] = $genreModel->findOrCreate($tmdbGenre);
            }
            $movieModel->syncGenres($newMovieId, $genreIds);
        }
        if ($newMovieId && !empty($data['cast'])) {
            $castToSync = [];
            foreach ($data['cast'] as $castMember) {
                $personId = $personModel->findOrCreate($castMember);
                $castToSync[] = [
                    'person_id'      => $personId,
                    'character_name' => $castMember['character'],
                    'order'          => $castMember['order']
                ];
            }
            $movieModel->syncCast($newMovieId, $castToSync);
        }
        $platformIds = $_POST['platform_ids'] ?? [];
        if ($newMovieId && !empty($platformIds)) {
            $movieModel->syncPlatforms($newMovieId, $platformIds);
        }
        if ($newMovieId) {
            header('Location: /admin/movies');
            exit();
        } else {
            die('An error occurred while saving the movie.');
        }
    }

    public function showEditMovieForm($id) {
        $movieModel = new MovieModel();
        $genreModel = new GenreModel();
        $personModel = new PersonModel();
        $platformModel = new PlatformModel();
        $allGenres = $genreModel->getAll();

        $movie = $movieModel->findById($id);
        if (!$movie) { http_response_code(404); die('Movie not found.'); }

        $genres = $genreModel->findAllByMovieId($id);
        $cast = $personModel->findCastByMovieId($id);
        $moviePlatforms = $movieModel->findPlatformsByMovieId($id); // HATA BURADAYDI, DÜZELTİLDİ
        $allPlatforms = $platformModel->getAll();

        return view('admin/edit-movie', [
            'title' => 'Edit Movie: ' . $movie['title'],
            'movie' => $movie,
            'genres' => $genres,
            'cast' => $cast,
            'moviePlatforms' => $moviePlatforms,
            'allPlatforms' => $allPlatforms,
            'allGenres' => $allGenres
        ]);
    }

    public function updateMovie($id) {
        $data = [
            'title'              => $_POST['title'] ?? '',
            'overview'           => $_POST['overview'] ?? '',
            'release_date'       => $_POST['release_date'] ?? null,
            'poster_path'        => $_POST['poster_path'] ?? null,
            'backdrop_path'      => $_POST['backdrop_path'] ?? null,
            'logo_backdrop_path' => $_POST['logo_backdrop_path'] ?? null,
            'logo_path'          => $_POST['logo_path'] ?? null,
            'runtime'            => $_POST['runtime'] ?? null,
            'trailer_key'        => $_POST['trailer_key'] ?? null
        ];

        $data['slug'] = slugify($data['title']); // YENİ: Başlıktan slug oluştur

        if (empty($data['title'])) { die('Title is required.'); }

        $movieModel = new MovieModel();
        $success = $movieModel->update($id, $data);
        
        $platformIds = $_POST['platform_ids'] ?? [];
        $genreIds = $_POST['genre_ids'] ?? [];
        $movieModel->syncPlatforms($id, $platformIds);
        $movieModel->syncGenres($id, $genreIds);

        if ($success) {
            header('Location: /admin/movies');
            exit();
        } else {
            die('An error occurred while updating the movie.');
        }
    }

    public function deleteMovie($id) {
        $movieModel = new MovieModel();
        $movieModel->deleteById($id);
        header('Location: /admin/movies');
        exit();
    }

    public function importFromTMDb() {
        $tmdbId = $_POST['tmdb_id'] ?? null;
        if (!$tmdbId) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'No TMDb ID provided.']);
            return;
        }
        $tmdbService = new TMDbService();
        $movieData = $tmdbService->getMovieById($tmdbId);
        header('Content-Type: application/json');
        echo json_encode($movieData);
        return;
    }

    // --- TV Show Methods ---
    public function listTvShows() {
        $tvShowModel = new TvShowModel();
        $tvShows = $tvShowModel->getAll();
        return view('admin/list-tv-shows', ['title' => 'Manage TV Shows', 'tvShows' => $tvShows]);
    }

    public function showAddTvShowForm() {
        return view('admin/add-tv-show', ['title' => 'Add New TV Show']);
    }

    public function storeTvShow() {
    // 1. Dizi verilerini formdan alıyoruz.
    // Başlık alanı, formda 'name' veya 'title' olarak gönderilme ihtimaline karşı kontrol ediliyor.
    // Find this in your storeTvShow() function
        $data = [
        'id'                 => $_POST['tmdb_id'] ?? null,
        'name'               => $_POST['name'] ?? $_POST['title'] ?? '',
        'overview'           => $_POST['overview'] ?? '',
        'first_air_date'     => $_POST['first_air_date'] ?? null,
        // ... other existing fields
        'genres'             => isset($_POST['genres']) ? json_decode($_POST['genres'], true) : [],
        'cast'               => isset($_POST['cast']) ? json_decode($_POST['cast'], true) : [],
        // ADD THE LINES BELOW
        'creator'            => $_POST['creator'] ?? null,
        'network'            => $_POST['network'] ?? null,
        'homepage'           => $_POST['homepage'] ?? null,
        'facebook_id'        => $_POST['facebook_id'] ?? null,
        'instagram_id'       => $_POST['instagram_id'] ?? null,
        'twitter_id'         => $_POST['twitter_id'] ?? null
        ];

    // 2. Dizi başlığından slug'ı otomatik olarak oluşturup $data dizisine ekliyoruz.
    $data['slug'] = slugify($data['name']);

    // 3. Başlığın boş olup olmadığını kontrol ediyoruz.
    if (empty($data['name'])) { die('Title is required.'); }

    // 4. İlgili modelleri çağırıyoruz.
    $tvShowModel = new TvShowModel();
    $genreModel = new GenreModel();
    $personModel = new PersonModel();
    
    // 5. Ana dizi verisini (slug dahil) veritabanına kaydedip yeni ID'yi alıyoruz.
    $newTvShowId = $tvShowModel->createAndGetId($data);
    
    // 6. Türleri, platformları ve oyuncuları senkronize ediyoruz.
    if ($newTvShowId && !empty($data['genres'])) {
        $genreIds = [];
        foreach ($data['genres'] as $tmdbGenre) {
            $genreIds[] = $genreModel->findOrCreate($tmdbGenre);
        }
        $tvShowModel->syncGenres($newTvShowId, $genreIds);
    }

    $platformIds = $_POST['platform_ids'] ?? [];
    if ($newTvShowId && !empty($platformIds)) {
        $tvShowModel->syncPlatforms($newTvShowId, $platformIds);
    }

    if ($newTvShowId && !empty($data['cast'])) {
        $castToSync = [];
        foreach ($data['cast'] as $castMember) {
            $personId = $personModel->findOrCreate($castMember);
            $castToSync[] = [
                'person_id'      => $personId,
                'character_name' => $castMember['character'],
                'order'          => $castMember['order']
            ];
        }
        $tvShowModel->syncCast($newTvShowId, $castToSync);
    }

    // 7. İşlem başarılıysa admin panelindeki ilgili sayfaya yönlendiriyoruz.
    if ($newTvShowId) {
        header('Location: /admin/tv-shows/edit/' . $newTvShowId);
        exit();
    } else {
        die('An error occurred while saving the TV Show.');
    }
}
    
    public function showEditTvShowForm($id) {
        $tvShowModel = new TvShowModel();
        $seasonModel = new SeasonModel();
        $episodeModel = new EpisodeModel();
        $genreModel = new GenreModel();
        $personModel = new PersonModel();
        $platformModel = new PlatformModel();

        $tvShow = $tvShowModel->findById($id);
        if (!$tvShow) { http_response_code(404); die('TV Show not found.'); }

        $seasons = $seasonModel->findAllByTvShowId($id);
        foreach ($seasons as $key => $season) {
            $seasons[$key]['episodes'] = $episodeModel->findAllBySeasonId($season['id']);
        }
        
        $genres = $genreModel->findAllByTvShowId($id);
        $cast = $personModel->findCastByTvShowId($id);
        $tvShowPlatforms = $tvShowModel->findPlatformsByTvShowId($id); // HATA BURADAYDI, DÜZELTİLDİ
        $allPlatforms = $platformModel->getAll();

        return view('admin/edit-tv-show', [
            'title' => 'Edit TV Show: ' . $tvShow['title'],
            'tvShow' => $tvShow,
            'seasons' => $seasons,
            'genres' => $genres,
            'cast' => $cast,
            'tvShowPlatforms' => $tvShowPlatforms,
            'allPlatforms' => $allPlatforms
        ]);
    }

    public function updateTvShow($id) {
    $data = [
        // Dizi başlığını hem 'name' hem de 'title' olarak arayarak alıyoruz.
        'title'              => $_POST['name'] ?? $_POST['title'] ?? '',
        'overview'           => $_POST['overview'] ?? '',
        'first_air_date'     => $_POST['first_air_date'] ?? null,
        'status'             => $_POST['status'] ?? null,
        'logo_backdrop_path' => $_POST['logo_backdrop_path'] ?? null,
        'logo_path'          => $_POST['logo_path'] ?? null,
        'trailer_key'        => $_POST['trailer_key'] ?? null
    ];

    // ==========================================================
    // EKSİK OLAN SATIRI BURAYA EKLİYORUZ
    // Başlıktan slug oluşturuyoruz.
    // ==========================================================
    $data['slug'] = slugify($data['title']);

    if (empty($data['title'])) { die('Title is required.'); }

    $tvShowModel = new TvShowModel();
    $success = $tvShowModel->update($id, $data);
    
    $platformIds = $_POST['platform_ids'] ?? [];
    $tvShowModel->syncPlatforms($id, $platformIds);
    
    if ($success) {
        header('Location: /admin/tv-shows/edit/' . $id);
        exit();
    } else {
        die('An error occurred while updating the TV show.');
    }
}

    public function deleteTvShow($id) {
        $tvShowModel = new TvShowModel();
        $tvShowModel->deleteById($id);
        header('Location: /admin/tv-shows');
        exit();
    }

    public function importTvShowFromTMDb() {
        $tmdbId = $_POST['tmdb_id'] ?? null;
        if (!$tmdbId) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'No TMDb ID provided.']);
            return;
        }
        $tmdbService = new TMDbService();
        $tvShowData = $tmdbService->getTvShowById($tmdbId);
        header('Content-Type: application/json');
        echo json_encode($tvShowData);
        return;
    }
    
    public function importSeasons($tvShowDbId, $tvShowTmdbId) {
        $tmdbService = new TMDbService();
        $tvShowData = $tmdbService->getTvShowById($tvShowTmdbId);
        if (isset($tvShowData['seasons'])) {
            $seasonModel = new SeasonModel();
            $episodeModel = new EpisodeModel();
            foreach ($tvShowData['seasons'] as $seasonData) {
                if ($seasonData['season_number'] == 0) continue;
                $seasonIdInDb = $seasonModel->findOrCreate($tvShowDbId, $seasonData);
                $seasonDetails = $tmdbService->getSeasonDetails($tvShowTmdbId, $seasonData['season_number']);
                if (isset($seasonDetails['episodes'])) {
                    foreach ($seasonDetails['episodes'] as $episodeData) {
                        $episodeModel->create($seasonIdInDb, $episodeData);
                    }
                }
            }
        }
        header('Location: /admin/manage-seasons/' . $tvShowDbId);
        exit();
    }
    
    // --- Episode Methods ---
    public function showEditEpisodeForm($id) {
        $episodeModel = new EpisodeModel();
        $episode = $episodeModel->findById($id);

        if (!$episode) {
            http_response_code(404);
            die('Episode not found.');
        }
        
        $seasonModel = new SeasonModel();
        $season = $seasonModel->findById($episode['season_id']);

        return view('admin/edit-episode', [
            'title' => 'Edit Episode',
            'episode' => $episode,
            'tvShowId' => $season['tv_show_id']
        ]);
    }

    public function updateEpisode($id) {
        $data = [
            'name'      => $_POST['name'] ?? '',
            'overview'  => $_POST['overview'] ?? '',
            'video_url' => $_POST['video_url'] ?? null
        ];

        if (empty($data['name'])) { die('Episode name is required.'); }

        $episodeModel = new EpisodeModel();
        $success = $episodeModel->update($id, $data);
        
        $episode = $episodeModel->findById($id);
        $seasonModel = new SeasonModel();
        $season = $seasonModel->findById($episode['season_id']);

        if ($success) {
            header('Location: /admin/tv-shows/edit/' . $season['tv_show_id']);
            exit();
        } else {
            die('An error occurred while updating the episode.');
        }
    }

    // --- Settings Methods ---
    
// --- Settings Methods ---
    
    public function showGeneralSettingsForm() {
        $settingModel = new SettingModel();
        $settings = $settingModel->getAllSettings();

        return view('admin/settings-general', [
            'title' => 'General Settings',
            'settings' => $settings
        ]);
    }

    public function updateGeneralSettings() {
        $settingModel = new SettingModel();
        $data = $_POST;
        
        $uploadDir = __DIR__ . '/../../public/assets/images/';

        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $logoName = 'logo_' . time() . '_' . basename($_FILES['logo']['name']);
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadDir . $logoName)) {
                $data['logo_path'] = $logoName;
            }
        }

        if (isset($_FILES['favicon']) && $_FILES['favicon']['error'] === UPLOAD_ERR_OK) {
            $faviconName = 'favicon_' . time() . '_' . basename($_FILES['favicon']['name']);
            if (move_uploaded_file($_FILES['favicon']['tmp_name'], $uploadDir . $faviconName)) {
                $data['favicon_path'] = $faviconName;
            }
        }
        
        $settingModel->updateSettings($data);
        header('Location: /admin/settings/general');
        exit();
    }

    public function showApiSettingsForm() {
        $settingModel = new SettingModel();
        $settings = $settingModel->getAllSettings();
        return view('admin/settings-api', ['title' => 'API Settings', 'settings' => $settings]);
    }

    public function updateApiSettings() {
        $data = ['tmdb_api_key' => $_POST['tmdb_api_key'] ?? ''];
        $settingModel = new SettingModel();
        $settingModel->updateSettings($data);
        header('Location: /admin/settings/api');
        exit();
    }

    public function showSecuritySettingsForm() {
        $settingModel = new SettingModel();
        $settings = $settingModel->getAllSettings();
        return view('admin/settings-security', ['title' => 'Security Settings', 'settings' => $settings]);
    }

    public function updateSecuritySettings() {
        $data = ['login_required' => $_POST['login_required'] ?? 0];
        $settingModel = new SettingModel();
        $settingModel->updateSettings($data);
        header('Location: /admin/settings/security');
        exit();
    }

    // --- Menu Manager Methods ---

   public function listMenuItems() {
       $menuModel = new MenuModel();
       $menuItems = $menuModel->getAllForAdmin();
       return view('admin/menu-manager', ['title' => 'Menu Manager', 'menuItems' => $menuItems]);
   }
   public function showAddMenuItemForm() {
       return view('admin/add-menu-item', ['title' => 'Add New Menu Item']);
   }
   public function storeMenuItem() {
       $data = ['title' => $_POST['title'] ?? '', 'url' => $_POST['url'] ?? '', 'menu_order' => $_POST['menu_order'] ?? 0, 'is_active' => $_POST['is_active'] ?? 0];
       if (empty($data['title']) || empty($data['url'])) { die('Title and URL are required.'); }
       $menuModel = new MenuModel();
       if ($menuModel->create($data)) { header('Location: /admin/menu'); exit(); }
       else { die('An error occurred.'); }
   }
   public function showEditMenuItemForm($id) {
       $menuModel = new MenuModel();
       $menuItem = $menuModel->findById($id);
       if (!$menuItem) { http_response_code(404); die('Menu item not found.'); }
       return view('admin/edit-menu-item', ['title' => 'Edit Menu Item', 'menuItem' => $menuItem]);
   }
   public function updateMenuItem($id) {
       $data = ['title' => $_POST['title'] ?? '', 'url' => $_POST['url'] ?? '', 'menu_order' => $_POST['menu_order'] ?? 0, 'is_active' => $_POST['is_active'] ?? 0];
       if (empty($data['title']) || empty($data['url'])) { die('Title and URL are required.'); }
       $menuModel = new MenuModel();
       if ($menuModel->update($id, $data)) { header('Location: /admin/menu'); exit(); }
       else { die('An error occurred.'); }
   }
   public function deleteMenuItem($id) {
       $menuModel = new MenuModel();
       $menuModel->deleteById($id);
       header('Location: /admin/menu');
       exit();
   }

   // --- SMTP & Mail Methods ---

    public function showSmtpSettingsForm() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $settingModel = new SettingModel();
        $settings = $settingModel->getAllSettings();

        $flashMessage = $_SESSION['flash_message'] ?? null;
        unset($_SESSION['flash_message']);

        return view('admin/smtp-settings', [
            'title' => 'SMTP Settings',
            'settings' => $settings,
            'flashMessage' => $flashMessage
        ]);
    }

    public function updateSmtpSettings() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $settingModel = new SettingModel();
        
        // Formdan gelen verileri al
        $data = [
            'site_email' => $_POST['site_email'] ?? '',
            'smtp_host' => $_POST['smtp_host'] ?? '',
            'smtp_port' => $_POST['smtp_port'] ?? '',
            'smtp_secure' => $_POST['smtp_secure'] ?? 'tls',
            'smtp_user' => $_POST['smtp_user'] ?? '',
        ];

        // Şifre alanı sadece yeni bir şifre girildiyse güncellenir
        if (!empty($_POST['smtp_pass'])) {
            $data['smtp_pass'] = $_POST['smtp_pass'];
        }

        $settingModel->updateSettings($data);

        $_SESSION['flash_message'] = 'SMTP settings have been updated.';
        header('Location: /admin/smtp-settings');
        exit();
    }

    public function sendTestEmail() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $mailService = new MailService();
        
        $toEmail = $_SESSION['user_email']; // Test e-postasını giriş yapmış olan admin'e gönder
        $toName = 'Admin';
        $subject = 'Test Email from ' . setting('site_name');
        $body = '<h1>SMTP Settings Correct!</h1><p>This is a test email to confirm that your SMTP settings are configured correctly.</p>';

        $success = $mailService->send($toEmail, $toName, $subject, $body);

        $_SESSION['flash_message'] = $success 
            ? 'Test email sent successfully to ' . htmlspecialchars($toEmail)
            : 'Failed to send test email. Please check your SMTP settings and MailHog/mail server logs.';

        header('Location: /admin/smtp-settings');
        exit();
    }

    // --- Email Template Methods ---

    public function showEmailTemplatesForm() {
        $templateModel = new \App\Models\EmailTemplateModel();
        $templates = $templateModel->getAllTemplates();
        
        return view('admin/email-templates', [
            'title' => 'Email Templates',
            'templates' => $templates
        ]);
    }

    public function updateEmailTemplates() {
        $templatesData = $_POST['templates'] ?? [];
        
        if (!empty($templatesData)) {
            $templateModel = new \App\Models\EmailTemplateModel();
            $templateModel->updateTemplates($templatesData);
        }

        // Başarı mesajı ile geri yönlendir (ileride eklenecek)
        header('Location: /admin/settings/email-templates');
        exit();
    }

    public function manageSeasons($tvShowId) {
        $tvShowModel = new \App\Models\TvShowModel();
        $seasonModel = new \App\Models\SeasonModel();

        // Dizinin kendi bilgilerini de alalım ki sayfada başlık olarak gösterebilelim
        $tvShow = $tvShowModel->findById($tvShowId);
        if (!$tvShow) {
            http_response_code(404);
            die('TV Show not found.');
        }
        
        // Bu diziye ait tüm sezonları veritabanından çekelim
        $seasons = $seasonModel->findAllByTvShowId($tvShowId);

        // Verileri view'e gönderelim
        return view('admin/manage-seasons', [
            'title' => 'Manage Seasons: ' . htmlspecialchars($tvShow['title']),
            'tvShow' => $tvShow,
            'seasons' => $seasons
        ]);
    }

// --- Movie Link & Subtitle Methods ---

    public function showMovieLinksManager($movieId) {
        $movieModel = new \App\Models\MovieModel();
        $movie = $movieModel->findById($movieId);
        if (!$movie) { http_response_code(404); die('Movie not found.'); }

        $videoLinkModel = new \App\Models\VideoLinkModel();
        $links = $videoLinkModel->findAllByMovieId($movieId);

        return view('admin/manage-movie-links', [
            'title' => 'Manage Links: ' . htmlspecialchars($movie['title']),
            'movie' => $movie,
            'links' => $links
        ]);
    }

    public function storeMovieLink($movieId) {
        $data = [
            'movie_id' => $movieId,
            'label' => $_POST['label'] ?? null,
            'quality' => $_POST['quality'] ?? null,
            'size' => $_POST['size'] ?? null,
            'source' => $_POST['source'] ?? '',
            'url' => $_POST['url'] ?? '',
            'link_type' => $_POST['link_type'] ?? 'stream',
            'status' => $_POST['status'] ?? 'publish'
        ];

        if (empty($data['source']) || empty($data['url'])) {
            die('Source and URL are required.');
        }
        
        $videoLinkModel = new \App\Models\VideoLinkModel();
        $videoLinkModel->create($data);

        header('Location: /admin/manage-movie-links/' . $movieId);
        exit();
    }

    public function showEditMovieLinkForm($linkId) {
        $videoLinkModel = new \App\Models\VideoLinkModel();
        $link = $videoLinkModel->findById($linkId);

        if (!$link) {
            http_response_code(404);
            die('Video link not found.');
        }

        $movieModel = new \App\Models\MovieModel();
        $movie = $movieModel->findById($link['movie_id']);

        return view('admin/edit-movie-link', [
            'title' => 'Edit Link for: ' . htmlspecialchars($movie['title']),
            'link' => $link,
            'movie' => $movie
        ]);
    }

    public function updateMovieLink($linkId) {
        $videoLinkModel = new \App\Models\VideoLinkModel();
        $link = $videoLinkModel->findById($linkId);
        if (!$link) {
            die('Link not found.');
        }
        
        $data = [
            'label' => $_POST['label'] ?? null,
            'quality' => $_POST['quality'] ?? null,
            'size' => $_POST['size'] ?? null,
            'source' => $_POST['source'] ?? '',
            'url' => $_POST['url'] ?? '',
            'link_type' => $_POST['link_type'] ?? 'stream',
            'status' => $_POST['status'] ?? 'publish'
        ];

        if (empty($data['source']) || empty($data['url'])) {
            die('Source and URL are required.');
        }
        
        $videoLinkModel->update($linkId, $data);

        header('Location: /admin/manage-movie-links/' . $link['movie_id']);
        exit();
    }

    public function deleteMovieLink($linkId, $movieId) {
        $videoLinkModel = new \App\Models\VideoLinkModel();
        $videoLinkModel->deleteById($linkId);
        
        header('Location: /admin/manage-movie-links/' . $movieId);
        exit();
    }

    public function storeManualSeason($tvShowId) {
        $data = [
            'tv_show_id' => $tvShowId,
            'season_number' => $_POST['season_number'] ?? null,
            'name' => $_POST['name'] ?? ''
        ];

        if (empty($data['season_number']) || empty($data['name'])) {
            die('Season Number and Name are required.');
        }
        
        $seasonModel = new \App\Models\SeasonModel();
        $seasonModel->createManual($data);

        // İşlem sonrası Sezon Yönetimi sayfasına geri dön
        header('Location: /admin/manage-seasons/' . $tvShowId);
        exit();
    }

    public function deleteSeason($seasonId) {
        $seasonModel = new \App\Models\SeasonModel();
        
        // Yönlendirme yapabilmek için önce sezonu bulup tv_show_id'sini almamız gerekiyor.
        $season = $seasonModel->findById($seasonId);
        if (!$season) {
            die('Season not found.');
        }
        $tvShowId = $season['tv_show_id'];

        // Sezonu ve bağlı bölümleri sil
        $seasonModel->deleteById($seasonId);

        // Sezon yönetimi sayfasına geri dön
        header('Location: /admin/manage-seasons/' . $tvShowId);
        exit();
    }

    public function showEditSeasonForm($seasonId) {
        // Bu metod, popup kullandığımız için doğrudan bir sayfa göstermeyecek,
        // ama POST isteğini aynı URL'den alabilmemiz için gereklidir.
        $seasonModel = new \App\Models\SeasonModel();
        $season = $seasonModel->findById($seasonId);
        if (!$season) { die('Season not found.'); }
        
        // Eğer birisi bu adrese doğrudan girmeye çalışırsa, onu sezon listesine geri yolla.
        header('Location: /admin/manage-seasons/' . $season['tv_show_id']);
        exit();
    }

    public function updateSeason($seasonId) {
        $seasonModel = new \App\Models\SeasonModel();
        // Yönlendirme için sezonun ait olduğu dizi ID'sine ihtiyacımız var.
        $season = $seasonModel->findById($seasonId);
        if (!$season) { die('Season not found.'); }

        $data = [
            'season_number' => $_POST['season_number'] ?? '',
            'name' => $_POST['name'] ?? ''
        ];

        if (empty($data['season_number']) || empty($data['name'])) {
            die('Season Number and Name are required.');
        }
        
        $seasonModel->update($seasonId, $data);

        header('Location: /admin/manage-seasons/' . $season['tv_show_id']);
        exit();
    }

    // --- Episode Management Methods ---

    public function manageEpisodes($seasonId) {
        $seasonModel = new \App\Models\SeasonModel();
        $season = $seasonModel->findById($seasonId);
        if (!$season) {
            http_response_code(404);
            die('Season not found.');
        }

        $tvShowModel = new \App\Models\TvShowModel();
        $tvShow = $tvShowModel->findById($season['tv_show_id']);

        $episodeModel = new \App\Models\EpisodeModel();
        $episodes = $episodeModel->findAllBySeasonId($seasonId);

        return view('admin/manage-episodes', [
            'title' => 'Manage Episodes: ' . htmlspecialchars($season['name']),
            'season' => $season,
            'tvShow' => $tvShow,
            'episodes' => $episodes
        ]);
    }

    public function fetchEpisodesForSeason($seasonId) {
        $seasonModel = new \App\Models\SeasonModel();
        $season = $seasonModel->findById($seasonId);
        if (!$season) { die('Season not found.'); }

        $tvShowModel = new \App\Models\TvShowModel();
        $tvShow = $tvShowModel->findById($season['tv_show_id']);
        if (!$tvShow) { die('TV Show not found.'); }

        $tmdbService = new \App\Services\TMDbService();
        $seasonDetails = $tmdbService->getSeasonDetails($tvShow['tmdb_id'], $season['season_number']);

        if (isset($seasonDetails['episodes'])) {
            $episodeModel = new \App\Models\EpisodeModel();
            foreach ($seasonDetails['episodes'] as $episodeData) {
                // Mevcut create metodumuz zaten duplikasyon kontrolü yapıyor
                $episodeModel->create($seasonId, $episodeData);
            }
        }

        // İşlem bittikten sonra bölümleri yönetme sayfasına geri dön
        header('Location: /admin/manage-episodes/' . $seasonId);
        exit();
    }

    public function storeManualEpisode($seasonId) {
        $data = [
            'season_id' => $seasonId,
            'episode_number' => $_POST['episode_number'] ?? null,
            'name' => $_POST['name'] ?? '',
            'overview' => $_POST['overview'] ?? ''
        ];

        if (empty($data['episode_number']) || empty($data['name'])) {
            die('Episode Number and Name are required.');
        }
        
        $episodeModel = new \App\Models\EpisodeModel();
        $episodeModel->createManual($data);

        // İşlem sonrası bölümleri yönetme sayfasına geri dön
        header('Location: /admin/manage-episodes/' . $seasonId);
        exit();
    }

    public function deleteEpisode($episodeId) {
        $episodeModel = new \App\Models\EpisodeModel();
        
        // Yönlendirme yapabilmek için önce bölümü bulup season_id'sini almamız gerekiyor.
        $episode = $episodeModel->findById($episodeId);
        if (!$episode) {
            die('Episode not found.');
        }
        $seasonId = $episode['season_id'];

        // Bölümü ve bağlı olan her şeyi (linkler, altyazılar) sil
        $episodeModel->deleteById($episodeId);

        // Bölümleri yönetme sayfasına geri dön
        header('Location: /admin/manage-episodes/' . $seasonId);
        exit();
    }

    public function updateEpisodeDetails($episodeId) {
        $episodeModel = new \App\Models\EpisodeModel();
        
        // Yönlendirme yapabilmek için önce bölümü bulup season_id'sini almamız gerekiyor.
        $episode = $episodeModel->findById($episodeId);
        if (!$episode) {
            die('Episode not found.');
        }
        $seasonId = $episode['season_id'];

        $data = [
            'episode_number' => $_POST['episode_number'] ?? null,
            'name' => $_POST['name'] ?? '',
            'overview' => $_POST['overview'] ?? '',
            'air_date' => $_POST['air_date'] ?? null
        ];

        if (empty($data['episode_number']) || empty($data['name'])) {
            die('Episode Number and Name are required.');
        }
        
        $episodeModel->update($episodeId, $data);

        // Bölümleri yönetme sayfasına geri dön
        header('Location: /admin/manage-episodes/' . $seasonId);
        exit();
    }

    // --- Episode Link & Subtitle Methods ---

    public function showEpisodeLinksManager($episodeId) {
        $episodeModel = new \App\Models\EpisodeModel();
        $episode = $episodeModel->findById($episodeId);
        if (!$episode) { http_response_code(404); die('Episode not found.'); }

        $videoLinkModel = new \App\Models\VideoLinkModel();
        $links = $videoLinkModel->findAllByEpisodeId($episodeId);

        // "Geri Dön" linki için sezon bilgisine ihtiyacımız var
        $seasonModel = new \App\Models\SeasonModel();
        $season = $seasonModel->findById($episode['season_id']);

        return view('admin/manage-episode-links', [
            'title' => 'Manage Links: ' . htmlspecialchars($episode['name']),
            'episode' => $episode,
            'season' => $season,
            'links' => $links
        ]);
    }

    public function storeEpisodeLink($episodeId) {
        $data = [
            'episode_id' => $episodeId,
            'label' => $_POST['label'] ?? null,
            'quality' => $_POST['quality'] ?? null,
            'size' => $_POST['size'] ?? null,
            'source' => $_POST['source'] ?? '',
            'url' => $_POST['url'] ?? '',
            'link_type' => $_POST['link_type'] ?? 'stream',
            'status' => $_POST['status'] ?? 'publish'
        ];
        if (empty($data['source']) || empty($data['url'])) { die('Source and URL are required.'); }
        
        $videoLinkModel = new \App\Models\VideoLinkModel();
        $videoLinkModel->create($data);

        header('Location: /admin/manage-episode-links/' . $episodeId);
        exit();
    }

    public function deleteEpisodeLink($linkId, $episodeId) {
        $videoLinkModel = new \App\Models\VideoLinkModel();
        $videoLinkModel->deleteById($linkId);
        
        header('Location: /admin/manage-episode-links/' . $episodeId);
        exit();
    }

    // --- Unified Subtitle Methods ---

    public function showSubtitleManager($linkId) {
        $videoLinkModel = new \App\Models\VideoLinkModel();
        $link = $videoLinkModel->findById($linkId);
        if (!$link) { http_response_code(404); die('Video link not found.'); }

        $subtitleModel = new \App\Models\SubtitleModel();
        $subtitles = $subtitleModel->findAllByVideoLinkId($linkId);

        $context = [];
        // Bu linkin bir filme mi yoksa bölüme mi ait olduğunu anla
        if (!empty($link['movie_id'])) {
            $movieModel = new \App\Models\MovieModel();
            $movie = $movieModel->findById($link['movie_id']);
            $context['type'] = 'movie';
            $context['parent'] = $movie;
            $context['back_link'] = '/admin/manage-movie-links/' . $movie['id'];
            $context['title_text'] = 'for Movie: ' . htmlspecialchars($movie['title']);
        } elseif (!empty($link['episode_id'])) {
            $episodeModel = new \App\Models\EpisodeModel();
            $episode = $episodeModel->findById($link['episode_id']);
            $seasonModel = new \App\Models\SeasonModel();
            $season = $seasonModel->findById($episode['season_id']);
            $context['type'] = 'episode';
            $context['parent'] = $episode;
            $context['season'] = $season;
            $context['back_link'] = '/admin/manage-episode-links/' . $episode['id'];
            $context['title_text'] = 'for Episode: ' . htmlspecialchars($episode['name']);
        }

        return view('admin/manage_subtitles', [
            'title' => 'Manage Subtitles',
            'link' => $link,
            'subtitles' => $subtitles,
            'context' => $context
        ]);
    }

    public function storeSubtitle($linkId) {
        $data = [
            'video_link_id' => $linkId,
            'language' => $_POST['language'] ?? '',
            'url' => $_POST['url'] ?? '',
            'type' => $_POST['type'] ?? 'vtt',
            'status' => $_POST['status'] ?? 'publish'
        ];
        if (empty($data['language']) || empty($data['url'])) { die('Language and URL are required.'); }
        
        $subtitleModel = new \App\Models\SubtitleModel();
        $subtitleModel->create($data);

        header('Location: /admin/manage_subtitles/' . $linkId);
        exit();
    }

    public function deleteSubtitle($subtitleId, $linkId) {
        $subtitleModel = new \App\Models\SubtitleModel();
        $subtitleModel->deleteById($subtitleId);
        
        header('Location: /admin/manage_subtitles/' . $linkId);
        exit();
    }

    // --- User Management Methods ---

    public function listUsers() {
        $userModel = new \App\Models\UserModel();
        $users = $userModel->getAllUsers();
        return view('admin/list-users', [
            'title' => 'User Management',
            'users' => $users
        ]);
    }

    public function showEditUserForm($id) {
        $userModel = new \App\Models\UserModel();
        $user = $userModel->findById($id);
        if (!$user) { http_response_code(404); die('User not found.'); }

        return view('admin/edit-user', [
            'title' => 'Edit User: ' . htmlspecialchars($user['email']),
            'user' => $user
        ]);
    }

    public function updateUser($id) {
        $data = [
            'email' => $_POST['email'] ?? '',
            'is_admin' => $_POST['is_admin'] ?? 0,
            'password' => $_POST['password'] ?? '' // Sadece yeni şifre girilirse güncellenir
        ];

        if (empty($data['email'])) { die('Email cannot be empty.'); }

        $userModel = new \App\Models\UserModel();
        $userModel->update($id, $data);

        header('Location: /admin/users');
        exit();
    }

    public function deleteUser($id) {
        // Adminin kendi hesabını silmesini engelle
        if (isset($_SESSION['user_id']) && $id == $_SESSION['user_id']) {
            die("You cannot delete your own account.");
        }

        $userModel = new \App\Models\UserModel();
        $userModel->deleteById($id);

        header('Location: /admin/users');
        exit();
    }

    // --- Comment Management Methods ---

    public function listComments() {
        $commentModel = new \App\Models\CommentModel();
        $comments = $commentModel->getAllCommentsWithUsers();

        return view('admin/list-comments', [
            'title' => 'Comment Management',
            'comments' => $comments
        ]);
    }

    public function approveComment($commentId) {
        $commentModel = new \App\Models\CommentModel();
        $commentModel->updateStatus($commentId, 'approved');
        header('Location: /admin/comments');
        exit();
    }

    public function unapproveComment($commentId) {
        $commentModel = new \App\Models\CommentModel();
        $commentModel->updateStatus($commentId, 'pending');
        header('Location: /admin/comments');
        exit();
    }

    public function deleteComment($commentId) {
        $commentModel = new \App\Models\CommentModel();
        $commentModel->deleteById($commentId);
        header('Location: /admin/comments');
        exit();
    }

    // --- Report Management Methods ---

    public function listReports() {
        $reportModel = new \App\Models\ReportModel();
        $reports = $reportModel->getAllReportsWithDetails();

        return view('admin/list-reports', [
            'title' => 'Report Management',
            'reports' => $reports
        ]);
    }

    public function resolveReport($reportId) {
        $reportModel = new \App\Models\ReportModel();
        $reportModel->updateStatus($reportId, 'resolved');
        header('Location: /admin/reports');
        exit();
    }

    public function deleteReport($reportId) {
        $reportModel = new \App\Models\ReportModel();
        $reportModel->deleteById($reportId);
        header('Location: /admin/reports');
        exit();
    }

    // --- Request Management Methods ---

    public function listRequests() {
        $requestModel = new \App\Models\RequestModel();
        $requests = $requestModel->getAllRequestsWithUsers();

        return view('admin/list-requests', [
            'title' => 'Request Management',
            'requests' => $requests
        ]);
    }

    public function updateRequestStatus($requestId, $status) {
        $requestModel = new \App\Models\RequestModel();
        $requestModel->updateStatus($requestId, $status);
        header('Location: /admin/requests');
        exit();
    }

    public function deleteRequest($requestId) {
        $requestModel = new \App\Models\RequestModel();
        $requestModel->deleteById($requestId);
        header('Location: /admin/requests');
        exit();
    }

    // --- ADS Settings Methods ---

    public function showAdsSettingsForm() {
        $settingModel = new \App\Models\SettingModel();
        $settings = $settingModel->getAllSettings();
        return view('admin/ads-settings', [
            'title' => 'ADS Settings',
            'settings' => $settings
        ]);
    }

    public function updateAdsSettings() {
        $settingModel = new \App\Models\SettingModel();
        $data = $_POST;
        
        $settingModel->updateSettings($data);

        header('Location: /admin/ads-settings');
        exit();
    }

    // --- Genre Management Methods ---

    public function listGenres() {
        $genreModel = new \App\Models\GenreModel();
        $genres = $genreModel->getAll();
        return view('admin/list-genres', [
            'title' => 'Genre Management',
            'genres' => $genres
        ]);
    }

    public function showAddGenreForm() {
        return view('admin/add-genre', ['title' => 'Add New Genre']);
    }

    public function storeGenre() {
        $name = $_POST['name'] ?? '';
        if (empty($name)) {
            die('Genre name is required.');
        }

        $genreModel = new \App\Models\GenreModel();
        $genreModel->create($name);

        header('Location: /admin/genres');
        exit();
    }

    public function showEditGenreForm($id) {
        $genreModel = new \App\Models\GenreModel();
        $genre = $genreModel->findById($id);
        if (!$genre) {
            http_response_code(404);
            die('Genre not found.');
        }

        return view('admin/edit-genre', [
            'title' => 'Edit Genre',
            'genre' => $genre
        ]);
    }

    public function updateGenre($id) {
        $name = $_POST['name'] ?? '';
        if (empty($name)) {
            die('Genre name is required.');
        }

        $genreModel = new \App\Models\GenreModel();
        $genreModel->update($id, $name);

        header('Location: /admin/genres');
        exit();
    }

    public function deleteGenre($id) {
        $genreModel = new \App\Models\GenreModel();
        $success = $genreModel->deleteById($id);

        if (!$success) {
            die('This genre cannot be deleted because it is currently in use by movies or TV shows.');
        }

        header('Location: /admin/genres');
        exit();
    }
    

    // --- Homepage (Content Networks) Management Methods ---

    public function showContentNetworksForm() {
        $homepageModel = new \App\Models\HomepageModel();
        $sections = $homepageModel->getAllSections();

        return view('admin/content-networks', [
            'title' => 'Content Networks',
            'sections' => $sections
        ]);
    }

    public function updateContentNetworks() {
        $sectionsData = $_POST['sections'] ?? [];
        
        if (!empty($sectionsData)) {
            $homepageModel = new \App\Models\HomepageModel();
            $homepageModel->updateSections($sectionsData);
        }

        header('Location: /admin/content-networks');
        exit();
    }

    // --- Content Platform Management Methods ---

    public function listPlatforms() {
        $platformModel = new \App\Models\PlatformModel();
        $platforms = $platformModel->getAll();

        return view('admin/list-platforms', [
            'title' => 'Content Platforms',
            'platforms' => $platforms
        ]);
    }

    public function storePlatform() {
        $name = $_POST['name'] ?? '';
        $slug = $_POST['slug'] ?? '';
        if (empty($name) || empty($slug)) { die('Platform name and slug are required.'); }

        $data = ['name' => $name, 'slug' => $slug];
        
        $uploadDir = __DIR__ . '/../../public/assets/images/platforms/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (isset($_FILES['logo_path']) && $_FILES['logo_path']['error'] === UPLOAD_ERR_OK) {
            $logoName = time() . '_logo_' . basename($_FILES['logo_path']['name']);
            if (move_uploaded_file($_FILES['logo_path']['tmp_name'], $uploadDir . $logoName)) {
                $data['logo_path'] = $logoName;
            }
        }

        if (isset($_FILES['background_path']) && $_FILES['background_path']['error'] === UPLOAD_ERR_OK) {
            $bgName = time() . '_bg_' . basename($_FILES['background_path']['name']);
            if (move_uploaded_file($_FILES['background_path']['tmp_name'], $uploadDir . $bgName)) {
                $data['background_path'] = $bgName;
            }
        }
        
        $platformModel = new \App\Models\PlatformModel();
        $platformModel->create($data);
        header('Location: /admin/platforms');
        exit();
    }

    public function deletePlatform($id) {
        $platformModel = new \App\Models\PlatformModel();
        $platformModel->deleteById($id);
        header('Location: /admin/platforms');
        exit();
    }

    public function updatePlatform($id) {
        $name = $_POST['name'] ?? '';
        $slug = $_POST['slug'] ?? '';
        if (empty($name) || empty($slug)) { die('Platform name and slug are required.'); }

        $platformModel = new \App\Models\PlatformModel();
        $platform = $platformModel->findById($id);
        if (!$platform) { die('Platform not found.'); }

        $data = [
            'name' => $name,
            'slug' => $slug,
            'logo_path' => $platform['logo_path'],
            'background_path' => $platform['background_path']
        ];
        
        $uploadDir = __DIR__ . '/../../public/assets/images/platforms/';

        if (isset($_FILES['logo_path']) && $_FILES['logo_path']['error'] === UPLOAD_ERR_OK) {
            if (!empty($data['logo_path']) && file_exists($uploadDir . $data['logo_path'])) {
                unlink($uploadDir . $data['logo_path']);
            }
            $logoName = time() . '_logo_' . basename($_FILES['logo_path']['name']);
            if (move_uploaded_file($_FILES['logo_path']['tmp_name'], $uploadDir . $logoName)) {
                $data['logo_path'] = $logoName;
            }
        }

        if (isset($_FILES['background_path']) && $_FILES['background_path']['error'] === UPLOAD_ERR_OK) {
             if (!empty($data['background_path']) && file_exists($uploadDir . $data['background_path'])) {
                unlink($uploadDir . $data['background_path']);
            }
            $bgName = time() . '_bg_' . basename($_FILES['background_path']['name']);
            if (move_uploaded_file($_FILES['background_path']['tmp_name'], $uploadDir . $bgName)) {
                $data['background_path'] = $bgName;
            }
        }
        
        $platformModel->update($id, $data);
        header('Location: /admin/platforms');
        exit();
    }    

    // --- Search Management ---
    public function recordSearch() {
        $searchTerm = $_POST['search_term'] ?? '';
        if (!empty($searchTerm)) {
            $searchModel = new \App\Models\SearchModel();
            $searchModel->recordSearch($searchTerm);
        }
        // Bu metod bir arayüz göstermez, sadece arama kaydını yapar.
        // Arama sonuçları sayfası ileride oluşturulacak.
        header('Location: /search?q=' . urlencode($searchTerm));
        exit();
    }

    // Add these new functions inside your AdminController class, before the final '}'

public function listVideoAds() {
    $adModel = new \App\Models\AdModel(); // We will create this model next
    $ads = $adModel->getAllAds();
    return view('admin/manage-video-ads', [
        'title' => 'Manage Video Ads',
        'ads' => $ads
    ]);
}

public function storeVideoAd() {
    $data = [
        'name' => $_POST['name'] ?? '',
        'type' => $_POST['type'] ?? 'preroll',
        'vast_url' => $_POST['vast_url'] ?? '',
        'offset_time' => ($_POST['type'] === 'midroll') ? ($_POST['offset_time'] ?? null) : null,
    ];

    if (empty($data['name']) || empty($data['vast_url'])) {
        die('Ad Name and VAST URL are required.');
    }

    $adModel = new \App\Models\AdModel();
    $adModel->createAd($data);

    header('Location: /admin/video-ads');
    exit();
}

public function deleteVideoAd($id) {
    $adModel = new \App\Models\AdModel();
    $adModel->deleteAd($id);
    header('Location: /admin/video-ads');
    exit();
}

public function toggleVideoAdStatus($id) {
    $adModel = new \App\Models\AdModel();
    $adModel->toggleStatus($id);
    header('Location: /admin/video-ads');
    exit();
}

    
}