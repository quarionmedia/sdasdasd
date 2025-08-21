<?php

namespace App\Services;

use Config; // We introduce the Config class from the global scope to this file

class TMDbService {
    private $apiKey;
    private $baseUrl = 'https://api.themoviedb.org/3';

    public function __construct() {
        // This line will now work correctly
        $this->apiKey = Config::get('database.services.tmdb.api_key');
    }

    private function findBestLogo($images) {
        $logos = $images['logos'] ?? [];
        $bestLogo = null;
        
        foreach ($logos as $logo) {
            if ($logo['iso_639_1'] === 'en') {
                $bestLogo = $logo;
                break;
            }
        }
        
        if (!$bestLogo && !empty($logos)) {
            $bestLogo = $logos[0];
        }
        
        return $bestLogo['file_path'] ?? null;
    }

    /**
     * Finds the best English (logo/title) backdrop from the images array.
     * @param array $images The 'images' array from the TMDb response.
     * @return string|null The file path of the best backdrop, or null if none found.
     */
    private function findBestLogoBackdrop($images) {
        $backdrops = $images['backdrops'] ?? [];
        $bestBackdrop = null;

        // Prioritize English backdrops
        foreach ($backdrops as $backdrop) {
            if ($backdrop['iso_639_1'] === 'en') {
                $bestBackdrop = $backdrop;
                break;
            }
        }
        
        // If no English backdrop is found, we don't return a fallback,
        // so the main backdrop can be used instead.
        return $bestBackdrop['file_path'] ?? null;
    }

    public function getMovieById($tmdbId) {
        if (empty($this->apiKey)) {
            return ['error' => 'TMDb API key is not set in config file.'];
        }

        $url = "{$this->baseUrl}/movie/{$tmdbId}?api_key={$this->apiKey}&language=en-US&append_to_response=credits,videos,images&include_image_language=en,null";
        
        $response = @file_get_contents($url);

        if ($response === FALSE) {
            return ['error' => 'Failed to fetch data from TMDb. Check TMDb ID.'];
        }

        $details = json_decode($response, true);
        
        if (isset($details['images'])) {
            // Find the best logo
            $details['logo_path'] = $this->findBestLogo($details['images']);
            // YENİ: Find the best backdrop with logo/text
            $details['logo_backdrop_path'] = $this->findBestLogoBackdrop($details['images']);
        }

        return $details;
    }

    public function getTvShowById($tmdbId) {
        if (empty($this->apiKey)) {
            return ['error' => 'TMDb API key is not set in config file.'];
        }

        $url = "{$this->baseUrl}/tv/{$tmdbId}?api_key={$this->apiKey}&language=en-US&append_to_response=credits,videos,images&include_image_language=en,null";
        
        $response = @file_get_contents($url);

        if ($response === FALSE) {
            return ['error' => 'Failed to fetch data from TMDb. Check TMDb ID.'];
        }

        $details = json_decode($response, true);

        if (isset($details['images'])) {
            // Find the best logo
            $details['logo_path'] = $this->findBestLogo($details['images']);
            // YENİ: Find the best backdrop with logo/text
            $details['logo_backdrop_path'] = $this->findBestLogoBackdrop($details['images']);
        }

        return $details;
    }

    public function getSeasonDetails($tvShowId, $seasonNumber) {
        if (empty($this->apiKey)) {
            return ['error' => 'TMDb API key is not set in config file.'];
        }

        $url = "{$this->baseUrl}/tv/{$tvShowId}/season/{$seasonNumber}?api_key={$this->apiKey}&language=en-US";
        
        $response = @file_get_contents($url);

        if ($response === FALSE) {
            return ['error' => 'Failed to fetch season data from TMDb.'];
        }

        return json_decode($response, true);
    }

    /**
 * A helper function to make API requests to TMDb.
 * If you already have a similar private function, you can adapt the methods below to use it.
 */
// Replace your temporary debug version of makeRequest with this final, correct version.
private function makeRequest($endpoint) {
    if (empty($this->apiKey)) {
        // Return null or handle the error appropriately if the API key is missing.
        return null; 
    }
    
    // Construct the base URL
    $url = "{$this->baseUrl}/{$endpoint}?api_key={$this->apiKey}&language=en-US";

    // ==========================================================
    // ADDED FIX: If this is a request for images, add a parameter
    // to include images in English and also those without a language tag.
    // ==========================================================
    if (strpos($endpoint, '/images') !== false) {
        $url .= "&include_image_language=en,null";
    }

    // Make the API call (with '@' to suppress warnings on failure)
    $response = @file_get_contents($url); 
    
    // Decode the JSON response if successful
    return $response ? json_decode($response, true) : null;
}

/**
 * Fetches videos (including trailers) for a specific movie from TMDb.
 *
 * @param int $tmdbId The TMDb ID of the movie.
 * @return array|null The API response or null on error.
 */
public function getMovieVideos($tmdbId) {
    return $this->makeRequest("movie/{$tmdbId}/videos");
}

/**
 * Fetches videos (including trailers) for a specific TV show from TMDb.
 *
 * @param int $tmdbId The TMDb ID of the TV show.
 * @return array|null The API response or null on error.
 */
public function getTvShowVideos($tmdbId) {
    return $this->makeRequest("tv/{$tmdbId}/videos");
}

// Add these two new functions inside your TMDbService class

/**
 * Fetches images (backdrops, posters) for a specific movie from TMDb.
 * @param int $tmdbId The TMDb ID of the movie.
 * @return array|null
 */
public function getMovieImages($tmdbId) {
    return $this->makeRequest("movie/{$tmdbId}/images");
}

/**
 * Fetches images (backdrops, posters) for a specific TV show from TMDb.
 * @param int $tmdbId The TMDb ID of the TV show.
 * @return array|null
 */
public function getTvShowImages($tmdbId) {
    return $this->makeRequest("tv/{$tmdbId}/images");
}
}