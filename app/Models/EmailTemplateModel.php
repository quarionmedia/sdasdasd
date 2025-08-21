<?php

namespace App\Models;

use Database;

class EmailTemplateModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Gets all email templates from the database.
     * @return array
     */
    public function getAllTemplates() {
        try {
            $stmt = $this->db->query("SELECT * FROM email_templates");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            return [];
        }
    }

    /**
     * Gets a single email template by its unique name.
     * @param string $templateName The name of the template (e.g., 'welcome_email').
     * @return array|false The template data or false if not found.
     */
    public function getTemplateByName($templateName) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM email_templates WHERE template_name = ?");
            $stmt->execute([$templateName]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Updates multiple email templates.
     * @param array $templatesData An array where keys are template IDs and values are arrays with subject/body.
     * @return bool True on success.
     */
    public function updateTemplates($templatesData) {
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare("UPDATE email_templates SET subject = ?, body = ? WHERE id = ?");
            
            foreach ($templatesData as $id => $data) {
                if (isset($data['subject']) && isset($data['body'])) {
                    $stmt->execute([
                        $data['subject'],
                        $data['body'],
                        $id
                    ]);
                }
            }
            
            $this->db->commit();
            return true;
        } catch (\PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }
}