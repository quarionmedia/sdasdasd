<?php require_once __DIR__ . '/partials/header.php'; ?>

<style>
    /* Diğer formlarla aynı stilleri kullanıyoruz */
    .settings-form .form-group { margin-bottom: 20px; }
    .settings-form label { display: block; margin-bottom: 5px; font-weight: bold; }
    .settings-form input, .settings-form select { width: 100%; max-width: 500px; padding: 8px; background-color: #333; border: 1px solid #555; color: #fff; border-radius: 4px; }
    .settings-form button { padding: 10px 20px; background-color: #00aaff; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
    .flash-message { background-color: #334; padding: 10px; margin-bottom: 15px; border-left: 3px solid #88f; border-radius: 4px; }
    .test-email-btn { display: inline-block; background: #007700; color: #fff; padding: 8px 15px; text-decoration: none; border-radius: 4px; margin-bottom: 20px; }
</style>

<main>
    <h1>SMTP Settings</h1>
    <p>Configure your site's email sending settings.</p>
    
    <?php if ($flashMessage): ?>
        <div class="flash-message"><?php echo htmlspecialchars($flashMessage); ?></div>
    <?php endif; ?>

    <a href="/admin/smtp-settings/test-mail" onclick="return confirm('This will send a test email to your logged-in admin email address. Continue?');" class="test-email-btn">Send Test Email</a>
    
    <hr>

    <form action="/admin/smtp-settings" method="POST" class="settings-form">
        <div class="form-group">
            <label for="site_email">Site "From" Email</label>
            <input type="text" id="site_email" name="site_email" placeholder="e.g., noreply@yourdomain.com" value="<?php echo htmlspecialchars($settings['site_email'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="smtp_host">SMTP Host</label>
            <input type="text" id="smtp_host" name="smtp_host" value="<?php echo htmlspecialchars($settings['smtp_host'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="smtp_port">SMTP Port</label>
            <input type="text" id="smtp_port" name="smtp_port" value="<?php echo htmlspecialchars($settings['smtp_port'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="smtp_secure">SMTP Security</label>
             <select name="smtp_secure" id="smtp_secure">
                <option value="tls" <?php echo ($settings['smtp_secure'] ?? '') == 'tls' ? 'selected' : ''; ?>>TLS</option>
                <option value="ssl" <?php echo ($settings['smtp_secure'] ?? '') == 'ssl' ? 'selected' : ''; ?>>SSL</option>
                <option value="" <?php echo ($settings['smtp_secure'] ?? '') == '' ? 'selected' : ''; ?>>None</option>
            </select>
        </div>
        <div class="form-group">
            <label for="smtp_user">SMTP User</label>
            <input type="text" id="smtp_user" name="smtp_user" value="<?php echo htmlspecialchars($settings['smtp_user'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="smtp_pass">SMTP Password</label>
            <input type="password" id="smtp_pass" name="smtp_pass" placeholder="Enter new password to update">
            <small style="color: #888;">Leave blank to keep the current password.</small>
        </div>
        
        <button type="submit">Save SMTP Settings</button>
    </form>
</main>

<?php require_once __DIR__ . '/partials/footer.php'; ?>