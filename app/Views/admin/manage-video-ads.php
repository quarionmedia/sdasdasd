<?php require_once __DIR__ . '/partials/header.php'; ?>

<main>
    <h1>Manage Video Ads (VAST)</h1>
    <p>Manage Pre-roll, Mid-roll, and Post-roll video ads for the player.</p>

    <div class="main-content">
        <div class="form-container" style="margin-bottom: 30px;">
            <h2>Add New VAST Ad</h2>
            <form action="/admin/video-ads/add" method="POST">
                <div class="form-group">
                    <label for="name">Ad Name (e.g., Main Preroll):</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="type">Ad Type:</label>
                    <select id="type" name="type">
                        <option value="preroll">Pre-roll (Before Video)</option>
                        <option value="midroll">Mid-roll (During Video)</option>
                        <option value="postroll">Post-roll (After Video)</option>
                        <option value="pauseroll">Pause-roll (On Pause)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="vast_url">VAST Tag URL:</label>
                    <textarea id="vast_url" name="vast_url" rows="3" required placeholder="Enter your VAST .xml URL here"></textarea>
                </div>
                <div class="form-group" id="offset-group" style="display:none;">
                    <label for="offset_time">Offset (for Mid-roll only):</label>
                    <input type="text" id="offset_time" name="offset_time" placeholder="e.g., 600 (for seconds) or 25%">
                    <small>Enter time in seconds (e.g., 300) or as a percentage (e.g., 50%).</small>
                </div>
                <button type="submit" class="button">Add Ad</button>
            </form>
        </div>

        <div class="table-container">
            <h2>Active Video Ads</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>VAST URL</th>
                        <th>Offset</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($ads)): ?>
                        <?php foreach ($ads as $ad): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($ad['name']); ?></td>
                                <td><?php echo ucfirst($ad['type']); ?></td>
                                <td><small><?php echo htmlspecialchars($ad['vast_url']); ?></small></td>
                                <td><?php echo htmlspecialchars($ad['offset_time'] ?? 'N/A'); ?></td>
                                <td><?php echo $ad['is_active'] ? 'Active' : 'Inactive'; ?></td>
                                <td>
                                    <a href="/admin/video-ads/toggle/<?php echo $ad['id']; ?>" class="button button-secondary">
                                        <?php echo $ad['is_active'] ? 'Deactivate' : 'Activate'; ?>
                                    </a>
                                    <a href="/admin/video-ads/delete/<?php echo $ad['id']; ?>" class="button button-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No video ads found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script>
    // Show offset field only when 'midroll' is selected
    document.getElementById('type').addEventListener('change', function() {
        const offsetGroup = document.getElementById('offset-group');
        if (this.value === 'midroll') {
            offsetGroup.style.display = 'block';
        } else {
            offsetGroup.style.display = 'none';
        }
    });
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>