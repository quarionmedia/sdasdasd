<?php
namespace App\Controllers;

use App\Models\UserModel;
use App\Models\HomepageModel;
use App\Models\ContentNetworkModel;
use App\Models\MovieModel;
use App\Models\TvShowModel;
use App\Models\SearchModel;
use App\Models\GenreModel;
use App\Services\TMDbService;
use App\Models\SubtitleModel;

class HomeController {
    // Replace the entire index() function in your HomeController.php file
public function index()
{
    $homepageModel = new \App\Models\HomepageModel();
    $sections = $homepageModel->getActiveSections();

    $movieModel = new \App\Models\MovieModel();
    $tvShowModel = new \App\Models\TvShowModel();
    $platformModel = new \App\Models\PlatformModel();
    $genreModel = new \App\Models\GenreModel();
    $searchModel = new \App\Models\SearchModel();
    $seasonModel = new \App\Models\SeasonModel();
    $episodeModel = new \App\Models\EpisodeModel();

    $pageData = [
        'title' => 'Homepage | ' . setting('site_name', 'MuvixTV'),
        'sections' => [],
    ];

    foreach ($sections as $section) {
        $content = [];
        $type = $section['section_type'];

        if ($type === 'slider') {
            $randomMovies = $movieModel->getRandom(5);
            $randomTvShows = $tvShowModel->getRandom(5);
            
            foreach ($randomTvShows as &$item) {
                $firstSeason = $seasonModel->findFirstByTvShowId($item['id']);
                if ($firstSeason) {
                    $firstEpisode = $episodeModel->findFirstBySeasonId($firstSeason['id']);
                    if ($firstEpisode) {
                        $item['watch_url'] = '/tv-show/' . $item['slug'] . '/' . $firstSeason['season_number'] . 'x' . $firstEpisode['episode_number'];
                    }
                }
            }
            unset($item);
            
            $content = array_merge($randomMovies, $randomTvShows);
            shuffle($content);
            
        } elseif ($type === 'latest_tv_shows') {
            $content = $tvShowModel->getLatest(12);
            foreach ($content as &$item) {
                $item['genres'] = $genreModel->findAllByTvShowId($item['id']);
                $item['number_of_seasons'] = $tvShowModel->getSeasonCount($item['id']);
                
                $firstSeason = $seasonModel->findFirstByTvShowId($item['id']);
                if ($firstSeason) {
                    $firstEpisode = $episodeModel->findFirstBySeasonId($firstSeason['id']);
                    if ($firstEpisode) {
                        $item['watch_url'] = '/tv-show/' . $item['slug'] . '/' . $firstSeason['season_number'] . 'x' . $firstEpisode['episode_number'];
                    }
                }
            }
            unset($item);
        
        } elseif ($type === 'all_genres') {
            $allGenres = $genreModel->getAll();
            $genresWithContent = [];
            foreach ($allGenres as $genre) {
                $moviesByGenre = $movieModel->findAllByGenreId($genre['id'], 6);
                $tvShowsByGenre = $tvShowModel->findAllByGenreId($genre['id'], 6);
                
                foreach ($tvShowsByGenre as &$item) {
                    $item['genres'] = $genreModel->findAllByTvShowId($item['id']);
                    $item['number_of_seasons'] = $tvShowModel->getSeasonCount($item['id']);
                    $firstSeason = $seasonModel->findFirstByTvShowId($item['id']);
                    if ($firstSeason) {
                        $firstEpisode = $episodeModel->findFirstBySeasonId($firstSeason['id']);
                        if ($firstEpisode) {
                            $item['watch_url'] = '/tv-show/' . $item['slug'] . '/' . $firstSeason['season_number'] . 'x' . $firstEpisode['episode_number'];
                        }
                    }
                }
                unset($item);

                $mergedContent = array_merge($moviesByGenre, $tvShowsByGenre);
                shuffle($mergedContent);
                if (!empty($mergedContent)) {
                    $genresWithContent[] = [ 'genre_info' => $genre, 'content' => array_slice($mergedContent, 0, 10) ];
                }
            }
            $content = $genresWithContent;
        
        } elseif ($type === 'latest_movies') {
            $content = $movieModel->getLatest(12);
             foreach ($content as &$item) { $item['genres'] = $genreModel->findAllByMovieId($item['id']); }
        } elseif ($type === 'platforms_section') {
            $content = $platformModel->getAllActive();
        } elseif ($type === 'trending') {
            $content = array_merge($movieModel->getMostViewed(10), $tvShowModel->getMostViewed(10));
            usort($content, fn($a, $b) => ($b['view_count'] ?? 0) <=> ($a['view_count'] ?? 0));
            $content = array_slice($content, 0, 10);
        }

        if (!empty($content)) {
             $pageData['sections'][] = [ 'title' => $section['section_title'], 'type' => $type, 'content' => $content ];
        }
    }
    
    
    if(!empty($topSearchesContent)) {
         $pageData['sections'][] = [
            'title' => 'Top Searches',
            'type' => 'top_searches',
            'content' => $topSearchesContent
        ];
    }

    return view('home', $pageData);
}

    // Replace the existing showPlatformPage function in HomeController.php with this
public function showPlatformPage($slug) {
    $platformModel = new \App\Models\PlatformModel();
    $platform = $platformModel->findBySlug($slug);

    if (!$platform) {
        http_response_code(404);
        die('Platform not found.');
    }

    $movieModel = new \App\Models\MovieModel();
    $tvShowModel = new \App\Models\TvShowModel();
    $genreModel = new \App\Models\GenreModel();
    $seasonModel = new \App\Models\SeasonModel();
    $episodeModel = new \App\Models\EpisodeModel();

    $movies = $movieModel->findAllByPlatformId($platform['id']);
    $tvShows = $tvShowModel->findAllByPlatformId($platform['id']);

    foreach ($movies as &$item) {
        $item['genres'] = $genreModel->findAllByMovieId($item['id']);
        $item['content_type'] = 'movie';
    }
    unset($item);

    foreach ($tvShows as &$item) {
        $item['genres'] = $genreModel->findAllByTvShowId($item['id']);
        $item['number_of_seasons'] = $tvShowModel->getSeasonCount($item['id']);
        $item['content_type'] = 'tv_show';
        
        // Create the watch URL for each TV show
        $firstSeason = $seasonModel->findFirstByTvShowId($item['id']);
        if ($firstSeason) {
            $firstEpisode = $episodeModel->findFirstBySeasonId($firstSeason['id']);
            if ($firstEpisode) {
                $item['watch_url'] = '/tv-show/' . $item['slug'] . '/' . $firstSeason['season_number'] . 'x' . $firstEpisode['episode_number'];
            }
        }
    }
    unset($item);
    
    $genres = $genreModel->getAll();
    
    return view('platform-page', [
        'title' => $platform['name'],
        'platform' => $platform,
        'genres' => $genres,
        'movies' => $movies,
        'tvShows' => $tvShows
    ]);
}

    public function showSearchPage() {
        // Arama sayfasında başlangıçta gösterilecek içerikleri çekelim
        $movieModel = new \App\Models\MovieModel();
        $tvShowModel = new \App\Models\TvShowModel();

        $initialMovies = $movieModel->getLatest(12); // Son 12 film
        $initialTvShows = $tvShowModel->getLatest(12); // Son 12 dizi

        // İki listeyi birleştirip karıştıralım
        $initialResults = array_merge($initialMovies, $initialTvShows);
        shuffle($initialResults);

        return view('search', [
            'title' => 'Search',
            'initialResults' => $initialResults // Başlangıç içeriğini view'e gönder
        ]);
    }

    public function search() {
        $query = $_GET['q'] ?? '';

        if (empty($query)) {
            header('Location: /');
            exit();
        }

        $searchModel = new \App\Models\SearchModel();
        $searchModel->recordSearch($query);
        
        $results = $searchModel->findContentByQuery($query);

        return view('search-results', [
            'title' => 'Search results for: ' . htmlspecialchars($query),
            'query' => $query,
            'results' => $results
        ]);
    }

    // Replace the existing liveSearch function in HomeController.php with this
public function liveSearch() {
    $query = $_GET['q'] ?? '';
    $results = [];

    if (!empty($query)) {
        $searchModel = new \App\Models\SearchModel();
        $results = $searchModel->findContentByQuery($query, 8);
        
        // Add watch URLs to the live search results for TV shows
        $seasonModel = new \App\Models\SeasonModel();
        $episodeModel = new \App\Models\EpisodeModel();

        foreach ($results as &$item) {
            $isTvShow = isset($item['first_air_date']) || isset($item['name']);
            if ($isTvShow) {
                $firstSeason = $seasonModel->findFirstByTvShowId($item['id']);
                if ($firstSeason) {
                    $firstEpisode = $episodeModel->findFirstBySeasonId($firstSeason['id']);
                    if ($firstEpisode) {
                        $item['watch_url'] = '/tv-show/' . $item['slug'] . '/' . $firstSeason['season_number'] . 'x' . $firstEpisode['episode_number'];
                    }
                }
            } else {
                 $item['watch_url'] = '/movie/' . ($item['slug'] ?? '') . '/watch';
            }
        }
        unset($item);
    }
    
    header('Content-Type: application/json');
    echo json_encode($results);
    exit();
}
    public function showMoviesPage() {
        $movieModel = new \App\Models\MovieModel();
        $genreModel = new \App\Models\GenreModel(); // YENİ: GenreModel'i ekle
        $movies = $movieModel->getAll();

        // DÜZELTME: Her film için türlerini doğru modelden çekiyoruz.
        foreach ($movies as &$movie) {
            $movie['genres'] = $genreModel->findAllByMovieId($movie['id']);
        }

        return view('list-all-movies', [
            'title' => 'Movies',
            'items' => $movies,
            'pageTitle' => 'All Movies'
        ]);
    }

    // Replace the existing showTvShowsPage function in HomeController.php with this
public function showTvShowsPage() {
    $tvShowModel = new \App\Models\TvShowModel();
    $genreModel = new \App\Models\GenreModel();
    $seasonModel = new \App\Models\SeasonModel();
    $episodeModel = new \App\Models\EpisodeModel();
    
    $tvShows = $tvShowModel->getAll();

    foreach ($tvShows as &$show) {
        $show['genres'] = $genreModel->findAllByTvShowId($show['id']);
        $show['number_of_seasons'] = $tvShowModel->getSeasonCount($show['id']);
        
        // Create the watch URL for each TV show
        $firstSeason = $seasonModel->findFirstByTvShowId($show['id']);
        if ($firstSeason) {
            $firstEpisode = $episodeModel->findFirstBySeasonId($firstSeason['id']);
            if ($firstEpisode) {
                $show['watch_url'] = '/tv-show/' . $show['slug'] . '/' . $firstSeason['season_number'] . 'x' . $firstEpisode['episode_number'];
            }
        }
    }
    unset($show);

    return view('list-all-tv-shows', [
        'title' => 'TV Shows',
        'items' => $tvShows,
        'pageTitle' => 'All TV Shows'
    ]);
}

    public function showMovieDetail($slug) { // Artık $id değil, $slug alıyor
        $movieModel = new \App\Models\MovieModel();
        $movie = $movieModel->findBySlug($slug); // findById yerine findBySlug kullanılıyor

        if (!$movie) {
            http_response_code(404);
            return view('404');
        }

        $genreModel = new \App\Models\GenreModel();
        $personModel = new \App\Models\PersonModel();
        $videoLinkModel = new \App\Models\VideoLinkModel();
        $commentModel = new \App\Models\CommentModel();

        $movie['genres'] = $genreModel->findAllByMovieId($movie['id']);
        $movie['cast'] = $personModel->findCastByMovieId($movie['id']);
        $movie['links'] = $videoLinkModel->findAllByMovieId($movie['id']);
        $userId = $_SESSION['user_id'] ?? null;
        $movie['comments'] = $commentModel->findAllByContentId($movie['id'], 'movie', $userId);
        $movie['media'] = ['posters' => [], 'backdrops' => []]; // This would normally be fetched from TMDb
        $socialLinks = [];
        if (!empty($movie['facebook_id'])) {
        $socialLinks['facebook'] = 'https://www.facebook.com/' . $movie['facebook_id'];
        }
        if (!empty($movie['twitter_id'])) {
        $socialLinks['twitter'] = 'https://www.twitter.com/' . $movie['twitter_id'];
        }
        if (!empty($movie['instagram_id'])) {
        $socialLinks['instagram'] = 'https://www.instagram.com/' . $movie['instagram_id'];
        }
        $movie['social_links'] = $socialLinks;
        $tmdbService = new TMDbService();
        $videos = $tmdbService->getMovieVideos($movie['tmdb_id']);
        // Filter for official Trailers only
        $movie['trailers'] = array_filter($videos['results'] ?? [], function($video) {
        return $video['type'] === 'Trailer' && $video['site'] === 'YouTube';
        });
        $images = $tmdbService->getMovieImages($movie['tmdb_id']);
        $movie['media']['backdrops'] = array_slice($images['backdrops'] ?? [], 0, 10); // Get first 10
        $movie['media']['posters'] = array_slice($images['posters'] ?? [], 0, 10);   // Get first 10
        
        return view('movie-detail', [
            'title' => $movie['title'],
            'movie' => $movie
        ]);
    }

    // Replace the entire showTvShowDetail function in your HomeController.php with this version

public function showTvShowDetail($slug) {
    $tvShowModel = new \App\Models\TvShowModel();
    $tvShow = $tvShowModel->findBySlug($slug);

    if (!$tvShow) {
        return view('404');
    }

    // Load other necessary models
    $genreModel = new \App\Models\GenreModel();
    $personModel = new \App\Models\PersonModel();
    $seasonModel = new \App\Models\SeasonModel();
    $commentModel = new \App\Models\CommentModel();
    $tmdbService = new \App\Services\TMDbService();

    // Fetch related data
    $tvShow['genres'] = $genreModel->findAllByTvShowId($tvShow['id']);
    $tvShow['cast'] = $personModel->findCastByTvShowId($tvShow['id']);
    $tvShow['seasons'] = $seasonModel->findAllByTvShowId($tvShow['id']);
    
    if (!empty($tvShow['seasons'])) {
        foreach ($tvShow['seasons'] as &$season) {
            $season['episodes'] = $seasonModel->findEpisodesBySeasonId($season['id']);
        }
        unset($season);
    }
    
    // --- THIS IS THE CRUCIAL PART THAT MAKES THE BUTTON APPEAR ---
    $firstEpisodeId = null;
    // Find the ID of the first episode of the first season
    if (!empty($tvShow['seasons'][0]['episodes'][0]['id'])) {
        $firstEpisodeId = $tvShow['seasons'][0]['episodes'][0]['id'];
    }
    // --- END OF THE CRUCIAL PART ---

    // Fetch comments
    $userId = $_SESSION['user_id'] ?? null;
    $tvShow['comments'] = $commentModel->findAllByContentId($tvShow['id'], 'tv_show', $userId);
    
    // Fetch media (trailers and images)
    $videos = $tmdbService->getTvShowVideos($tvShow['tmdb_id']);
    $tvShow['trailers'] = array_filter($videos['results'] ?? [], function($video) {
        return $video['type'] === 'Trailer' && $video['site'] === 'YouTube';
    });
    $images = $tmdbService->getTvShowImages($tvShow['tmdb_id']);
    $tvShow['media']['backdrops'] = array_slice($images['backdrops'] ?? [], 0, 10);
    $tvShow['media']['posters'] = array_slice($images['posters'] ?? [], 0, 10);

    return view('tv-show-detail', [
        'title' => $tvShow['title'],
        'tvShow' => $tvShow,
        'firstEpisodeId' => $firstEpisodeId // This line sends the ID to the view
    ]);
}

// Replace the existing storeComment() function in your HomeController.php file

public function storeComment() {
    // Ensure session is started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // User must be logged in to comment
    if (!isset($_SESSION['user_id'])) {
        die('You must be logged in to comment.');
    }

    $data = [
        'user_id'      => $_SESSION['user_id'],
        'content_id'   => $_POST['content_id'] ?? 0,
        'content_type' => $_POST['content_type'] ?? '',
        'rating'       => $_POST['rating'] ?? 0,
        'comment'      => trim($_POST['comment'] ?? ''),
        'is_spoiler'   => isset($_POST['is_spoiler']) ? 1 : 0,
        'parent_id'    => !empty($_POST['parent_id']) ? $_POST['parent_id'] : null
    ];

    // --- NEW, CORRECTED VALIDATION LOGIC ---
    
    // The comment text is always required.
    if (empty($data['comment'])) {
        die('Comment field cannot be empty.');
    }

    // The rating is ONLY required for top-level comments (when parent_id is null).
    // Replies do not require a rating.
    if (is_null($data['parent_id']) && empty($data['rating'])) {
        die('Rating is required for a new review.');
    }
    // --- END OF NEW VALIDATION ---


    $commentModel = new \App\Models\CommentModel();
    $success = $commentModel->create($data);

    // Redirect back to the previous page
    if ($success) {
        // Adding a fragment identifier to jump back to the comments section
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '#comments-section');
        exit();
    } else {
        die('An error occurred while posting your comment.');
    }
}

public function toggleCommentLike() {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    
    if (!isset($_SESSION['user_id'])) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Login required.']);
        exit();
    }
    
    $commentId = $_POST['comment_id'] ?? null;
    $userId = $_SESSION['user_id'];

    if (!$commentId) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Comment ID is missing.']);
        exit();
    }

    $commentModel = new \App\Models\CommentModel();
    $result = $commentModel->toggleLike($userId, $commentId);

    header('Content-Type: application/json');
    echo json_encode($result);
    exit();
}

// Replace the existing showMovieWatchPage function in HomeController.php
public function showMovieWatchPage($slug) {
    $movieModel = new \App\Models\MovieModel();
    $movie = $movieModel->findBySlug($slug);

    if (!$movie) { return view('404'); }

    $videoLinkModel = new \App\Models\VideoLinkModel();
    $subtitleModel = new \App\Models\SubtitleModel();
    $settingModel = new \App\Models\SettingModel(); // ADD THIS

    $movie['links'] = $videoLinkModel->findAllByMovieId($movie['id']);
    
    foreach ($movie['links'] as &$link) {
        $link['subtitles'] = $subtitleModel->findAllByVideoLinkId($link['id']);
    }
    unset($link);
    
    // ADD THIS to fetch ad settings
    $ads = $settingModel->getAdsSettings();
    
    return view('watch-player', [
        'content' => $movie,
        'type' => 'movie',
        'ads' => $ads // Pass ad settings to the view
    ]);
}

// Replace the existing showEpisodeWatchPageByNumber function in HomeController.php
public function showEpisodeWatchPageByNumber($slug, $seasonEpisodeString) {
    list($seasonNumber, $episodeNumber) = explode('x', $seasonEpisodeString);
    if (!is_numeric($seasonNumber) || !is_numeric($episodeNumber)) { return view('404'); }

    $episodeModel = new \App\Models\EpisodeModel();
    $episode = $episodeModel->findByShowSlugAndNumbers($slug, $seasonNumber, $episodeNumber);

    if (!$episode) { return view('404'); }

    $seasonModel = new \App\Models\SeasonModel();
    $tvShowModel = new \App\Models\TvShowModel();
    $videoLinkModel = new \App\Models\VideoLinkModel();
    $subtitleModel = new \App\Models\SubtitleModel();
    $settingModel = new \App\Models\SettingModel();
    
    $season = $seasonModel->findById($episode['season_id']);
    $tvShow = $tvShowModel->findById($season['tv_show_id']);
    $episode['tv_show'] = $tvShow;
    
    $episode['links'] = $videoLinkModel->findAllByEpisodeId($episode['id']);
    foreach ($episode['links'] as &$link) {
        $link['subtitles'] = $subtitleModel->findAllByVideoLinkId($link['id']);
    }
    unset($link);

    $ads = $settingModel->getAdsSettings();
    $nextEpisode = $episodeModel->findNextEpisode($episode['season_id'], $episode['episode_number']);
    $nextEpisodeUrl = null;
    if ($nextEpisode) {
        $nextEpisodeUrl = '/tv-show/' . $slug . '/' . $season['season_number'] . 'x' . $nextEpisode['episode_number'];
    }

    // --- NEW LOGIC: Fetch all seasons and episodes for the popup menu ---
    $allSeasons = $seasonModel->findAllByTvShowId($tvShow['id']);
    foreach ($allSeasons as &$s) {
        $s['episodes'] = $episodeModel->findAllBySeasonId($s['id']);
    }
    unset($s);
    // --- END OF NEW LOGIC ---
    
    return view('watch-player', [
        'content' => $episode,
        'type' => 'episode',
        'ads' => $ads,
        'nextEpisodeUrl' => $nextEpisodeUrl,
        'allSeasons' => $allSeasons // Pass all season data to the view
    ]);
}
    
    
}