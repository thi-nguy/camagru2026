<?php

class PhotoModel
{
    public function __construct(private PDO $db) {}

    public function getAllPhotos(int $page, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;
        try {
            /* Nice to have: pagination faster with index column, keyset pagination */
            $stmt = $this->db->prepare("SELECT p.filename, p.caption, p.created_at, p.id, p.like_count, p.comment_count, p.user_id, u.username FROM photos p INNER JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC LIMIT :perPage OFFSET :offset;");
            $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $photos = $stmt->fetchAll();
            return $photos;
        } catch (PDOException $e) {
            error_log("DB Error: " . $e->getMessage());
            throw new DatabaseException("Database Error: " . $e->getMessage());
        }
    }

    public function countAllPhotos(): int
    {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM photos;");
            $total = $stmt->fetchColumn();
            return $total;
        } catch (PDOException $e) {
            error_log("DB Error: " . $e->getMessage());
            throw new DatabaseException("Database Error: " . $e->getMessage());
        }
    }
}
