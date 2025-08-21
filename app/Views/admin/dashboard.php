<?php require_once __DIR__ . '/partials/header.php'; ?>

<style>
    /* Stats Grid Styles */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    .stat-card {
        background-color: #2d3748;
        padding: 25px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 20px;
        color: #fff;
        transition: transform 0.2s, box-shadow 0.2s;
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    .stat-card .icon {
        font-size: 28px;
        padding: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .stat-card .icon.movies { background-color: #3b82f6; }
    .stat-card .icon.unpublished-movies { background-color: #f97316; }
    .stat-card .icon.tv-shows { background-color: #8b5cf6; }
    .stat-card .icon.unpublished-tv-shows { background-color: #ec4899; }

    .stat-card .info h3 {
        margin: 0;
        font-size: 14px;
        color: #a0aec0;
        text-transform: uppercase;
    }
    .stat-card .info p {
        margin: 5px 0 0 0;
        font-size: 28px;
        font-weight: bold;
    }
    .stat-card .info a {
        font-size: 12px;
        color: #a0aec0;
        text-decoration: none;
        position: absolute;
        bottom: 10px;
        right: 15px;
    }

    /* Reports Grid Styles */
    .reports-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }
    .report-card {
        background-color: #2d3748;
        padding: 25px;
        border-radius: 8px;
    }
    .report-card h2 {
        margin-top: 0;
        border-bottom: 1px solid #4a5568;
        padding-bottom: 10px;
        font-size: 18px;
    }
    #userChartContainer {
        height: 250px; /* Chart height */
    }
</style>

<div class="stats-grid">
    <div class="stat-card">
        <div class="icon movies">🎬</div>
        <div class="info">
            <h3>Total Movie</h3>
            <p><?php echo $stats['movies'] ?? 0; ?></p>
            <a href="/admin/movies">View All &rarr;</a>
        </div>
    </div>
    <div class="stat-card">
        <div class="icon unpublished-movies">⚠️</div>
        <div class="info">
            <h3>Unpublished Movie</h3>
            <p>0</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="icon tv-shows">📺</div>
        <div class="info">
            <h3>Total Series</h3>
            <p><?php echo $stats['tv_shows'] ?? 0; ?></p>
            <a href="/admin/tv-shows">View All &rarr;</a>
        </div>
    </div>
    <div class="stat-card">
        <div class="icon unpublished-tv-shows">⚠️</div>
        <div class="info">
            <h3>Unpublished Series</h3>
            <p>0</p>
        </div>
    </div>
</div>

<div class="reports-grid">
    <div class="report-card">
        <h2>User Report</h2>
        <div id="userChartContainer"></div>
    </div>
    <div class="report-card">
        <h2>Cache</h2>
        <p style="text-align:center; font-size: 4em; margin-top: 20px;">☢️</p>
        <p style="text-align:center;">Cache functionality will be added later.</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // User Report Chart
        const userCtx = document.getElementById('userChartContainer').getContext('2d');
        new Chart(userCtx, {
            type: 'doughnut',
            data: {
                labels: ['Registered Users', 'Non-Registered'],
                datasets: [{
                    label: 'Users',
                    data: [<?php echo $stats['users'] ?? 0; ?>, 0], // Non-registered şimdilik 0
                    backgroundColor: [
                        '#3b82f6',
                        '#4a5568'
                    ],
                    borderColor: [
                        '#1f2937'
                    ],
                    borderWidth: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#fff'
                        }
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>