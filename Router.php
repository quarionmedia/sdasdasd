<?php
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;
class Router {
    public static function route($url) {
        $urlParts = explode('/', $url);

        // Home Page Route
        if ($url == '') {
            $controller = new HomeController();
            $controller->index();
        }

        // Platform sayfası rotası: /platforms/{slug}
        elseif ($urlParts[0] == 'platforms' && isset($urlParts[1])) {
            $slug = $urlParts[1];
            $controller = new HomeController();
            $controller->showPlatformPage($slug);
        }
        
        // Register Route
        elseif ($url == 'register') {
            $controller = new AuthController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->register();
            } else {
                $controller->showRegisterForm();
            }
        } 
        // Login Route
        elseif ($url == 'login') {
            $controller = new AuthController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->login();
            } else {
                $controller->showLoginForm();
            }
        }
        // Logout Route
        elseif ($url == 'logout') {
            $controller = new AuthController();
            $controller->logout();
        }
        // Admin Routes
        elseif ($urlParts[0] == 'admin') {
            
            // /admin
            if (!isset($urlParts[1])) {
                $controller = new AdminController();
                $controller->index();
            } 
            // /admin/movies/...
            elseif ($urlParts[1] == 'movies') {
                if (!isset($urlParts[2])) {
                    $controller = new AdminController();
                    $controller->listMovies();
                } 
                elseif ($urlParts[2] == 'add') {
                    $controller = new AdminController();
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->storeMovie();
                    } else {
                        $controller->showAddMovieForm();
                    }
                }
                elseif ($urlParts[2] == 'tmdb-import') {
                    $controller = new AdminController();
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->importFromTMDb();
                    }
                }
                elseif ($urlParts[2] == 'delete' && isset($urlParts[3])) {
                    $id = (int)$urlParts[3];
                    $controller = new AdminController();
                    $controller->deleteMovie($id);
                }
                elseif ($urlParts[2] == 'edit' && isset($urlParts[3])) {
                    $id = (int)$urlParts[3];
                    $controller = new AdminController();
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->updateMovie($id);
                    } else {
                        $controller->showEditMovieForm($id);
                    }
                }
            }
            // /admin/tv-shows/...
            elseif ($urlParts[1] == 'tv-shows') {
                if (!isset($urlParts[2])) {
                    $controller = new AdminController();
                    $controller->listTvShows();
                }
                elseif ($urlParts[2] == 'add') {
                    $controller = new AdminController();
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->storeTvShow();
                    } else {
                        $controller->showAddTvShowForm();
                    }
                }
                elseif ($urlParts[2] == 'tmdb-import') {
                    $controller = new AdminController();
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->importTvShowFromTMDb();
                    }
                }
                elseif ($urlParts[2] == 'import-seasons' && isset($urlParts[3]) && isset($urlParts[4])) {
                    $tvShowDbId = (int)$urlParts[3];
                    $tvShowTmdbId = (int)$urlParts[4];
                    $controller = new AdminController();
                    $controller->importSeasons($tvShowDbId, $tvShowTmdbId);
                }
                elseif ($urlParts[2] == 'edit' && isset($urlParts[3])) {
                    $id = (int)$urlParts[3];
                    $controller = new AdminController();
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->updateTvShow($id);
                    } else {
                        $controller->showEditTvShowForm($id);
                    }
                }
                elseif ($urlParts[2] == 'delete' && isset($urlParts[3])) {
                    $id = (int)$urlParts[3];
                    $controller = new AdminController();
                    $controller->deleteTvShow($id);
                }
            }

            // /admin/users/...
            elseif ($urlParts[1] == 'users') {
                $controller = new \App\Controllers\AdminController();

                // Edit and Update: /admin/users/edit/{id}
                if (isset($urlParts[2]) && $urlParts[2] == 'edit' && isset($urlParts[3])) {
                    $id = (int)$urlParts[3];
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->updateUser($id);
                    } else {
                        $controller->showEditUserForm($id);
                    }
                }
                // Delete: /admin/users/delete/{id}
                elseif (isset($urlParts[2]) && $urlParts[2] == 'delete' && isset($urlParts[3])) {
                    $id = (int)$urlParts[3];
                    $controller->deleteUser($id);
                }
                // List: /admin/users
                else {
                    $controller->listUsers();
                }
            }

            // /admin/manage-seasons/{id}
            // /admin/manage-seasons/...
            // /admin/manage-seasons/...
            // /admin/manage-seasons/...
            elseif ($urlParts[1] == 'manage-seasons') {
                $controller = new \App\Controllers\AdminController();

                // Düzenleme rotası: GET|POST /admin/manage-seasons/edit/{seasonId}
                if (isset($urlParts[2]) && $urlParts[2] == 'edit' && isset($urlParts[3])) {
                    $seasonId = (int)$urlParts[3];
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->updateSeason($seasonId);
                    } else {
                        $controller->showEditSeasonForm($seasonId);
                    }
                }
                // Silme rotası: GET /admin/manage-seasons/delete/{seasonId}
                elseif (isset($urlParts[2]) && $urlParts[2] == 'delete' && isset($urlParts[3])) {
                    $seasonId = (int)$urlParts[3];
                    $controller->deleteSeason($seasonId);
                }
                // Manuel sezon ekleme rotası: POST /admin/manage-seasons/add/{tvShowId}
                elseif (isset($urlParts[2]) && $urlParts[2] == 'add' && isset($urlParts[3])) {
                    $tvShowId = (int)$urlParts[3];
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->storeManualSeason($tvShowId);
                    }
                }
                // Sezon listeleme sayfası: GET /admin/manage-seasons/{tvShowId}
                elseif (isset($urlParts[2])) {
                    $id = (int)$urlParts[2];
                    $controller->manageSeasons($id);
                }
            }

            // /admin/manage-episodes/{seasonId}
            // /admin/manage-episodes/...
            elseif ($urlParts[1] == 'manage-episodes') {
                $controller = new \App\Controllers\AdminController();

                // Düzenleme rotası: POST /admin/manage-episodes/edit/{episodeId}
                if (isset($urlParts[2]) && $urlParts[2] == 'edit' && isset($urlParts[3])) {
                    $episodeId = (int)$urlParts[3];
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->updateEpisodeDetails($episodeId);
                    }
                }
                // Silme rotası: GET /admin/manage-episodes/delete/{episodeId}
                elseif (isset($urlParts[2]) && $urlParts[2] == 'delete' && isset($urlParts[3])) {
                    $episodeId = (int)$urlParts[3];
                    $controller->deleteEpisode($episodeId);
                }
                // Manuel bölüm ekleme rotası: POST /admin/manage-episodes/add/{seasonId}
                elseif (isset($urlParts[2]) && $urlParts[2] == 'add' && isset($urlParts[3])) {
                    $seasonId = (int)$urlParts[3];
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->storeManualEpisode($seasonId);
                    }
                }
                // Bölümleri çekme rotası: GET /admin/manage-episodes/fetch/{seasonId}
                elseif (isset($urlParts[2]) && $urlParts[2] == 'fetch' && isset($urlParts[3])) {
                    $seasonId = (int)$urlParts[3];
                    $controller->fetchEpisodesForSeason($seasonId);
                }
                // Bölüm listeleme sayfası: GET /admin/manage-episodes/{seasonId}
                elseif (isset($urlParts[2])) {
                    $seasonId = (int)$urlParts[2];
                    $controller->manageEpisodes($seasonId);
                }
            }

            // /admin/manage-episode-links/...
            elseif ($urlParts[1] == 'manage-episode-links') {
                $controller = new \App\Controllers\AdminController();

                // Silme rotası: /admin/manage-episode-links/delete/{linkId}/{episodeId}
                if (isset($urlParts[2]) && $urlParts[2] == 'delete' && isset($urlParts[3]) && isset($urlParts[4])) {
                    $linkId = (int)$urlParts[3];
                    $episodeId = (int)$urlParts[4];
                    $controller->deleteEpisodeLink($linkId, $episodeId);
                }
                // Listeleme ve Ekleme rotası: /admin/manage-episode-links/{episodeId}
                elseif (isset($urlParts[2])) {
                    $episodeId = (int)$urlParts[2];
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->storeEpisodeLink($episodeId);
                    } else {
                        $controller->showEpisodeLinksManager($episodeId);
                    }
                }
            }

            // /admin/manage_subtitles/... (Unified for both movies and episodes)
            elseif ($urlParts[1] == 'manage_subtitles') {
                $controller = new \App\Controllers\AdminController();

                // Delete route: /admin/manage_subtitles/delete/{subtitleId}/{linkId}
                if (isset($urlParts[2]) && $urlParts[2] == 'delete' && isset($urlParts[3]) && isset($urlParts[4])) {
                    $subtitleId = (int)$urlParts[3];
                    $linkId = (int)$urlParts[4];
                    $controller->deleteSubtitle($subtitleId, $linkId);
                }
                // List and Add routes: /admin/manage_subtitles/{linkId}
                elseif (isset($urlParts[2])) {
                    $linkId = (int)$urlParts[2];
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->storeSubtitle($linkId);
                    } else {
                        $controller->showSubtitleManager($linkId);
                    }
                }
            }
            
             // /admin/episodes/...
             elseif ($urlParts[1] == 'episodes') {
                if ($urlParts[2] == 'edit' && isset($urlParts[3])) {
                    $id = (int)$urlParts[3];
                    $controller = new AdminController();
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->updateEpisode($id);
                    } else {
                        $controller->showEditEpisodeForm($id);
                    }
                }
            }

            // /admin/manage-movie-links/...
            elseif ($urlParts[1] == 'manage-movie-links') {
                $controller = new \App\Controllers\AdminController();

                // Edit rotası: /admin/manage-movie-links/edit/{linkId}
                if (isset($urlParts[2]) && $urlParts[2] == 'edit' && isset($urlParts[3])) {
                    $linkId = (int)$urlParts[3];
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->updateMovieLink($linkId);
                    } else {
                        $controller->showEditMovieLinkForm($linkId);
                    }
                }
                // Silme rotası: /admin/manage-movie-links/delete/{linkId}/{movieId}
                elseif (isset($urlParts[2]) && $urlParts[2] == 'delete' && isset($urlParts[3]) && isset($urlParts[4])) {
                    $linkId = (int)$urlParts[3];
                    $movieId = (int)$urlParts[4];
                    $controller->deleteMovieLink($linkId, $movieId);
                }
                // Listeleme ve Ekleme rotası: /admin/manage-movie-links/{movieId}
                elseif (isset($urlParts[2])) {
                    $movieId = (int)$urlParts[2];
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->storeMovieLink($movieId);
                    } else {
                        $controller->showMovieLinksManager($movieId);
                    }
                }
            }
// --- START: Replace the old /admin/menu block with this combined block ---

// Handles /admin/menu AND /admin/video-ads
elseif ($urlParts[0] == 'admin' && isset($urlParts[1])) {
    
    $controller = new AdminController();

    // /admin/menu routing logic
    if ($urlParts[1] == 'menu') {
        if (isset($urlParts[2])) {
            if ($urlParts[2] == 'add') {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->storeMenuItem();
                } else {
                    $controller->showAddMenuItemForm();
                }
            }
            elseif ($urlParts[2] == 'delete' && isset($urlParts[3])) {
                $controller->deleteMenuItem((int)$urlParts[3]);
            }
            elseif ($urlParts[2] == 'edit' && isset($urlParts[3])) {
                $id = (int)$urlParts[3];
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->updateMenuItem($id);
                } else {
                    $controller->showEditMenuItemForm($id);
                }
            }
        } else {
            $controller->listMenuItems();
        }
    }
    // /admin/video-ads routing logic
    elseif ($urlParts[1] == 'video-ads') {
        if (isset($urlParts[2]) && $urlParts[2] == 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->storeVideoAd();
        }
        elseif (isset($urlParts[2]) && $urlParts[2] == 'delete' && isset($urlParts[3])) {
            $controller->deleteVideoAd((int)$urlParts[3]);
        }
        elseif (isset($urlParts[2]) && $urlParts[2] == 'toggle' && isset($urlParts[3])) {
            $controller->toggleVideoAdStatus((int)$urlParts[3]);
        }
        else {
            $controller->listVideoAds();
        }
    }
    // You can add other /admin/... routes here as 'elseif ($urlParts[1] == '...'))'
    
}

// --- END: Combined block ---

                 // /admin/users/...
              elseif ($urlParts[1] == 'users') {
              $controller = new \App\Controllers\AdminController();

              // Edit and Update: /admin/users/edit/{id}
              if (isset($urlParts[2]) && $urlParts[2] == 'edit' && isset($urlParts[3])) {
                $id = (int)$urlParts[3];
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->updateUser($id);
                               } else {
                             $controller->showEditUserForm($id);
                         }

                          // Delete: /admin/users/delete/{id}
                         } elseif (isset($urlParts[2]) && $urlParts[2] == 'delete' && isset($urlParts[3])) {
                        $id = (int)$urlParts[3];
                          $controller->deleteUser($id);

                             // List: /admin/users
                      } else {
                              $controller->listUsers();
                      }
                    }

                    // /admin/comments/...
            elseif ($urlParts[1] == 'comments') {
                $controller = new \App\Controllers\AdminController();

                // Approve: /admin/comments/approve/{id}
                if (isset($urlParts[2]) && $urlParts[2] == 'approve' && isset($urlParts[3])) {
                    $controller->approveComment((int)$urlParts[3]);
                }
                // Unapprove: /admin/comments/unapprove/{id}
                elseif (isset($urlParts[2]) && $urlParts[2] == 'unapprove' && isset($urlParts[3])) {
                    $controller->unapproveComment((int)$urlParts[3]);
                }
                // Delete: /admin/comments/delete/{id}
                elseif (isset($urlParts[2]) && $urlParts[2] == 'delete' && isset($urlParts[3])) {
                    $controller->deleteComment((int)$urlParts[3]);
                }
                // List: /admin/comments
                else {
                    $controller->listComments();
                }
            }

            // /admin/reports/...
            elseif ($urlParts[1] == 'reports') {
                $controller = new \App\Controllers\AdminController();

                // Mark as resolved: /admin/reports/resolve/{id}
                if (isset($urlParts[2]) && $urlParts[2] == 'resolve' && isset($urlParts[3])) {
                    $controller->resolveReport((int)$urlParts[3]);
                }
                // Delete: /admin/reports/delete/{id}
                elseif (isset($urlParts[2]) && $urlParts[2] == 'delete' && isset($urlParts[3])) {
                    $controller->deleteReport((int)$urlParts[3]);
                }
                // List: /admin/reports
                else {
                    $controller->listReports();
                }
            }

            // /admin/requests/...
            elseif ($urlParts[1] == 'requests') {
                $controller = new \App\Controllers\AdminController();

                // Durumu güncelleme rotası: /admin/requests/update-status/{id}/{status}
                if (isset($urlParts[2]) && $urlParts[2] == 'update-status' && isset($urlParts[3]) && isset($urlParts[4])) {
                    $requestId = (int)$urlParts[3];
                    $status = $urlParts[4]; // status bir string olduğu için int'e çevirmiyoruz
                    $controller->updateRequestStatus($requestId, $status);
                }
                // Silme rotası: /admin/requests/delete/{id}
                elseif (isset($urlParts[2]) && $urlParts[2] == 'delete' && isset($urlParts[3])) {
                    $controller->deleteRequest((int)$urlParts[3]);
                }
                // Listeleme rotası: /admin/requests
                else {
                    $controller->listRequests();
                }
            }
            
                     // /admin/settings/...
            
            elseif ($urlParts[1] == 'settings') {
                $controller = new AdminController();
                
                $page = $urlParts[2] ?? 'general'; // Varsayılan sayfa 'general' olsun

                if ($page == 'general') {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->updateGeneralSettings();
                    } else {
                        $controller->showGeneralSettingsForm();
                    }
                }
                elseif ($page == 'api') {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->updateApiSettings();
                    } else {
                        $controller->showApiSettingsForm();
                    }
                }
                elseif ($page == 'security') {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->updateSecuritySettings();
                    } else {
                        $controller->showSecuritySettingsForm();
                    }
                }
                // Eski rotaları koruyalım
                elseif ($page == 'test-mail') {
                    $controller->sendTestEmail();
                }
                elseif ($page == 'email-templates') {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->updateEmailTemplates();
                    } else {
                        $controller->showEmailTemplatesForm();
                    }
                }
            }

            // /admin/ads-settings
            elseif ($urlParts[1] == 'ads-settings') {
                $controller = new \App\Controllers\AdminController();
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->updateAdsSettings();
                } else {
                    $controller->showAdsSettingsForm();
                }
            }

            // /admin/content-networks
            elseif ($urlParts[1] == 'content-networks') {
                $controller = new \App\Controllers\AdminController();
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->updateContentNetworks();
                } else {
                    $controller->showContentNetworksForm();
                }
            }

            // /admin/platforms/...
            elseif ($urlParts[1] == 'platforms') {
                $controller = new \App\Controllers\AdminController();

                // Edit and Update: /admin/platforms/edit/{id}
                if (isset($urlParts[2]) && $urlParts[2] === 'edit' && isset($urlParts[3])) {
                    $id = (int)$urlParts[3];
                    // This route will handle the POST request from the edit modal
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->updatePlatform($id);
                    }
                }
                // Delete: /admin/platforms/delete/{id}
                elseif (isset($urlParts[2]) && $urlParts[2] === 'delete' && isset($urlParts[3])) {
                    $controller->deletePlatform((int)$urlParts[3]);
                } 
                // List & Store routes are combined on the base URL
                else {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->storePlatform();
                    } else {
                        $controller->listPlatforms();
                    }
                }
            }

            // /admin/genres/...
            elseif ($urlParts[1] == 'genres') {
                $controller = new \App\Controllers\AdminController();

                // Add Genre: /admin/genres/add
                if (isset($urlParts[2]) && $urlParts[2] == 'add') {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->storeGenre();
                    } else {
                        $controller->showAddGenreForm();
                    }
                }
                // Edit and Update Genre: /admin/genres/edit/{id}
                elseif (isset($urlParts[2]) && $urlParts[2] == 'edit' && isset($urlParts[3])) {
                    $id = (int)$urlParts[3];
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->updateGenre($id);
                    } else {
                        $controller->showEditGenreForm($id);
                    }
                }
                // Delete Genre: /admin/genres/delete/{id}
                elseif (isset($urlParts[2]) && $urlParts[2] == 'delete' && isset($urlParts[3])) {
                    $id = (int)$urlParts[3];
                    $controller->deleteGenre($id);
                }
                // List Genres: /admin/genres
                else {
                    $controller->listGenres();
                }
            }

            // /admin/smtp-settings
            elseif ($urlParts[1] == 'smtp-settings') {
                $controller = new AdminController();
                if (isset($urlParts[2]) && $urlParts[2] == 'test-mail') {
                    $controller->sendTestEmail();
                } else {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->updateSmtpSettings();
                    } else {
                        $controller->showSmtpSettingsForm();
                    }
                }
            }
            
        }

        // Arama Rotası
        elseif ($urlParts[0] == 'search') {
            $controller = new \App\Controllers\HomeController();
            if (isset($_GET['q'])) {
                $controller->search();
            } else {
                $controller->showSearchPage();
            }
        }

        // Canlı Arama API Rotası: /api/search?q=...
        elseif ($urlParts[0] == 'api' && isset($urlParts[1]) && $urlParts[1] == 'search') {
            $controller = new \App\Controllers\HomeController();
            $controller->liveSearch();
        }

        // Add this route to your Router file
        elseif ($urlParts[0] == 'comments' && isset($urlParts[1]) && $urlParts[1] == 'store') {
            $controller = new \App\Controllers\HomeController();
            $controller->storeComment();
        }
        // Add this route for the AJAX like functionality
        elseif ($urlParts[0] == 'api' && isset($urlParts[1]) && $urlParts[1] == 'comments' && isset($urlParts[2]) && $urlParts[2] == 'toggle-like') {
            $controller = new \App\Controllers\HomeController();
            $controller->toggleCommentLike();
        }

// =======================================================
// MOVIE ROUTES (Most specific to most general)
// =======================================================

// 1. Movie Watch Page (MOST SPECIFIC)
elseif ($urlParts[0] == 'movie' && isset($urlParts[1]) && isset($urlParts[2]) && $urlParts[2] == 'watch') {
    $slug = $urlParts[1];
    $controller = new \App\Controllers\HomeController();
    $controller->showMovieWatchPage($slug);
}

// 2. Movie Detail Page (More general)
elseif ($urlParts[0] == 'movie' && isset($urlParts[1])) {
    $slug = $urlParts[1];
    $controller = new \App\Controllers\HomeController();
    $controller->showMovieDetail($slug);
}

// 3. All Movies List Page (MOST GENERAL)
elseif ($urlParts[0] == 'movies') {
    $controller = new \App\Controllers\HomeController();
    $controller->showMoviesPage();
}


// =======================================================
// TV SHOW ROUTES (Most specific to most general)
// =======================================================

// 1. TV Show Episode Watch Page (MOST SPECIFIC)
elseif ($urlParts[0] == 'tv-show' && isset($urlParts[1]) && isset($urlParts[2]) && strpos($urlParts[2], 'x') !== false) {
    $slug = $urlParts[1];
    $seasonEpisode = $urlParts[2];
    $controller = new \App\Controllers\HomeController();
    $controller->showEpisodeWatchPageByNumber($slug, $seasonEpisode);
}

// 2. TV Show Detail Page (More general)
elseif ($urlParts[0] == 'tv-show' && isset($urlParts[1])) {
    $slug = $urlParts[1];
    $controller = new \App\Controllers\HomeController();
    $controller->showTvShowDetail($slug);
}

// 3. All TV Shows List Page (MOST GENERAL)
elseif ($urlParts[0] == 'tv-shows') {
    $controller = new \App\Controllers\HomeController();
    $controller->showTvShowsPage();
}

// ... (Your other routes like /404 else block go here) ...
        // 404 Not Found
        else {
    // Set the HTTP response code to 404 Not Found
    http_response_code(404);
    
    // Call your helper function to render the 404 view
    // Your HomeController already uses this, so it should work.
    view('404');
}
    }
}