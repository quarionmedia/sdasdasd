<?php require_once __DIR__ . '/partials/header.php'; ?>

<style>
    .report-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    .report-item {
        background-color: #2a2a2a;
        padding: 15px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .report-item img {
        width: 60px;
        height: 90px;
        object-fit: cover;
        border-radius: 4px;
        flex-shrink: 0;
    }
    .report-info {
        flex-grow: 1;
    }
    .report-info h3 {
        margin: 0 0 5px 0;
        font-size: 16px;
    }
    .report-info p {
        margin: 0;
        font-size: 14px;
        color: #ccc;
    }
    .report-info small {
        color: #888;
    }
    .report-actions {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .status-badge {
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: bold;
        text-transform: capitalize;
    }
    .status-pending {
        background-color: #e67e22;
        color: #fff;
    }
    .status-resolved {
        background-color: #27ae60;
        color: #fff;
    }
    .action-link {
        color: #a0aec0;
        text-decoration: none;
    }
    .action-link:hover {
        color: #fff;
    }
</style>

<main>
    <h1>Report Management</h1>
    <p>Review and manage user-submitted reports.</p>
    <hr style="border-color: #374151; margin: 20px 0;">

    <div class="report-list">
        <?php if (!empty($reports)): ?>
            <?php foreach ($reports as $report): ?>
                <div class="report-item">
                    <img src="https://image.tmdb.org/t/p/w200<?php echo $report['content_poster']; ?>" alt="">
                    <div class="report-info">
                        <h3><?php echo htmlspecialchars($report['content_title']); ?> <small>(<?php echo $report['content_type']; ?>)</small></h3>
                        <p><?php echo htmlspecialchars($report['reason']); ?></p>
                        <small>Reported by: <?php echo htmlspecialchars($report['user_email']); ?> on <?php echo date('Y-m-d', strtotime($report['created_at'])); ?></small>
                    </div>
                    <div class="report-actions">
                        <span class="status-badge status-<?php echo $report['status']; ?>"><?php echo $report['status']; ?></span>
                        <?php if ($report['status'] == 'pending'): ?>
                            <a href="/admin/reports/resolve/<?php echo $report['id']; ?>" class="action-link" title="Mark as Resolved">✔</a>
                        <?php endif; ?>
                        <a href="/admin/reports/delete/<?php echo $report['id']; ?>" class="action-link" title="Delete Report" onclick="return confirm('Are you sure?');">🗑️</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No reports found.</p>
        <?php endif; ?>
    </div>
</main>
    
<?php require_once __DIR__ . '/partials/footer.php'; ?>