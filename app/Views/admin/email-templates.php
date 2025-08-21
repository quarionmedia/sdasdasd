<?php require_once __DIR__ . '/partials/header.php'; ?>

<style>
    .tabs { display: flex; border-bottom: 1px solid #444; margin-bottom: 20px; flex-wrap: wrap;}
    .tab-link { padding: 10px 20px; cursor: pointer; border-bottom: 2px solid transparent; }
    .tab-link.active { border-bottom-color: #00aaff; color: #fff; }
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    .template-form .form-group { margin-bottom: 20px; }
    .template-form label { display: block; margin-bottom: 5px; font-weight: bold; }
    .template-form input, .template-form textarea { width: 100%; max-width: 700px; padding: 8px; background-color: #333; border: 1px solid #555; color: #fff; border-radius: 4px; }
    .template-form textarea { min-height: 250px; font-family: monospace; }
    .template-form button { padding: 10px 20px; background-color: #00aaff; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
    .short-codes { background: #2a2a2a; padding: 15px; border-radius: 5px; color: #f39c12; font-family: monospace; font-size: 14px; margin-bottom: 20px; line-height: 1.6; }
</style>

<main>
    <h1>Email Templates</h1>
    <p>Modify the email templates as you need. Use the short codes to insert dynamic content.</p>
    <p><a href="/admin/settings">&larr; Back to Settings</a></p>
    <hr>

    <div class="short-codes">
        <strong>Short Codes:</strong> {{site_name}}, {{user_name}}, {{user_email}}, {{reset_link}}, {{otp_code}}
    </div>

    <div class="tabs">
        <?php foreach ($templates as $index => $template): ?>
            <div class="tab-link <?php echo $index === 0 ? 'active' : ''; ?>" onclick="openTab(event, '<?php echo $template['template_name']; ?>')">
                <?php echo ucwords(str_replace('_', ' ', $template['template_name'])); ?>
            </div>
        <?php endforeach; ?>
    </div>

    <form action="/admin/settings/email-templates" method="POST" class="template-form">
        <?php foreach ($templates as $index => $template): ?>
            <div id="<?php echo $template['template_name']; ?>" class="tab-content <?php echo $index === 0 ? 'active' : ''; ?>">
                <div class="form-group">
                    <label for="subject_<?php echo $template['id']; ?>">Subject</label>
                    <input type="text" id="subject_<?php echo $template['id']; ?>" name="templates[<?php echo $template['id']; ?>][subject]" value="<?php echo htmlspecialchars($template['subject']); ?>">
                </div>
                <div class="form-group">
                    <label for="body_<?php echo $template['id']; ?>">Body (HTML is allowed)</label>
                    <textarea id="body_<?php echo $template['id']; ?>" name="templates[<?php echo $template['id']; ?>][body]"><?php echo htmlspecialchars($template['body']); ?></textarea>
                </div>
            </div>
        <?php endforeach; ?>

        <button type="submit">Save All Templates</button>
    </form>
</main>

<script>
    function openTab(evt, tabName) {
        let i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tab-link");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }
    // Sayfa yüklendiğinde ilk sekmeyi açık tut
    document.addEventListener('DOMContentLoaded', function() {
        if (document.querySelector('.tab-link')) {
            document.querySelector('.tab-link').click();
        }
    });
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>